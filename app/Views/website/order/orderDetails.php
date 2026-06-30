<!doctype html>
<html lang="<?= session()->get('site_lang') ?? 'en' ?>" dir="<?= dir_attribute() ?>">

<head>
    <?= $this->include('website/template/style') ?>
    <title><?= $settings['business_name'] ?></title>
    <style>
        .step-line { flex: 1; height: 3px; border-radius: 9999px; }
        .step-dot  { width: 2rem; height: 2rem; border-radius: 9999px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.75rem; font-weight: 700; }
        @keyframes pulse-ring { 0%,100%{box-shadow:0 0 0 0 rgba(34,197,94,.4)} 50%{box-shadow:0 0 0 8px rgba(34,197,94,0)} }
        .step-active-pulse { animation: pulse-ring 2s infinite; }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900">
    <?= $this->include('website/template/header') ?>

    <main class="max-w-7xl mx-auto">
        <section class="mt-2 md:mt-4 md:container md:mx-auto px-3">

            <!-- Page Header -->
            <div class="flex items-center gap-3 bg-white dark:bg-gray-800 mb-4 px-5 py-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <i class="fi fi-rr-box-alt text-green-600 dark:text-green-400 text-lg leading-none"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold dark:text-white leading-tight"><?php echo lang('website.order_details'); ?></h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"><?php echo lang('website.order_id'); ?> <span class="text-green-600 dark:text-green-400 font-semibold">#<?= $order['order_id'] ?></span></p>
                </div>
            </div>
        </section>

        <section class="md:container md:mx-auto md:px-3 px-3">
            <div class="flex flex-wrap lg:flex-nowrap lg:gap-x-6 gap-y-4">
                <?= $this->include('website/template/dashboardSidebar') ?>

                <div class="w-full space-y-4">

                    <!-- ── Order Tracking ── -->
                    <?php if ($order['status'] < 7 || $order['status'] == 8): ?>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                        <!-- Green top accent -->
                        
                        <div class="p-5">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-base font-bold dark:text-white flex items-center gap-2">
                                    <i class="fi fi-rr-location-alt text-green-500 leading-none"></i>
                                    <?php echo lang('website.your_order_status_is_as_follows'); ?>
                                </h3>
                                <span class="text-xs font-medium px-2.5 py-1 rounded-full <?= $order['bg_color'] ?> <?= $order['text_color'] ?>">
                                    <?= $order['status_name'] ?>
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6"><?php echo lang('website.thank_you'); ?> for your order!</p>

                            <!-- Step tracker -->
                            <div class="flex items-center w-full mb-8">
                                <?php foreach ($orderStatuses as $i => $status): ?>
                                    <?php $isActive = $status['is_active']; ?>
                                    <?php if ($i > 0): ?>
                                        <div class="step-line <?= $isActive ? 'bg-green-500' : 'bg-gray-200 dark:bg-gray-600' ?>"></div>
                                    <?php endif; ?>
                                    <div class="relative flex flex-col items-center">
                                        <div class="step-dot <?= $isActive ? 'bg-green-500 text-white step-active-pulse' : 'bg-gray-200 dark:bg-gray-700 text-gray-400 dark:text-gray-500' ?>">
                                            <?= $isActive ? '✔' : '' ?>
                                        </div>
                                        <div class="absolute top-10 w-16 sm:w-24 text-center">
                                            <p class="text-[10px] sm:text-xs font-semibold capitalize <?= $isActive ? 'text-green-600 dark:text-green-400' : 'text-gray-400 dark:text-gray-500' ?> leading-tight">
                                                <?= $status['name'] ?>
                                            </p>
                                            <?php if ($isActive): ?>
                                            <p class="text-[9px] text-gray-400 dark:text-gray-500 mt-0.5 leading-tight">
                                                <?= date('d M, h:i A', strtotime($status['created_at'])) ?>
                                            </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- ── Order Info + Shipping (2 col) ── -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- Order Info Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                            <div class="flex items-center justify-between px-5 py-4 border-b border-dashed border-gray-100 dark:border-gray-700">
                                <div class="flex items-center gap-2">
                                    <i class="fi fi-rr-document text-indigo-500 leading-none"></i>
                                    <h3 class="font-bold text-sm capitalize dark:text-white"><?php echo lang('website.order_details'); ?></h3>
                                </div>
                                <?php if ($is_order_cancelleble == 1): ?>
                                    <button type="button" onclick="openCancelOrderPopup()"
                                        class="flex items-center gap-1.5 text-xs font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 px-3 py-1.5 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition">
                                        <i class="fi fi-rr-trash leading-none"></i> <?php echo lang('website.cancel_order'); ?>
                                    </button>
                                <?php endif; ?>
                            </div>
                            <ul class="px-5 py-4 space-y-3">
                                <li class="flex items-start gap-3">
                                    <i class="fi fi-rr-hashtag text-gray-400 mt-0.5 leading-none text-sm flex-shrink-0"></i>
                                    <div>
                                        <p class="text-[11px] text-gray-400 dark:text-gray-500 uppercase tracking-wide"><?php echo lang('website.order_id'); ?></p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">#<?= $order['order_id'] ?></p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="fi fi-rr-calendar text-gray-400 mt-0.5 leading-none text-sm flex-shrink-0"></i>
                                    <div>
                                        <p class="text-[11px] text-gray-400 dark:text-gray-500 uppercase tracking-wide"><?php echo lang('website.order_date'); ?></p>
                                        <p class="text-sm font-medium text-gray-800 dark:text-gray-200"><?= date('d M Y, h:i A', strtotime($order['order_date'])) ?></p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="fi fi-rr-truck-side text-gray-400 mt-0.5 leading-none text-sm flex-shrink-0"></i>
                                    <div>
                                        <p class="text-[11px] text-gray-400 dark:text-gray-500 uppercase tracking-wide"><?php echo lang('website.delivery_datetime'); ?></p>
                                        <?php if ($order['status'] != 7): ?>
                                            <?php if (isset($order['delivery_date']) && $order['delivery_date']): ?>
                                                <p class="text-sm font-medium text-gray-800 dark:text-gray-200"><?= htmlspecialchars($order['delivery_date']) ?> &bull; <?= htmlspecialchars($order['timeslot']) ?></p>
                                            <?php else: ?>
                                                <span class="inline-block text-xs font-medium text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20 px-2 py-0.5 rounded-md mt-0.5">Will be updated soon</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="fi fi-rr-box text-gray-400 mt-0.5 leading-none text-sm flex-shrink-0"></i>
                                    <div>
                                        <p class="text-[11px] text-gray-400 dark:text-gray-500 uppercase tracking-wide"><?php echo lang('website.order_type'); ?></p>
                                        <p class="text-sm font-medium text-gray-800 dark:text-gray-200 capitalize"><?= $order['delivery_method'] ?></p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="fi fi-rr-credit-card text-gray-400 mt-0.5 leading-none text-sm flex-shrink-0"></i>
                                    <div>
                                        <p class="text-[11px] text-gray-400 dark:text-gray-500 uppercase tracking-wide"><?php echo lang('website.payment_method'); ?></p>
                                        <p class="text-sm font-medium text-gray-800 dark:text-gray-200"><?= $paymentMethod['title'] ?></p>
                                    </div>
                                </li>
                                <?php if (!empty($order['billing_gst'])): ?>
                                <li class="flex items-start gap-3">
                                    <i class="fi fi-rr-document-signed text-blue-400 mt-0.5 leading-none text-sm flex-shrink-0"></i>
                                    <div>
                                        <p class="text-[11px] text-gray-400 dark:text-gray-500 uppercase tracking-wide">GST Number</p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white tracking-wider"><?= esc($order['billing_gst']) ?></p>
                                    </div>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>

                        <!-- Shipping Address Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                            <div class="flex items-center gap-2 px-5 py-4 border-b border-dashed border-gray-100 dark:border-gray-700">
                                <i class="fi fi-rr-map-marker text-red-400 leading-none"></i>
                                <h3 class="font-bold text-sm capitalize dark:text-white"><?php echo lang('website.shipping_address'); ?></h3>
                            </div>
                            <div class="px-5 py-4">
                                <!-- Avatar + name row -->
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0">
                                        <i class="fi fi-rr-user text-indigo-500 leading-none"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white capitalize"><?= $address['user_name'] ?></p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500"><?= $user['email'] ?></p>
                                    </div>
                                </div>
                                <div class="space-y-2.5">
                                    <div class="flex items-center gap-2.5 text-sm text-gray-700 dark:text-gray-300">
                                        <i class="fi fi-rr-phone-call  leading-none flex-shrink-0"></i>
                                        <?= $address['user_mobile'] ?>
                                    </div>
                                    <div class="flex items-start gap-2.5 text-sm text-gray-700 dark:text-gray-300">
                                        <i class="fi fi-rr-marker leading-none flex-shrink-0 mt-0.5"></i>
                                        <span><?= $address['address'] ?>, <?= $address['area'] ?>, <?= $address['city'] ?>, <?= $address['state'] ?> – <?= $address['pincode'] ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── OTP Card ── -->
                    <?php if ($settings['order_delivery_verification'] == 1 && $order['status'] != 7): ?>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                        <div class="flex items-center justify-between px-5 py-4">
                            <div class="flex items-center gap-2">
                                <i class="fi fi-rr-shield-check text-green-500 leading-none"></i>
                                <div>
                                    <h3 class="font-bold text-sm dark:text-white"><?php echo lang('website.order_delivery_OTP'); ?></h3>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5"><?php echo lang('website.delivery_boy_asked_for_OTP_during_Delivery'); ?></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <?php
                                $otp = str_split($order['order_delivery_otp']);
                                foreach ($otp as $digit):
                                ?>
                                <span class="w-9 h-10 flex items-center justify-center rounded-lg bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 font-bold text-lg border border-green-200 dark:border-green-800">
                                    <?= $digit ?>
                                </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- ── Live Delivery Tracking ── -->
                    <?php if ($order['status'] == 5): ?>
                    <div id="liveTrackingCard" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                        <div class="h-1 bg-gradient-to-r from-blue-400 via-indigo-500 to-purple-400"></div>
                        <div class="flex items-center justify-between px-5 py-4 border-b border-dashed border-gray-100 dark:border-gray-700">
                            <div class="flex items-center gap-2">
                                <i class="fi fi-rr-location-alt text-blue-500 leading-none"></i>
                                <h3 class="font-bold text-sm dark:text-white">Live Delivery Tracking</h3>
                            </div>
                            <span id="trackingStatusBadge" class="text-xs font-medium px-2.5 py-1 rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">
                                Loading…
                            </span>
                        </div>
                        <div class="p-4">
                            <div id="deliveryMap" class="w-full rounded-xl overflow-hidden" style="height:320px; background:#e5e7eb;">
                                <div id="mapPlaceholder" class="flex flex-col items-center justify-center h-full text-gray-400 dark:text-gray-500 gap-2">
                                    <i class="fi fi-rr-map-marker text-3xl leading-none"></i>
                                    <p class="text-sm">Waiting for delivery boy location…</p>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2 text-center">Location updates every 10 seconds</p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- ── Order Summary ── -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">

                        <!-- Header -->
                        <div class="flex items-center justify-between px-5 py-4 border-b border-dashed border-gray-100 dark:border-gray-700">
                            <div class="flex items-center gap-2">
                                <i class="fi fi-rr-receipt text-orange-400 leading-none"></i>
                                <h3 class="font-bold text-sm capitalize dark:text-white"><?php echo lang('website.order_summery'); ?></h3>
                            </div>
                            <button type="button" onclick="downloadInvoice(<?= $order['id'] ?>, this)"
                                class="flex items-center gap-1.5 text-xs font-medium text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/20 px-3 py-1.5 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition">
                                <i class="fi fi-rr-cloud-download-alt leading-none"></i>
                                <?php echo lang('website.download_invoice'); ?>
                            </button>
                        </div>

                        <!-- Product List -->
                        <?php
                        $totalProductPrice = 0;
                        $totalProductTax   = 0;
                        ?>
                        <div class="divide-y divide-dashed divide-gray-100 dark:divide-gray-700">
                        <?php foreach ($orderProducts as $orderProduct): ?>
                            <?php
                            $itemPrice = (($orderProduct['discounted_price'] > 0) ? $orderProduct['discounted_price'] : $orderProduct['price']) * $orderProduct['quantity'];
                            $totalProductPrice += $itemPrice;
                            $totalProductTax   += $orderProduct['tax_amount'];
                            $displayPrice = $orderProduct['discounted_price'] > 0 ? $orderProduct['discounted_price'] : $orderProduct['price'];
                            ?>
                            <div class="flex gap-4 px-5 py-4">
                                <!-- Product image -->
                                <div class="relative flex-shrink-0">
                                    <img src="<?= $orderProduct['main_img'] ?>"
                                         alt="<?= $orderProduct['product_name'] ?>"
                                         class="w-20 h-20 object-cover rounded-xl shadow">
                                    <span class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-gray-800 dark:bg-gray-600 text-white text-[10px] font-bold flex items-center justify-center">
                                        <?= $orderProduct['quantity'] ?>
                                    </span>
                                </div>

                                <!-- Details -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <h6 class="font-semibold text-sm text-gray-900 dark:text-white leading-snug">
                                            <?= $orderProduct['product_name'] ?>
                                        </h6>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white whitespace-nowrap">
                                            <?= $settings['currency_symbol_position'] == 'left' ? $country['currency_symbol'] . number_format($itemPrice, 2) : number_format($itemPrice, 2) . $country['currency_symbol'] ?>
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5"><?= $orderProduct['product_variant_name'] ?></p>
                                    <div class="flex items-center justify-between mt-2">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            <?= $settings['currency_symbol_position'] == 'left' ? $country['currency_symbol'] . number_format($displayPrice, 2) : number_format($displayPrice, 2) . $country['currency_symbol'] ?>
                                            &times; <?= $orderProduct['quantity'] ?>
                                        </span>

                                        <!-- Return button -->
                                        <?php if ($order['status'] == 6 && $orderProduct['is_returnable']): ?>
                                        <div id="returningItem_<?= $order['id'] ?>_<?= $orderProduct['id'] ?>">
                                            <?php if (is_null($orderProduct['order_retuning_status'])): ?>
                                                <button onclick="openReturningItemPopup(<?= $order['id'] ?>, <?= $orderProduct['id'] ?>)" type="button"
                                                    class="flex items-center gap-1 text-xs font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 px-2 py-1 rounded-lg hover:bg-red-100 transition">
                                                    <i class="fi fi-rr-undo leading-none"></i> <?php echo lang('website.return_item'); ?>
                                                </button>
                                            <?php else: ?>
                                                <?php
                                                $returnBadges = [
                                                    1 => ['Pending',               'text-yellow-800 bg-yellow-100 dark:text-yellow-300 dark:bg-yellow-900/30'],
                                                    2 => ['Approved',              'text-green-800 bg-green-100 dark:text-green-300 dark:bg-green-900/30'],
                                                    3 => ['Rejected',              'text-red-800 bg-red-100 dark:text-red-300 dark:bg-red-900/30'],
                                                    4 => ['Return to Delivery Boy','text-blue-800 bg-blue-100 dark:text-blue-300 dark:bg-blue-900/30'],
                                                    5 => ['Refunded',              'text-purple-800 bg-purple-100 dark:text-purple-300 dark:bg-purple-900/30'],
                                                ];
                                                $badge = $returnBadges[$orderProduct['order_retuning_status']] ?? ['Unknown', 'text-gray-800 bg-gray-100 dark:text-gray-300 dark:bg-gray-700'];
                                                echo '<span class="text-xs font-medium px-2 py-0.5 rounded-full ' . $badge[1] . '">' . $badge[0] . '</span>';
                                                ?>
                                            <?php endif; ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        </div>

                        <!-- Delivery Instruction Banner -->
                        <?php if (!empty($order['delivery_instruction'])): ?>
                        <div class="mx-5 mb-4 p-3.5 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 flex gap-3 items-start">
                            <i class="fi fi-rr-memo-pad text-amber-500 text-base leading-none mt-0.5 flex-shrink-0"></i>
                            <div>
                                <p class="text-xs font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wide mb-0.5">Delivery Instructions</p>
                                <p class="text-sm text-amber-800 dark:text-amber-300"><?= esc($order['delivery_instruction']) ?></p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Price Breakdown -->
                        <?php
                        $tipAmount  = !empty($order['delivery_tip_amount']) ? (float)$order['delivery_tip_amount'] : 0;
                        $grandTotal = $totalProductPrice + $totalProductTax + $order['delivery_charge'] + $order['additional_charge'] + $tipAmount - $order['coupon_amount'] - $order['used_wallet_amount'];
                        ?>
                        <div class="mx-5 mb-5 rounded-xl bg-gray-50 dark:bg-gray-700/40 border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                                <div class="flex items-center justify-between px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300">
                                    <span class="flex items-center gap-1.5">
                                        <i class="fi fi-rr-shopping-bag leading-none text-xs"></i>
                                        <?php echo lang('website.subtotal'); ?>
                                    </span>
                                    <span class="font-medium"><?= $settings['currency_symbol_position'] == 'left' ? $country['currency_symbol'] . number_format($totalProductPrice, 2) : number_format($totalProductPrice, 2) . $country['currency_symbol'] ?></span>
                                </div>
                                <?php
                                // Aggregate tax breakdown across all order products
                                $taxSummary = [];
                                if (!empty($taxBreakdowns)) {
                                    foreach ($taxBreakdowns as $opId => $breakdowns) {
                                        foreach ($breakdowns as $tb) {
                                            $key = $tb['tax_name'] . '_' . $tb['tax_percentage'];
                                            if (!isset($taxSummary[$key])) {
                                                $taxSummary[$key] = ['name' => $tb['tax_name'], 'percentage' => $tb['tax_percentage'], 'amount' => 0];
                                            }
                                            $taxSummary[$key]['amount'] += $tb['tax_amount'];
                                        }
                                    }
                                }
                                ?>
                                <?php if (!empty($taxSummary)): ?>
                                    <?php foreach ($taxSummary as $ts): ?>
                                    <div class="flex items-center justify-between px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300">
                                        <span class="flex items-center gap-1.5">
                                            <i class="fi fi-rr-receipt leading-none text-xs"></i>
                                            <?= esc($ts['name']); ?> (<?= $ts['percentage']; ?>%)
                                        </span>
                                        <span class="font-medium"><?= $settings['currency_symbol_position'] == 'left' ? $country['currency_symbol'] . number_format($ts['amount'], 2) : number_format($ts['amount'], 2) . $country['currency_symbol'] ?></span>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <div class="flex items-center justify-between px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300">
                                    <span class="flex items-center gap-1.5">
                                        <i class="fi fi-rr-receipt  leading-none text-xs"></i>
                                        <?php echo lang('website.tax'); ?>
                                    </span>
                                    <span class="font-medium"><?= $settings['currency_symbol_position'] == 'left' ? $country['currency_symbol'] . number_format($totalProductTax, 2) : number_format($totalProductTax, 2) . $country['currency_symbol'] ?></span>
                                </div>
                                <div class="flex items-center justify-between px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300">
                                    <span class="flex items-center gap-1.5">
                                        <i class="fi fi-rr-truck-side  leading-none text-xs"></i>
                                        <?php echo lang('website.delivery_charge'); ?>
                                    </span>
                                    <span class="font-medium"><?= $settings['currency_symbol_position'] == 'left' ? $country['currency_symbol'] . number_format($order['delivery_charge'], 2) : number_format($order['delivery_charge'], 2) . $country['currency_symbol'] ?></span>
                                </div>
                                <?php if ($additional_charge_status == 1): ?>
                                <div class="flex items-center justify-between px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300">
                                    <span class="flex items-center gap-1.5">
                                        <i class="fi fi-rr-plus-small leading-none text-xs"></i>
                                        <?= $additional_charge_name ?>
                                    </span>
                                    <span class="font-medium"><?= $settings['currency_symbol_position'] == 'left' ? $country['currency_symbol'] . number_format($order['additional_charge'], 2) : number_format($order['additional_charge'], 2) . $country['currency_symbol'] ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if ($order['coupon_amount'] > 0): ?>
                                <div class="flex items-center justify-between px-4 py-2.5 text-sm">
                                    <span class="text-gray-600 dark:text-gray-300 flex items-center gap-1.5">
                                        <i class="fi fi-rr-ticket-alt text-green-500 leading-none text-xs"></i>
                                        <?php echo lang('website.discount'); ?>
                                        <?php if ($order['coupon_type'] == 1): ?>
                                            <span class="text-xs text-red-500">(<?= $order['coupon_value'] ?>%)</span>
                                        <?php endif; ?>
                                    </span>
                                    <span class="font-medium text-green-600 dark:text-green-400">
                                        – <?= $settings['currency_symbol_position'] == 'left' ? $country['currency_symbol'] . number_format($order['coupon_amount'], 2) : number_format($order['coupon_amount'], 2) . $country['currency_symbol'] ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                                <?php if ($order['used_wallet_amount'] > 0): ?>
                                <div class="flex items-center justify-between px-4 py-2.5 text-sm">
                                    <span class="text-gray-600 dark:text-gray-300 flex items-center gap-1.5">
                                        <i class="fi fi-rr-wallet text-indigo-400 leading-none text-xs"></i>
                                        <?php echo lang('website.wallet'); ?>
                                    </span>
                                    <span class="font-medium text-indigo-500 dark:text-indigo-400">
                                        – <?= $settings['currency_symbol_position'] == 'left' ? $country['currency_symbol'] . number_format($order['used_wallet_amount'], 2) : number_format($order['used_wallet_amount'], 2) . $country['currency_symbol'] ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                                <?php if ($tipAmount > 0): ?>
                                <div class="flex items-center justify-between px-4 py-2.5 text-sm">
                                    <span class="text-green-600 dark:text-green-400 flex items-center gap-1.5">
                                        <i class="fi fi-rr-hand-holding-heart leading-none text-xs"></i>
                                        Delivery Tip
                                    </span>
                                    <span class="font-medium text-green-600 dark:text-green-400">
                                        + <?= $settings['currency_symbol_position'] == 'left' ? $country['currency_symbol'] . number_format($tipAmount, 2) : number_format($tipAmount, 2) . $country['currency_symbol'] ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <!-- Grand Total -->
                            <div class="flex items-center justify-between px-4 py-3 bg-gray-100 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-600 rounded-b-xl">
                                <span class="font-bold dark:text-white text-sm"><?php echo lang('website.grand_total'); ?></span>
                                <span class="font-bold text-green-400 text-base">
                                    <?= $settings['currency_symbol_position'] == 'left' ? $country['currency_symbol'] . number_format($grandTotal, 2) : number_format($grandTotal, 2) . $country['currency_symbol'] ?>
                                </span>
                            </div>
                        </div>

                    </div><!-- /Order Summary -->

                    <!-- ── Returned Items ── -->
                    <?php if (count($returned_item_list)): ?>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-red-100 dark:border-red-900/30 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-2 px-5 py-4 border-b border-dashed border-red-100 dark:border-red-900/30">
                            <i class="fi fi-rr-undo text-red-400 leading-none"></i>
                            <h3 class="font-bold text-sm capitalize dark:text-white"><?php echo lang('website.returned_item_list'); ?></h3>
                        </div>

                        <?php
                        $totalRetunedPrice = 0;
                        $totalRetunedTax   = 0;
                        ?>
                        <div class="divide-y divide-dashed divide-gray-100 dark:divide-gray-700">
                        <?php foreach ($returned_item_list as $item): ?>
                            <?php
                            $itemPrice = ($item['order_product']['discounted_price'] > 0) ? $item['order_product']['discounted_price'] : $item['order_product']['price'];
                            $totalRetunedPrice += $itemPrice;
                            $totalRetunedTax   += $item['order_product']['tax_amount'];
                            ?>
                            <div class="flex gap-4 px-5 py-4">
                                <img src="<?= base_url($item['product']['main_img']) ?>"
                                     alt="<?= $item['order_product']['product_name'] ?>"
                                     class="w-18 h-18 w-[72px] h-[72px] object-cover rounded-xl shadow flex-shrink-0">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <h6 class="font-semibold text-sm text-gray-900 dark:text-white"><?= $item['order_product']['product_name'] ?></h6>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white whitespace-nowrap">
                                            <?= $settings['currency_symbol_position'] == 'left' ? $country['currency_symbol'] . number_format($itemPrice, 2) : number_format($itemPrice, 2) . $country['currency_symbol'] ?>
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-0.5"><?= $item['order_product']['product_variant_name'] ?></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><?php echo lang('website.quantity'); ?>: <?= $item['order_product']['quantity'] ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        </div>

                        <div class="flex items-center justify-between px-5 py-3 bg-red-50 dark:bg-red-900/20 border-t border-red-100 dark:border-red-900/30 rounded-b-2xl">
                            <span class="font-bold text-sm text-red-700 dark:text-red-400"><?php echo lang('website.return_total'); ?></span>
                            <span class="font-bold text-sm text-red-700 dark:text-red-400">
                                <?= $settings['currency_symbol_position'] == 'left' ? $country['currency_symbol'] . number_format($totalRetunedPrice + $totalRetunedTax, 2) : number_format($totalRetunedPrice + $totalRetunedTax, 2) . $country['currency_symbol'] ?>
                            </span>
                        </div>
                    </div>
                    <?php endif; ?>

                </div><!-- /right col -->
            </div>
        </section>

        <?= $this->include('website/template/mobileBottomMenu') ?>
        <?= $this->include('website/template/productVarientPopup') ?>
    </main>

    <?= $this->include('website/template/shopCart') ?>
    <?= $this->include('website/template/footer') ?>
    <?= $this->include('website/template/script') ?>

    <!-- Cancel Order Modal -->
    <?php if ($is_order_cancelleble == 1): ?>
    <div id="cancelOrderModal" class="fixed inset-0 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden z-50 px-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b dark:border-gray-700">
                <h2 class="text-base font-bold dark:text-white flex items-center gap-2">
                    <i class="fi fi-rr-trash text-red-500 leading-none"></i> Cancel Order
                </h2>
                <button onclick="closeCancelOrderPopup()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fi fi-rr-circle-xmark text-xl leading-none"></i>
                </button>
            </div>
            <form class="cancelOrderForm px-5 py-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes / Message</label>
                <textarea id="note" name="note" rows="3" placeholder="Enter reason for cancellation…"
                    class="w-full border border-gray-200 dark:border-gray-600 rounded-xl p-3 text-sm text-gray-900 dark:text-gray-100 dark:bg-gray-700 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none resize-none"></textarea>
                <button type="submit" class="mt-4 w-full bg-red-600 hover:bg-red-700 text-white font-semibold text-sm py-2.5 px-4 rounded-xl shadow transition">
                    Confirm Cancellation
                </button>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <!-- Return Item Modal -->
    <div id="returningItemModal" class="fixed inset-0 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden z-50 px-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b dark:border-gray-700">
                <h2 class="text-base font-bold dark:text-white flex items-center gap-2">
                    <i class="fi fi-rr-undo text-red-500 leading-none"></i> Return Item Request
                </h2>
                <button onclick="closeReturningItemPopup()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fi fi-rr-circle-xmark text-xl leading-none"></i>
                </button>
            </div>
            <form class="returningItemForm px-5 py-4">
                <input type="hidden" name="ri_order_id" id="ri_order_id" />
                <input type="hidden" name="ri_order_product_id" id="ri_order_product_id" />
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes / Message</label>
                <textarea id="note" name="note" rows="3" placeholder="Reason for return…"
                    class="w-full border border-gray-200 dark:border-gray-600 rounded-xl p-3 text-sm text-gray-900 dark:text-gray-100 dark:bg-gray-700 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none resize-none"></textarea>
                <button type="submit" class="mt-4 w-full bg-green-600 hover:bg-green-700 text-white font-semibold text-sm py-2.5 px-4 rounded-xl shadow transition">
                    Confirm Return Request
                </button>
            </form>
        </div>
    </div>

    <?= $this->include('website/template/orderDetailsScript') ?>
</body>
</html>
