<?php
header('Content-Type: application/json');
$log_dir = __DIR__ . '/../writable/logs';
if (!is_dir($log_dir)) {
    echo json_encode(["error" => "Logs dir not found: " . $log_dir]);
    exit;
}

$files = scandir($log_dir);
$log_contents = [];
foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'log') {
        $log_contents[$file] = file_get_contents($log_dir . '/' . $file);
    }
}
echo json_encode($log_contents, JSON_PRETTY_PRINT);
