<?php
header('Content-Type: text/plain');

$log_dir = __DIR__ . '/../writable/logs/';
if (!is_dir($log_dir)) {
    echo "Logs directory does not exist: $log_dir\n";
    exit;
}

$files = glob($log_dir . '*.log');
if (empty($files)) {
    echo "No log files found in $log_dir\n";
    
    // Let's also print directory listing of writable/
    echo "\n=== Listing writable/ contents ===\n";
    $writable_dir = __DIR__ . '/../writable/';
    $items = scandir($writable_dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $path = $writable_dir . $item;
        echo $item . (is_dir($path) ? '/' : '') . "\n";
    }
    exit;
}

// Sort files by modified time descending
usort($files, function($a, $b) {
    return filemtime($b) - filemtime($a);
});

echo "Reading latest log file: " . basename($files[0]) . "\n";
echo "========================================\n";
echo file_get_contents($files[0]);
