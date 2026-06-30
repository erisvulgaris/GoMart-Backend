<div id="pageLoader" class="fixed inset-0 w-full h-full bg-white/95 z-[9999] flex justify-center items-center font-sans">
    <div class="text-center w-full max-w-xs">
        <div class="relative h-32 mb-5">
            <!-- Cart Icon -->
            <div class="absolute left-[41%] -translate-x-1/2 w-14 h-14 animate-bounce">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 2L3 6V20C3 20.5304 3.21071 21.0391 3.58579 21.4142C3.96086 21.7893 4.46957 22 5 22H19C19.5304 22 20.0391 21.7893 20.4142 21.4142C20.7893 21.0391 21 20.5304 21 20V6L18 2H6Z" stroke="#4CAF50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M3 6H21" stroke="#4CAF50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M16 10C16 11.0609 15.5786 12.0783 14.8284 12.8284C14.0783 13.5786 13.0609 14 12 14C10.9391 14 9.92172 13.5786 9.17157 12.8284C8.42143 12.0783 8 11.0609 8 10" stroke="#4CAF50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>

            <!-- Produce Items -->
            <div class="absolute bottom-0 w-full flex justify-center gap-4">
                <!-- Apple -->
                <div class="fadeInDrop w-7 h-7 rounded-full bg-red-500 relative" style="animation-delay: 0.2s">
                    <div class="absolute -top-1 left-2 w-1 h-3 bg-green-500 rounded-sm -rotate-6"></div>
                </div>

                <!-- Banana -->
                <div class="fadeInDrop w-7 h-7 rounded-full bg-yellow-400 relative" style="animation-delay: 0.4s">
                    <div class="absolute top-3 -left-1 w-1 h-4 bg-amber-800 rounded-sm rotate-12"></div>
                </div>

                <!-- Carrot -->
                <div class="fadeInDrop w-7 h-7 rounded-full bg-orange-500 relative" style="animation-delay: 0.6s">
                    <div class="absolute -top-2 left-3 w-1 h-4 bg-lime-400 rounded-sm rotate-3"></div>
                </div>
            </div>
        </div>

        <p class="text-green-600 text-xl font-semibold mb-4"><?= $settings['website_loading_text'] ?></p>

        <div class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden">
            <div class="progress-bar h-full bg-gradient-to-r from-green-600 to-lime-500 rounded-full w-0"></div>
        </div>
    </div>
</div>

<?php
$langModel = new \App\Models\LanguageModel();
$languageList = $langModel->where('is_active', 1)->findAll();
$currentLang = session()->get('site_lang') ?? 'en';

?>

