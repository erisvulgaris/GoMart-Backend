<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Banner | <?= isset($settings['business_name']) ? esc($settings['business_name']) : '' ?></title>

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
                            <h1 class="m-0">Edit Banner</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url('admin/banner') ?>">Banner</a></li>
                                <li class="breadcrumb-item active">Edit</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-<?php echo $settings['primary_color']; ?>">
                                <div class="card-header">
                                    <h3 class="card-title">Edit Banner</h3>
                                </div>
                                <form id="editBannerForm" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="banner_id" id="banner_id" value="<?= $banner['id'] ?>">
                                    <div class="card-body">

                                        <div class="form-group">
                                            <label>Select Home Screen <span class="text-danger">*</span></label>
                                            <select class="form-control" name="home_screen_id" id="home_screen_id">
                                                <?php foreach ($homeScreens as $hs): ?>
                                                    <option value="<?= $hs['id'] ?>" <?= ($banner['home_screen_id'] == $hs['id']) ? 'selected' : '' ?>><?= esc($hs['name']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Select Banner Type <span class="text-danger">*</span></label>
                                            <select class="form-control" name="banner_type" id="banner_type">
                                                <option value="">Select Banner Type</option>
                                                <option value="category" <?= ($banner['banner_type'] == 'category') ? 'selected' : '' ?>>Category</option>
                                                <option value="product" <?= ($banner['banner_type'] == 'product') ? 'selected' : '' ?>>Product</option>
                                                <option value="brand" <?= ($banner['banner_type'] == 'brand') ? 'selected' : '' ?>>Brand</option>
                                                <option value="store" <?= ($banner['banner_type'] == 'store') ? 'selected' : '' ?>>Store</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Select Banner Placement <span class="text-danger">*</span></label>
                                            <select class="form-control" name="placement" id="placement">
                                                <option value="">Select Banner Placement</option>
                                                <option value="0" <?= ($banner['placement'] == '0') ? 'selected' : '' ?>>Header</option>
                                                <option value="3" <?= ($banner['placement'] == '3') ? 'selected' : '' ?>>Footer</option>
                                            </select>
                                        </div>


                                        <div class="form-group" id="contentIdGroup" style="<?= ($banner['banner_type'] == 'url' || $banner['banner_type'] == 'offer' || empty($banner['banner_type'])) ? 'display:none;' : '' ?>">
                                            <label id="contentIdLabel"><?= $banner['banner_type'] == 'category' ? 'Select Category' : ($banner['banner_type'] == 'brand' ? 'Select Brand' : ($banner['banner_type'] == 'product' ? 'Product ID' : 'Content')) ?></label>
                                            <?php if ($banner['banner_type'] == 'product'): ?>
                                                <input type="number" class="form-control" name="content_id" id="content_id" placeholder="Enter Product ID" value="<?= esc($banner['content_id'] ?? '') ?>">
                                            <?php else: ?>
                                                <select class="form-control" name="content_id" id="content_id">
                                                    <option value="">Select</option>
                                                    <?php if ($banner['banner_type'] == 'category'): ?>
                                                        <?php foreach ($categories as $category): ?>
                                                            <option value="<?= esc($category['id']); ?>" <?= ($banner['content_id'] == $category['id']) ? 'selected' : '' ?>><?= esc($category['category_name']); ?></option>
                                                        <?php endforeach; ?>
                                                    <?php elseif ($banner['banner_type'] == 'brand' && isset($brands)): ?>
                                                        <?php foreach ($brands as $brand): ?>
                                                            <option value="<?= esc($brand['id']); ?>" <?= ($banner['content_id'] == $brand['id']) ? 'selected' : '' ?>><?= esc($brand['brand'] ?? ''); ?></option>
                                                        <?php endforeach; ?>
                                                    <?php elseif ($banner['banner_type'] == 'store' && isset($stores)): ?>
                                                        <?php foreach ($stores as $store): ?>
                                                            <option value="<?= esc($store['id']); ?>" <?= ($banner['content_id'] == $store['id']) ? 'selected' : '' ?>><?= esc($store['name'] ?? ''); ?> (<?= esc($store['store_name'] ?? ''); ?>)</option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            <?php endif; ?>
                                        </div>

                                        <div class="form-group" id="redirectUrlGroup" style="<?= ($banner['banner_type'] == 'url') ? '' : 'display:none;' ?>">
                                            <label>Redirect URL</label>
                                            <input type="url" class="form-control" name="redirect_url" id="redirect_url" placeholder="https://example.com" value="<?= esc($banner['redirect_url'] ?? '') ?>">
                                        </div>

                                        <div class="form-group">
                                            <label>Banner Image</label>
                                            <input accept=".jpeg, .png, .jpg, .webp" type="file" id="banner_img" class="form-control" name="banner_img" onchange="convertImage(event)">
                                        </div>
                                        <div class="form-group">
                                            <img src="" id="banner_img_webp" style="width:100%">
                                        </div>
                                        <div class="form-group">
                                            <label>Current Image:</label><br>
                                            <img src="<?php echo base_url($banner['image']) ?>" style="width:100%">
                                        </div>

                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control" name="status" id="status">
                                                <option value="1" <?= ($banner['status'] == 1) ? 'selected' : '' ?>>Active</option>
                                                <option value="0" <?= ($banner['status'] == 0) ? 'selected' : '' ?>>Hidden</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Sort Order</label>
                                            <input type="number" class="form-control" name="sort_order" id="sort_order" value="<?= esc($banner['sort_order'] ?? 0) ?>" min="0">
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="button" class="btn btn-primary" onclick="updateBanner()">
                                            Update Banner
                                        </button>
                                        <a href="<?= base_url('admin/banner') ?>" class="btn btn-default">Back</a>
                                    </div>
                                </form>
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
        // Category, Brand, Store data from PHP
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

        function populateContentId(type) {
            var $container = $('#contentIdGroup');
            // Replace input/select with fresh select and destroy existing select2
            if ($('#content_id').hasClass('select2-hidden-accessible')) {
                $('#content_id').select2('destroy');
            }
            $container.find('#content_id').remove();

            if (type === 'category') {
                $('#contentIdLabel').text('Select Category');
                var $select = $('<select class="form-control" name="content_id" id="content_id"><option value="">Select</option></select>');
                categoriesData.forEach(function(item) {
                    $select.append('<option value="' + item.id + '">' + item.name + '</option>');
                });
                $container.html($select);
            } else if (type === 'brand') {
                $('#contentIdLabel').text('Select Brand');
                var $select = $('<select class="form-control" name="content_id" id="content_id"><option value="">Select</option></select>');
                brandsData.forEach(function(item) {
                    $select.append('<option value="' + item.id + '">' + item.name + '</option>');
                });
                $container.html($select);
            } else if (type === 'product') {
                $('#contentIdLabel').text('Select Product');
                var $productSelect = $('<select class="form-control select2-product" name="content_id" id="content_id" style="width:100%"></select>');
                $container.html($productSelect);
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
                }).val('<?= esc($banner['content_id'] ?? '') ?>').trigger('change');
            } else if (type === 'store') {
                $('#contentIdLabel').text('Select Store');
                var $select = $('<select class="form-control" name="content_id" id="content_id"><option value="">Select</option></select>');
                storesData.forEach(function(item) {
                    $select.append('<option value="' + item.id + '">' + item.name + ' (' + (item.store_name || '') + ')</option>');
                });
                $container.html($select);
                $('#content_id').val('<?= esc($banner['content_id'] ?? '') ?>');
            }
        }

        // Pre-populate if editing existing banner
        $(document).ready(function() {
            $('#banner_type').trigger('change');
        });

        function updateBanner() {
            var banner_type = $('#banner_type').val();
            var home_screen_id = $('#home_screen_id').val();
            var banner_id = $('#banner_id').val();

            if (banner_type == '') {
                toastr.error('Banner type is required', 'Admin says');
                return;
            }

            $.ajax({
                url: '/admin/banner/update',
                type: 'POST',
                data: {
                    banner_id: banner_id,
                    home_screen_id: home_screen_id,
                    banner_type: banner_type,
                    content_id: $('#content_id').val(),
                    redirect_url: $('#redirect_url').val(),
                    banner_img: $('#banner_img_webp').attr('src') || '',
                    status: $('#status').val(),
                    sort_order: $('#sort_order').val(),
                    placement: $('#placement').val(),
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message, 'Admin says');
                    } else {
                        toastr.error(response.message, 'Admin says');
                    }
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
