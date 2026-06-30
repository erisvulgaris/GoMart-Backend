// UPDATED: Add Product JavaScript - Replace the relevant sections in your add product JS

$(".select2").select2();
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

// Prevent Dropzone from auto-uploading
Dropzone.autoDiscover = false;

// Main File Dropzone
const mainFileDropzone = new Dropzone("#main-file-dropzone", {
  url: "#",
  autoProcessQueue: false,
  maxFiles: 1,
  acceptedFiles: ".jpeg,.jpg,.png,.webp",
  addRemoveLinks: true,
  clickable: ".dropzone-clickable-area",
});

// Images Dropzone
const imagesDropzone = new Dropzone("#images-dropzone", {
  url: "#",
  autoProcessQueue: false,
  acceptedFiles: ".jpeg,.jpg,.png, .webp",
  uploadMultiple: true,
  addRemoveLinks: true,
  clickable: ".dropzone-clickable-area1",
});

// Store variant dropzones
let variantDropzones = {};

// UPDATED: Support multiple images per variant
function initDropzoneFor(id) {
  if (variantDropzones[id]) {
    return; // Already initialized
  }
  
  variantDropzones[id] = new Dropzone("#" + id, {
    url: "#",
    autoProcessQueue: false,
    paramName: "file",
    uploadMultiple: true,
    maxFiles: 10, // Allow up to 10 images per variant
    parallelUploads: 10,
    acceptedFiles: "image/*,.jpeg,.jpg,.png,.webp",
    addRemoveLinks: true,
    clickable: `#${id} .dropzone-clickable-area`,
  });
}

$("#ptype").on("change", function () {
  var ptype = $("#ptype").val();
  count = 1; // Reset counter for variations
  variantDropzones = {}; // Clear variant dropzones

  // Function to generate image upload HTML for a given variation index
  function getImageUploadHTML(index) {
    return `
      <div class="form-group col-md-12">
        <label>Product Variant Images (Multiple) <span class="text-danger">*</span></label>
        <div class="dropzone custom-dropzone variation-image-dropzone" id="variant-file-dropzone_${index}">
            <div class="dropzone-clickable-area">
                <div class="icon"><i class="fi fi-br-upload"></i></div>
                <p>Upload Variant Images (Multiple)</p>
            </div>
        </div>
      </div>
    `;
  }
  
  if (ptype == "simple_product") {
    $("#new_variation_div").html(
      `
      <div class="row">
        <div class="form-group col-md-4">
          <label>Variation Title <span class="text-danger">*</span></label>
          <input type="text" class="form-control" required name="simple_product_title" placeholder="Variation Title">
        </div>

        <div class="form-group col-md-4">
          <label>Price <span class="text-danger">*</span></label>
          <input type="number" class="form-control" required name="simple_product_price" id="simple_product_price" placeholder="Price">
        </div>

        <div class="form-group col-md-4">
          <label>Offer Price</label>
          <input type="number" class="form-control" name="simple_product_special_price" id="simple_product_special_price" placeholder="Offer Price">
        </div>

        <div class="form-group col-md-4">
          <label>Stock <span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="simple_product_stock" placeholder="Stock">
        </div>

        <div class="form-group col-md-4">
          <label>Product Status <span class="text-danger">*</span></label>
          <select class="form-control" name="simple_product_status">
            <option value="1">Available</option>
            <option value="0">Sold Out</option>
          </select>
        </div>

        ${getImageUploadHTML('simple')}

      </div>
      `
    );
    
    // Initialize simple product dropzone
    setTimeout(() => {
      initDropzoneFor("variant-file-dropzone_simple");
    }, 100);
  }
  else if (ptype == "variation_product") {
    $("#new_variation_div").html(
      `
      <div id="add_variation">
        <div class="row bb-1" id="product_type_div_id_1">

          <div class="form-group col-md-4">
            <label>Variation Title <span class="text-danger">*</span></label>
            <input type="text" class="form-control" required name="variation_product_title_1" placeholder="Variation Title">
          </div>

          <div class="form-group col-md-4">
            <label>Price <span class="text-danger">*</span></label>
            <input type="number" class="form-control" required name="variation_product_price_1" id="variation_product_price_1" placeholder="Price">
          </div>

          <div class="form-group col-md-4">
            <label>Offer Price</label>
            <input type="number" class="form-control" name="variation_product_special_price_1" id="variation_product_special_price_1" placeholder="Offer Price">
          </div>

          <div class="form-group col-md-4">
            <label>Stock <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="variation_product_stock_1" placeholder="Stock">
          </div>

          <div class="form-group col-md-4">
            <label>Product Status <span class="text-danger">*</span></label>
            <select class="form-control" name="variation_product_status_1">
              <option value="1">Available</option>
              <option value="0">Sold Out</option>
            </select>
          </div>

          ${getImageUploadHTML(1)}

        </div>
      </div>

      <div class="row">
        <div class="form-group mr-1">
          <button type="button" onclick="add_type(); return false;" class="btn btn-success btn-sm">
            <i class="fa fa-plus"></i> Add Variation
          </button>
        </div>

        <div class="form-group mr-1">
          <button class="btn btn-danger btn-sm" onclick="delete_variation_div(); return false;">
            <i class="fa fa-trash"></i> Delete
          </button>
        </div>
      </div>
      `
    );
    
    // Initialize first variant dropzone
    setTimeout(() => {
      initDropzoneFor("variant-file-dropzone_1");
    }, 100);
  }
  else {
    $("#new_variation_div").html("");
  }
});

