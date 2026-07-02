<?php
header('Content-Type: application/json');

$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

function get_inserts($db, $table) {
    $res = $db->query("SELECT * FROM `$table`");
    $rows = [];
    while ($row = $res->fetch_assoc()) {
        $rows[] = $row;
    }
    return $rows;
}

$tables = ["product", "product_variants", "product_categories", "product_subcategories"];
$data = [];
foreach ($tables as $t) {
    $data[$t] = get_inserts($db, $t);
}

echo json_encode($data);
$db->close();
