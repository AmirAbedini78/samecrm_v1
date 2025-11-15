<?php
$xml = simplexml_load_file("storage/temp/customer_categories_extract/xl/sharedStrings.xml");
$xml->registerXPathNamespace('x', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
$items = $xml->xpath('//x:si');
var_export([
    'count' => is_array($items) ? count($items) : 'not array',
    'first' => isset($items[0]) ? (string) ($items[0]->t ?? '') : null,
]);
