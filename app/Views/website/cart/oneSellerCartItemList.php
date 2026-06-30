<!doctype html>
<html lang="<?= session()->get('site_lang') ?? 'en' ?>" dir="<?= dir_attribute() ?>">

<head>
    <?= $this->include('website/template/style') ?>
    <title><?= $settings['business_name'] ?></title>
</head>

<body class="bg-gray-50 dark:bg-gray-900">
    <?= $this->include('website/template/header') ?>
    <main class="max-w-7xl mx-auto">

        <!-- Page Header -->
        <section class="mt-2 md:mt-4 md:container md:mx-auto px-3">
            <div class="flex items-center gap-3 bg-white dark:bg-gray-800 mb-4 px-5 py-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <i class="fi fi-rr-shopping-cart text-green-600 dark:text-green-400 text-lg leading-none"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold dark:text-white leading-tight"><?php echo lang('website.cart'); ?></h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Review your items before checkout</p>
                </div>
            </div>
        </section>

        <section class="mt-2 md:mt-4 md:container md:mx-auto md:px-3">

            <!-- Stepper -->
            <ul class="w-full max-w-lg mx-auto my-12 pt-2 pb-5 px-4 flex items-center justify-center">
                <li class="w-full flex after:w-full after:h-1 after:content-[''] last:after:hidden last:w-fit after:bg-green-500">
                    <a href="/cart/<?= $seller_id ?>" class="flex flex-col items-center gap-4 -mt-[13px] relative">
                        <span class="w-[30px] h-[30px] flex items-center justify-center rounded-full border-4 border-green-600 bg-green-600">
                            <i class="fi fi-rr-shopping-cart text-white text-[11px] leading-none"></i>
                        </span>
                        <small class="text-green-600 dark:text-green-400 text-sm font-semibold capitalize absolute -bottom-8"><?php echo lang('website.cart'); ?></small>
                    </a>
                </li>
                <li class="w-full flex after:w-full after:h-1 last:after:hidden last:w-fit after:bg-gray-200 dark:after:bg-gray-700">
                    <a href="#" class="flex flex-col items-center gap-4 -mt-[13px] relative">
                        <span class="w-[30px] h-[30px] border-[4px] rounded-full border-[#D9DBE9] bg-[#D9DBE9] dark:border-gray-600 dark:bg-gray-600"></span>
                        <small class="text-gray-400 dark:text-gray-500 text-sm font-medium capitalize absolute -bottom-8">Checkout</small>
                    </a>
                </li>
                <li class="w-full flex after:w-full after:h-1 last:after:hidden last:w-fit after:bg-gray-200 dark:after:bg-gray-700">
                    <a href="#" class="flex flex-col items-center gap-4 -mt-[13px] relative">
                        <span class="w-[30px] h-[30px] border-[4px] rounded-full border-[#D9DBE9] bg-[#D9DBE9] dark:border-gray-600 dark:bg-gray-600"></span>
                        <small class="text-gray-400 dark:text-gray-500 text-sm font-medium capitalize absolute -bottom-8">Order</small>
                    </a>
                </li>
            </ul>

            <div class="flex flex-wrap lg:flex-nowrap lg:gap-x-6 gap-y-6">

                <!-- ── Left: Cart Items ── -->
                <div class="lg:w-2/3 w-full">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                        <!-- Card header -->
                        
                        <div class="flex items-center gap-2 px-5 py-4 border-b border-dashed border-gray-100 dark:border-gray-700">
                            <i class="fi fi-rr-box-alt text-green-500 leading-none"></i>
                            <h3 class="font-bold text-sm dark:text-white">Your Items</h3>
                            <span class="ml-auto text-xs text-gray-400 dark:text-gray-500"><?= count($productDetails) ?> item<?= count($productDetails) !== 1 ? 's' : '' ?></span>
                        </div>

                        <!-- Item list -->
                        <div class="divide-y divide-dashed divide-gray-100 dark:divide-gray-700">
                            <?php foreach ($productDetails as $productDetail): ?>
                                <div class="flex gap-4 px-5 py-4 group <?= $productDetail['slug'] . '-maindiv-' . $productDetail['product_variant_id'] ?>">

                                    <!-- Product image -->
                                    <div class="relative flex-shrink-0">
                                        <a href="<?= base_url('product/' . esc($productDetail['slug'])) ?>">
                                            <img src="<?= base_url($productDetail['main_img']) ?>"
                                                alt="<?= esc($productDetail['product_name']) ?>"
                                                class="w-24 h-24 object-cover rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm group-hover:shadow-md transition" />
                                        </a>
                                        <?php if ($productDetail['discounted_price'] > 0 && $productDetail['discounted_price'] < $productDetail['price']): ?>
                                            <?php $disc = round((1 - $productDetail['discounted_price'] / $productDetail['price']) * 100); ?>
                                            <span class="absolute -top-1.5 -left-1.5 bg-green-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-md leading-tight">
                                                -<?= $disc ?>%
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Details -->
                                    <div class="flex flex-col gap-1.5 flex-1 min-w-0">
                                        <a href="<?= base_url('product/' . esc($productDetail['slug'])) ?>">
                                            <h6 class="font-semibold text-sm text-gray-900 dark:text-white leading-snug hover:text-green-600 dark:hover:text-green-400 transition">
                                                <?= esc($productDetail['product_name']) ?>
                                            </h6>
                                        </a>
                                        <span class="text-xs text-gray-400 dark:text-gray-500"><?= esc($productDetail['variant_title']) ?></span>

                                        <!-- Price -->
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <?php if ($productDetail['discounted_price'] > 0 && $productDetail['discounted_price'] < $productDetail['price']): ?>
                                                <span class="font-bold text-sm text-gray-900 dark:text-white">
                                                    <?= $settings['currency_symbol_position'] == 'left' ? $country['currency_symbol'] . number_format($productDetail['discounted_price'], 2) : number_format($productDetail['discounted_price'], 2) . $country['currency_symbol'] ?>
                                                </span>
                                                <span class="line-through text-xs text-gray-400 dark:text-gray-500">
                                                    <?= $settings['currency_symbol_position'] == 'left' ? $country['currency_symbol'] . number_format($productDetail['price'], 2) : number_format($productDetail['price'], 2) . $country['currency_symbol'] ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="font-bold text-sm text-gray-900 dark:text-white">
                                                    <?= $settings['currency_symbol_position'] == 'left' ? $country['currency_symbol'] . number_format($productDetail['price'], 2) : number_format($productDetail['price'], 2) . $country['currency_symbol'] ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Qty + Remove row -->
                                        <div class="flex items-center justify-between mt-1">
                                            <!-- Qty stepper -->
                                            <div class="<?= $productDetail['slug'] . '-mainbtndiv-' . $productDetail['product_variant_id'] ?>">
                                                <div class="flex items-center gap-0 rounded-xl overflow-hidden border border-green-600 dark:border-green-500 bg-green-600">
                                                    <button type="button"
                                                        onclick="removeFromCart(<?= esc($productDetail['product_id']) ?>, <?= esc($productDetail['product_variant_id']) ?>)"
                                                        class="w-8 h-8 flex items-center justify-center hover:bg-green-700 transition">
                                                        <i class="fi fi-rr-minus-small text-white text-base leading-none"></i>
                                                    </button>
                                                    <span class="w-8 h-8 flex items-center justify-center text-sm font-bold text-white <?= $productDetail['slug'] . '-qty-' . $productDetail['product_variant_id'] ?>">
                                                        <?= esc($productDetail['quantity']) ?>
                                                    </span>
                                                    <button type="button"
                                                        onclick="addToCart(<?= esc($productDetail['product_id']) ?>, <?= esc($productDetail['product_variant_id']) ?>)"
                                                        class="w-8 h-8 flex items-center justify-center hover:bg-green-700 transition">
                                                        <i class="fi fi-rr-plus-small text-white text-base leading-none"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Remove button -->
                                            <button type="button"
                                                onclick="removeItem(<?= esc($productDetail['product_id']) ?>, <?= esc($productDetail['product_variant_id']) ?>)"
                                                class="flex items-center gap-1.5 text-xs font-medium text-red-500 dark:text-red-400 bg-red-50 dark:bg-red-900/20 px-2.5 py-1.5 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition">
                                                <i class="fi fi-rr-trash leading-none"></i>
                                                <?php echo lang('website.remove'); ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- ── Right: Order Summary ── -->
                <div class="w-full lg:w-1/3 md:w-full lg:sticky lg:top-6 self-start flex flex-col gap-3">

                    <!-- Summary card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                        
                        <div class="flex items-center gap-2 px-5 py-4 border-b border-dashed border-gray-100 dark:border-gray-700">
                            <i class="fi fi-rr-receipt text-orange-400 leading-none"></i>
                            <h2 class="text-sm font-bold dark:text-white"><?php echo lang('website.order_summary'); ?></h2>
                        </div>

                        <div class="divide-y divide-gray-100 dark:divide-gray-700 px-5">
                            <div class="flex items-center justify-between py-3 text-sm text-gray-600 dark:text-gray-300">
                                <span class="flex items-center gap-1.5">
                                    <i class="fi fi-rr-shopping-bag leading-none text-xs text-gray-400"></i>
                                    <?php echo lang('website.subtotal'); ?>
                                </span>
                                <span class="font-semibold text-gray-900 dark:text-white subtotal">
                                    <?php if ($settings['currency_symbol_position'] == 'left'): ?>
                                        <?= $country['currency_symbol'] ?><?= esc($subtotal) ?>
                                    <?php else: ?>
                                        <?= esc($subtotal) ?><?= $country['currency_symbol'] ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>

                        <!-- Info banner -->
                        <div class="mx-5 mb-4 p-3 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 flex gap-2.5 items-start">
                            <i class="fi fi-rr-info text-blue-400 text-sm leading-none mt-0.5 flex-shrink-0"></i>
                            <p class="text-xs text-blue-700 dark:text-blue-300 leading-relaxed">
                                <?php echo lang('website.delivery_Taxes_&_Discounts_calculated_at_checkout'); ?>
                            </p>
                        </div>

                        <!-- Grand total row -->
                        <div class="flex items-center justify-between px-5 py-3.5 bg-gray-50 dark:bg-gray-700/40 border-t border-gray-100 dark:border-gray-700">
                            <span class="font-bold text-sm dark:text-white"><?php echo lang('website.grand_total'); ?></span>
                            <span class="font-bold text-green-500 text-base subtotal">
                                <?php if ($settings['currency_symbol_position'] == 'left'): ?>
                                    <?= $country['currency_symbol'] ?><?= esc($subtotal) ?>
                                <?php else: ?>
                                    <?= esc($subtotal) ?><?= $country['currency_symbol'] ?>
                                <?php endif; ?>
                            </span>
                        </div>

                        <!-- Checkout button -->
                        <div class="px-5 pb-5 pt-3">
                            <a href="/checkout/<?= $seller_id ?>"
                                class="w-full flex items-center justify-between bg-green-600 hover:bg-green-700 text-white font-semibold text-sm py-3.5 px-5 rounded-xl shadow focus:outline-none focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800 transition">
                                <span class="flex items-center gap-2">
                                    <i class="fi fi-rr-shopping-cart-check leading-none"></i>
                                    <?php echo lang('website.go_to_checkout'); ?>
                                </span>
                                <span class="font-bold subtotal">
                                    <?php if ($settings['currency_symbol_position'] == 'left'): ?>
                                        <?= $country['currency_symbol'] ?><?= esc($subtotal) ?>
                                    <?php else: ?>
                                        <?= esc($subtotal) ?><?= $country['currency_symbol'] ?>
                                    <?php endif; ?>
                                </span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <?= $this->include('website/template/mobileBottomMenu') ?>
    </main>
    <?= $this->include('website/template/shopCart') ?>
    <?= $this->include('website/template/footer') ?>
    <?= $this->include('website/template/script') ?>
</body>

</html>