let count = 1;

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
        subcategoryname.empty();

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

function add_type() {
  count++;
  let dropzoneId = "variant-file-dropzone_" + count;

  $("#add_variation").append(
    '<div class="row bb-1" id="product_type_div_id_' +
      count +
      '"><div class="form-group col-md-4"><label for="variation_product_title_' +
      count +
      '">Variation Title <span class="text-danger text-sm">*</span></label><input type="text" required class="form-control " id="variation_product_title_' +
      count +
      '" name="variation_product_title_' +
      count +
      '" placeholder="Variation Title" autocomplete="off"></div><div class="form-group col-md-4"><label for="variation_product_price_' +
      count +
      '">Price <span class="text-danger text-sm">*</span></label><input type="number" required class="form-control " id="variation_product_price_' +
      count +
      '" name="variation_product_price_' +
      count +
      '" placeholder="Price" autocomplete="off"></div><div class="form-group col-md-4"><label for="variation_product_special_price_' +
      count +
      '">Offer Price</label><input type="number" class="form-control " id="variation_product_special_price_' +
      count +
      '" name="variation_product_special_price_' +
      count +
      '" placeholder="Offer Price" autocomplete="off"></div><div class="form-group col-md-4"><label for="variation_product_stock_' +
      count +
      '">Stock (leave empty if its unlimited) <span class="text-danger text-sm">*</span></label><input type="text" class="form-control " id="variation_product_stock_' +
      count +
      '" name="variation_product_stock_' +
      count +
      '" placeholder="Stock (leave empty if its unlimited)" autocomplete="off"></div><div class="form-group col-md-4"><label for="variation_product_title_' +
      count +
      '">Product Status <span class="text-danger text-sm">*</span></label><select class="form-control " id="variation_product_status_' +
      count +
      '" name="variation_product_status_' +
      count +
      '" style="width: 100%;"><option value="1">Available</option><option value="0">Sold Out</option></select></div> <div class="form-group col-md-12"> <label>Product Variant Images (Multiple) <span class="text-danger">*</span></label> <div class="dropzone custom-dropzone variation-image-dropzone" id="' +
      dropzoneId +
      '"> <div class="dropzone-clickable-area"> <div class="icon"><i class="fi fi-br-upload"></i></div> <p>Upload Variant Images (Multiple)</p> </div> </div> </div> </div>'
  );
  
  setTimeout(() => {
    initDropzoneFor(dropzoneId);
  }, 100);
}

function delete_variation_div() {
  if (count > 1) {
    let product_type_div_id = "product_type_div_id_" + count;
    let dropzoneId = "variant-file-dropzone_" + count;
    
    // Remove dropzone instance
    if (variantDropzones[dropzoneId]) {
      variantDropzones[dropzoneId].destroy();
      delete variantDropzones[dropzoneId];
    }
    
    document.getElementById(product_type_div_id).remove();
    --count;
  } else {
    toastr.error("No Variation to delete", "Admin says");
  }
}

