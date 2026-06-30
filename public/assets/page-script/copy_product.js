$("#seller_id").on("change", function () {
  var seller_id = $("#seller_id").val();

  if (seller_id !== "") {
    $.ajax({
      url: "/admin/product/get_category_by_seller",
      data: { seller_id },
      type: "POST",
      success: function (response) {
        $("#category_id").empty();

        $("#category_id").append(`<option value="">Select Category</option>`);

        response.forEach(function (item) {
          $("#category_id").append(
            `<option value="${item.category_id}">${item.category_name}</option>`
          );
        });
      },
    });
  } else {
    $("#product-list").html("");
    $("#category_id").empty().append(`<option value="">Select Category</option>`);
  }
});

$("#category_id").on("change", function () {
  var category_id = $("#category_id").val();

  if (category_id !== "") {
    $.ajax({
      url: "/admin/product/get_subcategory_by_category",
      data: { category_id },
      type: "POST",
      success: function (response) {
        $("#subcategory_id").empty();

        $("#subcategory_id").append(`<option value="">Select Subcategory</option>`);

        response.forEach(function (item) {
          $("#subcategory_id").append(
            `<option value="${item.id}">${item.name}</option>`
          );
        });
      },
    });
  } else {
    $("#product-list").html("");
    $("#subcategory_id").empty().append(`<option value="">Select Subcategory</option>`);
  }
});

$("#subcategory_id").on("change", function () {
  var subcategory_id = $("#subcategory_id").val();
  var category_id = $("#category_id").val();
  var seller_id = $("#seller_id").val();
  var to_seller_id = $("#to_seller_id").val();

  if (subcategory_id !== "") {
    $.ajax({
      url: "/admin/product/get_product_by_seller_category_subcategory",
      type: "POST",
      data: {
        subcategory_id,
        category_id,
        seller_id,
        to_seller_id
      },
      dataType: "json",
      success: function (response) {
        $("#product-list").empty();

        if (response.length === 0) {
          $("#product-list").html("<p>No products found.</p>");
          return;
        }

        let html = `<table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>Select</th>
                          <th>Product Name</th>
                          <th>Main Image</th>
                        </tr>
                      </thead>
                      <tbody>`;

  response.item.forEach(function (product) {
    const checked = product.exists ? "checked disabled title='Already copied'" : "";
    const statusLabel = product.exists
      ? '<span class="badge bg-success">Already Exists</span>'
      : '<span class="badge bg-warning text-dark">New</span>';

    html += `<tr>
              <td>
                <input type="checkbox" class="product-checkbox" value="${product.id}" ${checked}>
              </td>
              <td><a target="_blank" href="${response.base_url}admin/product/view/${product.id}">${product.product_name}</a></td>
              <td><img src="${response.base_url}${product.main_img}" style="width:50px"></td>
              <td>${statusLabel}</td>
            </tr>`;
  });

  html += `</tbody></table>`;
  $("#product-list").html(html);

      }
    });
  } else {
    $("#product-list").html("");
  }
});

// Copy selected products
$("#copy-selected").on("click", function () {
  var selectedProducts = [];
  $(".product-checkbox:checked:not(:disabled)").each(function () {
    selectedProducts.push($(this).val());
  });

  var to_seller_id = $("#to_seller_id").val();

  if (selectedProducts.length === 0) {
    alert("Please select at least one product to copy.");
    return;
  }

  $.ajax({
    url: "/admin/product/copy_selected_products",
    type: "POST",
    data: {
      product_ids: selectedProducts,
      to_seller_id: to_seller_id
    },
    dataType: "json",
    success: function (response) {
      if (response.success) {
        alert("Products copied successfully.");
      } else {
        alert("Error copying products.");
      }
    }
  });
});












