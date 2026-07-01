<?php
header('Content-Type: application/json');
$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

// 1. Get first seller
$seller_res = $db->query("SELECT id FROM seller LIMIT 1");
$seller = $seller_res->fetch_assoc();
$seller_id = $seller ? $seller['id'] : 1;

// 2. Clear tables
$db->query("SET FOREIGN_KEY_CHECKS = 0;");
$db->query("TRUNCATE TABLE category_group;");
$db->query("TRUNCATE TABLE category;");
$db->query("TRUNCATE TABLE subcategory;");
$db->query("TRUNCATE TABLE product;");
$db->query("TRUNCATE TABLE product_variants;");
$db->query("TRUNCATE TABLE product_categories;");
$db->query("TRUNCATE TABLE product_subcategories;");
$db->query("TRUNCATE TABLE seller_categories;");
$db->query("SET FOREIGN_KEY_CHECKS = 1;");

// 2.5 Insert Category Group
$db->query("INSERT INTO category_group (id, title, created_at) VALUES (1, 'Shop By Category', NOW());");


// 3. Define Seed Data (8 categories, subcategories, products, variants)
$seed = [
    [
        "name" => "Fruits & Vegetables",
        "img" => "https://images.unsplash.com/photo-1540420773420-3366772f4999?w=300",
        "subs" => [
            [
                "name" => "Fresh Vegetables",
                "products" => [
                    ["name" => "Fresh Tomato (Hybrid)", "price" => 40, "d_price" => 35, "unit" => "500 g", "img" => "https://images.unsplash.com/photo-1595855759920-86582396756a?w=300"],
                    ["name" => "Potato (Jyoti)", "price" => 30, "d_price" => 25, "unit" => "1 kg", "img" => "https://images.unsplash.com/photo-1518977676601-b53f82aba655?w=300"],
                    ["name" => "Fresh Onion", "price" => 50, "d_price" => 45, "unit" => "1 kg", "img" => "https://images.unsplash.com/photo-1508747703725-719777637510?w=300"]
                ]
            ],
            [
                "name" => "Fresh Fruits",
                "products" => [
                    ["name" => "Banana (Robusta)", "price" => 60, "d_price" => 49, "unit" => "6 pcs", "img" => "https://images.unsplash.com/photo-1571771894821-ce9b6c11b08e?w=300"],
                    ["name" => "Apple (Royal Gala)", "price" => 180, "d_price" => 149, "unit" => "4 pcs", "img" => "https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?w=300"],
                    ["name" => "Pomegranate (Kesar)", "price" => 190, "d_price" => 165, "unit" => "4 pcs", "img" => "https://images.unsplash.com/photo-1533604130617-32128522e86c?w=300"]
                ]
            ],
            [
                "name" => "Herbs & Seasonings",
                "products" => [
                    ["name" => "Fresh Coriander Leaves", "price" => 15, "d_price" => 10, "unit" => "100 g", "img" => "https://images.unsplash.com/photo-1608797178974-15b35a61d121?w=300"],
                    ["name" => "Ginger (Adrak)", "price" => 40, "d_price" => 35, "unit" => "250 g", "img" => "https://images.unsplash.com/photo-1615485290382-441e4d049cb5?w=300"],
                    ["name" => "Garlic (Lahsun)", "price" => 60, "d_price" => 50, "unit" => "200 g", "img" => "https://images.unsplash.com/photo-1540148426945-6cf22a6b2383?w=300"]
                ]
            ]
        ]
    ],
    [
        "name" => "Dairy, Bread & Eggs",
        "img" => "https://images.unsplash.com/photo-1563636619-e9143da7973b?w=300",
        "subs" => [
            [
                "name" => "Milk",
                "products" => [
                    ["name" => "Amul Taaza Toned Milk", "price" => 28, "d_price" => 27, "unit" => "500 ml", "img" => "https://images.unsplash.com/photo-1550583724-b2692b85b150?w=300"],
                    ["name" => "Amul Gold Full Cream Milk", "price" => 33, "d_price" => 32, "unit" => "500 ml", "img" => "https://images.unsplash.com/photo-1563636619-e9143da7973b?w=300"],
                    ["name" => "Mother Dairy Double Toned Milk", "price" => 25, "d_price" => 24, "unit" => "500 ml", "img" => "https://images.unsplash.com/photo-1528750901443-e986c7adef6e?w=300"]
                ]
            ],
            [
                "name" => "Butter & Cheese",
                "products" => [
                    ["name" => "Amul Butter", "price" => 60, "d_price" => 58, "unit" => "100 g", "img" => "https://images.unsplash.com/photo-1589985270826-4b7bb135bc9d?w=300"],
                    ["name" => "Amul Cheese Slices", "price" => 150, "d_price" => 140, "unit" => "200 g", "img" => "https://images.unsplash.com/photo-1582291957434-a0352d431056?w=300"],
                    ["name" => "Amul Paneer (Fresh)", "price" => 90, "d_price" => 85, "unit" => "200 g", "img" => "https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=300"]
                ]
            ],
            [
                "name" => "Eggs & Bread",
                "products" => [
                    ["name" => "White Farm Eggs", "price" => 55, "d_price" => 49, "unit" => "6 pcs", "img" => "https://images.unsplash.com/photo-1516448620398-c5f44bf9f441?w=300"],
                    ["name" => "English Oven White Bread", "price" => 45, "d_price" => 40, "unit" => "400 g", "img" => "https://images.unsplash.com/photo-1509440159596-0249088772ff?w=300"],
                    ["name" => "English Oven Brown Bread", "price" => 50, "d_price" => 45, "unit" => "400 g", "img" => "https://images.unsplash.com/photo-1549931319-a545dcf3bc73?w=300"]
                ]
            ]
        ]
    ],
    [
        "name" => "Munchies & Snacks",
        "img" => "https://images.unsplash.com/photo-1599490659213-e2b9527ec087?w=300",
        "subs" => [
            [
                "name" => "Chips & Crisps",
                "products" => [
                    ["name" => "Lay's American Style Cream & Onion", "price" => 20, "d_price" => 20, "unit" => "50 g", "img" => "https://images.unsplash.com/photo-1566478989037-eec170784d0b?w=300"],
                    ["name" => "Lay's Classic Salted", "price" => 20, "d_price" => 20, "unit" => "50 g", "img" => "https://images.unsplash.com/photo-1566478989037-eec170784d0b?w=300"],
                    ["name" => "Kurkure Masala Munch", "price" => 20, "d_price" => 20, "unit" => "80 g", "img" => "https://images.unsplash.com/photo-1600952841320-db92ec4047ca?w=300"]
                ]
            ],
            [
                "name" => "Biscuits & Cookies",
                "products" => [
                    ["name" => "Parle-G Gold Biscuits", "price" => 10, "d_price" => 10, "unit" => "120 g", "img" => "https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=300"],
                    ["name" => "Britannia Good Day Cashew Cookies", "price" => 30, "d_price" => 25, "unit" => "200 g", "img" => "https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=300"],
                    ["name" => "Oreo Chocolate Sandwich Cookies", "price" => 40, "d_price" => 35, "unit" => "120 g", "img" => "https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=300"]
                ]
            ],
            [
                "name" => "Namkeen & Sweets",
                "products" => [
                    ["name" => "Haldiram's Aloo Bhujia", "price" => 40, "d_price" => 35, "unit" => "150 g", "img" => "https://images.unsplash.com/photo-1601050690597-df056fb4ce78?w=300"],
                    ["name" => "Haldiram's Bhujia Sev", "price" => 40, "d_price" => 35, "unit" => "150 g", "img" => "https://images.unsplash.com/photo-1601050690597-df056fb4ce78?w=300"],
                    ["name" => "Cadbury Dairy Milk Silk", "price" => 80, "d_price" => 75, "unit" => "60 g", "img" => "https://images.unsplash.com/photo-1549007994-cb92ca817bc7?w=300"]
                ]
            ]
        ]
    ],
    [
        "name" => "Cold Drinks & Juices",
        "img" => "https://images.unsplash.com/photo-1622483767028-3f66f32aef97?w=300",
        "subs" => [
            [
                "name" => "Soft Drinks",
                "products" => [
                    ["name" => "Coca-Cola Aerated Drink", "price" => 40, "d_price" => 38, "unit" => "250 ml Can", "img" => "https://images.unsplash.com/photo-1622483767028-3f66f32aef97?w=300"],
                    ["name" => "Pepsi Soft Drink", "price" => 40, "d_price" => 38, "unit" => "250 ml Can", "img" => "https://images.unsplash.com/photo-1546173152-3160bec5b5c9?w=300"],
                    ["name" => "Sprite Lemon-Lime Drink", "price" => 40, "d_price" => 38, "unit" => "250 ml Can", "img" => "https://images.unsplash.com/photo-1622483767028-3f66f32aef97?w=300"]
                ]
            ],
            [
                "name" => "Fruit Juices",
                "products" => [
                    ["name" => "Real Fruit Power Mixed Fruit", "price" => 110, "d_price" => 99, "unit" => "1 L", "img" => "https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=300"],
                    ["name" => "Real Fruit Power Guava", "price" => 110, "d_price" => 99, "unit" => "1 L", "img" => "https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=300"],
                    ["name" => "Paper Boat Mango Juice", "price" => 40, "d_price" => 35, "unit" => "250 ml", "img" => "https://images.unsplash.com/photo-1534353436294-0dbd4bdac845?w=300"]
                ]
            ],
            [
                "name" => "Water & Soda",
                "products" => [
                    ["name" => "Bisleri Packaged Water", "price" => 20, "d_price" => 19, "unit" => "1 L", "img" => "https://images.unsplash.com/photo-1608885898957-a599fb1b468b?w=300"],
                    ["name" => "Kinley Club Soda", "price" => 20, "d_price" => 20, "unit" => "750 ml", "img" => "https://images.unsplash.com/photo-1608885898957-a599fb1b468b?w=300"],
                    ["name" => "Red Bull Energy Drink", "price" => 125, "d_price" => 119, "unit" => "250 ml Can", "img" => "https://images.unsplash.com/photo-1622483767028-3f66f32aef97?w=300"]
                ]
            ]
        ]
    ],
    [
        "name" => "Instant & Frozen Food",
        "img" => "https://images.unsplash.com/photo-1618413615392-15f1fcc6e147?w=300",
        "subs" => [
            [
                "name" => "Noodles & Pasta",
                "products" => [
                    ["name" => "Maggi 2-Min Masala Noodles", "price" => 14, "d_price" => 14, "unit" => "70 g", "img" => "https://images.unsplash.com/photo-1612966608967-302837410065?w=300"],
                    ["name" => "Maggi Masala Noodles (Pack of 4)", "price" => 56, "d_price" => 54, "unit" => "280 g", "img" => "https://images.unsplash.com/photo-1612966608967-302837410065?w=300"],
                    ["name" => "YiPPee! Magic Masala Noodles", "price" => 15, "d_price" => 14, "unit" => "70 g", "img" => "https://images.unsplash.com/photo-1612966608967-302837410065?w=300"]
                ]
            ],
            [
                "name" => "Frozen Veg Snacks",
                "products" => [
                    ["name" => "McCain French Fries", "price" => 120, "d_price" => 105, "unit" => "420 g", "img" => "https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=300"],
                    ["name" => "McCain Veggie Fingers", "price" => 140, "d_price" => 125, "unit" => "400 g", "img" => "https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=300"],
                    ["name" => "McCain Smiles", "price" => 130, "d_price" => 115, "unit" => "375 g", "img" => "https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=300"]
                ]
            ],
            [
                "name" => "Spreads & Ketchup",
                "products" => [
                    ["name" => "Kissan Fresh Tomato Ketchup", "price" => 120, "d_price" => 105, "unit" => "950 g", "img" => "https://images.unsplash.com/photo-1607305387299-a3d9611cd46f?w=300"],
                    ["name" => "FunFoods Veg Mayonnaise", "price" => 99, "d_price" => 89, "unit" => "250 g", "img" => "https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=300"],
                    ["name" => "Amul Dark Chocolate Spread", "price" => 110, "d_price" => 99, "unit" => "200 g", "img" => "https://images.unsplash.com/photo-1511381939415-e44015466834?w=300"]
                ]
            ]
        ]
    ],
    [
        "name" => "Atta, Rice & Dals",
        "img" => "https://images.unsplash.com/photo-1586201375761-83865001e31c?w=300",
        "subs" => [
            [
                "name" => "Atta & Flours",
                "products" => [
                    ["name" => "Aashirvaad Shudh Chakki Atta", "price" => 260, "d_price" => 239, "unit" => "5 kg", "img" => "https://images.unsplash.com/photo-1626132647523-66f5bf380027?w=300"],
                    ["name" => "Fortune Chakki Fresh Atta", "price" => 240, "d_price" => 219, "unit" => "5 kg", "img" => "https://images.unsplash.com/photo-1626132647523-66f5bf380027?w=300"],
                    ["name" => "Rajdhani Besan", "price" => 70, "d_price" => 65, "unit" => "500 g", "img" => "https://images.unsplash.com/photo-1626132647523-66f5bf380027?w=300"]
                ]
            ],
            [
                "name" => "Dals & Pulses",
                "products" => [
                    ["name" => "Tata Sampann Toor Dal", "price" => 180, "d_price" => 165, "unit" => "1 kg", "img" => "https://images.unsplash.com/photo-1586201375761-83865001e31c?w=300"],
                    ["name" => "Tata Sampann Chana Dal", "price" => 110, "d_price" => 99, "unit" => "1 kg", "img" => "https://images.unsplash.com/photo-1586201375761-83865001e31c?w=300"],
                    ["name" => "Tata Sampann Moong Dal", "price" => 160, "d_price" => 145, "unit" => "1 kg", "img" => "https://images.unsplash.com/photo-1586201375761-83865001e31c?w=300"]
                ]
            ],
            [
                "name" => "Rice & Rice Products",
                "products" => [
                    ["name" => "India Gate Basmati Rice Mogra", "price" => 120, "d_price" => 99, "unit" => "1 kg", "img" => "https://images.unsplash.com/photo-1586201375761-83865001e31c?w=300"],
                    ["name" => "India Gate Basmati Rice Super", "price" => 220, "d_price" => 189, "unit" => "1 kg", "img" => "https://images.unsplash.com/photo-1586201375761-83865001e31c?w=300"],
                    ["name" => "Fortune Everyday Basmati Rice", "price" => 90, "d_price" => 79, "unit" => "1 kg", "img" => "https://images.unsplash.com/photo-1586201375761-83865001e31c?w=300"]
                ]
            ]
        ]
    ],
    [
        "name" => "Cleaning Essentials",
        "img" => "https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=300",
        "subs" => [
            [
                "name" => "Detergents",
                "products" => [
                    ["name" => "Surf Excel Easy Wash", "price" => 140, "d_price" => 129, "unit" => "1 kg", "img" => "https://images.unsplash.com/photo-1563161402-8b434c267b1b?w=300"],
                    ["name" => "Surf Excel Matic Liquid", "price" => 220, "d_price" => 199, "unit" => "1 L", "img" => "https://images.unsplash.com/photo-1563161402-8b434c267b1b?w=300"],
                    ["name" => "Comfort Fabric Conditioner", "price" => 60, "d_price" => 55, "unit" => "220 ml", "img" => "https://images.unsplash.com/photo-1563161402-8b434c267b1b?w=300"]
                ]
            ],
            [
                "name" => "Dishwashers",
                "products" => [
                    ["name" => "Vim Dishwash Gel (Lemon)", "price" => 115, "d_price" => 105, "unit" => "500 ml", "img" => "https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=300"],
                    ["name" => "Vim Dishwash Bar", "price" => 20, "d_price" => 19, "unit" => "150 g", "img" => "https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=300"],
                    ["name" => "Pril Dishwash Liquid", "price" => 110, "d_price" => 99, "unit" => "425 ml", "img" => "https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=300"]
                ]
            ],
            [
                "name" => "Cleaners",
                "products" => [
                    ["name" => "Harpic Toilet Cleaner Liquid", "price" => 105, "d_price" => 95, "unit" => "500 ml", "img" => "https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=300"],
                    ["name" => "Lizol Floor Cleaner (Floral)", "price" => 110, "d_price" => 99, "unit" => "500 ml", "img" => "https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=300"],
                    ["name" => "Colin Glass Cleaner Spray", "price" => 100, "d_price" => 89, "unit" => "500 ml", "img" => "https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=300"]
                ]
            ]
        ]
    ],
    [
        "name" => "Personal Care",
        "img" => "https://images.unsplash.com/photo-1608248597481-496100c80836?w=300",
        "subs" => [
            [
                "name" => "Soaps & Body Wash",
                "products" => [
                    ["name" => "Dettol Liquid Handwash Refill", "price" => 99, "d_price" => 89, "unit" => "750 ml", "img" => "https://images.unsplash.com/photo-1608248597481-496100c80836?w=300"],
                    ["name" => "Dove Cream Beauty Bathing Bar", "price" => 65, "d_price" => 59, "unit" => "75 g", "img" => "https://images.unsplash.com/photo-1608248597481-496100c80836?w=300"],
                    ["name" => "Dettol Bathing Soap (Original)", "price" => 50, "d_price" => 46, "unit" => "125 g", "img" => "https://images.unsplash.com/photo-1608248597481-496100c80836?w=300"]
                ]
            ],
            [
                "name" => "Oral Care",
                "products" => [
                    ["name" => "Colgate Strong Teeth Toothpaste", "price" => 110, "d_price" => 99, "unit" => "200 g", "img" => "https://images.unsplash.com/photo-1559591937-e44015466834?w=300"],
                    ["name" => "Sensodyne Fresh Mint Toothpaste", "price" => 170, "d_price" => 155, "unit" => "100 g", "img" => "https://images.unsplash.com/photo-1559591937-e44015466834?w=300"],
                    ["name" => "Colgate Zig Zag Toothbrush (Medium)", "price" => 30, "d_price" => 28, "unit" => "1 pc", "img" => "https://images.unsplash.com/photo-1559591937-e44015466834?w=300"]
                ]
            ],
            [
                "name" => "Hair Care",
                "products" => [
                    ["name" => "Clinic Plus Strong & Long Shampoo", "price" => 150, "d_price" => 139, "unit" => "340 ml", "img" => "https://images.unsplash.com/photo-1526947425960-945c6e72858f?w=300"],
                    ["name" => "Head & Shoulders Anti-Dandruff", "price" => 195, "d_price" => 179, "unit" => "180 ml", "img" => "https://images.unsplash.com/photo-1526947425960-945c6e72858f?w=300"],
                    ["name" => "Parachute Coconut Hair Oil", "price" => 99, "d_price" => 89, "unit" => "250 ml", "img" => "https://images.unsplash.com/photo-1627756879520-4f81a4b93475?w=300"]
                ]
            ]
        ]
    ]
];

