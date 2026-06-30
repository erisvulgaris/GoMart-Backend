<!doctype html>
<html lang="<?= session()->get('site_lang') ?? 'en' ?>" dir="<?= dir_attribute() ?>">

<head>
    <?= $this->include('website/template/style') ?>
    <title><?= $settings['business_name'] ?></title>
</head>

<body class="bg-gray-100 dark:bg-gray-950">
    <?= $this->include('website/template/header') ?>
    <main class="max-w-7xl mx-auto">
        <section class="mt-2 md:mt-4 md:container md:mx-auto px-3">
            <div class="row bg-white dark:bg-gray-800 mb-2 p-4 rounded-lg">
                <div class="flex justify-between">
                    <h2 class="text-lg font-medium z-10 dark:text-white"><?php echo lang('website.hi'); ?>, <?=$greeting?> <?=$user_name?></h2>
                </div>
            </div>
        </section>
        <section class="mt-2 md:mt-4 md:container md:mx-auto md:px-3">
            <div class="flex flex-wrap lg:flex-nowrap lg:gap-x-6 gap-y-6">
                <?= $this->include('website/template/dashboardSidebar') ?>
                <div class="w-full lg:w-full md:w-full mx-auto">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">

                        <!-- Total Orders -->
                        <a href="/order-history" class="p-3 rounded-2xl shadow-xs bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <span class="bg-pink-500 dark:bg-pink-600 w-10 h-10 flex justify-center items-center rounded-lg text-white mb-6">
                                <i class="fi fi-rr-order-history text-lg"></i>
                            </span>
                            <h3 class="text-pink-500 dark:text-pink-400 text-2xl font-bold mb-1"><?= $orderCount ?></h3>
                            <p class="font-medium text-gray-700 dark:text-gray-300"><?php echo lang('website.total_orders'); ?></p>
                        </a>

                        <!-- Wallet Balance -->
                        <a href="/wallet" class="p-3 rounded-2xl shadow-xs bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <span class="bg-blue-500 dark:bg-blue-600 w-10 h-10 flex justify-center items-center rounded-lg text-white mb-6">
                                <i class="fi fi-rr-wallet text-lg"></i>
                            </span>
                            <h3 class="text-blue-500 dark:text-blue-400 text-2xl font-bold mb-1">
                                <?php if ($settings['currency_symbol_position'] == 'left'): ?>
                                    <?= $country['currency_symbol'] ?><?= $user_wallet ?>
                                <?php else: ?>
                                    <?= $user_wallet ?><?= $country['currency_symbol'] ?>
                                <?php endif; ?>
                            </h3>
                            <p class="font-medium text-gray-700 dark:text-gray-300"><?php echo lang('website.wallet_balance'); ?></p>
                        </a>

                    </div>
                </div>
            </div>
        </section>

        <?= $this->include('website/template/mobileBottomMenu') ?>
    </main>
    <?= $this->include('website/template/shopCart') ?>
    <?= $this->include('website/template/coupon') ?>
    <?= $this->include('website/template/footer') ?>
    <?= $this->include('website/template/script') ?>
</body>

</html>