<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpZip\ZipFile;
use PhpZip\Exception\ZipException;

class CustomerCategoryService
{
    private const CACHE_KEY = 'customer_category_dataset_v3';
    private const CACHE_TTL = 1800;
    private const XLSX_PATH = 'customer_categories.xlsx';
    private const LEGACY_XML_PATH = 'customer_categories.xml';

    /**
     * @return array<int,array{name:string,slug:string,client_count:int}>
     */
    public function allCategories(): array
    {
        return $this->loadData()['categories'];
    }

    /**
     * @return array<int,string>
     */
    public function customersForSlug(string $slug): array
    {
        $data = $this->loadData();

        return $data['category_map'][$slug] ?? [];
    }

    /**
     * @return array{categories: array<int,array{name:string,slug:string,client_count:int}>, category_map: array<string,array<int,string>>}
     */
    private function loadData(): array
    {
        $dataset = $this->resolveDataset();
        if ($dataset === null) {
            return $this->emptyPayload();
        }

        $cacheKey = self::CACHE_KEY . ':' . $dataset['signature'];

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($dataset) {
            try {
                if ($dataset['type'] === 'xlsx') {
                    return $this->parseXlsx($dataset['path']);
                }

                return $this->parseLegacyXml($dataset['path']);
            } catch (\Throwable $exception) {
                Log::error('Customer category parsing failed', [
                    'message' => $exception->getMessage(),
                    'type' => $dataset['type'],
                ]);

                return $this->emptyPayload();
            }
        });
    }

    /**
     * @return array<string,mixed>|null
     */
    private function resolveDataset(): ?array
    {
        $disk = Storage::disk('local');

        $xlsxCandidates = [
            self::XLSX_PATH,
            'app/' . self::XLSX_PATH,
        ];
        foreach ($xlsxCandidates as $candidate) {
            if ($disk->exists($candidate)) {
                $path = $disk->path($candidate);

                return [
                    'type' => 'xlsx',
                    'path' => $path,
                    'signature' => $this->buildSignature($path),
                ];
            }
        }

        $xmlCandidates = [
            self::LEGACY_XML_PATH,
            'app/' . self::LEGACY_XML_PATH,
        ];
        foreach ($xmlCandidates as $candidate) {
            if ($disk->exists($candidate)) {
                $path = $disk->path($candidate);

                return [
                    'type' => 'xml',
                    'path' => $path,
                    'signature' => $this->buildSignature($path),
                ];
            }
        }

        $publicDirectory = public_path('documents/xlsx');
        if (is_dir($publicDirectory)) {
            $files = glob($publicDirectory . DIRECTORY_SEPARATOR . '*.xlsx');
            if (!empty($files)) {
                usort($files, function ($a, $b) {
                    return @filemtime($b) <=> @filemtime($a);
                });

                $path = $files[0];
                if ($path && is_file($path)) {
                    return [
                        'type' => 'xlsx',
                        'path' => $path,
                        'signature' => $this->buildSignature($path),
                    ];
                }
            }
        }

        return null;
    }

    private function buildSignature(string $path): string
    {
        $mtime = @filemtime($path) ?: 0;
        $size = @filesize($path) ?: 0;

        return md5($path . '|' . $mtime . '|' . $size);
    }

    /**
     * @return array{categories: array<int,array{name:string,slug:string,client_count:int}>, category_map: array<string,array<int,string>>}
     */
    private function parseXlsx(string $path): array
    {
        $zip = new ZipFile();

        try {
            $zip->openFile($path);

            $sharedStrings = $this->loadSharedStringsFromZip($zip);
            $workbookXml = $zip->getEntryContents('xl/workbook.xml');
            $relsXml = $zip->hasEntry('xl/_rels/workbook.xml.rels')
                ? $zip->getEntryContents('xl/_rels/workbook.xml.rels')
                : null;

            $sheetTargets = $this->resolveSheetTargets($workbookXml, $relsXml);
            $tried = [];

            foreach ($sheetTargets as $target) {
                $normalized = $this->normalizeSheetTarget($target);
                if (isset($tried[$normalized])) {
                    continue;
                }
                $tried[$normalized] = true;

                if (!$zip->hasEntry($normalized)) {
                    continue;
                }

                $sheetXml = $zip->getEntryContents($normalized);
                $parsed = $this->parseWorksheet($sheetXml, $sharedStrings);
                if (!empty($parsed['categories'])) {
                    return $parsed;
                }
            }

            // Fallback: attempt default sheet path
            if ($zip->hasEntry('xl/worksheets/sheet1.xml')) {
                $sheetXml = $zip->getEntryContents('xl/worksheets/sheet1.xml');
                return $this->parseWorksheet($sheetXml, $sharedStrings);
            }

            return $this->emptyPayload();
        } catch (ZipException $exception) {
            Log::error('Customer category XLSX read error', [
                'message' => $exception->getMessage(),
            ]);

            return $this->emptyPayload();
        } finally {
            $zip->close();
        }
    }

    /**
     * @return array<int,string>
     */
    private function resolveSheetTargets(string $workbookXml, ?string $relsXml): array
    {
        $targets = [];
        $relsMap = [];

        if ($relsXml !== null) {
            $rels = simplexml_load_string($relsXml);
            if ($rels !== false) {
                foreach ($rels->Relationship as $relationship) {
                    $id = (string) $relationship['Id'];
                    $relTarget = (string) $relationship['Target'];
                    if ($id !== '' && $relTarget !== '') {
                        $relsMap[$id] = $relTarget;
                    }
                }
            }
        }

        $workbook = simplexml_load_string($workbookXml);
        if ($workbook === false) {
            return $targets;
        }

        $namespaces = $workbook->getNamespaces(true);
        $rNs = $namespaces['r'] ?? null;

        if (isset($workbook->sheets->sheet)) {
            foreach ($workbook->sheets->sheet as $sheet) {
                $name = (string) $sheet['name'];
                $rid = $rNs ? (string) $sheet->attributes($rNs)['id'] : '';

                if ($rid !== '' && isset($relsMap[$rid])) {
                    $targets[] = $relsMap[$rid];
                } elseif ($name !== '') {
                    // try standard naming convention
                    $targets[] = 'worksheets/' . Str::slug($name) . '.xml';
                }
            }
        }

        return $targets;
    }

    private function normalizeSheetTarget(string $target): string
    {
        $normalized = ltrim($target, '/');
        if (!str_starts_with($normalized, 'xl/')) {
            $normalized = 'xl/' . $normalized;
        }

        return $normalized;
    }

    /**
     * @return array<int,string>
     */
    private function loadSharedStringsFromZip(ZipFile $zip): array
    {
        if (!$zip->hasEntry('xl/sharedStrings.xml')) {
            return [];
        }

        $xml = simplexml_load_string($zip->getEntryContents('xl/sharedStrings.xml'));
        if ($xml === false) {
            return [];
        }

        $strings = [];
        if (isset($xml->si)) {
            foreach ($xml->si as $item) {
                $text = '';
                if (isset($item->t)) {
                    $text .= (string) $item->t;
                }
                if (isset($item->r)) {
                    foreach ($item->r as $run) {
                        $text .= (string) $run->t;
                    }
                }
                $strings[] = $text;
            }
        }

        return $strings;
    }

    /**
     * @param string $worksheetXml
     * @param array<int,string> $sharedStrings
     * @return array{categories: array<int,array{name:string,slug:string,client_count:int}>, category_map: array<string,array<int,string>>}
     */
    private function parseWorksheet(string $worksheetXml, array $sharedStrings): array
    {
        $xml = simplexml_load_string($worksheetXml);
        if ($xml === false) {
            return $this->emptyPayload();
        }

        $namespaces = $xml->getNamespaces(true);
        $mainNs = $namespaces[''] ?? 'http://schemas.openxmlformats.org/spreadsheetml/2006/main';
        $xml->registerXPathNamespace('x', $mainNs);

        $rows = $xml->xpath('//x:sheetData/x:row');
        if (!$rows) {
            return $this->emptyPayload();
        }

        $columnSlugs = [];
        $categories = [];
        $customerMap = [];

        foreach ($rows as $row) {
            $rowAttributes = $row->attributes();
            $rowNumber = isset($rowAttributes['r']) ? (int) $rowAttributes['r'] : null;

            foreach ($row->c as $cell) {
                $reference = (string) $cell['r'];
                [$columnIndex, $detectedRow] = $this->splitCellReference($reference);
                $currentRow = $rowNumber ?? $detectedRow;
                $value = $this->extractCellValue($cell, $sharedStrings);

                if ($currentRow === 1) {
                    if ($value === '') {
                        continue;
                    }
                    if ($columnIndex >= 4) {
                        $slug = $this->slugify($value);
                        $columnSlugs[$columnIndex] = $slug;
                        if (!isset($categories[$slug])) {
                            $categories[$slug] = [
                                'name' => trim($value),
                                'slug' => $slug,
                                'client_count' => 0,
                            ];
                            $customerMap[$slug] = [];
                        }
                    }
                    continue;
                }

                if ($value === '') {
                    continue;
                }

                $slug = $columnSlugs[$columnIndex] ?? null;
                if ($slug === null) {
                    continue;
                }

                $customer = $this->normalizeCustomerName($value);
                if ($customer === '') {
                    continue;
                }

                if (!in_array($customer, $customerMap[$slug], true)) {
                    $customerMap[$slug][] = $customer;
                    $categories[$slug]['client_count']++;
                }
            }
        }

        if (empty($categories)) {
            return $this->emptyPayload();
        }

        return [
            'categories' => array_values($categories),
            'category_map' => $customerMap,
        ];
    }

    /**
     * @return array{categories: array<int,array{name:string,slug:string,client_count:int}>, category_map: array<string,array<int,string>>}
     */
    private function parseLegacyXml(string $path): array
    {
        $xml = simplexml_load_file($path);
        if ($xml === false) {
            return $this->emptyPayload();
        }

        $namespace = 'urn:schemas-microsoft-com:office:spreadsheet';
        $xml->registerXPathNamespace('ss', $namespace);

        $tables = $xml->xpath('//ss:Worksheet[@ss:Name="Sheet2"]/ss:Table');
        if (empty($tables)) {
            $tables = $xml->xpath('//ss:Worksheet/ss:Table');
        }

        if (empty($tables)) {
            return $this->emptyPayload();
        }

        $rows = $tables[0]->xpath('ss:Row');
        if (empty($rows)) {
            return $this->emptyPayload();
        }

        $columnSlugs = [];
        $categories = [];
        $customerMap = [];

        $rowNumber = 0;
        foreach ($rows as $row) {
            $rowNumber++;
            $cells = $row->xpath('ss:Cell');
            $columnIndex = 1;

            foreach ($cells as $cell) {
                $attributes = $cell->attributes($namespace);
                if (isset($attributes['Index'])) {
                    $columnIndex = (int) $attributes['Index'];
                }

                $dataNode = $cell->xpath('ss:Data');
                $value = isset($dataNode[0]) ? trim((string) $dataNode[0]) : '';

                if ($rowNumber === 1) {
                    if ($columnIndex >= 4 && $value !== '') {
                        $slug = $this->slugify($value);
                        $columnSlugs[$columnIndex] = $slug;
                        $categories[$slug] = [
                            'name' => $value,
                            'slug' => $slug,
                            'client_count' => 0,
                        ];
                        $customerMap[$slug] = [];
                    }
                } else {
                    if ($value === '') {
                        $columnIndex++;
                        continue;
                    }

                    $slug = $columnSlugs[$columnIndex] ?? null;
                    if ($slug === null) {
                        $columnIndex++;
                        continue;
                    }

                    $customer = $this->normalizeCustomerName($value);
                    if ($customer === '') {
                        $columnIndex++;
                        continue;
                    }

                    if (!in_array($customer, $customerMap[$slug], true)) {
                        $customerMap[$slug][] = $customer;
                        $categories[$slug]['client_count']++;
                    }
                }

                $columnIndex++;
            }
        }

        if (empty($categories)) {
            return $this->emptyPayload();
        }

        return [
            'categories' => array_values($categories),
            'category_map' => $customerMap,
        ];
    }

    /**
     * @return array{categories: array<int,array{name:string,slug:string,client_count:int}>, category_map: array<string,array<int,string>>}
     */
    private function emptyPayload(): array
    {
        return [
            'categories' => [],
            'category_map' => [],
        ];
    }

    /**
     * @param \SimpleXMLElement $cell
     * @param array<int,string> $sharedStrings
     */
    private function extractCellValue(\SimpleXMLElement $cell, array $sharedStrings): string
    {
        $type = (string) $cell['t'];

        if ($type === 's') {
            $index = (int) $cell->v;
            return $sharedStrings[$index] ?? '';
        }

        if ($type === 'inlineStr' && isset($cell->is->t)) {
            return trim((string) $cell->is->t);
        }

        if ($type === 'str') {
            return trim((string) $cell->v);
        }

        return trim((string) $cell->v);
    }

    /**
     * @return array{0:int,1:int}
     */
    private function splitCellReference(string $reference): array
    {
        if ($reference === '') {
            return [0, 0];
        }

        $columnLetters = strtoupper(preg_replace('/[^A-Z]/', '', $reference));
        $rowNumber = (int) preg_replace('/[^0-9]/', '', $reference);

        $columnIndex = 0;
        $length = strlen($columnLetters);
        for ($i = 0; $i < $length; $i++) {
            $columnIndex = ($columnIndex * 26) + (ord($columnLetters[$i]) - 64);
        }

        return [$columnIndex, $rowNumber];
    }

    private function slugify(string $value): string
    {
        $slug = Str::slug($value);

        return $slug === '' ? md5($value) : $slug;
    }

    private function normalizeCustomerName(string $value): string
    {
        $normalized = preg_replace('/\s+/u', ' ', trim($value));

        return $normalized ?? trim($value);
    }
}

