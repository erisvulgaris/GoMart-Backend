<?php
header('Content-Type: text/plain');
echo "=== GoMart CodeIgniter Log Reader ===\n";

$log_dir = __DIR__ . '/../writable/logs';

if (!is_dir($log_dir)) {
    echo "Logs directory does not exist: $log_dir\n";
    exit;
}

$files = glob($log_dir . '/*.log');
if (empty($files)) {
    echo "No log files found in $log_dir\n";
    
    // Let's also check folder permissions
    echo "Permissions for writable directory: " . substr(sprintf('%o', fileperms(__DIR__ . '/../writable')), -4) . "\n";
    exit;
}

// Sort files by modification time descending (latest first)
usort($files, function($a, $b) {
    return filemtime($b) - filemtime($a);
});

echo "Found " . count($files) . " log files. Displaying latest log file: " . basename($files[0]) . "\n\n";
echo file_get_contents($files[0]);