$("#submitBtn").on("click", function (e) {
  e.preventDefault();
  var formData = new FormData($("#addproduct1")[0]);

  var productname = $("#productname").val();
  var brandname = $("#brandname").val();
  var categoryname = $("#categoryname").val();
  var subcategoryname = $("#subcategoryname").val();
  var seller = $("#seller").val();
  var description = $("#description").val();
  if (
    productname == "" ||
    brandname == "" ||
    categoryname == "" ||
    subcategoryname == "" ||
    seller == "" ||
    description == ""
  ) {
    toastr.error("All mandatory field are required", "Admin says");
    return false;
  }

  // Main product image
  if (mainFileDropzone.files.length > 0) {
    mainFileDropzone.files.forEach((file) =>
      formData.append("main_files[]", file)
    );
  } else {
    toastr.error("Select main product image", "Admin says");
    return false;
  }

  // Additional images
  if (imagesDropzone.files.length > 0) {
    imagesDropzone.files.forEach((file) => {
      formData.append("additional_files[]", file);
    });
  }

  var ptype = $("#ptype").val();
  
  if (ptype == "simple_product") {
    var simple_product_price = $("#simple_product_price").val();
    var simple_product_special_price = $("#simple_product_special_price").val();
    
    if (
      simple_product_special_price != "" &&
      simple_product_special_price != 0 &&
      +simple_product_special_price > +simple_product_price
    ) {
      toastr.error("Offer price can not be greater than price", "Admin says");
      return false;
    }

    // UPDATED: Collect simple product variant images - MULTIPLE
    let simpleDropzone = variantDropzones["variant-file-dropzone_simple"];
    if (simpleDropzone && simpleDropzone.files.length > 0) {
      simpleDropzone.files.forEach((file) => {
        formData.append("variant_images[]", file);
        formData.append("variant_image_indexes[]", "simple");
      });
    } else {
      toastr.error("Select at least one simple product variant image", "Admin says");
      return false;
    }
  } 
  else if (ptype == "variation_product") {
    // Validate variation prices
    for (let index = 1; index <= count; index++) {
      var variation_product_price = $("#variation_product_price_" + index).val();
      var variation_product_special_price = $("#variation_product_special_price_" + index).val();

      if (
        variation_product_special_price != "" &&
        variation_product_special_price != 0 &&
        +variation_product_special_price > +variation_product_price
      ) {
        toastr.error("Offer price can not be greater than price in variation " + index, "Admin says");
        return false;
      }
    }

    // UPDATED: Collect variant images with their indexes - MULTIPLE IMAGES PER VARIANT
    let hasAllImages = true;
    for (let index = 1; index <= count; index++) {
      let dropzoneId = "variant-file-dropzone_" + index;
      let variantDropzone = variantDropzones[dropzoneId];
      
      if (variantDropzone && variantDropzone.files.length > 0) {
        // Each file gets the same variant index
        variantDropzone.files.forEach((file) => {
          formData.append("variant_images[]", file);
          formData.append("variant_image_indexes[]", index);
        });
      } else {
        toastr.error("Select at least one image for variation " + index, "Admin says");
        hasAllImages = false;
        return false;
      }
    }

    if (!hasAllImages) {
      return false;
    }
  }

  $.ajax({
    url: "/admin/product/add",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      if (response.success == true) {
        toastr.success(response.message, "Admin says");
        $("#addproduct1").trigger("reset");
        mainFileDropzone.removeAllFiles(true);
        imagesDropzone.removeAllFiles(true);
        Object.values(variantDropzones).forEach(dz => dz.removeAllFiles(true));
        $(".select2").val(null).trigger("change");
        $("#tags").val(null).trigger("change");
        $("#new_variation_div").html("");
      } else {
        toastr.error(response.message, "Admin says");
        return false;
      }
    },
    error: function(xhr, status, error) {
      toastr.error("An error occurred while adding the product", "Admin says");
      console.error(error);
    }
  });
});

function generateDescriptionUsingAI() {
  var productname = $("#productname").val();
  var brandname = $("#brandname").val();
  var categoryname = $("#categoryname").val();

  if (productname == "") {
    toastr.error("Enter Product Name", "Admin says");
    return false;
  }

  if (brandname == "" || brandname == null) {
    toastr.error("Select Brand", "Admin says");
    return false;
  }
  if (categoryname == "" || categoryname == null) {
    toastr.error("Select Category", "Admin says");
    return false;
  }

  $.ajax({
    url: "/admin/product/generateDescription",
    type: "POST",
    data: {
      productname,
      brandname,
      categoryname,
    },
    dataType: "json",
    success: function (response) {
      if (response.success == true) {
        toastr.success(response.message, "Admin says");
        $("#description").val(response.response);
      } else {
        toastr.error(response.message, "Admin says");
        return false;
      }
    },
  });
}

function generateSEOUsingAI() {
  var productname = $("#productname").val();
  var brandname = $("#brandname").val();
  var categoryname = $("#categoryname").val();

  if (productname == "") {
    toastr.error("Enter Product Name", "Admin says");
    return false;
  }

  if (brandname == "" || brandname == null) {
    toastr.error("Select Brand", "Admin says");
    return false;
  }
  if (categoryname == "" || categoryname == null) {
    toastr.error("Select Category", "Admin says");
    return false;
  }

  $.ajax({
    url: "/admin/product/generateSeoContent",
    type: "POST",
    data: {
      productname,
      brandname,
      categoryname,
    },
    dataType: "json",
    success: function (response) {
      if (response.success == true) {
        toastr.success(response.message, "Admin says");
        resp = JSON.parse(JSON.stringify(response.response));
        $("#seo_title").val(resp.title);
        $("#seo_description").val(resp.description);
        $("#seo_keywords").val(resp.keywords);
        $("#seo_alt_text").val(resp.alt_text);
      } else {
        toastr.error(response.message, "Admin says");
        return false;
      }
    },
  });
}