

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


$(document).ready(function() {
    $('#tags').select2({
        tags: true, // Allow new tags
        placeholder: 'Select or create tags',
        tokenSeparators: [','], // Comma will trigger a tag creation
        ajax: {
            url: '/seller/tags/get-tags', // URL to fetch existing tags
            dataType: 'json',
            delay: 250,
            type: "POST",
            data: function(params) {
                return {
                    tags: params.term // search term
                };
            },
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            id: item.text,
                            text: item.text
                        };
                    })
                };
            }
        },
        createTag: function(params) {
            var term = $.trim(params.term); // Trim spaces before checking

            // Prevent tag creation if the input is just spaces or empty
            if (term === '' || term.length < 2) {
                return null;
            }

            return {
                id: term, // For now, assign the term as ID
                text: term,
                newTag: true // Mark this as a new tag
            };
        },
        insertTag: function(data, tag) {
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
        }
    });
});

// Prevent Dropzone from auto-uploading
Dropzone.autoDiscover = false;

// Main File Dropzone
const mainFileDropzone = new Dropzone("#main-file-dropzone", {
    url: '#',
    autoProcessQueue: false, // Hold file upload until submit
    maxFiles: 1,
    acceptedFiles: ".jpeg,.jpg,.png,.webp",
    addRemoveLinks: true,
    clickable: ".dropzone-clickable-area" // Targeting the inner container
});

// Images Dropzone
const imagesDropzone = new Dropzone("#images-dropzone", {
    url: '#',
    autoProcessQueue: false,
    acceptedFiles: ".jpeg,.jpg,.png, .webp",
    uploadMultiple: true,
    addRemoveLinks: true,
    clickable: ".dropzone-clickable-area1" // Targeting the inner container
});
$("#submitBtn").on("click", function(e) {
    e.preventDefault();
    var formData = new FormData($("#editproduct1")[0]);

    // Check if main file is selected


    var productname = $("#productname").val();
    var brandname = $("#brandname").val();
    var categoryname = $("#categoryname").val();
    var subcategoryname = $("#subcategoryname").val();
    var seller = $("#seller").val();
    var description = $("#description").val();
    var tax = $("#tax_id").val();

    if (
        productname == "" ||
        brandname == "" ||
        categoryname == "" ||
        subcategoryname == "" ||
        seller == "" ||
        description == "" ||
        tax == ""
    ) {
        toastr.error("All mandatory field are required", "Admin says");
        return false;
    }
    if (mainFileDropzone.files.length > 0) {
        mainFileDropzone.files.forEach(file => formData.append("main_files[]", file));
    }

    // Check if additional files are selected
    if (imagesDropzone.files.length > 0) {
        imagesDropzone.files.forEach(file => {
            formData.append("additional_files[]", file);
        });
    }


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
    

    $.ajax({
        url: "/seller/product/update",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function(response) {
            if (response.success == true) {
                toastr.success(response.message, "Admin says");

                function route() {
                    location = "/seller/product-list";
                }
                setTimeout(route, 2500);
            } else {
                toastr.error(response.message, "Admin says");
                return false;
            }
        },
    });
})

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
                url: "/seller/product/delete-other-image",
                type: "POST",
                data: {
                    productId,
                    id,
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message, "Admin says");
                        $('.image-container[data-image-id="' + id + '"]').remove();
                    } else {
                        toastr.error(response.message, "Admin says");
                    }
                },

                error: function(e) {
                    toastr.error("Error While deleting Product image", "Admin says");
                },
            });
        } else {
            toastr.info("Deleting Product Image Cancelled", "Admin says");
            return false;
        }
    })
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
        url: "/seller/product/generateDescription",
        type: "POST",
        data: {
            productname,
            brandname,
            categoryname
        },
        dataType: "json",
        success: function(response) {
            if (response.success == true) {
                toastr.success(response.message, "Admin says");
                $("#description").val(response.response)
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
            categoryname
        },
        dataType: "json",
        success: function(response) {
            if (response.success == true) {
                toastr.success(response.message, "Admin says");
                resp = JSON.parse(JSON.stringify(response.response))
                $("#seo_title").val(resp.title)
                $("#seo_description").val(resp.description)
                $("#seo_keywords").val(resp.keywords)
                $("#seo_alt_text").val(resp.alt_text)
            } else {
                toastr.error(response.message, "Admin says");
                return false;
            }
        },
    });

}