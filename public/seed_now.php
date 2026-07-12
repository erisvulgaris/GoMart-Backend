<?php
// Mock CLI environment and run seeder via Spark bootstrapper with output buffering
ob_start();

ini_set('memory_limit', '512M');
set_time_limit(300);

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Load paths config
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();

// Mock CLI environment variables so CodeIgniter Spark thinks it is running in CLI!
$_SERVER['argv'] = ['spark', 'db:seed', 'ProductImportSeeder'];
$_SERVER['argc'] = 3;

// Define CLI constants if not already defined (running in Apache/FPM)
if (!defined('STDIN')) {
    define('STDIN', fopen('php://input', 'r'));
}
if (!defined('STDOUT')) {
    define('STDOUT', fopen('php://output', 'w'));
}
if (!defined('STDERR')) {
    define('STDERR', fopen('php://output', 'w'));
}

// Load the Boot class
require $paths->systemDirectory . '/Boot.php';

// Boot Spark!
try {
    CodeIgniter\Boot::bootSpark($paths);
} catch (Exception $e) {
    ob_end_clean();
    header('Content-Type: text/plain');
    echo "Error during Spark execution:\n";
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit;
}

// Flush output
header('Content-Type: text/plain');
ob_end_flush();
