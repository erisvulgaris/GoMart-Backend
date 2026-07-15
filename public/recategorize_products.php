<?php
/**
 * Re-assign every product to Blinkit-style categories by product_name keywords.
 * ?key=cityloop_img_fix_2026&action=run|status
 *
 * Category map (CityLoop):
 *  1 Vegetables & Fruits | 2 Dairy, Bread & Eggs | 3 Munchies & Snacks
 *  4 Bakery & Biscuits | 5 Cold Drinks & Juices | 6 Tea, Coffee & Health Drinks
 *  7 Instant & Frozen Food | 8 Atta, Rice & Dal | 9 Chicken, Meat & Fish
 * 10 Cleaning & Household | 11 Personal Care | 12 Feminine Hygiene & Care
 * 13 Baby Care | 14 Pharma & Wellness | 15 Sexual Wellness | 16 Home & Kitchen
 */
declare(strict_types=1);
set_time_limit(0);
ini_set('memory_limit', '512M');
header('Content-Type: application/json; charset=utf-8');

const FIX_KEY = 'cityloop_img_fix_2026';
if (!hash_equals(FIX_KEY, (string) ($_GET['key'] ?? ''))) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Forbidden']);
    exit;
}

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

/** @return array{0:int,1:int} category_id, subcategory_id (sub 0 if unknown) */
function classify(string $name): array
{
    $n = mb_strtolower($name);

    // Order matters — most specific first (T24: Non-food & specialized categories evaluated before Produce/Dairy/Staples)
    $rules = [
        // 15 Sexual wellness
        [15, 0, '/condom|durex|kamasutra|lubricant|lube|massager|vibrator|intimate wash|sexual/'],
        // 13 Baby Care
        [13, 0, '/diaper|pampers|huggies|baby wipes|baby soap|baby shampoo|cerelac|infant|baby care|nappy|wipes baby|johnson.?s baby|mamy|littles|baby oil|feeding bottle/'],
        // 12 Feminine Hygiene
        [12, 0, '/sanitary|whisper|stayfree|sofy|tampon|panty liner|menstrual|feminine|v wash|intimate hygiene/'],
        // 14 Pharma (real medical — avoid "healthy" snacks)
        [14, 0, '/paracetamol|dolo|crocin|disprin|aspirin|ibuprofen|antacid|digene|eno|ors|electral|volini|moov|iodex|vicks|benadryl|cough syrup|band.?aid|bandage|betadine|antiseptic|neosporin|thermometer|glucometer|oximeter|face mask|n95|sanitizer gel|hand sanitizer|first aid|medical|pharma|dolo-?650|cetrizine|cetirizine|zincovit|becosules|supradyn|revital|shelcal|calcium tablet|multivitamin|protein powder|ensure|pediasure|horlicks women|muscleblaze|whey protein|pain relief|reliev|balm|pain|fever|antibiotic|ointment|eye drop|nasal spray|volini|moov spray|iodex|digene|eno sachet|melatonin|supplement|gummy|gummies|capsule|tablet\b/'],
        // 10 Cleaning & Household
        [10, 0, '/detergent|surf excel|ariel|tide|wheel|vim|pril|harpic|lizol|colin|comfort|hit|baygon|repellent|disinfectant|floor cleaner|toilet cleaner|dishwash|phenyl|garbage bag|tissue|napkin|foil|scrub|cleaner|domex|harpic|lizol|room freshener|odonil/'],
        // 11 Personal care
        [11, 0, '/shampoo|conditioner|soap|body wash|face wash|face cream|moisturizer|sunscreen|lotion|deodorant|perfume|toothpaste|toothbrush|colgate|close.?up|sensodyne|razor|shaving|hair oil|hair gel|nivea|pond.?s|vaseline|fair.?lovely|garnier|loreal|dove|pears|dettol soap|clinic plus|pantene|head.?shoulders|handwash|hand wash|talc|powder|lipstick|makeup|kajal|foundation|serum|toner|scrub|face pack/'],
        // 16 Home & kitchen / decor / stationery
        [16, 0, '/decor|candle|frame|vase|cushion|bedsheet|curtain|lamp|bulb|led|battery|duracell|matchbox|match box|lighter|kitchen tool|utensil|sink|pan|kadhai|tawa|bottle|flask|tiffin|container|storage|hanger|clip|stationery|notebook|pen|pencil|glue|tape|foil|aluminium|plastic bag|zip lock|broom|mop|bucket|mug|plate|spoon|fork|glass set|home.?kitchen|pooja|agarbatti|incense|diya/'],
        // 5 Cold drinks & Juices
        [5, 0, '/coke|pepsi|sprite|fanta|thums up|limca|maaza|frooti|slice|juice|cold drink|soft drink|soda|red bull|monster|energy drink|bisleri|kinley|mineral water|packaged water|water|drink|sparkling|beverage|tropicana|real juice|appy|paper boat/'],
        // 6 Tea coffee health drinks (not pharma)
        [6, 0, '/\btea\b|chai|coffee|nescafe|bru|bournvita|horlicks|complan|boost|green tea|black tea|tata tea|red label|taj mahal|health drink|malted|cocoa powder drink/'],
        // 4 Bakery biscuits
        [4, 0, '/biscuit|cookie|parle|oreo|bourbon|marie|hide.?seek|good day|rusk|khari|cake|muffin|pastry|croissant|bakery/'],
        // 3 Munchies & Snacks
        [3, 0, '/chips|lays|kurkure|bingo|namkeen|bhujia|mixture|sev|makhana|popcorn|nachos|pringles|snack|wafer|puffcorn|uncle chip|too yum|haldiram|bikaji|balaji|dry\s*fruit|dryfruit|almond|cashew|raisin|pista|kaju|badam|kishmish|walnut|dates\b|khajur|seeds?\b|candy|candies|toffees?|lollipops?|sweets|mithai|laddu|ladoo|peda|barfi|halwa|soan papdi|rasgulla|gulab jamun|crisps?|aloo\s*(lachha|bhujia|chips)/'],
        // 7 Instant / frozen / sauces — nut/chocolate spreads BEFORE dairy (peanut butter is NOT dairy)
        [7, 0, '/peanut butter|almond butter|cashew butter|hazelnut butter|nut butter|nutella|chocolate spread|fruit spread|sandwich spread|marmalade|jam\\b|jelly\\b|maggi|noodles|pasta|ketchup|sauce|mayonnaise|honey|ice cream|frozen|mccain|soup|oats|muesli|corn flakes|flakes\\b|chocos|cereal|instant|vermicelli|upma|ready to eat|pickle|achar|thokku|chutney|pop\\b|pops\\b|popsicle|kulfi/'],
        // 2 Dairy / eggs / bread — dairy butter only (not peanut/nut/chocolate "butter")
        [2, 0, '/\\bmilk\\b|doodh|curd|dahi|yogurt|yoghurt|paneer|(?<!(peanut|almond|cashew|nut|coco|hazelnut|sunflower|pumpkin|shea)\\s)butter|table butter|cooking butter|white butter|ghee|cheese|amul(?!\\s*dark\\s*chocolate)|mother dairy|toned milk|full cream|egg\\b|eggs\\b|brown egg|white egg|bread|pav|bun|sandwich bread|brown bread|white bread|cream cheese|lassi|buttermilk|chaas/'],
        // 8 Staples (Atta, Rice & Dal) — "wheat" alone is NOT atta (bread/biscuits use wheat too)
        [8, 0, '/atta|chakki|shudh chakki|multigrain atta|wheat atta|wheat flour|whole wheat atta|flour|maida|besan|suji|rava|rice|basmati|dal|toor|moong|masoor|chana|rajma|kabuli|soya chunk|poha|dalia|sattu|pulses|lentil|urad/'],
        // oil/masala under 8 (Staples)
        [8, 0, '/mustard oil|refined oil|olive oil|sunflower oil|groundnut oil|soyabean oil|oil\b|turmeric|haldi|jeera|cumin|masala|chilli powder|garam masala|hing|salt\b|sugar\b|jaggery|spice/'],
        // 9 Meat / eggs / fish
        [9, 0, '/chicken|mutton|fish|prawn|seafood|keema|sausage|salami|egg white liquid|raw chicken|boneless/'],
        // 1 Produce (Vegetables & Fruits) — include common Blinkit-style "Fresh …" titles
        [1, 0, '/\\b(fresh\\s+)?(onion|potato|tomato|aloo|pyaz|tamatar|ginger|garlic|adrak|lehsun|spinach|palak|bhindi|cabbage|cauliflower|carrot|cucumber|capsicum|lemon|banana|apple|mango|orange|grapes|papaya|watermelon|guava|pomegranate|coriander|mint|chilli|vegetable|fruits?|kela|seb|fresh green|beans|peas|mushroom|sweet corn|coconut|pineapple|kiwi|melon|broccoli|zucchini|avocado)s?\\b/i'],
    ];

    foreach ($rules as [$cat, $sub, $pat]) {
        if (preg_match($pat, $n)) {
            return [$cat, $sub];
        }
    }
    // default grocery munchies-ish → Home & Kitchen dump is better than wrong pharma
    return [16, 0];
}