$row_order = 1;
foreach ($seed as $c_data) {
    // 1. Insert Category
    $c_name = $db->real_escape_string($c_data['name']);
    $c_img = $db->real_escape_string($c_data['img']);
    $c_slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $c_data['name'])));
    
    $db->query("INSERT INTO category (category_group_id, row_order, category_name, slug, category_img, is_bestseller_category) VALUES (1, $row_order, '$c_name', '$c_slug', '$c_img', 1)");
    $cat_id = $db->insert_id;
    $row_order++;
    
    // Link category to default seller
    $db->query("INSERT INTO seller_categories (seller_id, category_id) VALUES ($seller_id, $cat_id)");
    
    $sub_order = 1;
    foreach ($c_data['subs'] as $s_data) {
        // 2. Insert Subcategory
        $s_name = $db->real_escape_string($s_data['name']);
        $s_slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $s_data['name'])));
        
        $db->query("INSERT INTO subcategory (category_id, row_order, name, slug, img) VALUES ($cat_id, $sub_order, '$s_name', '$s_slug', '$c_img')");
        $sub_id = $db->insert_id;
        $sub_order++;
        
        $prod_order = 1;
        foreach ($s_data['products'] as $p_data) {
            // 3. Insert Product
            $p_name = $db->real_escape_string($p_data['name']);
            $p_slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $p_data['name']))) . '-' . rand(100,999);
            $p_desc = $db->real_escape_string($p_data['name'] . " - High quality item delivered to your door in 10 minutes.");
            $p_img = $db->real_escape_string($p_data['img']);
            
            $db->query("INSERT INTO product (product_name, brand_id, seller_id, row_order, description, status, main_img, date, popular, deal_of_the_day, is_delete, slug) VALUES ('$p_name', 1, $seller_id, $prod_order, '$p_desc', 1, '$p_img', NOW(), 1, 1, 0, '$p_slug')");
            $prod_id = $db->insert_id;
            $prod_order++;
            
            // 4. Insert Product Variant
            $v_title = $db->real_escape_string($p_data['unit']);
            $v_price = $p_data['price'];
            $v_dprice = $p_data['d_price'];
            
            $db->query("INSERT INTO product_variants (product_id, status, title, price, discounted_price, stock, is_unlimited_stock, stock_unit_id, is_delete) VALUES ($prod_id, 1, '$v_title', $v_price, $v_dprice, 999, 1, 1, 0)");
            
            // 5. Insert Joins
            $db->query("INSERT INTO product_categories (product_id, category_id) VALUES ($prod_id, $cat_id)");
            $db->query("INSERT INTO product_subcategories (product_id, subcategory_id) VALUES ($prod_id, $sub_id)");
        }
    }
}

echo json_encode([
    "status" => "success",
    "message" => "Seed completed successfully. Cleared old records and seeded 8 categories, 24 subcategories, and 72 products."
]);
$db->close();
