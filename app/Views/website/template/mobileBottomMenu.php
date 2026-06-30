<!-- View Cart floating bar - mobile (above bottom tabs) -->
<div id="mobileViewCartBar" class="fixed bottom-[4.5rem] left-1/2 -translate-x-1/2 w-[85%] max-w-md z-50 hidden md:hidden">
    <div onclick="toggleShoppingCart()"
         class="flex items-center justify-between bg-gradient-to-r from-green-600 to-green-700 text-white rounded-2xl px-4 py-3 shadow-[0_8px_30px_rgba(22,163,74,0.35)] cursor-pointer backdrop-blur-sm">
        <div class="flex items-center gap-3">
            <!-- Up to 3 overlapping product images -->
            <div id="mobileCartImages" class="flex items-center" style="direction:ltr"></div>
            <div class="leading-tight">
                <div class="font-bold text-sm tracking-wide">View Cart</div>
                <div id="mobileCartBarCount" class="text-[11px] text-green-100 font-medium">0 items</div>
            </div>
        </div>
        <div class="flex items-center justify-center w-8 h-8 bg-white/20 rounded-full">
            <i class="fi fi-rr-angle-right text-sm"></i>
        </div>
    </div>
</div>

<!-- View Cart floating button - desktop -->
<div id="desktopViewCartBtn" class="fixed bottom-6 right-6 z-40" style="display:none">
    <button onclick="toggleShoppingCart()"
            class="flex items-center gap-3 bg-green-600 hover:bg-green-700 text-white rounded-full px-5 py-3 shadow-xl transition-all duration-200">
        <!-- Up to 3 overlapping product images -->
        <div id="desktopCartImages" class="flex items-center" style="direction:ltr"></div>
        <span class="font-semibold text-sm" id="desktopCartBarCount">View cart</span>
    </button>
</div>

<?php
    // Detect active page for bottom tab highlighting
    $currentUri = uri_string();
    $isHome = ($currentUri === '' || $currentUri === '/' || $currentUri === 'home');
    $isCategory = (strpos($currentUri, 'category') === 0);
    $isOrders = (strpos($currentUri, 'order-history') === 0 || strpos($currentUri, 'order') === 0);
    $isNotification = (strpos($currentUri, 'notification') === 0);
    $isMenu = (strpos($currentUri, 'menu') === 0);
?>

<!-- bottom mobile menu start -->
<nav class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800 block md:hidden z-30">
    <div class="flex items-end justify-around px-2 pt-1 pb-1 max-w-lg mx-auto" style="padding-bottom: env(safe-area-inset-bottom, 4px);">

        <!-- Home -->
        <a href="/" class="flex flex-col items-center gap-0.5 py-1.5 px-2 min-w-[3.5rem] group">
            <div class="flex items-center justify-center w-8 h-8 rounded-xl transition-all duration-200 <?= $isHome ? 'bg-green-100 dark:bg-green-900/40' : 'group-hover:bg-gray-100 dark:group-hover:bg-gray-800' ?>">
                <i class="fi fi-rr-house-chimney text-base <?= $isHome ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400' ?>"></i>
            </div>
            <span class="text-[10px] font-semibold leading-tight <?= $isHome ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400' ?>"><?= lang('website.home') ?></span>
        </a>

        <!-- Category -->
        <a href="/category" class="flex flex-col items-center gap-0.5 py-1.5 px-2 min-w-[3.5rem] group">
            <div class="flex items-center justify-center w-8 h-8 rounded-xl transition-all duration-200 <?= $isCategory ? 'bg-green-100 dark:bg-green-900/40' : 'group-hover:bg-gray-100 dark:group-hover:bg-gray-800' ?>">
                <i class="fi fi-rr-category-alt text-base <?= $isCategory ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400' ?>"></i>
            </div>
            <span class="text-[10px] font-semibold leading-tight <?= $isCategory ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400' ?>"><?= lang('website.category') ?></span>
        </a>

        <!-- Orders (center elevated button) -->
        <a href="/order-history" class="flex flex-col items-center -mt-4 group">
            <div class="flex items-center justify-center w-14 h-14 rounded-2xl shadow-lg transition-all duration-200
                <?= $isOrders
                    ? 'bg-green-600 shadow-green-600/30'
                    : 'bg-gradient-to-br from-green-500 to-green-700 shadow-green-600/25 group-hover:shadow-green-600/40 group-hover:scale-105' ?>">
                <i class="fi fi-rr-order-history text-xl text-white pt-1"></i>
            </div>
            <span class="text-[10px] font-semibold leading-tight mt-1 <?= $isOrders ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400' ?>"><?= lang('website.orders') ?? 'Orders' ?></span>
        </a>

        <!-- Notification -->
        <a href="/notification" class="flex flex-col items-center gap-0.5 py-1.5 px-2 min-w-[3.5rem] group">
            <div class="flex items-center justify-center w-8 h-8 rounded-xl transition-all duration-200 <?= $isNotification ? 'bg-green-100 dark:bg-green-900/40' : 'group-hover:bg-gray-100 dark:group-hover:bg-gray-800' ?>">
                <i class="fi fi-rr-bell text-base <?= $isNotification ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400' ?>"></i>
            </div>
            <span class="text-[10px] font-semibold leading-tight <?= $isNotification ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400' ?>"><?= lang('website.notification') ?></span>
        </a>

        <!-- Menu / Profile -->
        <a href="/menu" class="flex flex-col items-center gap-0.5 py-1.5 px-2 min-w-[3.5rem] group">
            <div class="flex items-center justify-center w-8 h-8 rounded-xl transition-all duration-200 <?= $isMenu ? 'bg-green-100 dark:bg-green-900/40' : 'group-hover:bg-gray-100 dark:group-hover:bg-gray-800' ?>">
                <?php if ((session()->has('email') && session()->get('is_email_verified') == 1) || (session()->has('mobile') && session()->get('is_mobile_verified') == 1)): ?>
                    <img class="w-6 h-6 rounded-lg object-cover ring-1 <?= $isMenu ? 'ring-green-400' : 'ring-gray-200 dark:ring-gray-600' ?>"
                         src="<?= isset($user) ? (($user['login_type'] === 'mobile') ? (isset($user['img']) ? $user['img'] : base_url() . $settings['logo']) : base_url() . $settings['logo']) : base_url() . $settings['logo'] ?>"
                         alt="Profile">
                <?php else: ?>
                    <i class="fi fi-rr-bars-staggered text-base <?= $isMenu ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400' ?>"></i>
                <?php endif; ?>
            </div>
            <span class="text-[10px] font-semibold leading-tight <?= $isMenu ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400' ?>"><?= lang('website.menu') ?></span>
        </a>

    </div>
</nav>
<!-- bottom mobile menu end -->

<!-- Hide floating cart & bottom nav when any modal is open -->
<style>
    body.modal-open #mobileViewCartBar,
    body.modal-open #desktopViewCartBtn,
    body.modal-open nav.fixed.bottom-0 {
        display: none !important;
    }
</style>
