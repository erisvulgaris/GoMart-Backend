<?php
header('Content-Type: application/json');
$db = new mysqli("db", "gomart", "gomart_secure_pass", "gomart");
if ($db->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $db->connect_error]);
    exit;
}

$user_id = 2;
// Mock fetchAddressList logic
$addressList = [];
$res = $db->query("SELECT * FROM address WHERE user_id = $user_id AND is_delete = 0");
while ($row = $res->fetch_assoc()) {
    $addressList[] = $row;
}

$output = [];
foreach ($addressList as $address) {
    $output[] = [
        "id" => $address['id'],
        "name" => $address['user_name'],
        "phone" => $address['user_mobile'],
        "address_type" => $address['address_type'],
        "addressLines" => [
            $address['flat'] . ", " . $address['address'],
            $address['area'] . ", " . $address['city'],
            $address['state'] . ", " . $address['pincode'],
        ],
        "bgColor" => $address['status'] == 1 ? 'bg-[#FFF4F1]' : 'bg-[#F7F7F7]',
        "borderColor" => $address['status'] == 1 ? 'border-red-400' : 'bg-[#F7F7F7]',
        "is_active" => $address['status']
    ];
}

echo json_encode([
    "status" => "success",
    "result" => "true",
    "message" => "Address list found",
    "data" => $output
], JSON_PRETTY_PRINT);

$db->close();
