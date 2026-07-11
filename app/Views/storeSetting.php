<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Store Setting | <?= isset($settings['business_name']) ? esc($settings['business_name']) : '' ?></title>

    <?= $this->include('template/style') ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" />
    <link rel="stylesheet" href="<?= base_url('/assets/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css') ?>">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
    <style>
        .bootstrap-switch {
            border-radius: 20px !important;
        }

        .bootstrap-switch .bootstrap-switch-handle-off.bootstrap-switch-success,
        .bootstrap-switch .bootstrap-switch-handle-on.bootstrap-switch-success {
            color: #fff;
            background: #005555;
        }

        .toggle-switch:not(.form-group) {
            margin-bottom: 0;
        }

        .toggle-switch {
            font-weight: 500;
        }

        .toggle-switch {
            border-color: #e7eaf3 !important;
        }

        .toggle-switch {
            position: relative;
            display: flex;
            align-items: center;
            cursor: pointer;
            font-size: 0.85rem;
            text-transform: capitalize;
        }

        .toggle-switch {
            position: relative;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            cursor: pointer;
        }

        input[type="checkbox"],
        input[type="radio"] {
            box-sizing: border-box;
            padding: 0;
        }

        .toggle-switch-input {
            position: absolute;
            z-index: -1;
            opacity: 0;
        }

        .form--check .form-check-input[type="radio"]:checked,
        .toggle-switch-input:checked+.toggle-switch-label {
            background-color: #14b19e;
        }

        .toggle-switch-input:checked+.toggle-switch-label {
            background-color: #00868f;
        }

        .switch--custom-label .toggle-switch-label {
            width: 44px;
            height: 26px;
            margin: 0;
        }

        .toggle-switch-label {
            position: relative;
            display: block;
            width: 3rem;
            height: 2rem;
            background-color: #e7eaf3;
            background-clip: content-box;
            border: 0.125rem solid transparent;
            border-radius: 6.1875rem;
            transition: 0.3s;
        }

        .switch--custom-label .toggle-switch-input:checked+.toggle-switch-label .toggle-switch-indicator {
            transform: translate3d(18px, 50%, 0);
        }

        .toggle-switch-input:checked+.toggle-switch-label .toggle-switch-indicator {
            -webkit-transform: translate3d(1.025rem, 50%, 0);
            transform: translate3d(1.025rem, 50%, 0);
        }

        .switch--custom-label .toggle-switch-indicator {
            width: 18px;
            height: 18px;
        }

        .toggle-switch-indicator {
            position: absolute;
            left: 0.125rem;
            bottom: 50%;
            width: 1.5rem;
            height: 1.5rem;
            background-color: #fff;
            -webkit-transform: initial;
            transform: initial;
            box-shadow: 0 3px 6px 0 rgba(140, 152, 164, 0.25);
            border-radius: 50%;
            -webkit-transform: translate3d(0, 50%, 0);
            transform: translate3d(0, 50%, 0);
            transition: 0.3s;
        }
    </style>
</head>

