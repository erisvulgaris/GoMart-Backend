<?php if (!empty($headerBanner)): ?>
    <section class="mt-2 md:mt-4 md:container md:mx-auto px-3">
        <div class="rounded-2xl overflow-hidden shadow-sm">
            <div class="swiper-container swiper" data-speed="400" data-space-between="100"
                data-pagination="true" data-navigation="false" data-autoplay="true" data-autoplay-delay="3000"
                data-effect="fade"
                data-breakpoints='{"480": {"slidesPerView": 1}, "768": {"slidesPerView": 1}, "1024": {"slidesPerView": 1}}'>
                <div class="swiper-wrapper">
                    <?php foreach ($headerBanner as $banner): ?>
                        <a href="<?= !empty($banner['firstSubcategory']) ? 'subcategory/' . $banner['firstSubcategory']['slug'] : '#' ?>" class="swiper-slide">
                            <img src="<?= esc($banner['image']) ?>" class="w-full dark:brightness-90" />
                        </a>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php if (!empty($dbSections)): ?>
    <?php foreach ($dbSections as $dbSection): ?>

        <!-- Category List Section -->
        <?php if ($dbSection['section_style'] === 'category_list' && !empty($dbSection['content'])): ?>
            <section class="mt-2 md:mt-4 md:container md:mx-auto px-3">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                    
                    <div class="flex items-center justify-between px-5 py-4 border-b border-dashed border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-2">
                            <i class="fi fi-rr-apps text-green-500 leading-none"></i>
                            <h2 class="text-lg font-medium dark:text-white"><?= esc($dbSection['title']) ?></h2>
                        </div>
                        <a href="/category" class="text-xs font-medium text-green-600 dark:text-green-400 flex items-center gap-1"><?= lang('website.view_all') ?> <i class="fi fi-rr-angle-right leading-none"></i></a>
                    </div>
                    <div class="swiper-container swiper px-4 pb-4 pt-2"
                        data-speed="400" data-space-between="20"
                        data-pagination="false" data-navigation="true"
                        data-autoplay="true" data-autoplay-delay="3000" data-effect="slide"
                        data-breakpoints='{"320": {"slidesPerView": 4}, "768": {"slidesPerView": 6}, "1024": {"slidesPerView": 8}}'>
                        <div class="swiper-wrapper py-2 text-center">
                            <?php foreach ($dbSection['content'] as $category): ?>
                                <div class="swiper-slide">
                                    <a href="<?= !empty($category['firstSubcategory']) ? 'subcategory/' . $category['firstSubcategory']['slug'] : '/no-product-avilable' ?>">
                                        <div class="flex flex-col justify-center items-center p-2">
                                            <img src="<?= $category['category_img'] ?? $category['image'] ?? base_url('assets/images/no-image.png') ?>" alt="<?= $category['category_name'] ?>" class="bg-green-50 dark:bg-gray-700 rounded-xl w-16 h-16 object-cover shadow-sm dark:brightness-90" />
                                            <h6 class="text-sm font-semibold mt-2 text-center dark:text-gray-200"><?= $category['category_name'] ?></h6>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Best Seller Section -->
        <?php if ($dbSection['section_style'] === 'best_seller' && !empty($dbSection['content'])): ?>
            <section class="mt-2 md:mt-4 md:container md:mx-auto px-3">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                    
                    <div class="flex items-center justify-between px-5 py-4 border-b border-dashed border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-2">
                            <i class="fi fi-rr-star text-yellow-500 leading-none"></i>
                            <h2 class="text-lg font-medium dark:text-white">
                                <?= lang('website.best_seller') ?>&nbsp;<?= lang('website.categories') ?>
                            </h2>
                        </div>
                    </div>
                    <div class="swiper-container swiper px-4 pb-4 pt-2"
                        data-speed="400" data-space-between="20"
                        data-pagination="false" data-navigation="true"
                        data-autoplay="true" data-autoplay-delay="3000" data-effect="slide"
                        data-breakpoints='{"320": {"slidesPerView": 2}, "768": {"slidesPerView": 3}, "1024": {"slidesPerView": 6}}'>
                        <div class="swiper-wrapper py-2 text-center">
                            <?php foreach ($dbSection['content'] as $category): ?>
                                <?php $slug = !empty($category['firstSubcategory']) ? $category['firstSubcategory']['slug'] : 'no-product-avilable'; ?>
                                <div class="swiper-slide">
                                    <a href="<?= base_url('subcategory/' . $slug) ?>" class="block bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition p-3">
                                        <div class="rounded-xl">
                                            <div class="flex flex-wrap justify-center gap-1 mb-2">
                                                <?php if (!empty($category['images'])): ?>
                                                    <?php foreach (array_slice($category['images'], 0, 4) as $img): ?>
                                                        <img src="<?= esc($img) ?>" alt="Product" class="w-14 h-14 rounded-lg object-cover dark:brightness-90" />
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <img src="<?= base_url('assets/images/no-image.png') ?>" alt="No Image" class="w-14 h-14 rounded-lg object-cover dark:brightness-75" />
                                                <?php endif; ?>
                                            </div>
                                            <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-lg w-2/5 mx-auto -mt-2">
                                                <p class="text-[10px] text-green-700 dark:text-green-400 py-0.5 px-2 text-center">+<?= esc($category['total_count']) ?></p>
                                            </div>
                                            <p class="text-xs mt-2 font-medium text-center dark:text-gray-200"><?= esc($category['category_name']) ?></p>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Product List Section -->
        <?php if ($dbSection['section_style'] === 'product_list' && !empty($dbSection['content'])): ?>
            <section class="mt-2 md:mt-4 md:container md:mx-auto px-3">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                    
                    <div class="flex items-center justify-between px-5 py-4 border-b border-dashed border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-2">
                            <i class="fi fi-rr-box-alt text-indigo-500 leading-none"></i>
                            <h2 class="text-lg font-medium dark:text-white"><?= esc($dbSection['title']) ?></h2>
                        </div>
                    </div>
                    <div class="swiper-container swiper px-4 pb-5 pt-3"
                        data-speed="400" data-space-between="14"
                        data-pagination="false" data-navigation="true"
                        data-autoplay="true" data-autoplay-delay="3000" data-effect="slide"
                        data-breakpoints='{"320": {"slidesPerView": 2}, "768": {"slidesPerView": 3}, "1024": {"slidesPerView": 4}, "1440": {"slidesPerView": 6}}'>
                        <div class="swiper-wrapper py-3">
                            <?php foreach ($dbSection['content'] as $product): ?>
                                <?php $firstVarient = $product['variants'][0] ?? null; ?>
                                <?php if ($firstVarient): ?>
                                    <?php
                                    $activePrice = $firstVarient['discounted_price'] > 0 ? $firstVarient['discounted_price'] : $firstVarient['price'];
                                    $formattedPrice = $settings['currency_symbol_position'] == 'left'
                                        ? $country['currency_symbol'] . $activePrice
                                        : $activePrice . $country['currency_symbol'];
                                    $hasDiscount = $firstVarient['discounted_price'] > 0;
                                    $discountPercentage = $hasDiscount ? round((($firstVarient['price'] - $firstVarient['discounted_price']) / $firstVarient['price']) * 100) : 0;
                                    $originalPrice = $hasDiscount
                                        ? ($settings['currency_symbol_position'] == 'left' ? $country['currency_symbol'] . $firstVarient['price'] : $firstVarient['price'] . $country['currency_symbol'])
                                        : '';
                                    $outOfStock = $firstVarient['stock'] == 0 && $firstVarient['is_unlimited_stock'] == 0;
                                    ?>
                                    <div class="swiper-slide group" id="<?= $product['slug'] . '-' . $firstVarient['id'] ?>">
                                        <div class="flex flex-col rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 overflow-hidden" style="height:100%;">
                                            <div class="relative w-full flex-shrink-0">
                                                <a href="/product/<?= $product['slug'] ?>" class="relative block w-full overflow-hidden" style="aspect-ratio:1/1; background:linear-gradient(135deg,#f8faf8 0%,#eef4ee 100%);">
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
                                                <?php
                                                $avgRating = isset($product['avg_rating']) ? round((float)$product['avg_rating'], 1) : 0;
                                                $ratingCount = isset($product['rating_count']) ? (int)$product['rating_count'] : 0;
                                                $fullStars = floor($avgRating);
                                                $halfStar = ($avgRating - $fullStars) >= 0.5 ? 1 : 0;
                                                $emptyStars = 5 - $fullStars - $halfStar;
                                                ?>
                                                <div class="flex items-center gap-1.5">
                                                    <div class="flex items-center gap-px">
                                                        <?php for ($s = 0; $s < $fullStars; $s++): ?>
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#f59e0b" class="w-3 h-3"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                                        <?php endfor; ?>
                                                        <?php if ($halfStar): ?>
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-3 h-3">
                                                                <defs><linearGradient id="half-<?= $product['id'] ?>"><stop offset="50%" stop-color="#f59e0b"/><stop offset="50%" stop-color="transparent"/></linearGradient></defs>
                                                                <path fill="url(#half-<?= $product['id'] ?>)" stroke="#f59e0b" stroke-width="1.5" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                            </svg>
                                                        <?php endif; ?>
                                                        <?php for ($s = 0; $s < $emptyStars; $s++): ?>
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="1.5" class="w-3 h-3 dark:stroke-gray-500"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                                        <?php endfor; ?>
                                                    </div>
                                                    <span class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 leading-none"><?= number_format($avgRating, 1) ?> (<?= $ratingCount ?>)</span>
                                                </div>
                                                <div class="flex items-center justify-between gap-1">
                                                    <span class="text-[11px] text-gray-400 dark:text-gray-500 truncate leading-none"><?= esc($firstVarient['title']) ?></span>
                                                    <span class="productDeliveryTime mt-1 hidden whitespace-nowrap">
                                                        <span class="inline-flex items-center gap-1 bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 text-[10px] font-bold px-1.5 py-0.5 rounded-md leading-none border border-orange-100 dark:border-orange-800">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 24" class="h-3.5" style="width:auto;" fill="currentColor">
                                                                <circle cx="9" cy="18" r="3.8" fill="none" stroke="currentColor" stroke-width="2.2"/>
                                                                <circle cx="9" cy="18" r="1.2"/>
                                                                <circle cx="29" cy="18" r="3.8" fill="none" stroke="currentColor" stroke-width="2.2"/>
                                                                <circle cx="29" cy="18" r="1.2"/>
                                                                <path d="M9,15.5 L11,10 L17,8.5 L25,9 L29,12 L29,15.5 Q19,17 9,15.5 Z"/>
                                                                <line x1="23" y1="8" x2="17" y2="3.5" stroke="currentColor" stroke-width="3" stroke-linecap="round" fill="none"/>
                                                                <circle cx="15.5" cy="2.5" r="2.6"/>
                                                                <line x1="17" y1="4" x2="12.5" y2="8" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>
                                                                <line x1="35" y1="9.5" x2="40" y2="9.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                                                                <line x1="36" y1="12.5" x2="44" y2="12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                                                                <line x1="34.5" y1="15.5" x2="42" y2="15.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                                                            </svg>
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
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Highlight Section -->
        <?php if ($dbSection['section_style'] === 'highlight' && !empty($dbSection['content'])): ?>
            <section class="mt-2 md:mt-4 md:container md:mx-auto px-3">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                    
                    <div class="flex items-center justify-between px-5 py-4 border-b border-dashed border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-2">
                            <i class="fi fi-rr-megaphone text-pink-500 leading-none"></i>
                            <h2 class="text-lg font-medium dark:text-white"><?= lang('website.highlights') ?></h2>
                        </div>
                    </div>
                    <div class="swiper-container swiper px-4 pb-4 pt-2"
                        data-speed="400" data-space-between="20"
                        data-pagination="false" data-navigation="true"
                        data-autoplay="true" data-autoplay-delay="3000" data-effect="slide"
                        data-breakpoints='{"320": {"slidesPerView": 1}, "480": {"slidesPerView": 2}, "768": {"slidesPerView": 2}, "1024": {"slidesPerView": 3}}'>
                        <div class="swiper-wrapper py-4">
                            <?php foreach ($dbSection['content'] as $item): ?>
                                <div class="swiper-slide rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition overflow-hidden bg-white dark:bg-gray-800">
                                    <div class="w-full overflow-hidden">
                                        <?php if (empty($item['image'])): ?>
                                            <iframe class="w-full h-48 rounded-t-xl" src="https://www.youtube.com/embed/<?= esc($item['video']) ?>" title="YouTube video player" frameborder="0" allowfullscreen></iframe>
                                        <?php else: ?>
                                            <img loading="lazy" src="<?= esc($item['image']) ?>" alt="<?= esc($item['title']) ?>" class="w-full h-48 object-cover dark:brightness-90" />
                                        <?php endif; ?>
                                        <a href="<?= base_url('seller/' . ($item['seller_slug'] ?? '')) ?>" class="p-4 flex items-end justify-between block dark:bg-gray-800">
                                            <div class="flex-1 pr-3">
                                                <h3 class="text-base font-medium dark:text-white"><?= esc($item['title']) ?></h3>
                                                <p class="text-gray-500 dark:text-gray-400 text-sm line-clamp-2"><?= esc($item['description']) ?></p>
                                            </div>
                                            <div class="w-10 h-10 bg-green-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                                <i class="fi fi-rr-arrow-right text-white"></i>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Shop By Brand Section -->
        <?php if ($dbSection['section_style'] === 'shop_by_brand' && !empty($dbSection['content'])): ?>
            <section class="mt-2 md:mt-4 md:container md:mx-auto px-3">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                    
                    <div class="flex items-center justify-between px-5 py-4 border-b border-dashed border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-2">
                            <i class="fi fi-rr-badge text-purple-500 leading-none"></i>
                            <h2 class="text-lg font-medium dark:text-white">
                                <span class="hidden md:inline"><?= lang('website.shop_by') ?>&nbsp;</span><?= lang('website.brand') ?>
                            </h2>
                        </div>
                        <a href="/brand" class="text-xs font-medium text-green-600 dark:text-green-400 flex items-center gap-1"><?= lang('website.view_all') ?> <i class="fi fi-rr-angle-right leading-none"></i></a>
                    </div>
                    <div class="swiper-container swiper px-4 pb-4 pt-2"
                        data-speed="400" data-space-between="20"
                        data-pagination="false" data-navigation="true"
                        data-autoplay="true" data-autoplay-delay="3000" data-effect="slide"
                        data-breakpoints='{"320": {"slidesPerView": 4}, "768": {"slidesPerView": 6}, "1024": {"slidesPerView": 8}}'>
                        <div class="swiper-wrapper py-2 text-center">
                            <?php foreach ($dbSection['content'] as $brand): ?>
                                <div class="swiper-slide">
                                    <a href="brand/<?= $brand['slug'] ?? '#' ?>">
                                        <div class="flex flex-col justify-center items-center p-3">
                                            <img src="<?= $brand['image'] ?? base_url('assets/images/no-image.png') ?>" alt="<?= $brand['brand_name'] ?? $brand['brand'] ?? 'Brand' ?>" class="bg-gray-50 dark:bg-gray-700 rounded-xl w-16 h-16 object-contain shadow-sm dark:brightness-90" />
                                            <h6 class="text-sm font-semibold mt-2 text-center dark:text-gray-200"><?= $brand['brand_name'] ?? $brand['brand'] ?? 'Brand' ?></h6>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Shop By Seller Section -->
        <?php if ($dbSection['section_style'] === 'shop_by_seller' && !empty($dbSection['content'])): ?>
            <section class="mt-2 md:mt-4 md:container md:mx-auto px-3">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                    
                    <div class="flex items-center justify-between px-5 py-4 border-b border-dashed border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-2">
                            <i class="fi fi-rr-shop text-blue-500 leading-none"></i>
                            <h2 class="text-lg font-medium dark:text-white">
                                <span class="hidden md:inline"><?= lang('website.shop_by') ?>&nbsp;</span><?= lang('website.seller') ?>
                            </h2>
                        </div>
                    </div>
                    <div class="swiper-container swiper px-4 pb-4 pt-2"
                        data-speed="400" data-space-between="20"
                        data-pagination="false" data-navigation="true"
                        data-autoplay="true" data-autoplay-delay="3000" data-effect="slide"
                        data-breakpoints='{"320": {"slidesPerView": 3}, "768": {"slidesPerView": 4}, "1024": {"slidesPerView": 6}}'>
                        <div class="swiper-wrapper py-4 text-center">
                            <?php foreach ($dbSection['content'] as $seller): ?>
                                <div class="swiper-slide">
                                    <a href="sellers/<?= $seller['slug'] ?>">
                                        <div class="relative flex w-full flex-auto flex-col place-content-inherit align-items-inherit h-auto break-words text-left overflow-y-auto subpixel-antialiased p-0 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm hover:shadow-md transition">
                                            <div class="relative w-full h-24 sm:h-32">
                                                <div class="relative w-full h-full overflow-hidden group rounded-t-xl">
                                                    <img class="opacity-0 shadow-black/5 data-[loaded=true]:opacity-100 shadow-none motion-reduce:transition-none !duration-300 rounded-large w-full h-full object-top absolute inset-0 z-10 rounded-t-xl rounded-b-none transition-transform duration-300 ease-in-out group-hover:scale-110" loading="lazy" alt="<?= esc($seller['store_name']) ?>" src="<?= $seller['banner'] ?? base_url('assets/images/no-image.png') ?>" data-loaded="true">
                                                </div>
                                                <div class="relative">
                                                    <span tabindex="-1" class="flex justify-center items-center box-border overflow-hidden align-middle outline-solid outline-transparent data-[focus-visible=true]:z-10 data-[focus-visible=true]:outline-2 data-[focus-visible=true]:outline-focus data-[focus-visible=true]:outline-offset-2 text-tiny bg-default text-default-foreground rounded-full ring-2 ring-offset-2 ring-offset-background dark:ring-offset-background-dark ring-default absolute -bottom-6 left-4 w-14 h-14 sm:w-16 sm:h-16 z-20">
                                                        <img class="flex object-cover w-full h-full transition-opacity !duration-500 opacity-0 data-[loaded=true]:opacity-100" alt="<?= esc($seller['store_name']) ?>" src="<?= $seller['logo'] ?? base_url('assets/images/no-image.png') ?>" data-loaded="true">
                                                    </span>
                                                    <span class="absolute top-2 left-16 w-3.5 h-3.5 bg-green-400 border-2 border-white rounded-full z-30"></span>
                                                </div>
                                            </div>
                                            <div class="px-4 pb-4 pt-8">
                                                <h3 class="text-sm sm:text-base font-semibold mb-2 line-clamp-1 text-gray-900 dark:text-white"><?= esc($seller['store_name']) ?></h3>
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-1.5 flex-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin w-3 h-3 text-gray-500 dark:text-gray-400 shrink-0" aria-hidden="true">
                                                            <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
                                                            <circle cx="12" cy="10" r="3"></circle>
                                                        </svg>
                                                        <div class="text-xxs text-gray-600 dark:text-gray-300 line-clamp-1 cursor-pointer" title="View on Map"><?= esc($seller['store_address'] ?? '') ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>

    <?php endforeach; ?>
