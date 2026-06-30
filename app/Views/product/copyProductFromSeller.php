<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Copy Product to Seller | <?= isset($settings['business_name']) ? esc($settings['business_name']) : '' ?></title>

    <?= $this->include('template/style') ?>

</head>

<body class="sidebar-mini control-sidebar-slide-open text-sm  layout-fixed <?php echo  $settings['thememode'] == 'Light' ? '' : 'dark-mode'; ?> layout-navbar-fixed text-sm" id="body">
    <div class="wrapper">


        <?= $this->include('template/header') ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?= $this->include('template/sidebar') ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->

            <section class="content">
                <div class="container-fluid">
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-<?php echo $settings['primary_color']; ?>">
                                <div class="card-header">
                                    <h3 class="card-title">Update Product Order</h3>
                                </div>
                                <!-- /.card-header -->
                                <form method="post" enctype="multipart/form-data">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleSelectBorder">Select Seller (From Import)</label>
                                                    <select class="custom-select " id="seller_id" name="seller_id">
                                                        <option value="">Select Seller (From Import)</option>
                                                        <?php foreach ($sellers as $seller): ?>
                                                            <option value="<?= esc($seller['id']); ?>"><?= esc($seller['name']); ?> (<?= esc($seller['store_name']); ?>)</option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleSelectBorder">Select Seller (To Import)</label>
                                                    <select class="custom-select " id="to_seller_id" name="to_seller_id">
                                                        <option value="">Select Seller (To Import)</option>
                                                        <?php foreach ($sellers as $seller): ?>
                                                            <option value="<?= esc($seller['id']); ?>"><?= esc($seller['name']); ?> (<?= esc($seller['store_name']); ?>)</option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 ">
                                                <div class="form-group">
                                                    <label for="exampleSelectBorder">Select Category</label>
                                                    <select class="custom-select " id="category_id" name="category_id">
                                                        <option value="">Select Category</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleSelectBorder">Select Subcategory</label>
                                                    <select class="custom-select " id="subcategory_id" name="subcategory_id">
                                                        <option value="">Select Subcategory</option>
                                                    </select>
                                                </div>
                                            </div>    
                                            
                                        </div>
                                            <ul id="product-list">
                                                </ul>
                                    </div>
                                    <div class="card-footer">
                                        <button type="button" id="copy-selected" class="btn btn-primary">
                                            Copy Selected Products
                                        </button>
                                    </div>
                                </form>
                                <!-- /.card-body -->
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
    <script src="<?= base_url('/assets/page-script/copy_product.js') ?>"></script>

</body>

</html>