/**
 * Map product name → subcategory_id for a given parent category (Blinkit-style aisles).
 * Returns 0 when no subcategory matches (category-level only).
 */
function classifySubcategoryId(string $name, int $categoryId, array $subIndex): int
{
    $n = mb_strtolower($name);
    $norm = static function (string $s): string {
        return preg_replace('/[^a-z0-9]+/', ' ', mb_strtolower($s)) ?? '';
    };

    $pick = static function (string $subName) use ($categoryId, $subIndex, $norm): int {
        $key = $categoryId . '|' . $norm($subName);
        return (int) ($subIndex[$key] ?? 0);
    };

    // --- Category 1: Vegetables & Fruits ---
    if ($categoryId === 1) {
        if (preg_match('/frozen\s*veg|frozen vegetable/i', $n)) {
            return $pick('Frozen Veg') ?: $pick('All');
        }
        if (preg_match('/hydroponic/i', $n)) {
            return $pick('Hydroponic') ?: $pick('All');
        }
        if (preg_match('/organic|organically/i', $n)) {
            return $pick('Trusted Organics') ?: $pick('All');
        }
        if (preg_match('/sprout|freshly cut|salad mix|cut fruit|cut veg/i', $n)) {
            return $pick('Freshly Cut & Sprouts') ?: $pick('All');
        }
        if (preg_match('/flower|marigold|rose\b|tulsi|leaves for pooja/i', $n)) {
            return $pick('Flowers & Leaves') ?: $pick('All');
        }
        if (preg_match('/\b(coriander|dhania|mint|pudina|curry leaves|spring onion|green chilli|adrak|ginger|lehsun|garlic|methi|fenugreek)\b/i', $n)) {
            return $pick('Coriander & Others') ?: $pick('All');
        }
        if (preg_match('/\b(avocado|broccoli|zucchini|dragon fruit|blueberry|raspberry|cherry tomato|bell pepper|asparagus|leek|exotic|kiwi)\b/i', $n)) {
            return $pick('Exotics') ?: $pick('All');
        }
        if (preg_match('/\b(litchi|strawberry|sitaphal|custard apple|jamun|seasonal|cherry\b)\b/i', $n)) {
            return $pick('Seasonal') ?: $pick('All');
        }
        if (preg_match('/\b(banana|apple|mango|orange|grapes|papaya|watermelon|guava|pomegranate|pineapple|melon|pear|fruit|kela|seb|kinnow|muskmelon|sapota|chikoo)\b/i', $n)
            && !preg_match('/tomato|potato|onion|vegetable|bhindi|palak/i', $n)) {
            return $pick('Fresh Fruits') ?: $pick('All');
        }
        if (preg_match('/\b(onion|potato|tomato|aloo|pyaz|spinach|palak|bhindi|cabbage|cauliflower|carrot|cucumber|capsicum|beans|peas|mushroom|sweet corn|vegetable|brinjal|pumpkin|beetroot|radish|lauki|tori|karela)\b/i', $n)) {
            return $pick('Fresh Vegetables') ?: $pick('All');
        }
        return $pick('All');
    }

    // --- Category 2: Dairy, Bread & Eggs ---
    if ($categoryId === 2) {
        if (preg_match('/peanut butter|almond butter|cashew butter|nut butter|nutella|chocolate spread|fruit spread|sandwich spread/i', $n)) {
            return 0;
        }
        if (preg_match('/\b(milk|doodh|toned|full cream|double toned|skimmed)\b/i', $n) && !preg_match('/chocolate|shake|milk cake/i', $n)) {
            return $pick('Milk') ?: $pick('All');
        }
        if (preg_match('/\b(bread|pav|bun|sandwich bread|brown bread|white bread)\b/i', $n)) {
            return $pick('Bread & Pav') ?: $pick('All');
        }
        if (preg_match('/\b(egg|eggs|brown egg|white egg)\b/i', $n)) {
            return $pick('Eggs') ?: $pick('All');
        }
        if (preg_match('/\b(curd|dahi|yogurt|yoghurt)\b/i', $n)) {
            return $pick('Curd & Yogurt') ?: $pick('All');
        }
        if (preg_match('/\b(paneer|tofu)\b/i', $n)) {
            return $pick('Paneer & Tofu') ?: $pick('All');
        }
        if (preg_match('/\b(batter|idli batter|dosa batter)\b/i', $n)) {
            return $pick('Batter') ?: $pick('All');
        }
        if (preg_match('/\b(lassi|buttermilk|chaas)\b/i', $n)) {
            return $pick('Lassi & More') ?: $pick('Curd & Yogurt') ?: $pick('All');
        }
        if (preg_match('/\b(cheese|(?<!(peanut|almond|cashew|nut|coco|hazelnut)\s)butter|ghee)\b/i', $n)) {
            return $pick('Cheese & Butter') ?: $pick('All');
        }
        return $pick('All');
    }

    return 0;
}

