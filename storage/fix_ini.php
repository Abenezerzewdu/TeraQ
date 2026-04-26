<?php
$iniPath = 'C:\\Users\\HP\\.config\\herd\\bin\\php84\\php.ini';
$content = file_get_contents($iniPath);

// Check encoding
$bom = substr($content, 0, 3);
if ($bom === chr(0xEF).chr(0xBB).chr(0xBF)) {
    echo "Encoding: UTF-8 BOM\n";
} elseif (substr($content, 0, 2) === chr(0xFF).chr(0xFE)) {
    echo "Encoding: UTF-16 LE BOM - THIS IS THE PROBLEM!\n";
    // Convert from UTF-16 LE to UTF-8
    $content = mb_convert_encoding($content, 'UTF-8', 'UTF-16LE');
    // Remove any BOM
    $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
} else {
    echo "Encoding: No BOM (plain)\n";
}

echo "File size: " . strlen($content) . " bytes\n";

// Check current values
preg_match('/^upload_tmp_dir\s*=.*/m', $content, $m);
echo "Current upload_tmp_dir line: " . ($m[0] ?? 'NOT FOUND') . "\n";

preg_match('/^sys_temp_dir\s*=.*/m', $content, $m);
echo "Current sys_temp_dir line: " . ($m[0] ?? 'NOT FOUND') . "\n";

preg_match('/^;upload_tmp_dir/m', $content, $m);
echo "Commented upload_tmp_dir: " . ($m[0] ?? 'NOT FOUND') . "\n";

preg_match('/^;sys_temp_dir/m', $content, $m);
echo "Commented sys_temp_dir: " . ($m[0] ?? 'NOT FOUND') . "\n";
