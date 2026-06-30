<!doctype html>
<html lang="<?= session()->get('site_lang') ?? 'en' ?>" dir="<?= dir_attribute() ?>">

<head>
    <?= $this->include('website/template/style') ?>
    <title><?= $settings['business_name'] ?></title>
</head>

<body class="bg-gray-50 dark:bg-gray-900">
    <?= $this->include('website/template/header') ?>
    <main class="max-w-7xl mx-auto">

        <div class="mt-2 md:mt-4 md:container md:mx-auto px-3">
            <div class="flex items-center gap-3 bg-white dark:bg-gray-800 mb-4 px-5 py-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                    <i class="fi fi-rr-box-alt text-indigo-600 dark:text-indigo-400 text-lg leading-none"></i>
                </div>
                <div>
                    <h1 class="text-base font-bold dark:text-white leading-tight"><?php echo lang('website.product'); ?></h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Browse and filter products</p>
                </div>
            </div>

            <div class="container">
                <div class="flex lg:gap-8">
                    <?php if (!$is_mobile): ?>
                        <aside class="lg:w-1/4 mb-6 md:">
                            <div class="md:hidden hidden lg:block">
                                <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                                    
                                    <div class="flex items-center gap-2 px-4 py-3 border-b border-dashed border-gray-100 dark:border-gray-700">
                                        <i class="fi fi-rr-filter text-green-500 leading-none"></i>
                                        <h3 class="font-bold text-sm dark:text-white">Filters</h3>
                                    </div>
                                    <div class="flex flex-col gap-4 p-4">
                                        <?php if (count($categorys)): ?>
                                            <div class="flex flex-col gap-3">
                                                <h5 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide"><?php echo lang('website.category'); ?></h5>
                                                <div class="flex flex-col gap-4">
                                                    <div class="flex flex-col gap-2">
                                                        <?php foreach ($categorys as $category): ?>
                                                            <div class="relative flex gap-2 items-center">
                                                                <input class="w-4 h-4 text-green-600 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-green-600 focus:outline-none focus:ring-2 category_<?= $category['slug'] ?>" onchange="applyFilter('category')" type="checkbox">
                                                                <label class="text-gray-800 dark:text-gray-300" for="<?= $category['slug'] ?>"><?= $category['category_name'] ?></label>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="border-gray-100 dark:border-gray-700">
                                        <?php endif; ?>
                                        <?php if (count($brands)): ?>
                                            <div class="flex flex-col gap-3">
                                                <h5 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide"><?php echo lang('website.brand'); ?></h5>
                                                <div class="flex flex-col gap-4">
                                                    <div class="flex flex-col gap-2">
                                                        <?php foreach ($brands as $brand): ?>
                                                            <div class="relative flex gap-2 items-center">
                                                                <input class="w-4 h-4 text-green-600 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-green-600 focus:outline-none focus:ring-2 brand_<?= $brand['slug'] ?>" onchange="applyFilter('brand')" type="checkbox">
                                                                <label class="text-gray-800 dark:text-gray-300" for="<?= $brand['slug'] ?>"><?= $brand['brand'] ?></label>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="border-gray-100 dark:border-gray-700">
                                        <?php endif; ?>
                                        <?php if (count($sellers)): ?>
                                            <div class="flex flex-col gap-3">
                                                <h5 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide"><?php echo lang('website.seller'); ?></h5>
                                                <div class="flex flex-col gap-4">
                                                    <div class="flex flex-col gap-2">
                                                        <?php foreach ($sellers as $seller): ?>
                                                            <div class="relative flex gap-2 items-center">
                                                                <input class="w-4 h-4 text-green-600 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-green-600 focus:outline-none focus:ring-2 seller_<?= $seller['slug'] ?>" onchange="applyFilter('seller')" type="checkbox">
                                                                <label class="text-gray-800 dark:text-gray-300" for="<?= $seller['slug'] ?>"><?= $seller['store_name'] ?></label>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="border-gray-100 dark:border-gray-700">
                                        <?php endif; ?>

                                        <div class="flex flex-col gap-3">
                                            <h5 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide"><?php echo lang('website.price_range'); ?></h5>

                                            <div id="price-range-slider" class="mt-2">
                                                <input type="hidden" name="from" id="slider-from" />
                                                <input type="hidden" name="to" id="slider-to" />
                                                <div>
                                                    <div class="relative w-11/12 h-1 bg-indigo-200 dark:bg-indigo-900 rounded-full mx-auto" id="slider-track">
                                                        <div id="slider-range" class="absolute bg-red-400 h-full"></div>
                                                        <div id="slider-handle-from" class="absolute -ml-2 -top-2 cursor-pointer rounded-full bg-red-600 w-5 h-5 z-30 shadow-lg"></div>
                                                        <div id="slider-handle-to" class="absolute -ml-2 -top-2 cursor-pointer rounded-full bg-red-600 w-5 h-5 z-30 shadow-lg"></div>
                                                    </div>
                                                    <div class="mt-2 flex select-none">
                                                        <span id="slider-value-from" class="flex-1 text-sm dark:text-gray-300"></span>
                                                        <span id="slider-value-to" class="text-sm dark:text-gray-300"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <button onclick="fetchProductList();" class="rounded-xl bg-green-600 hover:bg-green-700 text-white text-sm px-3 py-2 w-full transition"><?php echo lang('website.apply_price_range'); ?></button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </aside>
                    <?php endif; ?>

                    <section class="w-full bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                        

                        <div class="px-5 py-4 border-b border-dashed border-gray-100 dark:border-gray-700">
                            <div class="flex flex-col md:flex-row justify-between lg:items-center gap-3">
                                <div>
                                    <p class="text-sm dark:text-gray-300">
                                        <span class="text-gray-900 dark:text-white" id="product_count">0</span>
                                        <?php echo lang('website.products_found'); ?>
                                    </p>
                                </div>
                                <div class="flex flex-col md:flex-row justify-between md:items-center gap-3">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <button id="listViewButton" class="text-gray-600 dark:text-gray-300 p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition" onclick="setProductListView()">
                                                <i class="fi fi-rr-list"></i>
                                            </button>
                                            <button id="appViewButton" class="text-gray-600 dark:text-gray-300 p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition" onclick="setProductAppView()">
                                                <i class="fi fi-rr-apps"></i>
                                            </button>
                                            <button id="gridViewButton" class="text-gray-600 dark:text-gray-300 hidden md:block p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition" onclick="setProductGridView()">
                                                <i class="fi fi-rr-grid"></i>
                                            </button>
                                        </div>
                                        <div class="ml-3 lg:hidden">
                                            <button onclick="openProductFilterPopup()" class="text-sm btn inline-flex p-2 items-center gap-x-2 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 border-gray-300 dark:border-gray-600 border rounded-xl disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-gray-700 hover:border-gray-700 active:bg-gray-700 active:border-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600">
                                                <i class="fi fi-rr-filter"></i>
                                                <?php echo lang('website.filters'); ?>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="flex">
                                        <select class="text-sm p-2 block w-full border text-gray-700 dark:text-gray-200 border-gray-200 dark:border-gray-600 rounded-xl focus:border-green-600 focus:ring-green-600 disabled:opacity-50 disabled:pointer-events-none bg-white dark:bg-gray-700" id="productSort" onchange="applyFilter('sort')">
                                            <?php foreach ($productSorts as $productSort): ?>
                                                <option value="<?= $productSort['id'] ?>"><?= $productSort['sort'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-4">
                            <div class="grid grid-cols-1 gap-4 hidden" id="productListView"></div>
                            <div class="grid xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 grid-cols-2 gap-4" id="productAppView"></div>
                            <div class="grid xl:grid-cols-5 lg:grid-cols-4 md:grid-cols-4 grid-cols-2 gap-4 hidden" id="productGridView"></div>

                            <div id="noProductAvilable" class="flex flex-col gap-4 text-center hidden">
                                <img
                                    src="<?= base_url('assets/dist/img/no-data.png') ?>"
                                    alt="Coming Soon"
                                    class="mx-auto w-2/3 sm:w-1/3 rounded-2xl dark:brightness-75" />
                                <div class="text-sm text-gray-700 dark:text-gray-400">
                                    <?php echo lang('website.no_product_available'); ?>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

        </div>

        <?= $this->include('website/template/mobileBottomMenu') ?>
        <?= $this->include('website/template/productVarientPopup') ?>

    </main>

    <div id="productFilterModel" class="fixed inset-0 flex items-end md:items-center justify-center bg-black bg-opacity-50 dark:bg-opacity-70 hidden z-40">
        <div class="bg-white dark:bg-gray-800 rounded-t-2xl md:rounded-2xl border border-gray-100 dark:border-gray-700 shadow-lg w-full h-[70vh] md:max-w-2xl md:h-[70vh]">
            <div class="flex flex-col h-full p-4 pb-0">
                <!-- Header with Title and Close Button -->
                <div class="flex justify-between border-b dark:border-gray-700 mb-2">
                    <h5 class="text-lg font-semibold text-gray-800 dark:text-white pb-2 flex items-center gap-2">
                        <i class="fi fi-rr-filter text-green-500 leading-none"></i>
                        <?php echo lang('website.filters'); ?>
                    </h5>
                    <button type="button" class="p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition text-reset dark:text-gray-300" onclick="closeProductFilterPopup()">
                        <i class="fi fi-tr-x"></i>
                    </button>
                </div>

                <!-- Scrollable Content Area -->
                <?php if ($is_mobile): ?>
                    <div class="flex flex-col gap-4 overflow-y-auto flex-grow">
                        <?php if (count($categorys)): ?>
                            <div class="flex flex-col gap-3">
                                <h5 class="font-semibold dark:text-white"><?php echo lang('website.category'); ?></h5>
                                <div class="flex flex-col gap-4">
                                    <div class="flex flex-col gap-2">
                                        <?php foreach ($categorys as $category): ?>
                                            <div class="relative flex gap-2 items-center">
                                                <input class="w-4 h-4 text-green-600 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-green-600 focus:outline-none focus:ring-2 category_<?= $category['slug'] ?>" onchange="applyFilter('category')" type="checkbox">
                                                <label class="text-gray-800 dark:text-gray-300 text-sm" for="<?= $category['slug'] ?>"><?= $category['category_name'] ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <hr class="dark:border-gray-700">
                        <?php endif; ?>
                        <?php if (count($brands)): ?>
                            <div class="flex flex-col gap-3">
                                <h5 class="font-semibold dark:text-white"><?php echo lang('website.brand'); ?></h5>
                                <div class="flex flex-col gap-4">
                                    <div class="flex flex-col gap-2">
                                        <?php foreach ($brands as $brand): ?>
                                            <div class="relative flex gap-2 items-center">
                                                <input class="w-4 h-4 text-green-600 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-green-600 focus:outline-none focus:ring-2 brand_<?= $brand['slug'] ?>" onchange="applyFilter('brand')" type="checkbox">
                                                <label class="text-gray-800 dark:text-gray-300 text-sm" for="<?= $brand['slug'] ?>"><?= $brand['brand'] ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <hr class="dark:border-gray-700">
                        <?php endif; ?>
                        <?php if (count($sellers)): ?>
                            <div class="flex flex-col gap-3">
                                <h5 class="font-semibold dark:text-white"><?php echo lang('website.seller'); ?></h5>
                                <div class="flex flex-col gap-4">
                                    <div class="flex flex-col gap-2">
                                        <?php foreach ($sellers as $seller): ?>
                                            <div class="relative flex gap-2 items-center">
                                                <input class="w-4 h-4 text-green-600 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-green-600 focus:outline-none focus:ring-2 seller_<?= $seller['slug'] ?>" onchange="applyFilter('seller')" type="checkbox">
                                                <label class="text-gray-800 dark:text-gray-300 text-sm" for="<?= $seller['slug'] ?>"><?= $seller['store_name'] ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <hr class="dark:border-gray-700">
                        <?php endif; ?>
                        <div class="flex flex-col gap-3">
                            <h5 class="dark:text-white"><?php echo lang('website.price_range'); ?></h5>

                            <div id="price-range-slider" class="mt-2">
                                <input type="hidden" name="from" id="slider-from" />
                                <input type="hidden" name="to" id="slider-to" />
                                <div>
                                    <div class="relative w-11/12 h-1 bg-indigo-200 dark:bg-indigo-900 rounded-full mx-auto" id="slider-track">
                                        <div id="slider-range" class="absolute bg-red-400 h-full"></div>
                                        <div id="slider-handle-from" class="absolute -ml-2 -top-2 cursor-pointer rounded-full bg-red-600 w-5 h-5 z-30 shadow-lg"></div>
                                        <div id="slider-handle-to" class="absolute -ml-2 -top-2 cursor-pointer rounded-full bg-red-600 w-5 h-5 z-30 shadow-lg"></div>
                                    </div>
                                    <div class="mt-2 flex select-none">
                                        <span id="slider-value-from" class="flex-1 text-sm dark:text-gray-300"></span>
                                        <span id="slider-value-to" class="text-sm dark:text-gray-300"></span>
                                    </div>
                                </div>
                            </div>
                            <button onclick="fetchProductList();" class="rounded-xl bg-green-600 hover:bg-green-700 text-white text-sm px-3 py-2 w-full transition"><?php echo lang('website.apply_price_range'); ?></button>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <?= $this->include('website/template/shopCart') ?>
    <?= $this->include('website/template/footer') ?>
    <?= $this->include('website/template/script') ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/2.8.2/alpine.js"></script>

    <?= $this->include('website/template/productScript') ?>

</body>

</html>
