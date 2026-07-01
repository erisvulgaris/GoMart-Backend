<?php
header('Content-Type: application/json');
$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

$output = [];

// 1. Resolve Category Group ID
$group_res = $db->query("SELECT id FROM category_group LIMIT 1");
if ($group_res->num_rows > 0) {
    $group_row = $group_res->fetch_assoc();
    $group_id = $group_row['id'];
    $output[] = "Found existing category group ID: $group_id";
} else {
    $db->query("INSERT INTO `category_group` (`id`, `title`, `created_at`) VALUES (1, 'Grocery', NOW())");
    $group_id = 1;
    $output[] = "Created new category group ID: 1";
}

// 2. Resolve Brand ID
$brand_res = $db->query("SELECT id FROM brand LIMIT 1");
if ($brand_res->num_rows > 0) {
    $brand_row = $brand_res->fetch_assoc();
    $brand_id = $brand_row['id'];
    $output[] = "Found existing brand ID: $brand_id";
} else {
    $db->query("INSERT INTO `brand` (`id`, `brand`, `slug`, `image`, `row_order`) VALUES (1, 'CityLoop Farms', 'cityloop-farms', 'assets/dist/img/tekchi_logo.png', 1)");
    $brand_id = 1;
    $output[] = "Created new brand ID: 1";
}

// 3. Resolve Category ID
$cat_res = $db->query("SELECT id FROM category LIMIT 1");
if ($cat_res->num_rows > 0) {
    $cat_row = $cat_res->fetch_assoc();
    $cat_id = $cat_row['id'];
    $output[] = "Found existing category ID: $cat_id";
} else {
    $db->query("INSERT INTO `category` (`id`, `category_group_id`, `row_order`, `category_name`, `slug`, `category_img`, `is_bestseller_category`) VALUES (1, $group_id, 1, 'Fresh Fruits & Vegetables', 'fresh-fruits-vegetables', 'assets/dist/img/AdminLTELogo.png', 1)");
    $cat_id = 1;
    $output[] = "Created new category ID: 1";
}

// 4. Resolve Stock Unit ID
$unit_res = $db->query("SELECT id FROM stock_unit LIMIT 1");
if ($unit_res->num_rows > 0) {
    $unit_row = $unit_res->fetch_assoc();
    $unit_id = $unit_row['id'];
    $output[] = "Found existing stock unit ID: $unit_id";
} else {
    $db->query("INSERT INTO `stock_unit` (`id`, `unit_name`, `short_name`) VALUES (1, 'Kilogram', 'kg')");
    $unit_id = 1;
    $output[] = "Created new stock unit ID: 1";
}

// 5. Ensure a seller exists assigned to deliverable_area_id = 1 (Gorakhpur)
$seller_check = $db->query("SELECT id FROM seller WHERE email = 'store@cityloopapp.com'");
if ($seller_check->num_rows == 0) {
    $pass_hash = password_hash('12345678', PASSWORD_DEFAULT);
    $db->query("INSERT INTO `seller` (`id`, `name`, `store_name`, `slug`, `email`, `password`, `mobile`, `balance`, `logo`, `store_address`, `city_id`, `deliverable_area_id`, `commission`, `status`, `is_delete`, `registered_at`, `created_at`, `updated_at`) VALUES (1, 'CityLoop Gorakhpur Store', 'CityLoop Gorakhpur', 'cityloop-gorakhpur', 'store@cityloopapp.com', '$pass_hash', '9876543210', 1000.00, 'assets/dist/img/tekchi_logo.png', 'Gorakhpur, UP', 1, 1, 10.00, 1, 0, NOW(), NOW(), NOW())");
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
        'disc_price' => 120.00
    ],
    [
        'id' => 2,
        'name' => 'Organic Bananas',
        'slug' => 'organic-bananas',
        'desc' => 'Rich in potassium, fresh organic yellow bananas.',
        'variant_title' => '1 Dozen',
        'price' => 80.00,
        'disc_price' => 60.00
    ],
    [
        'id' => 3,
        'name' => 'Fresh Milk',
        'slug' => 'fresh-milk',
        'desc' => 'Pure pasteurized whole milk, fresh and healthy.',
        'variant_title' => '1 Litre',
        'price' => 70.00,
        'disc_price' => 64.00
    ],
    [
        'id' => 4,
        'name' => 'Brown Bread',
        'slug' => 'brown-bread',
        'desc' => 'Whole wheat fiber-rich brown bread freshly baked.',
        'variant_title' => '400g Pack',
        'price' => 45.00,
        'disc_price' => 40.00
    ],
    [
        'id' => 5,
        'name' => 'Fresh Potatoes',
        'slug' => 'fresh-potatoes',
        'desc' => 'Fresh locally grown farm potatoes, perfect for daily cooking.',
        'variant_title' => '2 kg',
        'price' => 60.00,
        'disc_price' => 50.00
    ]
];

foreach ($products_data as $p) {
    $db->query("INSERT INTO `product` (`id`, `brand_id`, `seller_id`, `row_order`, `product_name`, `slug`, `main_img`, `description`, `status`, `date`, `popular`, `deal_of_the_day`, `is_delete`, `added_by_seller`) 
                VALUES ({$p['id']}, $brand_id, 1, 1, '{$p['name']}', '{$p['slug']}', 'assets/dist/img/tekchi_logo.png', '{$p['desc']}', 1, NOW(), 1, 1, 0, 0)
                ON DUPLICATE KEY UPDATE status=1, is_delete=0");
                
    $db->query("INSERT INTO `product_category` (`product_id`, `category_id`) VALUES ({$p['id']}, $cat_id) ON DUPLICATE KEY UPDATE category_id=$cat_id");
    
    $db->query("INSERT INTO `product_variants` (`product_id`, `status`, `title`, `price`, `discounted_price`, `stock`, `is_unlimited_stock`, `stock_unit_id`, `is_delete`) 
                VALUES ({$p['id']}, 1, '{$p['variant_title']}', {$p['price']}, {$p['disc_price']}, 100, 0, $unit_id, 0)
                ON DUPLICATE KEY UPDATE status=1, is_delete=0, price={$p['price']}, discounted_price={$p['disc_price']}, stock=100");
}

$output[] = "5 Premium products seeded with categories and variants.";

echo json_encode(["success" => true, "logs" => $output], JSON_PRETTY_PRINT);
$db->close();
