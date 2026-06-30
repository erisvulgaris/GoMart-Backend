<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Product | <?= isset($settings['business_name']) ? esc($settings['business_name']) : '' ?></title>

    <?= $this->include('template/style') ?>

    <!-- Include Dropzone CSS and JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>

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
                            <h1 class="m-0">Edit Product</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
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
                        <div class="col-md-12">
                            <form method="post" enctype="multipart/form-data" id="editproduct1">
                                <input type="hidden" name="edit_id" id="edit_id" value="<?= $product['id'] ?>">

                                <div class="card card-<?php echo $settings['primary_color']; ?>">
                                    <div class="card-header">
                                        <h3 class="card-title">Edit Product</h3>
                                    </div>
                                    <!-- /.card-header -->

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputBorder">Product Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control " name="productname" id="productname" placeholder="Enter Product Name" value="<?= $product['product_name'] ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Select Category <span class="text-danger">*</span></label>
                                                    <div class="select2-olive">
                                                        <select id="categoryname"  multiple="multiple" name="categoryname[]" data-dropdown-css-class="select2-olive" class="form-control" required>
                                                            <?php foreach ($categories as $category): ?>
                                                                <option value="<?= esc($category['id']); ?>"><?= esc($category['category_name']); ?></option>
                                                            <?php endforeach; ?>

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Select SubCategory <span class="text-danger">*</span></label>
                                                    <div class="select2-olive">
                                                        <select id="subcategoryname" name="subcategoryname[]" class="form-control" multiple="multiple" data-dropdown-css-class="select2-olive" required>
                                                            <?php foreach ($subcategories as $subcategory): ?>
                                                            <option value="<?= esc($subcategory['id']); ?>"><?= esc($subcategory['name']); ?></option>

                                                        <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Product Publish Or Unpublish?</label>
                                                    <select id="ispublish" name="ispublish" class="form-control ">

                                                        <option value="0" <?php echo $product['status'] == 0 ? "selected" : "" ?>>Unpublish</option>
                                                        <option value="1" <?php echo $product['status'] == 1 ? "selected" : "" ?>>Publish</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Make Product Popular?</label>
                                                    <select id="popular" name="popular" class="form-control ">

                                                        <option value="1" <?php echo $product['popular'] == 1 ? "selected" : "" ?>>Yes</option>
                                                        <option value="0" <?php echo $product['popular'] == 0 ? "selected" : "" ?>>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Insert to Deal of the day?</label>
                                                    <select id="deal_of_the_day" name="deal_of_the_day" class="form-control ">
                                                        <option value="0" <?php echo $product['deal_of_the_day'] == 0 ? "selected" : "" ?>>No</option>
                                                        <option value="1" <?php echo $product['deal_of_the_day'] == 1 ? "selected" : "" ?>>Yes</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Select Brand <span class="text-danger">*</span></label>
                                                    <div class="select2-olive">
                                                        <select id="brandname" name="brandname" class="form-control select2 select2-olive select2-except" data-dropdown-css-class="select2-olive" ata-placeholder="Select Brand" required>
                                                            <option value="" selected="" disabled="">Select Brand</option>

                                                            <?php foreach ($brands as $brand): ?>
                                                                <option <?php echo $brand['id'] == $product['brand_id'] ? "selected" : "" ?> value="<?= esc($brand['id']); ?>" ><?= esc($brand['brand']); ?> <img src="<?= base_url($brand['image']) ?>" ></option>
                                                            <?php endforeach; ?>

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Select Seller <span class="text-danger">*</span></label>
                                                    <div class="select2-olive">
                                                        <select id="seller" name="seller" class="form-control select2 select2-olive select2-except" data-dropdown-css-class="select2-olive" ata-placeholder="Select Seller" required>
                                                            <option value="" selected="" disabled="">Select Seller</option>
                                                            <?php foreach ($sellers as $seller): ?>
                                                                <option value="<?= esc($seller['id']); ?>" <?php echo $seller['id'] == $product['seller_id'] ? 'selected' : '' ?>><?= esc($seller['store_name']); ?></option>
                                                            <?php endforeach; ?>

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="tags">Select Tags <span class="text-danger">This will help for search</span></label>
                                                    <div class="select2-olive">
                                                        <select id="tags" name="tags[]" class="form-control select2" multiple="multiple" style="width: 100%;" data-dropdown-css-class="select2-olive" data-placeholder="Select or create tags">
                                                            <?php if (!empty($tags)): ?>
                                                                <?php foreach ($tags as $tag): ?>
                                                                    <option value="<?= esc($tag['name']) ?>"><?= esc($tag['name']) ?></option>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Product Small Description <span class="text-danger">*</span> <?php if ($settings['chatgpt_status'] == 1) { ?> <button type="button" onclick="generateDescriptionUsingAI()" class="btn-ai"><i class="fi fi-bs-artificial-intelligence"></i> Generate using AI</button><?php } ?></label>
                                                    <textarea class="form-control " name="description" id="description" placeholder="Enter Product Small Description" required><?= $product['description'] ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- /.card-body -->
                                </div>
                                <div class="card card-<?php echo $settings['primary_color']; ?>">
                                    <div class="card-header">
                                        <h3 class="card-title">Add SEO Content <?php if ($settings['chatgpt_status'] == 1) { ?> <button type="button" onclick="generateSEOUsingAI()" class="btn-ai"><i class="fi fi-bs-artificial-intelligence"></i> Generate using AI</button><?php } ?></h3>
                                    </div>
                                    <!-- /.card-header -->

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputBorder">SEO Title</label>
                                                    <input type="text" class="form-control " value="<?= $product['seo_title'] ?>" name="seo_title" id="seo_title" placeholder="Enter SEO Title">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputBorder">SEO Keywords</label>
                                                    <input type="text" class="form-control " value="<?= $product['seo_keywords'] ?>" name="seo_keywords" id="seo_keywords" placeholder="Enter SEO Keywords">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputBorder">SEO Image Alt Text</label>
                                                    <input type="text" class="form-control " value="<?= $product['seo_alt_text'] ?>" name="seo_alt_text" id="seo_alt_text" placeholder="Enter SEO Image Alt Text">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="exampleInputBorder">SEO Description</label>
                                                    <input type="text" class="form-control " value="<?= $product['seo_description'] ?>" name="seo_description" id="seo_description" placeholder="Enter SEO Description">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>

                                <!-- Unified Variation Card -->

                                <div class="card card-<?php echo $settings['primary_color']; ?>">
                                    <div class="card-header">
                                        <h3 class="card-title">Add Variation</h3>
                                    </div>
                                    <!-- /.card-header -->

                                    <div class="card-body">
                                        <div id="new_variation_div">
                                            <div id="add_variation">
                                                <?php
                                                $x = 0;
                                                foreach ($variations as $variation):
                                                    $x++;
                                                ?>
                                                <div class="row bb-1" id="product_type_div_id_<?= $x ?>">
                                                    <input type="hidden" name="variation_product_id_<?= $x ?>" id="variation_product_id_<?= $x ?>" value="<?= $variation['id'] ?>">

                                                    <div class="form-group col-md-4">
                                                        <label for="variation_product_title_<?= $x ?>">Variation Title <span class="text-danger text-sm">*</span></label>
                                                        <input type="text" class="form-control " required id="variation_product_title_<?= $x ?>" name="variation_product_title_<?= $x ?>" placeholder="Variation Title" autocomplete="off" value="<?= $variation['title'] ?>">
                                                    </div>

                                                    <div class="form-group col-md-4">
                                                        <label for="variation_product_price_<?= $x ?>">Price <span class="text-danger text-sm">*</span></label>
                                                        <input type="number" required class="form-control " id="variation_product_price_<?= $x ?>" name="variation_product_price_<?= $x ?>" placeholder="Price" autocomplete="off" value="<?= $variation['price'] ?>">
                                                    </div>

                                                    <div class="form-group col-md-4">
                                                        <label for="variation_product_special_price_<?= $x ?>">Offer Price</label>
                                                        <input type="number" class="form-control " id="variation_product_special_price_<?= $x ?>" name="variation_product_special_price_<?= $x ?>" placeholder="Offer Price" autocomplete="off" value="<?php echo  $variation['discounted_price'] == '0.00' ? "" : $variation['discounted_price'] ?>">
                                                    </div>

                                                    <div class="form-group col-md-4">
                                                        <label for="variation_product_stock_<?= $x ?>">Stock (leave empty if its unlimited) <span class="text-danger text-sm">*</span></label>
                                                        <input type="text" class="form-control " id="variation_product_stock_<?= $x ?>" name="variation_product_stock_<?= $x ?>" placeholder="Stock (leave empty if its unlimited)" autocomplete="off" value="<?php echo $variation['is_unlimited_stock'] == 1 ? "" :  $variation['stock'] ?>">
                                                    </div>

                                                    <div class="form-group col-md-4">
                                                        <label for="variation_product_title_<?= $x ?>">Product Status <span class="text-danger text-sm">*</span></label>
                                                        <select class="form-control " id="variation_product_status_<?= $x ?>" name="variation_product_status_<?= $x ?>" style="width: 100%;">
                                                            <option value="1" <?php echo $variation['status'] == 1 ? "selected" : "" ?>>Available</option>
                                                            <option value="0" <?php echo $variation['status'] == 0 ? "selected" : "" ?>>Sold Out</option>
                                                        </select>
                                                    </div>

                                                    <!-- Variant Image Upload - Multiple Images -->
                                                    <div class="form-group col-md-12">
                                                        <label>Product Variant Images (Upload Multiple)</label>
                                                        <div class="dropzone custom-dropzone variation-image-dropzone" id="variant-file-dropzone_<?= $x ?>">
                                                            <div class="dropzone-clickable-area">
                                                                <div class="icon"><i class="fi fi-br-upload"></i></div>
                                                                <p>Upload Variant Images (Multiple)</p>
                                                            </div>
                                                        </div>

                                                        <!-- Display ALL existing variant images -->
                                                        <?php if (isset($variantImages[$variation['id']]) && !empty($variantImages[$variation['id']])): ?>
                                                        <div class="mt-3">
                                                            <label>Current Variant Images:</label>
                                                            <div class="d-flex flex-wrap gap-2">
                                                                <?php foreach ($variantImages[$variation['id']] as $vImg): ?>
                                                                <div class="variant-image-item position-relative d-inline-block m-1" data-variant-image-id="<?= $vImg['id'] ?>" style="position: relative;">
                                                                    <img src="<?= base_url($vImg['image']) ?>" style="width: 100px; height: 100px; object-fit: cover; border: 1px solid #ddd; border-radius: 4px;">
                                                                    <button class="btn btn-danger btn-xs position-absolute"
                                                                            style="top: 2px; right: 2px; padding: 2px 6px; font-size: 10px;"
                                                                            onclick="deleteVariantImage(<?= $product['id'] ?>, <?= $variation['id'] ?>, <?= $vImg['id'] ?>)"
                                                                            type="button"
                                                                            title="Delete this image">
                                                                        <i class='fi fi-tr-trash-xmark'></i>
                                                                    </button>
                                                                </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>

                                            <div class="row">
                                                <div class="form-group mr-1">
                                                    <button type="button" id="add_new_option_btn" name="add_new_option_btn" onclick="add_type(); return false;" class="btn btn-success btn-sm">
                                                        <i class="fa fa-plus"></i> Add Variation
                                                    </button>
                                                </div>
                                                <div class="form-group mr-1">
                                                    <button class="btn btn-danger btn-sm" onclick="delete_variation_div(); return false;">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>

                                <style>
                                /* CSS for variant image layout */
                                .variant-image-item {
                                    display: inline-block;
                                    margin: 5px;
                                }

                                .variant-image-item img {
                                    transition: transform 0.2s;
                                }

                                .variant-image-item:hover img {
                                    transform: scale(1.05);
                                }

                                .gap-2 {
                                    gap: 0.5rem;
                                }
                                </style>
                                <div class="card card-<?php echo $settings['primary_color']; ?>">
                                    <div class="card-header">
                                        <h3 class="card-title">Add Other Details</h3>
                                    </div>
                                    <!-- /.card-header -->

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputBorder">Manufacturer</label>
                                                    <input type="text" class="form-control " name="manufacturer" id="manufacturer" placeholder="Enter Manufacturer" value="<?= $product['manufacturer'] ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Made In</label>
                                                    <input type="text" id="made_in" class="form-control " placeholder="Enter Made In" name="made_in" value="<?= $product['made_in'] ?>">

                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Select Taxes <span class="text-info text-sm">Multiple allowed</span></label>
                                                    <div class="select2-olive">
                                                        <select id="tax_ids" name="tax_ids[]" class="form-control" multiple="multiple" data-dropdown-css-class="select2-olive">
                                                            <?php foreach ($taxes as $tax): ?>
                                                                <option value="<?= esc($tax['id']); ?>" <?php echo in_array($tax['id'], $selectedTaxIds) ? "selected" : "" ?>><?= esc($tax['tax']); ?> (<?= esc($tax['percentage']); ?>%)</option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Tax Included in Price?</label>
                                                    <select id="tax_included_in_price" name="tax_included_in_price" class="form-control">
                                                        <option value="0" <?php echo ($product['tax_included_in_price'] ?? 0) == 0 ? "selected" : "" ?>>No (Exclusive - Tax added on top)</option>
                                                        <option value="1" <?php echo ($product['tax_included_in_price'] ?? 0) == 1 ? "selected" : "" ?>>Yes (Inclusive - Price includes tax)</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>is Returnable? <span class="text-danger">*</span></label>
                                                    <select id="is_returnable" name="is_returnable" class="form-control ">
                                                        <option value="0" <?php echo $product['is_returnable'] == 0 ? "selected" : "" ?>>No</option>
                                                        <option value="1" <?php echo $product['is_returnable'] == 1 ? "selected" : "" ?>>Yes</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="return_days">Max Return Days</label>
                                                    <input type="text" class="form-control " name="return_days" id="return_days" placeholder="Enter Max Return Days" value="<?= $product['return_days'] ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="fssai_lic_no">FSSAI Lic. No.</label>
                                                    <input type="text" class="form-control " name="fssai_lic_no" id="fssai_lic_no" placeholder="Enter FSSAI Lic. No." value="<?= $product['fssai_lic_no'] ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="total_allowed_quantity">Total Allowed Quantity In Cart <span class="text-danger">Keep blank if no such limit</span></label>
                                                    <input type="number" class="form-control " name="total_allowed_quantity" id="total_allowed_quantity" placeholder="Enter Total Allowed Quantity" value="<?= $product['total_allowed_quantity'] ?>">
                                                </div>
                                            </div>

                                        </div>


                                    </div>


                                    <!-- /.card-body -->
                                </div>
                                <div class="card card-<?php echo $settings['primary_color']; ?>">
                                    <div class="card-header">
                                        <h3 class="card-title">Add Images</h3>
                                    </div>
                                    <!-- /.card-header -->

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputBorder">Product Main Image <span class="text-danger">*</span></label>
                                                    <div class="dropzone custom-dropzone" id="main-file-dropzone">
                                                        <div class="dropzone-clickable-area">
                                                            <div class="icon"><i class="fi fi-br-upload"></i></div>
                                                            <p>Upload Main Image </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputBorder">Old Main Image</label>
                                                    <img src="<?= base_url($product['main_img']) ?>" style="width: 80px;">
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="exampleInputBorder">Product Other Images </label>
                                                    <div class="dropzone custom-dropzone" id="images-dropzone">
                                                        <div class="dropzone-clickable-area1">
                                                            <div class="icon"><i class="fi fi-br-upload"></i></div>
                                                            <p>Upload Other Product Images Here</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputBorder">Old Other Image</label>
                                                    <?php foreach ($images as $image): ?>
                                                        <div class="d-inline image-container" data-image-id="<?= $image['id'] ?>">
                                                            <img src="<?= base_url($image['image']) ?>" style="width: 80px;">
                                                            <button class="btn btn-danger btn-xs" onclick="deleteOtherImage(<?= $product['id'] ?>, <?= $image['id'] ?>)" type="button"><i class='fi fi-tr-trash-xmark'></i></button>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" id="submitBtn" class="btn btn-primary">
                                            Edit Product
                                        </button>
                                    </div>


                                    <!-- /.card-body -->
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
    <script src="<?= base_url('/assets/page-script/product_edit.js') ?>"></script>
    <script>
        $(document).ready(function () {
             $("#tags").select2({
                tags: true, // Allow new tags
                placeholder: "Select or create tags",
                tokenSeparators: [","], // Comma will trigger a tag creation
                ajax: {
                  url: "/admin/tags/get-tags", // URL to fetch existing tags
                  dataType: "json",
                  delay: 250,
                  type: "POST",
                  data: function (params) {
                    return {
                      tags: params.term, // search term
                    };
                  },
                  processResults: function (data) {
                    return {
                      results: $.map(data, function (item) {
                        return {
                          id: item.text,
                          text: item.text,
                        };
                      }),
                    };
                  },
                },
                createTag: function (params) {
                  var term = $.trim(params.term); // Trim spaces before checking
            
                  // Prevent tag creation if the input is just spaces or empty
                  if (term === "" || term.length < 2) {
                    return null;
                  }
            
                  return {
                    id: term, // For now, assign the term as ID
                    text: term,
                    newTag: true, // Mark this as a new tag
                  };
                },
                insertTag: function (data, tag) {
                  // Prevent adding tags with only spaces
                  var found = false;
                  for (var i = 0; i < data.length; i++) {
                    if ($.trim(tag.text).toLowerCase() === data[i].text.toLowerCase()) {
                      found = true;
                      break;
                    }
                  }
                  if (!found) {
                    data.push(tag);
                  }
                },
            });
              var defaultValues = <?= json_encode(array_column($tags, 'name')) ?>;
              $('#tags').val(defaultValues).trigger('change');

              // Init multi-select for taxes
              $('#tax_ids').select2({
                  placeholder: "Select Taxes (CGST, SGST, Cess etc.)",
                  allowClear: true
              });

              // General Select2 init for everything else
              $('.select2-except').select2();
              $("#categoryname").select2({
                  placeholder: "Select Categories",
                  multiple: true,
                  allowClear: true,
                });
                $("#subcategoryname").select2({
                  placeholder: "Select Subcategories",
                  multiple: true,
                  allowClear: true,
                });

             var selectedCategories = <?= json_encode(array_column($selectedCategories, 'category_id')) ?>;
                 $('#categoryname').val(selectedCategories).trigger('change');

            var selectedSubcategories = <?= json_encode(array_column($selectedSubcategories, 'subcategory_id')) ?>;
                 $('#subcategoryname').val(selectedSubcategories).trigger('change');

            $("#categoryname").on("change", function () {
                  var catname = $("#categoryname").val();

                  if (catname != "") {
                    $.ajax({
                      url: "/admin/subcategory/getSub",
                      data: {
                        cat_change: catname,
                      },
                      type: "POST",
                      success: function (response) {
                        let subcategoryname = $("#subcategoryname");
                        subcategoryname.empty(); // Clear existing options

                        subcategoryname.append(`<option value="">Select Subcategory</option>`);
                        $.each(response.subcategory, function (index, subcategory) {
                          subcategoryname.append(
                            `<option value="${subcategory.id}">${subcategory.name}</option>`
                          );
                        });
                        $("#seller")
                          .empty()
                          .append(
                            '<option value="" selected="" disabled="">Select seller</option>'
                          );
                        $.each(response.seller, function (index, item) {
                          $("#seller").append(new Option(item.store_name, item.id));
                        });
                        $("#seller").trigger("change");
                      },
                    });
                  } else {
                    $("#subcategoryname").find("option").remove().end();
                    $("#subcategoryname").append(
                      '<option value="">Select Subcategory</option>'
                    );
                  }
                });

        });

        let count = <?= $x ?>;

        // Initialize dropzones for all existing variations on page load
        for (let i = 1; i <= count; i++) {
            initDropzoneFor('variant-file-dropzone_' + i);
        }

        function delete_variation_div() {
            if (count > 1) {
                Swal.fire({
                    title: "Are You Sure !",
                    text: "This variation will be deleted!",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Delete it!",
                    showLoaderOnConfirm: true,
                    preConfirm: function() {
                        return new Promise((resolve, reject) => {
                            $.ajax({
                                    url: "/admin/product/delete-variation",
                                    type: "POST",
                                    data: {
                                        product_id: <?= $product['id'] ?>, // product id from backend
                                        variation_id: $("#variation_product_id_" + count).val(),
                                    },
                                    dataType: "json",
                                })
                                .done(function(response) {
                                    if (response.success == true) {
                                        Swal.fire("Done!", response.message, "success");

                                    } else {
                                        Swal.fire("Oops...", response.message, "warning");
                                    }
                                })
                                .fail(function(jqXHR) {
                                    Swal.fire("Oops...", "Something went wrong!", "error");
                                });
                        });
                    },
                    allowOutsideClick: false,
                });


                let product_type_div_id = "product_type_div_id_" + count;
                document.getElementById(product_type_div_id).remove();
                --count;
            } else {
                toastr.error("At least one variation required", "Admin says");
            }

        }
    </script>

</body>

</html>