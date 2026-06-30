<!doctype html>
<html lang="<?= session()->get('site_lang') ?? 'en' ?>" dir="<?= dir_attribute() ?>">

<head>
    <?= $this->include('website/template/style') ?>
    <title><?= $settings['business_name'] ?></title>
</head>

<body class="bg-gray-50 dark:bg-gray-900">
    <?= $this->include('website/template/header') ?>
    <main class="max-w-7xl mx-auto">

        <!-- Home Screen Tabs -->
        <?php if (!empty($allHomeScreens) && count($allHomeScreens) > 1): ?>
            <section class="mt-2 md:mt-3 md:container md:mx-auto px-3">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                    <div class="flex items-center gap-1 px-2 py-2 overflow-x-auto scrollbar-hide" id="homeScreenTabs">
                        <?php foreach ($allHomeScreens as $screen): ?>
                            <button type="button"
                                class="home-screen-tab flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium whitespace-nowrap transition-all duration-200 flex-shrink-0
                                    <?= ($screen['id'] == ($homeScreen['id'] ?? 0)) ? 'bg-green-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' ?>"
                                data-screen-id="<?= $screen['id'] ?>"
                                data-is-default="<?= $screen['is_default'] ?>">
                                <?php if (!empty($screen['tab_icon'])): ?>
                                    <img src="<?= base_url($screen['tab_icon']) ?>" alt="" class="w-5 h-5 object-contain <?= ($screen['id'] == ($homeScreen['id'] ?? 0)) ? 'brightness-0 invert' : '' ?>" />
                                <?php endif; ?>
                                <span><?= esc($screen['name']) ?></span>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Home Screen Content Container -->
        <div id="homeScreenContent">
            <?= view('website/home/home_screen_content', [
                'settings' => $settings,
                'country' => $country,
                'dbSections' => $dbSections,
                'headerBanner' => $headerBanner,
                'footerBanner' => $footerBanner ?? [],
                'user' => $user ?? [],
            ]) ?>
        </div>

        <?= $this->include('website/template/mobileBottomMenu') ?>
        <?= $this->include('website/template/productVarientPopup') ?>

    </main>
    <?= $this->include('website/template/shopCart') ?>
    <?= $this->include('website/template/footer') ?>
    <?= $this->include('website/template/script') ?>

    <!-- Home Screen Tab Switching -->
    <script>
    (function() {
        var activeScreenId = <?= json_encode((int)($homeScreen['id'] ?? 0)) ?>;
        var screenCache = {};
        // Cache the initial screen content
        screenCache[activeScreenId] = document.getElementById('homeScreenContent').innerHTML;

        document.querySelectorAll('.home-screen-tab').forEach(function(tab) {
            tab.addEventListener('click', function() {
                var screenId = parseInt(this.getAttribute('data-screen-id'));
                if (screenId === activeScreenId) return;

                // Update tab styles
                document.querySelectorAll('.home-screen-tab').forEach(function(t) {
                    t.classList.remove('bg-green-600', 'text-white', 'shadow-md');
                    t.classList.add('text-gray-600', 'dark:text-gray-300', 'hover:bg-gray-100', 'dark:hover:bg-gray-700');
                    var icon = t.querySelector('img');
                    if (icon) icon.classList.remove('brightness-0', 'invert');
                });
                this.classList.add('bg-green-600', 'text-white', 'shadow-md');
                this.classList.remove('text-gray-600', 'dark:text-gray-300', 'hover:bg-gray-100', 'dark:hover:bg-gray-700');
                var activeIcon = this.querySelector('img');
                if (activeIcon) activeIcon.classList.add('brightness-0', 'invert');

                activeScreenId = screenId;

                // Check cache first
                if (screenCache[screenId]) {
                    document.getElementById('homeScreenContent').innerHTML = screenCache[screenId];
                    reinitSwipers();
                    return;
                }

                // Show loading spinner
                document.getElementById('homeScreenContent').innerHTML =
                    '<div class="flex justify-center items-center py-20">' +
                    '<div class="animate-spin rounded-full h-10 w-10 border-b-2 border-green-600"></div>' +
                    '</div>';

                // AJAX fetch
                var formData = new FormData();
                formData.append('home_screen_id', screenId);
                formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

                fetch('<?= base_url("home/screen-data") ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    if (data.status === 'success') {
                        screenCache[screenId] = data.html;
                        document.getElementById('homeScreenContent').innerHTML = data.html;
                        reinitSwipers();
                    } else {
                        document.getElementById('homeScreenContent').innerHTML =
                            '<div class="text-center py-20 text-gray-500">' + (data.message || 'Failed to load content') + '</div>';
                    }
                })
                .catch(function() {
                    document.getElementById('homeScreenContent').innerHTML =
                        '<div class="text-center py-20 text-gray-500">Failed to load content. Please try again.</div>';
                });
            });
        });

        // Re-initialize Swiper instances after AJAX content load
        function reinitSwipers() {
            var container = document.getElementById('homeScreenContent');
            if (!container) return;
            var swiperEls = container.querySelectorAll('.swiper-container.swiper');
            swiperEls.forEach(function(el) {
                var config = {
                    speed: parseInt(el.getAttribute('data-speed') || 400),
                    spaceBetween: parseInt(el.getAttribute('data-space-between') || 20),
                    effect: el.getAttribute('data-effect') || 'slide',
                    loop: false,
                };
                if (el.getAttribute('data-pagination') === 'true') {
                    config.pagination = { el: el.querySelector('.swiper-pagination'), clickable: true };
                }
                if (el.getAttribute('data-navigation') === 'true') {
                    config.navigation = {
                        nextEl: el.querySelector('.swiper-button-next'),
                        prevEl: el.querySelector('.swiper-button-prev')
                    };
                }
                if (el.getAttribute('data-autoplay') === 'true') {
                    config.autoplay = {
                        delay: parseInt(el.getAttribute('data-autoplay-delay') || 3000),
                        disableOnInteraction: false
                    };
                }
                var bp = el.getAttribute('data-breakpoints');
                if (bp) {
                    try { config.breakpoints = JSON.parse(bp); } catch(e) {}
                }
                new Swiper(el, config);
            });
        }
    })();
    </script>

    <style>
    /* Hide scrollbar for tab container */
    #homeScreenTabs::-webkit-scrollbar { display: none; }
    #homeScreenTabs { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</body>

</html>
