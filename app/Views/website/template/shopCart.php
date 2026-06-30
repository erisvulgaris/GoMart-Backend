<!-- Shop Cart model -->
<div class="mini-shopping-cart mini-shopping-cart-md mini-shopping-cart-right duration-2000 transition-all md:w-1/2 lg:w-1/3 dark:bg-gray-900"
	tabindex="-1" id="mini-shopping-cartRight">
	<div class="flex justify-between py-2 px-4 border-b dark:border-gray-700">
		<div>
			<h5 class="text-lg font-semibold text-gray-800 dark:text-white cartsHeading"><?php echo lang('website.your_cart');?></h5>
		</div>
		<button type="button" class="btn-close text-reset dark:text-white" onclick="toggleShoppingCart()">
			<i class="fi fi-rr-x"></i>
		</button>
	</div>
	<div class="mini-shopping-cart-body p-2 overflow-y-auto dark:bg-gray-900">
		<div class="discountedPricesaving"></div>
		<ul class="list-none" id="mini-shop-cart-item-list"></ul>
		<ul class="list-none" id="mini-seller-list"></ul>
		<div class="text-center flex flex-col items-center justify-center h-full hidden" id="emptyCartDiv">
			<img src="https://grocery-ci.apksoftwaresolution.com/assets/dist/img/no-data.png" class="w-24 mx-auto" />
			<p class="mt-2 text-gray-600 dark:text-white text-sm"> <?php echo lang('website.no_item_in_Cart');?></p>
		</div>
	</div>

	<div class="mini-shopping-cart-footer dark:bg-gray-900"></div>
</div>
<!-- Shop Cart model -->

<!-- Location Modal — 2-step: Search → Map Adjust -->
<div id="locationModal" class="fixed inset-0 flex items-end md:items-center justify-center bg-black/50 backdrop-blur-sm hidden z-40 md:px-[20%]">
    <div class="bg-white dark:bg-gray-800 rounded-t-2xl md:rounded-2xl shadow-2xl w-full max-h-[90vh] overflow-hidden flex flex-col">

        <!-- Modal Header -->
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-green-100 dark:bg-green-900/40 flex items-center justify-center">
                    <i class="fi fi-rr-marker text-green-600 dark:text-green-400 text-base"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-800 dark:text-white" id="locationModalTitle"><?php echo lang('website.delivery_to');?></h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400"><?= $settings['business_name'] ?></p>
                </div>
            </div>
            <button onclick="closeLocationModal()" class="w-8 h-8 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center justify-center transition-colors">
                <i class="fi fi-rr-cross-small text-gray-500 dark:text-gray-400 text-lg"></i>
            </button>
        </div>

        <!-- Step 1: Search Location -->
        <div id="locationStep1" class="flex-1 overflow-y-auto">
            <div class="p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4"><?php echo lang('website.please_provide_your_delivery_location_to_see_products_at_nearby_store');?></p>

                <!-- Search Input -->
                <div class="relative mb-3">
                    <i class="fi fi-rr-search absolute left-3.5 top-3 text-gray-400 text-sm"></i>
                    <input type="search" placeholder="Search for area, street name..." id="citySearch"
                        class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all"
                        oninput="searchCity(this.value)" autocomplete="off">
                </div>

                <!-- Use My Location Button -->
                <button onclick="useMyLocation()" type="button"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border-2 border-dashed border-green-300 dark:border-green-700 bg-green-50/50 dark:bg-green-900/20 hover:bg-green-50 dark:hover:bg-green-900/30 transition-colors group">
                    <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/50 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fi fi-rr-navigation text-green-600 dark:text-green-400 text-sm"></i>
                    </div>
                    <div class="text-left">
                        <span class="text-sm font-semibold text-green-700 dark:text-green-400"><?php echo lang('website.use_my_location');?></span>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Using GPS</p>
                    </div>
                </button>
            </div>

            <!-- City not found message -->
            <div class="hidden px-5 pb-4" id="cityNotFoundMsg">
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4 text-center">
                    <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/40 mx-auto mb-3 flex items-center justify-center">
                        <i class="fi fi-rr-marker-time text-red-500 text-xl"></i>
                    </div>
                    <p class="text-sm text-red-700 dark:text-red-400 font-medium"><?php echo lang('website.we_are_not_available_at_this_location_at_the_moment');?></p>
                    <p class="text-xs text-red-500 dark:text-red-500 mt-1"><?php echo lang('website.please_select_a_different_location');?></p>
                </div>
            </div>

            <!-- Search Suggestions -->
            <div id="citySuggestions" class="hidden border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 max-h-60 overflow-y-auto"></div>
        </div>

        <!-- Step 2: Map Adjust (hidden initially) -->
        <div id="locationStep2" class="hidden flex flex-col" style="height: calc(90vh - 70px);">
            <!-- Back button -->
            <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                <button onclick="goBackToSearch()" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 transition-colors">
                    <i class="fi fi-rr-arrow-left text-xs"></i>
                    <span>Change location</span>
                </button>
            </div>

            <!-- Map Container -->
            <div class="relative" style="flex:1; min-height:250px;">
                <div id="locationMap" class="w-full h-full rounded-lg"></div>
                <!-- Center Pin (fixed in middle of map) -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-full pointer-events-none z-10">
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full bg-green-600 shadow-lg flex items-center justify-center border-2 border-white">
                            <i class="fi fi-rr-marker text-white text-xs"></i>
                        </div>
                        <div class="w-2 h-2 rounded-full bg-green-600/50 mt-0.5"></div>
                    </div>
                </div>
                <!-- Map loading overlay -->
                <div id="mapLoadingOverlay" class="absolute inset-0 bg-white/70 dark:bg-gray-800/70 flex items-center justify-center z-20 hidden">
                    <div class="animate-spin w-8 h-8 border-3 border-green-600 border-t-transparent rounded-full"></div>
                </div>
            </div>

            <!-- Selected Address & Confirm -->
            <div class="border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 p-4">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/40 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fi fi-rr-marker text-green-600 dark:text-green-400 text-sm"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p id="mapSelectedArea" class="text-sm font-semibold text-gray-800 dark:text-white truncate">Loading...</p>
                        <p id="mapSelectedAddress" class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-2">Fetching address...</p>
                    </div>
                </div>
                <button onclick="confirmMapLocation()" id="confirmLocationBtn"
                    class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-green-600/20 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                    Confirm Location
                </button>
            </div>
        </div>

    </div>
</div>