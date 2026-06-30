var initialOrder = "";
var productList = document.getElementById("product-list");
$("#cat_id").on("change", function () {
  var catname = $("#cat_id").val();

  if (catname != "") {
    $.ajax({
      url: "/admin/product/get_product_by_category",
      data: {
        cat_change: catname,
      },
      type: "POST",
      success: function (response) {
        let product_list = $("#product-list");
        $("#product-list").html("");

        $.each(response, function (index, product) {
          product_list.append(
            `<li data-id="${product.id}"><span>${product.product_name}</span><span class="drag-handle">&#x2630;</span></li>`
          );
        });
        initialOrder = Array.from(productList.children).map(function (item) {
          return item.cloneNode(true); // Clone the nodes to preserve original structure
        });
      },
    });
  } else {
    $("#product-list").html("");
  }
});

document.addEventListener("DOMContentLoaded", function () {
  // Initialize SortableJS
  var sortable = new Sortable(productList, {
    animation: 150,
  });
  // Save the initial order of product elements (not just IDs)

  document.getElementById("save-order").addEventListener("click", function () {
    // Get the new order of product IDs
    var orderedIds = [];
    document.querySelectorAll("#product-list li").forEach(function (item) {
      orderedIds.push(item.getAttribute("data-id"));
    });

    $.ajax({
      url: "/admin/product_order/update",
      type: "POST",
      data: JSON.stringify({
        order: orderedIds,
      }),
      dataType: "json",
      success: function (response) {
        if (response.success == true) {
          toastr.success(response.message, "Admin says");
        } else {
          toastr.error(response.message, "Admin says");
        }
      },
    });
  });
  document.getElementById("reset-order").addEventListener("click", function () {
    // Clear the current list
    productList.innerHTML = "";

    // Rebuild the list using the cloned initial nodes
    initialOrder.forEach(function (item) {
      productList.appendChild(item.cloneNode(true));
    });
  });
});
