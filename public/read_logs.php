<?php
// Temporary script to inspect CodeIgniter writable/logs
header('Content-Type: text/plain');

$logDir = dirname(__DIR__) . '/writable/logs/';
if (!is_dir($logDir)) {
    echo "Logs directory not found at: $logDir\n";
    exit;
}

$files = glob($logDir . '*.log');
if (empty($files)) {
    echo "No log files found in: $logDir\n";
    exit;
}

// Sort files by modified time descending
usort($files, function($a, $b) {
    return filemtime($b) - filemtime($a);
});

echo "Found " . count($files) . " log files. Showing the latest:\n\n";

foreach ($files as $file) {
    echo "=========================================\n";
    echo "File: " . basename($file) . " (Modified: " . date('Y-m-d H:i:s', filemtime($file)) . ")\n";
    echo "=========================================\n";
    echo file_get_contents($file);
    echo "\n\n";
}
