<?php
// Turn off output buffering completely to show real-time progress
while (ob_get_level() > 0) {
    ob_end_clean();
}
header('Content-Type: text/plain');
ini_set('memory_limit', '512M');
set_time_limit(300);

echo "Starting debug seeder script...\n";
flush();

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
    flush();
    
    echo "Disabling foreign keys...\n";
    flush();
    $db->query('SET FOREIGN_KEY_CHECKS = 0;');
    
    echo "Truncating tables...\n";
    flush();
    $tables = ['product', 'product_variants', 'product_categories', 'product_subcategories', 'product_images', 'product_tag', 'product_taxes', 'category', 'category_group', 'subcategory', 'product_ratings'];
    foreach ($tables as $t) {
        echo " - Truncating `$t`...\n";
        flush();
        $db->table($t)->truncate();
    }
    
    echo "Re-enabling foreign keys...\n";
    flush();
    $db->query('SET FOREIGN_KEY_CHECKS = 1;');
    
    echo "Seeding Category Groups...\n";
    flush();
    $db->table('category_group')->insert([
        'id' => 1,
        'title' => 'Default Group',
        'created_at' => date('Y-m-d H:i:s')
    ]);
    
    echo "Seeding Categories...\n";
    flush();
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
    flush();
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
    flush();
    
    // Check files
    $importFile = WRITEPATH . 'import_products.json';
    echo "Checking import file at: $importFile\n";
    flush();
    if (!file_exists($importFile)) {
        throw new Exception("Import file not found!");
    }
    
    echo "Reading import file (Size: " . filesize($importFile) . " bytes)...\n";
    flush();
    $jsonData = file_get_contents($importFile);
    
    echo "Decoding JSON data...\n";
    flush();
    $products = json_decode($jsonData, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON format: " . json_last_error_msg());
    }
    
    echo "Loaded " . count($products) . " products from JSON.\n";
    flush();
    
    $classifySubcategory = function($productName, $categoryId) {
        $name = strtolower($productName);
        if ($categoryId == 1) { // Fruits & Vegetables
            if (strpos($name, 'herb') !== false || strpos($name, 'coriander') !== false || strpos($name, 'mint') !== false || strpos($name, 'ginger') !== false || strpos($name, 'garlic') !== false || strpos($name, 'chilli') !== false || strpos($name, 'lemon') !== false || strpos($name, 'dhaniya') !== false || strpos($name, 'adrak') !== false || strpos($name, 'lehsun') !== false) return 3;
            if (strpos($name, 'kiwi') !== false || strpos($name, 'imported') !== false || strpos($name, 'exotic') !== false || strpos($name, 'avocado') !== false || strpos($name, 'cherry') !== false || strpos($name, 'litchi') !== false) return 4;
            if (strpos($name, 'banana') !== false || strpos($name, 'apple') !== false || strpos($name, 'pomegranate') !== false || strpos($name, 'mango') !== false || strpos($name, 'orange') !== false || strpos($name, 'grapes') !== false || strpos($name, 'papaya') !== false || strpos($name, 'watermelon') !== false || strpos($name, 'melon') !== false || strpos($name, 'pineapple') !== false || strpos($name, 'pear') !== false || strpos($name, 'plum') !== false || strpos($name, 'peach') !== false || strpos($name, 'strawberry') !== false || strpos($name, 'guava') !== false || strpos($name, 'sapota') !== false || strpos($name, 'chiku') !== false) return 2;
            return 1;
        }
        if ($categoryId == 2) { // Dairy, Bread & Eggs
            if (strpos($name, 'milk') !== false) return 5;
            if (strpos($name, 'bread') !== false || strpos($name, 'pav') !== false || strpos($name, 'bun') !== false) return 6;
            if (strpos($name, 'egg') !== false) return 7;
            if (strpos($name, 'butter') !== false || strpos($name, 'ghee') !== false) return 8;
            if (strpos($name, 'cheese') !== false) return 9;
            if (strpos($name, 'paneer') !== false || strpos($name, 'tofu') !== false) return 10;
            if (strpos($name, 'curd') !== false || strpos($name, 'dahi') !== false || strpos($name, 'yogurt') !== false) return 11;
            return 12;
        }
        if ($categoryId == 3) { // Munchies & Snacks
            if (strpos($name, 'chips') !== false || strpos($name, 'lays') !== false || strpos($name, 'kurkure') !== false || strpos($name, 'pringles') !== false || strpos($name, 'nachos') !== false) return 13;
            if (strpos($name, 'namkeen') !== false || strpos($name, 'bhujia') !== false || strpos($name, 'sev') !== false || strpos($name, 'mixture') !== false || strpos($name, 'makhana') !== false || strpos($name, 'peanuts') !== false) return 14;
            if (strpos($name, 'sweet') !== false || strpos($name, 'chocolate') !== false || strpos($name, 'candy') !== false || strpos($name, 'lollipop') !== false || strpos($name, 'soan papdi') !== false || strpos($name, 'gulab jamun') !== false || strpos($name, 'rasgulla') !== false || strpos($name, 'bourn') !== false || strpos($name, 'cadbury') !== false || strpos($name, 'chewing gum') !== false) return 15;
            if (strpos($name, 'biscuit') !== false || strpos($name, 'cookie') !== false || strpos($name, 'parle') !== false || strpos($name, 'good day') !== false || strpos($name, 'oreo') !== false || strpos($name, 'bourbon') !== false || strpos($name, 'hide') !== false || strpos($name, 'marie') !== false || strpos($name, 'crack') !== false || strpos($name, 'krack') !== false || strpos($name, 'monaco') !== false || strpos($name, 'digestive') !== false) return 16;
            return 17;
        }
        if ($categoryId == 4) { // Cold Drinks & Juices
            if (strpos($name, 'coke') !== false || strpos($name, 'pepsi') !== false || strpos($name, 'sprite') !== false || strpos($name, 'thums') !== false || strpos($name, 'limca') !== false || strpos($name, 'fanta') !== false || strpos($name, 'soda') !== false) return 18;
            if (strpos($name, 'juice') !== false || strpos($name, 'maaza') !== false || strpos($name, 'slice') !== false || strpos($name, 'frooti') !== false || strpos($name, 'tropicana') !== false || strpos($name, 'drink') !== false || strpos($name, 'lassi') !== false || strpos($name, 'chaas') !== false || strpos($name, 'tang') !== false || strpos($name, 'rasna') !== false) return 19;
            if (strpos($name, 'tea') !== false || strpos($name, 'coffee') !== false || strpos($name, 'label') !== false || strpos($name, 'bru') !== false || strpos($name, 'tata') !== false || strpos($name, 'nescafe') !== false) return 20;
            return 21;
        }
        if ($categoryId == 5) { // Instant & Frozen Food
            if (strpos($name, 'noodle') !== false || strpos($name, 'maggi') !== false || strpos($name, 'yippee') !== false || strpos($name, 'ramen') !== false || strpos($name, 'pasta') !== false || strpos($name, 'soup') !== false) return 22;
            if (strpos($name, 'ketchup') !== false || strpos($name, 'sauce') !== false || strpos($name, 'jam') !== false || strpos($name, 'mayonnaise') !== false || strpos($name, 'spread') !== false || strpos($name, 'honey') !== false) return 23;
            if (strpos($name, 'ice cream') !== false || strpos($name, 'tub') !== false || strpos($name, 'dessert') !== false || strpos($name, 'cake') !== false || strpos($name, 'muffin') !== false || strpos($name, 'pastry') !== false) return 24;
            return 25;
        }
        if ($categoryId == 6) { // Atta, Rice & Dal
            if (strpos($name, 'atta') !== false || strpos($name, 'flour') !== false || strpos($name, 'besan') !== false || strpos($name, 'maida') !== false || strpos($name, 'suji') !== false || strpos($name, 'sattu') !== false) return 26;
            if (strpos($name, 'rice') !== false || strpos($name, 'basmati') !== false || strpos($name, 'poha') !== false) return 27;
            if (strpos($name, 'dal') !== false || strpos($name, 'pulse') !== false || strpos($name, 'chana') !== false || strpos($name, 'moong') !== false || strpos($name, 'toor') !== false || strpos($name, 'urad') !== false || strpos($name, 'masoor') !== false || strpos($name, 'rajma') !== false || strpos($name, 'chhole') !== false) return 28;
            if (strpos($name, 'oil') !== false || strpos($name, 'ghee') !== false || strpos($name, 'mustard') !== false || strpos($name, 'refined') !== false || strpos($name, 'olive') !== false) return 29;
            return 30;
        }
        if ($categoryId == 7) { // Cleaning & Home Essentials
            if (strpos($name, 'detergent') !== false || strpos($name, 'surf') !== false || strpos($name, 'ariel') !== false || strpos($name, 'tide') !== false || strpos($name, 'wheel') !== false || strpos($name, 'comfort') !== false) return 31;
            if (strpos($name, 'vim') !== false || strpos($name, 'pril') !== false || strpos($name, 'dishwash') !== false || strpos($name, 'cleaner') !== false || strpos($name, 'harpic') !== false || strpos($name, 'lizol') !== false || strpos($name, 'colin') !== false) return 32;
            if (strpos($name, 'repellent') !== false || strpos($name, 'hit') !== false || strpos($name, 'baygon') !== false || strpos($name, 'freshener') !== false || strpos($name, 'aer') !== false || strpos($name, 'candle') !== false || strpos($name, 'diffuser') !== false) return 33;
            if (strpos($name, 'notebook') !== false || strpos($name, 'diary') !== false || strpos($name, 'diaries') !== false || strpos($name, 'stationery') !== false || strpos($name, 'pen') !== false || strpos($name, 'game') !== false || strpos($name, 'toy') !== false) return 34;
            if (strpos($name, 'bulb') !== false || strpos($name, 'led') !== false || strpos($name, 'battery') !== false || strpos($name, 'duracell') !== false || strpos($name, 'trimmer') !== false || strpos($name, 'appliances') !== false) return 35;
            return 36;
        }
        if ($categoryId == 8) { // Personal Care & Wellness
            if (strpos($name, 'soap') !== false || strpos($name, 'body') !== false || strpos($name, 'shower') !== false || strpos($name, 'handwash') !== false) return 37;
            if (strpos($name, 'shampoo') !== false || strpos($name, 'hair') !== false || strpos($name, 'conditioner') !== false) return 38;
            if (strpos($name, 'face') !== false || strpos($name, 'cream') !== false || strpos($name, 'lotion') !== false || strpos($name, 'nivea') !== false || strpos($name, 'pond') !== false || strpos($name, 'vaseline') !== false || strpos($name, 'moisturizer') !== false) return 39;
            if (strpos($name, 'beauty') !== false || strpos($name, 'cosmetics') !== false || strpos($name, 'lipstick') !== false || strpos($name, 'gloss') !== false || strpos($name, 'liner') !== false || strpos($name, 'makeup') !== false) return 40;
            if (strpos($name, 'sanitary') !== false || strpos($name, 'pad') !== false || strpos($name, 'hygiene') !== false || strpos($name, 'panty') !== false || strpos($name, 'liner') !== false) return 41;
            if (strpos($name, 'baby') !== false || strpos($name, 'diaper') !== false || strpos($name, 'wipes') !== false || strpos($name, 'cerelac') !== false || strpos($name, 'johnson') !== false || strpos($name, 'pampers') !== false || strpos($name, 'huggies') !== false) return 42;
            if (strpos($name, 'health') !== false || strpos($name, 'pharma') !== false || strpos($name, 'pain') !== false || strpos($name, 'fever') !== false || strpos($name, 'crocin') !== false || strpos($name, 'calpol') !== false || strpos($name, 'spray') !== false || strpos($name, 'gel') !== false || strpos($name, 'sanitizer') !== false) return 43;
            return 44;
        }
        return 1;
    };

    $reviewsPool = [
        5 => [
            ['title' => 'Superb!', 'review' => 'Excellent quality product, very fresh and nicely packed.'],
            ['title' => 'Highly Recommended', 'review' => 'Extremely happy with the purchase. Standard quality matching expectations.'],
            ['title' => 'Brilliant', 'review' => 'Pure and authentic. Delivery was super fast within 10 minutes.'],
            ['title' => 'Perfect', 'review' => 'No complaints at all. Fresh packaging and good price.']
        ],
        4 => [
            ['title' => 'Good product', 'review' => 'Decent quality, packaging could be slightly better but product is fresh.'],
            ['title' => 'Satisfied', 'review' => 'Value for money. Good brand choice.'],
            ['title' => 'Worth it', 'review' => 'Overall good experience. Got it fresh and on time.']
        ],
        3 => [
            ['title' => 'Average', 'review' => 'Decent quality but not as fresh as expected.'],
            ['title' => 'Okay quality', 'review' => 'Average product. Delivery took slightly longer than 10 mins.']
        ]
    ];
    
    echo "Starting transaction for product inserts...\n";
    flush();
    $db->transStart();
    
    $successCount = 0;
    $ratingsBatch = [];
    $imagesBatch = [];
    $categoriesBatch = [];
    $subcategoriesBatch = [];
    
    foreach ($products as $item) {
        $productName = isset($item['product_name']) ? $item['product_name'] : 'Unnamed Product';
        $description = isset($item['description']) ? $item['description'] : '';
        
        $productName = str_ireplace('Blinkit', 'CityLoop', $productName);
        $description = str_ireplace('Blinkit', 'CityLoop', $description);
        
        $slug = url_title($productName, '-', true) . '-' . time() . '-' . rand(100, 999);
        
        $productData = [
            'brand_id'              => isset($item['brand_id']) ? $item['brand_id'] : 0,
            'seller_id'             => isset($item['seller_id']) ? $item['seller_id'] : 1,
            'tax_id'                => isset($item['tax_id']) ? $item['tax_id'] : 0,
            'product_name'          => $productName,
            'slug'                  => $slug,
            'main_img'              => isset($item['main_img']) ? $item['main_img'] : 'uploads/products/placeholder.png',
            'description'           => $description,
            'popular'               => 0,
            'deal_of_the_day'       => 0,
            'status'                => 1, // Published
            'is_delete'             => 0,
            'manufacturer'          => 'CityLoop Quality Farms Ltd',
            'made_in'               => 'India',
            'fssai_lic_no'          => '12724999000182',
            'return_days'           => 1,
            'is_returnable'         => 1,
            'date'                  => date('Y-m-d H:i:s')
        ];
        
        $db->table('product')->insert($productData);
        $productId = $db->insertID();
        
        if ($productId) {
            $variantData = [
                'product_id'         => $productId,
                'status'             => 1,
                'title'              => isset($item['unit']) ? $item['unit'] : '1 unit',
                'price'              => isset($item['price']) ? $item['price'] : 0.00,
                'discounted_price'   => isset($item['discounted_price']) ? $item['discounted_price'] : 0.00,
                'stock'              => 0, // Force stock to zero as requested
                'is_unlimited_stock' => 0,
                'is_delete'          => 0
            ];
            $db->table('product_variants')->insert($variantData);
            
            $categoryId = isset($item['category_id']) ? $item['category_id'] : 1;
            $categoriesBatch[] = [
                'product_id'  => $productId,
                'category_id' => $categoryId
            ];
            
            $subcatId = $classifySubcategory($productName, $categoryId);
            $subcategoriesBatch[] = [
                'product_id' => $productId,
                'subcategory_id' => $subcatId
            ];
            
            if (isset($item['images']) && is_array($item['images'])) {
                foreach ($item['images'] as $imgUrl) {
                    $imagesBatch[] = [
                        'product_id'          => $productId,
                        'product_variant_id'  => 0,
                        'image'               => $imgUrl
                    ];
                }
            }
            
            $numReviews = rand(3, 6);
            for ($i = 0; $i < $numReviews; $i++) {
                $star = rand(1, 10) <= 8 ? rand(4, 5) : 3;
                $tplList = $reviewsPool[$star];
                $tpl = $tplList[array_rand($tplList)];
                
                $ratingsBatch[] = [
                    'product_id' => $productId,
                    'user_id' => 0,
                    'order_id' => 0,
                    'rate' => $star,
                    'title' => $tpl['title'],
                    'review' => $tpl['review'],
                    'created_at' => date('Y-m-d H:i:s', time() - rand(0, 30) * 86400),
                    'is_approved_to_show' => 1,
                    'is_active' => 1,
                    'is_delete' => 0
                ];
            }
            
            $successCount++;
        }
    }
    
    echo "Inserting categories association batch...\n";
    flush();
    if (!empty($categoriesBatch)) {
        $db->table('product_categories')->insertBatch($categoriesBatch);
    }
    
    echo "Inserting subcategories association batch...\n";
    flush();
    if (!empty($subcategoriesBatch)) {
        $db->table('product_subcategories')->insertBatch($subcategoriesBatch);
    }
    
    echo "Inserting product images batch...\n";
    flush();
    if (!empty($imagesBatch)) {
        $db->table('product_images')->insertBatch($imagesBatch);
    }
    
    echo "Inserting ratings batch...\n";
    flush();
    if (!empty($ratingsBatch)) {
        $db->table('product_ratings')->insertBatch($ratingsBatch);
    }
    
    echo "Committing transaction...\n";
    flush();
    $db->transComplete();
    
    if ($db->transStatus() === FALSE) {
        throw new Exception("Transaction failed and was rolled back.");
    }
    
    echo "SUCCESS: Seeded {$successCount} products successfully!\n";
    flush();
    
} catch (Exception $e) {
    echo "EXCEPTION DETECTED:\n";
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    flush();
}
