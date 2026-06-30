<!doctype html>
<html lang="<?= session()->get('site_lang') ?? 'en' ?>" dir="<?= dir_attribute() ?>">

<head>
    <?= $this->include('website/template/style') ?>
    <title><?= $settings['business_name'] ?></title>
    <link rel="stylesheet" href="<?= base_url('/assets/website/css/policy.css') ?>">
    <style>
        .dark .policyclass,
        .dark .policyclass p,
        .dark .policyclass span,
        .dark .policyclass li,
        .dark .policyclass td,
        .dark .policyclass th,
        .dark .policyclass label,
        .dark .policyclass blockquote {
            color: #f9fafb !important;
        }

        .dark .policyclass h1,
        .dark .policyclass h2,
        .dark .policyclass h3,
        .dark .policyclass h4,
        .dark .policyclass h5,
        .dark .policyclass h6,
        .dark .policyclass strong,
        .dark .policyclass b {
            color: #ffffff !important;
        }

        .dark .policyclass a {
            color: #86efac !important;
        }

        .dark .policyclass a:hover {
            color: #4ade80 !important;
        }

        .dark .policyclass table {
            border-color: #374151 !important;
        }

        .dark .policyclass td,
        .dark .policyclass th {
            border-color: #374151 !important;
            background-color: transparent !important;
        }

        .dark .policyclass thead th {
            background-color: #1f2937 !important;
        }

        .dark .policyclass hr {
            border-color: #374151 !important;
        }

        .dark .policyclass blockquote {
            border-left-color: #4ade80 !important;
            background-color: #1f2937 !important;
        }

        .dark .policyclass code,
        .dark .policyclass pre {
            background-color: #1f2937 !important;
            color: #86efac !important;
        }

        .dark .policyclass ul li::marker,
        .dark .policyclass ol li::marker {
            color: #d1d5db !important;
        }

        .dark .policyclass img {
            filter: brightness(0.9);
        }
    </style>

</head>

<body class="bg-gray-100 dark:bg-gray-950">
    <?= $this->include('website/template/header') ?>
    <main class="max-w-7xl mx-auto">
        <section class="mt-2 md:mt-4 md:container md:mx-auto px-3">
            <div class="row bg-white dark:bg-gray-800 mb-2 p-4 rounded-lg">
                <div class="flex justify-between">
                    <h2 class="text-lg font-medium z-10 dark:text-white"><?php echo lang('website.refund_policy'); ?></h2>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg px-4 py-2 policyclass">
                    <?= $refundPolicy; ?>
            </div>

        </section>



        <?= $this->include('website/template/mobileBottomMenu') ?>
        <?= $this->include('website/template/productVarientPopup') ?>

    </main>
    <?= $this->include('website/template/shopCart') ?>
    <?= $this->include('website/template/footer') ?>
    <?= $this->include('website/template/script') ?>
</body>

</html>