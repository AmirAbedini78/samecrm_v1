<?php
require __DIR__ . "/application/vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Settings;

Settings::setZipClass(Settings::PCLZIP);

$path = __DIR__ . '/storage/app/customer_categories.xlsx';
$reader = IOFactory::createReader('Xlsx');
$spreadsheet = $reader->load($path);
$sheet = $spreadsheet->getSheetByName('Sheet2') ?: $spreadsheet->getActiveSheet();
$rows = [];
foreach ($sheet->toArray(null, true, true, true) as $row) {
    $rows[] = $row;
}
var_export(array_slice($rows, 0, 5));
