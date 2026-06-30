<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Send Mail | <?= isset($settings['business_name']) ? esc($settings['business_name']) : '' ?></title>

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
                            <h1 class="m-0">Send Mail</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Send Mail</li>
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
                            <form action="/admin/sendMail/<?php echo $secretkey ?>" method="post">
                                <div class="card-body">
                                    <div class="row">
                                        <textarea name="mail-content" id="mail-content"></textarea>
                                    </div>
                                    <input type="number" name="start" id="start" placeholder="start">
                                    <input type="number" name="limit" id="limit" placeholder="limit">
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Send</button>
                                </div>
                            </form>
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