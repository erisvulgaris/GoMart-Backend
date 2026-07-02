<?php
header('Content-Type: application/json');

$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

$data = [];

// header_category
$res = $db->query("SELECT * FROM header_category");
$data["header_category"] = [];
while ($row = $res->fetch_assoc()) {
    $data["header_category"][] = $row;
}

// section_categories
$res2 = $db->query("SELECT * FROM section_categories");
$data["section_categories"] = [];
while ($row = $res2->fetch_assoc()) {
    $data["section_categories"][] = $row;
}

// seller_categories
$res3 = $db->query("SELECT * FROM seller_categories");
$data["seller_categories"] = [];
while ($row = $res3->fetch_assoc()) {
    $data["seller_categories"][] = $row;
}

echo json_encode($data);
$db->close();
