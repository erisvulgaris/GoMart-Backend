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

    // Order matters — most specific first
    $rules = [
        // 15 Sexual wellness
        [15, 0, '/condom|durex|kamasutra|lubricant|lube|massager|vibrator|intimate wash|sexual/'],
        // 13 Baby
        [13, 0, '/diaper|pampers|huggies|baby wipes|baby soap|baby shampoo|cerelac|infant|baby care|nappy|wipes baby|johnson.?s baby|mamy|littles|baby oil|feeding bottle/'],
        // 12 Feminine
        [12, 0, '/sanitary|whisper|stayfree|sofy|tampon|panty liner|menstrual|feminine|v wash|intimate hygiene/'],
        // 14 Pharma (real medical — avoid "healthy" snacks)
        [14, 0, '/paracetamol|dolo|crocin|disprin|aspirin|ibuprofen|antacid|digene|eno|ors|electral|volini|moov|iodex|vicks|benadryl|cough syrup|band.?aid|bandage|betadine|antiseptic|neosporin|thermometer|glucometer|oximeter|face mask|n95|sanitizer gel|hand sanitizer|first aid|medical|pharma|dolo-?650|cetrizine|cetirizine|zincovit|becosules|supradyn|revital|shelcal|calcium tablet|multivitamin|protein powder|ensure|pediasure|horlicks women|muscleblaze|whey protein|pain relief|fever|antibiotic|ointment|eye drop|nasal spray|volini|moov spray|iodex|digene|eno sachet/'],
        // 9 Meat
        [9, 0, '/chicken|mutton|fish|prawn|seafood|keema|sausage|salami|egg white liquid|raw chicken|boneless/'],
        // 1 Produce
        [1, 0, '/onion|potato|tomato|aloo|pyaz|tamatar|ginger|garlic|adrak|lehsun|spinach|palak|bhindi|cabbage|cauliflower|carrot|cucumber|capsicum|lemon|banana|apple|mango|orange|grapes|papaya|watermelon|guava|pomegranate|coriander|mint|chilli|vegetable|fruit|kela|seb|fresh green|beans|peas|mushroom|sweet corn|coconut|pineapple|kiwi|melon/'],
        // 2 Dairy / eggs / bread (bread also bakery — dairy first for milk)
        [2, 0, '/\bmilk\b|doodh|curd|dahi|yogurt|yoghurt|paneer|butter|ghee|cheese|amul|mother dairy|toned milk|full cream|egg\b|eggs\b|brown egg|white egg|bread|pav|bun|sandwich bread|brown bread|white bread|cream cheese|lassi|buttermilk|chaas/'],
        // 8 Staples
        [8, 0, '/atta|flour|maida|besan|suji|rava|rice|basmati|dal|toor|moong|masoor|chana|rajma|kabuli|soya chunk|poha|dalia|sattu|wheat|pulses|lentil|urad/'],
        // 5 Cold drinks
        [5, 0, '/coke|pepsi|sprite|fanta|thums up|limca|maaza|frooti|slice|juice|cold drink|soft drink|soda|red bull|monster|energy drink|bisleri|kinley|mineral water|packaged water|sparkling|beverage|tropicana|real juice|appy|paper boat/'],
        // 6 Tea coffee health drinks (not pharma)
        [6, 0, '/\btea\b|chai|coffee|nescafe|bru|bournvita|horlicks|complan|boost|green tea|black tea|tata tea|red label|taj mahal|health drink|malted|cocoa powder drink/'],
        // 4 Bakery biscuits
        [4, 0, '/biscuit|cookie|parle|oreo|bourbon|marie|hide.?seek|good day|rusk|khari|cake|muffin|pastry|croissant|bakery/'],
        // 3 Munchies
        [3, 0, '/chips|lays|kurkure|bingo|namkeen|bhujia|mixture|sev|makhana|popcorn|nachos|pringles|snack|wafer|puffcorn|uncle chip|too yum|haldiram|bikaji|balaji/'],
        // 7 Instant / frozen / sauces
        [7, 0, '/maggi|noodles|pasta|ketchup|sauce|mayonnaise|jam|honey|peanut butter|ice cream|frozen|mccain|soup|oats|muesli|corn flakes|chocos|cereal|instant|vermicelli|upma|ready to eat|pickle|achar|thokku|chutney|spread/'],
        // oil/masala with staples-ish → 8 or 7; use masala as 8 adjacent — put oil/masala under 8 via kitchen
        [8, 0, '/mustard oil|refined oil|olive oil|sunflower oil|groundnut oil|soyabean oil|oil\b|turmeric|haldi|jeera|cumin|masala|chilli powder|garam masala|hing|salt\b|sugar\b|jaggery|spice/'],
        // 11 Personal care
        [11, 0, '/shampoo|conditioner|soap|body wash|face wash|face cream|moisturizer|sunscreen|lotion|deodorant|perfume|toothpaste|toothbrush|colgate|close.?up|sensodyne|razor|shaving|hair oil|hair gel|nivea|pond.?s|vaseline|fair.?lovely|garnier|loreal|dove|pears|dettol soap|clinic plus|pantene|head.?shoulders|handwash|hand wash|talc|powder|lipstick|makeup|kajal|foundation|serum|toner|scrub|face pack/'],
        // 10 Cleaning
        [10, 0, '/detergent|surf excel|ariel|tide|wheel|vim|pril|harpic|lizol|colin|comfort|hit|baygon|repellent|disinfectant|floor cleaner|toilet cleaner|dishwash|phenyl|garbage bag|tissue|napkin|foil|scrub|cleaner|domex|harpic|lizol|room freshener|odonil/'],
        // 16 Home & kitchen / decor / stationery
        [16, 0, '/decor|candle|frame|vase|cushion|bedsheet|curtain|lamp|bulb|led|battery|duracell|matchbox|match box|lighter|kitchen tool|utensil|pan|kadhai|tawa|bottle|flask|tiffin|container|storage|hanger|clip|stationery|notebook|pen|pencil|glue|tape|foil|aluminium|plastic bag|zip lock|broom|mop|bucket|mug|plate|spoon|fork|glass set|home.?kitchen|pooja|agarbatti|incense|diya/'],
    ];

    foreach ($rules as [$cat, $sub, $pat]) {
        if (preg_match($pat, $n)) {
            return [$cat, $sub];
        }
    }
    // default grocery munchies-ish → Home & Kitchen dump is better than wrong pharma
    return [16, 0];
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

if ($action === 'run') {
    $res = $db->query('SELECT id, product_name FROM product WHERE is_delete=0');
    $counts = [];
    $updated = 0;
    $db->query('DELETE FROM product_categories');
    // keep subcats optional — clear and reinsert only when we know sub
    $db->query('DELETE FROM product_subcategories');

    $ins = $db->prepare('INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)');
    while ($row = $res->fetch_assoc()) {
        [$cat] = classify((string) $row['product_name']);
        $pid = (int) $row['id'];
        $ins->bind_param('ii', $pid, $cat);
        $ins->execute();
        $counts[$cat] = ($counts[$cat] ?? 0) + 1;
        $updated++;
    }
    $ins->close();

    echo json_encode([
        'ok' => true,
        'reassigned' => $updated,
        'counts_by_category_id' => $counts,
        'next' => 'Reload app — verticals & category aisles use product_categories',
    ], JSON_PRETTY_PRINT);
    exit;
}

http_response_code(400);
echo json_encode(['ok' => false, 'error' => 'Unknown action']);
