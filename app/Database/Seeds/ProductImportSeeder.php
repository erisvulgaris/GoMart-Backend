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
            ['id' => 1, 'category_group_id' => 1, 'row_order' => 1, 'category_name' => 'Vegetables & Fruits', 'slug' => 'vegetables-fruits', 'category_img' => '', 'is_bestseller_category' => 1],
            ['id' => 2, 'category_group_id' => 1, 'row_order' => 2, 'category_name' => 'Dairy, Bread & Eggs', 'slug' => 'dairy-bread-eggs', 'category_img' => '', 'is_bestseller_category' => 1],
            ['id' => 3, 'category_group_id' => 1, 'row_order' => 3, 'category_name' => 'Munchies & Snacks', 'slug' => 'munchies-snacks', 'category_img' => '', 'is_bestseller_category' => 1],
            ['id' => 4, 'category_group_id' => 1, 'row_order' => 4, 'category_name' => 'Bakery & Biscuits', 'slug' => 'bakery-biscuits', 'category_img' => '', 'is_bestseller_category' => 1],
            ['id' => 5, 'category_group_id' => 1, 'row_order' => 5, 'category_name' => 'Cold Drinks & Juices', 'slug' => 'cold-drinks-juices', 'category_img' => '', 'is_bestseller_category' => 1],
            ['id' => 6, 'category_group_id' => 1, 'row_order' => 6, 'category_name' => 'Tea, Coffee & Health Drinks', 'slug' => 'tea-coffee-health-drinks', 'category_img' => '', 'is_bestseller_category' => 0],
            ['id' => 7, 'category_group_id' => 1, 'row_order' => 7, 'category_name' => 'Instant & Frozen Food', 'slug' => 'instant-frozen-food', 'category_img' => '', 'is_bestseller_category' => 0],
            ['id' => 8, 'category_group_id' => 1, 'row_order' => 8, 'category_name' => 'Atta, Rice & Dal', 'slug' => 'atta-rice-dal', 'category_img' => '', 'is_bestseller_category' => 0],
            ['id' => 9, 'category_group_id' => 1, 'row_order' => 9, 'category_name' => 'Chicken, Meat & Fish', 'slug' => 'chicken-meat-fish', 'category_img' => '', 'is_bestseller_category' => 0],
            ['id' => 10, 'category_group_id' => 1, 'row_order' => 10, 'category_name' => 'Cleaning & Household', 'slug' => 'cleaning-household', 'category_img' => '', 'is_bestseller_category' => 0],
            ['id' => 11, 'category_group_id' => 1, 'row_order' => 11, 'category_name' => 'Personal Care', 'slug' => 'personal-care', 'category_img' => '', 'is_bestseller_category' => 0],
            ['id' => 12, 'category_group_id' => 1, 'row_order' => 12, 'category_name' => 'Feminine Hygiene & Care', 'slug' => 'feminine-hygiene-care', 'category_img' => '', 'is_bestseller_category' => 0],
            ['id' => 13, 'category_group_id' => 1, 'row_order' => 13, 'category_name' => 'Baby Care', 'slug' => 'baby-care', 'category_img' => '', 'is_bestseller_category' => 0],
            ['id' => 14, 'category_group_id' => 1, 'row_order' => 14, 'category_name' => 'Pharma & Wellness', 'slug' => 'pharma-wellness', 'category_img' => '', 'is_bestseller_category' => 0],
            ['id' => 15, 'category_group_id' => 1, 'row_order' => 15, 'category_name' => 'Sexual Wellness', 'slug' => 'sexual-wellness', 'category_img' => '', 'is_bestseller_category' => 0],
            ['id' => 16, 'category_group_id' => 1, 'row_order' => 16, 'category_name' => 'Home & Kitchen', 'slug' => 'home-kitchen', 'category_img' => '', 'is_bestseller_category' => 0]
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
            ['id' => 6, 'category_id' => 2, 'row_order' => 2, 'name' => 'Butter & Ghee', 'slug' => 'butter-ghee', 'img' => ''],
            ['id' => 7, 'category_id' => 2, 'row_order' => 3, 'name' => 'Cheese', 'slug' => 'cheese', 'img' => ''],
            ['id' => 8, 'category_id' => 2, 'row_order' => 4, 'name' => 'Paneer & Tofu', 'slug' => 'paneer-tofu', 'img' => ''],
            ['id' => 9, 'category_id' => 2, 'row_order' => 5, 'name' => 'Curd & Yogurt', 'slug' => 'curd-yogurt', 'img' => ''],
            ['id' => 10, 'category_id' => 2, 'row_order' => 6, 'name' => 'Eggs', 'slug' => 'eggs', 'img' => ''],

            // Cat 3: Munchies & Snacks
            ['id' => 11, 'category_id' => 3, 'row_order' => 1, 'name' => 'Chips & Wafers', 'slug' => 'chips-wafers', 'img' => ''],
            ['id' => 12, 'category_id' => 3, 'row_order' => 2, 'name' => 'Namkeen & Bhujia', 'slug' => 'namkeen-bhujia', 'img' => ''],
            ['id' => 13, 'category_id' => 3, 'row_order' => 3, 'name' => 'Sweets & Chocolates', 'slug' => 'sweets-chocolates', 'img' => ''],
            ['id' => 14, 'category_id' => 3, 'row_order' => 4, 'name' => 'Dry Fruits, Nuts & Seeds', 'slug' => 'dry-fruits-nuts-seeds', 'img' => ''],
            ['id' => 15, 'category_id' => 3, 'row_order' => 5, 'name' => 'Popcorn & Puffs', 'slug' => 'popcorn-puffs', 'img' => ''],

            // Cat 4: Bakery & Biscuits
            ['id' => 16, 'category_id' => 4, 'row_order' => 1, 'name' => 'Biscuits & Cookies', 'slug' => 'biscuits-cookies', 'img' => ''],
            ['id' => 17, 'category_id' => 4, 'row_order' => 2, 'name' => 'Breads & Buns', 'slug' => 'breads-buns', 'img' => ''],
            ['id' => 18, 'category_id' => 4, 'row_order' => 3, 'name' => 'Rusk & Khari', 'slug' => 'rusk-khari', 'img' => ''],
            ['id' => 19, 'category_id' => 4, 'row_order' => 4, 'name' => 'Cakes & Muffins', 'slug' => 'cakes-muffins', 'img' => ''],

            // Cat 5: Cold Drinks & Juices
            ['id' => 20, 'category_id' => 5, 'row_order' => 1, 'name' => 'Soft Drinks & Soda', 'slug' => 'soft-drinks-soda', 'img' => ''],
            ['id' => 21, 'category_id' => 5, 'row_order' => 2, 'name' => 'Juices & Fruit Drinks', 'slug' => 'juices-fruit-drinks', 'img' => ''],
            ['id' => 22, 'category_id' => 5, 'row_order' => 3, 'name' => 'Energy Drinks', 'slug' => 'energy-drinks', 'img' => ''],
            ['id' => 23, 'category_id' => 5, 'row_order' => 4, 'name' => 'Water & Mixers', 'slug' => 'water-mixers', 'img' => ''],

            // Cat 6: Tea, Coffee & Health Drinks
            ['id' => 24, 'category_id' => 6, 'row_order' => 1, 'name' => 'Tea Bags & Leaf', 'slug' => 'tea-bags-leaf', 'img' => ''],
            ['id' => 25, 'category_id' => 6, 'row_order' => 2, 'name' => 'Instant Coffee', 'slug' => 'instant-coffee', 'img' => ''],
            ['id' => 26, 'category_id' => 6, 'row_order' => 3, 'name' => 'Milk Drinks & Mixes', 'slug' => 'milk-drinks-mixes', 'img' => ''],
            ['id' => 27, 'category_id' => 6, 'row_order' => 4, 'name' => 'Green & Herbal Tea', 'slug' => 'green-herbal-tea', 'img' => ''],

            // Cat 7: Instant & Frozen Food
            ['id' => 28, 'category_id' => 7, 'row_order' => 1, 'name' => 'Noodles & Cup Noodles', 'slug' => 'noodles-cup-noodles', 'img' => ''],
            ['id' => 29, 'category_id' => 7, 'row_order' => 2, 'name' => 'Pasta & Vermicelli', 'slug' => 'pasta-vermicelli', 'img' => ''],
            ['id' => 30, 'category_id' => 7, 'row_order' => 3, 'name' => 'Soups & Ready Meals', 'slug' => 'soups-ready-meals', 'img' => ''],
            ['id' => 31, 'category_id' => 7, 'row_order' => 4, 'name' => 'Frozen Snacks & Veggies', 'slug' => 'frozen-snacks-veggies', 'img' => ''],
            ['id' => 32, 'category_id' => 7, 'row_order' => 5, 'name' => 'Sauces, Ketchup & Spreads', 'slug' => 'sauces-ketchup-spreads', 'img' => ''],
            ['id' => 33, 'category_id' => 7, 'row_order' => 6, 'name' => 'Honey & Jams', 'slug' => 'honey-jams', 'img' => ''],

            // Cat 8: Atta, Rice & Dal
            ['id' => 34, 'category_id' => 8, 'row_order' => 1, 'name' => 'Atta & Flours', 'slug' => 'atta-flours', 'img' => ''],
            ['id' => 35, 'category_id' => 8, 'row_order' => 2, 'name' => 'Rice & Rice Products', 'slug' => 'rice-rice-products', 'img' => ''],
            ['id' => 36, 'category_id' => 8, 'row_order' => 3, 'name' => 'Dals & Pulses', 'slug' => 'dals-pulses', 'img' => ''],
            ['id' => 37, 'category_id' => 8, 'row_order' => 4, 'name' => 'Cooking Oils & Ghee', 'slug' => 'cooking-oils-ghee', 'img' => ''],
            ['id' => 38, 'category_id' => 8, 'row_order' => 5, 'name' => 'Spices & Masalas', 'slug' => 'spices-masalas', 'img' => ''],
            ['id' => 39, 'category_id' => 8, 'row_order' => 6, 'name' => 'Salt, Sugar & Jaggery', 'slug' => 'salt-sugar-jaggery', 'img' => ''],

            // Cat 9: Chicken, Meat & Fish
            ['id' => 40, 'category_id' => 9, 'row_order' => 1, 'name' => 'Fresh Chicken', 'slug' => 'fresh-chicken', 'img' => ''],
            ['id' => 41, 'category_id' => 9, 'row_order' => 2, 'name' => 'Fresh Mutton', 'slug' => 'fresh-mutton', 'img' => ''],
            ['id' => 42, 'category_id' => 9, 'row_order' => 3, 'name' => 'Fish & Seafood', 'slug' => 'fish-seafood', 'img' => ''],
            ['id' => 43, 'category_id' => 9, 'row_order' => 4, 'name' => 'Eggs & Cold Cuts', 'slug' => 'eggs-cold-cuts', 'img' => ''],

            // Cat 10: Cleaning & Household
            ['id' => 44, 'category_id' => 10, 'row_order' => 1, 'name' => 'Detergents & Fabric Care', 'slug' => 'detergents-fabric-care', 'img' => ''],
            ['id' => 45, 'category_id' => 10, 'row_order' => 2, 'name' => 'Dishwashers & Cleaners', 'slug' => 'dishwashers-cleaners', 'img' => ''],
            ['id' => 46, 'category_id' => 10, 'row_order' => 3, 'name' => 'Toilet & Bathroom Cleaners', 'slug' => 'toilet-bathroom-cleaners', 'img' => ''],
            ['id' => 47, 'category_id' => 10, 'row_order' => 4, 'name' => 'Trash Bags & Kitchen Needs', 'slug' => 'trash-bags-kitchen-needs', 'img' => ''],
            ['id' => 48, 'category_id' => 10, 'row_order' => 5, 'name' => 'Repellents & Air Fresheners', 'slug' => 'repellents-air-fresheners', 'img' => ''],

            // Cat 11: Personal Care
            ['id' => 49, 'category_id' => 11, 'row_order' => 1, 'name' => 'Bath & Body Soaps', 'slug' => 'bath-body-soaps', 'img' => ''],
            ['id' => 50, 'category_id' => 11, 'row_order' => 2, 'name' => 'Shampoos & Conditioners', 'slug' => 'shampoos-conditioners', 'img' => ''],
            ['id' => 51, 'category_id' => 11, 'row_order' => 3, 'name' => 'Hair Oils & Styling', 'slug' => 'hair-oils-styling', 'img' => ''],
            ['id' => 52, 'category_id' => 11, 'row_order' => 4, 'name' => 'Facewash & Skin Care', 'slug' => 'facewash-skin-care', 'img' => ''],
            ['id' => 53, 'category_id' => 11, 'row_order' => 5, 'name' => 'Deodorants & Perfumes', 'slug' => 'deodorants-perfumes', 'img' => ''],
            ['id' => 54, 'category_id' => 11, 'row_order' => 6, 'name' => 'Oral Care (Toothpaste & Brushes)', 'slug' => 'oral-care-toothpaste-brushes', 'img' => ''],

            // Cat 12: Feminine Hygiene & Care
            ['id' => 55, 'category_id' => 12, 'row_order' => 1, 'name' => 'Sanitary Pads & Liners', 'slug' => 'sanitary-pads-liners', 'img' => ''],
            ['id' => 56, 'category_id' => 12, 'row_order' => 2, 'name' => 'Intimate Care & Hygiene', 'slug' => 'intimate-care-hygiene', 'img' => ''],

            // Cat 13: Baby Care
            ['id' => 57, 'category_id' => 13, 'row_order' => 1, 'name' => 'Baby Diapers & Wipes', 'slug' => 'baby-diapers-wipes', 'img' => ''],
            ['id' => 58, 'category_id' => 13, 'row_order' => 2, 'name' => 'Baby Food & Formula', 'slug' => 'baby-food-formula', 'img' => ''],
            ['id' => 59, 'category_id' => 13, 'row_order' => 3, 'name' => 'Baby Bath & Skin Care', 'slug' => 'baby-bath-skin-care', 'img' => ''],

            // Cat 14: Pharma & Wellness
            ['id' => 60, 'category_id' => 14, 'row_order' => 1, 'name' => 'Pain Relief & Bandages', 'slug' => 'pain-relief-bandages', 'img' => ''],
            ['id' => 61, 'category_id' => 14, 'row_order' => 2, 'name' => 'Digestives & Antacids', 'slug' => 'digestives-antacids', 'img' => ''],
            ['id' => 62, 'category_id' => 14, 'row_order' => 3, 'name' => 'Cough, Cold & Immunity', 'slug' => 'cough-cold-immunity', 'img' => ''],
            ['id' => 63, 'category_id' => 14, 'row_order' => 4, 'name' => 'Sanitizers & Masks', 'slug' => 'sanitizers-masks', 'img' => ''],

            // Cat 15: Sexual Wellness
            ['id' => 64, 'category_id' => 15, 'row_order' => 1, 'name' => 'Condoms', 'slug' => 'condoms', 'img' => ''],
            ['id' => 65, 'category_id' => 15, 'row_order' => 2, 'name' => 'Lubricants & Massagers', 'slug' => 'lubricants-massagers', 'img' => ''],

            // Cat 16: Home & Kitchen
            ['id' => 66, 'category_id' => 16, 'row_order' => 1, 'name' => 'Batteries & Bulbs', 'slug' => 'batteries-bulbs', 'img' => ''],
            ['id' => 67, 'category_id' => 16, 'row_order' => 2, 'name' => 'Stationery & Office Needs', 'slug' => 'stationery-office-needs', 'img' => ''],
            ['id' => 68, 'category_id' => 16, 'row_order' => 3, 'name' => 'Toys & Party Needs', 'slug' => 'toys-party-needs', 'img' => ''],
            ['id' => 69, 'category_id' => 16, 'row_order' => 4, 'name' => 'Kitchen Tools & Cookware', 'slug' => 'kitchen-tools-cookware', 'img' => '']
        ];
        $db->table('subcategory')->insertBatch($subcategories);

        $containsAny = function($str, array $keywords) {
            foreach ($keywords as $kw) {
                if (stripos($str, $kw) !== false) {
                    return true;
                }
            }
            return false;
        };

        // Helper function to map product name to category_id + subcategory_id
        $classifyProduct = function($productName, $origCategoryId) use ($containsAny) {
            $name = strtolower($productName);

            // 15. Sexual Wellness
            if ($containsAny($name, ['condom', 'lubricant', 'massager', 'sleeve storker', 'intimacy', 'vibrator', 'pleasure', 'skore'])) {
                if (stripos($name, 'condom') !== false) {
                    return ['category_id' => 15, 'subcategory_id' => 64];
                }
                return ['category_id' => 15, 'subcategory_id' => 65];
            }

            // 13. Baby Care
            if ($containsAny($name, ['diaper', 'wipes', 'cerelac', 'johnson', 'pampers', 'huggies', 'baby', 'nestum'])) {
                if ($containsAny($name, ['diaper', 'pampers', 'huggies'])) {
                    return ['category_id' => 13, 'subcategory_id' => 57];
                }
                if ($containsAny($name, ['cerelac', 'nestum', 'formula', 'infant'])) {
                    return ['category_id' => 13, 'subcategory_id' => 58];
                }
                return ['category_id' => 13, 'subcategory_id' => 59];
            }

            // 12. Feminine Hygiene & Care
            if ($containsAny($name, ['whisper', 'stayfree', 'sanitary', 'pad', 'panty', 'softex', 'sofy', 'pee safe'])) {
                return ['category_id' => 12, 'subcategory_id' => 55];
            }

            // 9. Chicken, Meat & Fish
            if ($containsAny($name, ['chicken', 'mutton', 'fish', 'prawns', 'seafood', 'salami', 'sausage', 'bacon', 'licious', 'meat'])) {
                if (stripos($name, 'chicken') !== false) {
                    return ['category_id' => 9, 'subcategory_id' => 40];
                }
                if ($containsAny($name, ['mutton', 'goat', 'lamb'])) {
                    return ['category_id' => 9, 'subcategory_id' => 41];
                }
                if ($containsAny($name, ['fish', 'prawn', 'salmon', 'surmai'])) {
                    return ['category_id' => 9, 'subcategory_id' => 42];
                }
                return ['category_id' => 9, 'subcategory_id' => 43];
            }

            // 1. Vegetables & Fruits
            if ($containsAny($name, ['apple', 'banana', 'orange', 'grapes', 'mango', 'pomegranate', 'papaya', 'watermelon', 'melon', 'pineapple', 'pear', 'plum', 'peach', 'strawberry', 'guava', 'kiwi', 'avocado', 'cherry', 'litchi', 'coconut', 'gajar', 'carrot', 'potato', 'onion', 'garlic', 'ginger', 'coriander', 'mint', 'chilli', 'lemon', 'cucumber', 'bhindi', 'cabbage', 'cauliflower', 'tomato', 'pyaz', 'tamatar', 'mirch', 'adrak', 'lehsun', 'dhaniya', 'palak', 'spinach', 'kheera', 'beans', 'brinjal', 'aloo'])) {
                if (!$containsAny($name, ['juice', 'sauce', 'ketchup', 'chips', 'jam', 'biscuit', 'cookie', 'chocolate', 'ice cream', 'shampoo', 'hair oil', 'facewash', 'tea', 'soap', 'deodorant'])) {
                    if ($containsAny($name, ['herb', 'coriander', 'mint', 'ginger', 'garlic', 'chilli', 'lemon', 'dhaniya', 'adrak', 'lehsun', 'mirch', 'curry leaf', 'lemongrass'])) {
                        return ['category_id' => 1, 'subcategory_id' => 3];
                    }
                    if ($containsAny($name, ['kiwi', 'imported', 'exotic', 'avocado', 'cherry', 'litchi', 'mushroom', 'sprouts', 'sweet corn', 'broccoli'])) {
                        return ['category_id' => 1, 'subcategory_id' => 4];
                    }
                    if ($containsAny($name, ['banana', 'apple', 'pomegranate', 'mango', 'orange', 'grapes', 'papaya', 'watermelon', 'melon', 'pineapple', 'pear', 'plum', 'peach', 'strawberry', 'guava', 'sapota', 'chiku', 'fruit'])) {
                        return ['category_id' => 1, 'subcategory_id' => 2];
                    }
                    return ['category_id' => 1, 'subcategory_id' => 1];
                }
            }

            // 2. Dairy, Bread & Eggs
            if ($containsAny($name, ['milk', 'butter', 'ghee', 'cheese', 'paneer', 'tofu', 'curd', 'dahi', 'yogurt', 'egg', 'lassi', 'chaas', 'buttermilk', 'shrikhand', 'milkshake'])) {
                if (!$containsAny($name, ['biscuit', 'cookie', 'chocolate', 'soap', 'shampoo', 'hair', 'rusk'])) {
                    if (stripos($name, 'egg') !== false) {
                        return ['category_id' => 2, 'subcategory_id' => 10];
                    }
                    if ($containsAny($name, ['paneer', 'tofu'])) {
                        return ['category_id' => 2, 'subcategory_id' => 8];
                    }
                    if ($containsAny($name, ['curd', 'dahi', 'yogurt', 'shrikhand'])) {
                        return ['category_id' => 2, 'subcategory_id' => 9];
                    }
                    if ($containsAny($name, ['cheese', 'slice', 'spread'])) {
                        return ['category_id' => 2, 'subcategory_id' => 7];
                    }
                    if ($containsAny($name, ['butter', 'ghee'])) {
                        if (!$containsAny($name, ['peanut', 'chocolate'])) {
                            return ['category_id' => 2, 'subcategory_id' => 6];
                        }
                    }
                    return ['category_id' => 2, 'subcategory_id' => 5];
                }
            }

            // 4. Bakery & Biscuits
            if ($containsAny($name, ['biscuit', 'cookie', 'rusk', 'khari', 'bread', 'pav', 'bun', 'cake', 'muffin', 'croissant', 'pastry', 'waffle', 'tortilla'])) {
                if (stripos($name, 'ice cream') === false) {
                    if ($containsAny($name, ['biscuit', 'cookie', 'cookies'])) {
                        return ['category_id' => 4, 'subcategory_id' => 16];
                    }
                    if ($containsAny($name, ['bread', 'pav', 'bun', 'loaf'])) {
                        return ['category_id' => 4, 'subcategory_id' => 17];
                    }
                    if ($containsAny($name, ['rusk', 'khari', 'toast'])) {
                        return ['category_id' => 4, 'subcategory_id' => 18];
                    }
                    return ['category_id' => 4, 'subcategory_id' => 19];
                }
            }

            // 6. Tea, Coffee & Health Drinks
            if ($containsAny($name, ['tea', 'coffee', 'horlicks', 'boost', 'bournvita', 'complan', 'pediasure', 'ensure', 'health drink', 'chai'])) {
                if (!$containsAny($name, ['iced', 'oil', 'face', 'scrub', 'body', 'cup'])) {
                    if ($containsAny($name, ['green', 'herbal', 'chamomile'])) {
                        return ['category_id' => 6, 'subcategory_id' => 27];
                    }
                    if ($containsAny($name, ['coffee', 'nescafe', 'bru', 'davidoff'])) {
                        return ['category_id' => 6, 'subcategory_id' => 25];
                    }
                    if ($containsAny($name, ['horlicks', 'boost', 'bournvita', 'complan', 'pediasure', 'ensure', 'mix'])) {
                        return ['category_id' => 6, 'subcategory_id' => 26];
                    }
                    return ['category_id' => 6, 'subcategory_id' => 24];
                }
            }

            // 5. Cold Drinks & Juices
            if ($containsAny($name, ['coke', 'pepsi', 'sprite', 'fanta', 'limca', 'mirinda', 'soda', 'juice', 'drink', 'water', 'tonic', 'red bull', 'monster', 'energy', 'paper boat', 'real juice', 'tang', 'rasna', 'jaljeera', 'sharbats'])) {
                if (!$containsAny($name, ['wash', 'cream', 'shampoo'])) {
                    if ($containsAny($name, ['juice', 'pulp', 'maaza', 'frooti', 'slice', 'real', 'tropicana', 'paper boat'])) {
                        return ['category_id' => 5, 'subcategory_id' => 21];
                    }
                    if ($containsAny($name, ['red bull', 'monster', 'sting', 'energy', 'glucon'])) {
                        return ['category_id' => 5, 'subcategory_id' => 22];
                    }
                    if ($containsAny($name, ['water', 'bisleri', 'kinley', 'aquafina', 'tonic', 'club'])) {
                        return ['category_id' => 5, 'subcategory_id' => 23];
                    }
                    return ['category_id' => 5, 'subcategory_id' => 20];
                }
            }

            // 3. Munchies & Snacks
            if ($containsAny($name, ['chips', 'wafer', 'lays', 'kurkure', 'pringles', 'nachos', 'bingo', 'namkeen', 'bhujia', 'sev', 'mixture', 'gathiya', 'sweet', 'chocolate', 'candy', 'lollipop', 'cadbury', 'dairy milk', 'snickers', 'kitkat', '5 star', 'munch', 'gems', 'almond', 'cashew', 'pista', 'raisin', 'kishmish', 'walnut', 'makhana', 'popcorn', 'puff', 'puffs', 'kaju', 'badam'])) {
                if (!$containsAny($name, ['wash', 'shampoo', 'hair oil'])) {
                    if ($containsAny($name, ['chips', 'wafer', 'lays', 'pringles', 'nachos', 'bingo'])) {
                        return ['category_id' => 3, 'subcategory_id' => 11];
                    }
                    if ($containsAny($name, ['namkeen', 'bhujia', 'sev', 'mixture', 'gathiya', 'haldiram', 'bikaji'])) {
                        return ['category_id' => 3, 'subcategory_id' => 12];
                    }
                    if ($containsAny($name, ['sweet', 'chocolate', 'candy', 'lollipop', 'cadbury', 'dairy milk', 'snickers', 'kitkat', 'gems', 'toffee'])) {
                        return ['category_id' => 3, 'subcategory_id' => 13];
                    }
                    if ($containsAny($name, ['almond', 'cashew', 'pista', 'raisin', 'walnut', 'kaju', 'badam', 'kishmish', 'nuts', 'seeds'])) {
                        return ['category_id' => 3, 'subcategory_id' => 14];
                    }
                    return ['category_id' => 3, 'subcategory_id' => 15];
                }
            }

            // 7. Instant & Frozen Food
            if ($containsAny($name, ['noodle', 'maggi', 'yippee', 'ramen', 'pasta', 'vermicelli', 'macaroni', 'soup', 'ketchup', 'sauce', 'jam', 'mayonnaise', 'spread', 'honey', 'frozen', 'ready to', 'nugget', 'fries', 'burger patty', 'mccain', 'schezwan', 'chutney', 'peanut butter'])) {
                if ($containsAny($name, ['noodle', 'maggi', 'yippee', 'ramen'])) {
                    return ['category_id' => 7, 'subcategory_id' => 28];
                }
                if ($containsAny($name, ['pasta', 'vermicelli', 'macaroni'])) {
                    return ['category_id' => 7, 'subcategory_id' => 29];
                }
                if ($containsAny($name, ['soup', 'ready', 'instant meal'])) {
                    return ['category_id' => 7, 'subcategory_id' => 30];
                }
                if ($containsAny($name, ['ketchup', 'sauce', 'mayonnaise', 'spread', 'mustard', 'schezwan', 'chutney', 'peanut butter'])) {
                    return ['category_id' => 7, 'subcategory_id' => 32];
                }
                if ($containsAny($name, ['honey', 'jam', 'marmalade'])) {
                    return ['category_id' => 7, 'subcategory_id' => 33];
                }
                return ['category_id' => 7, 'subcategory_id' => 31];
            }

            // 8. Atta, Rice & Dal
            if ($containsAny($name, ['atta', 'flour', 'besan', 'maida', 'suji', 'sooji', 'sattu', 'rice', 'basmati', 'poha', 'dal', 'pulse', 'chana', 'moong', 'toor', 'urad', 'masoor', 'rajma', 'chhole', 'kabuli', 'oil', 'mustard', 'refined', 'olive', 'soyabean', 'spices', 'masala', 'turmeric', 'jeera', 'salt', 'sugar', 'jaggery', 'haldi', 'hing', 'pepper', 'cardamom', 'clove'])) {
                if (!$containsAny($name, ['hair', 'baby', 'cerelac', 'soap'])) {
                    if ($containsAny($name, ['atta', 'flour', 'besan', 'maida', 'suji', 'sooji', 'sattu'])) {
                        return ['category_id' => 8, 'subcategory_id' => 34];
                    }
                    if ($containsAny($name, ['rice', 'basmati', 'poha'])) {
                        return ['category_id' => 8, 'subcategory_id' => 35];
                    }
                    if ($containsAny($name, ['dal', 'pulse', 'chana', 'moong', 'toor', 'urad', 'masoor', 'rajma', 'chhole', 'kabuli'])) {
                        return ['category_id' => 8, 'subcategory_id' => 36];
                    }
                    if ($containsAny($name, ['oil', 'ghee'])) {
                        return ['category_id' => 8, 'subcategory_id' => 37];
                    }
                    if ($containsAny($name, ['salt', 'sugar', 'jaggery'])) {
                        return ['category_id' => 8, 'subcategory_id' => 39];
                    }
                    return ['category_id' => 8, 'subcategory_id' => 38];
                }
            }

            // 10. Cleaning & Household
            if ($containsAny($name, ['detergent', 'surf', 'ariel', 'tide', 'liquid wash', 'comfort', 'vanish', 'fabric', 'cleaner', 'vim', 'pril', 'dishwash', 'harpic', 'lizol', 'colin', 'phenyl', 'toilet', 'bathroom', 'garbage bag', 'trash bag', 'tissue', 'napkin', 'foil', 'scrub pad', 'repellent', 'hit', 'baygon', 'freshener', 'aer', 'odonil', 'coil', 'mortein'])) {
                if ($containsAny($name, ['detergent', 'surf', 'ariel', 'tide', 'comfort', 'vanish'])) {
                    return ['category_id' => 10, 'subcategory_id' => 44];
                }
                if ($containsAny($name, ['vim', 'pril', 'dishwash', 'scrub pad', 'sponge'])) {
                    return ['category_id' => 10, 'subcategory_id' => 45];
                }
                if ($containsAny($name, ['harpic', 'toilet', 'bathroom', 'phenyl', 'lizol', 'colin', 'cleaner'])) {
                    if ($containsAny($name, ['toilet', 'bathroom', 'harpic'])) {
                        return ['category_id' => 10, 'subcategory_id' => 46];
                    }
                }
                if ($containsAny($name, ['garbage', 'trash', 'foil', 'tissue', 'napkin', 'kitchen roll'])) {
                    return ['category_id' => 10, 'subcategory_id' => 47];
                }
                return ['category_id' => 10, 'subcategory_id' => 48];
            }

            // 11. Personal Care
            if ($containsAny($name, ['soap', 'body wash', 'shower gel', 'handwash', 'dettol', 'lifebuoy', 'shampoo', 'hair oil', 'conditioner', 'hair styling', 'hair color', 'facewash', 'face wash', 'skin', 'cream', 'lotion', 'moisturizer', 'deodorant', 'perfume', 'body spray', 'roll-on', 'toothpaste', 'toothbrush', 'oral', 'colgate', 'pepsodent', 'sensodyne', 'mouthwash'])) {
                if ($containsAny($name, ['soap', 'body wash', 'shower gel', 'handwash', 'liquid hand'])) {
                    return ['category_id' => 11, 'subcategory_id' => 49];
                }
                if ($containsAny($name, ['shampoo', 'conditioner'])) {
                    return ['category_id' => 11, 'subcategory_id' => 50];
                }
                if ($containsAny($name, ['hair oil', 'hair color', 'hair serum', 'almond oil', 'coconut oil'])) {
                    if ($containsAny($name, ['hair', 'color'])) {
                        return ['category_id' => 11, 'subcategory_id' => 51];
                    }
                }
                if ($containsAny($name, ['deodorant', 'perfume', 'spray', 'roll-on', 'cologne'])) {
                    return ['category_id' => 11, 'subcategory_id' => 53];
                }
                if ($containsAny($name, ['toothpaste', 'toothbrush', 'colgate', 'oral', 'mouthwash'])) {
                    return ['category_id' => 11, 'subcategory_id' => 54];
                }
                return ['category_id' => 11, 'subcategory_id' => 52];
            }

            // 14. Pharma & Wellness
            if ($containsAny($name, ['pain', 'bandage', 'crocin', 'calpol', 'combiflam', 'digene', 'eno', 'antacid', 'pudin hara', 'cough', 'vicks', 'strepsils', 'lozenge', 'multivitamin', 'capsule', 'tablet', 'sanitizer', 'mask', 'wellness'])) {
                if ($containsAny($name, ['digene', 'eno', 'antacid', 'pudin', 'gas'])) {
                    return ['category_id' => 14, 'subcategory_id' => 61];
                }
                if ($containsAny($name, ['cough', 'vicks', 'strepsils', 'lozenge', 'cold', 'immunity'])) {
                    return ['category_id' => 14, 'subcategory_id' => 62];
                }
                if ($containsAny($name, ['sanitizer', 'mask'])) {
                    return ['category_id' => 14, 'subcategory_id' => 63];
                }
                return ['category_id' => 14, 'subcategory_id' => 60];
            }

            // 16. Home & Kitchen (Default Fallback for non-grocery household items)
            if ($containsAny($name, ['battery', 'duracell', 'bulb', 'led', 'notebook', 'diary', 'pen', 'pencil', 'stationery', 'toy', 'game', 'party', 'balloon', 'knife', 'spoon', 'container', 'bottle', 'cookware'])) {
                if ($containsAny($name, ['battery', 'duracell', 'bulb', 'led'])) {
                    return ['category_id' => 16, 'subcategory_id' => 66];
                }
                if ($containsAny($name, ['notebook', 'diary', 'pen', 'pencil', 'stationery'])) {
                    return ['category_id' => 16, 'subcategory_id' => 67];
                }
                if ($containsAny($name, ['toy', 'game', 'party', 'balloon'])) {
                    return ['category_id' => 16, 'subcategory_id' => 68];
                }
                return ['category_id' => 16, 'subcategory_id' => 69];
            }

            // Default Fallback mapping based on original category_id
            $fallbackMap = [
                1 => ['category_id' => 1, 'subcategory_id' => 1],   // Fruits & Veg -> Vegetables
                2 => ['category_id' => 2, 'subcategory_id' => 5],   // Dairy -> Milk
                3 => ['category_id' => 3, 'subcategory_id' => 11],  // Munchies -> Chips
                4 => ['category_id' => 5, 'subcategory_id' => 20],  // Drinks -> Soft Drinks
                5 => ['category_id' => 7, 'subcategory_id' => 28],  // Instant -> Noodles
                6 => ['category_id' => 8, 'subcategory_id' => 34],  // Grains -> Atta
                7 => ['category_id' => 10, 'subcategory_id' => 44], // Cleaning -> Detergents
                8 => ['category_id' => 11, 'subcategory_id' => 49]  // Personal Care -> Soaps
            ];
            return isset($fallbackMap[$origCategoryId]) ? $fallbackMap[$origCategoryId] : ['category_id' => 1, 'subcategory_id' => 1];
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
        $importFile = ROOTPATH . 'import_products.json';
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
                
                // Category & Subcategory classification
                // Scraper-provided aisle ids are authoritative.  Only fall back
                // to the legacy name classifier for older import files that do
                // not carry a validated subcategory yet.
                $sourceCategoryId = (int) ($item['category_id'] ?? 0);
                $sourceSubcategoryId = (int) ($item['subcategory_id'] ?? 0);
                $sourceSubcategory = null;
                if ($sourceCategoryId > 0 && $sourceSubcategoryId > 0) {
                    $sourceSubcategory = $db->table('subcategory')
                        ->where('id', $sourceSubcategoryId)
                        ->where('category_id', $sourceCategoryId)
                        ->get()
                        ->getRowArray();
                }
                $classification = $sourceSubcategory
                    ? ['category_id' => $sourceCategoryId, 'subcategory_id' => $sourceSubcategoryId]
                    : $classifyProduct($productName, $sourceCategoryId ?: 1);
                $categoriesBatch[] = [
                    'product_id'  => $productId,
                    'category_id' => $classification['category_id']
                ];

                $subcategoriesBatch[] = [
                    'product_id' => $productId,
                    'subcategory_id' => $classification['subcategory_id']
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