<body class="sidebar-mini control-sidebar-slide-open text-sm  layout-fixed  <?php echo  $settings['thememode'] == 'Light' ? '' : 'dark-mode'; ?> layout-navbar-fixed text-sm" id="body">
    <div class="wrapper">


        <?= $this->include('template/header') ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?= $this->include('template/sidebar') ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Store Setting</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Store Setting</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2 pr-3">
                                        <div class="list-group" id="settingsTabs" role="tablist">
                                            <a class="list-group-item list-group-item-action <?php echo (!isset($_GET['setting']) || $_GET['setting'] == 'store') ? 'active ' : '' ?>" onclick="changeURL('/admin/store-setting?setting=store')" id="store-tab" data-toggle="pill" href="#store" role="tab" aria-controls="store" aria-selected="true">
                                                Store Setting
                                            </a>
                                            <a class="list-group-item list-group-item-action <?php echo  isset($_GET['setting']) && $_GET['setting'] == 'country' ? 'active' : '' ?>" onclick="changeURL('/admin/store-setting?setting=country')" id="country-tab" data-toggle="pill" href="#country" role="tab" aria-controls="country" aria-selected="false">
                                                Country Setting
                                            </a>
                                            <a class="list-group-item list-group-item-action <?php echo  isset($_GET['setting']) && $_GET['setting'] == 'order' ? 'active' : '' ?>" onclick="changeURL('/admin/store-setting?setting=order')" id="order-tab" data-toggle="pill" href="#order" role="tab" aria-controls="order" aria-selected="false">
                                                Order Setting
                                            </a>
                                            <a class="list-group-item list-group-item-action <?php echo  isset($_GET['setting']) && $_GET['setting'] == 'delivery-boy' ? 'active' : '' ?>" onclick="changeURL('/admin/store-setting?setting=delivery-boy')" id="delivery-boy-tab" data-toggle="pill" href="#delivery-boy" role="tab" aria-controls="delivery-boy" aria-selected="false">
                                                Delivery Boy Setting
                                            </a>
                                            <a class="list-group-item list-group-item-action <?php echo  isset($_GET['setting']) && $_GET['setting'] == 'seller' ? 'active' : '' ?>" onclick="changeURL('/admin/store-setting?setting=seller')" id="seller-tab" data-toggle="pill" href="#seller" role="tab" aria-controls="seller" aria-selected="false">
                                                Seller Setting
                                            </a>
                                            <a class="list-group-item list-group-item-action <?php echo  isset($_GET['setting']) && $_GET['setting'] == 'app-setting' ? 'active' : '' ?>" onclick="changeURL('/admin/store-setting?setting=app-setting')" id="app-setting-tab" data-toggle="pill" href="#app-setting" role="tab" aria-controls="app-setting" aria-selected="false">
                                                App Setting
                                            </a>
                                            <a class="list-group-item list-group-item-action <?php echo  isset($_GET['setting']) && $_GET['setting'] == 'frontend-landing' ? 'active' : '' ?>" onclick="changeURL('/admin/store-setting?setting=frontend-landing')" id="frontend-landing-tab" data-toggle="pill" href="#frontend-landing" role="tab" aria-controls="frontend-landing" aria-selected="false">
                                                Frontend Landing Page
                                            </a>
                                           
                                        </div>
                                    </div>
                                    <div class="col-md-10" style=" box-shadow: 0 0 10px rgb(0 0 0 / 13%), 0px 0px 10px rgb(0 0 0 / 0%); border-radius:5px;">
                                        <!-- Tab Content -->
                                        <div class="tab-content" id="settingsTabContent">
                                            <?= $this->include('store_setting_sections/store') ?>
                                            <?= $this->include('store_setting_sections/country') ?>
                                            <?= $this->include('store_setting_sections/order') ?>
                                            <?= $this->include('store_setting_sections/delivery_boy') ?>
                                            <?= $this->include('store_setting_sections/app_setting') ?>
                                            <?= $this->include('store_setting_sections/frontend_landing') ?>
                                            <?= $this->include('store_setting_sections/seller') ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>

        <!-- /.content-wrapper -->
        <?= $this->include('template/footer') ?>

        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->

    <?= $this->include('template/script') ?>
    <script src="<?= base_url('/assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js') ?>"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?= isset($settings['map_api_key']) ? esc($settings['map_api_key']) : '' ?>&callback=initAutocomplete&libraries=places&v=weekly" defer></script>
    <script src="<?= base_url('/assets/page-script/store_setting.js') ?>"></script>
    <script>
        function testMail() {
            Swal.fire({
                title: "Confirm?",
                text: "Make sure mail setting is update done successfully!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, setting updated",
            }).then((result) => {
                if (result.isConfirmed) {
                    var test_mail_id = $("#test_mail_id").val();
                    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                    if (!emailRegex.test(test_mail_id)) {
                        toastr.error("Please enter a valid email address", "Admin says");
                        return;
                    }
                    $.ajax({
                        url: "/admin/setting/mail/test",
                        type: "POST",
                        data: {
                            test_mail_id
                        },
                        dataType: "json",
                        beforeSend: function() {
                            toastr.info('Sending test mail', "Admin says");

                        },
                        success: function(response) {
                            if (response.success == true) {
                                toastr.success(response.message, "Admin says");
                            } else {
                                toastr.error(response.message, "Admin says");
                            }
                        },
                        error: function(e) {
                            toastr.error("Error while testing mail", "Admin says");
                        },
                    });
                }
            });
        }
    </script>
</body>

</html>