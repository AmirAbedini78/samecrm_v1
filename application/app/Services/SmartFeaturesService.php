<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\Sales;
use App\Models\InventoryEntry;
use App\Models\BelzonaInventory;
use App\Models\InvoiceSettlement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * ML-style analytics: demand forecast, smart alerts, clustering, anomaly.
 * All logic in PHP so it works without Python.
 */
class SmartFeaturesService
{
    /**
     * Demand forecast: aggregate sales by product (and optionally week), then apply exponential smoothing.
     * Returns suggested order quantity and trend for next period.
     */
    public function getDemandForecast(array $filters = []): array
    {
        $query = Sales::query()
            ->select('product_code', 'product_name', 'document_date', DB::raw('SUM(main_quantity) as qty'), DB::raw('SUM(base_sales_amount) as amount'))
            ->groupBy('product_code', 'product_name', 'document_date');

        $this->applyDateRange($query, $filters, 'document_date');
        if (!empty($filters['product_code'])) {
            $query->where('product_code', 'like', '%' . $filters['product_code'] . '%');
        }
        if (!empty($filters['warehouse'])) {
            $query->where('warehouse', 'like', '%' . $filters['warehouse'] . '%');
        }

        $rows = $query->orderBy('document_date')->get();

        // Fallback: if no sales data, use Belzona inventory output by product and date
        if ($rows->isEmpty()) {
            $rows = $this->getDemandSeriesFromBelzona($filters);
        }

        // Group by product and week (year-week key)
        $byProduct = [];
        foreach ($rows as $r) {
            $key = ($r->product_code ?? null) ?: $r->product_name;
            $date = $r->document_date ?? $r->date ?? null;
            if (!$date) {
                continue;
            }
            if (is_string($date)) {
                $date = Carbon::parse($date);
            }
            $weekKey = $date->format('Y-W');
            if (!isset($byProduct[$key])) {
                $byProduct[$key] = ['name' => $r->product_name, 'code' => $r->product_code, 'weeks' => []];
            }
            if (!isset($byProduct[$key]['weeks'][$weekKey])) {
                $byProduct[$key]['weeks'][$weekKey] = ['qty' => 0, 'amount' => 0];
            }
            $byProduct[$key]['weeks'][$weekKey]['qty'] += (float) ($r->qty ?? 0);
            $byProduct[$key]['weeks'][$weekKey]['amount'] += (float) ($r->amount ?? 0);
        }

        $result = [];
        foreach ($byProduct as $key => $data) {
            $quantities = array_values(array_map(function ($w) {
                return $w['qty'];
            }, $data['weeks']));
            if (count($quantities) < 2) {
                $forecast = $quantities[0] ?? 0;
                $trend = 0;
            } else {
                // Exponential smoothing: alpha=0.3
                $forecast = $this->exponentialSmoothing($quantities, 0.3);
                $trend = end($quantities) - reset($quantities);
            }
            $result[] = [
                'product_code' => $data['code'],
                'product_name' => $data['name'],
                'avg_weekly_qty' => count($quantities) ? array_sum($quantities) / count($quantities) : 0,
                'forecast_next_period' => round($forecast, 2),
                'trend' => round($trend, 2),
                'weeks_count' => count($data['weeks']),
            ];
        }
        usort($result, function ($a, $b) {
            return $b['forecast_next_period'] <=> $a['forecast_next_period'];
        });
        return array_slice($result, 0, 30);
    }

    private function exponentialSmoothing(array $values, float $alpha): float
    {
        if (empty($values)) {
            return 0;
        }
        $s = $values[0];
        for ($i = 1; $i < count($values); $i++) {
            $s = $alpha * $values[$i] + (1 - $alpha) * $s;
        }
        return $s;
    }

    /**
     * Smart inventory alerts: for each product compute days until min_stock and suggest reorder.
     */
    public function getSmartAlerts(array $filters = []): array
    {
        $query = Inventory::where('inventory_status', 'active')->select('inventory_id', 'inventory_code', 'inventory_name', 'current_quantity', 'minimum_stock', 'main_unit');
        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $query->where(function ($q) use ($s) {
                $q->where('inventory_code', 'like', '%' . $s . '%')->orWhere('inventory_name', 'like', '%' . $s . '%');
            });
        }
        $items = $query->get();

