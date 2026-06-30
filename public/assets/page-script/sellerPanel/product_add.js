$(".select2").select2();
$("#ptype").on("change", function () {
  var ptype = $("#ptype").val();

  if (ptype == "simple_product") {
    $("#new_variation_div").html(
      '<div class="row"><div class="form-group col-md-4"><label for="simple_product_title">Variation Title <span class="text-danger text-sm">*</span></label><input type="text" class="form-control " id="simple_product_title" required name="simple_product_title" placeholder="Variation Title" autocomplete="off"></div><div class="form-group col-md-4"><label for="simple_product_price">Price <span class="text-danger text-sm">*</span></label><input type="number" required class="form-control " id="simple_product_price" name="simple_product_price" placeholder="Price" autocomplete="off"></div><div class="form-group col-md-4"><label for="simple_product_special_price">Offer Price</label><input type="number" required class="form-control " id="simple_product_special_price" name="simple_product_special_price" placeholder="Offer Price" autocomplete="off"></div><div class="form-group col-md-4"><label for="simple_product_stock">Stock (leave empty if its unlimited) <span class="text-danger text-sm">*</span></label><input type="text" class="form-control " id="simple_product_stock" name="simple_product_stock" placeholder="Stock (leave empty if its unlimited)" autocomplete="off"></div><div class="form-group col-md-4"><label for="simple_product_title">Product Status <span class="text-danger text-sm">*</span></label><select class="form-control " id="simple_product_status" name="simple_product_status" style="width: 100%;"><option value="1">Available</option><option value="0">Sold Out</option></select></div></div>'
    );
  } else if (ptype == "variation_product") {
    $("#new_variation_div").html(
      '<div id="add_variation"><div class="row" id="product_type_div_id_1"><div class="form-group col-md-4"><label for="variation_product_title_1">Variation Title <span class="text-danger text-sm">*</span></label><input type="text" class="form-control " required id="variation_product_title_1" name="variation_product_title_1" placeholder="Variation Title" autocomplete="off"></div><div class="form-group col-md-4"><label for="variation_product_price_1">Price <span class="text-danger text-sm">*</span></label><input type="number" required class="form-control " id="variation_product_price_1" name="variation_product_price_1" placeholder="Price" autocomplete="off"></div><div class="form-group col-md-4"><label for="variation_product_special_price_1">Offer Price</label><input type="number" class="form-control " id="variation_product_special_price_1" name="variation_product_special_price_1" placeholder="Offer Price" autocomplete="off"></div><div class="form-group col-md-4"><label for="variation_product_stock_1">Stock (leave empty if its unlimited) <span class="text-danger text-sm">*</span></label><input type="text" class="form-control " id="variation_product_stock_1" name="variation_product_stock_1" placeholder="Stock (leave empty if its unlimited)" autocomplete="off"></div><div class="form-group col-md-4"><label for="variation_product_title_1">Product Status <span class="text-danger text-sm">*</span></label><select class="form-control " id="variation_product_status_1" name="variation_product_status_1" style="width: 100%;"><option value="1">Available</option><option value="0">Sold Out</option></select></div></div></div><div class="row"><div class="form-group mr-1"><button type="button" id="add_new_option_btn" name="add_new_option_btn" onclick="add_type(); return false;" class="btn btn-success btn-sm"> <i class="fa fa-plus  "></i> Add Variation</button> </div><div class="form-group mr-1"><button class="btn btn-danger btn-sm" onclick="delete_variation_div(); return false;"><i class="fa fa-trash  "></i> Delete</button></div></div>'
    );
  } else {
    $("#new_variation_div").html("");
  }
  count = 1;
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

function add_type() {
  count++;
  $("#add_variation").append(
    '<div class="row" id="product_type_div_id_' +
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
      '" style="width: 100%;"><option value="1">Available</option><option value="0">Sold Out</option></select></div></div>'
  );
}

function delete_variation_div() {
  if (count > 1) {
    let product_type_div_id = "product_type_div_id_" + count;
    document.getElementById(product_type_div_id).remove();
    --count;
  } else {
    toastr.error("No Variation to delete", "Admin says");
  }
}

$(document).ready(function () {
  $("#tags").select2({
    tags: true, // Allow new tags
    placeholder: "Select or create tags",
    tokenSeparators: [","], // Comma will trigger a tag creation
    ajax: {
      url: '/seller/tags/get-tags', // URL to fetch existing tags
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
});

// Prevent Dropzone from auto-uploading
Dropzone.autoDiscover = false;

// Main File Dropzone
const mainFileDropzone = new Dropzone("#main-file-dropzone", {
  url: "#",
  autoProcessQueue: false, // Hold file upload until submit
  maxFiles: 1,
  acceptedFiles: ".jpeg,.jpg,.png,.webp",
  addRemoveLinks: true,
  clickable: ".dropzone-clickable-area", // Targeting the inner container
});

// Images Dropzone
const imagesDropzone = new Dropzone("#images-dropzone", {
  url: "#",
  autoProcessQueue: false,
  acceptedFiles: ".jpeg,.jpg,.png, .webp",
  uploadMultiple: true,
  addRemoveLinks: true,
  clickable: ".dropzone-clickable-area1", // Targeting the inner container
});
$("#submitBtn").on("click", function (e) {
  e.preventDefault();
  var formData = new FormData($("#addproduct1")[0]);

  // Check if main file is selected

  var productname = $("#productname").val();
  var brandname = $("#brandname").val();
  var categoryname = $("#categoryname").val();
  var subcategoryname = $("#subcategoryname").val();
  var seller = $("#seller").val();
  var description = $("#description").val();
  var tax_id = $("#tax_id").val();

  if (
    productname == "" ||
    brandname == "" ||
    categoryname == "" ||
    subcategoryname == "" ||
    seller == "" ||
    description == "" ||
    tax_id == ""
  ) {
    toastr.error("All mandatory field are required", "Admin says");
    return false;
  }
  if (mainFileDropzone.files.length > 0) {
    mainFileDropzone.files.forEach((file) =>
      formData.append("main_files[]", file)
    );
  } else {
    toastr.error("Select main product image", "Admin says"); 
    return false;
  }

  // Check if additional files are selected
  if (imagesDropzone.files.length > 0) {
    imagesDropzone.files.forEach((file) => {
      formData.append("additional_files[]", file);
    });
  } else {
    console.log("No additional files selected");
  }

  var ptype = $("#ptype").val();
  if (ptype == "simple_product") {
    var simple_product_price = $("#simple_product_price").val();
    var simple_product_special_price = $("#simple_product_special_price").val();
    if (
      simple_product_special_price == "" &&
      simple_product_special_price == 0
    ) {
    } else if (+simple_product_special_price > +simple_product_price) {
      toastr.error("Offer price can not be greater than price", "Admin says");
      return false;
    }
  } else {
    for (let index = 1; index <= count; index++) {
      var variation_product_price = $(
        "#variation_product_price_" + index
      ).val();
      var variation_product_special_price = $(
        "#variation_product_special_price_" + index
      ).val();

      if (
        variation_product_special_price == "" &&
        variation_product_special_price == 0
      ) {
      } else if (+variation_product_special_price > +variation_product_price) {
        toastr.error("Offer price can not be greater than price", "Admin says");
        return false;
      }
    }
  }
  
  $.ajax({
    url: "/seller/product/add",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      if (response.success == true) {
        toastr.success(response.message, "Admin says");
        $("#addproduct1").trigger("reset");
        $(".select2").val(null).trigger("change");
        $("#tags").val(null).trigger("change");
        mainFileDropzone.removeAllFiles(true);
        imagesDropzone.removeAllFiles(true);
        $("#new_variation_div").html("");
      } else {
        toastr.error(response.message, "Admin says");
        return false;
      }
    },
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
    url: "/seller/product/generateDescription",
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
    url: "/seller/product/generateSeoContent",
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