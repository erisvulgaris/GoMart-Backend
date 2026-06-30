// Prevent Dropzone from auto-uploading
Dropzone.autoDiscover = false;

// Main File Dropzone
const mainFileDropzone = new Dropzone("#main-file-dropzone", {
  url: "#",
  autoProcessQueue: false,
  maxFiles: 1,
  acceptedFiles: ".jpeg,.jpg,.png,.webp",
  addRemoveLinks: true,
  clickable: "#main-file-dropzone .dropzone-clickable-area",
});

// Images Dropzone
const imagesDropzone = new Dropzone("#images-dropzone", {
  url: "#",
  autoProcessQueue: false,
  acceptedFiles: ".jpeg,.jpg,.png, .webp",
  uploadMultiple: true,
  addRemoveLinks: true,
  clickable: "#images-dropzone .dropzone-clickable-area1",
});

// Store variant dropzones
let variantDropzones = {};

// Allow multiple images per variant
function initDropzoneFor(id) {
  if (variantDropzones[id]) {
    return;
  }

  variantDropzones[id] = new Dropzone("#" + id, {
    url: "#",
    autoProcessQueue: false,
    paramName: "file",
    maxFiles: 10,
    uploadMultiple: false,
    parallelUploads: 10,
    acceptedFiles: ".jpeg,.jpg,.png,.webp",
    addRemoveLinks: true,
    clickable: `#${id} .dropzone-clickable-area`,

    init: function() {
      this.hiddenFileInput.setAttribute('multiple', 'multiple');

      this.on("maxfilesexceeded", function(file) {
        this.removeFile(file);
        toastr.warning("Maximum 10 images allowed per variant", "Admin says");
      });
    }
  });
}

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
      '" style="width: 100%;"><option value="1">Available</option><option value="0">Sold Out</option></select></div><div class="form-group col-md-12"><label>Product Variant Images (Multiple)</label><div class="dropzone custom-dropzone variation-image-dropzone" id="' +
      dropzoneId +
      '"><div class="dropzone-clickable-area"><div class="icon"><i class="fi fi-br-upload"></i></div><p>Upload Variant Images (Multiple)</p></div></div></div></div>'
  );

  setTimeout(() => {
    initDropzoneFor(dropzoneId);
  }, 100);
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
      preConfirm: function () {
        return new Promise((resolve, reject) => {
          $.ajax({
            url: "/admin/product/delete-variation",
            type: "POST",
            data: {
              product_id: $("#edit_id").val(),
              variation_id: $("#variation_product_id_" + count).val(),
            },
            dataType: "json",
          })
            .done(function (response) {
              if (response.success == true) {
                Swal.fire("Done!", response.message, "success");
              } else {
                Swal.fire("Oops...", response.message, "warning");
              }
            })
            .fail(function (jqXHR) {
              Swal.fire("Oops...", "Something went wrong!", "error");
            });
        });
      },
      allowOutsideClick: false,
    });

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
    toastr.error("At least one variation required", "Admin says");
  }
}

$("#submitBtn").on("click", function (e) {
  e.preventDefault();
  var formData = new FormData($("#editproduct1")[0]);

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
  }

  // Additional images
  if (imagesDropzone.files.length > 0) {
    imagesDropzone.files.forEach((file) => {
      formData.append("additional_files[]", file);
    });
  }

  // Validate variation prices
  for (let index = 1; index <= count; index++) {
    var variation_product_price = $("#variation_product_price_" + index).val();
    var variation_product_special_price = $(
      "#variation_product_special_price_" + index
    ).val();

    if (
      variation_product_special_price != "" &&
      variation_product_special_price != 0 &&
      +variation_product_special_price > +variation_product_price
    ) {
      toastr.error("Offer price can not be greater than price in variation " + index, "Admin says");
      return false;
    }
  }

  // Collect variant images with their indexes - MULTIPLE IMAGES PER VARIANT
  for (let index = 1; index <= count; index++) {
    let dropzoneId = "variant-file-dropzone_" + index;
    let variantDropzone = variantDropzones[dropzoneId];

    if (variantDropzone && variantDropzone.files.length > 0) {
      // Each file from this dropzone gets the same variant index
      variantDropzone.files.forEach((file) => {
        formData.append("variant_images[]", file);
        formData.append("variant_image_indexes[]", index);
      });
    }
  }

  $.ajax({
    url: "/admin/product/update",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      if (response.success == true) {
        toastr.success(response.message, "Admin says");

        function route() {
          location = "/admin/product-list";
        }
        setTimeout(route, 2500);
      } else {
        toastr.error(response.message, "Admin says");
        return false;
      }
    },
    error: function (xhr, status, error) {
      toastr.error("An error occurred while updating the product", "Admin says");
      console.error(error);
    }
  });
});

function deleteOtherImage(productId, id) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "error",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, proceed please!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "/admin/product/delete-other-image",
        type: "POST",
        data: {
          productId,
          id,
        },
        dataType: "json",
        success: function (response) {
          if (response.success) {
            toastr.success(response.message, "Admin says");
            $('.image-container[data-image-id="' + id + '"]').remove();
          } else {
            toastr.error(response.message, "Admin says");
          }
        },
        error: function (e) {
          toastr.error("Error While deleting Product Image", "Admin says");
        },
      });
    } else {
      toastr.info("Deleting Product Image Cancelled", "Admin says");
      return false;
    }
  });
}

// Delete individual variant image
function deleteVariantImage(productId, variantId, imageId) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "error",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, proceed please!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "/admin/product/delete-variant-image",
        type: "POST",
        data: {
          productId,
          variantId,
          imageId,
        },
        dataType: "json",
        success: function (response) {
          if (response.success) {
            toastr.success(response.message, "Admin says");
            // Remove the specific image element
            $('.variant-image-item[data-variant-image-id="' + imageId + '"]').remove();
          } else {
            toastr.error(response.message, "Admin says");
          }
        },
        error: function (e) {
          toastr.error("Error While deleting Variant Image", "Admin says");
        },
      });
    } else {
      toastr.info("Deleting Variant Image Cancelled", "Admin says");
      return false;
    }
  });
}

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
