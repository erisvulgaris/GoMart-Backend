<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Setup</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Inter', sans-serif;
      background: #f4f6fb;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 24px rgba(0,0,0,0.08);
      padding: 40px 36px;
      width: 100%;
      max-width: 440px;
    }
    h2 {
      font-size: 1.4rem;
      font-weight: 600;
      color: #1a1a2e;
      margin-bottom: 8px;
    }
    p.sub {
      font-size: 0.875rem;
      color: #6b7280;
      margin-bottom: 28px;
      line-height: 1.5;
    }
    label {
      font-size: 0.8rem;
      font-weight: 500;
      color: #374151;
      display: block;
      margin-bottom: 6px;
    }
    input[type=text] {
      width: 100%;
      padding: 10px 14px;
      border: 1.5px solid #d1d5db;
      border-radius: 8px;
      font-size: 0.9rem;
      outline: none;
      transition: border-color .2s;
      color: #111827;
    }
    input[type=text]:focus { border-color: #4f46e5; }
    .err {
      background: #fef2f2;
      border: 1px solid #fca5a5;
      color: #b91c1c;
      padding: 10px 14px;
      border-radius: 8px;
      font-size: 0.82rem;
      margin-bottom: 18px;
    }
    button {
      margin-top: 20px;
      width: 100%;
      padding: 11px;
      background: #4f46e5;
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 0.95rem;
      font-weight: 500;
      cursor: pointer;
      transition: background .2s;
    }
    button:hover { background: #4338ca; }
    .hint {
      margin-top: 14px;
      font-size: 0.78rem;
      color: #9ca3af;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="card">
    <h2>Application Setup</h2>
    <p class="sub">Enter your purchase code to activate the application. You can find this in your CodeCanyon downloads page.</p>

    <?php if (session()->getFlashdata('err')): ?>
      <div class="err"><?= esc(session()->getFlashdata('err')) ?></div>
    <?php endif; ?>

    <form action="/init/process" method="POST">
      <?= csrf_field() ?>
      <label for="rc">Purchase Code</label>
      <input type="text" id="rc" name="rc" placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx" autocomplete="off" required>
      <button type="submit">Activate</button>
    </form>
    <p class="hint">This is a one-time setup. You won't be asked again on this server.</p>
  </div>
</body>
</html>