<header class="bg-white dark:bg-gray-900 sticky top-0 z-30">

    <!-- ===== MAIN HEADER BAR ===== -->
    <div class="border-b dark:border-gray-700">
        <div class="px-3 py-2 md:py-3">
            <div class="container md:mx-auto max-w-[85rem]">
                <div class="flex items-center justify-between w-full gap-2">

                    <!-- LEFT: Logo + Delivery + Delivery Time -->
                    <div class="flex items-center gap-2 md:gap-3 min-w-0 flex-shrink-0 <?= space_reverse() ?>">
                        <a href="/" class="flex-shrink-0">
                            <img src="<?= base_url($settings['logo']) ?>" class="rounded-lg drop-shadow <?php if ($settings['logo_aspect_ratio'] == '1:1') { echo 'w-8 md:w-10'; } else { echo 'w-20 md:w-28 h-10 md:h-14 object-contain'; } ?>" alt="<?= $settings['business_name'] ?>" />
                        </a>
                        <div class="min-w-0 max-w-[160px] md:max-w-[220px] cursor-pointer" onclick="openLocationModel()">
                            <!-- Top line: "Delivery in X minutes" or "Delivery to" -->
                            <div class="flex items-center gap-1.5">
                                <!-- Default (no location set) -->
                                <div id="deliveryLabelDefault" class="flex items-center gap-1">
                                    <span class="text-xs md:text-sm font-semibold text-gray-800 dark:text-white truncate"><?php echo lang('website.delivery_to'); ?></span>
                                    <i class="fi fi-tr-caret-down text-xs text-gray-500 flex-shrink-0"></i>
                                </div>
                                <!-- When location set, show "Delivery in X min" -->
                                <div id="deliveryLabelWithTime" class="hidden items-center gap-1.5">
                                    <span class="text-sm md:text-base font-bold text-gray-900 dark:text-white">Delivery in</span>
                                    <span id="proxyDeliveryTime" class="text-sm md:text-base font-bold text-gray-900 dark:text-white"></span>
                                    <span class="text-sm md:text-base font-bold text-gray-900 dark:text-white">min</span>
                                    <i class="fi fi-tr-caret-down text-xs text-gray-500 flex-shrink-0"></i>
                                </div>
                            </div>
                            <!-- Bottom line: address -->
                            <div class="flex items-center gap-1 mt-0.5 max-w-[200px] md:max-w-xs">
                                <span id="locationBarSubtitle" class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1 font-medium"><?php echo lang('website.choose_location'); ?></span>
                            </div>
                            <!-- Hidden wrapper kept for JS compatibility -->
                            <div id="proxyDeliveryTimeWrapper" class="hidden"></div>
                        </div>
                    </div>

                    <!-- CENTER: Search Bar (desktop only) -->
                    <div class="hidden md:flex flex-1 mx-4 relative">
                        <div class="relative w-full">
                            <button class="absolute left-3 top-2.5">
                                <i class="fi fi-tr-issue-loupe text-lg dark:text-gray-400"></i>
                            </button>
                            <input type="text" placeholder="<?php echo lang('website.search_for_products'); ?>"
                                class="w-full pl-10 pr-4 py-2.5 border dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 focus:ring-2 focus:ring-green-500 focus:outline-none text-sm"
                                autocomplete="off"
                                onfocus="openSearchDropdown()"
                                oninput="handleSearchInput(this)"
                                onkeydown="if(event.key==='Enter'){navigateToSearch(this.value)}">
                        </div>

                        <!-- Search Dropdown -->
                        <div id="searchDropdown" class="hidden absolute top-full left-0 right-0 mt-2 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 z-50 max-h-[70vh] overflow-y-auto"
                            style="animation: slideDown 0.2s ease-out;">
                            <!-- Trending Searches -->
                            <div class="p-6 border-b dark:border-gray-700">
                                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Trending Searches</h3>
                                <div id="trendingSearchesList" class="space-y-2">
                                    <div class="text-gray-400 dark:text-gray-500 text-sm">Loading...</div>
                                </div>
                            </div>

                            <!-- Popular / Search Results Products -->
                            <div class="p-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 id="dropdownResultsTitle" class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Popular Products</h3>
                                    <a href="/search" class="text-green-600 hover:text-green-700 text-sm font-medium">See All</a>
                                </div>
                                <div id="trendingProductsList" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="text-gray-400 dark:text-gray-500 text-sm col-span-4">Loading...</div>
                                </div>
                                <div id="dropdownSearchResults" class="hidden grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Search Dropdown Overlay -->
                    <div id="searchOverlay" class="hidden fixed inset-0 bg-black/30 z-40" onclick="closeSearchDropdown()"></div>

                    <!-- RIGHT: Action Buttons -->
                    <div class="flex items-center gap-1 md:gap-3 flex-shrink-0 <?= flex_direction() ?>">

                        <!-- Language Dropdown (desktop only) -->
                        <div class="relative hidden md:block">
                            <button id="langToggle" class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white flex items-center space-x-1 <?= flex_direction() ?> hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors" onclick="toggleLangDropdown()">
                                <span id="currentLang" class="font-medium text-sm"><?= strtoupper($currentLang) ?></span>
                                <i class="fi fi-tr-caret-down text-xs"></i>
                            </button>
                            <ul class="lang-dropdown-menu absolute bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 mt-2 rounded-lg shadow-lg z-20 hidden min-w-[140px] <?= space_reverse() == 'space-x-reverse' ? 'left-0' : 'right-0' ?>" id="langDropdown">
                                <?php foreach ($languageList as $language): ?>
                                    <li>
                                        <a href="<?= base_url('language/' . $language['id']) ?>" class="dropdown-item block px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 flex items-center space-x-2 <?= flex_direction() ?> whitespace-nowrap" onclick="closeLangDropdown()">
                                            <span><?= strtoupper(htmlspecialchars($language['lang_short'])) ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <!-- Dark Mode Toggle (desktop only) -->
                        <button onclick="toggleDark()"
                            class="hidden md:flex p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors items-center justify-center">
                            <span id="theme-icon" class="text-sm leading-none">
                                <i class="fi fi-rr-moon dark:hidden text-base"></i>
                                <i class="fi fi-rr-sun hidden dark:inline text-base"></i>
                            </span>
                        </button>

                        <!-- Cart Button -->
                        <button type="button" class="text-gray-600 dark:text-gray-300 relative p-2" onclick="toggleShoppingCart()">
                            <i class="fi fi-tr-cart-shopping-fast text-xl md:text-2xl"></i>
                            <span id="cartCount"
                                class="absolute -top-0.5 -right-0.5 md:top-0 md:right-0 rounded-full h-4 w-4 bg-green-600 text-white text-center font-semibold text-[10px] leading-4">
                                <?= $cartItemCount ?>
                            </span>
                        </button>

                        <!-- Profile Button -->
                        <?php if ((session()->has('email') && session()->get('is_email_verified') == 1) || (session()->has('mobile') && session()->get('is_mobile_verified') == 1)) { ?>
                            <div class="relative">
                                <a href="#!" class="flex dropdown-toggle text-reset items-center p-1" id="dropdownUserLink">
                                    <img class="h-7 w-7 md:h-8 md:w-8 rounded-full ring-2 ring-gray-200 dark:ring-gray-600" src="<?php
                                        echo isset($user)
                                            ? (
                                                $user['login_type'] === 'mobile'
                                                ? (isset($user['img']) ? $user['img'] : base_url() . $settings['logo'])
                                                : (
                                                    $user['login_type'] === 'google'
                                                    ? $user['img']
                                                    : base_url() . $settings['logo']
                                                )
                                            )
                                            : base_url() . $settings['logo'];
                                    ?>" alt="">
                                </a>

                                <ul class="dropdown-menu absolute bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 mt-2 rounded-lg shadow-lg z-10 hidden w-35 <?= session()->get('is_rtl') ? 'left-0' : 'right-0' ?>" id="dropdownUser">
                                    <li>
                                        <a href="/order-history" class="dropdown-item block px-2 py-1 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 whitespace-nowrap">
                                            <i class="fi fi-rr-order-history"></i><?php echo lang('website.order'); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/profile" class="dropdown-item block px-2 py-1 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 whitespace-nowrap">
                                            <i class="fi fi-rr-circle-user"></i> <?php echo lang('website.account'); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/address" class="dropdown-item block px-2 py-1 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 whitespace-nowrap">
                                            <i class="fi fi-rr-marker"></i> <?php echo lang('website.address'); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/wallet" class="dropdown-item block px-2 py-1 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 whitespace-nowrap">
                                            <i class="fi fi-rr-wallet"></i> <?php echo lang('website.wallet'); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/logout" class="dropdown-item block px-2 py-1 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 whitespace-nowrap">
                                            <i class="fi fi-rr-sign-out-alt"></i> <?php echo lang('website.logout'); ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        <?php } else { ?>
                            <a href="/login" class="text-gray-600 dark:text-gray-300 p-1">
                                <i class="fi fi-tr-circle-user text-xl md:text-2xl"></i>
                            </a>
                        <?php } ?>

                        <!-- Search Button (mobile only) -->
                        <a href="/search" class="md:hidden text-gray-600 dark:text-gray-300 p-2">
                            <i class="fi fi-tr-issue-loupe text-xl"></i>
                        </a>

                        <!-- More Menu Button (mobile only) - for Language & Dark Mode -->
                        <button id="mobileMoreBtn" class="md:hidden text-gray-600 dark:text-gray-300 p-2" onclick="toggleMobileMoreMenu()">
                            <i class="fi fi-rr-menu-dots-vertical text-lg"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div id="mobileMoreMenu" class="hidden md:hidden border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-800 animate-slideDown">
        <div class="px-3 py-2">
            <div class="container mx-auto max-w-[85rem]">
                <div class="flex items-center justify-between gap-3">
                    <!-- Language Selector (mobile) -->
                    <div class="relative flex-1">
                        <button id="langToggleMobile" class="w-full px-3 py-2 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white flex items-center justify-between hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors border border-gray-200 dark:border-gray-600" onclick="toggleLangDropdownMobile()">
                            <span class="flex items-center gap-2">
                                <i class="fi fi-rr-globe text-sm"></i>
                                <span class="font-medium text-sm"><?= strtoupper($currentLang) ?></span>
                            </span>
                            <i class="fi fi-tr-caret-down text-xs"></i>
                        </button>
                        <ul class="absolute bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 mt-1 rounded-lg shadow-lg z-20 hidden min-w-full left-0" id="langDropdownMobile">
                            <?php foreach ($languageList as $language): ?>
                                <li>
                                    <a href="<?= base_url('language/' . $language['id']) ?>" class="block px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 text-sm">
                                        <?= strtoupper(htmlspecialchars($language['lang_short'])) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Dark Mode Toggle (mobile) -->
                    <button onclick="toggleDark()"
                        class="px-4 py-2 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white border border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors flex items-center gap-2">
                        <i class="fi fi-rr-moon dark:hidden text-sm"></i>
                        <i class="fi fi-rr-sun hidden dark:inline text-sm"></i>
                        <span class="text-sm font-medium dark:hidden">Dark</span>
                        <span class="text-sm font-medium hidden dark:inline">Light</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleLangDropdown() {
            document.getElementById('langDropdown').classList.toggle('hidden');
        }
        function closeLangDropdown() {
            document.getElementById('langDropdown').classList.add('hidden');
        }
        function toggleLangDropdownMobile() {
            document.getElementById('langDropdownMobile').classList.toggle('hidden');
        }
        function toggleMobileMoreMenu() {
            var menu = document.getElementById('mobileMoreMenu');
            menu.classList.toggle('hidden');
        }

        // Close dropdowns on outside click
        document.addEventListener('click', function(event) {
            // Desktop lang dropdown
            var toggle = document.getElementById('langToggle');
            var dropdown = document.getElementById('langDropdown');
            if (toggle && dropdown && !toggle.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
            // Mobile lang dropdown
            var toggleM = document.getElementById('langToggleMobile');
            var dropdownM = document.getElementById('langDropdownMobile');
            if (toggleM && dropdownM && !toggleM.contains(event.target) && !dropdownM.contains(event.target)) {
                dropdownM.classList.add('hidden');
            }
            // Mobile more menu
            var moreBtn = document.getElementById('mobileMoreBtn');
            var moreMenu = document.getElementById('mobileMoreMenu');
            if (moreBtn && moreMenu && !moreBtn.contains(event.target) && !moreMenu.contains(event.target)) {
                moreMenu.classList.add('hidden');
            }
        });

        // Search dropdown functions
        function openSearchDropdown() {
            document.getElementById('searchDropdown').classList.remove('hidden');
            document.getElementById('searchOverlay').classList.remove('hidden');
        }
        function closeSearchDropdown() {
            document.getElementById('searchDropdown').classList.add('hidden');
            document.getElementById('searchOverlay').classList.add('hidden');
        }
    </script>
    <style>
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slideDown {
            animation: slideDown 0.2s ease-out;
        }
    </style>
</header>
