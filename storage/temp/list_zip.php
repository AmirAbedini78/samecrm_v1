<?php
$zip = new ZipArchive();
if (!$zip->open('storage/temp/foroosh.zip')) {
    fwrite(STDERR, "open fail\n");
    exit(1);
}
for ($i = 0; $i < $zip->numFiles; $i++) {
    echo $zip->getNameIndex($i), "\n";
}
