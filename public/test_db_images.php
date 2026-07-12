<?php
header('Content-Type: application/json');

$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => $db->connect_error]);
    exit;
}

$webp = $db->query("SELECT COUNT(*) as count FROM product WHERE main_img LIKE '%.webp%'")->fetch_assoc()['count'];
$placeholder = $db->query("SELECT COUNT(*) as count FROM product WHERE main_img = 'uploads/products/placeholder.png'")->fetch_assoc()['count'];
$external = $db->query("SELECT COUNT(*) as count FROM product WHERE main_img LIKE 'http%'")->fetch_assoc()['count'];

$sample_webp = [];
$res = $db->query("SELECT id, product_name, main_img FROM product WHERE main_img LIKE '%.webp%' LIMIT 5");
while ($row = $res->fetch_assoc()) {
    $sample_webp[] = $row;
}

echo json_encode([
    "webp_count" => $webp,
    "placeholder_count" => $placeholder,
    "external_count" => $external,
    "sample_webp" => $sample_webp
], JSON_PRETTY_PRINT);
$db->close();
