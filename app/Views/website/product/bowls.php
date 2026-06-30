<!doctype html>
<html lang="<?= session()->get('site_lang') ?? 'en' ?>" dir="<?= dir_attribute() ?>">

<head>
    <?= $this->include('website/template/style') ?>
    <title><?= $settings['business_name'] ?></title>
</head>

<body class="bg-gray-100">
    <?= $this->include('website/template/header') ?>
    <main class="max-w-7xl mx-auto">

        <div class="mt-2 md:mt-4 md:container md:mx-auto px-3">
            <div class="relative flex flex-col min-w-0 rounded-lg break-words bg-white p-4 mb-6">
                <div class="flex justify-between">
                    <h1 class="text-lg font-medium z-10"><?php echo lang('website.product'); ?></h1>
                </div>
            </div>

            <div class="container">
                <div class="flex lg:gap-8">
                    <section class="w-full bg-white rounded-lg p-4">

                        <div class="flex flex-col md:flex-row justify-between lg:items-center mb-6 gap-3">
                            <div>
                                <p class="text-sm">
                                    <span class="text-gray-900" id="product_count">0</span>
                                    <?php echo lang('website.products_found'); ?>
                                </p>
                            </div>
                            <div class="flex flex-col md:flex-row justify-between md:items-center gap-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <button id="listViewButton" class="text-gray-600" onclick="setProductListView()">
                                            <i class="fi fi-rr-list"></i>
                                        </button>
                                        <button id="appViewButton" class="text-gray-600" onclick="setProductAppView()">
                                            <i class="fi fi-rr-apps"></i>
                                        </button>
                                        <button id="gridViewButton" class="text-gray-600 hidden md:block" onclick="setProductGridView()">
                                            <i class="fi fi-rr-grid"></i>
                                        </button>
                                    </div>
                                    <div class="ml-3 lg:hidden">
                                        <button onclick="openProductFilterPopup()" class="text-sm btn inline-flex p-2 items-center gap-x-2 bg-white text-gray-800 border-gray-300 border rounded-lg disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-gray-700 hover:border-gray-700 active:bg-gray-700 active:border-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-300">
                                            <i class="fi fi-rr-filter"></i>
                                            <?php echo lang('website.filters'); ?>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex">
                                    <select class="text-sm p-2 block w-full border text-gray-700 border-gray-300 rounded-lg focus:border-green-600 focus:ring-green-600 disabled:opacity-50 disabled:pointer-events-none" id="productSort" onchange="applyFilter('sort')">
                                        <?php foreach ($productSorts as $productSort): ?>
                                            <option value="<?= $productSort['id'] ?>"><?= $productSort['sort'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 hidden" id="productListView">

                        </div>
                        <div class="grid xl:grid-cols-3 lg:grid-cols-3 md:grid-cols-2 grid-cols-2 gap-4" id="productAppView">

                            <!-- Card -->
                            <div class=" rounded-xl shadow border overflow-hidden">
                                <div class="flex-auto p-2">
                                    <div class="text-center relative flex justify-center">
                                        <div class="absolute -top-2 left-1">
                                            <svg width="29" height="28" viewBox="0 0 29 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M28.9499 0C28.3999 0 27.9361 1.44696 27.9361 2.60412V27.9718L24.5708 25.9718L21.2055 27.9718L17.8402 25.9718L14.4749 27.9718L11.1096 25.9718L7.74436 27.9718L4.37907 25.9718L1.01378 27.9718V2.6037C1.01378 1.44655 0.549931 0 0 0H28.9499Z" fill="#15803D"></path>
                                            </svg>
                                        </div>
                                        <span class="absolute text-xs text-white font-bold left-[6px] -top-2 break-words">11%</span>
                                        <span class="absolute text-xs text-white font-bold left-[8px] top-1 break-words">off</span>
                                        <a href="/bowls/details">
                                            <img src="https://health-fitness-calc-gs.vercel.app/static/media/Garden%20Fresh%20Delight.jpeg.9e8ebcd6eba5922316d8.jpg" alt="Maggi 2 - Minute Instant Noodles (Pack of 12)" class="w-4/5 h-auto ml-auto mr-auto">
                                        </a>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-800">Garden Fresh Delight Salad</h3>
                                    <p class="text-sm font-semibold text-gray-700 mt-2">Ingredients:</p>
                                    <div class="flex flex-wrap gap-2 mt-1">
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">Asparagus</span>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">Baby Corn</span>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">Cherry tomatoes</span>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">English Cucumber</span>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">Beetroot</span>
                                    </div>

                                    <div class="mt-3 text-sm text-gray-700 space-y-1">
                                        <p>Total Protein: 53 g</p>
                                        <p>Total Calories: 1074 kcal</p>
                                        <p>Total Weight: 3000 g</p>
                                    </div>

                                    <div class="mt-2 text-sm">
                                        <span class="font-semibold">Also rich in :</span>
                                        <span class="bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded ml-1">Vitamin C</span>
                                        <span class="bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded ml-1">Vitamin A</span>
                                    </div>

                                    <div class="flex justify-between items-center mt-2">
                                        <div class="flex flex-col">
                                            <span class="text-sm text-gray-900 font-semibold">₹160.00</span>
                                            <span class="line-through text-gray-500 text-xs">₹180.00</span>
                                        </div>
                                        <div class="maggi-2---minute-instant-noodles-pack-of-12-mainbtndiv-3">

                                            <button type="button" class="text-sm px-2 py-1 rounded-lg items-center gap-x-1 bg-green-700 text-white border-green-700 disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-green-700 hover:border-green-700 btn-sm maggi-2---minute-instant-noodles-pack-of-12-3">
                                                <i class="fi fi-rr-shopping-cart"></i>
                                                <span>Add</span>
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class=" rounded-xl shadow border overflow-hidden">
                                <div class="flex-auto p-2">
                                    <div class="text-center relative flex justify-center">
                                        <div class="absolute -top-2 left-1">
                                            <svg width="29" height="28" viewBox="0 0 29 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M28.9499 0C28.3999 0 27.9361 1.44696 27.9361 2.60412V27.9718L24.5708 25.9718L21.2055 27.9718L17.8402 25.9718L14.4749 27.9718L11.1096 25.9718L7.74436 27.9718L4.37907 25.9718L1.01378 27.9718V2.6037C1.01378 1.44655 0.549931 0 0 0H28.9499Z" fill="#15803D"></path>
                                            </svg>
                                        </div>
                                        <span class="absolute text-xs text-white font-bold left-[6px] -top-2 break-words">11%</span>
                                        <span class="absolute text-xs text-white font-bold left-[8px] top-1 break-words">off</span>
                                        <a href="/bowls/details">
                                            <img src="https://health-fitness-calc-gs.vercel.app/static/media/Fresh%20Harvest%20Crunch.jpeg.a2293f4e5742dd606391.jpg" alt="Maggi 2 - Minute Instant Noodles (Pack of 12)" class="w-4/5 h-auto ml-auto mr-auto">
                                        </a>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-800">Fresh Harvest Crunch</h3>
                                    <p class="text-sm font-semibold text-gray-700 mt-2">Ingredients:</p>
                                    <div class="flex flex-wrap gap-2 mt-1">
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">Asparagus</span>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">Baby Corn</span>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">Cherry tomatoes</span>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">English Cucumber</span>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">Beetroot</span>
                                    </div>

                                    <div class="mt-3 text-sm text-gray-700 space-y-1">
                                        <p>Total Protein: 53 g</p>
                                        <p>Total Calories: 1074 kcal</p>
                                        <p>Total Weight: 3000 g</p>
                                    </div>

                                    <div class="mt-2 text-sm">
                                        <span class="font-semibold">Also rich in :</span>
                                        <span class="bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded ml-1">Vitamin C</span>
                                        <span class="bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded ml-1">Vitamin A</span>
                                    </div>

                                    <div class="flex justify-between items-center mt-2">
                                        <div class="flex flex-col">
                                            <span class="text-sm text-gray-900 font-semibold">₹160.00</span>
                                            <span class="line-through text-gray-500 text-xs">₹180.00</span>
                                        </div>
                                        <div class="maggi-2---minute-instant-noodles-pack-of-12-mainbtndiv-3">

                                            <button type="button" class="text-sm px-2 py-1 rounded-lg items-center gap-x-1 bg-green-700 text-white border-green-700 disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-green-700 hover:border-green-700 btn-sm maggi-2---minute-instant-noodles-pack-of-12-3">
                                                <i class="fi fi-rr-shopping-cart"></i>
                                                <span>Add</span>
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class=" rounded-xl shadow border overflow-hidden">
                                <div class="flex-auto p-2">
                                    <div class="text-center relative flex justify-center">
                                        <div class="absolute -top-2 left-1">
                                            <svg width="29" height="28" viewBox="0 0 29 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M28.9499 0C28.3999 0 27.9361 1.44696 27.9361 2.60412V27.9718L24.5708 25.9718L21.2055 27.9718L17.8402 25.9718L14.4749 27.9718L11.1096 25.9718L7.74436 27.9718L4.37907 25.9718L1.01378 27.9718V2.6037C1.01378 1.44655 0.549931 0 0 0H28.9499Z" fill="#15803D"></path>
                                            </svg>
                                        </div>
                                        <span class="absolute text-xs text-white font-bold left-[6px] -top-2 break-words">11%</span>
                                        <span class="absolute text-xs text-white font-bold left-[8px] top-1 break-words">off</span>
                                        <a href="/bowls/details">
                                            <img src="https://health-fitness-calc-gs.vercel.app/static/media/Rainbow%20Beet%20salad.jpeg.f47a5f981856ba6f3e32.jpg" alt="Maggi 2 - Minute Instant Noodles (Pack of 12)" class="w-4/5 h-auto ml-auto mr-auto">
                                        </a>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-800">Rainbow Beet Salad
</h3>
                                    <p class="text-sm font-semibold text-gray-700 mt-2">Ingredients:</p>
                                    <div class="flex flex-wrap gap-2 mt-1">
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">Asparagus</span>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">Baby Corn</span>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">Cherry tomatoes</span>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">English Cucumber</span>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">Beetroot</span>
                                    </div>

                                    <div class="mt-3 text-sm text-gray-700 space-y-1">
                                        <p>Total Protein: 53 g</p>
                                        <p>Total Calories: 1074 kcal</p>
                                        <p>Total Weight: 3000 g</p>
                                    </div>

                                    <div class="mt-2 text-sm">
                                        <span class="font-semibold">Also rich in :</span>
                                        <span class="bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded ml-1">Vitamin C</span>
                                        <span class="bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded ml-1">Vitamin A</span>
                                    </div>

                                    <div class="flex justify-between items-center mt-2">
                                        <div class="flex flex-col">
                                            <span class="text-sm text-gray-900 font-semibold">₹160.00</span>
                                            <span class="line-through text-gray-500 text-xs">₹180.00</span>
                                        </div>
                                        <div class="maggi-2---minute-instant-noodles-pack-of-12-mainbtndiv-3">

                                            <button type="button" class="text-sm px-2 py-1 rounded-lg items-center gap-x-1 bg-green-700 text-white border-green-700 disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-green-700 hover:border-green-700 btn-sm maggi-2---minute-instant-noodles-pack-of-12-3">
                                                <i class="fi fi-rr-shopping-cart"></i>
                                                <span>Add</span>
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class=" rounded-xl shadow border overflow-hidden">
                                <div class="flex-auto p-2">
                                    <div class="text-center relative flex justify-center">
                                        <div class="absolute -top-2 left-1">
                                            <svg width="29" height="28" viewBox="0 0 29 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M28.9499 0C28.3999 0 27.9361 1.44696 27.9361 2.60412V27.9718L24.5708 25.9718L21.2055 27.9718L17.8402 25.9718L14.4749 27.9718L11.1096 25.9718L7.74436 27.9718L4.37907 25.9718L1.01378 27.9718V2.6037C1.01378 1.44655 0.549931 0 0 0H28.9499Z" fill="#15803D"></path>
                                            </svg>
                                        </div>
                                        <span class="absolute text-xs text-white font-bold left-[6px] -top-2 break-words">11%</span>
                                        <span class="absolute text-xs text-white font-bold left-[8px] top-1 break-words">off</span>
                                        <a href="/bowls/details">
                                            <img src="https://health-fitness-calc-gs.vercel.app/static/media/Golden%20corn%20fiesta.jpeg.48fa8358d53f1474cc4c.jpg" alt="Maggi 2 - Minute Instant Noodles (Pack of 12)" class="w-4/5 h-auto ml-auto mr-auto">
                                        </a>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-800">Garden Fresh Delight Salad</h3>
                                    <p class="text-sm font-semibold text-gray-700 mt-2">Ingredients:</p>
                                    <div class="flex flex-wrap gap-2 mt-1">
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">Asparagus</span>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">Baby Corn</span>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">Cherry tomatoes</span>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">English Cucumber</span>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">Beetroot</span>
                                    </div>

                                    <div class="mt-3 text-sm text-gray-700 space-y-1">
                                        <p>Total Protein: 53 g</p>
                                        <p>Total Calories: 1074 kcal</p>
                                        <p>Total Weight: 3000 g</p>
                                    </div>

                                    <div class="mt-2 text-sm">
                                        <span class="font-semibold">Also rich in :</span>
                                        <span class="bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded ml-1">Vitamin C</span>
                                        <span class="bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded ml-1">Vitamin A</span>
                                    </div>

                                    <div class="flex justify-between items-center mt-2">
                                        <div class="flex flex-col">
                                            <span class="text-sm text-gray-900 font-semibold">₹160.00</span>
                                            <span class="line-through text-gray-500 text-xs">₹180.00</span>
                                        </div>
                                        <div class="maggi-2---minute-instant-noodles-pack-of-12-mainbtndiv-3">

                                            <button type="button" class="text-sm px-2 py-1 rounded-lg items-center gap-x-1 bg-green-700 text-white border-green-700 disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-green-700 hover:border-green-700 btn-sm maggi-2---minute-instant-noodles-pack-of-12-3">
                                                <i class="fi fi-rr-shopping-cart"></i>
                                                <span>Add</span>
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class=" rounded-xl shadow border overflow-hidden">
                                <div class="flex-auto p-2">
                                    <div class="text-center relative flex justify-center">
                                        <div class="absolute -top-2 left-1">
                                            <svg width="29" height="28" viewBox="0 0 29 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M28.9499 0C28.3999 0 27.9361 1.44696 27.9361 2.60412V27.9718L24.5708 25.9718L21.2055 27.9718L17.8402 25.9718L14.4749 27.9718L11.1096 25.9718L7.74436 27.9718L4.37907 25.9718L1.01378 27.9718V2.6037C1.01378 1.44655 0.549931 0 0 0H28.9499Z" fill="#15803D"></path>
                                            </svg>
                                        </div>
                                        <span class="absolute text-xs text-white font-bold left-[6px] -top-2 break-words">11%</span>
                                        <span class="absolute text-xs text-white font-bold left-[8px] top-1 break-words">off</span>
                                        <a href="/bowls/details">
                                            <img src="https://health-fitness-calc-gs.vercel.app/static/media/Sweet%20and%20sunny%20salad.jpeg.499b835403b6dff99a64.jpg" alt="Maggi 2 - Minute Instant Noodles (Pack of 12)" class="w-4/5 h-auto ml-auto mr-auto">
                                        </a>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-800">Sweet and Sunny Salad</h3>
                                    <p class="text-sm font-semibold text-gray-700 mt-2">Ingredients:</p>
                                    <div class="flex flex-wrap gap-2 mt-1">
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">Asparagus</span>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">Baby Corn</span>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">Cherry tomatoes</span>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">English Cucumber</span>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">Beetroot</span>
                                    </div>

                                    <div class="mt-3 text-sm text-gray-700 space-y-1">
                                        <p>Total Protein: 53 g</p>
                                        <p>Total Calories: 1074 kcal</p>
                                        <p>Total Weight: 3000 g</p>
                                    </div>

                                    <div class="mt-2 text-sm">
                                        <span class="font-semibold">Also rich in :</span>
                                        <span class="bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded ml-1">Vitamin C</span>
                                        <span class="bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded ml-1">Vitamin A</span>
                                    </div>

                                    <div class="flex justify-between items-center mt-2">
                                        <div class="flex flex-col">
                                            <span class="text-sm text-gray-900 font-semibold">₹160.00</span>
                                            <span class="line-through text-gray-500 text-xs">₹180.00</span>
                                        </div>
                                        <div class="maggi-2---minute-instant-noodles-pack-of-12-mainbtndiv-3">

                                            <button type="button" class="text-sm px-2 py-1 rounded-lg items-center gap-x-1 bg-green-700 text-white border-green-700 disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-green-700 hover:border-green-700 btn-sm maggi-2---minute-instant-noodles-pack-of-12-3">
                                                <i class="fi fi-rr-shopping-cart"></i>
                                                <span>Add</span>
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class=" rounded-xl shadow border overflow-hidden">
                                <div class="flex-auto p-2">
                                    <div class="text-center relative flex justify-center">
                                        <div class="absolute -top-2 left-1">
                                            <svg width="29" height="28" viewBox="0 0 29 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M28.9499 0C28.3999 0 27.9361 1.44696 27.9361 2.60412V27.9718L24.5708 25.9718L21.2055 27.9718L17.8402 25.9718L14.4749 27.9718L11.1096 25.9718L7.74436 27.9718L4.37907 25.9718L1.01378 27.9718V2.6037C1.01378 1.44655 0.549931 0 0 0H28.9499Z" fill="#15803D"></path>
                                            </svg>
                                        </div>
                                        <span class="absolute text-xs text-white font-bold left-[6px] -top-2 break-words">11%</span>
                                        <span class="absolute text-xs text-white font-bold left-[8px] top-1 break-words">off</span>
                                        <a href="/bowls/details">
                                            <img src="https://health-fitness-calc-gs.vercel.app/static/media/Crunchy%20veggie%20delight.jpeg.2f214066a87861097748.jpg" alt="Maggi 2 - Minute Instant Noodles (Pack of 12)" class="w-4/5 h-auto ml-auto mr-auto">
                                        </a>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-800">Crunchy Veggie Delight</h3>
                                    <p class="text-sm font-semibold text-gray-700 mt-2">Ingredients:</p>
                                    <div class="flex flex-wrap gap-2 mt-1">
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">Asparagus</span>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">Baby Corn</span>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">Cherry tomatoes</span>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">English Cucumber</span>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">Beetroot</span>
                                    </div>

                                    <div class="mt-3 text-sm text-gray-700 space-y-1">
                                        <p>Total Protein: 53 g</p>
                                        <p>Total Calories: 1074 kcal</p>
                                        <p>Total Weight: 3000 g</p>
                                    </div>

                                    <div class="mt-2 text-sm">
                                        <span class="font-semibold">Also rich in :</span>
                                        <span class="bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded ml-1">Vitamin C</span>
                                        <span class="bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded ml-1">Vitamin A</span>
                                    </div>

                                    <div class="flex justify-between items-center mt-2">
                                        <div class="flex flex-col">
                                            <span class="text-sm text-gray-900 font-semibold">₹160.00</span>
                                            <span class="line-through text-gray-500 text-xs">₹180.00</span>
                                        </div>
                                        <div class="maggi-2---minute-instant-noodles-pack-of-12-mainbtndiv-3">

                                            <button type="button" class="text-sm px-2 py-1 rounded-lg items-center gap-x-1 bg-green-700 text-white border-green-700 disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-green-700 hover:border-green-700 btn-sm maggi-2---minute-instant-noodles-pack-of-12-3">
                                                <i class="fi fi-rr-shopping-cart"></i>
                                                <span>Add</span>
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grid xl:grid-cols-5 lg:grid-cols-4 md:grid-cols-4 grid-cols-2 gap-4 hidden" id="productGridView"></div>

                        <div id="noProductAvilable" class="flex flex-col gap-4 text-center hidden">
                            <img
                                src="<?= base_url('assets/dist/img/no-data.png') ?>"
                                alt="Coming Soon"
                                class="mx-auto w-2/3 sm:w-1/3 rounded-lg" />
                            <div class="text-sm text-gray-700">
                                <?php echo lang('website.no_product_available'); ?>
                            </div>
                        </div>
                    </section>
                </div>
            </div>


        </div>


        <?= $this->include('website/template/mobileBottomMenu') ?>
        <?= $this->include('website/template/productVarientPopup') ?>

    </main>



    <?= $this->include('website/template/shopCart') ?>
    <?= $this->include('website/template/footer') ?>
    <?= $this->include('website/template/script') ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/2.8.2/alpine.js"></script>


</body>

</html>