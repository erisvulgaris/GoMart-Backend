<!doctype html>
<html lang="<?= session()->get('site_lang') ?? 'en' ?>" dir="<?= dir_attribute() ?>">

<head>
    <?= $this->include('website/template/style') ?>
    <title><?= $settings['business_name'] ?></title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>

        .dark .deliveryMethod {
            background-color: #1f2937 !important; /* gray-800 */
            border-color:     #16a34a !important; /* green-600 */
        }
        .dark .deliveryMethod p              { color: #d1fae5 !important; }
        .dark .deliveryMethod .text-gray-500 { color: #86efac !important; }
        .dark .deliveryMethod:hover {
            background-color: #14532d !important;
            border-color:     #4ade80 !important;
        }
        /* active: JS adds bg-green-50 */
        .dark .deliveryMethod.bg-green-50 {
            background-color: #14532d !important;
            border-color:     #4ade80 !important;
            box-shadow:       0 0 0 2px #4ade8055;
        }
        .dark .deliveryMethod.bg-green-50 p              { color: #ffffff !important; }
        .dark .deliveryMethod.bg-green-50 .text-gray-500 { color: #86efac !important; }



        .dark .date,
        .dark .time {
            background-color: #374151 !important; /* gray-700 */
            border-color:     #6b7280 !important; /* gray-500 */
            color:            #f3f4f6 !important; /* gray-100 */
        }
        .dark .date small { color: #9ca3af !important; }
        .dark .date:hover,
        .dark .time:hover {
            background-color: #4b5563 !important;
            border-color:     #4ade80 !important;
        }
        /* active: JS adds bg-green-50 */
        .dark .date.bg-green-50,
        .dark .time.bg-green-50 {
            background-color: #15803d !important; /* green-700 */
            border-color:     #4ade80 !important; /* green-400 */
            color:            #ffffff !important;
            box-shadow:       0 0 0 2px #4ade8055;
        }
        .dark .date.bg-green-50 small { color: #bbf7d0 !important; }

        .dark [id^="paymentMethod_"] {
            background-color: #374151 !important; /* gray-700 */
            border-color:     #4b5563 !important; /* gray-600 */
            transition: all 0.15s ease;
        }
        .dark [id^="paymentMethod_"] span { color: #e5e7eb !important; }
        .dark [id^="paymentMethod_"] img  { filter: brightness(0.85); }
        .dark [id^="paymentMethod_"]:hover {
            background-color: #1f2937 !important;
            border-color:     #f87171 !important;
        }
        .dark [id^="paymentMethod_"]:hover span { color: #ffffff !important; }

        .dark [id^="paymentMethod_"].border-red-400 {
            background-color: #3b0a0a !important; /* red-950  */
            border-color:     #f87171 !important; /* red-400  */
            box-shadow:       0 0 0 2px #f8717155 !important;
        }
        .dark [id^="paymentMethod_"].border-red-400 span {
            color: #ffffff !important;
        }
        .dark [id^="paymentMethod_"].border-red-400 img {
            filter: brightness(1.2) !important;
        }

        /* Hide scrollbar for date/time rows */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Date chip active */
        .date.active-date {
            background-color: #fff7ed !important;
            border-color: #fb923c !important;
            color: #c2410c !important;
        }
        .dark .date.active-date {
            background-color: #431407 !important;
            border-color: #fb923c !important;
            color: #fed7aa !important;
        }

        /* Time chip active */
        .time.active-time {
            background-color: #faf5ff !important;
            border-color: #a855f7 !important;
            color: #7e22ce !important;
        }
        .dark .time.active-time {
            background-color: #3b0764 !important;
            border-color: #a855f7 !important;
            color: #e9d5ff !important;
        }

        /* Tip chip active state */
        .tip-chip.active {
            background-color: #16a34a !important;
            border-color: #16a34a !important;
            color: #ffffff !important;
        }
        .dark .tip-chip.active {
            background-color: #15803d !important;
            border-color: #4ade80 !important;
            color: #ffffff !important;
            box-shadow: 0 0 0 2px #4ade8055;
        }
        /* Instruction chip active state */
        .instruction-chip.active {
            background-color: #fffbeb !important;
            border-color: #f59e0b !important;
            color: #92400e !important;
            font-weight: 600;
        }
        .dark .instruction-chip.active {
            background-color: #451a03 !important;
            border-color: #fbbf24 !important;
            color: #fde68a !important;
        }

    </style>
</head>

<body class="bg-gray-100 dark:bg-gray-950">
    <?= $this->include('website/template/header') ?>
    <main class="max-w-7xl mx-auto">

        <section class="mt-2 md:mt-4 md:container md:mx-auto px-3">
            <div class="flex items-center gap-3 bg-white dark:bg-gray-800 mb-4 px-5 py-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <i class="fi fi-rr-shopping-cart-check text-green-600 dark:text-green-400 text-lg leading-none"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold dark:text-white leading-tight"><?php echo lang('website.checkout'); ?></h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Review your order and complete payment</p>
                </div>
            </div>
        </section>

        <section class="mt-2 md:mt-4 md:container md:mx-auto md:px-3">

            <!-- Stepper -->
            <ul class="w-full max-w-lg mx-auto my-12 pt-2 pb-5 px-4 flex items-center justify-center">
                <li class="w-full flex after:w-full after:h-1 after:content-[''] last:after:hidden last:w-fit after:bg-green-600">
                    <a href="/cart" class="flex flex-col items-center gap-4 -mt-[13px] relative">
                        <i class="fi fi-tr-check-circle text-lg w-[30px] h-[30px] leading-[34px] text-center rounded-2xl text-white bg-green-600"></i>
                        <small class="text-secondary text-sm font-medium capitalize absolute -bottom-8 dark:text-gray-300">Cart</small>
                    </a>
                </li>
                <li class="w-full flex after:w-full after:h-1 last:after:hidden last:w-fit after:bg-gray-200 dark:after:bg-gray-700">
                    <a href="/checkout" class="flex flex-col items-center gap-4 -mt-[13px] relative">
                        <span class="w-[30px] h-[30px] border-[4px] rounded-2xl border-[#D9DBE9] bg-[#D9DBE9] dark:border-gray-600 dark:bg-gray-600"></span>
                        <small class="text-secondary text-sm font-medium capitalize absolute -bottom-8 dark:text-gray-300">Checkout</small>
                    </a>
                </li>
                <li class="w-full flex after:w-full after:h-1 last:after:hidden last:w-fit after:bg-gray-200 dark:after:bg-gray-700">
                    <a href="#" class="flex flex-col items-center gap-4 -mt-[13px] relative">
                        <span class="w-[30px] h-[30px] border-[4px] rounded-2xl border-[#D9DBE9] bg-[#D9DBE9] dark:border-gray-600 dark:bg-gray-600"></span>
                        <small class="text-secondary text-sm font-medium capitalize absolute -bottom-8 dark:text-gray-300">Order</small>
                    </a>
                </li>
            </ul>

            <div class="flex flex-wrap lg:flex-nowrap lg:gap-x-12 gap-y-6">

                <!-- ── Left Column ── -->
                <div class="lg:w-2/3 w-full">
                    <div class="flex flex-col gap-1">

                        <!-- Delivery Address -->
                        <div class="mb-3 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                            
                            <div class="flex items-center justify-between gap-3 px-5 py-4 border-b border-dashed border-gray-100 dark:border-gray-700">
                                <div class="flex items-center gap-2">
                                    <i class="fi fi-rr-map-marker text-red-400 leading-none"></i>
                                    <h4 class="font-bold text-sm capitalize dark:text-white"><?php echo lang('website.delivery_address'); ?></h4>
                                </div>
                                <button type="button" onclick="openAddressPopup()"
                                    class="flex items-center gap-1.5 text-xs font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 px-3 py-1.5 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition">
                                    <i class="fi fi-rr-plus leading-none"></i>
                                    <span class="capitalize whitespace-nowrap"><?php echo lang('website.add_new'); ?></span>
                                </button>
                            </div>
                            <div class="md:flex gap-6 p-4 address-div"></div>
                        </div>

                        <!-- Delivery Method -->
                        <div class="mb-3 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                            <div class="flex items-center gap-2 px-5 py-4 border-b border-dashed border-gray-100 dark:border-gray-700">
                                <i class="fi fi-rr-truck-side text-indigo-500 leading-none"></i>
                                <h4 class="font-bold text-sm capitalize dark:text-white"><?php echo lang('website.delivery_method'); ?></h4>
                            </div>
                            <div class="flex flex-col md:flex-row gap-4 px-5 py-4">
                                <?php if (!empty($home_delivery_status) && isset($home_delivery_status['status']) && $home_delivery_status['status'] == 1): ?>
                                    <div id="<?= $home_delivery_status['id'] ?>" class="deliveryMethod flex flex-row w-full sm:w-1/3 p-2 border border-green-700 dark:border-green-600 rounded-lg cursor-pointer items-center text-green-700 dark:text-green-400 bg-white dark:bg-gray-800 hover:bg-green-50 dark:hover:bg-green-900/20" onclick="selectDeliveryMethod('<?= $home_delivery_status['id'] ?>')">
                                        <img src="<?= base_url() . '/' . $home_delivery_status['image'] ?>" class="w-10 h-10 mr-2 dark:brightness-90" alt="<?= esc($home_delivery_status['title']) ?>" />
                                        <div>
                                            <p class="font-semibold dark:text-white"><?= esc($home_delivery_status['title']) ?></p>
                                            <p class="text-gray-500 dark:text-gray-400 text-xs"><?= esc($home_delivery_status['description']) ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($schedule_delivery_status) && isset($schedule_delivery_status['status']) && $schedule_delivery_status['status'] == 1): ?>
                                    <div id="<?= esc($schedule_delivery_status['id']) ?>" class="deliveryMethod flex flex-row w-full sm:w-1/3 p-2 border border-green-700 dark:border-green-600 rounded-lg cursor-pointer items-center text-green-700 dark:text-green-400 bg-white dark:bg-gray-800 hover:bg-green-50 dark:hover:bg-green-900/20" onclick="selectDeliveryMethod('<?= esc($schedule_delivery_status['id']) ?>')">
                                        <img src="<?= base_url() . '/' . esc($schedule_delivery_status['image']) ?>" class="w-10 h-10 mr-2 dark:brightness-90" alt="<?= esc($schedule_delivery_status['title']) ?>" />
                                        <div>
                                            <p class="font-semibold dark:text-white"><?= esc($schedule_delivery_status['title']) ?></p>
                                            <p class="text-gray-500 dark:text-gray-400 text-xs"><?= esc($schedule_delivery_status['description']) ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($takeaway_status) && isset($takeaway_status['status']) && $takeaway_status['status'] == 1 && $seller_only_one_seller_cart == 1): ?>
                                    <div id="<?= esc($takeaway_status['id']) ?>" class="deliveryMethod flex flex-row w-full sm:w-1/3 p-2 border border-green-700 dark:border-green-600 rounded-lg cursor-pointer items-center text-green-700 dark:text-green-400 bg-white dark:bg-gray-800 hover:bg-green-50 dark:hover:bg-green-900/20" onclick="selectDeliveryMethod('<?= esc($takeaway_status['id']) ?>')">
                                        <img src="<?= base_url() . '/' . esc($takeaway_status['image']) ?>" class="w-10 h-10 mr-2 dark:brightness-90" alt="<?= esc($takeaway_status['title']) ?>" />
                                        <div>
                                            <p class="font-semibold dark:text-white"><?= esc($takeaway_status['title']) ?></p>
                                            <p class="text-gray-500 dark:text-gray-400 text-xs"><?= esc($takeaway_status['description']) ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Delivery Date -->
                        <div class="mb-3 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm hidden" id="deliveryDateDiv">
                            <div class="flex items-center gap-2 px-5 py-4 border-b border-dashed border-gray-100 dark:border-gray-700">
                                <i class="fi fi-rr-calendar text-orange-400 leading-none"></i>
                                <h4 class="font-bold text-sm capitalize dark:text-white"><?php echo lang('website.delivery_date'); ?></h4>
                            </div>
                            <div class="px-4 py-3 flex gap-2 overflow-x-auto no-scrollbar">
                                <?php foreach ($days as $day): ?>
                                    <button class="flex flex-col items-center flex-shrink-0 border py-2.5 px-4 rounded-xl bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:border-orange-400 hover:bg-orange-50 dark:hover:border-orange-400 dark:hover:bg-orange-900/20 transition-all duration-150 date"
                                        data-day="<?= $day['day'] ?>" data-date="<?= $day['date'] ?>"
                                        onclick="setActiveDate(this)">
                                        <span class="text-xs font-semibold"><?= $day['day'] ?></span>
                                        <span class="text-[11px] text-gray-400 dark:text-gray-400 mt-0.5"><?= $day['date'] ?></span>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Delivery Time -->
                        <div class="mb-3 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm hidden" id="deliveryTimeDiv">
                            <div class="flex items-center gap-2 px-5 py-4 border-b border-dashed border-gray-100 dark:border-gray-700">
                                <i class="fi fi-rr-clock text-purple-400 leading-none"></i>
                                <h4 class="font-bold text-sm capitalize dark:text-white"><?php echo lang('website.delivery_time'); ?></h4>
                            </div>
                            <div class="px-4 py-3 flex gap-2 overflow-x-auto no-scrollbar" id="timeslotDiv"></div>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-3 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                            <div class="flex items-center gap-2 px-5 py-4 border-b border-dashed border-gray-100 dark:border-gray-700">
                                <i class="fi fi-rr-credit-card text-blue-400 leading-none"></i>
                                <h4 class="font-bold text-sm capitalize dark:text-white"><?php echo lang('website.select_payment_method'); ?></h4>
                            </div>
                            <div class="grid grid-cols-3 sm:grid-cols-5 gap-4 px-5 py-4">
                                <?php foreach ($paymentMethods as $paymentMethod): ?>
                                    <div class="border dark:border-gray-600 flex flex-col items-center justify-center gap-2.5 py-4 rounded-lg cursor-pointer dark:bg-gray-700 transition-all duration-150"
                                        id="paymentMethod_<?= $paymentMethod['id'] ?>"
                                        onclick="setPaymentMethode(<?= $paymentMethod['id'] ?>)">
                                        <img class="h-6 dark:brightness-90" src="<?= base_url() . $paymentMethod['img'] ?>" alt="<?= $paymentMethod['title'] ?>">
                                        <span class="text-xs font-medium dark:text-gray-200"><?= $paymentMethod['title'] ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ── Right Sidebar ── -->
                <div class="w-full lg:w-1/3 md:w-full lg:sticky lg:top-6 self-start flex flex-col gap-3">

                    <!-- Coupon -->
                    <div id="couponDiv">
                        <!-- Apply Coupon -->
                        <div id="applyCouponDiv" class="rounded-2xl border border-green-400 dark:border-green-700 flex items-center gap-4 px-4 py-3.5 cursor-pointer bg-white dark:bg-gray-800 shadow-sm hover:shadow transition" onclick="openCouponPopup()">
                            <div class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-green-50 dark:bg-green-900/30 rounded-xl">
                                <i class="fi fi-rr-badge-percent text-xl text-green-600 dark:text-green-400 leading-none"></i>
                            </div>
                            <div class="flex-auto overflow-hidden">
                                <h4 class="font-semibold text-sm text-green-700 dark:text-green-400"><?php echo lang('website.apply_coupon_code'); ?></h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"><?php echo lang('website.get_discount_with_your_order'); ?></p>
                            </div>
                            <i class="fi fi-rr-angle-right text-green-500 dark:text-green-400 leading-none"></i>
                        </div>
                        <!-- Coupon Applied -->
                        <div id="couponAppliedDiv" class="rounded-2xl border border-green-500 dark:border-green-700 flex items-center gap-4 px-4 py-3.5 cursor-pointer bg-green-50 dark:bg-green-900/20 shadow-sm hidden">
                            <div class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-green-100 dark:bg-green-900/40 rounded-xl">
                                <i class="fi fi-rr-badge-percent text-xl text-green-600 dark:text-green-400 leading-none"></i>
                            </div>
                            <div class="flex-auto overflow-hidden">
                                <h4 class="font-semibold text-sm text-green-700 dark:text-green-400"><?php echo lang('website.coupon_applied'); ?></h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"><?php echo lang('website.you_saved'); ?> <span class="couponAmount font-semibold text-green-600 dark:text-green-400"></span></p>
                            </div>
                            <button type="button" onclick="removeCoupon()" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 transition flex-shrink-0">
                                <i class="fi fi-rr-trash text-red-500 dark:text-red-400 text-sm leading-none"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Delivery Tip -->
                    <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-2 px-5 py-4 border-b border-dashed border-gray-100 dark:border-gray-700">
                            <i class="fi fi-rr-hand-holding-heart text-green-500 leading-none"></i>
                            <div>
                                <h4 class="font-bold text-sm dark:text-white">Tip Your Delivery Partner</h4>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">100% goes to your delivery partner</p>
                            </div>
                        </div>
                        <div class="px-5 py-4 flex flex-col gap-3">
                            <div class="flex flex-wrap gap-2" id="tipChips">
                                <?php
                                $tipAmounts = [0, 10, 20, 30, 50];
                                foreach ($tipAmounts as $tip): ?>
                                <button type="button"
                                    onclick="selectTip(this, <?= $tip ?>)"
                                    data-tip="<?= $tip ?>"
                                    class="tip-chip text-sm font-semibold px-4 py-2 rounded-full border transition-all duration-150
                                        <?= $tip === 0 ? 'border-green-500 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300' : 'border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-700 hover:border-green-500 hover:text-green-700 dark:hover:border-green-400' ?>">
                                    <?= $tip === 0 ? 'No Tip' : $country['currency_symbol'] . $tip ?>
                                </button>
                                <?php endforeach; ?>
                                <button type="button" id="customTipBtn"
                                    onclick="showCustomTip()"
                                    class="tip-chip text-sm font-semibold px-4 py-2 rounded-full border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-700 hover:border-green-500 hover:text-green-700 dark:hover:border-green-400 transition-all duration-150">
                                    Custom
                                </button>
                            </div>
                            <div id="customTipInputDiv" class="hidden flex items-center gap-2">
                                <span class="text-sm font-semibold text-gray-500 dark:text-gray-400"><?= $country['currency_symbol'] ?></span>
                                <input type="number" id="customTipInput" min="1" placeholder="Enter amount"
                                    class="w-36 text-sm p-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500 transition"
                                    oninput="applyCustomTip(this.value)" />
                                <button type="button" onclick="cancelCustomTip()" class="text-xs text-red-500 dark:text-red-400 hover:underline">Cancel</button>
                            </div>
                            <div id="tipSummary" class="hidden flex items-center gap-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-3">
                                <i class="fi fi-rr-heart text-green-600 dark:text-green-400 leading-none flex-shrink-0"></i>
                                <span class="text-sm text-green-700 dark:text-green-300">Tipping <strong><?= $country['currency_symbol'] ?><span id="tipSummaryAmt">0</span></strong> — Thank you!</span>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                        
                        <div class="px-5 py-4 border-b border-dashed border-gray-100 dark:border-gray-700 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="fi fi-rr-receipt text-orange-400 leading-none"></i>
                                <h2 class="text-sm font-bold dark:text-white"><?php echo lang('website.order_summary'); ?></h2>
                            </div>
                            <button id="toggleSummaryBtn" class="text-xs font-medium text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 px-3 py-1.5 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition"><?php echo lang('website.show_details'); ?></button>
                        </div>

                        <div id="orderSummaryDetails" class="hidden">
                            <div class="divide-y divide-gray-100 dark:divide-gray-700 px-5">
                                <div class="flex items-center justify-between py-2.5 text-sm text-gray-600 dark:text-gray-300">
                                    <span class="flex items-center gap-1.5">
                                        <i class="fi fi-rr-shopping-bag leading-none text-xs text-gray-400"></i>
                                        <?php echo lang('website.subtotal'); ?>
                                    </span>
                                    <span class="font-medium">
                                        <?php if ($settings['currency_symbol_position'] == 'left'): ?>
                                            <?= $country['currency_symbol'] ?><span class="subtotal"><?= esc($subtotal) ?></span>
                                        <?php else: ?>
                                            <span class="subtotal"><?= esc($subtotal) ?></span><?= $country['currency_symbol'] ?>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <div class="flex items-center justify-between py-2.5 text-sm text-gray-600 dark:text-gray-300">
                                    <span class="flex items-center gap-1.5">
                                        <i class="fi fi-rr-receipt leading-none text-xs text-gray-400"></i>
                                        <?php echo lang('website.tax'); ?>
                                        <div class="relative group">
                                            <i class="fi fi-rr-lightbulb-question cursor-pointer text-xs text-gray-400"></i>
                                            <div class="absolute hidden group-hover:block bg-gray-800 dark:bg-gray-700 text-white text-xs py-1 px-2 rounded-lg shadow-md left-[150%] top-0 whitespace-nowrap z-10">
                                                <?php echo lang('website.some_product_based_tax'); ?>
                                            </div>
                                        </div>
                                    </span>
                                    <span class="font-medium">
                                        <?php if ($settings['currency_symbol_position'] == 'left'): ?>
                                            <?= $country['currency_symbol'] ?><span class="taxTotal"><?= esc($taxTotal) ?></span>
                                        <?php else: ?>
                                            <span class="taxTotal"><?= esc($taxTotal) ?></span><?= $country['currency_symbol'] ?>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <div class="flex items-center justify-between py-2.5 text-sm text-gray-600 dark:text-gray-300">
                                    <span class="flex items-center gap-1.5">
                                        <i class="fi fi-rr-truck-side leading-none text-xs text-gray-400"></i>
                                        <?php echo lang('website.delivery_charge'); ?>
                                        <div class="relative group">
                                            <i class="fi fi-rr-lightbulb-question cursor-pointer text-xs text-gray-400"></i>
                                            <div class="absolute hidden group-hover:block bg-gray-800 dark:bg-gray-700 text-white text-xs py-1 px-2 rounded-lg shadow-md left-[150%] top-0 whitespace-nowrap z-10">
                                                <?php echo lang('website.calculate_based_on_delivery_distance'); ?>
                                            </div>
                                        </div>
                                    </span>
                                    <span class="font-medium">
                                        <?php if ($settings['currency_symbol_position'] == 'left'): ?>
                                            <?= $country['currency_symbol'] ?><span class="deliveryCharge"><?= esc($deliveryCharge) ?></span>
                                        <?php else: ?>
                                            <span class="deliveryCharge"><?= esc($deliveryCharge) ?></span><?= $country['currency_symbol'] ?>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <?php if ($additional_charge_status == 1): ?>
                                <div class="flex items-center justify-between py-2.5 text-sm text-gray-600 dark:text-gray-300 additional_charge_div">
                                    <span class="flex items-center gap-1.5">
                                        <i class="fi fi-rr-plus-small leading-none text-xs text-gray-400"></i>
                                        <?= $additional_charge_name ?>
                                    </span>
                                    <span class="font-medium">
                                        <?php if ($settings['currency_symbol_position'] == 'left'): ?>
                                            <?= $country['currency_symbol'] ?><span class="additional_charge"><?= esc($additional_charge) ?></span>
                                        <?php else: ?>
                                            <span class="additional_charge"><?= esc($additional_charge) ?></span><?= $country['currency_symbol'] ?>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                                <div class="flex items-center justify-between py-2.5 text-sm">
                                    <span class="text-gray-600 dark:text-gray-300 flex items-center gap-1.5">
                                        <i class="fi fi-rr-ticket-alt text-green-500 leading-none text-xs"></i>
                                        <?php echo lang('website.discount'); ?>
                                        <span class="text-xs text-red-500 dark:text-red-400 discountInPercIfApplicable"></span>
                                    </span>
                                    <span class="font-medium text-green-600 dark:text-green-400">
                                        <?php if ($settings['currency_symbol_position'] == 'left'): ?>
                                            – <?= $country['currency_symbol'] ?><span class="couponAmount">0</span>
                                        <?php else: ?>
                                            – <span class="couponAmount">0</span><?= $country['currency_symbol'] ?>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <div class="flex items-center justify-between py-2.5 text-sm">
                                    <span class="text-gray-600 dark:text-gray-300 flex items-center gap-1.5">
                                        <i class="fi fi-rr-wallet text-indigo-400 leading-none text-xs"></i>
                                        <?php echo lang('website.wallet'); ?>
                                        <span class="text-xs text-green-600 dark:text-green-400 cursor-pointer remaining_wallet_balance" onclick="applyWallet()">(<?= esc($wallet) ?> apply)</span>
                                    </span>
                                    <span class="font-medium text-indigo-500 dark:text-indigo-400">
                                        <?php if ($settings['currency_symbol_position'] == 'left'): ?>
                                            – <?= $country['currency_symbol'] ?><span class="wallet_applied">0</span>
                                        <?php else: ?>
                                            – <span class="wallet_applied">0</span><?= $country['currency_symbol'] ?>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <div class="flex items-center justify-between py-2.5 text-sm tip_summary_row hidden">
                                    <span class="text-green-600 dark:text-green-400 flex items-center gap-1.5">
                                        <i class="fi fi-rr-hand-holding-heart leading-none text-xs"></i>
                                        Delivery Tip
                                    </span>
                                    <span class="font-medium text-green-600 dark:text-green-400">
                                        <?php if ($settings['currency_symbol_position'] == 'left'): ?>
                                            + <?= $country['currency_symbol'] ?><span class="tip_amount_display">0</span>
                                        <?php else: ?>
                                            + <span class="tip_amount_display">0</span><?= $country['currency_symbol'] ?>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Grand Total -->
                        <div class="flex items-center justify-between px-5 py-3.5 bg-gray-50 dark:bg-gray-700/40 border-t border-gray-100 dark:border-gray-700">
                            <span class="font-bold text-sm dark:text-white"><?php echo lang('website.grand_total'); ?></span>
                            <span class="font-bold text-green-500 text-base">
                                <?php if ($settings['currency_symbol_position'] == 'left'): ?>
                                    <?= $country['currency_symbol'] ?><span class="grand_total">0</span>
                                <?php else: ?>
                                    <span class="grand_total">0</span><?= $country['currency_symbol'] ?>
                                <?php endif; ?>
                            </span>
                        </div>

                        <!-- Pay Button -->
                        <div class="px-5 pb-5 pt-3 flex flex-col gap-2">
                            <button onclick="verifyOrderDetails()" id="verifyOrderDetails"
                                class="w-full flex items-center justify-between bg-green-600 hover:bg-green-700 active:bg-green-700 text-white font-semibold text-sm py-3.5 px-5 rounded-xl shadow focus:outline-none focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800 disabled:opacity-50 disabled:pointer-events-none transition">
                                <span class="flex items-center gap-2">
                                    <i class="fi fi-rr-lock leading-none"></i>
                                    <?php echo lang('website.save_&_pay'); ?>
                                </span>
                                <span class="font-bold">
                                    <?php if ($settings['currency_symbol_position'] == 'left'): ?>
                                        <?= $country['currency_symbol'] ?><span class="grand_total">0</span>
                                    <?php else: ?>
                                        <span class="grand_total">0</span><?= $country['currency_symbol'] ?>
                                    <?php endif; ?>
                                </span>
                            </button>
                            <div id="paypal-button-container" class="hidden"></div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 text-center leading-relaxed">
                                <?php echo lang('website.by_placing_your_order_you_agree_to_be_bound_by_the'); ?>
                                <a href="<?php echo base_url() . 'terms-condition' ?>" target="_blank" class="text-green-600 dark:text-green-400 hover:underline"><?php echo lang('website.terms_condition'); ?></a>
                                <?php echo lang('website.and'); ?>
                                <a href="<?php echo base_url() . 'privacy-policy' ?>" target="_blank" class="text-green-600 dark:text-green-400 hover:underline"><?php echo lang('website.privacy_policy'); ?></a>
                            </p>
                        </div>
                    </div>

                    <!-- GST Number -->
                    <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-2 px-5 py-4 border-b border-dashed border-gray-100 dark:border-gray-700">
                            <i class="fi fi-rr-document-signed text-blue-500 leading-none"></i>
                            <div>
                                <h4 class="font-bold text-sm dark:text-white">GST Number</h4>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Optional — for business invoicing with GST</p>
                            </div>
                        </div>
                        <div class="px-5 py-4">
                            <input type="text" id="gstNumberInput" maxlength="15"
                                placeholder="e.g. 27AAPFU0939F1ZV"
                                class="w-full text-sm p-3 rounded-xl border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400 uppercase transition"
                                oninput="this.value = this.value.toUpperCase()">
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Your GST number will be printed on the invoice.</p>
                        </div>
                    </div>

                    <!-- Delivery Instructions -->
                    <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-2 px-5 py-4 border-b border-dashed border-gray-100 dark:border-gray-700">
                            <i class="fi fi-rr-memo-pad text-amber-500 leading-none"></i>
                            <div>
                                <h4 class="font-bold text-sm dark:text-white">Delivery Instructions</h4>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Optional — special notes for delivery</p>
                            </div>
                        </div>
                        <div class="px-5 py-4 flex flex-col gap-3">
                            <div class="flex flex-wrap gap-2" id="instructionChips">
                                <?php
                                $chips = ['Leave at door', 'Ring doorbell', 'Call on arrival', 'Leave with security', 'Contactless delivery'];
                                foreach ($chips as $chip): ?>
                                <button type="button"
                                    onclick="toggleInstructionChip(this)"
                                    data-value="<?= $chip ?>"
                                    class="instruction-chip text-xs px-3 py-1.5 rounded-full border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-700 hover:border-amber-400 hover:text-amber-700 dark:hover:border-amber-400 dark:hover:text-amber-400 transition-all duration-150 select-none">
                                    <?= $chip ?>
                                </button>
                                <?php endforeach; ?>
                            </div>
                            <textarea id="deliveryInstructionInput" rows="2"
                                placeholder="Add any specific delivery notes…"
                                class="w-full text-sm p-3 rounded-xl border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-400 resize-none transition"></textarea>
                            <p class="text-xs text-gray-400 dark:text-gray-500">Tap a chip to add it to your note.</p>
                        </div>
                    </div>

                </div>

            </div>

            <div id="card-element"></div>

        </section>

        <?= $this->include('website/template/mobileBottomMenu') ?>
        <div id="orderResponsePopup" class="fixed inset-0 flex items-center justify-center bg-black/50 z-50 hidden"></div>
    </main>

    <?= $this->include('website/template/shopCart') ?>
    <?= $this->include('website/template/address') ?>
    <?= $this->include('website/template/coupon') ?>
    <?= $this->include('website/template/footer') ?>
    <?= $this->include('website/template/script') ?>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://js.paystack.co/v2/inline.js"></script>
    <script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>

    <?= $this->include('website/template/checkoutScript') ?>

    <!-- Summary toggle -->
    <script>
        const toggleBtn = document.getElementById('toggleSummaryBtn');
        const summaryDetails = document.getElementById('orderSummaryDetails');
        let isVisible = false;
        toggleBtn.addEventListener('click', () => {
            isVisible = !isVisible;
            if (isVisible) {
                summaryDetails.classList.remove('hidden');
                toggleBtn.textContent = 'Hide Details';
            } else {
                summaryDetails.classList.add('hidden');
                toggleBtn.textContent = 'Show Details';
            }
        });
    </script>

    <!-- Delivery Tip & Instruction Logic -->
    <script>
        let selectedTip = 0;

        function selectTip(el, amount) {
            selectedTip = amount;
            // Deactivate all preset chips (not custom btn)
            document.querySelectorAll('#tipChips .tip-chip').forEach(c => c.classList.remove('active'));
            el.classList.add('active');
            // Hide custom input
            document.getElementById('customTipInputDiv').classList.add('hidden');
            document.getElementById('customTipInput').value = '';
            // Update summary
            updateTipSummary(amount);
            recalcGrandTotal();
        }

        function showCustomTip() {
            document.querySelectorAll('#tipChips .tip-chip').forEach(c => c.classList.remove('active'));
            document.getElementById('customTipBtn').classList.add('active');
            document.getElementById('customTipInputDiv').classList.remove('hidden');
            document.getElementById('customTipInput').focus();
        }

        function cancelCustomTip() {
            document.getElementById('customTipInputDiv').classList.add('hidden');
            document.getElementById('customTipInput').value = '';
            document.getElementById('customTipBtn').classList.remove('active');
            // Reactivate "No Tip"
            const noTipBtn = document.querySelector('#tipChips .tip-chip[data-tip="0"]');
            if (noTipBtn) { noTipBtn.classList.add('active'); }
            selectedTip = 0;
            updateTipSummary(0);
            recalcGrandTotal();
        }

        function applyCustomTip(val) {
            const amount = parseFloat(val) || 0;
            selectedTip = amount;
            updateTipSummary(amount);
            recalcGrandTotal();
        }

        function updateTipSummary(amount) {
            const summary = document.getElementById('tipSummary');
            const amtSpan = document.getElementById('tipSummaryAmt');
            const tipRows = document.querySelectorAll('.tip_summary_row');
            const tipDisplays = document.querySelectorAll('.tip_amount_display');

            if (amount > 0) {
                summary.classList.remove('hidden');
                amtSpan.textContent = parseFloat(amount).toFixed(2);
                tipRows.forEach(r => r.classList.remove('hidden'));
                tipDisplays.forEach(d => d.textContent = parseFloat(amount).toFixed(2));
            } else {
                summary.classList.add('hidden');
                tipRows.forEach(r => r.classList.add('hidden'));
                tipDisplays.forEach(d => d.textContent = '0');
            }
        }

        function recalcGrandTotal() {
            // Grab existing values from the DOM and add tip
            const subtotal = parseFloat(document.querySelector('.subtotal')?.innerText || 0) || 0;
            const tax = parseFloat(document.querySelector('.taxTotal')?.innerText || 0) || 0;
            const delivery = parseFloat(document.querySelector('.deliveryCharge')?.innerText || 0) || 0;
            const additional = parseFloat(document.querySelector('.additional_charge')?.innerText || 0) || 0;
            const coupon = parseFloat(document.querySelector('.couponAmount')?.innerText || 0) || 0;
            const wallet = parseFloat(document.querySelector('.wallet_applied')?.innerText || 0) || 0;
            const tip = selectedTip || 0;

            const grand = subtotal + tax + delivery + additional + tip - coupon - wallet;
            document.querySelectorAll('.grand_total').forEach(el => {
                el.innerText = grand.toFixed(2);
            });
        }

        function getDeliveryInstruction() {
            return document.getElementById('deliveryInstructionInput')?.value?.trim() || '';
        }

        function getGstNumber() {
            return document.getElementById('gstNumberInput')?.value?.trim() || '';
        }

        function getDeliveryTip() {
            return selectedTip || 0;
        }

        function toggleInstructionChip(el) {
            const val = el.dataset.value;
            const textarea = document.getElementById('deliveryInstructionInput');
            if (el.classList.contains('active')) {
                // Deactivate — remove from textarea
                el.classList.remove('active');
                const current = textarea.value;
                textarea.value = current.replace(val, '').replace(/,\s*,/g, ',').replace(/^,\s*|,\s*$/g, '').trim();
            } else {
                el.classList.add('active');
                const current = textarea.value.trim();
                textarea.value = current ? current + ', ' + val : val;
            }
        }

        // Init: activate "No Tip" chip on load
        document.addEventListener('DOMContentLoaded', function () {
            const noTipBtn = document.querySelector('#tipChips .tip-chip[data-tip="0"]');
            if (noTipBtn) noTipBtn.classList.add('active');
        });
    </script>

    <script>
    (function () {
        const isDark = () => document.documentElement.classList.contains('dark');


        function applyPaymentDark() {
            if (!isDark()) return;
            document.querySelectorAll('[id^="paymentMethod_"]').forEach(card => {
                const span = card.querySelector('span');
                const img  = card.querySelector('img');
                if (card.classList.contains('border-red-400')) {
                    /* ACTIVE */
                    card.style.setProperty('background-color', '#3b0a0a', 'important');
                    card.style.setProperty('border-color',     '#f87171', 'important');
                    card.style.setProperty('box-shadow', '0 0 0 2px #f8717155', 'important');
                    if (span) span.style.setProperty('color', '#ffffff', 'important');
                    if (img)  img.style.setProperty('filter', 'brightness(1.2)', 'important');
                } else {
                    /* INACTIVE */
                    card.style.setProperty('background-color', '#374151', 'important');
                    card.style.setProperty('border-color',     '#4b5563', 'important');
                    card.style.setProperty('box-shadow', 'none', 'important');
                    if (span) span.style.setProperty('color', '#e5e7eb', 'important');
                    if (img)  img.style.setProperty('filter', 'brightness(0.85)', 'important');
                }
            });
        }

        function applyDateTimeDark() {
            if (!isDark()) return;
            document.querySelectorAll('.date, .time').forEach(btn => {
                const small = btn.querySelector('small');
                if (btn.classList.contains('bg-green-50')) {
                    /* ACTIVE */
                    btn.style.setProperty('background-color', '#15803d', 'important');
                    btn.style.setProperty('border-color',     '#4ade80', 'important');
                    btn.style.setProperty('color',            '#ffffff', 'important');
                    btn.style.setProperty('box-shadow', '0 0 0 2px #4ade8055', 'important');
                    if (small) small.style.setProperty('color', '#bbf7d0', 'important');
                } else {
                    /* INACTIVE */
                    btn.style.setProperty('background-color', '#374151', 'important');
                    btn.style.setProperty('border-color',     '#6b7280', 'important');
                    btn.style.setProperty('color',            '#f3f4f6', 'important');
                    btn.style.setProperty('box-shadow', 'none', 'important');
                    if (small) small.style.setProperty('color', '#9ca3af', 'important');
                }
            });
        }

        function observe() {
            const mo = new MutationObserver(() => {
                applyPaymentDark();
                applyDateTimeDark();
            });
            const opts = { attributes: true, attributeFilter: ['class'] };

            document.querySelectorAll('[id^="paymentMethod_"], .date').forEach(el => mo.observe(el, opts));

            const timeslotDiv = document.getElementById('timeslotDiv');
            if (timeslotDiv) {
                mo.observe(timeslotDiv, { childList: true, subtree: true, attributes: true, attributeFilter: ['class'] });
            }
        }

        document.addEventListener('click', e => {
            if (
                e.target.closest('[id^="paymentMethod_"]') ||
                e.target.closest('.date') ||
                e.target.closest('.time')
            ) {
                setTimeout(() => { applyPaymentDark(); applyDateTimeDark(); }, 30);
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            applyPaymentDark();
            applyDateTimeDark();
            observe();
        });

    })();
    </script>

</body>
</html>