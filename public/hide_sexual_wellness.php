<?php
/**
 * One-shot: hide Sexual Wellness category (id 15 / name match) from platform.
 * Renames + moves row_order so admin can still recover; customer API also hard-filters.
 *
 * Open: /hide_sexual_wellness.php?key=cityloop_admin
 */
header('Content-Type: application/json; charset=utf-8');

$key = $_GET['key'] ?? '';
if ($key !== 'cityloop_admin') {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Forbidden']);
    exit;
}

// Bootstrap CodeIgniter database via env / common paths
$paths = [
    __DIR__ . '/../app/Config/Database.php',
];

// Prefer CI4 connection if available
require_once __DIR__ . '/../vendor/autoload.php';

// Minimal DB from .env
$envFile = __DIR__ . '/../.env';
$host = 'localhost';
$user = 'root';
$pass = '';
$dbName = 'groceryhub';
$port = 3306;
if (is_file($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        if (!str_contains($line, '=')) continue;
        [$k, $v] = array_map('trim', explode('=', $line, 2));
        $v = trim($v, "\"'");
        if ($k === 'database.default.hostname') $host = $v;
        if ($k === 'database.default.username') $user = $v;
        if ($k === 'database.default.password') $pass = $v;
        if ($k === 'database.default.database') $dbName = $v;
        if ($k === 'database.default.port') $port = (int) $v;
    }
}

try {
    $mysqli = new mysqli($host, $user, $pass, $dbName, $port);
    if ($mysqli->connect_error) {
        throw new RuntimeException($mysqli->connect_error);
    }

    $affected = 0;
    // Hide by renaming slug/name so listings that miss API filter still look deactivated
    $sql = "UPDATE category
            SET category_name = CONCAT('[HIDDEN] ', category_name),
                slug = CONCAT('hidden-', slug),
                row_order = 9999,
                is_bestseller_category = 0
            WHERE (id = 15 OR category_name LIKE '%Sexual%Wellness%' OR slug LIKE '%sexual%')
              AND category_name NOT LIKE '[HIDDEN]%'";
    if ($mysqli->query($sql)) {
        $affected = $mysqli->affected_rows;
    }

    // Soft-hide products only linked to sexual wellness (optional safety: mark name prefix)
    $prodSql = "UPDATE product p
                INNER JOIN product_category pc ON pc.product_id = p.id
                SET p.product_name = CONCAT('[HIDDEN] ', p.product_name)
                WHERE pc.category_id = 15
                  AND p.product_name NOT LIKE '[HIDDEN]%'
                LIMIT 500";
    $prodAffected = 0;
    if ($mysqli->query($prodSql)) {
        $prodAffected = $mysqli->affected_rows;
    }

    // List remaining visible categories
    $res = $mysqli->query("SELECT id, category_name, slug, row_order FROM category ORDER BY row_order ASC");
    $cats = [];
    while ($row = $res->fetch_assoc()) {
        $cats[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'categories_hidden' => $affected,
        'products_prefixed' => $prodAffected,
        'categories' => $cats,
        'message' => 'Sexual Wellness removed from customer listing (hidden in DB + API filter).',
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