/** @return array<string,int> keys "categoryId|normalized sub name" */
function loadSubcategoryIndex(mysqli $db): array
{
    $index = [];
    $res = $db->query('SELECT id, category_id, name FROM subcategory');
    if (!$res) {
        return $index;
    }
    while ($row = $res->fetch_assoc()) {
        $cid = (int) ($row['category_id'] ?? 0);
        $name = (string) ($row['name'] ?? '');
        if ($cid <= 0 || $name === '') {
            continue;
        }
        $norm = preg_replace('/[^a-z0-9]+/', ' ', mb_strtolower($name)) ?? '';
        $index[$cid . '|' . trim($norm)] = (int) $row['id'];
    }
    return $index;
}

$action = $_GET['action'] ?? 'status';

if ($action === 'status') {
    $total = (int) ($db->query('SELECT COUNT(*) c FROM product WHERE is_delete=0')->fetch_assoc()['c'] ?? 0);
    $mapped = (int) ($db->query('SELECT COUNT(DISTINCT product_id) c FROM product_categories')->fetch_assoc()['c'] ?? 0);
    $byCat = [];
    $res = $db->query(
        'SELECT c.id, c.category_name, COUNT(DISTINCT pc.product_id) cnt
         FROM category c
         LEFT JOIN product_categories pc ON pc.category_id = c.id
         GROUP BY c.id ORDER BY c.id'
    );
    while ($row = $res->fetch_assoc()) {
        $byCat[] = $row;
    }
    echo json_encode([
        'ok' => true,
        'products_total' => $total,
        'products_with_category_map' => $mapped,
        'by_category' => $byCat,
    ], JSON_PRETTY_PRINT);
    exit;
}

