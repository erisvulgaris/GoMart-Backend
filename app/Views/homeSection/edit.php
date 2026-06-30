<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home Section | <?= isset($settings['business_name']) ? esc($settings['business_name']) : '' ?></title>

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
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Home Section</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Home Section</li>
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

                        <div class="col-md-4">
                            <div class="card card-<?php echo $settings['primary_color']; ?>">
                                <div class="card-header">
                                    <h3 class="card-title">Edit Home Section</h3>
                                </div>
                                <!-- /.card-header -->
                                <form method="post" enctype="multipart/form-select_home_section">
                                    <input type="hidden" name="home_section_id" id="home_section_id" value="<?= $homeSection['id'] ?>">
                                    <div class="card-body">

                                        <div class="form-group">
                                            <label>Title</label>
                                            <input type="text" id="title" class="form-control " placeholder="Enter Title" name="title" value="<?= $homeSection['title'] ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Select Category</label>
                                            <select id="category" name="category" class=" form-control" required>
                                                <option value="" selected="">Select Category</option>
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?= esc($category['id']); ?>" <?php echo $category['id'] == $homeSection['category_id'] ? "selected" : "" ?> ><?= esc($category['category_name']); ?></option>
                                                <?php endforeach; ?>

                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Select SubCategory</label>
                                            <select id="sub_category" name="sub_category" class=" form-control" required>
                                                <option value="" selected="" disabled="">Select Subcategory</option>
                                                <?php foreach ($subcategories as $subcategory): ?>
                                                    <option value="<?= esc($subcategory['id']); ?>" <?php echo $subcategory['id'] == $homeSection['subcategory_id'] ? "selected" : "" ?>><?= esc($subcategory['name']); ?></option>
                                                <?php endforeach; ?>


                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Select City</label>
                                            <select class="form-control " name="city_id" id="city_id">
                                                        <option>Select City</option>
                                                        <?php foreach ($city as $key => $val) { ?>
                                                            <option value="<?php echo $val['id'] ?>" <?= $val['id'] == $homeSection['city_id'] ? 'selected' : '' ?>><?php echo $val['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Select Deliverable Area</label>
                                            <select id="deliverable_area_id" name="deliverable_area_id" class=" form-control" required>
                                                <option value="" selected="">Select Deliverable Area</option>
                                                <?php foreach ($deliverable_area as $key => $val) { ?>
                                                            <option value="<?php echo $val['id'] ?>" <?= $val['id'] == $homeSection['deliverable_area_id'] ? 'selected' : '' ?>><?php echo $val['deliverable_area_title'] ?></option>
                                                        <?php } ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Status?</label>
                                            <select id="status" name="status" class=" form-control" required>
                                                <option value="" selected="">Select Status</option>
                                                <option value="1" <?php echo $homeSection['is_active'] == 1 ? "selected" : "" ?>>Publish</option>
                                                <option value="0" <?php echo $homeSection['is_active'] == 0 ? "selected" : "" ?>>Unpublish</option>


                                            </select>
                                        </div>
                                        <div class="form-group">
                                           <label for="sort_by"> Product Sort By<span class="text-danger text-xs">*</span></label>
                                                                <select id="sort_by" name="sort_by" required="" class="form-control ">
                                                                    <option value="default" <?php if ($homeSection['sort_by'] == 'default') { echo 'selected'; } ?>>Default</option>
                                                                    <option value="best_selling" <?php if ($homeSection['sort_by'] == 'best_selling') { echo 'selected'; } ?>>Best Selling</option>
                                                                    <option value="low_to_high" <?php if ($homeSection['sort_by'] == 'low_to_high') { echo 'selected'; } ?>>Low To High</option>
                                                                    <option value="high_to_low" <?php if ($homeSection['sort_by'] == 'high_to_low') { echo 'selected'; } ?>>High To Low</option>
                                                                    <option value="maximum_discount" <?php if ($homeSection['sort_by'] == 'maximum_discount') { echo 'selected'; } ?>>Maximum Discount %</option>
                                                                    <option value="best_rated" <?php if ($homeSection['sort_by'] == 'best_rated') { echo 'selected'; } ?>>Best Rated</option>
                                                                    <option value="alphabetical" <?php if ($homeSection['sort_by'] == 'alphabetical') { echo 'selected'; } ?>>Alphabetical</option>
                                                                    
                                                                </select>
                                        </div>
                                        <div class="form-group ">
                                                                <label for="product_show_limit">Product Limit <span class="text-danger text-xs">*</span></label>
                                                                <input type="number" value="<?= isset($homeSection['product_show_limit']) ? esc($homeSection['product_show_limit']) : '' ?>" id="product_show_limit" name="product_show_limit" required="" class="form-control ">
                                                            </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="button" name="sub_section" id="sub_section" class="btn btn-primary" onclick="update_section()">
                                            Edit Home Section
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
    <script src="<?= base_url('/assets/page-script/home_section.js') ?>"></script>

</body>

</html>