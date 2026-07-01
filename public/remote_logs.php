<?php
header('Content-Type: text/plain');
$logFile = '/var/www/html/writable/logs/log-2026-07-01.log';
if (file_exists($logFile)) {
    $content = file_get_contents($logFile);
    echo substr($content, -15000);
} else {
    echo "Log file does not exist: " . $logFile;
}
