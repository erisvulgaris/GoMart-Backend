<!doctype html>
<html lang="<?= session()->get('site_lang') ?? 'en' ?>" dir="<?= dir_attribute() ?>">

<head>
    <?= $this->include('website/template/style') ?>

    <title><?= $product['product_name'] ?> - <?= $settings['business_name'] ?></title>
    <meta name="description" content="<?= $product['seo_description'] ?>" />
    <meta name="keywords" content="<?= $product['seo_keywords'] ?>" />

    <!-- Canonical URL -->
    <link rel="canonical" href="<?= base_url("product/" . $product['slug']) ?>" />

    <!-- Robots Meta Tag -->
    <meta name="robots" content="index, follow" />

    <!-- Open Graph Meta Tags (For Facebook, LinkedIn, etc.) -->
    <meta property="og:url" content="<?= current_url(); ?>" />
    <meta property="og:type" content="product" />
    <meta property="og:title" content="<?= $product['seo_title'] ?>" />
    <meta property="og:description" content="<?= $product['seo_description'] ?>" />
    <meta property="og:image" content="<?= base_url($product['main_img']) ?>" />
    <meta property="og:image:alt" content="<?= $product['product_name'] ?>" />
    <meta property="og:site_name" content="<?= $settings['business_name'] ?>" />

    <!-- Twitter Card Meta Tags (For Twitter Sharing) -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?= $product['seo_title'] ?>" />
    <meta name="twitter:description" content="<?= $product['seo_description'] ?>" />
    <meta name="twitter:image" content="<?= base_url($product['main_img']) ?>" />
    <meta name="twitter:site" content="@yourtwitterhandle" />

    <!-- Schema Markup (Structured Data for Google Rich Snippets) -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Product",
            "name": "<?= $product['product_name'] ?>",
            "image": "<?= base_url($product['main_img']) ?>",
            "description": "<?= $product['seo_description'] ?>",
            "brand": {
                "@type": "Brand",
                "name": "<?= $settings['business_name'] ?>"
            },
            "offers": {
                "@type": "Offer",
                "priceCurrency": "<?= $country['currency_symbol'] ?>",
                "price": "<?= $product['variants'][0]['discounted_price'] ?: $product['variants'][0]['price'] ?>",
                "itemCondition": "https://schema.org/NewCondition",
                "availability": "https://schema.org/InStock",
                "seller": {
                    "@type": "Organization",
                    "name": "<?= $settings['business_name'] ?>"
                }
            }
        }
    </script>
    <style>
        .zoom-container {
            position: relative;
            cursor: zoom-in;
        }

        .image-wrapper {
            position: relative;
            background: #f8f9fa;
            border-radius: 8px;
            overflow: hidden;
            width: 333px;
            height: 333px;
            margin: 0 auto;
        }

        .main-product-image {
            width: 333px !important;
            height: 333px !important;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .thumbnails-img {
            position: relative;
            transition: all 0.3s ease;
        }

        .thumbnails-img:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .thumbnails-img.active {
            border-color: #007bff !important;
            box-shadow: 0 0 0 1px #007bff;
        }

        .thumbnail-image {
            width: 333px;
            height: 333px;
            object-fit: cover;
            aspect-ratio: 1;
        }

        /* Zoom lens styling */
        .zoom-lens {
            border: 2px solid #007bff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            background: rgba(255, 255, 255, 0.3);
        }

        /* Zoom window styling */
        .zoom-window {
            background: #ffffff;
            border: 1px solid #dee2e6;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        }

        /* Modal styling */
        .zoom-modal {
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }

        .zoom-modal-content {
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .zoom-modal-close {
            transition: all 0.2s ease;
        }

        .zoom-modal-close:hover {
            background: rgba(255, 255, 255, 0.9);
            transform: scale(1.1);
        }

        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .zoom-window {
                display: none !important;
            }
        }

        @media (max-width: 768px) {
            .thumbnails {
                flex-wrap: wrap;
            }

            .thumbnail-wrapper {
                width: calc(25% - 0.5rem);
            }
        }

        .nav-link.active-tab { border-color: #16a34a !important; color: #16a34a !important; background-color: #f0fdf4 !important; }
        .dark .nav-link.active-tab { border-color: #22c55e !important; color: #4ade80 !important; background-color: rgba(22,163,74,0.15) !important; }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900">
    <?= $this->include('website/template/header') ?>
    <main class="max-w-7xl mx-auto">

        <section class="mt-2 md:mt-4 md:container md:mx-auto px-3">
            <div class="flex flex-wrap">
                <div class="lg:w-1/3 w-full">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden p-4">
                    <!-- Main Swiper Container -->
                    <div class="swiper-container swiper" id="productSwiper">
                        <div class="swiper-wrapper">
                            <?php foreach ($product['images'] as $index => $image): ?>
                                <div class="swiper-slide zoom-container" data-index="<?= $index; ?>">
                                    <div class="image-wrapper bg-white dark:bg-gray-800" style="position: relative; overflow: hidden;">
                                        <img src="<?= base_url($image['image']) ?>"
                                            alt="Product Image <?= $index + 1; ?>"
                                            class="main-product-image w-full h-auto object-contain"
                                            style="display: block; max-width: 100%; height: auto;" />
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Thumbnails -->
                    <div class="thumbnails flex gap-3 mt-4" id="productThumbnails">
                        <?php foreach ($product['images'] as $index => $image): ?>
                            <div class="thumbnail-wrapper w-1/4">
                                <div class="thumbnails-img cursor-pointer border-2 border-transparent dark:border-gray-700 rounded-xl overflow-hidden transition-all duration-300 hover:border-gray-300 dark:hover:border-gray-500"
                                    data-index="<?= $index; ?>">
                                    <img src="<?= base_url($image['image']) ?>"
                                        alt="Thumbnail <?= $index + 1; ?>"
                                        class="thumbnail-image w-full h-auto object-cover rounded-xl" />
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    </div><!-- end left-column card -->
                </div>


                <div class="lg:w-2/3 w-full lg:pl-4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                    
                    <div class="px-5 py-5">
                        <div class="flex flex-col gap-4">
                            <!-- heading -->
                            <div class="flex flex-col">
                                <div class="flex flex-row justify-between gap-3">
                                    <h1 class="text-lg md:text-xl lg:text-2xl font-bold mb-2 dark:text-white"><?= $product['product_name'] ?></h1>
                                    <i class="fi fi-rr-share-square md:hidden p-1 border dark:border-gray-600 rounded-full shadow-sm self-center w-7 h-7 dark:text-gray-300" id="shareButton"></i>
                                </div>

                                <div class="flex flex-col gap-4">
                                    <div class="flex items-center gap-2">
                                        <small class="text-yellow-500 inline-flex items-center">
                                            <?php
                                            $fullStars = floor($product['average_rating']);
                                            $hasHalfStar = ($product['average_rating'] - $fullStars) >= 0.5;

                                            for ($i = 0; $i < $fullStars; $i++) {
                                                echo '<i class="fi fi-sc-star"></i>';
                                            }

                                            if ($hasHalfStar) {
                                                echo '<i class="fi fi-rr-star-sharp-half-stroke"></i>';
                                            }

                                            for ($i = $fullStars + ($hasHalfStar ? 1 : 0); $i < 5; $i++) {
                                                echo '<i class="fi fi-rr-star-exclamation"></i>';
                                            }
                                            ?>
                                        </small>
                                        <a href="#" class="text-green-600 dark:text-green-400 text-sm">(<?= $product['rating_count'] ?> reviews)</a>
                                    </div>

                                    <hr class="dark:border-gray-600">

                                    <!-- Variants Section -->
                                    <div class="flex flex-wrap gap-2">

                                        <?php $first = true;
                                        foreach ($product['variants'] as $varient): if ($first): ?>
                                                <!-- Active / First Variant -->
                                                <div id="variant-<?= $varient['id'] ?>" class="border border-green-600 bg-green-50 dark:bg-green-900/30 dark:border-green-500 rounded-xl shadow-sm p-4 cursor-pointer active" onclick="updateVariantUI(this); switchVarient(<?= $product['id'] ?>, <?= $varient['id'] ?>, '<?= $product['slug'] ?>')">
                                                    <div class="flex flex-col items-center">
                                                        <p class="text-sm font-semibold dark:text-white"><?= $varient['title'] ?></p>
                                                        <div class="flex justify-between w-full mt-2 gap-4">
                                                            <?php if ($varient['discounted_price'] > 0): ?>
                                                                <p class="text-green-700 dark:text-green-400 font-bold">
                                                                    <?php if ($settings['currency_symbol_position'] == 'left'): ?>
                                                                        <?= $country['currency_symbol'] ?><?= $varient['discounted_price'] ?>
                                                                    <?php else: ?>
                                                                        <?= $varient['discounted_price'] ?><?= $country['currency_symbol'] ?>
                                                                    <?php endif; ?>
                                                                </p>
                                                                <p class="text-xs text-gray-400 dark:text-gray-500 font-semibold self-center">
                                                                    <span class="line-through font-bold">
                                                                        <?php if ($settings['currency_symbol_position'] == 'left'): ?>
                                                                            <?= $country['currency_symbol'] ?><?= $varient['price'] ?>
                                                                        <?php else: ?>
                                                                            <?= $varient['price'] ?><?= $country['currency_symbol'] ?>
                                                                        <?php endif; ?>
                                                                    </span>
                                                                </p>
                                                            <?php else: ?>
                                                                <p class="text-sm text-gray-800 dark:text-gray-100">
                                                                    <span class="font-bold">
                                                                        <?php if ($settings['currency_symbol_position'] == 'left'): ?>
                                                                            <?= $country['currency_symbol'] ?><?= $varient['price'] ?>
                                                                        <?php else: ?>
                                                                            <?= $varient['price'] ?><?= $country['currency_symbol'] ?>
                                                                        <?php endif; ?>
                                                                    </span>
                                                                </p>
                                                            <?php endif; ?>

                                                        </div>
                                                    </div>
                                                    <?php $first = false; ?>
                                                </div>
                                            <?php else: ?>
                                                <!-- Inactive Variants -->
                                                <div id="variant-<?= $varient['id'] ?>" class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl p-4 cursor-pointer hover:border-green-500" onclick="updateVariantUI(this); switchVarient(<?= $product['id'] ?>, <?= $varient['id'] ?>, '<?= $product['slug'] ?>')">
                                                    <div class="flex flex-col items-center">
                                                        <p class="text-sm font-semibold dark:text-white"><?= $varient['title'] ?></p>
                                                        <div class="flex justify-between w-full mt-2 gap-4">
                                                            <?php if ($varient['discounted_price'] > 0): ?>
                                                                <p class="text-green-700 dark:text-green-400 font-bold">
                                                                    <?php if ($settings['currency_symbol_position'] == 'left'): ?>
                                                                        <?= $country['currency_symbol'] ?><?= $varient['discounted_price'] ?>
                                                                    <?php else: ?>
                                                                        <?= $varient['discounted_price'] ?><?= $country['currency_symbol'] ?>
                                                                    <?php endif; ?>
                                                                </p>
                                                                <p class="text-xs text-gray-400 dark:text-gray-500 font-semibold self-center">
                                                                    <span class="line-through font-bold">
                                                                        <?php if ($settings['currency_symbol_position'] == 'left'): ?>
                                                                            <?= $country['currency_symbol'] ?><?= $varient['price'] ?>
                                                                        <?php else: ?>
                                                                            <?= $varient['price'] ?><?= $country['currency_symbol'] ?>
                                                                        <?php endif; ?>
                                                                    </span>
                                                                </p>
                                                            <?php else: ?>
                                                                <p class="text-sm text-gray-800 dark:text-gray-100">
                                                                    <span class="font-bold">
                                                                        <?php if ($settings['currency_symbol_position'] == 'left'): ?>
                                                                            <?= $country['currency_symbol'] ?><?= $varient['price'] ?>
                                                                        <?php else: ?>
                                                                            <?= $varient['price'] ?><?= $country['currency_symbol'] ?>
                                                                        <?php endif; ?>
                                                                    </span>
                                                                </p>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>

                                </div>
                            </div>

                            <div class="gap-6">
                                <div class="<?= $product['slug'] . '-mainbtndiv' ?>">
                                    <?php $first = true; ?>
                                    <?php foreach ($product['variants'] as $varient): ?>
                                        <?php if ($first): ?>
                                            <div class="<?= $product['slug'] . '-mainbtndiv-' . $varient['id'] ?>">
                                                <?php if ($varient['cart_quantity']): ?>
                                                    <div class="inline-flex items-center gap-1 p-1 rounded-xl overflow-hidden border border-green-600 bg-green-600 shadow-md">
                                                        <button type="button" onclick="removeFromCart(<?= $product['id'] ?>, <?= $varient['id'] ?>)"
                                                            class="text-lg leading-none hover:text-primary <?= $product['slug'] . '-removebtn-' . $varient['id'] ?>">
                                                            <i class="fi fi-rr-minus-small text-white"></i>
                                                        </button>
                                                        <span class="text-center h-5 text-sm font-medium text-white <?= $product['slug'] . '-qty-' . $varient['id'] ?>">
                                                            <?= $varient['cart_quantity'] ?>
                                                        </span>
                                                        <button type="button" onclick="addToCart(<?= $product['id'] ?>, <?= $varient['id'] ?>)"
                                                            class="text-lg leading-none hover:text-primary <?= $product['slug'] . '-addbtn-' . $varient['id'] ?>">
                                                            <i class="fi fi-rr-plus-small text-white"></i>
                                                        </button>
                                                    </div>
                                                <?php else: ?>
                                                    <button type="button" onclick="addToCart(<?= $product['id'] ?>, <?= $varient['id'] ?>)"
                                                        class="text-sm px-2 py-1 rounded-xl items-center gap-x-1 bg-green-600 hover:bg-green-700 text-white border-green-600 disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:border-green-700 btn-sm">
                                                        <i class="fi fi-rr-shopping-cart"></i>
                                                        <span><?php echo lang('website.add'); ?></span>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                            <?php $first = false; ?>
                                        <?php else: ?>
                                            <div class="<?= $product['slug'] . '-mainbtndiv-' . $varient['id'] ?>"></div>

                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <hr class="dark:border-gray-600">

                            <div class="flex flex-col gap-4">
                                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl py-3 px-1">
                                <ul class="flex items-center text-sm gap-4">
                                    <li class="font-medium dark:text-gray-300"><i class="fi fi-rr-share-square"></i><?php echo lang('website.share_product'); ?> : </li>
                                    <li>
                                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= current_url(); ?>" target="_blank">
                                            <i class="fi fi-brands-facebook text-xl text-blue-600"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://wa.me/?text=Check%20out%20this%20product:%20<?= current_url(); ?>" target="_blank">
                                            <i class="fi fi-brands-whatsapp text-xl text-green-600"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://t.me/share/url?url=<?= current_url(); ?>&text=Check%20out%20this%20product!" target="_blank">
                                            <i class="fi fi-brands-telegram text-xl text-cyan-500"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#!" onclick="copyLink()" class="">
                                            <i class="fi fi-rr-link-alt text-xl font-bold dark:text-gray-300"></i>
                                        </a>
                                    </li>
                                </ul>
                                </div>
                            </div>
                        </div>
                    </div><!-- end px-5 py-5 inner wrapper -->
                    </div><!-- end right-column card -->

                </div>
            </div>
        </section>



        <section class="mt-2 md:mt-4 md:container md:mx-auto px-3 ">
            <div class="flex flex-wrap bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="h-1 bg-gradient-to-r from-green-400 via-emerald-500 to-teal-400 w-full"></div>
                <div class="w-full p-2 md:p-4">

                    <ul class="nav pl-0 gap-3 pb-6 border-b dark:border-gray-600 flex flex-wrap">

                        <li class="nav-item">
                            <button
                                class="inline-block py-2 px-3 border border-gray-200 dark:border-gray-600 dark:text-white dark:bg-gray-700 rounded-xl text-sm font-semibold nav-link active-tab"
                                data-bs-target="#reviews-tab-pane" type="button" onclick="showTab(this)">
                                <?php echo lang('website.rating_&_reviews'); ?>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                                class="inline-block py-2 px-3 border border-gray-200 dark:border-gray-600 dark:text-white dark:bg-gray-700 rounded-xl text-sm font-semibold nav-link"
                                data-bs-target="#product-details-tab-pane" type="button" onclick="showTab(this)">
                                <?php echo lang('website.product_details'); ?>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                                class="inline-block py-2 px-3 border border-gray-200 dark:border-gray-600 dark:text-white dark:bg-gray-700 rounded-xl text-sm font-semibold nav-link"
                                data-bs-target="#seller-details-tab-pane" type="button" onclick="showTab(this)">
                                <?php echo lang('website.seller'); ?> Details
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <div id="reviews-tab-pane" class="tab-pane block">
                            <div class="mt-4">
                                <div class="flex flex-wrap md:flex-nowrap gap-6">
                                    <div class="md:w-1/3 w-full">
                                        <div class="flex flex-col gap-6 mb-6">
                                            <div class="flex flex-col gap-2">
                                                <h3 class="text-sm font-semibold dark:text-white"><?php echo lang('website.customer_reviews'); ?> <span class="text-xs text-green-600 dark:text-green-400">(<?= $product['rating_count'] ?> Review)</span></h3>
                                                <div class="lg:flex items-center gap-6 ">

                                                    <small class="text-yellow-500 inline-flex items-center">
                                                        <?php
                                                        $fullStars = floor($product['average_rating']);
                                                        $hasHalfStar = ($product['average_rating'] - $fullStars) >= 0.5;

                                                        for ($i = 0; $i < $fullStars; $i++) {
                                                            echo '<i class="fi fi-sc-star"></i>';
                                                        }

                                                        if ($hasHalfStar) {
                                                            echo '<i class="fi fi-rr-star-sharp-half-stroke"></i>';
                                                        }

                                                        for ($i = $fullStars + ($hasHalfStar ? 1 : 0); $i < 5; $i++) {
                                                            echo '<i class="fi fi-rr-star-exclamation"></i>';
                                                        }
                                                        ?>
                                                    </small>

                                                    <span class="dark:text-gray-300"><?= $product['average_rating'] ?>/5</span>

                                                </div>
                                            </div>
                                            <div class="flex flex-col gap-3">
                                                <!-- progress -->
                                                <div class="flex items-center gap-4">
                                                    <div class="text-gray-500 dark:text-gray-400 flex items-center gap-2">
                                                        <span class="inline-block align-middle text-gray-500 dark:text-gray-400">5</span>
                                                        <span class="text-yellow-500">
                                                            <i class="fi fi-sc-star"></i>
                                                        </span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-1.5">
                                                        <div class="bg-yellow-500 h-1.5 rounded-full" style="width: <?php echo (int)$product['rating_count'] ? ((int)$product['star_ratings']['5_star'] / (int)$product['rating_count']) * 100 : 0 ?>%"></div>
                                                    </div>
                                                    <span class="text-gray-500 dark:text-gray-400"><?php echo (int)$product['rating_count'] ? ((int)$product['star_ratings']['5_star'] / (int)$product['rating_count']) * 100 : 0 ?>%</span>
                                                </div>

                                                <!-- progress -->
                                                <div class="flex items-center gap-4">
                                                    <div class="text-gray-500 dark:text-gray-400 flex items-center gap-2">
                                                        <span class="inline-block align-middle text-gray-500 dark:text-gray-400">4</span>
                                                        <span class="text-yellow-500">
                                                            <i class="fi fi-sc-star"></i>
                                                        </span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-1.5">
                                                        <div class="bg-yellow-500 h-1.5 rounded-full" style="width: <?php echo (int)$product['rating_count'] ? ((int)$product['star_ratings']['4_star'] / (int)$product['rating_count']) * 100 : 0 ?>%"></div>
                                                    </div>
                                                    <span class="text-gray-500 dark:text-gray-400"><?php echo (int)$product['rating_count'] ? ((int)$product['star_ratings']['4_star'] / (int)$product['rating_count']) * 100 : 0 ?>%</span>
                                                </div>
                                                <!-- progress -->
                                                <div class="flex items-center gap-4">
                                                    <div class="text-gray-500 dark:text-gray-400 flex items-center gap-2">
                                                        <span class="inline-block align-middle text-gray-500 dark:text-gray-400">3</span>
                                                        <span class="text-yellow-500">
                                                            <i class="fi fi-sc-star"></i>
                                                        </span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-1.5">
                                                        <div class="bg-yellow-500 h-1.5 rounded-full" style="width: <?php echo (int)$product['rating_count'] ? ((int)$product['star_ratings']['3_star'] / (int)$product['rating_count']) * 100 : 0 ?>%"></div>
                                                    </div>
                                                    <span class="text-gray-500 dark:text-gray-400"><?php echo (int)$product['rating_count'] ? ((int)$product['star_ratings']['3_star'] / (int)$product['rating_count']) * 100 : 0 ?>%</span>
                                                </div>
                                                <!-- progress -->
                                                <div class="flex items-center gap-4">
                                                    <div class="text-gray-500 dark:text-gray-400 flex items-center gap-2">
                                                        <span class="inline-block align-middle text-gray-500 dark:text-gray-400">2</span>
                                                        <span class="text-yellow-500">
                                                            <i class="fi fi-sc-star"></i>
                                                        </span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-1.5">
                                                        <div class="bg-yellow-500 h-1.5 rounded-full" style="width: <?php echo (int)$product['rating_count'] ? ((int)$product['star_ratings']['2_star'] / (int)$product['rating_count']) * 100 : 0 ?>%"></div>
                                                    </div>
                                                    <span class="text-gray-500 dark:text-gray-400"><?php echo (int)$product['rating_count'] ? ((int)$product['star_ratings']['2_star'] / (int)$product['rating_count']) * 100 : 0 ?>%</span>
                                                </div>
                                                <!-- progress -->
                                                <div class="flex items-center gap-4">
                                                    <div class="text-gray-500 dark:text-gray-400 flex items-center gap-2">
                                                        <span class="inline-block align-middle text-gray-500 dark:text-gray-400">1</span>
                                                        <span class="text-yellow-500">
                                                            <i class="fi fi-sc-star"></i>
                                                        </span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-1.5">
                                                        <div class="bg-yellow-500 h-1.5 rounded-full" style="width: <?php echo (int)$product['rating_count'] ? ((int)$product['star_ratings']['1_star'] / (int)$product['rating_count']) * 100 : 0 ?>%"></div>
                                                    </div>
                                                    <span class="text-gray-500 dark:text-gray-400"><?php echo (int)$product['rating_count'] ? ((int)$product['star_ratings']['1_star'] / (int)$product['rating_count']) * 100 : 0 ?>%</span>
                                                </div>
                                            </div>
                                            <div class="flex flex-col gap-4">
                                                <div class="flex flex-col">
                                                    <h3 class="text-sm font-semibold dark:text-white"><?php echo lang('website.review_this_product'); ?></h3>
                                                    <p class="dark:text-gray-300"> <?php echo lang('website.share_your_thoughts_with_other_customers'); ?></p>
                                                </div>
                                                <button type="button" onclick="openWriteReviewPopup(<?= $product['id'] ?>)" class="btn inline-flex text-center items-center gap-x-2 p-2 rounded-xl bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-800 dark:text-white border-gray-300 dark:border-gray-500 border disabled:opacity-50 disabled:pointer-events-none hover:text-gray-900 hover:border-gray-700 dark:hover:border-gray-500 active:bg-gray-100 active:border-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600">
                                                    <?php echo lang('website.write_the_review'); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="md:w-2/3">
                                        <div>
                                            <div class="flex justify-between mb-8">
                                                <div>
                                                    <!-- heading -->
                                                    <h3 class="text-sm font-semibold dark:text-white"> <?php echo lang('website.review'); ?></h3>
                                                </div>
                                            </div>
                                            <div class="flex flex-col gap-5 ">
                                                <?php foreach ($productRatings as $productRating): ?>
                                                    <div class="flex flex-row border-b dark:border-gray-600 mb-4 pb-2">
                                                        <img src="<?php
                                                                    echo $productRating['login_type'] === 'normal'
                                                                        ? (isset($productRating['img']) ? base_url() . $productRating['img'] : base_url() . $settings['logo'])
                                                                        : (isset($productRating['img']) ? $productRating['img'] : base_url() . $settings['logo'])

                                                                    ?>" alt="" class="rounded-full border border-gray-300 dark:border-gray-600 p-1 h-12 w-12 mr-4">
                                                        <div class="flex flex-col gap-4">
                                                            <div class="flex flex-col gap-1">
                                                                <h4 class="text-base dark:text-white"><?= $productRating['name'] ?></h4>
                                                                <!-- select option -->
                                                                <!-- content -->
                                                                <p class="text-xs md:flex flex-row gap-3">
                                                                    <span class="text-gray-500 dark:text-gray-400"><?= date('d-m-Y H:i:s a', strtotime($productRating['created_at'])) ?></span>
                                                                </p>
                                                            </div>
                                                            <!-- rating -->
                                                            <div class="md:flex md:items-center gap-3">
                                                                <small class="text-yellow-500 inline-flex items-center">

                                                                    <?php

                                                                    for ($i = 1; $i <= 5; $i++) {
                                                                        if ($i <= $productRating['rate']) {
                                                                            echo '<i class="fi fi-sc-star text-yellow-500"></i>';
                                                                        } else {
                                                                            echo '<i class="fi fi-rr-star text-gray-300 dark:text-gray-600"></i>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </small>
                                                                <span class="text-gray-900 dark:text-white text-sm font-semibold"><?= $productRating['title'] ?></span>
                                                            </div>
                                                            <!-- text-->
                                                            <p class="text-gray-500 dark:text-gray-400 text-sm">
                                                                <?= $productRating['review'] ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Details Tab -->
                        <div id="product-details-tab-pane" class="tab-pane hidden">
                            <div class="mt-4">
                                <div class="flex flex-col gap-4">
                                    <div class="flex flex-col gap-2">
                                        <h3 class="text-sm font-semibold dark:text-white"><?php echo lang('website.product_details'); ?></h3>
                                        <p class="text-sm text-gray-700 dark:text-gray-300"><?= $product['description'] ?></p>
                                    </div>
                                    <?php if (!empty($product['fssai_lic_no'])): ?>
                                    <div class="flex flex-row gap-2">
                                        <h3 class="text-sm font-semibold dark:text-white"><?php echo lang('website.FSSAI_license'); ?>:</h3>
                                        <p class="text-sm text-gray-700 dark:text-gray-300"><?= $product['fssai_lic_no'] ?></p>
                                    </div>
                                    <?php endif; ?>
                                    <?php if (!empty($product['manufacturer'])): ?>
                                    <div class="flex flex-row gap-2">
                                        <h3 class="text-sm font-semibold dark:text-white"><?php echo lang('website.manufacturer'); ?>:</h3>
                                        <p class="text-sm text-gray-700 dark:text-gray-300"><?= $product['manufacturer'] ?></p>
                                    </div>
                                    <?php endif; ?>
                                    <?php if (!empty($product['made_in'])): ?>
                                    <div class="flex flex-row gap-2">
                                        <h3 class="text-sm font-semibold dark:text-white"><?php echo lang('website.made_in'); ?>:</h3>
                                        <p class="text-sm text-gray-700 dark:text-gray-300"><?= $product['made_in'] ?></p>
                                    </div>
                                    <?php endif; ?>
                                    <div class="flex flex-row gap-2">
                                        <h3 class="text-sm font-semibold dark:text-white"><?php echo lang('website.is_returnable'); ?>:</h3>
                                        <?= $product['is_returnable'] ? '<span class="text-green-600 dark:text-green-400 text-sm">Yes (in ' . $product['is_returnable'] . ' Days)</span>' : '<span class="text-red-600 dark:text-red-400 text-sm">No</span>' ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Seller Details Tab -->
                        <div id="seller-details-tab-pane" class="tab-pane hidden">
                            <div class="mt-4">
                                <div class="flex flex-col gap-4">
                                    <h3 class="text-sm font-semibold dark:text-white"><?php echo lang('website.seller'); ?> Details</h3>

                                    <!-- Store Name -->
                                    <div class="flex flex-row gap-2 items-center">
                                        <i class="fi fi-rr-shop text-green-600 dark:text-green-400"></i>
                                        <div>
                                            <h3 class="text-sm font-semibold dark:text-white"><?php echo lang('website.seller'); ?></h3>
                                            <p class="text-sm text-gray-700 dark:text-gray-300"><?= $product['seller']['store_name'] ?></p>
                                        </div>
                                    </div>

                                    <!-- Store Address -->
                                    <?php if (!empty($product['seller']['store_address'])): ?>
                                    <div class="flex flex-row gap-2 items-start">
                                        <i class="fi fi-rr-marker text-green-600 dark:text-green-400 mt-0.5"></i>
                                        <div>
                                            <h3 class="text-sm font-semibold dark:text-white">Store Address</h3>
                                            <p class="text-sm text-gray-700 dark:text-gray-300"><?= esc($product['seller']['store_address']) ?></p>
                                            <?php if (!empty($product['seller']['map_address'])): ?>
                                                <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($product['seller']['map_address']) ?>" target="_blank" class="text-xs text-green-600 dark:text-green-400 hover:underline">
                                                    <i class="fi fi-rr-link-alt"></i> View on Google Maps
                                                </a>
                                            <?php elseif (!empty($product['seller']['latitude']) && !empty($product['seller']['longitude'])): ?>
                                                <a href="https://www.google.com/maps?q=<?= $product['seller']['latitude'] ?>,<?= $product['seller']['longitude'] ?>" target="_blank" class="text-xs text-green-600 dark:text-green-400 hover:underline">
                                                    <i class="fi fi-rr-link-alt"></i> View on Google Maps
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Distance & Delivery Time (JS computed from localStorage) -->
                                    <div class="flex flex-row gap-4 flex-wrap">
                                        <div class="flex flex-row gap-2 items-center" id="sellerDistanceWrapper" style="display:none!important">
                                            <i class="fi fi-rr-route text-green-600 dark:text-green-400"></i>
                                            <div>
                                                <h3 class="text-sm font-semibold dark:text-white">Distance</h3>
                                                <p class="text-sm text-gray-700 dark:text-gray-300"><span id="sellerDistanceVal">—</span> km from your location</p>
                                            </div>
                                        </div>
                                        <div class="flex flex-row gap-2 items-center" id="sellerDeliveryWrapper" style="display:none!important">
                                            <i class="fi fi-rr-clock text-green-600 dark:text-green-400"></i>
                                            <div>
                                                <h3 class="text-sm font-semibold dark:text-white">Estimated Delivery</h3>
                                                <p class="text-sm text-gray-700 dark:text-gray-300"><span id="sellerDeliveryVal">—</span> min delivery</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Map embed -->
                                    <?php if (!empty($product['seller']['latitude']) && !empty($product['seller']['longitude'])): ?>
                                    <div class="rounded-lg overflow-hidden border dark:border-gray-600" style="height:240px;">
                                        <iframe
                                            width="100%" height="100%" frameborder="0" style="border:0"
                                            src="https://www.google.com/maps?q=<?= $product['seller']['latitude'] ?>,<?= $product['seller']['longitude'] ?>&z=15&output=embed"
                                            allowfullscreen loading="lazy">
                                        </iframe>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <script>
                        (function () {
                            try {
                                var loc = JSON.parse(localStorage.getItem('location') || '{}');
                                var deliveryTime = loc.delivery_time || document.getElementById('proxyDeliveryTime')?.textContent?.trim() || '';
                                var distanceKm = loc.distance_km || '';

                                if (deliveryTime) {
                                    document.getElementById('sellerDeliveryVal').textContent = deliveryTime;
                                    document.getElementById('sellerDeliveryWrapper').style.removeProperty('display');
                                }
                                if (distanceKm) {
                                    document.getElementById('sellerDistanceVal').textContent = parseFloat(distanceKm).toFixed(1);
                                    document.getElementById('sellerDistanceWrapper').style.removeProperty('display');
                                }
                            } catch(e) {}
                        })();
                        </script>

                    </div>
                </div>
            </div>
        </section>


        <?php if (!empty($similarProducts)): ?>
            <section class="mt-2 md:mt-4 md:container md:mx-auto px-3">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                
                <div class="flex justify-between p-4 border-b border-dashed border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-medium z-10 dark:text-white flex items-center gap-2"><i class="fi fi-rr-heart text-rose-500 leading-none"></i><?php echo lang('website.similar_products'); ?></h2>
                </div>

                <div class="swiper-container swiper bg-white dark:bg-gray-800 px-3" id="swiper-1" data-pagination-type=""
                    data-speed="400" data-space-between="20" data-pagination="false" data-navigation="true"
                    data-autoplay="true" data-autoplay-delay="3000" data-effect="slide"
                    data-breakpoints='{"320": {"slidesPerView": 2}, "768": {"slidesPerView": 3}, "1024": {"slidesPerView": 4}, "1440": {"slidesPerView": 6}}'>
                    <div class="swiper-wrapper py-4 text-center">
                        <?php foreach ($similarProducts as $product): ?>
                            <?php
                            $firstVarient = $product['variants'][0];
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
                                        $avgRating = round((float)($product['avg_rating'] ?? 0), 1);
                                        $ratingCount = (int)($product['rating_count'] ?? 0);
                                        
                                            $fullStars = floor($avgRating);
                                            $halfStar = ($avgRating - $fullStars) >= 0.5 ? 1 : 0;
                                            $emptyStars = 5 - $fullStars - $halfStar;
                                        ?>
                                        <div class="flex items-center gap-1.5">
                                            <div class="flex items-center gap-px">
                                                <?php for ($s = 0; $s < $fullStars; $s++): ?><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#f59e0b" class="w-3 h-3"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg><?php endfor; ?>
                                                <?php if ($halfStar): ?><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-3 h-3"><defs><linearGradient id="hs-sim-<?= $product['id'] ?>"><stop offset="50%" stop-color="#f59e0b"/><stop offset="50%" stop-color="transparent"/></linearGradient></defs><path fill="url(#hs-sim-<?= $product['id'] ?>)" stroke="#f59e0b" stroke-width="1.5" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg><?php endif; ?>
                                                <?php for ($s = 0; $s < $emptyStars; $s++): ?><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="1.5" class="w-3 h-3 dark:stroke-gray-500"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg><?php endfor; ?>
                                            </div>
                                            <span class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 leading-none"><?= $avgRating ?> (<?= $ratingCount ?>)</span>
                                        </div>
                                        
                                        <div class="flex items-center justify-between gap-1">
                                            <span class="text-[11px] text-gray-400 dark:text-gray-500 truncate leading-none"><?= esc($firstVarient['title']) ?></span>
                                            <span class="productDeliveryTime whitespace-nowrap hidden"><span class="inline-flex items-center gap-1 bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 text-[10px] font-bold px-1.5 py-0.5 rounded-md leading-none border border-orange-100 dark:border-orange-800"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 24" class="h-3.5" style="width:auto;" fill="currentColor"><circle cx="9" cy="18" r="3.8" fill="none" stroke="currentColor" stroke-width="2.2"/><circle cx="9" cy="18" r="1.2"/><circle cx="29" cy="18" r="3.8" fill="none" stroke="currentColor" stroke-width="2.2"/><circle cx="29" cy="18" r="1.2"/><path d="M9,15.5 L11,10 L17,8.5 L25,9 L29,12 L29,15.5 Q19,17 9,15.5 Z"/><line x1="23" y1="8" x2="17" y2="3.5" stroke="currentColor" stroke-width="3" stroke-linecap="round" fill="none"/><circle cx="15.5" cy="2.5" r="2.6"/><line x1="17" y1="4" x2="12.5" y2="8" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/><line x1="35" y1="9.5" x2="40" y2="9.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none"/><line x1="36" y1="12.5" x2="44" y2="12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none"/><line x1="34.5" y1="15.5" x2="42" y2="15.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none"/></svg><span class="productDeliveryTimeVal"></span>m</span></span>
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
                    <div class="swiper-pagination"></div>
                </div>

                <div class="grid gap-4 grid-cols-1 md:grid-cols-2 lg:gap-4 xl:grid-cols-5">
                </div>
                </div><!-- end similar products card -->
            </section>
        <?php endif; ?>

        <?php if (!empty($categoryProducts)): ?>
            <section class="mt-2 md:mt-4 md:container md:mx-auto px-3">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                
                <div class="flex justify-between p-4 border-b border-dashed border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-medium z-10 dark:text-white flex items-center gap-2"><i class="fi fi-rr-grid text-blue-500 leading-none"></i><?php echo lang('website.category_products'); ?></h2>
                </div>

                <div class="swiper-container swiper bg-white dark:bg-gray-800 px-3" id="swiper-1" data-pagination-type=""
                    data-speed="400" data-space-between="20" data-pagination="false" data-navigation="true"
                    data-autoplay="true" data-autoplay-delay="3000" data-effect="slide"
                    data-breakpoints='{"320": {"slidesPerView": 2}, "768": {"slidesPerView": 3}, "1024": {"slidesPerView": 4}, "1440": {"slidesPerView": 6}}'>
                    <div class="swiper-wrapper py-4 text-center">
                        <?php foreach ($categoryProducts as $product): ?>
                            <?php
                            $firstVarient = $product['variants'][0];
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
                                        $avgRating = round((float)($product['avg_rating'] ?? 0), 1);
                                        $ratingCount = (int)($product['rating_count'] ?? 0);
                                        
                                            $fullStars = floor($avgRating);
                                            $halfStar = ($avgRating - $fullStars) >= 0.5 ? 1 : 0;
                                            $emptyStars = 5 - $fullStars - $halfStar;
                                        ?>
                                        <div class="flex items-center gap-1.5">
                                            <div class="flex items-center gap-px">
                                                <?php for ($s = 0; $s < $fullStars; $s++): ?><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#f59e0b" class="w-3 h-3"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg><?php endfor; ?>
                                                <?php if ($halfStar): ?><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-3 h-3"><defs><linearGradient id="hs-cat-<?= $product['id'] ?>"><stop offset="50%" stop-color="#f59e0b"/><stop offset="50%" stop-color="transparent"/></linearGradient></defs><path fill="url(#hs-cat-<?= $product['id'] ?>)" stroke="#f59e0b" stroke-width="1.5" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg><?php endif; ?>
                                                <?php for ($s = 0; $s < $emptyStars; $s++): ?><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="1.5" class="w-3 h-3 dark:stroke-gray-500"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg><?php endfor; ?>
                                            </div>
                                            <span class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 leading-none"><?= $avgRating ?> (<?= $ratingCount ?>)</span>
                                        </div>
                                        
                                        <div class="flex items-center justify-between gap-1">
                                            <span class="text-[11px] text-gray-400 dark:text-gray-500 truncate leading-none"><?= esc($firstVarient['title']) ?></span>
                                            <span class="productDeliveryTime whitespace-nowrap hidden"><span class="inline-flex items-center gap-1 bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 text-[10px] font-bold px-1.5 py-0.5 rounded-md leading-none border border-orange-100 dark:border-orange-800"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 24" class="h-3.5" style="width:auto;" fill="currentColor"><circle cx="9" cy="18" r="3.8" fill="none" stroke="currentColor" stroke-width="2.2"/><circle cx="9" cy="18" r="1.2"/><circle cx="29" cy="18" r="3.8" fill="none" stroke="currentColor" stroke-width="2.2"/><circle cx="29" cy="18" r="1.2"/><path d="M9,15.5 L11,10 L17,8.5 L25,9 L29,12 L29,15.5 Q19,17 9,15.5 Z"/><line x1="23" y1="8" x2="17" y2="3.5" stroke="currentColor" stroke-width="3" stroke-linecap="round" fill="none"/><circle cx="15.5" cy="2.5" r="2.6"/><line x1="17" y1="4" x2="12.5" y2="8" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/><line x1="35" y1="9.5" x2="40" y2="9.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none"/><line x1="36" y1="12.5" x2="44" y2="12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none"/><line x1="34.5" y1="15.5" x2="42" y2="15.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" fill="none"/></svg><span class="productDeliveryTimeVal"></span>m</span></span>
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
                    <div class="swiper-pagination"></div>
                </div>

                <div class="grid gap-4 grid-cols-1 md:grid-cols-2 lg:gap-4 xl:grid-cols-5">
                </div>
                </div><!-- end category products card -->
            </section>
        <?php endif; ?>


        <?= $this->include('website/template/mobileBottomMenu') ?>
        <?= $this->include('website/template/productVarientPopup') ?>

    </main>
    <?= $this->include('website/template/shopCart') ?>
    <?= $this->include('website/template/footer') ?>
    <?= $this->include('website/template/script') ?>


    <div id="writeReviewModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-40 px-4 md:px-0">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-4">
            <div class="flex justify-between mb-6 border-b dark:border-gray-600">
                <h2 class="text-base font-semibold pb-1 dark:text-white"> </h2>
                <i class="fi fi-rr-circle-xmark text-red-800 dark:text-red-400 cursor-pointer" onclick="closeWriteReviewPopup()"></i>
            </div>
            <form class="writeReviewForm">
                <input type="hidden" name="product_id" id="product_id" />

                <!-- Star Rating -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php echo lang('website.rating'); ?>
                        <div id="ratingError" class="text-red-500 dark:text-red-400 text-sm hidden"></div>
                    </label>
                    <div class="flex space-x-2 <?= flex_direction() ?> text-2xl" id="starRating">
                        <i class="fi fi-rr-star-exclamation cursor-pointer text-gray-400 dark:text-gray-500" data-value="1"></i>
                        <i class="fi fi-rr-star-exclamation cursor-pointer text-gray-400 dark:text-gray-500" data-value="2"></i>
                        <i class="fi fi-rr-star-exclamation cursor-pointer text-gray-400 dark:text-gray-500" data-value="3"></i>
                        <i class="fi fi-rr-star-exclamation cursor-pointer text-gray-400 dark:text-gray-500" data-value="4"></i>
                        <i class="fi fi-rr-star-exclamation cursor-pointer text-gray-400 dark:text-gray-500" data-value="5"></i>
                    </div>
                    <input type="hidden" name="rating" id="rating" value="0" />
                </div>

                <!-- Title Input -->
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php echo lang('website.title'); ?> </label>
                    <input type="text" id="title" name="title" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 rounded-lg p-2 text-sm text-gray-900 focus:ring-green-600 focus:border-green-600 dark:focus:ring-green-500 dark:focus:border-green-500" placeholder="<?php echo lang('website.enter'); ?> <?php echo lang('website.title'); ?>" />
                    <div id="titleError" class="text-red-500 dark:text-red-400 text-sm mt-1 hidden"></div>
                </div>

                <!-- Notes/Message -->
                <div class="mb-4">
                    <label for="note" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php echo lang('website.review'); ?></label>
                    <textarea id="review" name="review" rows="3" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 rounded-lg p-2 text-sm text-gray-900 focus:ring-green-600 focus:border-green-600 dark:focus:ring-green-500 dark:focus:border-green-500" placeholder="<?php echo lang('website.enter'); ?> <?php echo lang('website.review'); ?>"></textarea>
                    <div id="reviewError" class="text-red-500 dark:text-red-400 text-sm mt-1 hidden"></div>
                </div>

                <!-- Save Button -->
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-600 text-white font-medium text-sm py-2 px-4 rounded-lg shadow focus:ring-2 focus:ring-green-500 focus:ring-offset-1 dark:focus:ring-offset-gray-800">
                    <?php echo lang('website.send_review'); ?>
                </button>
            </form>
        </div>
    </div>

    <script src="<?= base_url('/assets/page-script/website/productDetails.js') ?>"></script>

    <script>
        // Restore delivery time badges from localStorage on page load
        document.addEventListener('DOMContentLoaded', function() {
            try {
                const t = document.getElementById('proxyDeliveryTime')?.textContent?.trim()
                    || JSON.parse(localStorage.getItem('location') || '{}').delivery_time || '';
                if (t && typeof setProductDeliveryTime === 'function') setProductDeliveryTime(t);
            } catch(e) {}
        });
    </script>

    <script>
        // Configuration
        const zoomConfig = {
            swiperContainer: '#productSwiper',
            thumbnailsContainer: '#productThumbnails',
            zoomLensSize: 50,
            zoomWindowSize: 600,
            zoomLevel: 2.5
        };

        // Global variables
        var currentSlideIndex = 0;
        var isZooming = false;
        var zoomLens = null;
        var zoomWindow = null;
        var zoomImage = null;
        var productZoomModal = null;
        var swiper = null;

        // Initialize zoom functionality
        function initProductZoom() {
            initSwiper();
            createZoomElements();
            setupEventListeners();
            setupThumbnailNavigation();
            setupModalZoom();
        }

        // Initialize Swiper
        function initSwiper() {
            swiper = new Swiper('#productSwiper', {
                slidesPerView: 1,
                spaceBetween: 0,
                loop: false,
                grabCursor: true,
                on: {
                    slideChange: function() {
                        currentSlideIndex = this.activeIndex;
                        updateActiveThumbnail(currentSlideIndex);
                    }
                }
            });
        }

        // Create zoom lens and window elements
        function createZoomElements() {
            zoomLens = document.createElement('div');
            zoomLens.className = 'zoom-lens';
            zoomLens.style.cssText = `
                position: absolute;
                width: ${zoomConfig.zoomLensSize}px;
                height: ${zoomConfig.zoomLensSize}px;
                border: 2px solid #ccc;
                background: rgba(255,255,255,0.3);
                cursor: none;
                pointer-events: none;
                z-index: 10;
                display: none;
                border-radius: 50%;
            `;

            zoomWindow = document.createElement('div');
            zoomWindow.className = 'zoom-window';
            zoomWindow.style.cssText = `
                position: absolute;
                width: ${zoomConfig.zoomWindowSize}px;
                height: ${zoomConfig.zoomWindowSize}px;
                border: 2px solid #ccc;
                background: white;
                z-index: 20;
                display: none;
                overflow: hidden;
                box-shadow: 0 4px 20px rgba(0,0,0,0.3);
                border-radius: 8px;
            `;

            zoomImage = document.createElement('img');
            zoomImage.style.cssText = `
                position: absolute;
                pointer-events: none;
                max-width: none;
                max-height: none;
            `;
            zoomWindow.appendChild(zoomImage);

            document.body.appendChild(zoomLens);
            document.body.appendChild(zoomWindow);
        }

        // Setup event listeners for zoom functionality
        function setupEventListeners() {
            const swiperContainer = document.querySelector(zoomConfig.swiperContainer);
            const swiperSlides = swiperContainer.querySelectorAll('.swiper-slide');

            swiperSlides.forEach(function(slide, index) {
                const img = slide.querySelector('img');

                slide.addEventListener('mouseenter', function(e) {
                    startZoom(slide, img, index);
                });

                slide.addEventListener('mousemove', function(e) {
                    if (isZooming) {
                        updateZoom(e, slide, img);
                    }
                });

                slide.addEventListener('mouseleave', function() {
                    stopZoom();
                });

                slide.addEventListener('click', function(e) {
                    openModalZoom(img.src, index);
                });
            });
        }

        // Start zoom functionality
        function startZoom(slide, img, index) {
            isZooming = true;
            currentSlideIndex = index;

            zoomImage.src = img.src;

            const zoomImageWidth = img.naturalWidth || img.width;
            const zoomImageHeight = img.naturalHeight || img.height;

            zoomImage.style.width = (zoomImageWidth * zoomConfig.zoomLevel) + 'px';
            zoomImage.style.height = (zoomImageHeight * zoomConfig.zoomLevel) + 'px';

            const slideRect = slide.getBoundingClientRect();
            zoomWindow.style.left = (slideRect.right + 20) + 'px';
            zoomWindow.style.top = slideRect.top + 'px';

            zoomLens.style.display = 'block';
            zoomWindow.style.display = 'block';

            slide.style.cursor = 'zoom-in';
        }

        // Update zoom position based on mouse movement
        function updateZoom(e, slide, img) {
            const slideRect = slide.getBoundingClientRect();
            const imgRect = img.getBoundingClientRect();

            const mouseX = e.clientX - imgRect.left;
            const mouseY = e.clientY - imgRect.top;

            const lensX = mouseX - zoomConfig.zoomLensSize / 2;
            const lensY = mouseY - zoomConfig.zoomLensSize / 2;

            const maxLensX = imgRect.width - zoomConfig.zoomLensSize;
            const maxLensY = imgRect.height - zoomConfig.zoomLensSize;

            const constrainedLensX = Math.max(0, Math.min(lensX, maxLensX));
            const constrainedLensY = Math.max(0, Math.min(lensY, maxLensY));

            zoomLens.style.left = (imgRect.left + constrainedLensX) + 'px';
            zoomLens.style.top = (imgRect.top + constrainedLensY) + 'px';

            const zoomImageX = -(constrainedLensX / imgRect.width) * (zoomImage.offsetWidth - zoomConfig.zoomWindowSize);
            const zoomImageY = -(constrainedLensY / imgRect.height) * (zoomImage.offsetHeight - zoomConfig.zoomWindowSize);

            zoomImage.style.left = zoomImageX + 'px';
            zoomImage.style.top = zoomImageY + 'px';
        }

        // Stop zoom functionality
        function stopZoom() {
            isZooming = false;

            zoomLens.style.display = 'none';
            zoomWindow.style.display = 'none';

            const slides = document.querySelectorAll('.swiper-slide');
            slides.forEach(function(slide) {
                slide.style.cursor = 'default';
            });
        }

        // Setup thumbnail navigation
        function setupThumbnailNavigation() {
            const thumbnails = document.querySelectorAll('#productThumbnails .thumbnails-img');

            thumbnails.forEach(function(thumbnail, index) {
                thumbnail.addEventListener('click', function() {
                    switchToSlide(index);
                });
            });
        }

        // Switch to specific slide
        function switchToSlide(index) {
            currentSlideIndex = index;

            if (swiper && swiper.slideTo) {
                swiper.slideTo(index);
            }

            updateActiveThumbnail(index);
        }

        // Update active thumbnail styling
        function updateActiveThumbnail(index) {
            const thumbnails = document.querySelectorAll('#productThumbnails .thumbnails-img');

            thumbnails.forEach(function(thumbnail, i) {
                if (i === index) {
                    thumbnail.classList.add('active');
                } else {
                    thumbnail.classList.remove('active');
                }
            });
        }

        // Setup modal zoom functionality
        function setupModalZoom() {
            productZoomModal = document.createElement('div');
            productZoomModal.className = 'zoom-modal';
            productZoomModal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.9);
                z-index: 1000;
                display: none;
                justify-content: center;
                align-items: center;
            `;

            var modalContent = document.createElement('div');
            modalContent.className = 'zoom-modal-content';
            modalContent.style.cssText = `
                position: relative;
                max-width: 90%;
                max-height: 90%;
                background: white;
                border-radius: 8px;
                overflow: hidden;
            `;

            var modalImage = document.createElement('img');
            modalImage.className = 'zoom-modal-image';
            modalImage.style.cssText = `
                width: 100%;
                height: 100%;
                object-fit: contain;
                cursor: zoom-in;
            `;

            var closeButton = document.createElement('button');
            closeButton.innerHTML = '×';
            closeButton.className = 'zoom-modal-close';
            closeButton.style.cssText = `
                position: absolute;
                top: 10px;
                right: 15px;
                background: rgba(255,255,255,0.8);
                border: none;
                font-size: 24px;
                cursor: pointer;
                width: 30px;
                height: 30px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            `;

            modalContent.appendChild(modalImage);
            modalContent.appendChild(closeButton);
            productZoomModal.appendChild(modalContent);
            document.body.appendChild(productZoomModal);

            closeButton.addEventListener('click', closeModalZoom);
            productZoomModal.addEventListener('click', function(e) {
                if (e.target === productZoomModal) {
                    closeModalZoom();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (productZoomModal.style.display === 'flex') {
                    if (e.key === 'Escape') {
                        closeModalZoom();
                    } else if (e.key === 'ArrowLeft') {
                        navigateModal(-1);
                    } else if (e.key === 'ArrowRight') {
                        navigateModal(1);
                    }
                }
            });

            var isModalZoomed = false;
            var modalZoomLevel = 1;

            modalImage.addEventListener('click', function(e) {
                if (!isModalZoomed) {
                    modalZoomLevel = 2;
                    modalImage.style.transform = 'scale(2)';
                    modalImage.style.cursor = 'zoom-out';
                    isModalZoomed = true;
                } else {
                    modalZoomLevel = 1;
                    modalImage.style.transform = 'scale(1)';
                    modalImage.style.cursor = 'zoom-in';
                    isModalZoomed = false;
                }
            });
        }

        // Open modal zoom
        function openModalZoom(imageSrc, index) {
            var modalImage = productZoomModal.querySelector('.zoom-modal-image');
            modalImage.src = imageSrc;
            productZoomModal.style.display = 'flex';
            currentSlideIndex = index;

            modalImage.style.transform = 'scale(1)';
            modalImage.style.cursor = 'zoom-in';

            document.body.style.overflow = 'hidden';
        }

        // Close modal zoom
        function closeModalZoom() {
            productZoomModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Navigate within modal
        function navigateModal(direction) {
            var slides = document.querySelectorAll('.swiper-slide');
            var totalSlides = slides.length;

            currentSlideIndex = (currentSlideIndex + direction + totalSlides) % totalSlides;

            var newImageSrc = slides[currentSlideIndex].querySelector('img').src;
            var modalImage = productZoomModal.querySelector('.zoom-modal-image');
            modalImage.src = newImageSrc;

            modalImage.style.transform = 'scale(1)';
            modalImage.style.cursor = 'zoom-in';

            switchToSlide(currentSlideIndex);
        }

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            initProductZoom();
        });
    </script>
    <script>
        function updateVariantUI(element) {
            // Define active and inactive classes
            const activeClasses = ['border-green-600', 'bg-green-50', 'dark:bg-green-900/30', 'dark:border-green-500', 'shadow-sm', 'active'];
            const inactiveClasses = ['bg-gray-50', 'dark:bg-gray-700', 'border-gray-200', 'dark:border-gray-600', 'hover:border-green-500'];

            // Reset all variants
            document.querySelectorAll('[id^="variant-"]').forEach(el => {
                el.classList.remove(...activeClasses);
                el.classList.add(...inactiveClasses);
            });

            // Set active variant
            element.classList.remove(...inactiveClasses);
            element.classList.add(...activeClasses);
        }
    </script>
</body>

</html>