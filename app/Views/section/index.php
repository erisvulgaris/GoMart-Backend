<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sections | <?= isset($settings['business_name']) ? esc($settings['business_name']) : '' ?></title>

    <?= $this->include('template/style') ?>
    <style>
        .section-card { border: 1px solid #dee2e6; border-radius: 8px; margin-bottom: 15px; padding: 15px 15px 15px 46px; position: relative; background: #fff; }
        .section-card:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .drag-handle { position: absolute; left: 0; top: 0; bottom: 0; width: 36px; display: flex; align-items: center; justify-content: center; cursor: move; color: #adb5bd; border-right: 1px solid #dee2e6; border-radius: 8px 0 0 8px; }
        .drag-handle:hover { background: #f8f9fa; color: #6c757d; }
        .section-card .section-title { font-weight: 600; font-size: 16px; }
        .section-card .section-desc  { font-size: 13px; color: #6c757d; margin-top: 2px; }
        .section-card .section-meta  { color: #6c757d; font-size: 13px; }
        .section-card .section-actions { position: absolute; top: 10px; right: 10px; }
        .home-screen-tabs .nav-link { cursor: pointer; }
        .home-screen-tabs .nav-link.active { font-weight: 600; }
        .ui-sortable-helper { box-shadow: 0 4px 16px rgba(0,0,0,0.2); }
        .dark-mode .section-card { background: #343a40; border-color: #6c757d; }
        #sectionModal .modal-dialog { max-width: 720px; }
        .cond { display: none; }
        .style-option-card { border: 2px solid #dee2e6; border-radius: 8px; padding: 12px 10px; cursor: pointer; text-align: center; transition: all .2s; height: 100%; }
        .style-option-card:hover { border-color: #007bff; background: #f0f7ff; }
        .style-option-card.selected { border-color: #007bff; background: #e8f3ff; }
        .style-option-card .s-icon  { font-size: 22px; margin-bottom: 4px; }
        .style-option-card .s-label { font-size: 12px; font-weight: 600; }
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
                        <div class="col-sm-6"><h1 class="m-0">Home Sections</h1></div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Sections</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs home-screen-tabs" id="homeScreenTabs">
                                <?php foreach ($homeScreens as $index => $screen): ?>
                                    <li class="nav-item">
                                        <a class="nav-link <?= $index === 0 ? 'active' : '' ?>"
                                           data-screen-id="<?= $screen['id'] ?>"
                                           data-toggle="tab"
                                           href="#screen_<?= $screen['id'] ?>">
                                            <?= esc($screen['name']) ?>
                                            <?php if ($screen['is_default']): ?>
                                                <span class="badge badge-success ml-1">Default</span>
                                            <?php endif; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <button type="button" class="btn btn-primary" onclick="openAddModal()">
                                    <i class="fi fi-tr-plus"></i> Add Section
                                </button>
                            </div>
                            <div class="tab-content" id="homeScreenTabContent">
                                <?php foreach ($homeScreens as $index => $screen): ?>
                                    <div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>"
                                         id="screen_<?= $screen['id'] ?>"
                                         data-screen-id="<?= $screen['id'] ?>">
                                        <div class="sections-container" id="sections_<?= $screen['id'] ?>">
                                            <div class="text-center py-4 text-muted">Loading sections...</div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <?= $this->include('template/footer') ?>
    </div>

    <!-- ═══════════════════ Add / Edit Section Modal ═══════════════════ -->
    <div class="modal fade" id="sectionModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sectionModalTitle">Add Section</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <form id="sectionForm">
                        <input type="hidden" id="edit_section_id"      name="section_id"    value="">
                        <input type="hidden" id="modal_home_screen_id" name="home_screen_id" value="">
                        <input type="hidden" id="sec_section_style"    name="section_style" value="category_list">

                        <!-- ── Section Type Picker ── -->
                        <div class="form-group">
                            <label class="d-block font-weight-bold mb-2">Section Type <span class="text-danger">*</span></label>
                            <div class="row no-gutters" style="gap:8px; flex-wrap:wrap; display:flex;">
                                <div style="flex:1; min-width:100px;">
                                    <div class="style-option-card selected" data-style="category_list" onclick="pickStyle('category_list')">
                                        <div class="s-icon">🗂️</div>
                                        <div class="s-label">Category List</div>
                                    </div>
                                </div>
                                <div style="flex:1; min-width:100px;">
                                    <div class="style-option-card" data-style="best_seller" onclick="pickStyle('best_seller')">
                                        <div class="s-icon">🏆</div>
                                        <div class="s-label">Best Seller Category</div>
                                    </div>
                                </div>
                                <div style="flex:1; min-width:100px;">
                                    <div class="style-option-card" data-style="product_list" onclick="pickStyle('product_list')">
                                        <div class="s-icon">📦</div>
                                        <div class="s-label">Product List</div>
                                    </div>
                                </div>
                                <div style="flex:1; min-width:100px;">
                                    <div class="style-option-card" data-style="highlight" onclick="pickStyle('highlight')">
                                        <div class="s-icon">✨</div>
                                        <div class="s-label">Highlight</div>
                                    </div>
                                </div>
                                <div style="flex:1; min-width:100px;">
                                    <div class="style-option-card" data-style="shop_by_brand" onclick="pickStyle('shop_by_brand')">
                                        <div class="s-icon">🏷️</div>
                                        <div class="s-label">Shop by Brand</div>
                                    </div>
                                </div>
                                <div style="flex:1; min-width:100px;">
                                    <div class="style-option-card" data-style="shop_by_seller" onclick="pickStyle('shop_by_seller')">
                                        <div class="s-icon">🏪</div>
                                        <div class="s-label">Shop by Seller</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- ── Common: Title / Description / Status / BG ── -->
                        <div class="row ">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Title <span class="text-danger">*</span></label>
                                    <input type="text"  id="sec_title" name="title" class="form-control" placeholder="e.g. Fresh Vegetables" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="text"  id="sec_description" name="description" class="form-control" placeholder="Short subtitle shown below title">
                                </div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select id="sec_status" name="status" class="form-control">
                                        <option value="1" >Show</option>
                                        <option value="0">Hidden</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Background Color</label>
                                    <input type="color" id="sec_bg_color" name="bg_color" class="form-control" value="#FFFFFF">
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- ══════════ 1. Category List Panel ══════════ -->
                        <div id="panel_category_list" class="cond">
                            <h6 class="text-primary mb-3">🗂️ Category List Options</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Selection</label>
                                        <select id="cat_section_type" class="form-control" onchange="onCatTypeChange()">
                                            <option value="0">All Categories (Dynamic)</option>
                                            <option value="1">Select Specific Categories</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>No. of Rows</label>
                                        <input type="number" id="cat_no_of_row" class="form-control" value="1" min="1">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Max Items</label>
                                        <input type="number" id="cat_no_of_content" class="form-control" value="10" min="1">
                                    </div>
                                </div>
                            </div>
                            <div id="catManualField" class="cond">
                                <div class="form-group">
                                    <label>Select Categories</label>
                                    <select id="sec_manual_categories" class="form-control select2-cat" multiple style="width:100%">
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= $cat['id'] ?>"><?= esc($cat['category_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>View All Button</label>
                                        <select id="cat_view_all" class="form-control">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ══════════ 2. Best Seller Category Panel ══════════ -->
                        <div id="panel_best_seller" class="cond">
                            <h6 class="text-primary mb-3">🏆 Best Seller Category Options</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Selection</label>
                                        <select id="bs_section_type" class="form-control" onchange="onBsTypeChange()">
                                            <option value="0">All Best Seller Categories (Dynamic)</option>
                                            <option value="1">Select Specific Best Seller Categories</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>No. of Rows</label>
                                        <input type="number" id="bs_no_of_row" class="form-control" value="1" min="1">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Max Items</label>
                                        <input type="number" id="bs_no_of_content" class="form-control" value="10" min="1">
                                    </div>
                                </div>
                            </div>
                            <div id="bsManualField" class="cond">
                                <div class="form-group">
                                    <label>Select Best Seller Categories</label>
                                    <select id="sec_manual_bs_categories" class="form-control select2-bs" multiple style="width:100%">
                                        <?php foreach ($bestSellerCategories as $cat): ?>
                                            <option value="<?= $cat['id'] ?>"><?= esc($cat['category_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (empty($bestSellerCategories)): ?>
                                        <small class="text-warning">No best seller categories found. Mark categories as best seller in the Category module.</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>View All Button</label>
                                        <select id="bs_view_all" class="form-control">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ══════════ 3. Product List Panel ══════════ -->
                        <div id="panel_product_list" class="cond">
                            <h6 class="text-primary mb-3">📦 Product List Options</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Selection</label>
                                        <select id="pl_section_type" class="form-control" onchange="onPlTypeChange()">
                                            <option value="0">Dynamic (Filter by Category / Brand / Seller)</option>
                                            <option value="1">Manual (Select specific products)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>No. of Rows</label>
                                        <input type="number" id="pl_no_of_row" class="form-control" value="1" min="1">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Max Items</label>
                                        <input type="number" id="pl_no_of_content" class="form-control" value="10" min="1">
                                    </div>
                                </div>
                            </div>

                            <!-- Dynamic filters -->
                            <div id="plDynamicFields">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Category <small class="text-muted">(optional)</small></label>
                                            <select id="pl_category_id" class="form-control" onchange="loadSubcategories(this.value)">
                                                <option value="">All Categories</option>
                                                <?php foreach ($categories as $cat): ?>
                                                    <option value="<?= $cat['id'] ?>"><?= esc($cat['category_name']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Sub Category <small class="text-muted">(optional)</small></label>
                                            <select id="pl_sub_category_id" class="form-control">
                                                <option value="">All Sub Categories</option>
                                                <?php foreach ($subcategories as $sub): ?>
                                                    <option value="<?= $sub['id'] ?>" data-cat="<?= $sub['category_id'] ?>"><?= esc($sub['name']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Brand <small class="text-muted">(optional)</small></label>
                                            <select id="pl_brand_id" class="form-control">
                                                <option value="">All Brands</option>
                                                <?php foreach ($brands as $brand): ?>
                                                    <option value="<?= $brand['id'] ?>"><?= esc($brand['brand']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Seller <small class="text-muted">(optional)</small></label>
                                            <select id="pl_seller_id" class="form-control">
                                                <option value="">All Sellers</option>
                                                <?php foreach ($sellers as $seller): ?>
                                                    <option value="<?= $seller['id'] ?>"><?= esc($seller['store_name'] ?? $seller['name'] ?? 'Seller #'.$seller['id']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Manual product picker -->
                            <div id="plManualField" class="cond">
                                <div class="form-group">
                                    <label>Select Products</label>
                                    <select id="sec_manual_products" class="form-control select2-products" multiple style="width:100%"></select>
                                </div>
                            </div>

                            <!-- Sort By + View All + Load More -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Sort By</label>
                                        <select id="pl_sort_by" class="form-control">
                                            <option value="default">Default</option>
                                            <option value="best_selling">Best Selling</option>
                                            <option value="low_to_high">Price: Low to High</option>
                                            <option value="high_to_low">Price: High to Low</option>
                                            <option value="max_discount">Maximum Discount %</option>
                                            <option value="best_rated">Best Rated</option>
                                            <option value="alphabetical">Alphabetical</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>View All</label>
                                        <select id="pl_view_all" class="form-control">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Load More</label>
                                        <select id="pl_load_more" class="form-control">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ══════════ 4. Highlight Panel ══════════ -->
                        <div id="panel_highlight" class="cond">
                            <h6 class="text-primary mb-3">✨ Highlight Options</h6>
                            <div class="alert alert-info py-2 px-3">
                                Highlights appear as a single horizontal row. Configure where each card redirects (brand / category / subcategory / seller) inside the <a href="<?= base_url('admin/highlights') ?>" target="_blank">Highlights module</a>.
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Selection</label>
                                        <select id="hl_section_type" class="form-control" onchange="onHlTypeChange()">
                                            <option value="0">All Active Highlights (Dynamic)</option>
                                            <option value="1">Select Specific Highlights</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Max Items</label>
                                        <input type="number" id="hl_no_of_content" class="form-control" value="10" min="1">
                                    </div>
                                </div>
                            </div>
                            <div id="hlManualField" class="cond">
                                <div class="form-group">
                                    <label>Select Highlights</label>
                                    <select id="sec_manual_highlights" class="form-control select2-hl" multiple style="width:100%">
                                        <?php foreach ($highlights as $hl): ?>
                                            <option value="<?= $hl['id'] ?>"><?= esc($hl['title'] ?: 'Highlight #'.$hl['id']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- ══════════ 5. Shop by Brand Panel ══════════ -->
                        <div id="panel_shop_by_brand" class="cond">
                            <h6 class="text-primary mb-3">🏷️ Shop by Brand Options</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Selection</label>
                                        <select id="sbb_section_type" class="form-control" onchange="onSbbTypeChange()">
                                            <option value="0">All Brands (Dynamic)</option>
                                            <option value="1">Select Specific Brands</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Max Items</label>
                                        <input type="number" id="sbb_no_of_content" class="form-control" value="10" min="1">
                                    </div>
                                </div>
                            </div>
                            <div id="sbbManualField" class="cond">
                                <div class="form-group">
                                    <label>Select Brands</label>
                                    <select id="sec_manual_brands" class="form-control select2-sbb" multiple style="width:100%">
                                        <?php foreach ($brands as $brand): ?>
                                            <option value="<?= $brand['id'] ?>"><?= esc($brand['brand']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- ══════════ 6. Shop by Seller Panel ══════════ -->
                        <div id="panel_shop_by_seller" class="cond">
                            <h6 class="text-primary mb-3">🏪 Shop by Seller Options</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Selection</label>
                                        <select id="sbs_section_type" class="form-control" onchange="onSbsTypeChange()">
                                            <option value="0">All Sellers (Dynamic)</option>
                                            <option value="1">Select Specific Sellers</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Max Items</label>
                                        <input type="number" id="sbs_no_of_content" class="form-control" value="10" min="1">
                                    </div>
                                </div>
                            </div>
                            <div id="sbsManualField" class="cond">
                                <div class="form-group">
                                    <label>Select Sellers</label>
                                    <select id="sec_manual_sellers" class="form-control select2-sbs" multiple style="width:100%">
                                        <?php foreach ($sellers as $seller): ?>
                                            <option value="<?= $seller['id'] ?>"><?= esc($seller['store_name'] ?? $seller['name'] ?? 'Seller #'.$seller['id']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveSection()">Save Section</button>
                </div>
            </div>
        </div>
    </div>

    <?= $this->include('template/script') ?>

    <script>
        var SUBCATEGORIES = <?= json_encode(array_map(fn($s) => ['id' => $s['id'], 'name' => $s['name'], 'category_id' => $s['category_id']], $subcategories)) ?>;
        var activeScreenId = <?= $homeScreens[0]['id'] ?? 1 ?>;
        var currentStyle   = 'category_list';

        // ── Init ──────────────────────────────────────────────────────────────
        $(document).ready(function () {
            $('#sec_manual_categories').select2({ placeholder: 'Select categories...',              dropdownParent: $('#sectionModal') });
            $('#sec_manual_bs_categories').select2({ placeholder: 'Select best seller categories...', dropdownParent: $('#sectionModal') });
            $('#sec_manual_highlights').select2({ placeholder: 'Select highlights...',              dropdownParent: $('#sectionModal') });
            $('#sec_manual_brands').select2({ placeholder: 'Select brands...',                      dropdownParent: $('#sectionModal') });
            $('#sec_manual_sellers').select2({ placeholder: 'Select sellers...',                    dropdownParent: $('#sectionModal') });

            $('#sec_manual_products').select2({
                placeholder: 'Search and select products...',
                dropdownParent: $('#sectionModal'),
                ajax: {
                    url: '/admin/sections/search-products',
                    dataType: 'json',
                    delay: 300,
                    data: function (p) { return { q: p.term }; },
                    processResults: function (data) {
                        return { results: data.map(function (i) { return { id: i.id, text: i.text }; }) };
                    },
                    cache: true
                },
                minimumInputLength: 2
            });

            loadSections(activeScreenId);

            $('.home-screen-tabs .nav-link').on('click', function () {
                activeScreenId = $(this).data('screen-id');
                loadSections(activeScreenId);
            });

            pickStyle('category_list', false);
        });

        // ── Style picker ──────────────────────────────────────────────────────
        function pickStyle(style, reset) {
            currentStyle = style;
            $('#sec_section_style').val(style);
            $('.style-option-card').removeClass('selected');
            $('.style-option-card[data-style="' + style + '"]').addClass('selected');
            $('[id^="panel_"]').hide();
            $('#panel_' + style).show();
            if (reset !== false) resetStyleFields();
        }

        function resetStyleFields() {
            $('#cat_section_type, #bs_section_type, #pl_section_type, #hl_section_type, #sbb_section_type, #sbs_section_type').val('0');
            $('#catManualField, #bsManualField, #plManualField, #hlManualField, #sbbManualField, #sbsManualField').hide();
            $('#plDynamicFields').show();
            $('#sec_manual_categories, #sec_manual_bs_categories, #sec_manual_highlights, #sec_manual_brands, #sec_manual_sellers').val(null).trigger('change');
            $('#sec_manual_products').empty().trigger('change');
            $('#pl_category_id, #pl_sub_category_id, #pl_brand_id, #pl_seller_id').val('');
            $('#pl_sort_by').val('default');
            $('#pl_sub_category_id').find('option:not(:first)').remove();
        }

        // Per-style section_type toggles
        function onCatTypeChange()  { $('#catManualField').toggle($('#cat_section_type').val() == '1'); }
        function onBsTypeChange()   { $('#bsManualField').toggle($('#bs_section_type').val()  == '1'); }
        function onHlTypeChange()   { $('#hlManualField').toggle($('#hl_section_type').val()  == '1'); }
        function onSbbTypeChange()  { $('#sbbManualField').toggle($('#sbb_section_type').val() == '1'); }
        function onSbsTypeChange()  { $('#sbsManualField').toggle($('#sbs_section_type').val() == '1'); }

        function onPlTypeChange() {
            var manual = $('#pl_section_type').val() == '1';
            $('#plDynamicFields').toggle(!manual);
            $('#plManualField').toggle(manual);
        }

        // Sub-category loader
        function loadSubcategories(catId) {
            var $sub = $('#pl_sub_category_id');
            $sub.find('option:not(:first)').remove();
            if (!catId) return;
            SUBCATEGORIES.filter(function (s) { return s.category_id == catId; })
                .forEach(function (s) { $sub.append('<option value="' + s.id + '">' + s.name + '</option>'); });
        }

        // ── Render section cards ──────────────────────────────────────────────
        var STYLE_META = {
            category_list:   { label: 'Category List',       color: 'badge-info',      icon: '🗂️' },
            best_seller:     { label: 'Best Seller',          color: 'badge-danger',    icon: '🏆' },
            product_list:    { label: 'Product List',         color: 'badge-warning',   icon: '📦' },
            highlight:       { label: 'Highlight',            color: 'badge-success',   icon: '✨' },
            shop_by_brand:   { label: 'Shop by Brand',        color: 'badge-primary',   icon: '🏷️' },
            shop_by_seller:  { label: 'Shop by Seller',       color: 'badge-secondary', icon: '🏪' },
        };
        var STYLE_ENTITY = {
            category_list:  { s: 'category',  p: 'categories', z: 'No categories found.' },
            best_seller:    { s: 'category',  p: 'categories', z: 'No best seller categories found.' },
            product_list:   { s: 'product',   p: 'products',   z: 'No products with variants found.' },
            highlight:      { s: 'highlight', p: 'highlights', z: 'No active highlights found.' },
            shop_by_brand:  { s: 'brand',     p: 'brands',     z: 'No brands with active products found.' },
            shop_by_seller: { s: 'seller',    p: 'sellers',    z: 'No sellers found.' },
        };

        function loadSections(screenId) {
            var $c = $('#sections_' + screenId);
            $c.html('<div class="text-center py-4 text-muted">Loading sections...</div>');
            $.post('/admin/sections/list', { home_screen_id: screenId }, function (r) {
                if (r.success && r.data.length > 0) {
                    var html = '';
                    r.data.forEach(function (s) { html += renderCard(s); });
                    $c.html(html);
                    $c.sortable({ handle: '.drag-handle', update: function () { saveSortOrder(screenId); } });
                } else {
                    $c.html('<div class="text-center py-4 text-muted">No sections yet. Click "Add Section" to create one.</div>');
                }
            }, 'json');
        }

        function renderCard(s) {
            var style  = s.section_style || 'category_list';
            var meta   = STYLE_META[style]   || { label: style, color: 'badge-secondary', icon: '' };
            var entity = STYLE_ENTITY[style] || { s: 'item', p: 'items', z: 'Nothing found.' };
            var cnt    = s.live_count != null ? parseInt(s.live_count) : null;
            var typeLabel = s.section_type == 1
                ? '<span class="badge badge-success">Manual</span>'
                : '<span class="badge badge-primary">Dynamic</span>';
            var cntBadge = '';
            if (cnt === 0) {
                cntBadge = ' <span class="badge badge-danger" title="' + entity.z + '">⚠ 0 ' + entity.p + '</span>';
            } else if (cnt > 0) {
                cntBadge = ' <span class="badge badge-success">' + cnt + ' ' + (cnt == 1 ? entity.s : entity.p) + '</span>';
            }
            var statusBtn = s.status == 1
                ? '<button class="btn btn-success btn-xs" onclick="toggleStatus(' + s.id + ', 0)"><i class="fi fi-tr-eye"></i> Visible</button>'
                : '<button class="btn btn-danger  btn-xs" onclick="toggleStatus(' + s.id + ', 1)"><i class="fi fi-tr-eye-crossed"></i> Hidden</button>';

            return '<div class="section-card" data-id="' + s.id + '">' +
                '<div class="drag-handle" title="Drag to reorder"><i class="fi fi-tr-grip-dots-vertical"></i></div>' +
                '<div class="section-actions">' +
                    statusBtn + ' ' +
                    '<button class="btn btn-primary btn-xs" onclick="editSection(' + s.id + ')"><i class="fi fi-tr-customize-edit"></i> Edit</button> ' +
                    '<button class="btn btn-danger  btn-xs" onclick="deleteSection(' + s.id + ')"><i class="fi fi-tr-trash-xmark"></i></button>' +
                '</div>' +
                '<div class="section-title">' + meta.icon + ' ' + escHtml(s.title) + cntBadge + '</div>' +
                (s.description ? '<div class="section-desc">' + escHtml(s.description) + '</div>' : '') +
                '<div class="section-meta mt-1">' +
                    '<span class="badge ' + meta.color + '">' + meta.label + '</span> ' +
                    typeLabel +
                    ' | Max: ' + s.no_of_content +
                    (s.no_of_row > 0 ? ' | Rows: ' + s.no_of_row : '') +
                    (s.section_type == 1 ? ' | Pinned: ' + (s.manual_items || 0) : '') +
                    ' | <small>BG: ' + escHtml(s.bg_color || '#FFFFFF') + '</small>' +
                '</div>' +
            '</div>';
        }

        function escHtml(str) {
            if (!str) return '';
            return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        // ── Modal open ────────────────────────────────────────────────────────
        function openAddModal() {
            resetForm();
            $('#modal_home_screen_id').val(activeScreenId);
            $('#sectionModalTitle').text('Add Section');
            $('#sectionModal').modal('show');
        }

        function editSection(sectionId) {
            $.post('/admin/sections/list', { home_screen_id: activeScreenId }, function (r) {
                if (!r.success) return;
                var s = r.data.find(function (x) { return x.id == sectionId; });
                if (!s) return;
                resetForm();
                fillForm(s);
                $('#sectionModalTitle').text('Edit Section');
                $('#sectionModal').modal('show');
            }, 'json');
        }

        function fillForm(s) {
            var style = s.section_style || 'category_list';
            pickStyle(style, false);

            $('#edit_section_id').val(s.id);
            $('#modal_home_screen_id').val(s.home_screen_id);
            $('#sec_title').val(s.title);
            $('#sec_description').val(s.description || '');
            $('#sec_status').val(s.status);
            $('#sec_bg_color').val(s.bg_color || '#FFFFFF');

            var sType = s.section_type == 1 ? '1' : '0';

            if (style === 'category_list') {
                $('#cat_section_type').val(sType).trigger('change');
                $('#cat_no_of_row').val(s.no_of_row || 1);
                $('#cat_no_of_content').val(s.no_of_content || 10);
                $('#cat_view_all').val(s.view_all || 0);
            } else if (style === 'best_seller') {
                $('#bs_section_type').val(sType).trigger('change');
                $('#bs_no_of_row').val(s.no_of_row || 1);
                $('#bs_no_of_content').val(s.no_of_content || 10);
                $('#bs_view_all').val(s.view_all || 0);
            } else if (style === 'product_list') {
                $('#pl_section_type').val(sType);
                $('#pl_no_of_row').val(s.no_of_row || 1);
                $('#pl_no_of_content').val(s.no_of_content || 10);
                $('#pl_sort_by').val(s.sort_by || 'default');
                $('#pl_view_all').val(s.view_all || 0);
                $('#pl_load_more').val(s.load_more || 0);
                if (sType == '0') {
                    $('#pl_category_id').val(s.category_id || '');
                    loadSubcategories(s.category_id || '');
                    $('#pl_sub_category_id').val(s.sub_category_id || '');
                    $('#pl_brand_id').val(s.brand_id || '');
                    $('#pl_seller_id').val(s.seller_id || '');
                }
                onPlTypeChange();
            } else if (style === 'highlight') {
                $('#hl_section_type').val(sType).trigger('change');
                $('#hl_no_of_content').val(s.no_of_content || 10);
            } else if (style === 'shop_by_brand') {
                $('#sbb_section_type').val(sType).trigger('change');
                $('#sbb_no_of_content').val(s.no_of_content || 10);
            } else if (style === 'shop_by_seller') {
                $('#sbs_section_type').val(sType).trigger('change');
                $('#sbs_no_of_content').val(s.no_of_content || 10);
            }

            // Load pinned items for manual sections
            if (s.section_type == 1) {
                $.post('/admin/sections/get-manual-items', { section_id: s.id, section_style: style }, function (items) {
                    if (style === 'category_list') {
                        $('#sec_manual_categories').val(items.map(function (i) { return i.category_id; })).trigger('change');
                    } else if (style === 'best_seller') {
                        $('#sec_manual_bs_categories').val(items.map(function (i) { return i.category_id; })).trigger('change');
                    } else if (style === 'product_list') {
                        items.forEach(function (i) {
                            $('#sec_manual_products').append(new Option(i.product_name || 'Product #' + i.product_id, i.product_id, true, true));
                        });
                        $('#sec_manual_products').trigger('change');
                    } else if (style === 'highlight') {
                        $('#sec_manual_highlights').val(items.map(function (i) { return i.highlight_id; })).trigger('change');
                    } else if (style === 'shop_by_brand') {
                        $('#sec_manual_brands').val(items.map(function (i) { return i.brand_id; })).trigger('change');
                    } else if (style === 'shop_by_seller') {
                        $('#sec_manual_sellers').val(items.map(function (i) { return i.seller_id; })).trigger('change');
                    }
                }, 'json');
            }
        }

        function resetForm() {
            $('#edit_section_id').val('');
            $('#sectionForm')[0].reset();
            $('#sec_bg_color').val('#FFFFFF');
            currentStyle = 'category_list';
            resetStyleFields();
            pickStyle('category_list', false);
        }

        // ── Save ──────────────────────────────────────────────────────────────
        function saveSection() {
            var title = $.trim($('#sec_title').val());
            if (!title) { toastr.error('Title is required', 'Admin says'); return; }

            var style     = currentStyle;
            var sectionId = $('#edit_section_id').val();

            var sTypeSelectors = {
                category_list: '#cat_section_type', best_seller: '#bs_section_type',
                product_list: '#pl_section_type',   highlight: '#hl_section_type',
                shop_by_brand: '#sbb_section_type', shop_by_seller: '#sbs_section_type',
            };
            var sectionType = $(sTypeSelectors[style]).val() || '0';

            var formData = {
                section_id:     sectionId,
                home_screen_id: $('#modal_home_screen_id').val(),
                section_style:  style,
                title:          title,
                description:    $('#sec_description').val(),
                status:         $('#sec_status').val(),
                bg_color:       $('#sec_bg_color').val(),
                section_type:   sectionType,
            };

            if (style === 'category_list') {
                formData.no_of_row     = $('#cat_no_of_row').val();
                formData.no_of_content = $('#cat_no_of_content').val();
                formData.view_all      = $('#cat_view_all').val();
                if (sectionType == '1') formData['manual_category_ids[]'] = $('#sec_manual_categories').val();

            } else if (style === 'best_seller') {
                formData.no_of_row     = $('#bs_no_of_row').val();
                formData.no_of_content = $('#bs_no_of_content').val();
                formData.view_all      = $('#bs_view_all').val();
                if (sectionType == '1') formData['manual_category_ids[]'] = $('#sec_manual_bs_categories').val();

            } else if (style === 'product_list') {
                formData.no_of_row     = $('#pl_no_of_row').val();
                formData.no_of_content = $('#pl_no_of_content').val();
                formData.sort_by       = $('#pl_sort_by').val();
                formData.view_all      = $('#pl_view_all').val();
                formData.load_more     = $('#pl_load_more').val();
                if (sectionType == '0') {
                    formData.category_id     = $('#pl_category_id').val();
                    formData.sub_category_id = $('#pl_sub_category_id').val();
                    formData.brand_id        = $('#pl_brand_id').val();
                    formData.seller_id       = $('#pl_seller_id').val();
                } else {
                    formData['manual_product_ids[]'] = $('#sec_manual_products').val();
                }

            } else if (style === 'highlight') {
                formData.no_of_content = $('#hl_no_of_content').val();
                if (sectionType == '1') formData['manual_highlight_ids[]'] = $('#sec_manual_highlights').val();

            } else if (style === 'shop_by_brand') {
                formData.no_of_content = $('#sbb_no_of_content').val();
                if (sectionType == '1') formData['manual_brand_ids[]'] = $('#sec_manual_brands').val();

            } else if (style === 'shop_by_seller') {
                formData.no_of_content = $('#sbs_no_of_content').val();
                if (sectionType == '1') formData['manual_seller_ids[]'] = $('#sec_manual_sellers').val();
            }

            var url = sectionId ? '/admin/sections/update' : '/admin/sections/add';
            $.ajax({
                url: url, type: 'POST', data: formData, dataType: 'json',
                success: function (r) {
                    if (r.success) {
                        toastr.success(r.message, 'Admin says');
                        $('#sectionModal').modal('hide');
                        loadSections(activeScreenId);
                    } else {
                        toastr.error(r.message, 'Admin says');
                    }
                }
            });
        }

        // ── Delete ────────────────────────────────────────────────────────────
        function deleteSection(id) {
            Swal.fire({
                title: 'Are you sure?', text: 'This section will be permanently deleted!',
                icon: 'error', showCancelButton: true,
                confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then(function (r) {
                if (!r.isConfirmed) return;
                $.post('/admin/sections/delete', { section_id: id }, function (resp) {
                    if (resp.success) { toastr.success(resp.message, 'Admin says'); loadSections(activeScreenId); }
                    else              { toastr.error(resp.message,  'Admin says'); }
                }, 'json');
            });
        }

        // ── Toggle Status ─────────────────────────────────────────────────────
        function toggleStatus(id, newStatus) {
            $.post('/admin/sections/toggle-status', { section_id: id, status: newStatus }, function (r) {
                if (r.success) { toastr.success(r.message, 'Admin says'); loadSections(activeScreenId); }
            }, 'json');
        }

        // ── Sort Order ────────────────────────────────────────────────────────
        function saveSortOrder(screenId) {
            var items = [];
            $('#sections_' + screenId + ' .section-card').each(function (i) {
                items.push({ id: $(this).data('id'), sort_order: i });
            });
            $.post('/admin/sections/update-sort-order', { items: items }, function (r) {
                if (r.success) toastr.success('Sort order saved', 'Admin says');
            }, 'json');
        }
    </script>
</body>

</html>