        $alerts = [];
        foreach ($items as $inv) {
            $current = (float) $inv->current_quantity;
            $min = (float) $inv->minimum_stock;
            $dailyUsage = $this->getAverageDailyUsage($inv->inventory_id, $filters);
            $daysUntilMin = $dailyUsage > 0 ? ($current - $min) / $dailyUsage : 999;
            $suggestedOrder = $min > $current ? ($min - $current) : 0;
            if ($min <= 0) {
                $daysUntilMin = 999;
                $suggestedOrder = 0;
            }
            $alerts[] = [
                'inventory_id' => $inv->inventory_id,
                'inventory_code' => $inv->inventory_code,
                'inventory_name' => $inv->inventory_name,
                'current_quantity' => $current,
                'minimum_stock' => $min,
                'main_unit' => $inv->main_unit ?? 'عدد',
                'avg_daily_usage' => round($dailyUsage, 4),
                'days_until_min_stock' => $daysUntilMin < 0 ? 0 : round($daysUntilMin, 1),
                'suggested_reorder_qty' => max(0, round($suggestedOrder, 2)),
                'is_low' => $current <= $min,
            ];
        }
        usort($alerts, function ($a, $b) {
            if ($a['is_low'] !== $b['is_low']) {
                return $a['is_low'] ? -1 : 1;
            }
            return $a['days_until_min_stock'] <=> $b['days_until_min_stock'];
        });
        return $alerts;
    }

    private function getAverageDailyUsage(int $inventoryId, array $filters): float
    {
        $inv = Inventory::find($inventoryId);
        if (!$inv) {
            return 0;
        }
        $daysBack = (int) ($filters['days_back'] ?? 3650);
        if ($daysBack <= 0) {
            $daysBack = 3650;
        }
        $from = Carbon::now()->subDays($daysBack)->format('Y-m-d');
        $out = InventoryEntry::where('inventory_id', $inventoryId)
            ->whereIn('entry_type', ['خروجی', 'output', 'OUT', 'خروج'])
            ->where('entry_date', '>=', $from)
            ->sum('quantity');
        $salesQty = Sales::where('product_code', $inv->inventory_code)
            ->where('document_date', '>=', $from)
            ->sum('main_quantity');
        $belzonaOut = BelzonaInventory::where('date', '>=', $from)
            ->where(function ($q) use ($inv) {
                $q->where('product_name', $inv->inventory_name)
                    ->orWhere('product_name', $inv->inventory_code);
            })
            ->sum('output');
        $totalOut = (float) $out + (float) $salesQty + (float) $belzonaOut;
        return $daysBack > 0 ? $totalOut / $daysBack : 0;
    }

    /**
     * Customer clustering: aggregate by customer, then simple k-means (3 segments).
     */
    public function getCustomerClustering(array $filters = []): array
    {
        $query = Sales::query()
            ->select('customer_name', DB::raw('COUNT(*) as order_count'), DB::raw('SUM(base_sales_amount) as total_amount'), DB::raw('MAX(document_date) as last_date'))
            ->whereNotNull('customer_name')->where('customer_name', '!=', '')
            ->groupBy('customer_name');

        $this->applyDateRange($query, $filters, 'document_date');
        $rows = $query->get();

        if ($rows->isEmpty()) {
            $rows = $this->getCustomerSeriesFromSettlements($filters);
        }

        $customers = [];
        $maxAmount = 0;
        $maxCount = 0;
        foreach ($rows as $r) {
            $last = $r->last_date ? Carbon::parse($r->last_date)->diffInDays(Carbon::now()) : 9999;
            $customers[] = [
                'customer_name' => $r->customer_name,
                'order_count' => (int) $r->order_count,
                'total_amount' => (float) $r->total_amount,
                'recency_days' => $last,
                '_amount' => (float) $r->total_amount,
                '_count' => (int) $r->order_count,
                '_recency' => min($last, 365),
            ];
            if ($r->total_amount > $maxAmount) {
                $maxAmount = $r->total_amount;
            }
            if ($r->order_count > $maxCount) {
                $maxCount = $r->order_count;
            }
        }
        if (empty($customers)) {
            return ['segments' => [], 'customers' => []];
        }
        $maxAmount = $maxAmount ?: 1;
        $maxCount = $maxCount ?: 1;
        foreach ($customers as &$c) {
            $c['_n1'] = $c['_amount'] / $maxAmount;
            $c['_n2'] = $c['_count'] / $maxCount;
            $c['_n3'] = 1 - (min($c['_recency'], 365) / 365);
        }
        unset($c);

        $k = 3;
        $centroids = $this->kMeansInit($customers, $k, ['_n1', '_n2', '_n3']);
        for ($iter = 0; $iter < 20; $iter++) {
            foreach ($customers as &$c) {
                $best = 0;
                $bestD = 1e9;
                for ($i = 0; $i < $k; $i++) {
                    $d = pow($c['_n1'] - $centroids[$i][0], 2) + pow($c['_n2'] - $centroids[$i][1], 2) + pow($c['_n3'] - $centroids[$i][2], 2);
                    if ($d < $bestD) {
                        $bestD = $d;
                        $best = $i;
                    }
                }
                $c['segment'] = $best;
            }
            unset($c);
            $newC = array_fill(0, $k, [0, 0, 0, 0]);
            foreach ($customers as $c) {
                $s = $c['segment'];
                $newC[$s][0] += $c['_n1'];
                $newC[$s][1] += $c['_n2'];
                $newC[$s][2] += $c['_n3'];
                $newC[$s][3]++;
            }
            for ($i = 0; $i < $k; $i++) {
                if ($newC[$i][3] > 0) {
                    $centroids[$i] = [$newC[$i][0] / $newC[$i][3], $newC[$i][1] / $newC[$i][3], $newC[$i][2] / $newC[$i][3]];
                }
            }
        }

        $labels = ['A (باارزش)', 'B (متوسط)', 'C (کم‌تعامل)'];
        $bySegment = [];
        foreach ($customers as $c) {
            $s = $c['segment'];
            unset($c['_n1'], $c['_n2'], $c['_n3']);
            $c['segment_label'] = $labels[$s] ?? ('بخش ' . ($s + 1));
            $bySegment[$s][] = $c;
        }
        krsort($bySegment);
        $segments = [];
        foreach ($bySegment as $idx => $list) {
            $segments[] = ['id' => $idx, 'label' => $labels[$idx] ?? 'بخش ' . ($idx + 1), 'count' => count($list), 'customers' => array_slice($list, 0, 50)];
        }
        return ['segments' => $segments, 'total_customers' => count($customers)];
    }

    private function kMeansInit(array $data, int $k, array $keys): array
    {
        $n = count($data);
        if ($n <= $k) {
            $c = [];
            for ($i = 0; $i < $k; $i++) {
                $c[] = [$i * 0.33, $i * 0.33, $i * 0.33];
            }
            return $c;
        }
        $step = (int) floor($n / $k);
        $c = [];
        for ($i = 0; $i < $k; $i++) {
            $idx = min($i * $step, $n - 1);
            $row = $data[$idx];
            $c[] = [$row['_n1'], $row['_n2'], $row['_n3']];
        }
        return $c;
    }

    /**
     * Anomaly detection: daily sales totals, flag days beyond IQR or z-score.
     */
    public function getAnomalyDetection(array $filters = []): array
    {
        $query = Sales::query()
            ->select(DB::raw('DATE(document_date) as day'), DB::raw('COUNT(*) as order_count'), DB::raw('SUM(base_sales_amount) as total_amount'))
            ->groupBy(DB::raw('DATE(document_date)'));

        $this->applyDateRange($query, $filters, 'document_date');
        $rows = $query->orderBy('day')->get();

        if ($rows->isEmpty()) {
            $rows = $this->getDailyTotalsFromBelzona($filters);
        }

        $amounts = $rows->pluck('total_amount')->map(function ($v) {
            return (float) $v;
        })->values()->toArray();
        if (count($amounts) < 3) {
            return ['anomalies' => [], 'stats' => [], 'days' => [], 'source' => $rows->isEmpty() ? 'none' : 'belzona'];
        }
        $mean = array_sum($amounts) / count($amounts);
        $variance = 0;
        foreach ($amounts as $a) {
            $variance += ($a - $mean) ** 2;
        }
        $std = $variance > 0 ? sqrt($variance / count($amounts)) : 0;
        $q1 = $this->percentile($amounts, 25);
        $q3 = $this->percentile($amounts, 75);
        $iqr = $q3 - $q1;
        $lower = $q1 - 1.5 * $iqr;
        $upper = $q3 + 1.5 * $iqr;

        $anomalies = [];
        $days = [];
        foreach ($rows as $i => $r) {
            $amt = (float) $r->total_amount;
            $z = $std > 0 ? abs($amt - $mean) / $std : 0;
            $isOutlier = $amt < $lower || $amt > $upper || $z > 2;
            $days[] = [
                'day' => $r->day,
                'order_count' => (int) $r->order_count,
                'total_amount' => $amt,
                'z_score' => round($z, 2),
                'is_anomaly' => $isOutlier,
            ];
            if ($isOutlier) {
                $anomalies[] = [
                    'day' => $r->day,
                    'order_count' => (int) $r->order_count,
                    'total_amount' => $amt,
                    'reason' => $amt > $upper ? 'فروش بسیار بالا' : ($amt < $lower ? 'فروش بسیار پایین' : 'انحراف از میانگین'),
                ];
            }
        }
        return [
            'anomalies' => $anomalies,
            'stats' => ['mean' => round($mean, 2), 'std' => round($std, 2), 'q1' => $q1, 'q3' => $q3, 'lower' => $lower, 'upper' => $upper],
            'days' => $days,
        ];
    }

    private function percentile(array $sorted, float $p): float
    {
        $copy = $sorted;
        sort($copy);
        $n = count($copy);
        if ($n === 0) {
            return 0;
        }
        $idx = $p / 100 * ($n - 1);
        $low = (int) floor($idx);
        $high = min($low + 1, $n - 1);
        $w = $idx - $low;
        return $copy[$low] * (1 - $w) + $copy[$high] * $w;
    }

    /**
     * Apply date filter. Use "all" data when days is 0, 'all', or >= 3650 (no filter).
     */
    private function applyDateRange($query, array $filters, string $col): void
    {
        if (!empty($filters['from_date'])) {
            $query->where($col, '>=', $filters['from_date']);
        }
        if (!empty($filters['to_date'])) {
            $query->where($col, '<=', $filters['to_date']);
        }
        $days = isset($filters['days']) ? (is_numeric($filters['days']) ? (int) $filters['days'] : $filters['days']) : null;
        if ($days === 0 || $days === '0' || $days === 'all' || (is_int($days) && $days >= 3650)) {
            return;
        }
        if (empty($filters['from_date']) && empty($filters['to_date']) && $days !== null && $days !== '') {
            $from = Carbon::now()->subDays((int) $days)->format('Y-m-d');
            $query->where($col, '>=', $from);
        }
    }

    /**
     * Demand series from Belzona (output by product and date). Same shape as sales aggregate for forecast.
     */
    private function getDemandSeriesFromBelzona(array $filters): \Illuminate\Support\Collection
    {
        $query = BelzonaInventory::query()
            ->select('product_name', DB::raw('DATE(date) as date'), DB::raw('SUM(COALESCE(output, 0)) as qty'), DB::raw('0 as amount'))
            ->whereNotNull('date')
            ->groupBy('product_name', DB::raw('DATE(date)'));
        $this->applyDateRange($query, $filters, 'date');
        $rows = $query->orderBy('date')->get();
        return $rows->map(function ($r) {
            return (object) [
                'product_code' => null,
                'product_name' => $r->product_name,
                'document_date' => $r->date,
                'qty' => (float) $r->qty,
                'amount' => 0,
            ];
        });
    }

    /**
     * Customer aggregate from invoice_settlements (when sales has no customers).
     */
    private function getCustomerSeriesFromSettlements(array $filters): \Illuminate\Support\Collection
    {
        $query = InvoiceSettlement::query()
            ->select('customer_name', DB::raw('COUNT(*) as order_count'), DB::raw('SUM(COALESCE(base_net_amount, 0)) as total_amount'), DB::raw('MAX(document_date) as last_date'))
            ->whereNotNull('customer_name')->where('customer_name', '!=', '')
            ->groupBy('customer_name');
        $this->applyDateRange($query, $filters, 'document_date');
        return $query->get();
    }

    /**
     * Daily totals from Belzona (output) for anomaly detection when sales is empty.
     */
    private function getDailyTotalsFromBelzona(array $filters): \Illuminate\Support\Collection
    {
        $query = BelzonaInventory::query()
            ->select(DB::raw('DATE(date) as day'), DB::raw('COUNT(*) as order_count'), DB::raw('SUM(COALESCE(output, 0)) as total_amount'))
            ->whereNotNull('date')
            ->groupBy(DB::raw('DATE(date)'));
        $this->applyDateRange($query, $filters, 'date');
        return $query->orderBy('day')->get();
    }

    /**
     * LSTM-based demand forecast (calls Python script if available).
     * Returns same structure as getDemandForecast but with optional LSTM predictions per product.
     */
    public function getDemandForecastLstm(array $filters = []): array
    {
        $phpForecast = $this->getDemandForecast($filters);
        if (!config('inventory.enable_python_ml', true)) {
            foreach ($phpForecast as &$row) {
                $row['lstm_predictions'] = [];
                $row['lstm_method'] = 'غیرفعال در این سرور';
            }
            unset($row);
            return $phpForecast;
        }
        $scriptPath = $this->getScriptPath('inventory.lstm_forecast_script', 'tools/lstm_forecast.py');
        $pythonPath = config('inventory.python_path', 'python');
        $projectRoot = defined('BASE_DIR') ? rtrim(BASE_DIR, DIRECTORY_SEPARATOR) : dirname(base_path());

        $query = Sales::query()
            ->select('product_code', 'product_name', 'document_date', DB::raw('SUM(main_quantity) as qty'))
            ->groupBy('product_code', 'product_name', 'document_date');
        $this->applyDateRange($query, $filters, 'document_date');
        $rows = $query->orderBy('document_date')->get();

        $byProduct = [];
        foreach ($rows as $r) {
            $key = $r->product_code ?: $r->product_name;
            $date = is_string($r->document_date) ? Carbon::parse($r->document_date) : $r->document_date;
            $weekKey = $date->format('Y-W');
            if (!isset($byProduct[$key])) {
                $byProduct[$key] = ['name' => $r->product_name, 'code' => $r->product_code, 'weeks' => []];
            }
            $byProduct[$key]['weeks'][$weekKey] = ($byProduct[$key]['weeks'][$weekKey] ?? 0) + (float) $r->qty;
        }

        foreach ($phpForecast as &$row) {
            $key = $row['product_code'] ?: $row['product_name'];
            $weeks = $byProduct[$key]['weeks'] ?? [];
            ksort($weeks);
            $series = array_values($weeks);
            $row['lstm_predictions'] = [];
            $row['lstm_method'] = null;
            if (count($series) >= 10 && $scriptPath && file_exists($scriptPath)) {
                $tmpDir = storage_path('app/temp');
                if (!is_dir($tmpDir)) {
                    @mkdir($tmpDir, 0755, true);
                }
                $tmpFile = $tmpDir . '/lstm_series_' . uniqid() . '.json';
                file_put_contents($tmpFile, json_encode(['series' => $series]));
                $cmd = sprintf('%s %s --input %s --steps 4 --lookback 8 2>&1', escapeshellarg($pythonPath), escapeshellarg($scriptPath), escapeshellarg($tmpFile));
                $output = [];
                @exec($cmd, $output);
                @unlink($tmpFile);
                $json = json_decode(implode("\n", $output), true);
                if (!empty($json['success']) && !empty($json['predictions'])) {
                    $row['lstm_predictions'] = $json['predictions'];
                    $row['lstm_method'] = $json['method'] ?? 'lstm';
                }
            }
        }
        unset($row);
        return $phpForecast;
    }

    /**
     * Autoencoder-based anomaly detection (calls Python script if available).
     */
    public function getAnomalyAutoencoder(array $filters = []): array
    {
        $iqrResult = $this->getAnomalyDetection($filters);
        if (!config('inventory.enable_python_ml', true)) {
            $iqrResult['autoencoder_anomalies'] = [];
            $iqrResult['autoencoder_method'] = 'غیرفعال در این سرور';
            return $iqrResult;
        }
        $scriptPath = $this->getScriptPath('inventory.autoencoder_anomaly_script', 'tools/autoencoder_anomaly.py');
        $pythonPath = config('inventory.python_path', 'python');

        $days = $iqrResult['days'] ?? [];
        if (count($days) < 10 || !$scriptPath || !file_exists($scriptPath)) {
            return array_merge($iqrResult, ['autoencoder_anomalies' => [], 'autoencoder_method' => null]);
        }
        $tmpDir = storage_path('app/temp');
        if (!is_dir($tmpDir)) {
            @mkdir($tmpDir, 0755, true);
        }
        $tmpFile = $tmpDir . '/ae_days_' . uniqid() . '.json';
        file_put_contents($tmpFile, json_encode(['days' => array_map(function ($d) {
            return ['date' => $d['day'], 'amount' => $d['total_amount'], 'count' => $d['order_count'] ?? 0];
        }, $days)]));
        $cmd = sprintf('%s %s --input %s --threshold 2.0 2>&1', escapeshellarg($pythonPath), escapeshellarg($scriptPath), escapeshellarg($tmpFile));
        $output = [];
        @exec($cmd, $output);
        @unlink($tmpFile);
        $json = json_decode(implode("\n", $output), true);
        $aeAnomalies = [];
        $aeMethod = null;
        if (!empty($json['success']) && isset($json['anomalies'])) {
            $aeAnomalies = $json['anomalies'];
            $aeMethod = $json['method'] ?? 'autoencoder';
        }
        $iqrResult['autoencoder_anomalies'] = $aeAnomalies;
        $iqrResult['autoencoder_method'] = $aeMethod;
        return $iqrResult;
    }

    /**
     * Summary from invoice_settlements: total balance, top customers by balance. No date filter so always useful.
     */
    public function getSettlementSummary(): array
    {
        $totalBalance = InvoiceSettlement::sum(DB::raw('COALESCE(balance_amount, 0)'));
        $totalNet = InvoiceSettlement::sum(DB::raw('COALESCE(base_net_amount, 0)'));
        $totalPaid = InvoiceSettlement::sum(DB::raw('COALESCE(paid_amount, 0)'));
        $byCustomer = InvoiceSettlement::query()
            ->select('customer_name', DB::raw('SUM(COALESCE(balance_amount, 0)) as balance'), DB::raw('COUNT(*) as doc_count'))
            ->whereNotNull('customer_name')->where('customer_name', '!=', '')
            ->groupBy('customer_name')
            ->orderByDesc('balance')
            ->limit(15)
            ->get()
            ->map(function ($r) {
                return ['customer_name' => $r->customer_name, 'balance' => (float) $r->balance, 'doc_count' => (int) $r->doc_count];
            })
            ->toArray();
        return [
            'total_balance' => (float) $totalBalance,
            'total_net' => (float) $totalNet,
            'total_paid' => (float) $totalPaid,
            'top_by_balance' => $byCustomer,
            'record_count' => InvoiceSettlement::count(),
        ];
    }

    /**
     * Summary from belzona_inventories: total output/input by product (top), record count.
     */
    public function getBelzonaSummary(): array
    {
        $count = BelzonaInventory::count();
        $byProduct = BelzonaInventory::query()
            ->select('product_name', DB::raw('SUM(COALESCE(output, 0)) as total_output'), DB::raw('SUM(COALESCE(input, 0)) as total_input'))
            ->whereNotNull('product_name')->where('product_name', '!=', '')
            ->groupBy('product_name')
            ->orderByDesc('total_output')
            ->limit(15)
            ->get()
            ->map(function ($r) {
                return [
                    'product_name' => $r->product_name,
                    'total_output' => (float) $r->total_output,
                    'total_input' => (float) $r->total_input,
                ];
            })
            ->toArray();
        return [
            'record_count' => $count,
            'top_by_output' => $byProduct,
        ];
    }

    /**
     * Summary from sales: total records, amount, quantity, date range, top products, top customers.
     */
    public function getSalesSummary(): array
    {
        $count = Sales::count();
        $totalAmount = Sales::sum(DB::raw('COALESCE(base_sales_amount, 0)'));
        $totalQty = Sales::sum(DB::raw('COALESCE(main_quantity, 0)'));
        $minDate = Sales::min('document_date');
        $maxDate = Sales::max('document_date');
        $topByAmount = Sales::query()
            ->select('product_code', 'product_name', DB::raw('SUM(COALESCE(base_sales_amount, 0)) as total_amount'), DB::raw('SUM(COALESCE(main_quantity, 0)) as total_qty'))
            ->whereNotNull('product_name')->where('product_name', '!=', '')
            ->groupBy('product_code', 'product_name')
            ->orderByDesc('total_amount')
            ->limit(15)
            ->get()
            ->map(function ($r) {
                return [
                    'product_code' => $r->product_code,
                    'product_name' => $r->product_name,
                    'total_amount' => (float) $r->total_amount,
                    'total_qty' => (float) $r->total_qty,
                ];
            })
            ->toArray();
        $topCustomers = Sales::query()
            ->select('customer_name', DB::raw('SUM(COALESCE(base_sales_amount, 0)) as total_amount'), DB::raw('COUNT(*) as doc_count'))
            ->whereNotNull('customer_name')->where('customer_name', '!=', '')
            ->groupBy('customer_name')
            ->orderByDesc('total_amount')
            ->limit(15)
            ->get()
            ->map(function ($r) {
                return [
                    'customer_name' => $r->customer_name,
                    'total_amount' => (float) $r->total_amount,
                    'doc_count' => (int) $r->doc_count,
                ];
            })
            ->toArray();
        $uniqueProducts = Sales::whereNotNull('product_name')->where('product_name', '!=', '')->distinct()->count('product_name');
        $uniqueCustomers = Sales::whereNotNull('customer_name')->where('customer_name', '!=', '')->distinct()->count('customer_name');
        return [
            'record_count' => $count,
            'total_amount' => (float) $totalAmount,
            'total_quantity' => (float) $totalQty,
            'min_date' => $minDate,
            'max_date' => $maxDate,
            'unique_products' => $uniqueProducts,
            'unique_customers' => $uniqueCustomers,
            'top_by_amount' => $topByAmount,
            'top_customers' => $topCustomers,
        ];
    }

    /**
     * Summary from inventory: total items, active, with min_stock, total quantity, low stock count, top by quantity.
     */
    public function getInventorySummary(): array
    {
        $count = Inventory::count();
        $activeCount = Inventory::where('inventory_status', 'active')->count();
        $withMinStock = Inventory::where('inventory_status', 'active')->where('minimum_stock', '>', 0)->count();
        $totalQty = Inventory::where('inventory_status', 'active')->sum(DB::raw('COALESCE(current_quantity, 0)'));
        $lowStockCount = Inventory::where('inventory_status', 'active')
            ->whereRaw('COALESCE(minimum_stock, 0) > 0')
            ->whereRaw('COALESCE(current_quantity, 0) <= COALESCE(minimum_stock, 0)')
            ->count();
        $topByQuantity = Inventory::where('inventory_status', 'active')
            ->select('inventory_code', 'inventory_name', 'current_quantity', 'minimum_stock', 'main_unit')
            ->orderByDesc(DB::raw('COALESCE(current_quantity, 0)'))
            ->limit(15)
            ->get()
            ->map(function ($r) {
                return [
                    'inventory_code' => $r->inventory_code,
                    'inventory_name' => $r->inventory_name,
                    'current_quantity' => (float) $r->current_quantity,
                    'minimum_stock' => (float) $r->minimum_stock,
                    'main_unit' => $r->main_unit ?? 'عدد',
                ];
            })
            ->toArray();
        $zeroStockCount = Inventory::where('inventory_status', 'active')->whereRaw('COALESCE(current_quantity, 0) = 0')->count();
        return [
            'record_count' => $count,
            'active_count' => $activeCount,
            'with_minimum_stock' => $withMinStock,
            'total_quantity' => (float) $totalQty,
            'low_stock_count' => $lowStockCount,
            'zero_stock_count' => $zeroStockCount,
            'top_by_quantity' => $topByQuantity,
        ];
    }

    private function getScriptPath(string $configKey, string $default): string
    {
        $path = config($configKey, $default);
        $root = defined('BASE_DIR') ? rtrim(BASE_DIR, DIRECTORY_SEPARATOR) : dirname(base_path());
        if (!preg_match('#^[A-Za-z]:#', $path)) {
            $path = $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path);
        }
        return $path;
    }
}
