<?php

namespace App\Console\Commands;

use App\Imports\InventoryEntryImport;
use App\Services\InventoryEntryService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class InventoryEntriesImportCommand extends Command
{
    protected $signature = 'inventory:import-entries {path : Relative path to the Excel file}';
    protected $description = 'Import inventory entry batches from Excel template';

    public function handle(InventoryEntryService $entryService)
    {
        $path = $this->argument('path');
        $fullPath = $this->resolvePath($path);

        if (!file_exists($fullPath)) {
            $this->error("File not found: {$fullPath}");
            return Command::FAILURE;
        }

        $import = new InventoryEntryImport($entryService);
        $import->import($fullPath);

        if ($import->failures()->isNotEmpty()) {
            foreach ($import->failures() as $failure) {
                $this->warn(
                    sprintf(
                        'Row %d: %s',
                        $failure->row(),
                        implode(', ', $failure->errors())
                    )
                );
            }
        }

        $this->info('Inventory entry import completed.');
        return Command::SUCCESS;
    }

    private function resolvePath(string $path): string
    {
        if (file_exists($path)) {
            return $path;
        }

        $storagePath = storage_path($path);
        if (file_exists($storagePath)) {
            return $storagePath;
        }

        return base_path($path);
    }
}



