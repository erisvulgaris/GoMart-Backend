<?php
header('Content-Type: application/json');

$db = @new mysqli('db', 'gomart', 'gomart_secure_pass', 'gomart');
if ($db->connect_error) {
    $db = @new mysqli('127.0.0.1', 'gomart', 'gomart_secure_pass', 'gomart');
}
if ($db->connect_error) {
    echo json_encode(['error' => $db->connect_error]);
    exit;
}

$res = $db->query("
    SELECT p.id, p.product_name, p.status, p.is_delete 
    FROM product p 
    JOIN product_categories pc ON pc.product_id = p.id 
    WHERE pc.category_id = 1
    ORDER BY p.id DESC
    LIMIT 100
");

$out = [];
while ($row = $res->fetch_assoc()) {
    $p = $row['product_name'];
    
    $snack = '/\b(chips?|namkeen|kurkure|lays|bingo|chocolate|muesli|kellogg|yoga\s*bar|biscuit|cookie|maggi|ketchup|pickle|achar|cereal|flakes|drink\s*mix|soda|cola|pepsi|coke)\b/i';
    $produceJunk = '/\b(ketchup|muesli|chips?|juice|drink\s*mix|tropicana|tang\b|paper\s*boat|real\s+fruit|delight\s+(juice|drink)|instant\s+drink|pasta\s*sauce|pizza\s*sauce|chutney|puree|salsa|baked\s*beans|ragu|veeba|heinz)\b/i';
    $produceNonFood = '/\b(dove|nivea|aqualogica|dettol|medimix|lifebuoy|lux\b|pears\b|himalaya|garnier|loreal|ponds|vaseline|colgate|forest\s*essentials|exo\b|vim\b|harpic|lizol|sunscreen|moisturizer|moisturis|hand\s*wash|body\s*polish|body\s*scrub|face\s*(wash|cleanser)|cleanser|mouthwash|dishwash|dishwashing|shampoo|conditioner|toothpaste|deodorant|perfume|lotion|serum|soap\b|cream\b|wipes?)\b/i';
    
    $is_snack = preg_match($snack, $p);
    $is_junk = preg_match($produceJunk, $p);
    $is_nonfood = preg_match($produceNonFood, $p);
    $fits = !($is_snack || $is_junk || $is_nonfood);
    
    $out[] = [
        'id' => $row['id'],
        'product_name' => $row['product_name'],
        'status' => $row['status'],
        'is_delete' => $row['is_delete'],
        'is_snack' => $is_snack,
        'is_junk' => $is_junk,
        'is_nonfood' => $is_nonfood,
        'fits' => $fits
    ];
}

echo json_encode($out, JSON_PRETTY_PRINT);
