<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductImportSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // 1. Disable foreign key checks to safely truncate tables
        $db->query('SET FOREIGN_KEY_CHECKS = 0;');
        
        echo "Clearing existing products, categories, subcategories, and ratings...\n";
        $db->table('product')->truncate();
        $db->table('product_variants')->truncate();
        $db->table('product_categories')->truncate();
        $db->table('product_subcategories')->truncate();
        $db->table('product_images')->truncate();
        $db->table('product_tag')->truncate();
        $db->table('product_taxes')->truncate();
        $db->table('category')->truncate();
        $db->table('category_group')->truncate();
        $db->table('subcategory')->truncate();
        $db->table('product_ratings')->truncate();
        
        $db->query('SET FOREIGN_KEY_CHECKS = 1;');
        
        // 2. Seed Category Group
        echo "Seeding Category Groups...\n";
        $db->table('category_group')->insert([
            'id' => 1,
            'title' => 'Default Group',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // 3. Seed Categories
        echo "Seeding Categories...\n";
        $categories = [
            [
                'id' => 1,
                'category_group_id' => 1,
                'row_order' => 1,
                'category_name' => 'Vegetables & Fruits',
                'slug' => 'vegetables-fruits',
                'category_img' => '',
                'is_bestseller_category' => 1
            ],
            [
                'id' => 2,
                'category_group_id' => 1,
                'row_order' => 2,
                'category_name' => 'Dairy, Bread & Eggs',
                'slug' => 'dairy-bread-eggs',
                'category_img' => '',
                'is_bestseller_category' => 1
            ],
            [
                'id' => 3,
                'category_group_id' => 1,
                'row_order' => 3,
                'category_name' => 'Munchies & Snacks',
                'slug' => 'munchies-snacks',
                'category_img' => '',
                'is_bestseller_category' => 1
            ],
            [
                'id' => 4,
                'category_group_id' => 1,
                'row_order' => 4,
                'category_name' => 'Cold Drinks & Juices',
                'slug' => 'cold-drinks-juices',
                'category_img' => '',
                'is_bestseller_category' => 1
            ],
            [
                'id' => 5,
                'category_group_id' => 1,
                'row_order' => 5,
                'category_name' => 'Instant & Frozen Food',
                'slug' => 'instant-frozen-food',
                'category_img' => '',
                'is_bestseller_category' => 0
            ],
            [
                'id' => 6,
                'category_group_id' => 1,
                'row_order' => 6,
                'category_name' => 'Atta, Rice & Dal',
                'slug' => 'atta-rice-dal',
                'category_img' => '',
                'is_bestseller_category' => 0
            ],
            [
                'id' => 7,
                'category_group_id' => 1,
                'row_order' => 7,
                'category_name' => 'Cleaning & Home Essentials',
                'slug' => 'cleaning-home-essentials',
                'category_img' => '',
                'is_bestseller_category' => 0
            ],
            [
                'id' => 8,
                'category_group_id' => 1,
                'row_order' => 8,
                'category_name' => 'Personal Care & Wellness',
                'slug' => 'personal-care-wellness',
                'category_img' => '',
                'is_bestseller_category' => 0
            ]
        ];
        $db->table('category')->insertBatch($categories);

        // 4. Seed Subcategories
        echo "Seeding Subcategories...\n";
        $subcategories = [
            // Cat 1: Vegetables & Fruits
            ['id' => 1, 'category_id' => 1, 'row_order' => 1, 'name' => 'Fresh Vegetables', 'slug' => 'fresh-vegetables', 'img' => ''],
            ['id' => 2, 'category_id' => 1, 'row_order' => 2, 'name' => 'Fresh Fruits', 'slug' => 'fresh-fruits', 'img' => ''],
            ['id' => 3, 'category_id' => 1, 'row_order' => 3, 'name' => 'Herbs & Seasonings', 'slug' => 'herbs-seasonings', 'img' => ''],
            ['id' => 4, 'category_id' => 1, 'row_order' => 4, 'name' => 'Exotic Produce', 'slug' => 'exotic-produce', 'img' => ''],

            // Cat 2: Dairy, Bread & Eggs
            ['id' => 5, 'category_id' => 2, 'row_order' => 1, 'name' => 'Milk', 'slug' => 'milk', 'img' => ''],
            ['id' => 6, 'category_id' => 2, 'row_order' => 2, 'name' => 'Bread & Pav', 'slug' => 'bread-pav', 'img' => ''],
            ['id' => 7, 'category_id' => 2, 'row_order' => 3, 'name' => 'Eggs', 'slug' => 'eggs', 'img' => ''],
            ['id' => 8, 'category_id' => 2, 'row_order' => 4, 'name' => 'Butter & Ghee', 'slug' => 'butter-ghee', 'img' => ''],
            ['id' => 9, 'category_id' => 2, 'row_order' => 5, 'name' => 'Cheese', 'slug' => 'cheese', 'img' => ''],
            ['id' => 10, 'category_id' => 2, 'row_order' => 6, 'name' => 'Paneer & Tofu', 'slug' => 'paneer-tofu', 'img' => ''],
            ['id' => 11, 'category_id' => 2, 'row_order' => 7, 'name' => 'Curd & Yogurt', 'slug' => 'curd-yogurt', 'img' => ''],
            ['id' => 12, 'category_id' => 2, 'row_order' => 8, 'name' => 'Chicken, Meat & Fish', 'slug' => 'chicken-meat-fish', 'img' => ''],

            // Cat 3: Munchies & Snacks
            ['id' => 13, 'category_id' => 3, 'row_order' => 1, 'name' => 'Chips & Wafers', 'slug' => 'chips-wafers', 'img' => ''],
            ['id' => 14, 'category_id' => 3, 'row_order' => 2, 'name' => 'Namkeen & Bhujia', 'slug' => 'namkeen-bhujia', 'img' => ''],
            ['id' => 15, 'category_id' => 3, 'row_order' => 3, 'name' => 'Sweets & Chocolates', 'slug' => 'sweets-chocolates', 'img' => ''],
            ['id' => 16, 'category_id' => 3, 'row_order' => 4, 'name' => 'Biscuits & Cookies', 'slug' => 'biscuits-cookies', 'img' => ''],
            ['id' => 17, 'category_id' => 3, 'row_order' => 5, 'name' => 'Dry Fruits, Nuts & Seeds', 'slug' => 'dry-fruits-nuts-seeds', 'img' => ''],

            // Cat 4: Cold Drinks & Juices
            ['id' => 18, 'category_id' => 4, 'row_order' => 1, 'name' => 'Soft Drinks & Soda', 'slug' => 'soft-drinks-soda', 'img' => ''],
            ['id' => 19, 'category_id' => 4, 'row_order' => 2, 'name' => 'Juices & Fruit Drinks', 'slug' => 'juices-fruit-drinks', 'img' => ''],
            ['id' => 20, 'category_id' => 4, 'row_order' => 3, 'name' => 'Tea & Coffee', 'slug' => 'tea-coffee', 'img' => ''],
            ['id' => 21, 'category_id' => 4, 'row_order' => 4, 'name' => 'Energy Drinks & Water', 'slug' => 'energy-drinks-water', 'img' => ''],

            // Cat 5: Instant & Frozen Food
            ['id' => 22, 'category_id' => 5, 'row_order' => 1, 'name' => 'Noodles, Pasta & Soup', 'slug' => 'noodles-pasta-soup', 'img' => ''],
            ['id' => 23, 'category_id' => 5, 'row_order' => 2, 'name' => 'Sauces & Spreads', 'slug' => 'sauces-spreads', 'img' => ''],
            ['id' => 24, 'category_id' => 5, 'row_order' => 3, 'name' => 'Ice Cream & Desserts', 'slug' => 'ice-cream-desserts', 'img' => ''],
            ['id' => 25, 'category_id' => 5, 'row_order' => 4, 'name' => 'Ready to Eat & Frozen Snacks', 'slug' => 'ready-to-eat-frozen-snacks', 'img' => ''],

            // Cat 6: Atta, Rice & Dal
            ['id' => 26, 'category_id' => 6, 'row_order' => 1, 'name' => 'Atta & Flours', 'slug' => 'atta-flours', 'img' => ''],
            ['id' => 27, 'category_id' => 6, 'row_order' => 2, 'name' => 'Rice & Rice Products', 'slug' => 'rice-rice-products', 'img' => ''],
            ['id' => 28, 'category_id' => 6, 'row_order' => 3, 'name' => 'Dals & Pulses', 'slug' => 'dals-pulses', 'img' => ''],
            ['id' => 29, 'category_id' => 6, 'row_order' => 4, 'name' => 'Cooking Oils & Ghee', 'slug' => 'cooking-oils-ghee', 'img' => ''],
            ['id' => 30, 'category_id' => 6, 'row_order' => 5, 'name' => 'Spices & Masalas', 'slug' => 'spices-masalas', 'img' => ''],

            // Cat 7: Cleaning & Home Essentials
            ['id' => 31, 'category_id' => 7, 'row_order' => 1, 'name' => 'Detergents & Fabric Care', 'slug' => 'detergents-fabric-care', 'img' => ''],
            ['id' => 32, 'category_id' => 7, 'row_order' => 2, 'name' => 'Dishwashers & Cleaners', 'slug' => 'dishwashers-cleaners', 'img' => ''],
            ['id' => 33, 'category_id' => 7, 'row_order' => 3, 'name' => 'Repellents & Fresheners', 'slug' => 'repellents-fresheners', 'img' => ''],
            ['id' => 34, 'category_id' => 7, 'row_order' => 4, 'name' => 'Stationery, Games & Toys', 'slug' => 'stationery-games-toys', 'img' => ''],
            ['id' => 35, 'category_id' => 7, 'row_order' => 5, 'name' => 'Electronics & Batteries', 'slug' => 'electronics-batteries', 'img' => ''],
            ['id' => 36, 'category_id' => 7, 'row_order' => 6, 'name' => 'Home & Lifestyle', 'slug' => 'home-lifestyle-sub', 'img' => ''],

            // Cat 8: Personal Care & Wellness
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

        // Helper function to map category_id + product_name to subcategory_id
        $classifySubcategory = function($productName, $categoryId) {
            $name = strtolower($productName);
            if ($categoryId == 1) { // Fruits & Vegetables
                if (strpos($name, 'herb') !== false || strpos($name, 'coriander') !== false || strpos($name, 'mint') !== false || strpos($name, 'ginger') !== false || strpos($name, 'garlic') !== false || strpos($name, 'chilli') !== false || strpos($name, 'lemon') !== false || strpos($name, 'dhaniya') !== false || strpos($name, 'adrak') !== false || strpos($name, 'lehsun') !== false) {
                    return 3;
                }
                if (strpos($name, 'kiwi') !== false || strpos($name, 'imported') !== false || strpos($name, 'exotic') !== false || strpos($name, 'avocado') !== false || strpos($name, 'cherry') !== false || strpos($name, 'litchi') !== false) {
                    return 4;
                }
                if (strpos($name, 'banana') !== false || strpos($name, 'apple') !== false || strpos($name, 'pomegranate') !== false || strpos($name, 'mango') !== false || strpos($name, 'orange') !== false || strpos($name, 'grapes') !== false || strpos($name, 'papaya') !== false || strpos($name, 'watermelon') !== false || strpos($name, 'melon') !== false || strpos($name, 'pineapple') !== false || strpos($name, 'pear') !== false || strpos($name, 'plum') !== false || strpos($name, 'peach') !== false || strpos($name, 'strawberry') !== false || strpos($name, 'guava') !== false || strpos($name, 'sapota') !== false || strpos($name, 'chiku') !== false) {
                    return 2;
                }
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
                return 12; // Chicken, Meat & Fish
            }
            if ($categoryId == 3) { // Munchies & Snacks
                if (strpos($name, 'chips') !== false || strpos($name, 'lays') !== false || strpos($name, 'kurkure') !== false || strpos($name, 'pringles') !== false || strpos($name, 'nachos') !== false) return 13;
                if (strpos($name, 'namkeen') !== false || strpos($name, 'bhujia') !== false || strpos($name, 'sev') !== false || strpos($name, 'mixture') !== false || strpos($name, 'makhana') !== false || strpos($name, 'peanuts') !== false) return 14;
                if (strpos($name, 'sweet') !== false || strpos($name, 'chocolate') !== false || strpos($name, 'candy') !== false || strpos($name, 'lollipop') !== false || strpos($name, 'soan papdi') !== false || strpos($name, 'gulab jamun') !== false || strpos($name, 'rasgulla') !== false || strpos($name, 'bourn') !== false || strpos($name, 'cadbury') !== false || strpos($name, 'chewing gum') !== false) return 15;
                if (strpos($name, 'biscuit') !== false || strpos($name, 'cookie') !== false || strpos($name, 'parle') !== false || strpos($name, 'good day') !== false || strpos($name, 'oreo') !== false || strpos($name, 'bourbon') !== false || strpos($name, 'hide') !== false || strpos($name, 'marie') !== false || strpos($name, 'crack') !== false || strpos($name, 'krack') !== false || strpos($name, 'monaco') !== false || strpos($name, 'digestive') !== false) return 16;
                return 17; // Dry Fruits
            }
            if ($categoryId == 4) { // Cold Drinks & Juices
                if (strpos($name, 'coke') !== false || strpos($name, 'pepsi') !== false || strpos($name, 'sprite') !== false || strpos($name, 'thums') !== false || strpos($name, 'limca') !== false || strpos($name, 'fanta') !== false || strpos($name, 'soda') !== false) return 18;
                if (strpos($name, 'juice') !== false || strpos($name, 'maaza') !== false || strpos($name, 'slice') !== false || strpos($name, 'frooti') !== false || strpos($name, 'tropicana') !== false || strpos($name, 'drink') !== false || strpos($name, 'lassi') !== false || strpos($name, 'chaas') !== false || strpos($name, 'tang') !== false || strpos($name, 'rasna') !== false) return 19;
                if (strpos($name, 'tea') !== false || strpos($name, 'coffee') !== false || strpos($name, 'label') !== false || strpos($name, 'bru') !== false || strpos($name, 'tata') !== false || strpos($name, 'nescafe') !== false) return 20;
                return 21; // Energy Drinks
            }
            if ($categoryId == 5) { // Instant & Frozen Food
                if (strpos($name, 'noodle') !== false || strpos($name, 'maggi') !== false || strpos($name, 'yippee') !== false || strpos($name, 'ramen') !== false || strpos($name, 'pasta') !== false || strpos($name, 'soup') !== false) return 22;
                if (strpos($name, 'ketchup') !== false || strpos($name, 'sauce') !== false || strpos($name, 'jam') !== false || strpos($name, 'mayonnaise') !== false || strpos($name, 'spread') !== false || strpos($name, 'honey') !== false) return 23;
                if (strpos($name, 'ice cream') !== false || strpos($name, 'tub') !== false || strpos($name, 'dessert') !== false || strpos($name, 'cake') !== false || strpos($name, 'muffin') !== false || strpos($name, 'pastry') !== false) return 24;
                return 25; // Ready to Eat & Frozen
            }
            if ($categoryId == 6) { // Atta, Rice & Dal
                if (strpos($name, 'atta') !== false || strpos($name, 'flour') !== false || strpos($name, 'besan') !== false || strpos($name, 'maida') !== false || strpos($name, 'suji') !== false || strpos($name, 'sattu') !== false) return 26;
                if (strpos($name, 'rice') !== false || strpos($name, 'basmati') !== false || strpos($name, 'poha') !== false) return 27;
                if (strpos($name, 'dal') !== false || strpos($name, 'pulse') !== false || strpos($name, 'chana') !== false || strpos($name, 'moong') !== false || strpos($name, 'toor') !== false || strpos($name, 'urad') !== false || strpos($name, 'masoor') !== false || strpos($name, 'rajma') !== false || strpos($name, 'chhole') !== false) return 28;
                if (strpos($name, 'oil') !== false || strpos($name, 'ghee') !== false || strpos($name, 'mustard') !== false || strpos($name, 'refined') !== false || strpos($name, 'olive') !== false) return 29;
                return 30; // Spices & Masalas
            }
            if ($categoryId == 7) { // Cleaning & Home Essentials
                if (strpos($name, 'detergent') !== false || strpos($name, 'surf') !== false || strpos($name, 'ariel') !== false || strpos($name, 'tide') !== false || strpos($name, 'wheel') !== false || strpos($name, 'comfort') !== false) return 31;
                if (strpos($name, 'vim') !== false || strpos($name, 'pril') !== false || strpos($name, 'dishwash') !== false || strpos($name, 'cleaner') !== false || strpos($name, 'harpic') !== false || strpos($name, 'lizol') !== false || strpos($name, 'colin') !== false) return 32;
                if (strpos($name, 'repellent') !== false || strpos($name, 'hit') !== false || strpos($name, 'baygon') !== false || strpos($name, 'freshener') !== false || strpos($name, 'aer') !== false || strpos($name, 'candle') !== false || strpos($name, 'diffuser') !== false) return 33;
                if (strpos($name, 'notebook') !== false || strpos($name, 'diary') !== false || strpos($name, 'diaries') !== false || strpos($name, 'stationery') !== false || strpos($name, 'pen') !== false || strpos($name, 'game') !== false || strpos($name, 'toy') !== false) return 34;
                if (strpos($name, 'bulb') !== false || strpos($name, 'led') !== false || strpos($name, 'battery') !== false || strpos($name, 'duracell') !== false || strpos($name, 'trimmer') !== false || strpos($name, 'appliances') !== false) return 35;
                return 36; // Home & Lifestyle
            }
            if ($categoryId == 8) { // Personal Care & Wellness
                if (strpos($name, 'soap') !== false || strpos($name, 'body') !== false || strpos($name, 'shower') !== false || strpos($name, 'handwash') !== false) return 37;
                if (strpos($name, 'shampoo') !== false || strpos($name, 'hair') !== false || strpos($name, 'conditioner') !== false) return 38;
                if (strpos($name, 'face') !== false || strpos($name, 'cream') !== false || strpos($name, 'lotion') !== false || strpos($name, 'nivea') !== false || strpos($name, 'pond') !== false || strpos($name, 'vaseline') !== false || strpos($name, 'moisturizer') !== false) return 39;
                if (strpos($name, 'beauty') !== false || strpos($name, 'cosmetics') !== false || strpos($name, 'lipstick') !== false || strpos($name, 'gloss') !== false || strpos($name, 'liner') !== false || strpos($name, 'makeup') !== false) return 40;
                if (strpos($name, 'sanitary') !== false || strpos($name, 'pad') !== false || strpos($name, 'hygiene') !== false || strpos($name, 'panty') !== false || strpos($name, 'liner') !== false) return 41;
                if (strpos($name, 'baby') !== false || strpos($name, 'diaper') !== false || strpos($name, 'wipes') !== false || strpos($name, 'cerelac') !== false || strpos($name, 'johnson') !== false || strpos($name, 'pampers') !== false || strpos($name, 'huggies') !== false) return 42;
                if (strpos($name, 'health') !== false || strpos($name, 'pharma') !== false || strpos($name, 'pain') !== false || strpos($name, 'fever') !== false || strpos($name, 'crocin') !== false || strpos($name, 'calpol') !== false || strpos($name, 'spray') !== false || strpos($name, 'gel') !== false || strpos($name, 'sanitizer') !== false) return 43;
                return 44; // Sexual Wellness
            }
            return 1;
        };

        // Reviews templates pool for generating ratings
        $reviewsPool = [
            5 => [
                ['title' => 'Excellent quality!', 'review' => 'Very fresh and high quality product. Delivery was super fast.'],
                ['title' => 'Highly recommended', 'review' => 'Great value for money, absolutely fresh and clean packaging.'],
                ['title' => 'Superb!', 'review' => 'Tastes great and fresh. Will definitely order again.'],
                ['title' => 'Best in class', 'review' => 'Exactly as described, perfect packaging and top-notch quality.'],
                ['title' => 'Highly fresh', 'review' => 'Extremely fresh and delicious. Fully satisfied with the purchase.']
            ],
            4 => [
                ['title' => 'Good product', 'review' => 'Nice quality and fresh. Package was neat and clean.'],
                ['title' => 'Worth buying', 'review' => 'Good quality, but price is slightly on the higher side.'],
                ['title' => 'Very satisfied', 'review' => 'Product was good and delivered on time.'],
                ['title' => 'Nice quality', 'review' => 'Fresh and good for daily cooking. Recommended.']
            ],
            3 => [
                ['title' => 'Average', 'review' => 'Decent quality but not as fresh as expected.'],
                ['title' => 'Okay quality', 'review' => 'Average product. Delivery took slightly longer than 10 mins.']
            ]
        ];

        // 5. Locate the import JSON data file
        $importFile = WRITEPATH . 'import_products.json';
        if (!file_exists($importFile)) {
            echo "Error: Import file not found at {$importFile}\n";
            return;
        }
        
        $jsonData = file_get_contents($importFile);
        $products = json_decode($jsonData, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "Error: Invalid JSON format in {$importFile}\n";
            return;
        }
        
        echo "Importing " . count($products) . " products...\n";
        
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
            
            // Insert Product
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
                // Insert Variant
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
                
                // Category association
                $categoryId = isset($item['category_id']) ? $item['category_id'] : 1;
                $categoriesBatch[] = [
                    'product_id'  => $productId,
                    'category_id' => $categoryId
                ];

                // Subcategory classification
                $subcatId = $classifySubcategory($productName, $categoryId);
                $subcategoriesBatch[] = [
                    'product_id' => $productId,
                    'subcategory_id' => $subcatId
                ];
                
                // Link Multiple Images
                if (isset($item['images']) && is_array($item['images'])) {
                    foreach ($item['images'] as $imgUrl) {
                        $imagesBatch[] = [
                            'product_id'          => $productId,
                            'product_variant_id'  => 0,
                            'image'               => $imgUrl
                        ];
                    }
                }
                
                // Ratings seeding (Generate 3-6 reviews per product)
                $numReviews = rand(3, 6);
                for ($i = 0; $i < $numReviews; $i++) {
                    // Weighted star generation (80% 5 or 4 stars, 20% 3 stars)
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

        // Insert batches
        if (!empty($categoriesBatch)) {
            $db->table('product_categories')->insertBatch($categoriesBatch);
        }
        if (!empty($subcategoriesBatch)) {
            $db->table('product_subcategories')->insertBatch($subcategoriesBatch);
        }
        if (!empty($imagesBatch)) {
            $db->table('product_images')->insertBatch($imagesBatch);
        }
        if (!empty($ratingsBatch)) {
            $db->table('product_ratings')->insertBatch($ratingsBatch);
        }
        
        echo "Successfully imported {$successCount} products, categories, subcategories, and ratings!\n";
    }
}
