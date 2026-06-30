<!doctype html>
<html lang="<?= session()->get('site_lang') ?? 'en' ?>" dir="<?= dir_attribute() ?>">

<head>
    <?= $this->include('website/template/style') ?>
    <title><?= $settings['business_name'] ?></title>
</head>

<body class="bg-gray-100 dark:bg-gray-950">
    <?= $this->include('website/template/header') ?>
    <main class="max-w-7xl mx-auto">

        <div class="mt-2 md:mt-4 md:container md:mx-auto px-3">
            <div class="relative flex flex-col min-w-0 rounded-lg break-words bg-white dark:bg-gray-800 p-4 mb-6">
                <div class="flex justify-between">
                    <h1 class="text-lg font-medium z-10 dark:text-white"><?php echo lang('website.buy'); ?> <?= $category['category_name'] ?? '' ?> <?php echo lang('website.online'); ?></h1>
                </div>
            </div>

            <div class="grid grid-cols-9 gap-3 md:gap-5 -mx-3 md:mx-0">
                <div class="col-span-2">
                    <div class="bg-white dark:bg-gray-800 flex flex-col items-center rounded-lg md:hidden">
                        <?php
                        foreach ($subcategories as $subcategory): ?>
                            <?php if ($subcategory['slug'] === $subcategorySlug): ?>
                                <a href="<?= base_url('subcategory/' . $subcategory['slug']) ?>" class="py-2 w-full">
                                    <div class="w-full h-full">
                                        <div class="relative flex flex-col items-center justify-center gap-1">
                                            <div class="relative h-12 w-12 rounded-lg overflow-hidden flex items-center justify-center bg-gray-100 dark:bg-gray-700">
                                                <img class="h-10 w-10 object-contain" src="<?= base_url($subcategory['img']) ?>" alt="<?= $subcategory['name'] ?>">
                                            </div>
                                            <div class="text-black dark:text-gray-200 font-normal text-sm text-center w-4/5 break-words"><?= $subcategory['name'] ?></div>
                                            <div class="absolute right-0 h-full w-1 rounded-bl-lg rounded-tl-lg bg-green-700 dark:bg-green-500"></div>
                                        </div>
                                    </div>
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('subcategory/' . $subcategory['slug']) ?>" class="py-2 w-full">
                                    <div class="w-full h-full">
                                        <div class="relative flex flex-col items-center justify-center gap-1">
                                            <div class="relative h-12 w-12 rounded-lg overflow-hidden flex items-center justify-center bg-gray-100 dark:bg-gray-700">
                                                <img class="h-10 w-10 object-contain" src="<?= base_url($subcategory['img']) ?>" alt="<?= $subcategory['name'] ?>">
                                            </div>
                                            <div class="text-black dark:text-gray-400 font-normal text-xs text-center w-4/5 break-words"><?= $subcategory['name'] ?></div>
                                        </div>
                                    </div>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>

                    <div class="bg-white dark:bg-gray-800 flex flex-col items-center rounded-lg hidden md:block">
                        <ul class="category list-none">
                            <nav class="category-list space-y-2">
                                <?php
                                foreach ($subcategories as $subcategory): ?>
                                    <?php if ($subcategory['slug'] === $subcategorySlug): ?>
                                        <a href="<?= base_url('subcategory/' . $subcategory['slug']) ?>" class="flex items-center p-2 bg-green-100 dark:bg-green-900 border-l-4 border-green-700 dark:border-green-500">
                                            <div class="w-10 h-10 flex-shrink-0">
                                                <img src="<?= base_url($subcategory['img']) ?>" alt="<?= $subcategory['name'] ?>" class="w-full h-full object-contain dark:brightness-90">
                                            </div>
                                            <div class="ml-3 text-black dark:text-white font-semibold"><?= $subcategory['name'] ?></div>
                                            <?php $first = false; ?>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= base_url('subcategory/' . $subcategory['slug']) ?>" class="flex items-center p-2 dark:hover:bg-gray-700">
                                            <div class="w-10 h-10 flex-shrink-0">
                                                <img src="<?= base_url($subcategory['img']) ?>" alt="<?= $subcategory['name'] ?>" class="w-full h-full object-contain dark:brightness-90">
                                            </div>
                                            <div class="ml-3 text-black dark:text-gray-300 font-normal"><?= $subcategory['name'] ?></div>
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                            </nav>
                        </ul>
                    </div>
                </div>

                <div class="col-span-7">
                    <div class="flex flex-col md:flex-row justify-between lg:items-center mb-2 gap-3 p-4 bg-white dark:bg-gray-800 rounded-lg">
                        <div>
                            <p class="text-sm dark:text-gray-300">
                                <span class="text-gray-900 dark:text-white" id="product_count">0</span>
                                <?php echo lang('website.products_found'); ?>
                            </p>
                        </div>
                        <div class="flex flex-col md:flex-row justify-between md:items-center gap-3 ">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <button id="listViewButton" class="text-gray-600 dark:text-gray-300" onclick="setProductListView()">
                                        <i class="fi fi-rr-list"></i>
                                    </button>
                                    <button id="appViewButton" class="text-gray-600 dark:text-gray-300" onclick="setProductAppView()">
                                        <i class="fi fi-rr-apps"></i>
                                    </button>
                                    <button id="gridViewButton" class="text-gray-600 dark:text-gray-300 hidden md:block" onclick="setProductGridView()">
                                        <i class="fi fi-rr-grid"></i>
                                    </button>
                                </div>
                                <div class="ml-3">
                                    <select class="text-sm p-2 block w-full border text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 rounded-lg focus:border-green-600 focus:ring-green-600 disabled:opacity-50 disabled:pointer-events-none bg-white dark:bg-gray-700" id="productSort" onchange="applyFilter('sort')">
                                        <?php foreach ($productSorts as $productSort): ?>
                                            <option value="<?= $productSort['id'] ?>"><?= $productSort['sort'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 hidden" id="productListView"></div>
                    <div class="grid xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 grid-cols-2 gap-4" id="productAppView"></div>
                    <div class="grid xl:grid-cols-5 lg:grid-cols-4 md:grid-cols-4 grid-cols-2 gap-4 hidden" id="productGridView"></div>

                    <div id="noProductAvilable" class="flex flex-col gap-4 text-center hidden">
                        <img
                            src="<?= base_url('assets/dist/img/no-data.png') ?>"
                            alt="Coming Soon"
                            class="mx-auto w-2/3 sm:w-1/3 rounded-lg dark:brightness-75" />
                        <div class="text-sm text-gray-700 dark:text-gray-400">
                            <?php echo lang('website.no_product_available'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php 
            if(isset($category['is_it_have_warning']) && $category['is_it_have_warning'] == 1){ ?>
                <div id="ageModal" class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 flex items-center justify-center z-50">
                  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 max-w-md w-full text-center">
                    <h2 class="text-2xl font-semibold mb-4 dark:text-white">Please make sure...</h2>
                    
                    <div class="space-y-4 text-left dark:text-gray-300">
                      <div class="flex items-start space-x-3"> 
                      <?php echo nl2br($category['warning_content'] ?? '') ?>
                      </div>
                
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">
                          We are bound to report your account in case of any transgressions!
                          <a href="/terms-condition" class="text-green-600 dark:text-green-400 underline">Read Terms & Conditions</a>
                        </p>
                    
                        <div class="flex justify-center gap-4 mt-6">
                      <button 
                        onclick="window.history.back()" 
                        class="border border-gray-400 dark:border-gray-600 rounded-lg px-6 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" >
                        Cancel
                      </button>
                    
                      <button onclick="document.getElementById('ageModal').style.display='none'" 
                        class="bg-green-600 dark:bg-green-700 text-white rounded-lg px-6 py-2 hover:bg-green-700 dark:hover:bg-green-600" >
                        Yes, I confirm
                      </button>
                    </div>
                  </div>
                </div>
            </div>
            <?php }
            ?>

        <?= $this->include('website/template/mobileBottomMenu') ?>
        <?= $this->include('website/template/productVarientPopup') ?>
    </main>
    <?= $this->include('website/template/shopCart') ?>
    <?= $this->include('website/template/footer') ?>
    <?= $this->include('website/template/script') ?>

    <?= $this->include('website/template/subcategoryProductListScript') ?>

</body>

</html>