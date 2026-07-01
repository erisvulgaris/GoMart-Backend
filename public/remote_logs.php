<?php
header('Content-Type: application/json');
$logDir = '/var/www/html/writable/logs/';
$files = [];
if (is_dir($logDir)) {
    foreach (scandir($logDir) as $f) {
        if ($f !== '.' && $f !== '..' && str_ends_with($f, '.log')) {
            $files[$f] = file_get_contents($logDir . $f);
        }
    }
}
echo json_encode([
    "log_dir_exists" => is_dir($logDir),
    "log_files" => $files
], JSON_PRETTY_PRINT);
