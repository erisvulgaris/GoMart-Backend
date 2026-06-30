<script>
    const productFilterModel = document.getElementById('productFilterModel');

    <?php if (isset($is_popular) && $is_popular) {
    ?>
        const productFilter = {
            category: [],
            brand: [],
            seller: [],
            minPrice: 0,
            maxPrice: 0,
            fromPrice: 0,
            toPrice: 0,
            sort: 6
        };
        localStorage.setItem('productFilter', JSON.stringify(productFilter));
    <?php
    } ?>
    <?php if (isset($is_dealoftheday) && $is_dealoftheday) {
    ?>
        const productFilter = {
            category: [],
            brand: [],
            seller: [],
            minPrice: 0,
            maxPrice: 0,
            fromPrice: 0,
            toPrice: 0,
            sort: 7
        };
        localStorage.setItem('productFilter', JSON.stringify(productFilter));
    <?php
    } ?>

    <?php if (isset($brand_slug) && $is_brand) {
    ?>
        const productFilter = {
            category: [],
            brand: ['<?= $brand_slug ?>'],
            seller: [],
            minPrice: 0,
            maxPrice: 0,
            fromPrice: 0,
            toPrice: 0,
            sort: 1
        };
        localStorage.setItem('productFilter', JSON.stringify(productFilter));
    <?php
    } ?>

    <?php if (isset($seller_slug) && $is_seller) {
    ?>
        const productFilter = {
            category: [],
            brand: [],
            seller: ['<?= $seller_slug ?>'],
            minPrice: 0,
            maxPrice: 0,
            fromPrice: 0,
            toPrice: 0,
            sort: 1
        };
        localStorage.setItem('productFilter', JSON.stringify(productFilter));
    <?php
    } ?>


    if (!localStorage.getItem('productFilter')) {
        localStorage.setItem('productFilter', JSON.stringify(productFilter));
    }

    // Function to set the active view
    function setActiveView(view) {
        // Hide all views
        document.getElementById('productListView').classList.add('hidden');
        document.getElementById('productAppView').classList.add('hidden');
        document.getElementById('productGridView').classList.add('hidden');

        // Reset button classes to inactive
        document.getElementById('listViewButton').classList.replace('text-green-600', 'text-gray-600');
        document.getElementById('appViewButton').classList.replace('text-green-600', 'text-gray-600');
        document.getElementById('gridViewButton').classList.replace('text-green-600', 'text-gray-600');

        // Show the selected view and set button as active
        if (view === 'list') {
            document.getElementById('productListView').classList.remove('hidden');
            document.getElementById('listViewButton').classList.replace('text-gray-600', 'text-green-600');
        } else if (view === 'app') {
            document.getElementById('productAppView').classList.remove('hidden');
            document.getElementById('appViewButton').classList.replace('text-gray-600', 'text-green-600');
        } else if (view === 'grid') {
            document.getElementById('productGridView').classList.remove('hidden');
            document.getElementById('gridViewButton').classList.replace('text-gray-600', 'text-green-600');
        }

        // Save the selected view to localStorage
        localStorage.setItem('productView', view);
    }

    const productView = localStorage.getItem('productView') || 'grid';
    setActiveView(productView);

    function setProductListView() {
        setActiveView('list');
    }

    function setProductAppView() {
        setActiveView('app');
    }

    function setProductGridView() {
        setActiveView('grid');
    }

    function initializeFilters() {
        const storedFilter = JSON.parse(localStorage.getItem('productFilter'));

        if (storedFilter) {
            for (const filterType in storedFilter) {
                if (Array.isArray(storedFilter[filterType])) {
                    storedFilter[filterType].forEach(slug => {
                        const checkboxes = document.querySelectorAll(`input.${filterType}_${slug}`);
                        checkboxes.forEach(checkbox => {
                            checkbox.checked = true;
                        });
                    });
                }
            }

            const productSortElement = document.getElementById('productSort');
            const storedSortValue = storedFilter.sort;

            const options = productSortElement.options;
            for (let i = 0; i < options.length; i++) {
                if (options[i].value == storedSortValue) {
                    options[i].selected = true;
                    break;
                }
            }
        }
    }
    initializeFilters();

    function applyFilter(u) {
        let updatedFilter = JSON.parse(localStorage.getItem('productFilter'));

        const updateArray = (array, selectedSlugs) => {
            const filteredArray = array.filter(slug => selectedSlugs.includes(slug));
            const newArray = filteredArray.concat(selectedSlugs.filter(slug => !filteredArray.includes(slug)));
            return newArray;
        };

        if (u === 'category') {
            const selectedCategories = Array.from(document.querySelectorAll("input[class*='category_']"))
                .filter(input => input.checked)
                .map(input => input.className.split("_")[1]);

            updatedFilter.category = updateArray(updatedFilter.category, selectedCategories);
            console.log(updatedFilter.category);
        }

        if (u === 'brand') {
            const selectedBrands = Array.from(document.querySelectorAll("input[class*='brand_']"))
                .filter(input => input.checked)
                .map(input => input.className.split("_")[1]);

            updatedFilter.brand = updateArray(updatedFilter.brand, selectedBrands);
        }

        if (u === 'seller') {
            const selectedSellers = Array.from(document.querySelectorAll("input[class*='seller_']"))
                .filter(input => input.checked)
                .map(input => input.className.split("_")[1]);

            updatedFilter.seller = updateArray(updatedFilter.seller, selectedSellers);
        }

        if (u === 'sort') {
            const productSort = document.getElementById('productSort').value;
            updatedFilter.sort = +productSort;
        }

        localStorage.setItem('productFilter', JSON.stringify(updatedFilter));

        fetchProductList();
    }

    async function fetchProductList() {
        const productSort = document.getElementById('productSort').value;

        let updatedFilter = JSON.parse(localStorage.getItem('productFilter'));

        try {
            const response = await fetch('/fetchProductList', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    productSort,
                    categorys: updatedFilter.category,
                    brands: updatedFilter.brand,
                    sellers: updatedFilter.seller,
                    fromPrice: updatedFilter.fromPrice,
                    toPrice: updatedFilter.toPrice,
                }),
            });
            const result = await response.json();

            if (result.status === 'success') {
                const products = result.products;
                const productListView = document.getElementById('productListView');
                const productAppView = document.getElementById('productAppView');
                const productGridView = document.getElementById('productGridView');
                productListView.innerHTML = '';
                productAppView.innerHTML = '';
                productGridView.innerHTML = '';

                updatedFilter.minPrice = +result.minPrice
                updatedFilter.maxPrice = +result.maxPrice
                updatedFilter.fromPrice = +result.fromPrice
                updatedFilter.toPrice = +result.toPrice
                localStorage.setItem('productFilter', JSON.stringify(updatedFilter));

                initializeRangeSlider('price-range-slider', JSON.parse(localStorage.getItem('productFilter')).minPrice, JSON.parse(localStorage.getItem('productFilter')).maxPrice, JSON.parse(localStorage.getItem('productFilter')).fromPrice, JSON.parse(localStorage.getItem('productFilter')).toPrice);

                document.getElementById('product_count').innerText = result.products.length;

                if (!result.products.length) {
                    document.getElementById('noProductAvilable').classList.remove('hidden');
                    document.getElementById('noProductAvilable').classList.add('block');
                } else {
                    document.getElementById('noProductAvilable').classList.remove('block');
                    document.getElementById('noProductAvilable').classList.add('hidden');
                }

                const currency_symbol = result.currency_symbol;
                const currency_symbol_position = result.currency_symbol_position;
                const deliveryTime = (() => { try { const t = document.getElementById('proxyDeliveryTime')?.textContent?.trim(); if (t) return t; const loc = JSON.parse(localStorage.getItem('location') || '{}'); return loc.delivery_time || ''; } catch(e) { return ''; } })();

                const formatPrice = (price) => {
                    return currency_symbol_position === 'left' ?
                        `${currency_symbol}${price}` :
                        `${price}${currency_symbol}`;
                };

                const buildRatingHtml = (product) => {
                    const rating = parseFloat(product.avg_rating) || 0;
                    const count = parseInt(product.rating_count) || 0;
                    const full = Math.floor(rating), half = (rating - full) >= 0.5 ? 1 : 0, empty = 5 - full - half;
                    let stars = '';
                    for (let i = 0; i < full; i++) stars += '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#f59e0b" class="w-3 h-3"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
                    if (half) stars += `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-3 h-3"><defs><linearGradient id="hs-${product.id}"><stop offset="50%" stop-color="#f59e0b"/><stop offset="50%" stop-color="transparent"/></linearGradient></defs><path fill="url(#hs-${product.id})" stroke="#f59e0b" stroke-width="1.5" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>`;
                    for (let i = 0; i < empty; i++) stars += '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="1.5" class="w-3 h-3 dark:stroke-gray-500"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
                    return `<div class="flex items-center gap-1.5"><div class="flex items-center gap-px">${stars}</div><span class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 leading-none">${rating.toFixed(1)} (${count})</span></div>`;
                };

                const deliveryBadge = `<span class="inline-flex items-center gap-1 bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 text-[10px] font-bold px-1.5 py-0.5 rounded-md leading-none border border-orange-100 dark:border-orange-800"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 24" class="h-3.5" style="width:auto;" fill="currentColor"><circle cx="9" cy="18" r="3.8" fill="none" stroke="currentColor" stroke-width="2.2"/><circle cx="9" cy="18" r="1.2"/><circle cx="29" cy="18" r="3.8" fill="none" stroke="currentColor" stroke-width="2.2"/><circle cx="29" cy="18" r="1.2"/><path d="M9,15.5 L11,10 L17,8.5 L25,9 L29,12 L29,15.5 Q19,17 9,15.5 Z"/><line x1="23" y1="8" x2="17" y2="3.5" stroke="currentColor" stroke-width="3" stroke-linecap="round" fill="none"/><circle cx="15.5" cy="2.5" r="2.6"/><line x1="17" y1="4" x2="12.5" y2="8" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/><line x1="35" y1="9.5" x2="40" y2="9.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none"/><line x1="36" y1="12.5" x2="44" y2="12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none"/><line x1="34.5" y1="15.5" x2="42" y2="15.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none"/></svg><span class="productDeliveryTimeVal">${deliveryTime}</span>m</span>`;

                // ── List View ──
                products.forEach(product => {
                    const firstVariant = product.variants[0];
                    const discountPercentage = firstVariant.discounted_price > 0 ?
                        Math.round(((firstVariant.price - firstVariant.discounted_price) / firstVariant.price) * 100) : 0;
                    const isOutOfStock = firstVariant.is_unlimited_stock == 0 && firstVariant.stock == 0;
                    const activePrice = firstVariant.discounted_price > 0 ? firstVariant.discounted_price : firstVariant.price;
                    const ratingHtml = buildRatingHtml(product);

                    let listCartBtn = '';
                    if (isOutOfStock) {
                        listCartBtn = `<span class="inline-block text-[10px] font-semibold text-red-400 dark:text-red-500 bg-white dark:bg-gray-800 border border-red-200 dark:border-red-700 rounded-full px-2.5 py-1 shadow-md">Unavailable</span>`;
                    } else if (product.cart_quantity > 0) {
                        listCartBtn = `
                        <div class="flex items-center rounded-xl border-2 border-green-500 dark:border-green-600 overflow-hidden bg-white dark:bg-gray-800 shadow-lg">
                            <button type="button" onclick="removeFromCart(${product.id}, ${firstVariant.id})" class="w-7 h-7 flex items-center justify-center bg-green-700 text-white hover:bg-green-600 transition-colors">
                                <i class="fi fi-rr-minus-small text-base leading-none"></i>
                            </button>
                            <span class="w-6 text-center text-[13px] font-bold text-green-700 dark:text-green-300 ${product.slug}-qty-${firstVariant.id}">${product.cart_quantity}</span>
                            <button type="button" onclick="addToCart(${product.id}, ${firstVariant.id})" class="w-7 h-7 flex items-center justify-center bg-green-700 text-white hover:bg-green-600 transition-colors">
                                <i class="fi fi-rr-plus-small text-base leading-none"></i>
                            </button>
                        </div>`;
                    } else {
                        listCartBtn = `
                        <button type="button" onclick="openProductVariantPopup(${product.id}, '${product.slug}')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white dark:bg-gray-800 border-2 border-green-600 dark:border-green-500 text-green-600 dark:text-green-400 shadow-lg hover:bg-green-600 hover:text-white hover:border-green-600 active:scale-90 transition-all duration-150 ${product.slug}-${firstVariant.id}">
                            <i class="fi fi-rr-plus text-sm leading-none"></i>
                        </button>`;
                    }

                    const listCard = `
                        <div class="group rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden" id="${product.slug}">
                            <div class="flex">
                                <div class="relative flex-shrink-0 w-36 md:w-44" style="background:linear-gradient(135deg,#f8faf8 0%,#eef4ee 100%);">
                                    ${isOutOfStock ? `
                                        <div class="absolute inset-0 z-20 flex items-center justify-center bg-white/50 dark:bg-gray-900/50 backdrop-blur-[2px]">
                                            <span class="text-[10px] font-bold tracking-wide uppercase text-red-500 dark:text-red-400 bg-white dark:bg-gray-800 border border-red-200 dark:border-red-700 px-2.5 py-1 rounded-full shadow">Out of Stock</span>
                                        </div>
                                    ` : ''}
                                    ${discountPercentage > 0 ? `
                                        <div class="absolute top-0 left-0 z-10" style="width:54px;height:54px;overflow:hidden;">
                                            <div style="position:absolute;top:8px;left:-18px;width:72px;transform:rotate(-45deg);background:linear-gradient(90deg,#ef4444,#f97316);color:#fff;font-size:9px;font-weight:800;text-align:center;padding:3px 0;letter-spacing:0.04em;box-shadow:0 2px 6px rgba(239,68,68,.35);">
                                                ${discountPercentage}% OFF
                                            </div>
                                        </div>
                                    ` : ''}
                                    <a href="/product/${product.slug}" class="block w-full h-full">
                                        <img src="${result.base_url+product.main_img}" alt="${product.product_name}" class="w-full h-full object-contain p-3 dark:brightness-90">
                                    </a>
                                </div>
                                <div class="flex flex-col justify-center flex-1 px-4 py-3 gap-1">
                                    <h3 class="text-[12.5px] font-semibold leading-snug text-gray-800 dark:text-gray-100 line-clamp-2">${product.product_name}</h3>
                                    ${ratingHtml}
                                    <span class="text-[11px] text-gray-400 dark:text-gray-500">${firstVariant.title}</span>
                                    <span class="productDeliveryTime mt-1 whitespace-nowrap${deliveryTime ? '' : ' hidden'}">${deliveryBadge}</span>
                                    <div class="flex justify-between items-center mt-2">
                                        <div class="flex items-center gap-1.5 flex-wrap min-w-0">
                                            <span class="text-[13px] font-extrabold text-gray-900 dark:text-white truncate">${formatPrice(activePrice)}</span>
                                            ${firstVariant.discounted_price > 0 ? `<span class="text-[10px] text-gray-400 dark:text-gray-500 line-through leading-tight truncate">${formatPrice(firstVariant.price)}</span>` : ''}
                                        </div>
                                        <div class="${product.slug}-mainbtndiv-${firstVariant.id}">
                                            ${listCartBtn}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;

                    productListView.insertAdjacentHTML('beforeend', listCard);
                });

                // ── App View ──
                products.forEach(product => {
                    const firstVariant = product.variants[0];
                    const discountPercentage = firstVariant.discounted_price > 0 ?
                        Math.round(((firstVariant.price - firstVariant.discounted_price) / firstVariant.price) * 100) : 0;
                    const isOutOfStock = firstVariant.is_unlimited_stock == 0 && firstVariant.stock == 0;
                    const activePrice = firstVariant.discounted_price > 0 ? firstVariant.discounted_price : firstVariant.price;
                    const ratingHtml = buildRatingHtml(product);

                    let cartBtnHtml = '';
                    if (isOutOfStock) {
                        cartBtnHtml = `<span class="inline-block text-[10px] font-semibold text-red-400 dark:text-red-500 bg-white dark:bg-gray-800 border border-red-200 dark:border-red-700 rounded-full px-2.5 py-1 shadow-md">Unavailable</span>`;
                    } else if (product.cart_quantity > 0) {
                        cartBtnHtml = `
                        <div class="flex items-center rounded-xl border-2 border-green-500 dark:border-green-600 overflow-hidden bg-white dark:bg-gray-800 shadow-lg">
                            <button type="button" onclick="removeFromCart(${product.id}, ${firstVariant.id})" class="w-7 h-7 flex items-center justify-center bg-green-700 text-white hover:bg-green-600 transition-colors">
                                <i class="fi fi-rr-minus-small text-base leading-none"></i>
                            </button>
                            <span class="w-6 text-center text-[13px] font-bold text-green-700 dark:text-green-300 ${product.slug}-qty-${firstVariant.id}">${product.cart_quantity}</span>
                            <button type="button" onclick="addToCart(${product.id}, ${firstVariant.id})" class="w-7 h-7 flex items-center justify-center bg-green-700 text-white hover:bg-green-600 transition-colors">
                                <i class="fi fi-rr-plus-small text-base leading-none"></i>
                            </button>
                        </div>`;
                    } else {
                        cartBtnHtml = `
                        <button type="button" onclick="openProductVariantPopup(${product.id}, '${product.slug}')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white dark:bg-gray-800 border-2 border-green-600 dark:border-green-500 text-green-600 dark:text-green-400 shadow-lg hover:bg-green-600 hover:text-white hover:border-green-600 active:scale-90 transition-all duration-150 ${product.slug}-${firstVariant.id}">
                            <i class="fi fi-rr-plus text-sm leading-none"></i>
                        </button>`;
                    }

                    const productCard = `
                        <div class="group" id="${product.slug}-app-${firstVariant.id}">
                            <div class="flex flex-col rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 overflow-hidden" style="height:100%;">
                                <div class="relative w-full flex-shrink-0">
                                    <a href="/product/${product.slug}" class="relative block w-full overflow-hidden" style="aspect-ratio:1/1; background:linear-gradient(135deg,#f8faf8 0%,#eef4ee 100%);">
                                        ${isOutOfStock ? `
                                            <div class="absolute inset-0 z-20 flex items-center justify-center bg-white/50 dark:bg-gray-900/50 backdrop-blur-[2px]">
                                                <span class="text-[10px] font-bold tracking-wide uppercase text-red-500 dark:text-red-400 bg-white dark:bg-gray-800 border border-red-200 dark:border-red-700 px-2.5 py-1 rounded-full shadow">Out of Stock</span>
                                            </div>
                                        ` : ''}
                                        ${discountPercentage > 0 ? `
                                            <div class="absolute top-0 left-0 z-10" style="width:54px;height:54px;overflow:hidden;">
                                                <div style="position:absolute;top:8px;left:-18px;width:72px;transform:rotate(-45deg);background:linear-gradient(90deg,#ef4444,#f97316);color:#fff;font-size:9px;font-weight:800;text-align:center;padding:3px 0;letter-spacing:0.04em;box-shadow:0 2px 6px rgba(239,68,68,.35);">
                                                    ${discountPercentage}% OFF
                                                </div>
                                            </div>
                                        ` : ''}
                                        <img src="${result.base_url+product.main_img}" alt="${product.product_name}" class="absolute inset-0 w-full h-full object-contain p-4 group-hover:scale-[1.07] transition-transform duration-500 ease-out dark:brightness-90" />
                                    </a>
                                    <div class="absolute -bottom-4 right-2 z-30 ${product.slug}-mainbtndiv-${firstVariant.id}">
                                        ${cartBtnHtml}
                                    </div>
                                </div>
                                <div class="flex flex-col flex-1 px-3 pt-5 pb-3 gap-1">
                                    <h3 class="text-[12.5px] font-semibold leading-snug text-gray-800 dark:text-gray-100 line-clamp-2" style="min-height:2.6em;">${product.product_name}</h3>
                                    ${ratingHtml}
                                    <div class="flex items-center justify-between gap-1">
                                        <span class="text-[11px] text-gray-400 dark:text-gray-500 truncate leading-none">${firstVariant.title}</span>
                                        <span class="productDeliveryTime whitespace-nowrap${deliveryTime ? '' : ' hidden'}">${deliveryBadge}</span>
                                    </div>
                                    <div class="flex-1"></div>
                                    <div class="flex items-center gap-1.5 flex-wrap min-w-0">
                                        <span class="text-[13px] font-extrabold text-gray-900 dark:text-white truncate">${formatPrice(activePrice)}</span>
                                        ${firstVariant.discounted_price > 0 ? `<span class="text-[10px] text-gray-400 dark:text-gray-500 line-through leading-tight truncate">${formatPrice(firstVariant.price)}</span>` : ''}
                                    </div>
                                </div>
                            </div>
                        </div>`;

                    productAppView.insertAdjacentHTML('beforeend', productCard);
                });

                // ── Grid View ──
                products.forEach(product => {
                    const firstVariant = product.variants[0];
                    const discountPercentage = firstVariant.discounted_price > 0 ?
                        Math.round(((firstVariant.price - firstVariant.discounted_price) / firstVariant.price) * 100) : 0;
                    const isOutOfStock = firstVariant.is_unlimited_stock == 0 && firstVariant.stock == 0;
                    const activePrice = firstVariant.discounted_price > 0 ? firstVariant.discounted_price : firstVariant.price;
                    const ratingHtml = buildRatingHtml(product);

                    let gridCartBtn = '';
                    if (isOutOfStock) {
                        gridCartBtn = `<span class="inline-block text-[10px] font-semibold text-red-400 dark:text-red-500 bg-white dark:bg-gray-800 border border-red-200 dark:border-red-700 rounded-full px-2.5 py-1 shadow-md">Unavailable</span>`;
                    } else if (product.cart_quantity > 0) {
                        gridCartBtn = `
                        <div class="flex items-center rounded-xl border-2 border-green-500 dark:border-green-600 overflow-hidden bg-white dark:bg-gray-800 shadow-lg">
                            <button type="button" onclick="removeFromCart(${product.id}, ${firstVariant.id})" class="w-7 h-7 flex items-center justify-center bg-green-700 text-white hover:bg-green-600 transition-colors">
                                <i class="fi fi-rr-minus-small text-base leading-none"></i>
                            </button>
                            <span class="w-6 text-center text-[13px] font-bold text-green-700 dark:text-green-300 ${product.slug}-qty-${firstVariant.id}">${product.cart_quantity}</span>
                            <button type="button" onclick="addToCart(${product.id}, ${firstVariant.id})" class="w-7 h-7 flex items-center justify-center bg-green-700 text-white hover:bg-green-600 transition-colors">
                                <i class="fi fi-rr-plus-small text-base leading-none"></i>
                            </button>
                        </div>`;
                    } else {
                        gridCartBtn = `
                        <button type="button" onclick="openProductVariantPopup(${product.id}, '${product.slug}')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white dark:bg-gray-800 border-2 border-green-600 dark:border-green-500 text-green-600 dark:text-green-400 shadow-lg hover:bg-green-600 hover:text-white hover:border-green-600 active:scale-90 transition-all duration-150 ${product.slug}-${firstVariant.id}">
                            <i class="fi fi-rr-plus text-sm leading-none"></i>
                        </button>`;
                    }

                    const productCard = `
                        <div class="group" id="${product.slug}-grid-${firstVariant.id}">
                            <div class="flex flex-col rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 overflow-hidden" style="height:100%;">
                                <div class="relative w-full flex-shrink-0">
                                    <a href="/product/${product.slug}" class="relative block w-full overflow-hidden" style="aspect-ratio:1/1; background:linear-gradient(135deg,#f8faf8 0%,#eef4ee 100%);">
                                        ${isOutOfStock ? `
                                            <div class="absolute inset-0 z-20 flex items-center justify-center bg-white/50 dark:bg-gray-900/50 backdrop-blur-[2px]">
                                                <span class="text-[10px] font-bold tracking-wide uppercase text-red-500 dark:text-red-400 bg-white dark:bg-gray-800 border border-red-200 dark:border-red-700 px-2.5 py-1 rounded-full shadow">Out of Stock</span>
                                            </div>
                                        ` : ''}
                                        ${discountPercentage > 0 ? `
                                            <div class="absolute top-0 left-0 z-10" style="width:54px;height:54px;overflow:hidden;">
                                                <div style="position:absolute;top:8px;left:-18px;width:72px;transform:rotate(-45deg);background:linear-gradient(90deg,#ef4444,#f97316);color:#fff;font-size:9px;font-weight:800;text-align:center;padding:3px 0;letter-spacing:0.04em;box-shadow:0 2px 6px rgba(239,68,68,.35);">
                                                    ${discountPercentage}% OFF
                                                </div>
                                            </div>
                                        ` : ''}
                                        <img src="${result.base_url+product.main_img}" alt="${product.product_name}" class="absolute inset-0 w-full h-full object-contain p-4 group-hover:scale-[1.07] transition-transform duration-500 ease-out dark:brightness-90" />
                                    </a>
                                    <div class="absolute -bottom-4 right-2 z-30 ${product.slug}-mainbtndiv-${firstVariant.id}">
                                        ${gridCartBtn}
                                    </div>
                                </div>
                                <div class="flex flex-col flex-1 px-3 pt-5 pb-3 gap-1">
                                    <h3 class="text-[12.5px] font-semibold leading-snug text-gray-800 dark:text-gray-100 line-clamp-2" style="min-height:2.6em;">${product.product_name}</h3>
                                    ${ratingHtml}
                                    <div class="flex items-center justify-between gap-1">
                                        <span class="text-[11px] text-gray-400 dark:text-gray-500 truncate leading-none">${firstVariant.title}</span>
                                        <span class="productDeliveryTime whitespace-nowrap${deliveryTime ? '' : ' hidden'}">${deliveryBadge}</span>
                                    </div>
                                    <div class="flex-1"></div>
                                    <div class="flex items-center gap-1.5 flex-wrap min-w-0">
                                        <span class="text-[13px] font-extrabold text-gray-900 dark:text-white truncate">${formatPrice(activePrice)}</span>
                                        ${firstVariant.discounted_price > 0 ? `<span class="text-[10px] text-gray-400 dark:text-gray-500 line-through leading-tight truncate">${formatPrice(firstVariant.price)}</span>` : ''}
                                    </div>
                                </div>
                            </div>
                        </div>`;

                    productGridView.insertAdjacentHTML('beforeend', productCard);
                });

            } else {
                console.error('Failed to load products');
            }
        } catch (error) {
            console.error('Error fetching products:', error);
        }
    }
    fetchProductList();

    function initializeRangeSlider(sliderId, min, max, from, to) {
        const slider = document.getElementById(sliderId);
        const track = slider.querySelector('#slider-track');
        const range = slider.querySelector('#slider-range');
        const handleFrom = slider.querySelector('#slider-handle-from');
        const handleTo = slider.querySelector('#slider-handle-to');
        const valueFrom = slider.querySelector('#slider-value-from');
        const valueTo = slider.querySelector('#slider-value-to');
        const inputFrom = slider.querySelector('#slider-from');
        const inputTo = slider.querySelector('#slider-to');

        let draggingFrom = false;
        let draggingTo = false;

        let currentFrom = from;
        let currentTo = to;

        const updateSlider = () => {
            console.log(min, max, from, to)
            const fromPercent = ((currentFrom - min) / (max - min)) * 100;
            const toPercent = ((currentTo - min) / (max - min)) * 100;

            handleFrom.style.left = `${fromPercent}%`;
            handleTo.style.left = `${toPercent}%`;
            range.style.left = `${Math.min(fromPercent, toPercent)}%`;
            range.style.width = `${Math.abs(toPercent - fromPercent)}%`;

            valueFrom.textContent = Math.min(currentFrom, currentTo);
            valueTo.textContent = Math.max(currentFrom, currentTo);

            inputFrom.value = Math.min(currentFrom, currentTo);
            inputTo.value = Math.max(currentFrom, currentTo);

            let updatedFilter = JSON.parse(localStorage.getItem('productFilter'));

            updatedFilter.minPrice = +min
            updatedFilter.maxPrice = +max
            updatedFilter.fromPrice = +currentFrom
            updatedFilter.toPrice = +currentTo
            localStorage.setItem('productFilter', JSON.stringify(updatedFilter));
        };

        const handleDrag = (event, isFromHandle) => {
            const rect = track.getBoundingClientRect();
            const clientX = event.type.includes('touch') ? event.touches[0].clientX : event.clientX;
            let position = Math.round(((clientX - rect.left) / rect.width) * (max - min) + min);

            position = Math.max(min, Math.min(max, position));

            if (isFromHandle) {
                currentFrom = position;
            } else {
                currentTo = position;
            }

            updateSlider();
        };

        const startDrag = (event, isFromHandle) => {
            event.preventDefault();
            if (isFromHandle) {
                draggingFrom = true;
            } else {
                draggingTo = true;
            }
        };

        const stopDrag = () => {
            draggingFrom = false;
            draggingTo = false;
        };

        document.addEventListener('mousemove', (event) => {
            if (draggingFrom) handleDrag(event, true);
            if (draggingTo) handleDrag(event, false);
        });

        document.addEventListener('touchmove', (event) => {
            if (draggingFrom) handleDrag(event, true);
            if (draggingTo) handleDrag(event, false);
        });

        document.addEventListener('mouseup', stopDrag);
        document.addEventListener('touchend', stopDrag);

        handleFrom.addEventListener('mousedown', (event) => startDrag(event, true));
        handleFrom.addEventListener('touchstart', (event) => startDrag(event, true));

        handleTo.addEventListener('mousedown', (event) => startDrag(event, false));
        handleTo.addEventListener('touchstart', (event) => startDrag(event, false));

        updateSlider();
    }

    function closeProductFilterPopup() {
        productFilterModel.classList.add('hidden');
        document.body.classList.remove('modal-open');
    }

    function openProductFilterPopup() {
        productFilterModel.classList.remove('hidden');
        document.body.classList.add('modal-open');
    }
</script>