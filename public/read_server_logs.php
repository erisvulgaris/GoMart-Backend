<?php
header('Content-Type: text/plain');

$log_dir = __DIR__ . '/../writable/logs/';
if (!is_dir($log_dir)) {
    echo "Logs directory not found: " . $log_dir . "\n";
    exit;
}

$files = glob($log_dir . 'log-*.log');
if (empty($files)) {
    echo "No log files found in " . $log_dir . "\n";
    exit;
}

// Sort by modified time descending
usort($files, function($a, $b) {
    return filemtime($b) - filemtime($a);
});

$latest_file = $files[0];
echo "Latest log file: " . basename($latest_file) . " (Modified: " . date("Y-m-d H:i:s", filemtime($latest_file)) . ")\n\n";

// Print last 100 lines of the file
$lines = file($latest_file);
$last_lines = array_slice($lines, -100);
echo implode("", $last_lines);
