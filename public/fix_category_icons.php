<?php
/**
 * Seed category + subcategory icons from blinkit_category_icons.json
 * Downloads via server (image-proxy path) so CDN 403 from local PCs is avoided.
 *
 * ?key=cityloop_img_fix_2026&action=status|apply|download
 */
declare(strict_types=1);
set_time_limit(0);
header('Content-Type: application/json; charset=utf-8');

const FIX_KEY = 'cityloop_img_fix_2026';
const MAP_FILE = __DIR__ . '/data/blinkit_category_icons.json';

$key = $_GET['key'] ?? '';
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

function load_map(): array
{
    if (!is_file(MAP_FILE)) {
        throw new RuntimeException('Missing blinkit_category_icons.json');
    }
    $d = json_decode(file_get_contents(MAP_FILE), true);
    if (!is_array($d)) {
        throw new RuntimeException('Invalid JSON');
    }
    return $d;
}

function slugify(string $s): string
{
    $s = strtolower(trim($s));
    $s = preg_replace('/[^a-z0-9]+/', '_', $s) ?? $s;
    return trim($s, '_');
}

function normalize_cdn(string $url): string
{
    $url = str_replace('http://', 'https://', $url);
    return $url;
}

function download_icon(string $url, string $destAbs): bool
{
    $url = normalize_cdn($url);
    $dir = dirname($destAbs);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    if (is_file($destAbs) && filesize($destAbs) > 400) {
        return true;
    }
    $candidates = [
        $url,
        'https://cityloopapp.com/api/v1_6/customer/image-proxy?url=' . rawurlencode($url),
    ];
    foreach ($candidates as $candidate) {
        $ch = curl_init($candidate);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 35,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; CityLoopIconSeed/1.0)',
            CURLOPT_HTTPHEADER => [
                'Referer: https://blinkit.com/',
                'Accept: image/avif,image/webp,image/apng,image/*,*/*;q=0.8',
            ],
        ]);
        $data = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($code === 200 && is_string($data) && strlen($data) > 400 && !str_starts_with(ltrim($data), '<!DOCTYPE')) {
            file_put_contents($destAbs, $data);
            return true;
        }
    }
    return false;
}

function name_score(string $a, string $b): float
{
    $a = strtolower(preg_replace('/[^a-z0-9]+/', ' ', $a) ?? $a);
    $b = strtolower(preg_replace('/[^a-z0-9]+/', ' ', $b) ?? $b);
    if ($a === $b) {
        return 1.0;
    }
    if (str_contains($a, $b) || str_contains($b, $a)) {
        return 0.85;
    }
    similar_text($a, $b, $pct);
    return $pct / 100.0;
}

function best_match(string $name, array $candidates): ?array
{
    $best = null;
    $bestScore = 0.45;
    foreach ($candidates as $c) {
        $score = name_score($name, $c['name'] ?? '');
        if ($score > $bestScore) {
            $bestScore = $score;
            $best = $c;
            $best['__score'] = $score;
        }
    }
    return $best;
}

