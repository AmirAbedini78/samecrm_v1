<?php

namespace App\Imports;

use App\Models\GuaranteeLetter;
use App\Models\GuaranteeLetterAssignment;
use App\Models\User;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class GuaranteeLetterImport implements ToModel, WithStartRow, SkipsOnFailure, WithChunkReading, WithBatchInserts, WithProgressBar, WithHeadingRow {

    use Importable, SkipsFailures;

    private $rows = 0;
    private $skipped = 0;
    private $rowIndex = 0;
    private $skippedDetails = [];
    private $guaranteeType;
    private $sheetName;
    private $industrialTypeMapping = [];
 
    public function __construct($guaranteeType = null, $sheetName = null) {
        $this->guaranteeType = $guaranteeType;
        $this->sheetName = $sheetName;
        
        // Map industrial types to user IDs
        // بلبرینگ: هاشمی، عطازاده، کریمی
        // بلزونا: مستعلی، رخ فروز
        // پایپ: محمودی، رامین فر
        $this->industrialTypeMapping = [
            'bearing' => $this->getUserIdsByNames(['هاشمی', 'عطازاده', 'کریمی']),
            'belzona' => $this->getUserIdsByNames(['مستعلی', 'رخ فروز']),
            'pipe' => $this->getUserIdsByNames(['محمودی', 'رامین فر']),
        ];
    }

    /**
     * Get user IDs by names (first name or last name)
     */
    private function getUserIdsByNames($names) {
        $userIds = [];
        foreach ($names as $name) {
            $users = User::where(function($query) use ($name) {
                $query->where('first_name', 'LIKE', '%' . $name . '%')
                      ->orWhere('last_name', 'LIKE', '%' . $name . '%');
            })->pluck('id')->toArray();
            $userIds = array_merge($userIds, $users);
        }
        return array_unique($userIds);
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row) {

        // track current excel row number
        $this->rowIndex++;

        // Skip empty rows
        if (empty($row[0]) && empty($row[1])) {
            $this->skipped++;
            $this->recordSkip('empty_row', $row);
            return null;
        }

        // Debug information
        if ($this->rows % 100 == 0) {
            \Log::info("GuaranteeLetterImport Progress", [
                'processed_rows' => $this->rows,
                'guarantee_type' => $this->guaranteeType,
                'sheet_name' => $this->sheetName,
            ]);
        }

        ++$this->rows;

        try {
            // Map row data to guarantee letter fields
            // Since columns may vary, we'll try to detect them dynamically
            $guaranteeNumber = $this->getValue($row, ['guarantee_number', 'شماره ضمانت نامه', 'شماره', 0]);
            $industrialType = $this->detectIndustrialType($row);
            $issueDate = $this->getDateValue($row, ['issue_date', 'تاریخ صدور', 'تاریخ صدور ضمانت نامه', 1]);
            $expiryDate = $this->getDateValue($row, ['expiry_date', 'تاریخ انقضا', 'تاریخ انقضای ضمانت نامه', 2]);
            $renewalDate = $this->getDateValue($row, ['renewal_date', 'تاریخ تمدید', 3]);
            $settlementDate = $this->getDateValue($row, ['settlement_date', 'تاریخ تسویه', 4]);
            $amount = $this->getDecimalValue($row, ['amount', 'مبلغ', 5]);
            $currency = $this->getValue($row, ['currency', 'ارز', 6], 'IRR');
            $issuingBank = $this->getValue($row, ['issuing_bank', 'بانک صادرکننده', 'بانک', 7]);
            $beneficiary = $this->getValue($row, ['beneficiary', 'ذینفع', 8]);
            $description = $this->getValue($row, ['description', 'توضیحات', 9]);

            // Determine guarantee type from sheet name or row data
            $guaranteeType = $this->guaranteeType;
            if (!$guaranteeType) {
                $guaranteeType = $this->detectGuaranteeType($row);
            }

            // Skip if no guarantee number
            if (empty($guaranteeNumber)) {
                $this->skipped++;
                $this->recordSkip('missing_guarantee_number', $row);
                return null;
            }

            // Check for duplicates
            if ($this->isDuplicate($guaranteeNumber)) {
                $this->skipped++;
                $this->recordSkip('duplicate_guarantee_number', $row);
                return null;
            }

            // Determine assigned user based on industrial type
            $assignedUserId = null;
            if ($industrialType && isset($this->industrialTypeMapping[$industrialType])) {
                $userIds = $this->industrialTypeMapping[$industrialType];
                if (!empty($userIds)) {
                    $assignedUserId = $userIds[0]; // Assign to first matching user
                }
            }

            // Create guarantee letter
            $guarantee = new GuaranteeLetter([
                'guarantee_number' => $guaranteeNumber,
                'guarantee_type' => $guaranteeType,
                'industrial_type' => $industrialType ?: 'bearing',
                'issue_date' => $issueDate,
                'expiry_date' => $expiryDate,
                'renewal_date' => $renewalDate,
                'settlement_date' => $settlementDate,
                'amount' => $amount,
                'currency' => $currency,
                'issuing_bank' => $issuingBank,
                'beneficiary' => $beneficiary,
                'status' => $this->determineStatus($expiryDate),
                'assigned_user_id' => $assignedUserId,
                'description' => $description,
                'guarantee_creatorid' => auth()->id(),
            ]);

            // Save to get the ID
            if ($guarantee->save()) {
                // Create assignment if user is assigned
                if ($assignedUserId) {
                    GuaranteeLetterAssignment::create([
                        'guarantee_id' => $guarantee->guarantee_id,
                        'user_id' => $assignedUserId,
                        'assigned_at' => now(),
                        'assigned_by' => auth()->id(),
                    ]);
                }
            }

            return $guarantee;

        } catch (\Exception $e) {
            Log::error("GuaranteeLetter import error: " . $e->getMessage(), ['row' => $row, 'trace' => $e->getTraceAsString()]);
            $this->skipped++;
            $this->recordSkip('exception: ' . $e->getMessage(), $row);
            return null;
        }
    }

    /**
     * Get value from row by key or index
     */
    private function getValue($row, $keys, $default = null) {
        foreach ($keys as $key) {
            if (is_numeric($key) && isset($row[$key])) {
                return $row[$key] ?: $default;
            } elseif (isset($row[$key])) {
                return $row[$key] ?: $default;
            }
        }
        return $default;
    }

    /**
     * Get date value from row
     */
    private function getDateValue($row, $keys) {
        $value = $this->getValue($row, $keys);
        return $this->parseDate($value);
    }

    /**
     * Get decimal value from row
     */
    private function getDecimalValue($row, $keys) {
        $value = $this->getValue($row, $keys, 0);
        return $this->parseDecimal($value);
    }

    /**
     * Detect industrial type from row data
     */
    private function detectIndustrialType($row) {
        $rowString = implode(' ', array_map('strval', $row));
        
        if (stripos($rowString, 'بلبرینگ') !== false || stripos($rowString, 'bearing') !== false) {
            return 'bearing';
        }
        if (stripos($rowString, 'بلزونا') !== false || stripos($rowString, 'belzona') !== false) {
            return 'belzona';
        }
        if (stripos($rowString, 'پایپ') !== false || stripos($rowString, 'pipe') !== false) {
            return 'pipe';
        }
        
        return null;
    }

    /**
     * Detect guarantee type from row data or sheet name
     */
    private function detectGuaranteeType($row) {
        // Check sheet name first
        if ($this->sheetName) {
            if (stripos($this->sheetName, 'شرکت در مناقصه') !== false || stripos($this->sheetName, 'tender') !== false) {
                return 'tender_participation';
            }
            if (stripos($this->sheetName, 'حسن انجام کار') !== false || stripos($this->sheetName, 'performance') !== false) {
                return 'performance';
            }
            if (stripos($this->sheetName, 'پیش پرداخت') !== false || stripos($this->sheetName, 'advance') !== false) {
                return 'advance_payment';
            }
        }
        
        // Check row data
        $rowString = implode(' ', array_map('strval', $row));
        if (stripos($rowString, 'شرکت در مناقصه') !== false || stripos($rowString, 'tender') !== false) {
            return 'tender_participation';
        }
        if (stripos($rowString, 'حسن انجام کار') !== false || stripos($rowString, 'performance') !== false) {
            return 'performance';
        }
        if (stripos($rowString, 'پیش پرداخت') !== false || stripos($rowString, 'advance') !== false) {
            return 'advance_payment';
        }
        
        return 'tender_participation'; // Default
    }

    /**
     * Determine status based on expiry date
     */
    private function determineStatus($expiryDate) {
        if (!$expiryDate) {
            return 'active';
        }
        
        $expiry = \Carbon\Carbon::parse($expiryDate);
        if ($expiry->isPast()) {
            return 'expired';
        }
        
        return 'active';
    }

    /**
     * @return int
     */
    public function startRow(): int {
        return 2; // Start from row 2 (assuming row 1 is header)
    }

    /**
     * Check for duplicates
     */
    private function isDuplicate($guaranteeNumber) {
        if (empty($guaranteeNumber)) {
            return true;
        }
        
        return GuaranteeLetter::where('guarantee_number', $guaranteeNumber)->exists();
    }

    /**
     * Parse decimal values
     */
    private function parseDecimal($value) {
        if (empty($value) || $value === null) {
            return 0;
        }

        // Remove any non-numeric characters except decimal point and minus
        $value = preg_replace('/[^0-9.-]/', '', $value);
        
        return (float) $value;
    }

    /**
     * Parse date values
     */
    private function parseDate($value) {
        if (empty($value)) {
            return null;
        }

        // Try to parse different date formats
        $formats = ['Y-m-d', 'd/m/Y', 'd-m-Y', 'Y/m/d', 'Y/m/d H:i:s', 'Y-m-d H:i:s'];
        
        foreach ($formats as $format) {
            $date = \DateTime::createFromFormat($format, $value);
            if ($date !== false) {
                return $date->format('Y-m-d');
            }
        }

        // Try Carbon parsing
        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get row count
     */
    public function getRowCount() {
        return $this->rows;
    }

    /**
     * Get skipped count
     */
    public function getSkippedCount() {
        return $this->skipped;
    }

    /**
     * Chunk size for processing
     */
    public function chunkSize(): int
    {
        return 100;
    }

    /**
     * Batch size for database inserts
     */
    public function batchSize(): int
    {
        return 100;
    }

    /**
     * Return detailed skipped rows
     */
    public function getSkippedDetails(): array
    {
        return $this->skippedDetails;
    }

    /**
     * Record a skipped row with details
     */
    private function recordSkip(string $reason, array $row): void
    {
        $excelRowNumber = $this->startRow() + $this->rowIndex - 1;
        $this->skippedDetails[] = [
            'row_number' => $excelRowNumber,
            'reason' => $reason,
            'guarantee_number' => $row[0] ?? null,
        ];
    }
}

