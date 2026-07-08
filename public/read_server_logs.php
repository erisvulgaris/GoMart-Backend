<?php
header('Content-Type: text/plain');

$log_dir = "/var/www/html/writable/logs";
if (!is_dir($log_dir)) {
    echo "Log directory does not exist: $log_dir\n";
    exit;
}

$files = glob("$log_dir/*.log");
if (empty($files)) {
    echo "No log files found in $log_dir\n";
    exit;
}

// Sort by modified time descending
usort($files, function($a, $b) {
    return filemtime($b) - filemtime($a);
});

echo "Latest log files in $log_dir:\n";
foreach (array_slice($files, 0, 3) as $file) {
    echo "\n--- File: " . basename($file) . " (Modified: " . date("Y-m-d H:i:s", filemtime($file)) . ") ---\n";
    // Show last 50 lines of the log file
    $lines = file($file);
    $last_lines = array_slice($lines, -50);
    echo implode("", $last_lines);
}
