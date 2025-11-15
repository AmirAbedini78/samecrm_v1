<?php
function loadSharedStrings($path)
{
    $xml = simplexml_load_file($path);
    if (!$xml) {
        throw new RuntimeException('shared strings load fail');
    }
    $ns = $xml->getNamespaces(true);
    $mainNs = $ns[''] ?? null;
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
    $type = (string)$cell['t'];
    $value = (string)$cell->v;
    if ($type === 's') {
        $index = (int)$value;
        return $sharedStrings[$index] ?? '';
    }
    return $value;
}

$sharedStrings = loadSharedStrings('storage/temp/foroosh_extract/xl/sharedStrings.xml');
$sheet = simplexml_load_file('storage/temp/foroosh_extract/xl/worksheets/sheet1.xml');
$ns = $sheet->getNamespaces(true);
$sheet->registerXPathNamespace('a', $ns['']);
$rows = $sheet->xpath('//a:sheetData/a:row');
foreach (array_slice($rows, 0, 5) as $row) {
    $cells = [];
    foreach ($row->c as $cell) {
        $ref = (string)$cell['r'];
        $cells[$ref] = cellValue($cell, $sharedStrings);
    }
    echo json_encode($cells, JSON_UNESCAPED_UNICODE), "\n";
}