if ($action === 'debug') {
    $ids = [4399, 3932, 3872, 3867, 3866, 3803, 3800, 3687, 3686, 3685, 4298, 4387];
    $out = [];
    foreach ($ids as $id) {
        $row = $db->query("SELECT product_name FROM product WHERE id = $id")->fetch_assoc();
        if ($row) {
            $name = (string) $row['product_name'];
            [$cat] = classify($name);
            
            // Check current mapping
            $mapRow = $db->query("SELECT category_id FROM product_categories WHERE product_id = $id")->fetch_assoc();
            $mappedCat = $mapRow ? (int) $mapRow['category_id'] : null;
            
            $out[] = [
                'id' => $id,
                'name' => $name,
                'classified_cat' => $cat,
                'mapped_cat' => $mappedCat
            ];
        }
    }
    echo json_encode(['ok' => true, 'debug' => $out], JSON_PRETTY_PRINT);
    exit;
}

if ($action === 'run') {
    $subIndex = loadSubcategoryIndex($db);
    $res = $db->query('SELECT id, product_name FROM product WHERE is_delete=0');
    $counts = [];
    $subCounts = [];
    $updated = 0;
    $db->query('DELETE FROM product_categories');
    $db->query('DELETE FROM product_subcategories');

    $ins = $db->prepare('INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)');
    $insSub = $db->prepare('INSERT INTO product_subcategories (product_id, subcategory_id) VALUES (?, ?)');
    while ($row = $res->fetch_assoc()) {
        $pname = (string) $row['product_name'];
        [$cat] = classify($pname);
        $pid = (int) $row['id'];
        $ins->bind_param('ii', $pid, $cat);
        $ins->execute();
        $counts[$cat] = ($counts[$cat] ?? 0) + 1;

        $subId = classifySubcategoryId($pname, $cat, $subIndex);
        if ($subId > 0) {
            $insSub->bind_param('ii', $pid, $subId);
            $insSub->execute();
            $subCounts[$subId] = ($subCounts[$subId] ?? 0) + 1;
        }
        $updated++;
    }
    $ins->close();
    $insSub->close();

    echo json_encode([
        'ok' => true,
        'reassigned' => $updated,
        'counts_by_category_id' => $counts,
        'counts_by_subcategory_id' => $subCounts,
        'next' => 'Reload app — categories + subcategory aisles use product_categories / product_subcategories',
    ], JSON_PRETTY_PRINT);
    exit;
}

http_response_code(400);
echo json_encode(['ok' => false, 'error' => 'Unknown action']);
