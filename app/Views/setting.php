<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>App Setting | <?= isset($settings['business_name']) ? esc($settings['business_name']) : '' ?></title>

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
                            <h1 class="m-0">App Setting</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">App Setting</li>
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

                                            <a class="list-group-item list-group-item-action <?php echo (!isset($_GET['setting']) || (isset($_GET['setting']) && $_GET['setting'] === 'mail')) == 'mail' ? 'active' : '' ?>" onclick="changeURL('/admin/setting?setting=mail')" id="mail-tab" data-toggle="pill" href="#mail" role="tab" aria-controls="mail" aria-selected="true">
                                                Mail Setting
                                            </a>
                                            <a class="list-group-item list-group-item-action <?php echo  isset($_GET['setting']) && $_GET['setting'] == 'google-map-api' ? 'active' : '' ?>" onclick="changeURL('/admin/setting?setting=google-map-api')" id="google-map-api-tab" data-toggle="pill" href="#google-map-api" role="tab" aria-controls="google-map-api" aria-selected="false">
                                                Google API / Recaptcha
                                            </a>
                                            <a class="list-group-item list-group-item-action <?php echo  isset($_GET['setting']) && $_GET['setting'] == 'firebase-setting-api' ? 'active' : '' ?>" onclick="changeURL('/admin/setting?setting=firebase-setting-api')" id="firebase-setting-api-tab" data-toggle="pill" href="#firebase-setting-api" role="tab" aria-controls="firebase-setting-api" aria-selected="false">
                                                Firebase Setting
                                            </a>
                                            <a class="list-group-item list-group-item-action <?php echo  isset($_GET['setting']) && $_GET['setting'] == 'notification' ? 'active' : '' ?>" onclick="changeURL('/admin/setting?setting=notification')" id="notification-tab" data-toggle="pill" href="#notification" role="tab" aria-controls="notification" aria-selected="false">
                                                Notification
                                            </a>
                                            <a class="list-group-item list-group-item-action <?php echo  isset($_GET['setting']) && $_GET['setting'] == 'login' ? 'active' : '' ?>" onclick="changeURL('/admin/setting?setting=login')" id="login-tab" data-toggle="pill" href="#login" role="tab" aria-controls="login" aria-selected="false">
                                                Login Setting
                                            </a>
                                            <a class="list-group-item list-group-item-action <?php echo  isset($_GET['setting']) && $_GET['setting'] == 'social-links' ? 'active' : '' ?>" onclick="changeURL('/admin/setting?setting=social-links')" id="social-links-tab" data-toggle="pill" href="#social-links" role="tab" aria-controls="social-links" aria-selected="false">
                                                Social Links
                                            </a>
                                            <a class="list-group-item list-group-item-action <?php echo  isset($_GET['setting']) && $_GET['setting'] == 'external-api-setting' ? 'active' : '' ?>" onclick="changeURL('/admin/setting?setting=external-api-setting')" id="external-api-setting-tab" data-toggle="pill" href="#external-api-setting" role="tab" aria-controls="external-api-setting" aria-selected="false">
                                                3rd Party API
                                            </a>
                                            <a class="list-group-item list-group-item-action <?php echo  isset($_GET['setting']) && $_GET['setting'] == 'language' ? 'active' : '' ?>" onclick="changeURL('/admin/setting?setting=language')" id="language-tab" data-toggle="pill" href="#language" role="tab" aria-controls="language" aria-selected="false">
                                                Language Setting
                                            </a>
                                            <a class="list-group-item list-group-item-action <?php echo  isset($_GET['setting']) && $_GET['setting'] == 'app-main-header' ? 'active' : '' ?>" onclick="changeURL('/admin/setting?setting=app-main-header')" id="app-main-header-tab" data-toggle="pill" href="#app-main-header" role="tab" aria-controls="app-main-header" aria-selected="false">
                                                App Main Header Setting
                                            </a>
                                            <a class="list-group-item list-group-item-action <?php echo  isset($_GET['setting']) && $_GET['setting'] == 'other' ? 'active' : '' ?>" onclick="changeURL('/admin/setting?setting=other')" id="other-tab" data-toggle="pill" href="#other" role="tab" aria-controls="other" aria-selected="false">
                                                Other Setting
                                            </a>
                                            <a class="list-group-item list-group-item-action <?php echo  isset($_GET['setting']) && $_GET['setting'] == 'sys-info' ? 'active' : '' ?>" onclick="changeURL('/admin/setting?setting=sys-info')" id="sys-info-tab" data-toggle="pill" href="#sys-info" role="tab" aria-controls="sys-info" aria-selected="false">
                                                System Info
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-10" style=" box-shadow: 0 0 10px rgb(0 0 0 / 13%), 0px 0px 10px rgb(0 0 0 / 0%); border-radius:5px;">
                                        <!-- Tab Content -->
                                        <div class="tab-content" id="settingsTabContent">
                                            <?= $this->include('setting_sections/mail') ?>
                                            <?= $this->include('setting_sections/google_map_api') ?>
                                            <?= $this->include('setting_sections/login') ?>
                                            <?= $this->include('setting_sections/notification') ?>
                                            <?= $this->include('setting_sections/social_links') ?>
                                            <?= $this->include('setting_sections/firebase') ?>
                                            <?= $this->include('setting_sections/external_api') ?>
                                            <?= $this->include('setting_sections/other') ?>
                                            <?= $this->include('setting_sections/language') ?>
                                            <?= $this->include('setting_sections/app_main_header') ?>
                                            <?= $this->include('setting_sections/sys_info') ?>
