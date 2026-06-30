<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Banner | <?= isset($settings['business_name']) ? esc($settings['business_name']) : '' ?></title>

    <?= $this->include('template/style') ?>

</head>

<body class="sidebar-mini control-sidebar-slide-open text-sm layout-fixed <?php echo $settings['thememode'] == 'Light' ? '' : 'dark-mode'; ?> layout-navbar-fixed text-sm" id="body">
    <div class="wrapper">

        <?= $this->include('template/header') ?>

        <!-- Main Sidebar Container -->
        <?= $this->include('template/sidebar') ?>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Banner</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Banner</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card card-<?php echo $settings['primary_color']; ?>">
                                <div class="card-header">
                                    <h3 class="card-title">Add Banner</h3>
                                </div>
                                <form id="addBannerForm" method="post" enctype="multipart/form-data">
                                    <div class="card-body">

                                        <div class="form-group">
                                            <label>Select Home Screen <span class="text-danger">*</span></label>
                                            <select class="form-control" name="home_screen_id" id="home_screen_id">
                                                <?php foreach ($homeScreens as $hs): ?>
                                                    <option value="<?= $hs['id'] ?>"><?= esc($hs['name']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Select Banner Type <span class="text-danger">*</span></label>
                                            <select class="form-control" name="banner_type" id="banner_type">
                                                <option value="">Select Banner Type</option>
                                                <option value="category">Category</option>
                                                <option value="product">Product</option>
                                                <option value="brand">Brand</option>
                                                <option value="store">Store</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Select Banner Placement <span class="text-danger">*</span></label>
                                            <select class="form-control" name="placement" id="placement">
                                                <option value="">Select Banner Placement</option>
                                                <option value="0">Header</option>
                                                <option value="3">Footer</option>
                                            </select>
                                        </div>

                                        <div class="form-group" id="contentIdGroup" style="display:none;">
                                            <label id="contentIdLabel">Content</label>
                                            <select class="form-control" name="content_id" id="content_id">
                                                <option value="">Select</option>
                                            </select>
                                        </div>

                                        <div class="form-group" id="redirectUrlGroup" style="display:none;">
                                            <label>Redirect URL</label>
                                            <input type="url" class="form-control" name="redirect_url" id="redirect_url" placeholder="https://example.com">
                                        </div>

                                        <div class="form-group">
                                            <label>Banner Image <span class="text-danger">*</span></label>
                                            <input accept=".jpeg, .png, .jpg, .webp" type="file" id="banner_img" class="form-control" name="banner_img" onchange="convertImage(event)">
                                        </div>
                                        <div class="form-group">
                                            <img src="" id="banner_img_webp" style="width:100%">
                                        </div>

                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control" name="status" id="status">
                                                <option value="1">Active</option>
                                                <option value="0">Hidden</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Sort Order</label>
                                            <input type="number" class="form-control" name="sort_order" id="sort_order" value="0" min="0">
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="button" name="sub_product" class="btn btn-primary" onclick="addBanner()">
                                            Add Banner
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <!-- Home Screen Tabs for filtering -->
                                    <ul class="nav nav-tabs" id="bannerScreenTabs">
                                        <?php foreach ($homeScreens as $index => $hs): ?>
                                            <li class="nav-item">
                                                <a class="nav-link <?= $index === 0 ? 'active' : '' ?>" href="#" data-screen-id="<?= $hs['id'] ?>" onclick="filterBannersByScreen(<?= $hs['id'] ?>, this)">
                                                    <?= esc($hs['name']) ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <table id="view_banner" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Sr No</th>
                                                <th>Banner Type</th>
                                                <th>Banner Image</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <?= $this->include('template/footer') ?>
    </div>

    <?= $this->include('template/script') ?>
    <script>
        var activeScreenId = <?= $homeScreens[0]['id'] ?? 1 ?>;

        // Init DataTable
        var bannerTable = $("#view_banner").DataTable({
            paging: true,
            lengthChange: true,
            searching: true,
            ordering: true,
            info: true,
            autoWidth: true,
            responsive: true,
            ajax: {
                url: "/admin/banner/list",
                type: "POST",
                dataType: "json",
                dataSrc: "data",
                data: function(d) {
                    d.home_screen_id = activeScreenId;
                }
            }
        });

        // Category, Brand, Product data from PHP
        var categoriesData = <?= json_encode(array_map(function($c) { return ['id' => $c['id'], 'name' => $c['category_name']]; }, $categories)) ?>;
        var brandsData = <?= isset($brands) ? json_encode(array_map(function($b) { return ['id' => $b['id'], 'name' => $b['brand'] ?? '']; }, $brands)) : '[]' ?>;
        var storesData = <?= isset($stores) ? json_encode(array_map(function($s) { return ['id' => $s['id'], 'name' => $s['name'] ?? '', 'store_name' => $s['store_name'] ?? '']; }, $stores)) : '[]' ?>;

        // Toggle URL/Content fields based on banner type
        $('#banner_type').on('change', function() {
            var val = $(this).val();
            if (val === 'url') {
                $('#redirectUrlGroup').show();
                $('#contentIdGroup').hide();
            } else if (val === '' || val === 'offer') {
                $('#redirectUrlGroup').hide();
                $('#contentIdGroup').hide();
            } else {
                $('#redirectUrlGroup').hide();
                $('#contentIdGroup').show();
                populateContentId(val);
            }
        });

        // Destroy select2 on hide to prevent memory leaks
        $('#contentIdGroup').on('hide', function() {
            if ($('#content_id').hasClass('select2-hidden-accessible')) {
                $('#content_id').select2('destroy');
            }
        });

        function populateContentId(type) {
            var $select = $('#content_id');
            $select.empty().append('<option value="">Select</option>');

            if (type === 'category') {
                $('#contentIdLabel').text('Select Category');
                categoriesData.forEach(function(item) {
                    $select.append('<option value="' + item.id + '">' + item.name + '</option>');
                });
            } else if (type === 'brand') {
                $('#contentIdLabel').text('Select Brand');
                brandsData.forEach(function(item) {
                    $select.append('<option value="' + item.id + '">' + item.name + '</option>');
                });
            } else if (type === 'product') {
                $('#contentIdLabel').text('Select Product');
                var $productSelect = $('<select class="form-control select2-product" name="content_id" id="content_id" style="width:100%"></select>');
                $('#contentIdGroup').html($productSelect);
                $('#content_id').select2({
                    placeholder: 'Search and select products...',
                    ajax: {
                        url: '/admin/banner/search-products',
                        dataType: 'json',
                        delay: 300,
                        data: function (p) { return { q: p.term }; },
                        processResults: function (data) {
                            return { results: data };
                        },
                        cache: true
                    },
                    minimumInputLength: 2
                });
            } else if (type === 'store') {
                $('#contentIdLabel').text('Select Store');
                storesData.forEach(function(item) {
                    $select.append('<option value="' + item.id + '">' + item.name + ' (' + item.store_name + ')</option>');
                });
            }
        }

        function filterBannersByScreen(screenId, el) {
            activeScreenId = screenId;
            $('#bannerScreenTabs .nav-link').removeClass('active');
            $(el).addClass('active');
            bannerTable.ajax.reload();
        }

        function addBanner() {
            var banner_type = $('#banner_type').val();
            var home_screen_id = $('#home_screen_id').val();
            var banner_img = $('#banner_img_webp').attr('src');

            if (banner_type == '' || banner_img == '') {
                toastr.error('Banner type and image are required', 'Admin says');
                return;
            }

            $.ajax({
                url: '/admin/banner/add',
                type: 'POST',
                data: {
                    home_screen_id: home_screen_id,
                    banner_type: banner_type,
                    content_id: $('#content_id').val(),
                    redirect_url: $('#redirect_url').val(),
                    banner_img: banner_img,
                    status: $('#status').val(),
                    sort_order: $('#sort_order').val(),
                    placement: $('#placement').val(),
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message, 'Admin says');
                        bannerTable.ajax.reload();
                        // Reset form
                        $('#banner_type').val('');
                        $('#content_id').val('');
                        $('#redirect_url').val('');
                        $('#banner_img').val('');
                        $('#banner_img_webp').attr('src', '');
                    } else {
                        toastr.error(response.message, 'Admin says');
                    }
                }
            });
        }

        function deletebanner(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/banner/delete',
                        type: 'POST',
                        data: { ban_id: id },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                toastr.success('Banner deleted successfully!', 'Admin says');
                                bannerTable.ajax.reload();
                            } else {
                                toastr.error('Error while deleting banner', 'Admin says');
                            }
                        }
                    });
                }
            });
        }

        function convertImage(event) {
            var input = event.target;
            var reader = new FileReader();
            reader.onload = function() {
                var img = new Image();
                img.onload = function() {
                    var canvas = document.createElement('canvas');
                    canvas.width = img.width;
                    canvas.height = img.height;
                    var ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0);
                    var webpData = canvas.toDataURL('image/webp');
                    document.getElementById('banner_img_webp').src = webpData;
                };
                img.src = reader.result;
            };
            if (input.files && input.files[0]) {
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>
