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
                                    <h3 class="card-title">Add Home Section</h3>
                                </div>
                                <!-- /.card-header -->
                                <form method="post" enctype="multipart/form-select_home_section">
                                    <div class="card-body">

                                        <div class="form-group">
                                            <label>Title</label>
                                            <input type="text" id="title" class="form-control " placeholder="Enter Title" name="title" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Select Category</label>
                                            <select id="category" name="category" class=" form-control" required>
                                                <option value="" selected="">Select Category</option>
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?= esc($category['id']); ?>"><?= esc($category['category_name']); ?></option>
                                                <?php endforeach; ?>

                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Select SubCategory</label>
                                            <select id="sub_category" name="sub_category" class=" form-control" required>
                                                <option value="" selected="">Select SubCategory</option>


                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Select City</label>
                                            <select class="form-control " name="city_id" id="city_id">
                                                        <option>Select City</option>
                                                        <?php foreach ($city as $key => $val) { ?>
                                                            <option value="<?php echo $val['id'] ?>"><?php echo $val['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Select Deliverable Area</label>
                                            <select id="deliverable_area_id" name="deliverable_area_id" class=" form-control" required>
                                                <option value="" selected="">Select Deliverable Area</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Status?</label>
                                            <select id="status" name="status" class=" form-control" required>
                                                <option value="" selected="">Select Status</option>
                                                <option value="1">Publish</option>
                                                <option value="0">Unpublish</option>


                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                           <label for="sort_by"> Product Sort By<span class="text-danger text-xs">*</span></label>
                                                                <select id="sort_by" name="sort_by" required="" class="form-control ">
                                                                    <option value="default" >Default</option>
                                                                    <option value="best_selling" >Best Selling</option>
                                                                    <option value="low_to_high" >Low To High</option>
                                                                    <option value="high_to_low" >High To Low</option>
                                                                    <option value="maximum_discount">Maximum Discount %</option>
                                                                    <option value="best_rated" >Best Rated</option>
                                                                    <option value="alphabetical">Alphabetical</option>
                                                                    
                                                                </select>
                                        </div>
                                                                                                    <div class="form-group ">
                                                                <label for="product_show_limit">Product Limit <span class="text-danger text-xs">*</span></label>
                                                                <input type="number"  id="product_show_limit" name="product_show_limit" required="" class="form-control ">
                                                            </div>
                                        
                                    </div>
                                    <div class="card-footer">
                                        <button type="button" name="sub_section" id="sub_section" class="btn btn-primary" onclick="add_section()">
                                            Add Home Section
                                        </button>
                                    </div>

                                </form>
                                <!-- /.card-body -->
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">View Home Sections</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="view_section" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Sr No</th>
                                                <th>Section Name</th>
                                                <th>Section Category</th>
                                                <th>Section Subcategory</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
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