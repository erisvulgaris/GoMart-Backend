<?php
header('Content-Type: application/json');
$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

$tables = [];
$res = $db->query("SHOW TABLES");
while ($row = $res->fetch_array()) {
    $table = $row[0];
    $count_res = $db->query("SELECT COUNT(*) as cnt FROM `$table`");
    $cnt = $count_res ? $count_res->fetch_assoc()['cnt'] : "error";
    $tables[$table] = $cnt;
}

// Check if there are active sellers
$sellers = [];
$sel_res = $db->query("SELECT id, name, status, city_id, is_delete FROM seller");
if ($sel_res) {
    while ($r = $sel_res->fetch_assoc()) {
        $sellers[] = $r;
    }
}

// Check some products
$products = [];
$prod_res = $db->query("SELECT id, product_name, status, is_delete, seller_id FROM product LIMIT 5");
if ($prod_res) {
    while ($r = $prod_res->fetch_assoc()) {
        $products[] = $r;
    }
}

echo json_encode([
    "tables" => $tables,
    "sellers" => $sellers,
    "sample_products" => $products
], JSON_PRETTY_PRINT);

$db->close();
