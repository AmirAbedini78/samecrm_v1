<?php

namespace App\Imports;

use App\Models\Checklist;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class LeadsChecklistImport implements ToModel, WithStartRow, WithValidation, SkipsOnFailure, WithChunkReading, WithBatchInserts, WithProgressBar {

    use Importable, SkipsFailures;

    private $rows = 0;
    private $skipped = 0;
    private $lead_id;

    public function __construct($lead_id) {
        $this->lead_id = $lead_id;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row) {

        // No import limit - process all records

        //check for duplicates before creating the checklist item
        if ($this->isDuplicate($row)) {
            $this->skipped++;
            return null;
        }

        ++$this->rows;

        //get next position
        if ($last = \App\Models\Checklist::Where('checklistresource_type', 'lead')
            ->Where('checklistresource_id', $this->lead_id)
            ->Where('checklist_clientid', request('access_control_customer_unique_id'))
            ->orderBy('checklist_position', 'desc')
            ->first()) {
            $position = $last->checklist_position + 1;
        } else {
            //default position
            $position = 1;
        }

        //determine checklist status from second column
        $status = $this->determineStatus($row);

        //get checklist text - try expected column names first, then fall back to first column
        $checklist_text = $this->getChecklistText($row);

        return new Checklist([
            'checklist_text' => $checklist_text,
            'checklist_status' => $status,
            'checklist_position' => $position + $this->rows,
            'checklistresource_type' => 'lead',
            'checklistresource_id' => $this->lead_id,
            'checklist_creatorid' => auth()->id(),
            'checklist_clientid' => request('access_control_customer_unique_id'),
            'checklist_created' => now(),
        ]);
    }

    /**
     * Get checklist text from first column (index 0)
     * @param array $row
     * @return string
     */
    private function getChecklistText($row) {
        //get the first column value
        $values = array_values($row);
        $text = isset($values[0]) ? trim($values[0]) : '';

        //clean up text
        $text = strip_tags($text);
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        //limit text length for database constraints
        if (strlen($text) > 500) {
            $text = substr($text, 0, 500);
        }

        return $text;
    }

    /**
     * Determine checklist status from second column
     * @param array $row
     * @return string
     */
    private function determineStatus($row) {
        $status = 'pending'; //default status

        //get values as array (ignoring keys)
        $values = array_values($row);

        //check if there's a second column
        $status_value = isset($values[1]) ? trim($values[1]) : '';

        //convert to lowercase for comparison
        $status_value = strtolower($status_value);

        //check for completed status indicators
        $completed_indicators = [
            'x',
            'done', 'completed', 'complete', 'finished',
            'yes', 'y', 'true', '1',
            'checked', 'tick', 'ticked',
        ];

        if (in_array($status_value, $completed_indicators)) {
            $status = 'completed';
        }

        return $status;
    }

    /**
     * Check if the checklist item is a duplicate
     * @param array $row
     * @return bool
     */
    protected function isDuplicate($row) {

        $checklist_text = $this->getChecklistText($row);

        if (empty($checklist_text)) {
            return true; //skip empty rows
        }

        //lets ignore this functionality for now
        return false;
    }

    public function rules(): array
    {
        return [
            //no specific rules needed for position-based columns
        ];
    }

    /**
     * we start with row number (1) since there are no headers
     * @return int
     */
    public function startRow(): int {
        return 1;
    }

    /**
     * lets count the total imported rows
     * @return int
     */
    public function getRowCount(): int {
        return $this->rows;
    }

    /**
     * get count of skipped duplicate rows
     * @return int
     */
    public function getSkippedCount(): int {
        return $this->skipped;
    }

    /**
     * Chunk size for processing
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * Batch size for database inserts
     */
    public function batchSize(): int
    {
        return 1000;
    }
}