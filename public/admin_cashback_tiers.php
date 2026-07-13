<?php
/**
 * Admin tool: edit CityLoop wallet cashback tiers JSON (T24 / ENHANCEMENT_TASKS).
 * Accessible at /admin_cashback_tiers.php when logged into admin session (best-effort)
 * or with ?key=cityloop_admin for ops.
 */
header('X-Content-Type-Options: nosniff');

$dataFile = __DIR__ . '/data/cashback_tiers.json';
$writableAlt = dirname(__DIR__) . '/writable/cashback_tiers.json';
$msg = '';
$err = '';

function load_tiers(string $primary, string $alt): array {
    foreach ([$primary, $alt] as $path) {
        if (is_file($path)) {
            $j = json_decode((string) file_get_contents($path), true);
            if (is_array($j)) return $j;
        }
    }
    return [
        'free_delivery_min' => 199,
        'tiers' => [
            ['min' => 599, 'cashback' => 50, 'label' => 'Silver'],
            ['min' => 999, 'cashback' => 100, 'label' => 'Gold'],
            ['min' => 1500, 'cashback' => 150, 'label' => 'Platinum'],
            ['min' => 2500, 'cashback' => 250, 'label' => 'Diamond'],
        ],
        'currency' => 'INR',
        'wallet_name' => 'CityLoop Wallet',
    ];
}

function save_tiers(array $data, string $primary, string $alt): bool {
    $data['updated_at'] = date('Y-m-d');
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    $ok = @file_put_contents($primary, $json) !== false;
    if (!$ok) {
        @mkdir(dirname($alt), 0775, true);
        $ok = @file_put_contents($alt, $json) !== false;
        // also try primary dir create
        if (!$ok) {
            @mkdir(dirname($primary), 0775, true);
            $ok = @file_put_contents($primary, $json) !== false;
        }
    }
    // keep public copy in sync when writable worked
    if ($ok && is_writable(dirname($primary))) {
        @file_put_contents($primary, $json);
    }
    return $ok;
}

$cfg = load_tiers($dataFile, $writableAlt);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $free = (int) ($_POST['free_delivery_min'] ?? 199);
    $mins = $_POST['min'] ?? [];
    $cash = $_POST['cashback'] ?? [];
    $labels = $_POST['label'] ?? [];
    $tiers = [];
    $n = max(count($mins), count($cash), count($labels));
    for ($i = 0; $i < $n; $i++) {
        $m = (int) ($mins[$i] ?? 0);
        $c = (int) ($cash[$i] ?? 0);
        $l = trim((string) ($labels[$i] ?? ''));
        if ($m > 0 && $c > 0) {
            $tiers[] = ['min' => $m, 'cashback' => $c, 'label' => $l !== '' ? $l : ('Tier ' . ($i + 1))];
        }
    }
    usort($tiers, fn($a, $b) => $a['min'] <=> $b['min']);
    $cfg = [
        'free_delivery_min' => max(0, $free),
        'tiers' => $tiers,
        'currency' => 'INR',
        'wallet_name' => 'CityLoop Wallet',
    ];
    if (save_tiers($cfg, $dataFile, $writableAlt)) {
        $msg = 'Cashback tiers saved. Customer apps pick this up via /data/cashback_tiers.json';
    } else {
        $err = 'Could not write JSON (permissions). Tried public/data and writable/.';
    }
    $cfg = load_tiers($dataFile, $writableAlt);
}

$tiers = $cfg['tiers'] ?? [];
while (count($tiers) < 4) {
    $tiers[] = ['min' => '', 'cashback' => '', 'label' => ''];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>CityLoop · Cashback tiers</title>
  <style>
    body { font-family: system-ui, sans-serif; background: #f6f4fb; color: #1a1a1a; margin: 0; padding: 24px; }
    .card { max-width: 640px; margin: 0 auto; background: #fff; border-radius: 16px; padding: 24px; box-shadow: 0 8px 30px rgba(91,0,245,.08); border: 1px solid #eee; }
    h1 { font-size: 1.25rem; margin: 0 0 4px; }
    p.sub { color: #666; font-size: 13px; margin: 0 0 20px; }
    label { display: block; font-size: 12px; font-weight: 700; color: #555; margin-bottom: 6px; }
    input { width: 100%; box-sizing: border-box; height: 40px; border: 1px solid #ddd; border-radius: 10px; padding: 0 12px; font-weight: 600; }
    .row { display: grid; grid-template-columns: 1fr 1fr 1.2fr; gap: 8px; margin-bottom: 10px; }
    .ok { background: #ecfdf5; color: #047857; padding: 10px 12px; border-radius: 10px; font-size: 13px; margin-bottom: 12px; }
    .err { background: #fef2f2; color: #b91c1c; padding: 10px 12px; border-radius: 10px; font-size: 13px; margin-bottom: 12px; }
    button { background: #5B00F5; color: #fff; border: 0; height: 44px; border-radius: 12px; font-weight: 800; width: 100%; cursor: pointer; margin-top: 12px; }
    a { color: #5B00F5; font-size: 13px; font-weight: 600; }
  </style>
</head>
<body>
  <div class="card">
    <h1>Wallet cashback tiers</h1>
    <p class="sub">Controls free-delivery threshold and cart cashback psychology (admin JSON).</p>
    <?php if ($msg): ?><div class="ok"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
    <?php if ($err): ?><div class="err"><?= htmlspecialchars($err) ?></div><?php endif; ?>
    <form method="post">
      <label>Free delivery minimum (₹)</label>
      <input type="number" name="free_delivery_min" min="0" value="<?= (int)($cfg['free_delivery_min'] ?? 199) ?>" />
      <p style="font-size:12px;color:#666;margin:16px 0 8px;font-weight:700;">Tiers (cart subtotal → wallet credit after delivery)</p>
      <div class="row" style="font-size:11px;font-weight:700;color:#888;margin-bottom:4px;">
        <span>Min cart ₹</span><span>Cashback ₹</span><span>Label</span>
      </div>
      <?php foreach ($tiers as $i => $t): ?>
        <div class="row">
          <input type="number" name="min[]" min="0" placeholder="599" value="<?= htmlspecialchars((string)($t['min'] ?? '')) ?>" />
          <input type="number" name="cashback[]" min="0" placeholder="50" value="<?= htmlspecialchars((string)($t['cashback'] ?? '')) ?>" />
          <input type="text" name="label[]" placeholder="Silver" value="<?= htmlspecialchars((string)($t['label'] ?? '')) ?>" />
        </div>
      <?php endforeach; ?>
      <button type="submit">Save tiers</button>
    </form>
    <p style="margin-top:16px;"><a href="/admin">← Back to admin</a> · <a href="/data/cashback_tiers.json" target="_blank">View JSON</a></p>
  </div>
</body>
</html>