<?php else: ?>
    <section class="mt-2 md:mt-4 md:container md:mx-auto px-3">
        <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl text-center">
            <p class="text-gray-500 dark:text-gray-400">No sections found.</p>
        </div>
    </section>
<?php endif; ?>

<?php if (!empty($footerBanner)): ?>
    <?php $fCount = count($footerBanner); ?>
    <section class="mt-2 md:mt-4 mb-4 md:container md:mx-auto px-3">

        <?php if ($fCount === 1): ?>
            <!-- 1 banner: full width -->
            <a href="<?= !empty($footerBanner[0]['firstSubcategory']) ? 'subcategory/' . $footerBanner[0]['firstSubcategory']['slug'] : '#' ?>">
                <img src="<?= esc($footerBanner[0]['image']) ?>" class="w-full rounded-2xl shadow-sm dark:brightness-90" />
            </a>

        <?php elseif ($fCount === 2): ?>
            <!-- 2 banners: 50/50 on desktop, single scroll on mobile -->
            <div class="hidden md:grid md:grid-cols-2 gap-3">
                <?php foreach ($footerBanner as $banner): ?>
                    <a href="<?= !empty($banner['firstSubcategory']) ? 'subcategory/' . $banner['firstSubcategory']['slug'] : '#' ?>">
                        <img src="<?= esc($banner['image']) ?>" class="w-full rounded-2xl shadow-sm dark:brightness-90" />
                    </a>
                <?php endforeach; ?>
            </div>
            <!-- Mobile: horizontal scroll, one at a time -->
            <div class="md:hidden flex gap-3 overflow-x-auto snap-x snap-mandatory scrollbar-hide">
                <?php foreach ($footerBanner as $banner): ?>
                    <a href="<?= !empty($banner['firstSubcategory']) ? 'subcategory/' . $banner['firstSubcategory']['slug'] : '#' ?>" class="flex-shrink-0 w-full snap-center">
                        <img src="<?= esc($banner['image']) ?>" class="w-full rounded-2xl shadow-sm dark:brightness-90" />
                    </a>
                <?php endforeach; ?>
            </div>

        <?php elseif ($fCount === 3): ?>
            <!-- 3 banners: 33/33/33 on desktop, single scroll on mobile -->
            <div class="hidden md:grid md:grid-cols-3 gap-3">
                <?php foreach ($footerBanner as $banner): ?>
                    <a href="<?= !empty($banner['firstSubcategory']) ? 'subcategory/' . $banner['firstSubcategory']['slug'] : '#' ?>">
                        <img src="<?= esc($banner['image']) ?>" class="w-full rounded-2xl shadow-sm dark:brightness-90" />
                    </a>
                <?php endforeach; ?>
            </div>
            <!-- Mobile: horizontal scroll, one at a time -->
            <div class="md:hidden flex gap-3 overflow-x-auto snap-x snap-mandatory scrollbar-hide">
                <?php foreach ($footerBanner as $banner): ?>
                    <a href="<?= !empty($banner['firstSubcategory']) ? 'subcategory/' . $banner['firstSubcategory']['slug'] : '#' ?>" class="flex-shrink-0 w-full snap-center">
                        <img src="<?= esc($banner['image']) ?>" class="w-full rounded-2xl shadow-sm dark:brightness-90" />
                    </a>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <!-- 4+ banners: 3 visible at a time, scrollable swiper -->
            <div class="swiper-container swiper footer-banner-swiper" data-speed="400" data-space-between="12"
                data-pagination="true" data-navigation="true" data-autoplay="false" data-effect="slide"
                data-breakpoints='{"320": {"slidesPerView": 1}, "640": {"slidesPerView": 2}, "1024": {"slidesPerView": 3}}'>
                <div class="swiper-wrapper">
                    <?php foreach ($footerBanner as $banner): ?>
                        <a href="<?= !empty($banner['firstSubcategory']) ? 'subcategory/' . $banner['firstSubcategory']['slug'] : '#' ?>" class="swiper-slide">
                            <img src="<?= esc($banner['image']) ?>" class="w-full rounded-2xl shadow-sm dark:brightness-90" />
                        </a>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination mt-3"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        <?php endif; ?>

    </section>
<?php endif; ?>