try {
    $map = load_map();
    $catsMap = $map['categories'] ?? [];
    $subsMap = $map['subcategories'] ?? [];

    if ($action === 'status') {
        $catTotal = (int) ($db->query('SELECT COUNT(*) c FROM category')->fetch_assoc()['c'] ?? 0);
        $catWith = (int) ($db->query("SELECT COUNT(*) c FROM category WHERE category_img IS NOT NULL AND category_img != '' AND category_img NOT LIKE '%cityloopapp.com/' AND category_img NOT LIKE 'https://cityloopapp.com' AND category_img NOT LIKE 'http://cityloopapp.com%'")->fetch_assoc()['c'] ?? 0);
        // subcategory table uses `img` (legacy) — no is_delete on base schema in some dumps
        $subTotal = 0;
        $subWith = 0;
        try {
            $subTotal = (int) ($db->query('SELECT COUNT(*) c FROM subcategory')->fetch_assoc()['c'] ?? 0);
            $subWith = (int) ($db->query("SELECT COUNT(*) c FROM subcategory WHERE img IS NOT NULL AND img != '' AND img NOT LIKE '%cityloopapp.com/' AND img NOT LIKE 'https://cityloopapp.com'")->fetch_assoc()['c'] ?? 0);
        } catch (Throwable $e) {
            /* ignore */
        }
        echo json_encode([
            'ok' => true,
            'map_categories' => count($catsMap),
            'map_subcategories' => count($subsMap),
            'db_categories' => $catTotal,
            'db_categories_with_img' => $catWith,
            'db_subcategories' => $subTotal,
            'db_subcategories_with_img' => $subWith,
        ], JSON_PRETTY_PRINT);
        exit;
    }

    if ($action === 'apply' || $action === 'download') {
        $doDownload = $action === 'download';
        $updatedCats = 0;
        $updatedSubs = 0;
        $downloaded = 0;
        $proxyFallback = 0;

        // --- Categories ---
        $exactCategoryIcons = [
            'Vegetables & Fruits' => 'vegetables_fruits.png',
            'Dairy, Bread & Eggs' => 'dairy_bread_eggs.png',
            'Munchies & Snacks' => 'chips_namkeen.png',
            'Bakery & Biscuits' => 'bakery_biscuits.png',
            'Cold Drinks & Juices' => 'drinks_juices.png',
            'Tea, Coffee & Health Drinks' => 'tea_coffee_milk_drinks.png',
            'Instant & Frozen Food' => 'instant_food.png',
            'Atta, Rice & Dal' => 'atta_rice_dal.png',
            'Chicken, Meat & Fish' => 'chicken_meat_fish.png',
            'Cleaning & Household' => 'cleaners_repellents.png',
            'Personal Care' => 'bath_body.png',
            'Feminine Hygiene & Care' => 'feminine_hygiene.png',
            'Baby Care' => 'baby_care.png',
            'Pharma & Wellness' => 'health_pharma.png',
            'Home & Kitchen' => 'home_lifestyle.png',
        ];
        $res = $db->query('SELECT id, category_name, category_img FROM category');
        $stmt = $db->prepare('UPDATE category SET category_img = ? WHERE id = ?');
        while ($row = $res->fetch_assoc()) {
            $exactFile = $exactCategoryIcons[$row['category_name']] ?? null;
            $match = best_match($row['category_name'], $catsMap);
            if (!$match || empty($match['icon_url'])) {
                if (!$exactFile) continue;
            }
            $cdn = normalize_cdn($match['icon_url'] ?? '');
            $pathRel = 'uploads/category/' . ($exactFile ?: slugify($match['name']) . '.png');
            $pathAbs = __DIR__ . '/' . $pathRel;
            $final = $pathRel;

            if ($doDownload) {
                if (download_icon($cdn, $pathAbs)) {
                    $downloaded++;
                } else {
                    // Use image-proxy path so frontend still gets the Blinkit asset
                    $final = 'api/v1_6/customer/image-proxy?url=' . rawurlencode($cdn);
                    $proxyFallback++;
                }
            } else {
                // Prefer local if already exists, else proxy
                if (is_file($pathAbs) && filesize($pathAbs) > 400) {
                    $final = $pathRel;
                } else {
                    $final = 'api/v1_6/customer/image-proxy?url=' . rawurlencode($cdn);
                    $proxyFallback++;
                }
            }

            $id = (int) $row['id'];
            $stmt->bind_param('si', $final, $id);
            $stmt->execute();
            $updatedCats++;
        }
        $stmt->close();

        // --- Subcategories (column is `img`) ---
        $res2 = $db->query('SELECT id, name, img FROM subcategory');
        $stmt2 = $db->prepare('UPDATE subcategory SET img = ? WHERE id = ?');
        if ($res2 && $stmt2) {
            while ($row = $res2->fetch_assoc()) {
                $match = best_match($row['name'], $subsMap);
                if (!$match || empty($match['icon_url'])) {
                    continue;
                }
                $cdn = normalize_cdn($match['icon_url']);
                $pathRel = 'uploads/subcategory/' . slugify(($match['parent'] ?? '') . '_' . $match['name']) . '.png';
                $pathAbs = __DIR__ . '/' . $pathRel;
                $final = $pathRel;
                if ($doDownload) {
                    if (download_icon($cdn, $pathAbs)) {
                        $downloaded++;
                    } else {
                        $final = 'api/v1_6/customer/image-proxy?url=' . rawurlencode($cdn);
                        $proxyFallback++;
                    }
                } else {
                    if (is_file($pathAbs) && filesize($pathAbs) > 400) {
                        $final = $pathRel;
                    } else {
                        $final = 'api/v1_6/customer/image-proxy?url=' . rawurlencode($cdn);
                        $proxyFallback++;
                    }
                }
                $id = (int) $row['id'];
                $stmt2->bind_param('si', $final, $id);
                $stmt2->execute();
                $updatedSubs++;
            }
            $stmt2->close();
        }

        echo json_encode([
            'ok' => true,
            'action' => $action,
            'updated_categories' => $updatedCats,
            'updated_subcategories' => $updatedSubs,
            'downloaded_files' => $downloaded,
            'proxy_urls' => $proxyFallback,
        ], JSON_PRETTY_PRINT);
        exit;
    }

    if ($action === 'save_one') {
        $input = json_decode(file_get_contents('php://input') ?: '{}', true) ?: [];
        $kind = $input['kind'] ?? '';
        $name = trim((string) ($input['name'] ?? ''));
        $image = trim((string) ($input['image'] ?? ''));
        if ($name === '' || $image === '') {
            throw new RuntimeException('name and image required');
        }
        if ($kind === 'category') {
            $stmt = $db->prepare('UPDATE category SET category_img = ? WHERE category_name = ?');
            $stmt->bind_param('ss', $image, $name);
            $stmt->execute();
            $n = $stmt->affected_rows;
            // fuzzy: if 0 rows, try LIKE
            if ($n <= 0) {
                $like = '%' . $db->real_escape_string($name) . '%';
                $db->query("UPDATE category SET category_img = '" . $db->real_escape_string($image) . "' WHERE category_name LIKE '$like' LIMIT 1");
                $n = $db->affected_rows;
            }
            echo json_encode(['ok' => true, 'kind' => 'category', 'name' => $name, 'affected' => $n, 'image' => $image]);
            exit;
        }
        if ($kind === 'subcategory') {
            $stmt = $db->prepare('UPDATE subcategory SET img = ? WHERE name = ?');
            $n = 0;
            if ($stmt) {
                $stmt->bind_param('ss', $image, $name);
                $stmt->execute();
                $n = $stmt->affected_rows;
            }
            echo json_encode(['ok' => true, 'kind' => 'subcategory', 'name' => $name, 'affected' => $n, 'image' => $image]);
            exit;
        }
        throw new RuntimeException('kind must be category or subcategory');
    }

    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Unknown action']);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
