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
        
        echo "Clearing existing products...\n";
        $db->table('product')->truncate();
        $db->table('product_variants')->truncate();
        $db->table('product_categories')->truncate();
        $db->table('product_subcategories')->truncate();
        $db->table('product_images')->truncate();
        $db->table('product_tag')->truncate();
        $db->table('product_taxes')->truncate();
        
        $db->query('SET FOREIGN_KEY_CHECKS = 1;');
        
        // 2. Locate the import JSON data file
        $importFile = WRITEPATH . 'import_products.json';
        if (!file_exists($importFile)) {
            echo "Error: Import file not found at {$importFile}\n";
            echo "Please place your structured JSON catalog file there to import products.\n";
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
        foreach ($products as $item) {
            // Process names and descriptions to replace "Blinkit" with "CityLoop"
            $productName = isset($item['product_name']) ? $item['product_name'] : 'Unnamed Product';
            $description = isset($item['description']) ? $item['description'] : '';
            
            $productName = str_ireplace('Blinkit', 'CityLoop', $productName);
            $description = str_ireplace('Blinkit', 'CityLoop', $description);
            
            $slug = url_title($productName, '-', true) . '-' . time() . '-' . rand(100, 999);
            
            // Insert Product
            $productData = [
                'brand_id'              => isset($item['brand_id']) ? $item['brand_id'] : 0,
                'seller_id'             => isset($item['seller_id']) ? $item['seller_id'] : 1, // Default seller
                'tax_id'                => isset($item['tax_id']) ? $item['tax_id'] : 0,
                'product_name'          => $productName,
                'slug'                  => $slug,
                'main_img'              => isset($item['main_img']) ? $item['main_img'] : 'uploads/products/placeholder.png',
                'description'           => $description,
                'popular'               => 0,
                'deal_of_the_day'       => 0,
                'status'                => 1, // Published
                'is_delete'             => 0,
                'date'                  => date('Y-m-d H:i:s')
            ];
            
            $db->table('product')->insert($productData);
            $productId = $db->insertID();
            
            if ($productId) {
                // Insert Product Variant (Force stock to 0)
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
                
                // Link Category
                if (isset($item['category_id'])) {
                    $categoryData = [
                        'product_id'  => $productId,
                        'category_id' => $item['category_id']
                    ];
                    $db->table('product_categories')->insert($categoryData);
                }
                
                // Link Multiple Images
                if (isset($item['images']) && is_array($item['images'])) {
                    foreach ($item['images'] as $imgUrl) {
                        $imageData = [
                            'product_id'          => $productId,
                            'product_variant_id'  => 0,
                            'image'               => $imgUrl
                        ];
                        $db->table('product_images')->insert($imageData);
                    }
                }
                
                $successCount++;
            }
        }
        
        echo "Successfully imported {$successCount} products with stock initialized to 0!\n";
    }
}
