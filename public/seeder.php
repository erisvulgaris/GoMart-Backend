<?php
header('Content-Type: application/json');
$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

$output = [];

// 1. Ensure a category group exists
$db->query("INSERT INTO `category_group` (`id`, `group_name`, `row_order`) VALUES (1, 'Grocery', 1) ON DUPLICATE KEY UPDATE id=id");

// 2. Ensure a brand exists
$db->query("INSERT INTO `brand` (`id`, `brand_name`, `slug`, `brand_img`, `status`, `is_delete`) VALUES (1, 'CityLoop Farms', 'cityloop-farms', 'assets/dist/img/tekchi_logo.png', 1, 0) ON DUPLICATE KEY UPDATE id=id");

// 3. Ensure a category exists
$db->query("INSERT INTO `category` (`id`, `category_group_id`, `row_order`, `category_name`, `slug`, `category_img`, `is_bestseller_category`) VALUES (1, 1, 1, 'Fresh Fruits & Vegetables', 'fresh-fruits-vegetables', 'assets/dist/img/app_logo_1728028997.webp', 1) ON DUPLICATE KEY UPDATE id=id");

// 4. Ensure a stock unit exists
$db->query("INSERT INTO `stock_unit` (`id`, `unit_name`, `short_name`) VALUES (1, 'Kilogram', 'kg'), (2, 'Litre', 'L'), (3, 'Piece', 'pc') ON DUPLICATE KEY UPDATE id=id");

// 5. Ensure a seller exists assigned to deliverable_area_id = 1 (Gorakhpur)
$seller_check = $db->query("SELECT id FROM seller WHERE email = 'store@cityloopapp.com'");
if ($seller_check->num_rows == 0) {
    $pass_hash = password_hash('12345678', PASSWORD_DEFAULT);
    $db->query("INSERT INTO `seller` (`id`, `name`, `store_name`, `slug`, `email`, `password`, `mobile`, `balance`, `logo`, `store_address`, `city_id`, `deliverable_area_id`, `commission`, `status`, `is_delete`) VALUES (1, 'CityLoop Gorakhpur Store', 'CityLoop Gorakhpur', 'cityloop-gorakhpur', 'store@cityloopapp.com', '$pass_hash', '9876543210', 1000.00, 'assets/dist/img/tekchi_logo.png', 'Gorakhpur, UP', 1, 1, 10.00, 1, 0)");
    $output[] = "Seller created.";
} else {
    $row = $seller_check->fetch_assoc();
    $db->query("UPDATE seller SET deliverable_area_id = 1, status = 1, is_delete = 0 WHERE id = " . $row['id']);
    $output[] = "Seller updated to be active and assigned to Gorakhpur deliverable area.";
}

// 6. Seed some test products
$products_data = [
    [
        'id' => 1,
        'name' => 'Fresh Red Apples',
        'slug' => 'fresh-red-apples',
        'desc' => 'Sweet and crunchy fresh red apples sourced from local farms.',
        'variant_title' => '1 kg',
        'price' => 150.00,
        'disc_price' => 120.00,
        'unit' => 1
    ],
    [
        'id' => 2,
        'name' => 'Organic Bananas',
        'slug' => 'organic-bananas',
        'desc' => 'Rich in potassium, fresh organic yellow bananas.',
        'variant_title' => '1 Dozen',
        'price' => 80.00,
        'disc_price' => 60.00,
        'unit' => 3
    ],
    [
        'id' => 3,
        'name' => 'Fresh Milk',
        'slug' => 'fresh-milk',
        'desc' => 'Pure pasteurized whole milk, fresh and healthy.',
        'variant_title' => '1 Litre',
        'price' => 70.00,
        'disc_price' => 64.00,
        'unit' => 2
    ],
    [
        'id' => 4,
        'name' => 'Brown Bread',
        'slug' => 'brown-bread',
        'desc' => 'Whole wheat fiber-rich brown bread freshly baked.',
        'variant_title' => '400g Pack',
        'price' => 45.00,
        'disc_price' => 40.00,
        'unit' => 3
    ],
    [
        'id' => 5,
        'name' => 'Fresh Potatoes',
        'slug' => 'fresh-potatoes',
        'desc' => 'Fresh locally grown farm potatoes, perfect for daily cooking.',
        'variant_title' => '2 kg',
        'price' => 60.00,
        'disc_price' => 50.00,
        'unit' => 1
    ]
];

foreach ($products_data as $p) {
    $db->query("INSERT INTO `product` (`id`, `brand_id`, `seller_id`, `row_order`, `product_name`, `slug`, `main_img`, `description`, `status`, `date`, `popular`, `deal_of_the_day`, `is_delete`, `added_by_seller`) 
                VALUES ({$p['id']}, 1, 1, 1, '{$p['name']}', '{$p['slug']}', 'assets/dist/img/tekchi_logo.png', '{$p['desc']}', 1, NOW(), 1, 1, 0, 0)
                ON DUPLICATE KEY UPDATE status=1, is_delete=0");
                
    $db->query("INSERT INTO `product_category` (`product_id`, `category_id`) VALUES ({$p['id']}, 1) ON DUPLICATE KEY UPDATE category_id=1");
    
    $db->query("INSERT INTO `product_variants` (`product_id`, `status`, `title`, `price`, `discounted_price`, `stock`, `is_unlimited_stock`, `stock_unit_id`, `is_delete`) 
                VALUES ({$p['id']}, 1, '{$p['variant_title']}', {$p['price']}, {$p['disc_price']}, 100, 0, {$p['unit']}, 0)
                ON DUPLICATE KEY UPDATE status=1, is_delete=0, price={$p['price']}, discounted_price={$p['disc_price']}, stock=100");
}

$output[] = "5 Premium products seeded with categories and variants.";

echo json_encode(["success" => true, "logs" => $output], JSON_PRETTY_PRINT);
$db->close();
