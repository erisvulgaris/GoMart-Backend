<!doctype html>
<html lang="<?= session()->get('site_lang') ?? 'en' ?>" dir="<?= dir_attribute() ?>">

<head>
    <?= $this->include('website/template/style') ?>

    <title>Garden Fresh Delight Salad -
        <?= $settings['business_name'] ?>
    </title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />

    <!-- Canonical URL -->

    <!-- Robots Meta Tag -->
    <meta name="robots" content="index, follow" />

    <!-- Open Graph Meta Tags (For Facebook, LinkedIn, etc.) -->
    <meta property="og:url" content="<?= current_url(); ?>" />
    <meta property="og:type" content="product" />
    <meta property="og:site_name" content="<?= $settings['business_name'] ?>" />

    <!-- Twitter Card Meta Tags (For Twitter Sharing) -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@yourtwitterhandle" />

    <!-- Schema Markup (Structured Data for Google Rich Snippets) -->

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
        }

        .main-product-image {
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
            aspect-ratio: 1;
            object-fit: cover;
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
    </style>
</head>

<body class="bg-gray-100">
    <?= $this->include('website/template/header') ?>
    <main class="max-w-7xl mx-auto">

        <section class="mt-2 md:mt-4 md:container md:mx-auto px-3">
            <div class="flex flex-wrap">
                <div class="lg:w-1/3 w-full">
                    <!-- Main Swiper Container -->
                    <div class="swiper-container swiper mb-4" id="productSwiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide zoom-container" data-index="1">
                                <div class="image-wrapper bg-white" style="position: relative; overflow: hidden;">
                                    <img src="https://health-fitness-calc-gs.vercel.app/static/media/Garden%20Fresh%20Delight.jpeg.9e8ebcd6eba5922316d8.jpg"
                                        alt="Product Image " class="main-product-image w-full h-auto object-contain"
                                        style="display: block; max-width: 100%; height: auto;" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thumbnails -->
                    <div class="thumbnails flex gap-3 mt-4" id="productThumbnails">
                        <div class="thumbnail-wrapper w-1/4">
                            <div class="thumbnails-img cursor-pointer border-2 border-transparent rounded-lg overflow-hidden transition-all duration-300 hover:border-gray-300"
                                data-index="1">
                                <img src="https://health-fitness-calc-gs.vercel.app/static/media/Garden%20Fresh%20Delight.jpeg.9e8ebcd6eba5922316d8.jpg"
                                    alt="Thumbnail 1" class="thumbnail-image w-full h-auto object-cover rounded-lg" />
                            </div>
                        </div>
                    </div>
                </div>


                <div class="lg:w-2/3 w-full lg:pl-4">
                    <div class="px-4 mt-6 md:mt-0 bg-white rounded-lg py-4">
                        <div class="flex flex-col gap-4">
                            <!-- content -->
                            <a href="#!" class="text-sm block text-gray-500">Bowls</a>
                            <!-- heading -->
                            <div class="flex flex-col">
                                <div class="flex flex-row justify-between gap-3">
                                    <h1 class="text-lg md:text-xl lg:text-2xl font-semibold mb-2">Fresh Harvest Crunch
                                    </h1>
                                    <i class="fi fi-rr-share-square md:hidden p-1 border rounded-full shadow-sm self-center w-7 h-7"
                                        id="shareButton"></i>
                                </div>

                                <div class="flex flex-col gap-4">
                                    <div class="flex items-center gap-2">
                                        <small class="text-yellow-500 inline-flex items-center">
                                            <i class="fi fi-rr-star-exclamation"></i>
                                            <i class="fi fi-rr-star-exclamation"></i>
                                            <i class="fi fi-rr-star-exclamation"></i><i
                                                class="fi fi-rr-star-exclamation"></i><i
                                                class="fi fi-rr-star-exclamation"></i>
                                        </small>
                                        <a href="#" class="text-green-600 text-sm">(5 reviews)</a>
                                    </div>

                                    <hr>
                                    <div class="flex flex-col md:flex-row gap-6">

                                        <!-- Details -->
                                        <div class="flex-1">
                                            <p class="text-gray-700 mb-4">
                                                Enjoy our Fresh Harvest Crunch, a vibrant salad rich in vitamin C and
                                                potassium. Perfectly portioned for six, each 500g serving offers a
                                                nutritious and flavorful bite.
                                            </p>
                                            <ul class="text-gray-800 space-y-1 mb-4">
                                                <li><strong>No. of Servings:</strong> 6</li>
                                                <li><strong>Serving Size:</strong> 500 g</li>
                                                <li><strong>Total Weight:</strong> 3000 g</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="flex flex-wrap gap-2">

                                        <div
                                            class="border border-green-700 bg-[#F7FFF9] rounded-lg p-4 shadow-md cursor-pointer active">
                                            <div class="flex flex-col items-center">
                                                <p class="text-sm font-semibold">3000 G</p>
                                                <div class="flex justify-between w-full mt-2 gap-4">
                                                    <!-- Discounted Price -->
                                                    <p class="text-green-700 font-bold">Rs 12.50</p>
                                                    <p class="text-xs text-gray-400 font-semibold self-center">
                                                        <span class="line-through font-bold">Rs 15.00</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>

                            <div class="gap-6">
                                <div class="fresh-harvest-crunch-mainbtndiv">


                                    <!-- Second variant with "Add to Cart" button -->
                                    <div class="fresh-harvest-crunch-mainbtndiv-2">
                                        <button type="button"
                                            class="text-sm px-2 py-1 rounded-lg items-center gap-x-1 bg-green-700 text-white border-green-700 disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-green-700 hover:border-green-700 btn-sm">
                                            <i class="fi fi-rr-shopping-cart"></i>
                                            <span>Add</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="px-4 mt-4 bg-white rounded-lg py-4"></div> -->
                </div>
            </div>
        </section>
        <section class="mt-2 md:mt-4 md:container md:mx-auto px-3 ">
            <div class="flex flex-wrap bg-white rounded-lg">
                <div class="w-full p-2 md:p-4">
                    <div class="">


                        <!-- Nutrient Table -->
                        <div class="mt-6">
                            <h3 class="font-semibold text-gray-800 mb-3">Nutrient Values:</h3>
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse">
                                    <thead>
                                        <tr class=" ">
                                            <th class="px-4 py-2 text-left">NUTRIENT</th>
                                            <th class="px-4 py-2 text-left">VALUE</th>
                                        </tr>
                                    </thead>
                                    <tbody class="">
                                        <tr>
                                            <td class="px-4 py-2 border-t">Total Calories</td>
                                            <td class="px-4 py-2 border-t">2082 kcal</td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-2 border-t">Total Fiber</td>
                                            <td class="px-4 py-2 border-t">86 g</td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-2 border-t">Total Protein</td>
                                            <td class="px-4 py-2 border-t">44 g</td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-2 border-t">Total Vitamin C</td>
                                            <td class="px-4 py-2 border-t">750 mg</td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-2 border-t">Total Vitamin A</td>
                                            <td class="px-4 py-2 border-t">1397 µg</td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-2 border-t">Total Calcium</td>
                                            <td class="px-4 py-2 border-t">786 mg</td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-2 border-t">Total Potassium</td>
                                            <td class="px-4 py-2 border-t">7528 mg</td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-2 border-t">Total Magnesium</td>
                                            <td class="px-4 py-2 border-t">570 mg</td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-2 border-t">Total Iron</td>
                                            <td class="px-4 py-2 border-t">27 mg</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    <hr>

                    <div class="flex flex-col gap-4">
                        <div class="font-medium text-sm">
                            <i class="fi fi-rr-restock"></i>
                            <?php echo lang('website.is_returnable'); ?> : <span class="text-green-600">yes</span><span
                                class="text-green-600">(in 1
                                Days)</span>
                        </div>
                        <ul class="flex items-center text-sm gap-4 mt-3">
                            <li class="font-medium"><i class="fi fi-rr-share-square"></i>
                                <?php echo lang('website.share_product'); ?> :
                            </li>
                            <li>
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= current_url(); ?>"
                                    target="_blank">
                                    <i class="fi fi-brands-facebook text-xl text-blue-600"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://wa.me/?text=Check%20out%20this%20product:%20<?= current_url(); ?>"
                                    target="_blank">
                                    <i class="fi fi-brands-whatsapp text-xl text-green-600"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://t.me/share/url?url=<?= current_url(); ?>&text=Check%20out%20this%20product!"
                                    target="_blank">
                                    <i class="fi fi-brands-telegram text-xl text-cyan-500"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#!" onclick="copyLink()" class="">
                                    <i class="fi fi-rr-link-alt text-xl font-bold"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-2 md:mt-4 md:container md:mx-auto px-3 ">
            <div class="flex flex-wrap bg-white rounded-lg">
                <div class="w-full p-2 md:p-4">

                    <ul class="nav pl-0 gap-3 pb-6 border-b flex">

                        <li class="nav-item">
                            <button
                                class="inline-block py-2 px-3 border-2 border-gray-300 rounded-lg font-semibold nav-link active-tab"
                                data-bs-target="#ingredients-tab-pane" type="button" onclick="showTab(this)">
                                Ingridents
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                                class="inline-block py-2 px-3 border-2 border-gray-300 rounded-lg font-semibold nav-link"
                                data-bs-target="#reviews-tab-pane" type="button" onclick="showTab(this)">
                                <?php echo lang('website.rating_&_reviews'); ?>
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <div id="ingredients-tab-pane" class="tab-pane block">
                            <div class="mt-4">
  <div class="flex flex-wrap gap-6">
    <div class="w-full">
      <div class="flex flex-col gap-6 mb-6">
        <div class="flex flex-col gap-2">
          <h3 class="text-sm font-semibold">Ingredients</h3>
        </div>
        <div class="flex flex-col gap-3">
          <!-- Scrollable Container -->
          <div class="overflow-x-auto">
            <div class="flex space-x-4 py-4">

              
              <!-- Ingredient -->
              <div class="flex flex-col items-center text-center min-w-[6rem] flex-shrink-0">
                <img src="https://health-fitness-calc-gs.vercel.app/static/media/Garden%20Fresh%20Delight.jpeg.9e8ebcd6eba5922316d8.jpg"
                     alt="Avocado"
                     class="w-40 aspect-square rounded-full object-cover border-2 border-green-500 shadow-md">
                <p class="mt-2 text-sm font-semibold text-gray-700">Avocado</p>
              </div>

              <!-- Repeat for other items -->
              <div class="flex flex-col items-center text-center min-w-[6rem] flex-shrink-0">
                <img src="https://health-fitness-calc-gs.vercel.app/static/media/Garden%20Fresh%20Delight.jpeg.9e8ebcd6eba5922316d8.jpg"
                     alt="Tomato"
                     class="w-40 aspect-square rounded-full object-cover border-2 border-red-500 shadow-md">
                <p class="mt-2 text-sm font-semibold text-gray-700">Tomato</p>
              </div>

              <div class="flex flex-col items-center text-center min-w-[6rem] flex-shrink-0">
                <img src="https://health-fitness-calc-gs.vercel.app/static/media/Garden%20Fresh%20Delight.jpeg.9e8ebcd6eba5922316d8.jpg"
                     alt="Tomato"
                     class="w-40 aspect-square rounded-full object-cover border-2 border-red-500 shadow-md">
                <p class="mt-2 text-sm font-semibold text-gray-700">Tomato</p>
              </div>

              <div class="flex flex-col items-center text-center min-w-[6rem] flex-shrink-0">
                <img src="https://health-fitness-calc-gs.vercel.app/static/media/Garden%20Fresh%20Delight.jpeg.9e8ebcd6eba5922316d8.jpg"
                     alt="Tomato"
                     class="w-40 aspect-square rounded-full object-cover border-2 border-red-500 shadow-md">
                <p class="mt-2 text-sm font-semibold text-gray-700">Tomato</p>
              </div>

              <div class="flex flex-col items-center text-center min-w-[6rem] flex-shrink-0">
                <img src="https://health-fitness-calc-gs.vercel.app/static/media/Garden%20Fresh%20Delight.jpeg.9e8ebcd6eba5922316d8.jpg"
                     alt="Tomato"
                     class="w-40 aspect-square rounded-full object-cover border-2 border-red-500 shadow-md">
                <p class="mt-2 text-sm font-semibold text-gray-700">Tomato</p>
              </div>

              <div class="flex flex-col items-center text-center min-w-[6rem] flex-shrink-0">
                <img src="https://health-fitness-calc-gs.vercel.app/static/media/Garden%20Fresh%20Delight.jpeg.9e8ebcd6eba5922316d8.jpg"
                     alt="Tomato"
                     class="w-40 aspect-square rounded-full object-cover border-2 border-red-500 shadow-md">
                <p class="mt-2 text-sm font-semibold text-gray-700">Tomato</p>
              </div>

              <div class="flex flex-col items-center text-center min-w-[6rem] flex-shrink-0">
                <img src="https://health-fitness-calc-gs.vercel.app/static/media/Garden%20Fresh%20Delight.jpeg.9e8ebcd6eba5922316d8.jpg"
                     alt="Tomato"
                     class="w-40 aspect-square rounded-full object-cover border-2 border-red-500 shadow-md">
                <p class="mt-2 text-sm font-semibold text-gray-700">Tomato</p>
              </div>

              <!-- ... continue others ... -->

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>




                        </div>
                        <div id="reviews-tab-pane" class="tab-pane hidden">
                            <div class="mt-4">
                                <div class="flex flex-wrap md:flex-nowrap gap-6">
                                    <div class="md:w-1/3 w-full">
                                        <div class="flex flex-col gap-6 mb-6">
                                            <div class="flex flex-col gap-2">
                                                <h3 class="text-sm font-semibold">
                                                    <?php echo lang('website.customer_reviews'); ?> <span
                                                        class="text-xs text-green-600">(5 Review)</span>
                                                </h3>
                                                <div class="lg:flex items-center gap-6 ">

                                                    <small class="text-yellow-500 inline-flex items-center">
                                                        <i class="fi fi-rr-star-exclamation"></i><i
                                                            class="fi fi-rr-star-exclamation"></i><i
                                                            class="fi fi-rr-star-exclamation"></i><i
                                                            class="fi fi-rr-star-exclamation"></i><i
                                                            class="fi fi-rr-star-exclamation"></i>
                                                    </small>

                                                    <span>
                                                        4 /5</span>

                                                </div>
                                            </div>
                                            <div class="flex flex-col gap-3">
                                                <!-- progress -->
                                                <div class="flex items-center gap-4">
                                                    <div class="text-gray-500 flex items-center gap-2">
                                                        <span class="inline-block align-middle text-gray-500">5</span>
                                                        <span class="text-yellow-500">
                                                            <i class="fi fi-sc-star"></i>
                                                        </span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                        <div class="bg-yellow-500 h-1.5 rounded-full" style="width: 0%">
                                                        </div>
                                                    </div>
                                                    <span class="text-gray-500">
                                                        <?php echo 0 ?>%
                                                    </span>
                                                </div>

                                                <!-- progress -->
                                                <div class="flex items-center gap-4">
                                                    <div class="text-gray-500 flex items-center gap-2">
                                                        <span class="inline-block align-middle text-gray-500">4</span>
                                                        <span class="text-yellow-500">
                                                            <i class="fi fi-sc-star"></i>
                                                        </span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                        <div class="bg-yellow-500 h-1.5 rounded-full"
                                                            style="width: <?php echo  0 ?>%"></div>
                                                    </div>
                                                    <span class="text-gray-500">
                                                        <?php echo  0 ?>%
                                                    </span>
                                                </div>
                                                <!-- progress -->
                                                <div class="flex items-center gap-4">
                                                    <div class="text-gray-500 flex items-center gap-2">
                                                        <span class="inline-block align-middle text-gray-500">3</span>
                                                        <span class="text-yellow-500">
                                                            <i class="fi fi-sc-star"></i>
                                                        </span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                        <div class="bg-yellow-500 h-1.5 rounded-full"
                                                            style="width: <?php echo 0 ?>%"></div>
                                                    </div>
                                                    <span class="text-gray-500">
                                                        <?php echo 0 ?>%
                                                    </span>
                                                </div>
                                                <!-- progress -->
                                                <div class="flex items-center gap-4">
                                                    <div class="text-gray-500 flex items-center gap-2">
                                                        <span class="inline-block align-middle text-gray-500">2</span>
                                                        <span class="text-yellow-500">
                                                            <i class="fi fi-sc-star"></i>
                                                        </span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                        <div class="bg-yellow-500 h-1.5 rounded-full"
                                                            style="width: <?php echo 0 ?>%"></div>
                                                    </div>
                                                    <span class="text-gray-500">
                                                        <?php echo 0 ?>%
                                                    </span>
                                                </div>
                                                <!-- progress -->
                                                <div class="flex items-center gap-4">
                                                    <div class="text-gray-500 flex items-center gap-2">
                                                        <span class="inline-block align-middle text-gray-500">1</span>
                                                        <span class="text-yellow-500">
                                                            <i class="fi fi-sc-star"></i>
                                                        </span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                        <div class="bg-yellow-500 h-1.5 rounded-full"
                                                            style="width: <?php echo 0 ?>%"></div>
                                                    </div>
                                                    <span class="text-gray-500">
                                                        <?php echo  0 ?>%
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex flex-col gap-4">
                                                <div class="flex flex-col">
                                                    <h3 class="text-sm font-semibold">
                                                        <?php echo lang('website.review_this_product'); ?>
                                                    </h3>
                                                    <p>
                                                        <?php echo lang('website.share_your_thoughts_with_other_customers'); ?>
                                                    </p>
                                                </div>
                                                <button type="button"
                                                    class="btn inline-flex text-center items-center gap-x-2 p-2 rounded-lg bg-white text-gray-800 border-gray-300 border disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-gray-700 hover:border-gray-700 active:bg-gray-700 active:border-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-300">
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
                                                    <h3 class="text-sm font-semibold">
                                                        <?php echo lang('website.review'); ?>
                                                    </h3>
                                                </div>
                                            </div>
                                            <div class="flex flex-col gap-5 ">

                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>




        <?= $this->include('website/template/mobileBottomMenu') ?>
        <?= $this->include('website/template/productVarientPopup') ?>

    </main>
    <?= $this->include('website/template/shopCart') ?>
    <?= $this->include('website/template/footer') ?>
    <?= $this->include('website/template/script') ?>


    <script>
        // Initialize Swiper
        const swiper1 = new Swiper('#swiper-1', {
            // Basic settings
            slidesPerView: 2,
            spaceBetween: 20,
            speed: 400,

            // Autoplay
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },

            // Navigation arrows
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },

            // Pagination
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },

            // Responsive breakpoints
            breakpoints: {
                320: {
                    slidesPerView: 2,
                    spaceBetween: 15
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 20
                },
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 20
                },
                1440: {
                    slidesPerView: 5,
                    spaceBetween: 25
                }
            },

            // Loop mode
            loop: true,
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
        var swiper = null; // This will hold our Swiper instance

        // Initialize zoom functionality
        function initProductZoom() {
            // Initialize Swiper first
            initSwiper();

            // Then initialize zoom functionality
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
                    slideChange: function () {
                        currentSlideIndex = this.activeIndex;
                        updateActiveThumbnail(currentSlideIndex);
                    }
                }
            });
        }

        // Create zoom lens and window elements
        function createZoomElements() {
            // Create zoom lens
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

            // Create zoom window
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

            // Create zoom image inside zoom window
            zoomImage = document.createElement('img');
            zoomImage.style.cssText = `
                position: absolute;
                pointer-events: none;
                max-width: none;
                max-height: none;
            `;
            zoomWindow.appendChild(zoomImage);

            // Append to body
            document.body.appendChild(zoomLens);
            document.body.appendChild(zoomWindow);
        }

        // Setup event listeners for zoom functionality
        function setupEventListeners() {
            const swiperContainer = document.querySelector(zoomConfig.swiperContainer);
            const swiperSlides = swiperContainer.querySelectorAll('.swiper-slide');

            swiperSlides.forEach(function (slide, index) {
                const img = slide.querySelector('img');

                // Mouse enter - show zoom elements
                slide.addEventListener('mouseenter', function (e) {
                    startZoom(slide, img, index);
                });

                // Mouse move - update zoom position
                slide.addEventListener('mousemove', function (e) {
                    if (isZooming) {
                        updateZoom(e, slide, img);
                    }
                });

                // Mouse leave - hide zoom elements
                slide.addEventListener('mouseleave', function () {
                    stopZoom();
                });

                // Click - open modal zoom
                slide.addEventListener('click', function (e) {
                    openModalZoom(img.src, index);
                });
            });
        }

        // Start zoom functionality
        function startZoom(slide, img, index) {
            isZooming = true;
            currentSlideIndex = index;

            // Set zoom image source
            zoomImage.src = img.src;

            // Calculate zoom image size
            const zoomImageWidth = img.naturalWidth || img.width;
            const zoomImageHeight = img.naturalHeight || img.height;

            zoomImage.style.width = (zoomImageWidth * zoomConfig.zoomLevel) + 'px';
            zoomImage.style.height = (zoomImageHeight * zoomConfig.zoomLevel) + 'px';

            // Position zoom window
            const slideRect = slide.getBoundingClientRect();
            zoomWindow.style.left = (slideRect.right + 20) + 'px';
            zoomWindow.style.top = slideRect.top + 'px';

            // Show zoom elements
            zoomLens.style.display = 'block';
            zoomWindow.style.display = 'block';

            // Add zoom cursor to slide
            slide.style.cursor = 'zoom-in';
        }

        // Update zoom position based on mouse movement
        function updateZoom(e, slide, img) {
            const slideRect = slide.getBoundingClientRect();
            const imgRect = img.getBoundingClientRect();

            // Calculate mouse position relative to image
            const mouseX = e.clientX - imgRect.left;
            const mouseY = e.clientY - imgRect.top;

            // Calculate lens position
            const lensX = mouseX - zoomConfig.zoomLensSize / 2;
            const lensY = mouseY - zoomConfig.zoomLensSize / 2;

            // Constrain lens within image bounds
            const maxLensX = imgRect.width - zoomConfig.zoomLensSize;
            const maxLensY = imgRect.height - zoomConfig.zoomLensSize;

            const constrainedLensX = Math.max(0, Math.min(lensX, maxLensX));
            const constrainedLensY = Math.max(0, Math.min(lensY, maxLensY));

            // Position lens
            zoomLens.style.left = (imgRect.left + constrainedLensX) + 'px';
            zoomLens.style.top = (imgRect.top + constrainedLensY) + 'px';

            // Calculate zoom image position
            const zoomImageX = -(constrainedLensX / imgRect.width) * (zoomImage.offsetWidth - zoomConfig.zoomWindowSize);
            const zoomImageY = -(constrainedLensY / imgRect.height) * (zoomImage.offsetHeight - zoomConfig.zoomWindowSize);

            // Position zoom image
            zoomImage.style.left = zoomImageX + 'px';
            zoomImage.style.top = zoomImageY + 'px';
        }

        // Stop zoom functionality
        function stopZoom() {
            isZooming = false;

            // Hide zoom elements
            zoomLens.style.display = 'none';
            zoomWindow.style.display = 'none';

            // Reset cursor
            const slides = document.querySelectorAll('.swiper-slide');
            slides.forEach(function (slide) {
                slide.style.cursor = 'default';
            });
        }

        // Setup thumbnail navigation
        function setupThumbnailNavigation() {
            const thumbnails = document.querySelectorAll('#productThumbnails .thumbnails-img');

            thumbnails.forEach(function (thumbnail, index) {
                thumbnail.addEventListener('click', function () {
                    switchToSlide(index);
                });
            });
        }

        // Switch to specific slide - FIXED VERSION
        function switchToSlide(index) {
            currentSlideIndex = index;

            // Use Swiper's slideTo method
            if (swiper && swiper.slideTo) {
                swiper.slideTo(index);
            }

            // Update active thumbnail
            updateActiveThumbnail(index);
        }

        // Update active thumbnail styling
        function updateActiveThumbnail(index) {
            const thumbnails = document.querySelectorAll('#productThumbnails .thumbnails-img');

            thumbnails.forEach(function (thumbnail, i) {
                if (i === index) {
                    thumbnail.classList.add('active');
                } else {
                    thumbnail.classList.remove('active');
                }
            });
        }

        // Setup modal zoom functionality
        function setupModalZoom() {
            // Create modal structure
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

            // Modal event listeners
            closeButton.addEventListener('click', closeModalZoom);
            productZoomModal.addEventListener('click', function (e) {
                if (e.target === productZoomModal) {
                    closeModalZoom();
                }
            });

            // Keyboard navigation
            document.addEventListener('keydown', function (e) {
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

            // Zoom functionality within modal
            var isModalZoomed = false;
            var modalZoomLevel = 1;

            modalImage.addEventListener('click', function (e) {
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

            // Reset zoom
            modalImage.style.transform = 'scale(1)';
            modalImage.style.cursor = 'zoom-in';

            // Prevent body scroll
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

            // Reset zoom
            modalImage.style.transform = 'scale(1)';
            modalImage.style.cursor = 'zoom-in';

            // Update main swiper and thumbnails
            switchToSlide(currentSlideIndex);
        }

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', function () {
            initProductZoom();
        });
    </script>
</body>

</html>