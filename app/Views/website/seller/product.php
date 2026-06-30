<!doctype html>
<html lang="<?= session()->get('site_lang') ?? 'en' ?>" dir="<?= dir_attribute() ?>">

<head>
    <?= $this->include('website/template/style') ?>
    <title><?= $settings['business_name'] ?></title>
</head>

<body class="bg-gray-100">
    <?= $this->include('website/template/header') ?>
    <main class="max-w-7xl mx-auto">
        <section class="mt-2 md:mt-4 md:container md:mx-auto px-3">
            <div class="row bg-white mb-2 p-4 rounded-lg">
                <div class="flex justify-between">
                    <h2 class="text-lg font-medium z-10"><?= $seller ?></h2>
                </div>
            </div>
        </section>

        <section class="mt-2 md:mt-4 md:container md:mx-auto px-3">
            <div class="grid xl:grid-cols-6 lg:grid-cols-4 md:grid-cols-3 grid-cols-2 gap-4">
                <?php foreach ($products as $product): ?>
                    <?php
                    $firstVarient = $product['variants'][0];
                    $activePrice = $firstVarient['discounted_price'] > 0 ? $firstVarient['discounted_price'] : $firstVarient['price'];
                    $formattedPrice = isset($settings['currency_symbol_position']) && $settings['currency_symbol_position'] == 'left'
                        ? ($country['currency_symbol'] ?? '') . $activePrice
                        : $activePrice . ($country['currency_symbol'] ?? '');
                    $hasDiscount = $firstVarient['discounted_price'] > 0;
                    $discountPercentage = $hasDiscount ? round((($firstVarient['price'] - $firstVarient['discounted_price']) / $firstVarient['price']) * 100) : 0;
                    $originalPrice = $hasDiscount
                        ? (isset($settings['currency_symbol_position']) && $settings['currency_symbol_position'] == 'left' ? ($country['currency_symbol'] ?? '') . $firstVarient['price'] : $firstVarient['price'] . ($country['currency_symbol'] ?? ''))
                        : '';
                    $outOfStock = $firstVarient['stock'] == 0 && $firstVarient['is_unlimited_stock'] == 0;
                    ?>
                    <div class="group" id="<?= $product['slug'] . '-' . $firstVarient['id'] ?>">
                        <div class="flex flex-col rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 overflow-hidden" style="height:100%;">
                            <div class="relative w-full flex-shrink-0">
                                <a href="<?= base_url('product/' . $product['slug']) ?>" class="relative block w-full overflow-hidden" style="aspect-ratio:1/1; background:linear-gradient(135deg,#f8faf8 0%,#eef4ee 100%);">
                                    <?php if ($outOfStock): ?>
                                        <div class="absolute inset-0 z-20 flex items-center justify-center bg-white/50 dark:bg-gray-900/50 backdrop-blur-[2px]">
                                            <span class="text-[10px] font-bold tracking-wide uppercase text-red-500 dark:text-red-400 bg-white dark:bg-gray-800 border border-red-200 dark:border-red-700 px-2.5 py-1 rounded-full shadow">
                                                <?= lang('website.out_of_Stock') ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($hasDiscount): ?>
                                        <div class="absolute top-0 left-0 z-10" style="width:54px;height:54px;overflow:hidden;">
                                            <div style="position:absolute;top:8px;left:-18px;width:72px;transform:rotate(-45deg);background:linear-gradient(90deg,#ef4444,#f97316);color:#fff;font-size:9px;font-weight:800;text-align:center;padding:3px 0;letter-spacing:0.04em;box-shadow:0 2px 6px rgba(239,68,68,.35);">
                                                <?= $discountPercentage ?>% OFF
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <img src="<?= base_url($product['main_img']) ?>"
                                        alt="<?= esc($product['product_name']) ?>"
                                        class="absolute inset-0 w-full h-full object-contain p-4 group-hover:scale-[1.07] transition-transform duration-500 ease-out dark:brightness-90" />
                                </a>
                                <div class="absolute -bottom-4 right-2 z-30 <?= $product['slug'] . '-mainbtndiv-' . $firstVarient['id'] ?>">
                                    <?php if ($outOfStock): ?>
                                        <span class="inline-block text-[10px] font-semibold text-red-400 dark:text-red-500 bg-white dark:bg-gray-800 border border-red-200 dark:border-red-700 rounded-full px-2.5 py-1 shadow-md">Unavailable</span>
                                    <?php elseif ($product['cart_quantity'] > 0): ?>
                                        <div class="flex items-center rounded-xl border-2 border-green-500 dark:border-green-600 overflow-hidden bg-white dark:bg-gray-800 shadow-lg">
                                            <button type="button" onclick="removeFromCart(<?= $product['id'] ?>, <?= $firstVarient['id'] ?>)"
                                                class="w-7 h-7 flex items-center justify-center bg-green-700 text-white hover:bg-green-600 transition-colors">
                                                <i class="fi fi-rr-minus-small text-base leading-none"></i>
                                            </button>
                                            <span class="w-6 text-center text-[13px] font-bold text-green-700 dark:text-green-300 <?= $product['slug'] . '-qty-' . $firstVarient['id'] ?>"><?= $product['cart_quantity'] ?></span>
                                            <button type="button" onclick="addToCart(<?= $product['id'] ?>, <?= $firstVarient['id'] ?>)"
                                                class="w-7 h-7 flex items-center justify-center bg-green-700 text-white hover:bg-green-600 transition-colors">
                                                <i class="fi fi-rr-plus-small text-base leading-none"></i>
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <button type="button"
                                            onclick="openProductVariantPopup(<?= $product['id'] ?>, '<?= $product['slug'] ?>')"
                                            class="w-9 h-9 flex items-center justify-center rounded-xl bg-white dark:bg-gray-800 border-2 border-green-600 dark:border-green-500 text-green-600 dark:text-green-400 shadow-lg hover:bg-green-600 hover:text-white hover:border-green-600 active:scale-90 transition-all duration-150 <?= $product['slug'] . '-' . $firstVarient['id'] ?>">
                                            <i class="fi fi-rr-plus text-sm leading-none"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="flex flex-col flex-1 px-3 pt-5 pb-3 gap-1">
                                <h3 class="text-[12.5px] font-semibold leading-snug text-gray-800 dark:text-gray-100 line-clamp-2" style="min-height:2.6em;"><?= esc($product['product_name']) ?></h3>
                                <div class="flex items-center justify-between gap-1">
                                    <span class="text-[11px] text-gray-400 dark:text-gray-500 truncate leading-none"><?= esc($firstVarient['title']) ?></span>
                                    <span class="productDeliveryTime hidden whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 text-[10px] font-bold px-1.5 py-0.5 rounded-md leading-none border border-orange-100 dark:border-orange-800">
                                            <i class="fi fi-rr-clock text-[9px]"></i>
                                            <span class="productDeliveryTimeVal"></span>m
                                        </span>
                                    </span>
                                </div>
                                <div class="flex-1"></div>
                                <div class="flex items-center gap-1.5 flex-wrap min-w-0">
                                    <span class="text-[13px] font-extrabold text-gray-900 dark:text-white truncate"><?= $formattedPrice ?></span>
                                    <?php if ($hasDiscount): ?>
                                        <span class="text-[10px] text-gray-400 dark:text-gray-500 line-through leading-tight truncate"><?= $originalPrice ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>


        <?= $this->include('website/template/mobileBottomMenu') ?>
        <?= $this->include('website/template/productVarientPopup') ?>

    </main>
    <?= $this->include('website/template/shopCart') ?>
    <?= $this->include('website/template/footer') ?>
    <?= $this->include('website/template/script') ?>
</body>

</html>