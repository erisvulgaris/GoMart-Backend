<?php
// Direct seeder execution bypassing routing and CLI
header('Content-Type: text/plain');

ini_set('memory_limit', '512M');
set_time_limit(300);

echo "Starting seeder script...\n";

// Path to the front controller
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Ensure the current directory is pointing to the front controller's directory
if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

// Load paths config
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();

// Load bootstrap
require $paths->systemDirectory . '/Boot.php';

// Manually boot the framework
CodeIgniter\Boot::bootWeb($paths);

// Resolve seeder and execute
try {
    echo "Running ProductImportSeeder...\n";
    $seeder = new \App\Database\Seeds\ProductImportSeeder();
    $seeder->run();
    echo "\nSeeder finished successfully!\n";
} catch (Exception $e) {
    echo "\nError during seeder execution:\n";
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
