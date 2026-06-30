<div id="pageLoader" class="fixed inset-0 w-full h-full bg-white/95 dark:bg-gray-900/95 z-[9999] flex justify-center items-center font-sans">
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
                <div class="fadeInDrop w-7 h-7 rounded-full bg-red-500 relative" style="animation-delay: 0.2s">
                    <div class="absolute -top-1 left-2 w-1 h-3 bg-green-500 rounded-sm -rotate-6"></div>
                </div>
                <div class="fadeInDrop w-7 h-7 rounded-full bg-yellow-400 relative" style="animation-delay: 0.4s">
                    <div class="absolute top-3 -left-1 w-1 h-4 bg-amber-800 rounded-sm rotate-12"></div>
                </div>
                <div class="fadeInDrop w-7 h-7 rounded-full bg-orange-500 relative" style="animation-delay: 0.6s">
                    <div class="absolute -top-2 left-3 w-1 h-4 bg-lime-400 rounded-sm rotate-3"></div>
                </div>
            </div>
        </div>

        <p class="text-green-600 text-xl font-semibold mb-4"><?= $settings['website_loading_text'] ?></p>

        <div class="w-full h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
            <div class="progress-bar h-full bg-gradient-to-r from-green-600 to-lime-500 rounded-full w-0"></div>
        </div>
    </div>
</div>

<header class="bg-white dark:bg-gray-800">
    <div class="border-b dark:border-gray-700">
        <div class="p-3">
            <div class="container md:mx-auto max-w-[85rem]">
                <div class="flex items-center w-full space-x-4 <?= flex_direction() ?>">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <a href="/">
                            <img src="<?= base_url($settings['logo']) ?>" class="rounded-lg <?php if (isset($settings['logo_aspect_ratio']) && $settings['logo_aspect_ratio'] == '1:1') {
                                echo "w-8";
                            } else {
                                echo "w-28 h-10 object-contain";
                            } ?> drop-shadow" alt="<?= $settings['business_name'] ?>" />
                        </a>
                    </div>

                    <!-- Location Bar -->
                    <div class="hidden md:flex items-center">
                        <div onclick="openLocationModel()" class="cursor-pointer">
                            <div class="text-sm font-semibold text-gray-800 dark:text-white"><?php echo lang('website.delivery_to'); ?></div>
                            <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                                <span id="locationBarSubtitle"><?php echo lang('website.choose_location'); ?></span>
                                <i class="fi fi-tr-caret-down ml-1"></i>
                            </div>
                        </div>
                    </div>

                    <div class="flex w-full lg:items-center lg:justify-center space-x-4 <?= flex_direction() ?>">
                        <!-- Search Bar with Dropdown -->
                        <div class="w-full relative">
                            <div class="relative">
                                <button class="absolute left-3 top-2" type="button">
                                    <i class="fi fi-tr-issue-loupe text-lg dark:text-gray-300"></i>
                                </button>
                                <input type="text" oninput="handleSearchInput(this)" onkeyup="searchProducts(this.value)"
                                    onkeydown="if(event.key==='Enter'){navigateToSearch(this.value)}"
                                    onfocus="openSearchHeaderDropdown()"
                                    placeholder="<?php echo lang('website.search_for_products'); ?>"
                                    autocomplete="off"
                                    class="w-full pl-10 p-2 border dark:border-gray-600 bg-gray-100 dark:bg-gray-700 rounded-lg text-gray-900 dark:text-gray-200 dark:placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:outline-none">
                            </div>

                            <!-- Search Dropdown for search header -->
                            <div id="searchHeaderDropdown" class="hidden absolute top-full left-0 right-0 mt-2 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 z-50 max-h-[70vh] overflow-y-auto"
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

                        <!-- Cart Button -->
                        <button type="button" class="text-gray-600 dark:text-gray-300 relative" onclick="toggleShoppingCart()">
                            <i class="fi fi-tr-cart-shopping-fast text-2xl"></i>
                            <span id="cartCount"
                                class="absolute top-0 -mt-1 left-full rounded-full h-4 w-4 -ml-3 bg-green-600 text-white text-center font-semibold text-xs">
                                <?= $cartItemCount ?>
                            </span>
                        </button>

                        <!-- Profile Button -->
                        <?php if ((session()->has('email') && session()->get('is_email_verified') == 1) || (session()->has('mobile') && session()->get('is_mobile_verified') == 1)) { ?>
                            <div class="relative hidden md:block">
                                <a href="#!" class="flex dropdown-toggle text-reset items-center" id="dropdownUserLink">
                                    <img class="h-6 w-6 rounded-full ring-2 ring-white dark:ring-gray-600" src="<?php
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
                                    <li><a href="/order-history" class="dropdown-item block px-2 py-1 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 whitespace-nowrap"><i class="fi fi-rr-order-history"></i> <?php echo lang('website.order'); ?></a></li>
                                    <li><a href="/profile" class="dropdown-item block px-2 py-1 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 whitespace-nowrap"><i class="fi fi-rr-circle-user"></i> <?php echo lang('website.account'); ?></a></li>
                                    <li><a href="/address" class="dropdown-item block px-2 py-1 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 whitespace-nowrap"><i class="fi fi-rr-marker"></i> <?php echo lang('website.address'); ?></a></li>
                                    <li><a href="/wallet" class="dropdown-item block px-2 py-1 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 whitespace-nowrap"><i class="fi fi-rr-wallet"></i> <?php echo lang('website.wallet'); ?></a></li>
                                    <li><a href="/logout" class="dropdown-item block px-2 py-1 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 whitespace-nowrap"><i class="fi fi-rr-sign-out-alt"></i> <?php echo lang('website.logout'); ?></a></li>
                                </ul>
                            </div>
                        <?php } else { ?>
                            <a href="/login" class="text-gray-600 dark:text-gray-300 hidden md:block">
                                <i class="fi fi-tr-circle-user text-2xl"></i>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Search Header Dropdown Overlay -->
<div id="searchHeaderOverlay" class="hidden fixed inset-0 bg-black/30 z-40" onclick="closeSearchHeaderDropdown()"></div>

<script>
    function openSearchHeaderDropdown() {
        document.getElementById('searchHeaderDropdown').classList.remove('hidden');
        document.getElementById('searchHeaderOverlay').classList.remove('hidden');
    }

    function closeSearchHeaderDropdown() {
        document.getElementById('searchHeaderDropdown').classList.add('hidden');
        document.getElementById('searchHeaderOverlay').classList.add('hidden');
    }
</script>

<style>
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
