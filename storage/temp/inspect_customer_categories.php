<?php
function loadSharedStrings($path)
{
    if (!file_exists($path)) {
        return [];
    }
    $xml = simplexml_load_file($path);
    if (!$xml) {
        return [];
    }
    $strings = [];
    foreach ($xml->si as $si) {
        $text = '';
        if ($si->t) {
            $text = (string)$si->t;
        } elseif ($si->r) {
            foreach ($si->r as $run) {
                $text .= (string)$run->t;
            }
        }
        $strings[] = $text;
    }
    return $strings;
}

function cellValue($cell, $sharedStrings)
{
    $type = isset($cell['t']) ? (string)$cell['t'] : null;
    $value = (string)$cell->v;
    if ($type === 's') {
        $index = (int)$value;
        return $sharedStrings[$index] ?? '';
    }
    return $value;
}

$base = 'storage/temp/customer_categories_extract/xl';
$sharedStrings = loadSharedStrings($base . '/sharedStrings.xml');
$sheet = simplexml_load_file($base . '/worksheets/sheet1.xml');
$ns = $sheet->getNamespaces(true);
$sheet->registerXPathNamespace('a', $ns['']);
$rows = $sheet->xpath('//a:sheetData/a:row');
foreach (array_slice($rows, 0, 10) as $row) {
    $cells = [];
    foreach ($row->c as $cell) {
        $ref = (string)$cell['r'];
        $cells[$ref] = cellValue($cell, $sharedStrings);
    }
    echo json_encode($cells, JSON_UNESCAPED_UNICODE), "\n";
}
