<?php
/**
 * Unlock catalog stock so ADD does not fail with "Insufficient stock".
 * ?key=cityloop_img_fix_2026&action=stock|status
 */
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

const FIX_KEY = 'cityloop_img_fix_2026';
$key = $_GET['key'] ?? $_SERVER['HTTP_X_FIX_KEY'] ?? '';
if (!hash_equals(FIX_KEY, (string) $key)) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Forbidden']);
    exit;
}

$action = $_GET['action'] ?? 'status';
$db = @new mysqli('db', 'gomart', 'gomart_secure_pass', 'gomart');
if ($db->connect_error) {
    $db = @new mysqli('127.0.0.1', 'gomart', 'gomart_secure_pass', 'gomart');
}
if ($db->connect_error) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $db->connect_error]);
    exit;
}
$db->set_charset('utf8mb4');

if ($action === 'status') {
    $total = (int) ($db->query('SELECT COUNT(*) c FROM product_variants WHERE is_delete=0')->fetch_assoc()['c'] ?? 0);
    $zero = (int) ($db->query('SELECT COUNT(*) c FROM product_variants WHERE is_delete=0 AND is_unlimited_stock=0 AND stock<=0')->fetch_assoc()['c'] ?? 0);
    $unlimited = (int) ($db->query('SELECT COUNT(*) c FROM product_variants WHERE is_delete=0 AND is_unlimited_stock=1')->fetch_assoc()['c'] ?? 0);
    echo json_encode([
        'ok' => true,
        'variants_total' => $total,
        'variants_zero_stock' => $zero,
        'variants_unlimited' => $unlimited,
    ], JSON_PRETTY_PRINT);
    exit;
}

if ($action === 'stock') {
    // Make catalog buyable: stock 100 for limited variants that are empty
    $db->query('UPDATE product_variants SET stock = 100 WHERE is_delete = 0 AND is_unlimited_stock = 0 AND (stock IS NULL OR stock <= 0)');
    $a = $db->affected_rows;
    // Also ensure any remaining zero gets unlimited as safety net
    $db->query('UPDATE product_variants SET is_unlimited_stock = 1 WHERE is_delete = 0 AND is_unlimited_stock = 0 AND (stock IS NULL OR stock <= 0)');
    $b = $db->affected_rows;
    $zero = (int) ($db->query('SELECT COUNT(*) c FROM product_variants WHERE is_delete=0 AND is_unlimited_stock=0 AND stock<=0')->fetch_assoc()['c'] ?? 0);
    echo json_encode([
        'ok' => true,
        'action' => 'stock',
        'set_stock_100_rows' => $a,
        'set_unlimited_rows' => $b,
        'variants_zero_stock_remaining' => $zero,
    ], JSON_PRETTY_PRINT);
    exit;
}

http_response_code(400);
echo json_encode(['ok' => false, 'error' => 'Unknown action']);
