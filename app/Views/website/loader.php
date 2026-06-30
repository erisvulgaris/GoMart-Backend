<!doctype html>
<html lang="<?= session()->get('site_lang') ?? 'en' ?>" dir="<?= dir_attribute() ?>">

<head>
    <?= $this->include('website/template/style') ?>
    <title><?= $settings['business_name'] ?></title>
</head>

<body class="bg-gray-100 dark:bg-gray-900">
    <?= $this->include('website/template/header') ?>
    <main class="flex flex-col gap-4 w-full p-4 bg-white dark:bg-gray-800 rounded-lg">
        <section class="mt-2">
            <div class="w-full h-40 bg-gray-300 dark:bg-gray-700 animate-pulse rounded-lg"></div>

            <!-- Circle Skeleton Placeholders -->
            <div class="flex justify-between my-4">
                <div class="w-20 h-20 bg-gray-300 dark:bg-gray-700 animate-pulse rounded-full"></div>
                <div class="w-20 h-20 bg-gray-300 dark:bg-gray-700 animate-pulse rounded-full"></div>
                <div class="w-20 h-20 bg-gray-300 dark:bg-gray-700 animate-pulse rounded-full"></div>
                <div class="w-20 h-20 bg-gray-300 dark:bg-gray-700 animate-pulse rounded-full"></div>
                <div class="w-20 h-20 bg-gray-300 dark:bg-gray-700 animate-pulse rounded-full hidden md:block"></div>
                <div class="w-20 h-20 bg-gray-300 dark:bg-gray-700 animate-pulse rounded-full hidden md:block"></div>
                <div class="w-20 h-20 bg-gray-300 dark:bg-gray-700 animate-pulse rounded-full hidden md:block"></div>
                <div class="w-20 h-20 bg-gray-300 dark:bg-gray-700 animate-pulse rounded-full hidden md:block"></div>
            </div>

            <div class="flex flex-col md:flex-row gap-1">
                <div class="flex flex-col gap-2 w-full md:w-1/4">
                    <div class="w-full h-20 bg-gray-300 dark:bg-gray-700 animate-pulse rounded-lg mb-4"></div>
                    <div class="w-3/4 h-5 bg-gray-300 dark:bg-gray-700 animate-pulse rounded mb-2"></div>
                    <div class="w-full h-4 bg-gray-300 dark:bg-gray-700 animate-pulse rounded mb-2"></div>
                    <div class="w-5/6 h-4 bg-gray-300 dark:bg-gray-700 animate-pulse rounded mb-2"></div>
                    <div class="w-2/3 h-4 bg-gray-300 dark:bg-gray-700 animate-pulse rounded"></div>
                </div>

                <div class="flex flex-col gap-2 w-full md:w-1/4">
                    <div class="w-full h-20 bg-gray-300 dark:bg-gray-700 animate-pulse rounded-lg mb-4"></div>
                    <div class="w-3/4 h-5 bg-gray-300 dark:bg-gray-700 animate-pulse rounded mb-2"></div>
                    <div class="w-full h-4 bg-gray-300 dark:bg-gray-700 animate-pulse rounded mb-2"></div>
                    <div class="w-5/6 h-4 bg-gray-300 dark:bg-gray-700 animate-pulse rounded mb-2"></div>
                    <div class="w-2/3 h-4 bg-gray-300 dark:bg-gray-700 animate-pulse rounded"></div>
                </div>

                <div class="flex flex-col gap-2 w-full md:w-1/4">
                    <div class="w-full h-20 bg-gray-300 dark:bg-gray-700 animate-pulse rounded-lg mb-4"></div>
                    <div class="w-3/4 h-5 bg-gray-300 dark:bg-gray-700 animate-pulse rounded mb-2"></div>
                    <div class="w-full h-4 bg-gray-300 dark:bg-gray-700 animate-pulse rounded mb-2"></div>
                    <div class="w-5/6 h-4 bg-gray-300 dark:bg-gray-700 animate-pulse rounded mb-2"></div>
                    <div class="w-2/3 h-4 bg-gray-300 dark:bg-gray-700 animate-pulse rounded"></div>
                </div>

                <div class="flex flex-col gap-2 w-full md:w-1/4">
                    <div class="w-full h-20 bg-gray-300 dark:bg-gray-700 animate-pulse rounded-lg mb-4"></div>
                    <div class="w-3/4 h-5 bg-gray-300 dark:bg-gray-700 animate-pulse rounded mb-2"></div>
                    <div class="w-full h-4 bg-gray-300 dark:bg-gray-700 animate-pulse rounded mb-2"></div>
                    <div class="w-5/6 h-4 bg-gray-300 dark:bg-gray-700 animate-pulse rounded mb-2"></div>
                    <div class="w-2/3 h-4 bg-gray-300 dark:bg-gray-700 animate-pulse rounded"></div>
                </div>
            </div>

            <div class="w-full h-40 bg-gray-300 dark:bg-gray-700 animate-pulse rounded-lg my-4"></div>
        </section>

        <?= $this->include('website/template/mobileBottomMenu') ?>
        <?= $this->include('website/template/productVarientPopup') ?>

    </main>
    <?= $this->include('website/template/shopCart') ?>
    <?= $this->include('website/template/footer') ?>
    <?= $this->include('website/template/script') ?>

    <script>
        <?php if (isset($session_load) && $session_load == 0): ?>
            var locationDatas = JSON.parse(localStorage.getItem('location'));

            // Only reload if location has a valid city_id (area confirmed by server)
            // Checking just .city caused infinite loops when the area was deleted from DB
            if (locationDatas && locationDatas.city && locationDatas.city_id > 0) {
                setTimeout(() => {
                    location.reload();
                }, 1000)
            }
        <?php endif; ?>
    </script>
</body>

</html>