<!doctype html>
<html lang="<?= session()->get('site_lang') ?? 'en' ?>" dir="<?= dir_attribute() ?>">

<head>
    <?= $this->include('website/template/style') ?>
    <title><?= $settings['business_name'] ?></title>
</head>

<body class="bg-gray-100 dark:bg-gray-950">
    <div class="block md:hidden">
        <?= $this->include('website/template/header') ?>
    </div>
    <main class="max-w-7xl mx-auto">

        <div class="flex flex-wrap md:h-screen">
            <div class="flex w-screen flex-col lg:w-1/2">

                <div class="md:mx-[15%] mx-[5%] my-auto flex flex-col justify-center lg:justify-start py-6">
                    <p class="text-center text-xl md:text-3xl font-bold dark:text-white"><?php echo lang('website.forget_password'); ?></p>
                    <p class="mt-2 text-center text-gray-500 dark:text-gray-400"><?php echo lang('website.Dont_worry_It_happens_Please_enter_the_email_address_associated_with_your_account'); ?></p>
                    <form class="flex flex-col pt-3 md:pt-8">

                        <div class="flex flex-col pt-4">
                            <label class="text-gray-800 dark:text-gray-200 font-medium inline-block" for="email">
                                <?php echo lang('website.email'); ?>
                                <span class="text-red-600">*</span>
                            </label>
                            <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-lg focus-within:border-green-600 focus-within:shadow-[0_0_0_.25rem_rgba(10,173,10,.25)] dark:bg-gray-800">
                                <span class="px-3 text-gray-500 dark:text-gray-400">
                                    <i class="fi fi-tr-envelopes"></i>
                                </span>
                                <input type="email" id="email" name="email" class="text-gray-900 dark:text-gray-100 dark:bg-gray-800 focus:ring-0 focus:border-0 block p-2 w-full text-base border-0 disabled:opacity-50 disabled:pointer-events-none" placeholder="<?php echo lang('website.enter'); ?> <?php echo lang('website.email'); ?>">
                            </div>
                            <div id="emailError" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div id="message" class="mt-2 mb-6"></div>

                        <button type="submit" class="w-full rounded-lg bg-green-700 px-4 py-2 text-center text-base font-semibold text-white shadow-md ring-gray-500 ring-offset-2 transition focus:ring-2"><?php echo lang('website.send_password_reset_link'); ?></button>
                    </form>

                </div>
            </div>
            <div class="pointer-events-none relative hidden select-none bg-white dark:bg-gray-900 lg:block lg:w-1/2">
                <img class="absolute top-0 h-full w-full dark:brightness-75" src="<?= base_url('/assets/dist/img/reset-password-banner.svg') ?>" />
            </div>
        </div>

        <?= $this->include('website/template/mobileBottomMenu') ?>

    </main>
    <?= $this->include('website/template/script') ?>
    <script src="<?= base_url('/assets/page-script/website/resetPassword.js') ?>"></script>

</body>

</html>