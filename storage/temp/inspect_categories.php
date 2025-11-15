<?php
function loadSharedStrings($path)
{
    $xml = simplexml_load_file($path);
    if (!$xml) {
        throw new RuntimeException('shared strings load fail');
    }
    $strings = [];
    foreach ($xml->si as $si) {
        $text = '';
        if ($si->t) {
            $text .= (string) $si->t;
        }
        if ($si->r) {
            foreach ($si->r as $run) {
                $text .= (string) $run->t;
            }
        }
        $strings[] = $text;
    }
    return $strings;
}

function cellValue(SimpleXMLElement $cell, array $sharedStrings)
{
    $type = (string) $cell['t'];
    $value = (string) $cell->v;
    if ($type === 's') {
        $index = (int) $value;
        return $sharedStrings[$index] ?? '';
    }
    return $value;
}

$base = 'storage/temp/customer_categories_extract/xl';
$sharedStrings = loadSharedStrings($base . '/sharedStrings.xml');
$sheet = simplexml_load_file($base . '/worksheets/sheet1.xml');
$sheet->registerXPathNamespace('x', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
$rows = $sheet->xpath('//x:worksheet/x:sheetData/x:row');
foreach ($rows as $rowIdx => $row) {
    $cells = [];
    foreach ($row->c as $cell) {
        $ref = (string) $cell['r'];
        $cells[$ref] = cellValue($cell, $sharedStrings);
    }
    echo json_encode($cells, JSON_UNESCAPED_UNICODE), "\n";
    if ($rowIdx > 40) {
        break;
    }
}
