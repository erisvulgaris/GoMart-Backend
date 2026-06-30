<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home Screens | <?= isset($settings['business_name']) ? esc($settings['business_name']) : '' ?></title>

    <?= $this->include('template/style') ?>
    <style>
        .gradient-preview { display:block; width:100%; height:36px; border-radius:6px; border:1px solid #dee2e6; transition:background .3s; }
        .img-thumb { max-height:60px; border-radius:4px; border:1px solid #dee2e6; }
        .header-type-toggle .btn { flex:1; }
        .section-sep { font-size:11px; letter-spacing:.5px; text-transform:uppercase; color:#6c757d; margin-bottom:8px; margin-top:4px; }
    </style>
</head>

<body class="sidebar-mini control-sidebar-slide-open text-sm layout-fixed <?php echo $settings['thememode'] == 'Light' ? '' : 'dark-mode'; ?> layout-navbar-fixed text-sm" id="body">
    <div class="wrapper">

        <?= $this->include('template/header') ?>
        <?= $this->include('template/sidebar') ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6"><h1 class="m-0">Home Screens</h1></div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Home Screens</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">

                        <!-- ── Add / Edit Form ───────────────────────────────── -->
                        <div class="col-md-4">
                            <div class="card card-<?php echo $settings['primary_color']; ?>">
                                <div class="card-header">
                                    <h3 class="card-title" id="formTitle">Add Home Screen</h3>
                                </div>
                                <form id="homeScreenForm" enctype="multipart/form-data">
                                    <input type="hidden" name="id" id="screen_id" value="">
                                    <div class="card-body">

                                        <div class="form-group">
                                            <label>Name <span class="text-danger">*</span></label>
                                            <input type="text" id="screen_name" class="form-control" placeholder="e.g. Wedding, Christmas" name="name" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Slug</label>
                                            <input type="text" id="screen_slug" class="form-control" placeholder="Auto-generated from name" name="slug">
                                            <small class="text-muted">Leave blank to auto-generate</small>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <select id="screen_status" name="status" class="form-control">
                                                        <option value="1">Active</option>
                                                        <option value="0">Hidden</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label>Sort Order</label>
                                                    <input type="number" id="screen_sort_order" name="sort_order" class="form-control" value="0" min="0">
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="mt-1 mb-2">
                                        <p class="section-sep">Header Appearance</p>

                                        <!-- Header type toggle -->
                                        <div class="form-group">
                                            <label>Background Type</label>
                                            <div class="btn-group btn-group-sm header-type-toggle d-flex" role="group">
                                                <button type="button" class="btn btn-outline-primary active" id="btnTypeGradient" onclick="setHeaderType('gradient')">
                                                    <i class="fas fa-fill-drip mr-1"></i>Gradient
                                                </button>
                                                <button type="button" class="btn btn-outline-primary" id="btnTypeGif" onclick="setHeaderType('gif')">
                                                    <i class="fas fa-image mr-1"></i>GIF / Image
                                                </button>
                                            </div>
                                            <input type="hidden" name="header_type" id="header_type" value="gradient">
                                        </div>

                                        <!-- Gradient fields -->
                                        <div id="gradientSection">
                                            <div class="row mb-1">
                                                <div class="col-6">
                                                    <div class="form-group mb-1">
                                                        <label class="small">Start Color</label>
                                                        <input type="color" name="gradient_start" id="gradient_start"
                                                               class="form-control form-control-sm p-1" value="#56ab2f"
                                                               oninput="updateGradientPreview()">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group mb-1">
                                                        <label class="small">End Color</label>
                                                        <input type="color" name="gradient_end" id="gradient_end"
                                                               class="form-control form-control-sm p-1" value="#a8e063"
                                                               oninput="updateGradientPreview()">
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="gradient-preview mb-2" id="gradientPreview"></span>
                                        </div>

                                        <!-- GIF / Image fields -->
                                        <div id="gifSection" style="display:none">
                                            <div class="form-group">
                                                <label>Header GIF / Image</label>
                                                <input type="file" name="header_gif" id="header_gif_input"
                                                       accept="image/*" class="form-control-file">
                                                <div id="currentGifWrap" class="mt-2" style="display:none">
                                                    <img id="currentGifImg" src="" alt="Current" class="img-thumb mb-1">
                                                    <br>
                                                    <label class="small text-danger cursor-pointer">
                                                        <input type="checkbox" name="clear_header_gif" value="1" id="clear_header_gif">
                                                        Remove current image
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="small">Text Color on Image
                                                    <span class="text-muted">(location, delivery time, etc.)</span>
                                                </label>
                                                <input type="color" name="overlay_text_color" id="overlay_text_color"
                                                       class="form-control form-control-sm p-1" value="#ffffff">
                                                <small class="text-muted">Choose white for dark images, black for light images</small>
                                            </div>
                                        </div>

                                        <hr class="mt-1 mb-2">
                                        <p class="section-sep">Tab Appearance</p>

                                        <!-- Tab Icon -->
                                        <div class="form-group">
                                            <label>Tab Icon <small class="text-muted">(PNG / WebP, optional)</small></label>
                                            <input type="file" name="tab_icon" id="tab_icon_input"
                                                   accept="image/*" class="form-control-file">
                                            <div id="currentIconWrap" class="mt-2" style="display:none">
                                                <img id="currentIconImg" src="" alt="Icon" class="img-thumb mb-1" style="max-height:36px;">
                                                <br>
                                                <label class="small text-danger cursor-pointer">
                                                    <input type="checkbox" name="clear_tab_icon" value="1" id="clear_tab_icon">
                                                    Remove current icon
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Tab Colors -->
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label class="small">Active Color</label>
                                                    <input type="color" name="tab_active_color" id="tab_active_color"
                                                           class="form-control form-control-sm p-1" value="#000000"
                                                           oninput="updateTabPreview()">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label class="small">Inactive Color</label>
                                                    <input type="color" name="tab_inactive_color" id="tab_inactive_color"
                                                           class="form-control form-control-sm p-1" value="#888888"
                                                           oninput="updateTabPreview()">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tab live preview -->
                                        <div class="p-2 rounded d-flex align-items-center gap-2"
                                             style="background:#f1f1f1;gap:16px;" id="tabPreviewBar">
                                            <div class="text-center">
                                                <div id="tabPreviewActive"
                                                     style="font-size:13px;font-weight:700;color:#000000">Home</div>
                                                <div style="height:3px;width:28px;border-radius:3px;background:#000000;margin:3px auto 0" id="tabIndicator"></div>
                                            </div>
                                            <div id="tabPreviewInactive"
                                                 style="font-size:13px;color:#888888">Other</div>
                                        </div>

                                    </div><!-- /.card-body -->
                                    <div class="card-footer">
                                        <button type="button" id="btnSubmit" class="btn btn-primary" onclick="saveScreen()">
                                            Add Home Screen
                                        </button>
                                        <button type="button" id="btnCancel" class="btn btn-secondary d-none" onclick="resetForm()">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- ── List Table ─────────────────────────────────────── -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">All Home Screens</h3>
                                </div>
                                <div class="card-body">
                                    <table id="view_home_screens" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Slug</th>
                                                <th>Header</th>
                                                <th>Tab Icon</th>
                                                <th>Default</th>
                                                <th>Status</th>
                                                <th>Sort</th>
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
        var BASE_URL = '<?= base_url() ?>';

        // ── Auto slug ─────────────────────────────────────────────────────────
        $('#screen_name').on('input', function () {
            var val = $(this).val();
            $('#screen_slug').val(val.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, ''));
            $('#tabPreviewActive').text(val || 'Screen');
        });

        // ── Gradient preview ──────────────────────────────────────────────────
        function updateGradientPreview() {
            var s = $('#gradient_start').val(), e = $('#gradient_end').val();
            $('#gradientPreview').css('background', 'linear-gradient(90deg,' + s + ',' + e + ')');
        }
        updateGradientPreview();

        // ── Tab preview ───────────────────────────────────────────────────────
        function updateTabPreview() {
            var a = $('#tab_active_color').val(), i = $('#tab_inactive_color').val();
            $('#tabPreviewActive').css('color', a);
            $('#tabIndicator').css('background', a);
            $('#tabPreviewInactive').css('color', i);
        }

        // ── Header type toggle ────────────────────────────────────────────────
        function setHeaderType(type) {
            $('#header_type').val(type);
            if (type === 'gradient') {
                $('#btnTypeGradient').addClass('active');
                $('#btnTypeGif').removeClass('active');
                $('#gradientSection').show();
                $('#gifSection').hide();
            } else {
                $('#btnTypeGif').addClass('active');
                $('#btnTypeGradient').removeClass('active');
                $('#gradientSection').hide();
                $('#gifSection').show();
            }
        }

        // ── DataTable ─────────────────────────────────────────────────────────
        $('#view_home_screens').dataTable({
            paging: true, lengthChange: true, searching: true,
            ordering: true, info: true, autoWidth: true, responsive: true,
            ajax: { url: '/admin/home-screens/list', type: 'POST', dataType: 'json', dataSrc: 'data' }
        });

        // ── Save ──────────────────────────────────────────────────────────────
        function saveScreen() {
            var name = $('#screen_name').val().trim();
            if (!name) { toastr.error('Name is required', 'Admin says'); return; }

            var id  = $('#screen_id').val();
            var url = id ? '/admin/home-screens/update' : '/admin/home-screens/add';
            var fd  = new FormData();

            if (id) fd.append('id', id);
            fd.append('name',               name);
            fd.append('slug',               $('#screen_slug').val());
            fd.append('status',             $('#screen_status').val());
            fd.append('sort_order',         $('#screen_sort_order').val());
            fd.append('header_type',        $('#header_type').val());
            fd.append('gradient_start',      $('#gradient_start').val());
            fd.append('gradient_end',        $('#gradient_end').val());
            fd.append('overlay_text_color',  $('#overlay_text_color').val());
            fd.append('tab_active_color',    $('#tab_active_color').val());
            fd.append('tab_inactive_color',  $('#tab_inactive_color').val());

            var gifFile  = $('#header_gif_input')[0].files[0];
            var iconFile = $('#tab_icon_input')[0].files[0];
            if (gifFile)  fd.append('header_gif', gifFile);
            if (iconFile) fd.append('tab_icon',   iconFile);

            if ($('#clear_header_gif').is(':checked')) fd.append('clear_header_gif', '1');
            if ($('#clear_tab_icon').is(':checked'))   fd.append('clear_tab_icon',   '1');

            $.ajax({
                url: url, type: 'POST', data: fd,
                processData: false, contentType: false, dataType: 'json',
                success: function (r) {
                    if (r.success) {
                        toastr.success(r.message, 'Admin says');
                        $('#view_home_screens').DataTable().ajax.reload();
                        resetForm();
                    } else {
                        toastr.error(r.message, 'Admin says');
                    }
                }
            });
        }

        // ── Edit ──────────────────────────────────────────────────────────────
        function editScreen(el) {
            var screen = JSON.parse($(el).attr('data-row'));

            $('#screen_id').val(screen.id);
            $('#screen_name').val(screen.name);
            $('#screen_slug').val(screen.slug);
            $('#screen_status').val(screen.status);
            $('#screen_sort_order').val(screen.sort_order || 0);

            setHeaderType(screen.header_type || 'gradient');

            if (screen.gradient_start)    $('#gradient_start').val(screen.gradient_start);
            if (screen.gradient_end)      $('#gradient_end').val(screen.gradient_end);
            $('#overlay_text_color').val(screen.overlay_text_color || '#ffffff');
            updateGradientPreview();

            if (screen.header_gif) {
                $('#currentGifImg').attr('src', BASE_URL + screen.header_gif);
                $('#currentGifWrap').show();
            } else {
                $('#currentGifWrap').hide();
            }
            $('#clear_header_gif').prop('checked', false);
            $('#header_gif_input').val('');

            if (screen.tab_icon) {
                $('#currentIconImg').attr('src', BASE_URL + screen.tab_icon);
                $('#currentIconWrap').show();
            } else {
                $('#currentIconWrap').hide();
            }
            $('#clear_tab_icon').prop('checked', false);
            $('#tab_icon_input').val('');

            $('#tab_active_color').val(screen.tab_active_color || '#000000');
            $('#tab_inactive_color').val(screen.tab_inactive_color || '#888888');
            $('#tabPreviewActive').text(screen.name).css('color', screen.tab_active_color || '#000000');
            $('#tabIndicator').css('background', screen.tab_active_color || '#000000');
            $('#tabPreviewInactive').css('color', screen.tab_inactive_color || '#888888');

            $('#formTitle').text('Edit: ' + screen.name);
            $('#btnSubmit').text('Update Home Screen');
            $('#btnCancel').removeClass('d-none');
            $('html, body').animate({ scrollTop: 0 }, 300);
        }

        // ── Reset ─────────────────────────────────────────────────────────────
        function resetForm() {
            $('#screen_id, #screen_slug').val('');
            $('#screen_name').val('');
            $('#screen_status').val('1');
            $('#screen_sort_order').val('0');
            setHeaderType('gradient');
            $('#gradient_start').val('#56ab2f');
            $('#gradient_end').val('#a8e063');
            updateGradientPreview();
            $('#header_gif_input, #tab_icon_input').val('');
            $('#currentGifWrap, #currentIconWrap').hide();
            $('#clear_header_gif, #clear_tab_icon').prop('checked', false);
            $('#overlay_text_color').val('#ffffff');
            $('#tab_active_color').val('#000000');
            $('#tab_inactive_color').val('#888888');
            $('#tabPreviewActive').css('color', '#000000').text('Home');
            $('#tabIndicator').css('background', '#000000');
            $('#tabPreviewInactive').css('color', '#888888');
            $('#formTitle').text('Add Home Screen');
            $('#btnSubmit').text('Add Home Screen');
            $('#btnCancel').addClass('d-none');
        }

        // ── Delete ────────────────────────────────────────────────────────────
        function deleteScreen(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This will delete the home screen and all its sections & banners!',
                icon: 'error', showCancelButton: true,
                confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then(function (result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/home-screens/delete', type: 'POST',
                        data: { screen_id: id }, dataType: 'json',
                        success: function (r) {
                            if (r.success) {
                                toastr.success(r.message, 'Admin says');
                                $('#view_home_screens').DataTable().ajax.reload();
                            } else {
                                toastr.error(r.message, 'Admin says');
                            }
                        }
                    });
                }
            });
        }
    </script>
</body>

</html>
