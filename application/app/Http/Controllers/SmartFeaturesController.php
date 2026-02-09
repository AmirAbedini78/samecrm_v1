<?php

namespace App\Http\Controllers;

use App\Services\SmartFeaturesService;
use Illuminate\Http\Request;

class SmartFeaturesController extends Controller
{
    protected SmartFeaturesService $smart;

    public function __construct(SmartFeaturesService $smart)
    {
        parent::__construct();
        $this->middleware('auth');
        $this->smart = $smart;
    }

    /**
     * Dashboard: فیچرهای جدید
     */
    public function index()
    {
        $page = [
            'page' => 'smart-features',
            'crumbs' => [__('lang.accounting'), 'فیچرهای جدید'],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => 'فیچرهای جدید',
            'heading' => 'فیچرهای جدید',
            'mainmenu_accounting' => 'active',
            'submenu_smart_features' => 'active',
        ];
        return view('pages.smart-features.index', compact('page'));
    }

    public function demandForecast(Request $request)
    {
        $filters = $request->only(['from_date', 'to_date', 'days', 'product_code', 'warehouse']);
        if (empty($filters['days']) && empty($filters['from_date'])) {
            $filters['days'] = 3650;
        }
        $data = $this->smart->getDemandForecast($filters);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function smartAlerts(Request $request)
    {
        $filters = $request->only(['search', 'days_back', 'days']);
        if (empty($filters['days_back']) && !empty($filters['days'])) {
            $filters['days_back'] = (int) $filters['days'];
        }
        if (empty($filters['days_back'])) {
            $filters['days_back'] = 3650;
        }
        $data = $this->smart->getSmartAlerts($filters);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function customerClustering(Request $request)
    {
        $filters = $request->only(['from_date', 'to_date', 'days']);
        if (empty($filters['days']) && empty($filters['from_date'])) {
            $filters['days'] = 3650;
        }
        $data = $this->smart->getCustomerClustering($filters);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function anomalyDetection(Request $request)
    {
        $filters = $request->only(['from_date', 'to_date', 'days']);
        if (empty($filters['days']) && empty($filters['from_date'])) {
            $filters['days'] = 3650;
        }
        $data = $this->smart->getAnomalyDetection($filters);
        return response()->json(['success' => true, 'data' => $data]);
    }

    /**
     * Demand forecast with LSTM (Python). Falls back to PHP forecast if script unavailable.
     */
    public function demandForecastLstm(Request $request)
    {
        $filters = $request->only(['from_date', 'to_date', 'days', 'product_code', 'warehouse']);
        if (empty($filters['days']) && empty($filters['from_date'])) {
            $filters['days'] = 3650;
        }
        $data = $this->smart->getDemandForecastLstm($filters);
        return response()->json(['success' => true, 'data' => $data]);
    }

    /**
     * Anomaly detection with Autoencoder (Python). Includes IQR result + autoencoder anomalies.
     */
    public function anomalyAutoencoder(Request $request)
    {
        $filters = $request->only(['from_date', 'to_date', 'days']);
        if (empty($filters['days']) && empty($filters['from_date'])) {
            $filters['days'] = 3650;
        }
        $data = $this->smart->getAnomalyAutoencoder($filters);
        return response()->json(['success' => true, 'data' => $data]);
    }

    /**
     * خلاصه تسویه فاکتورها (همیشه از کل داده)
     */
    public function settlementSummary()
    {
        $data = $this->smart->getSettlementSummary();
        return response()->json(['success' => true, 'data' => $data]);
    }

    /**
     * خلاصه انبار بلزونا (همیشه از کل داده)
     */
    public function belzonaSummary()
    {
        $data = $this->smart->getBelzonaSummary();
        return response()->json(['success' => true, 'data' => $data]);
    }

    /**
     * خلاصه فروش (همیشه از کل داده)
     */
    public function salesSummary()
    {
        $data = $this->smart->getSalesSummary();
        return response()->json(['success' => true, 'data' => $data]);
    }

    /**
     * خلاصه انبار (همیشه از کل داده)
     */
    public function inventorySummary()
    {
        $data = $this->smart->getInventorySummary();
        return response()->json(['success' => true, 'data' => $data]);
    }
}
