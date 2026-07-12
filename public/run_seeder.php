<?php
// Temporary script to manually execute the seeder and view output
header('Content-Type: text/plain');

$command = "php " . dirname(__DIR__) . "/spark db:seed ProductImportSeeder 2>&1";
echo "Running command: $command\n";
echo "=========================================\n";

$output = [];
$retval = null;
exec($command, $output, $retval);

echo implode("\n", $output);
echo "\n=========================================\n";
echo "Exit Code: $retval\n";
