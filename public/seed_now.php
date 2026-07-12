<?php
// Direct seeder execution using Composer autoloader and Constants
header('Content-Type: text/plain');

ini_set('memory_limit', '512M');
set_time_limit(300);

echo "Starting direct seeder runner...\n";

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Load paths config
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();

// Define path constants required by CodeIgniter
define('APPPATH', realpath($paths->appDirectory) . DIRECTORY_SEPARATOR);
define('SYSTEMPATH', realpath($paths->systemDirectory) . DIRECTORY_SEPARATOR);
define('WRITEPATH', realpath($paths->writableDirectory) . DIRECTORY_SEPARATOR);
define('ROOTPATH', dirname(APPPATH) . DIRECTORY_SEPARATOR);

// Load constants first (defines Config\APP_NAMESPACE etc.)
if (file_exists(APPPATH . 'Config/Constants.php')) {
    require_once APPPATH . 'Config/Constants.php';
}

// Include Composer autoloader to resolve framework classes
if (file_exists(ROOTPATH . 'vendor/autoload.php')) {
    require ROOTPATH . 'vendor/autoload.php';
}

// Load the autoloader config
require SYSTEMPATH . 'Autoloader/Autoloader.php';
require APPPATH . 'Config/Autoload.php';
require APPPATH . 'Config/Services.php';

$loader = CodeIgniter\Config\Services::autoloader();
$loader->initialize(new Config\Autoload(), new Config\Modules());
$loader->register();

// Load Common functions
require SYSTEMPATH . 'Common.php';

// Now run the seeder directly!
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
