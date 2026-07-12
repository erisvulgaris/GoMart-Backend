<?php
// Temporary diagnostic script to inspect production database table counts
header('Content-Type: application/json');

try {
    $dsn = 'mysql:host=db;dbname=' . getenv('MYSQL_DATABASE') . ';charset=utf8mb4';
    $user = getenv('MYSQL_USER');
    $password = getenv('MYSQL_PASSWORD');
    
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    $tables = ['product', 'product_variants', 'seller', 'city', 'deliverable_area', 'category', 'subcategory'];
    $results = [];
    
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM `$table`");
            $row = $stmt->fetch();
            $results[$table] = $row['cnt'];
        } catch (Exception $e) {
            $results[$table] = 'Error: ' . $e->getMessage();
        }
    }
    
    // Also fetch some seller details to check city_id
    try {
        $stmt = $pdo->query("SELECT id, name, city_id, deliverable_area_id, status FROM `seller` LIMIT 5");
        $results['seller_details'] = $stmt->fetchAll();
    } catch (Exception $e) {
        $results['seller_details_error'] = $e->getMessage();
    }
    
    // Also fetch active settings
    try {
        $stmt = $pdo->query("SELECT `key`, `value` FROM `settings` WHERE `key` IN ('website', 'business_name', 'map_api_key')");
        $results['settings'] = $stmt->fetchAll();
    } catch (Exception $e) {
        $results['settings_error'] = $e->getMessage();
    }
    
    echo json_encode($results, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT);
}
