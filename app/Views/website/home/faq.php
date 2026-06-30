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
                    <h2 class="text-lg font-medium z-10 dark:text-white"><?php echo lang('website.faq'); ?></h2>
                </div>
            </div>

            <div class="space-y-2">
                <?php foreach ($faqs as $item): ?>
                    <div class="border dark:border-gray-700 rounded-lg overflow-hidden">
                        <button class="w-full text-left p-4 bg-gray-200 dark:bg-gray-700 font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition dark:text-gray-100" onclick="toggleFaq(this)">
                            <?php echo htmlspecialchars($item['question']); ?>
                        </button>
                        <div class="p-4 bg-white dark:bg-gray-800 hidden dark:text-gray-300">
                            <?php echo nl2br(htmlspecialchars($item['answer'])); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </section>



        <?= $this->include('website/template/mobileBottomMenu') ?>
        <?= $this->include('website/template/productVarientPopup') ?>


    </main>
    <?= $this->include('website/template/shopCart') ?>
    <?= $this->include('website/template/footer') ?>
    <?= $this->include('website/template/script') ?>
    <script src="<?= base_url('/assets/page-script/website/faq.js') ?>"></script>

</body>

</html>