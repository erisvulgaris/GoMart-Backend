<?php
// Direct seeder execution using Composer autoloader, Constants, and Configs in correct order
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

// 1. Load constants first (defines Config\APP_NAMESPACE etc.)
if (file_exists(APPPATH . 'Config/Constants.php')) {
    require_once APPPATH . 'Config/Constants.php';
}

// 2. Include Composer autoloader to resolve framework classes (including parent config classes)
if (file_exists(ROOTPATH . 'vendor/autoload.php')) {
    require_once ROOTPATH . 'vendor/autoload.php';
}

// 3. Load Modules configuration class (now resolved safely via composer autoload)
if (file_exists(APPPATH . 'Config/Modules.php')) {
    require_once APPPATH . 'Config/Modules.php';
}

// 4. Load the autoloader config
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
    $seeder = \Config\Database::seeder();
    $seeder->call('ProductImportSeeder');
    echo "\nSeeder finished successfully!\n";
} catch (Exception $e) {
    echo "\nError during seeder execution:\n";
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
