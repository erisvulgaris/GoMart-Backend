<?php
header('Content-Type: text/plain');
echo "=== GoMart Network Debug ===\n";

$host = 'db';
$port = 3306;

echo "Resolving '$host'...\n";
$ip = gethostbyname($host);
echo "IP of '$host': $ip\n\n";

echo "Attempting to connect to $host:$port...\n";
$errno = 0;
$errstr = '';
$fp = @fsockopen($host, $port, $errno, $errstr, 5);

if (!$fp) {
    echo "Connection FAILED!\n";
    echo "Error Number: $errno\n";
    echo "Error String: $errstr\n";
} else {
    echo "Connection SUCCESSFUL!\n";
    fclose($fp);
}
