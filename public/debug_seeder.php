<?php
// Verbose debug seeder script to catch step-by-step errors
header('Content-Type: text/plain');
ini_set('memory_limit', '512M');
set_time_limit(300);

echo "Starting debug seeder script...\n";

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Load paths config
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();

// Define constants
define('ENVIRONMENT', 'production');
define('APPPATH', realpath($paths->appDirectory) . DIRECTORY_SEPARATOR);
define('SYSTEMPATH', realpath($paths->systemDirectory) . DIRECTORY_SEPARATOR);
define('WRITEPATH', realpath($paths->writableDirectory) . DIRECTORY_SEPARATOR);
define('ROOTPATH', dirname(APPPATH) . DIRECTORY_SEPARATOR);

// Load constants, autoloaders, modules
require APPPATH . 'Config/Constants.php';
require ROOTPATH . 'vendor/autoload.php';
require APPPATH . 'Config/Modules.php';
require SYSTEMPATH . 'Autoloader/Autoloader.php';
require APPPATH . 'Config/Autoload.php';
require APPPATH . 'Config/Services.php';

$loader = CodeIgniter\Config\Services::autoloader();
$loader->initialize(new Config\Autoload(), new Config\Modules());
$loader->register();

require SYSTEMPATH . 'Common.php';

try {
    $db = \Config\Database::connect();
    echo "DB Connected successfully!\n";
    
    echo "Disabling foreign keys...\n";
    $db->query('SET FOREIGN_KEY_CHECKS = 0;');
    
    echo "Truncating tables...\n";
    $tables = ['product', 'product_variants', 'product_categories', 'product_subcategories', 'product_images', 'product_tag', 'product_taxes', 'category', 'category_group', 'subcategory', 'product_ratings'];
    foreach ($tables as $t) {
        echo " - Truncating `$t`...\n";
        $db->table($t)->truncate();
    }
    
    echo "Re-enabling foreign keys...\n";
    $db->query('SET FOREIGN_KEY_CHECKS = 1;');
    
    echo "Seeding Category Groups...\n";
    $db->table('category_group')->insert([
        'id' => 1,
        'title' => 'Default Group',
        'created_at' => date('Y-m-d H:i:s')
    ]);
    
    echo "Seeding Categories...\n";
    $categories = [
        ['id' => 1, 'category_group_id' => 1, 'row_order' => 1, 'category_name' => 'Vegetables & Fruits', 'slug' => 'vegetables-fruits', 'category_img' => '', 'is_bestseller_category' => 1],
        ['id' => 2, 'category_group_id' => 1, 'row_order' => 2, 'category_name' => 'Dairy, Bread & Eggs', 'slug' => 'dairy-bread-eggs', 'category_img' => '', 'is_bestseller_category' => 1],
        ['id' => 3, 'category_group_id' => 1, 'row_order' => 3, 'category_name' => 'Munchies & Snacks', 'slug' => 'munchies-snacks', 'category_img' => '', 'is_bestseller_category' => 1],
        ['id' => 4, 'category_group_id' => 1, 'row_order' => 4, 'category_name' => 'Cold Drinks & Juices', 'slug' => 'cold-drinks-juices', 'category_img' => '', 'is_bestseller_category' => 1],
        ['id' => 5, 'category_group_id' => 1, 'row_order' => 5, 'category_name' => 'Instant & Frozen Food', 'slug' => 'instant-frozen-food', 'category_img' => '', 'is_bestseller_category' => 0],
        ['id' => 6, 'category_group_id' => 1, 'row_order' => 6, 'category_name' => 'Atta, Rice & Dal', 'slug' => 'atta-rice-dal', 'category_img' => '', 'is_bestseller_category' => 0],
        ['id' => 7, 'category_group_id' => 1, 'row_order' => 7, 'category_name' => 'Cleaning & Home Essentials', 'slug' => 'cleaning-home-essentials', 'category_img' => '', 'is_bestseller_category' => 0],
        ['id' => 8, 'category_group_id' => 1, 'row_order' => 8, 'category_name' => 'Personal Care & Wellness', 'slug' => 'personal-care-wellness', 'category_img' => '', 'is_bestseller_category' => 0]
    ];
    $db->table('category')->insertBatch($categories);
    
    echo "Seeding Subcategories...\n";
    $subcategories = [
        ['id' => 1, 'category_id' => 1, 'row_order' => 1, 'name' => 'Fresh Vegetables', 'slug' => 'fresh-vegetables', 'img' => ''],
        ['id' => 2, 'category_id' => 1, 'row_order' => 2, 'name' => 'Fresh Fruits', 'slug' => 'fresh-fruits', 'img' => ''],
        ['id' => 3, 'category_id' => 1, 'row_order' => 3, 'name' => 'Seasonings & Herbs', 'slug' => 'seasonings-herbs', 'img' => ''],
        ['id' => 4, 'category_id' => 1, 'row_order' => 4, 'name' => 'Exotics & Premium', 'slug' => 'exotics-premium', 'img' => ''],
        ['id' => 5, 'category_id' => 2, 'row_order' => 1, 'name' => 'Milk', 'slug' => 'milk', 'img' => ''],
        ['id' => 6, 'category_id' => 2, 'row_order' => 2, 'name' => 'Bread & Pav', 'slug' => 'bread-pav', 'img' => ''],
        ['id' => 7, 'category_id' => 2, 'row_order' => 3, 'name' => 'Eggs', 'slug' => 'eggs', 'img' => ''],
        ['id' => 8, 'category_id' => 2, 'row_order' => 4, 'name' => 'Butter & Ghee', 'slug' => 'butter-ghee', 'img' => ''],
        ['id' => 9, 'category_id' => 2, 'row_order' => 5, 'name' => 'Cheese', 'slug' => 'cheese', 'img' => ''],
        ['id' => 10, 'category_id' => 2, 'row_order' => 6, 'name' => 'Paneer & Tofu', 'slug' => 'paneer-tofu', 'img' => ''],
        ['id' => 11, 'category_id' => 2, 'row_order' => 7, 'name' => 'Curd & Yogurt', 'slug' => 'curd-yogurt', 'img' => ''],
        ['id' => 12, 'category_id' => 2, 'row_order' => 8, 'name' => 'Meat, Chicken & Fish', 'slug' => 'meat-chicken-fish', 'img' => ''],
        ['id' => 13, 'category_id' => 3, 'row_order' => 1, 'name' => 'Potato Chips & Crisps', 'slug' => 'potato-chips-crisps', 'img' => ''],
        ['id' => 14, 'category_id' => 3, 'row_order' => 2, 'name' => 'Namkeen & Bhujia', 'slug' => 'namkeen-bhujia', 'img' => ''],
        ['id' => 15, 'category_id' => 3, 'row_order' => 3, 'name' => 'Sweets & Chocolates', 'slug' => 'sweets-chocolates', 'img' => ''],
        ['id' => 16, 'category_id' => 3, 'row_order' => 4, 'name' => 'Biscuits & Cookies', 'slug' => 'biscuits-cookies', 'img' => ''],
        ['id' => 17, 'category_id' => 3, 'row_order' => 5, 'name' => 'Dry Fruits, Nuts & Seeds', 'slug' => 'dry-fruits-nuts-seeds', 'img' => ''],
        ['id' => 18, 'category_id' => 4, 'row_order' => 1, 'name' => 'Soft Drinks & Soda', 'slug' => 'soft-drinks-soda', 'img' => ''],
        ['id' => 19, 'category_id' => 4, 'row_order' => 2, 'name' => 'Juices & Fruit Drinks', 'slug' => 'juices-fruit-drinks', 'img' => ''],
        ['id' => 20, 'category_id' => 4, 'row_order' => 3, 'name' => 'Tea & Coffee', 'slug' => 'tea-coffee', 'img' => ''],
        ['id' => 21, 'category_id' => 4, 'row_order' => 4, 'name' => 'Energy Drinks & Water', 'slug' => 'energy-drinks-water', 'img' => ''],
        ['id' => 22, 'category_id' => 5, 'row_order' => 1, 'name' => 'Noodles, Pasta & Soup', 'slug' => 'noodles-pasta-soup', 'img' => ''],
        ['id' => 23, 'category_id' => 5, 'row_order' => 2, 'name' => 'Sauces & Spreads', 'slug' => 'sauces-spreads', 'img' => ''],
        ['id' => 24, 'category_id' => 5, 'row_order' => 3, 'name' => 'Ice Cream & Desserts', 'slug' => 'ice-cream-desserts', 'img' => ''],
        ['id' => 25, 'category_id' => 5, 'row_order' => 4, 'name' => 'Ready to Eat & Frozen Snacks', 'slug' => 'ready-to-eat-frozen-snacks', 'img' => ''],
        ['id' => 26, 'category_id' => 6, 'row_order' => 1, 'name' => 'Atta & Flours', 'slug' => 'atta-flours', 'img' => ''],
        ['id' => 27, 'category_id' => 6, 'row_order' => 2, 'name' => 'Rice & Rice Products', 'slug' => 'rice-rice-products', 'img' => ''],
        ['id' => 28, 'category_id' => 6, 'row_order' => 3, 'name' => 'Dals & Pulses', 'slug' => 'dals-pulses', 'img' => ''],
        ['id' => 29, 'category_id' => 6, 'row_order' => 4, 'name' => 'Cooking Oils & Ghee', 'slug' => 'cooking-oils-ghee', 'img' => ''],
        ['id' => 30, 'category_id' => 6, 'row_order' => 5, 'name' => 'Spices & Masalas', 'slug' => 'spices-masalas', 'img' => ''],
        ['id' => 31, 'category_id' => 7, 'row_order' => 1, 'name' => 'Detergents & Fabric Care', 'slug' => 'detergents-fabric-care', 'img' => ''],
        ['id' => 32, 'category_id' => 7, 'row_order' => 2, 'name' => 'Dishwashers & Cleaners', 'slug' => 'dishwashers-cleaners', 'img' => ''],
        ['id' => 33, 'category_id' => 7, 'row_order' => 3, 'name' => 'Repellents & Fresheners', 'slug' => 'repellents-fresheners', 'img' => ''],
        ['id' => 34, 'category_id' => 7, 'row_order' => 4, 'name' => 'Stationery, Games & Toys', 'slug' => 'stationery-games-toys', 'img' => ''],
        ['id' => 35, 'category_id' => 7, 'row_order' => 5, 'name' => 'Electronics & Batteries', 'slug' => 'electronics-batteries', 'img' => ''],
        ['id' => 36, 'category_id' => 7, 'row_order' => 6, 'name' => 'Home & Lifestyle', 'slug' => 'home-lifestyle-sub', 'img' => ''],
        ['id' => 37, 'category_id' => 8, 'row_order' => 1, 'name' => 'Bath & Body Care', 'slug' => 'bath-body-care', 'img' => ''],
        ['id' => 38, 'category_id' => 8, 'row_order' => 2, 'name' => 'Hair Care', 'slug' => 'hair-care', 'img' => ''],
        ['id' => 39, 'category_id' => 8, 'row_order' => 3, 'name' => 'Skin & Face Care', 'slug' => 'skin-face-care', 'img' => ''],
        ['id' => 40, 'category_id' => 8, 'row_order' => 4, 'name' => 'Beauty & Cosmetics', 'slug' => 'beauty-cosmetics-sub', 'img' => ''],
        ['id' => 41, 'category_id' => 8, 'row_order' => 5, 'name' => 'Feminine Hygiene', 'slug' => 'feminine-hygiene-sub', 'img' => ''],
        ['id' => 42, 'category_id' => 8, 'row_order' => 6, 'name' => 'Baby Care', 'slug' => 'baby-care-sub', 'img' => ''],
        ['id' => 43, 'category_id' => 8, 'row_order' => 7, 'name' => 'Health & Pharma', 'slug' => 'health-pharma-sub', 'img' => ''],
        ['id' => 44, 'category_id' => 8, 'row_order' => 8, 'name' => 'Sexual Wellness', 'slug' => 'sexual-wellness-sub', 'img' => '']
    ];
    $db->table('subcategory')->insertBatch($subcategories);
    echo "Subcategories seeded successfully!\n";
    
    // Now load import JSON
    $importFile = WRITEPATH . 'import_products.json';
    echo "Checking import file at: $importFile\n";
    if (!file_exists($importFile)) {
        throw new Exception("Import file not found!");
    }
    
    echo "Reading import file...\n";
    $jsonData = file_get_contents($importFile);
    echo "Decoding JSON data...\n";
    $products = json_decode($jsonData, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON format: " . json_last_error_msg());
    }
    
    echo "Successfully loaded " . count($products) . " products from JSON.\n";
    
} catch (Exception $e) {
    echo "EXCEPTION DETECTED:\n";
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
