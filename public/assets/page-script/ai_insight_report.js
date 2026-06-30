$(document).ready(function () {
  var table = $("#top_selling_product_table").DataTable({
    paging: true,
    lengthChange: false,
    ordering: true,
    info: true,
    autoWidth: true,
    responsive: true,
    pageLength: 6,
    buttons: [
      {
        extend: "copy",
        title: "Export Data",
      },
      {
        extend: "csv",
        title: "Export Data",
      },
      {
        extend: "excel",
        title: "Export Data",
      },
      {
        extend: "pdf",
        title: "Export Data",
      },
      {
        extend: "print",
        title: "Export Data",
      },
    ],
  });
  $.ajax({
    url: "/admin/order-report/generateOrderInsights",
    type: "post",
    dataType: "json",
    success: function (data) {
      $("#totalOrders").text(data.order_summary.total_orders);
      $("#totalRevenue").text(
        data.order_summary.country.currency_symbol +
          data.order_summary.total_revenue
      );
      $("#totalDiscounts").text(
        data.order_summary.country.currency_symbol +
          data.order_summary.total_discounts
      );
      $("#totalRefunds").text(data.order_summary.total_refunds);
      $(".report-date").text(data.order_summary.report_date);
      table.clear().destroy();

      let topProductsHtml = "";
      data.order_summary.top_products.forEach((product) => {
        topProductsHtml += `<tr><td>${product.product_name}</td><td>${product.total_sold}</td></tr>`;
      });
      $("#topProducts").html(topProductsHtml);

      // Reinitialize DataTable after data is inserted
      table = $("#top_selling_product_table").DataTable({
        paging: true,
        lengthChange: false,
        ordering: true,
        info: true,
        autoWidth: true,
        responsive: true,
        pageLength: 6,
        buttons: [
          {
            extend: "copy",
            title: "Export Data",
          },
          {
            extend: "csv",
            title: "Export Data",
          },
          {
            extend: "excel",
            title: "Export Data",
          },
          {
            extend: "pdf",
            title: "Export Data",
          },
          {
            extend: "print",
            title: "Export Data",
          },
        ],
      });

      $("#aiInsights").html(data.ai_insights);
    },
  });

  // Hide the original DataTable buttons
  table.buttons().container().hide();
  table.page.len("6").draw();

  $(".dataTables_filter").hide();

  $("#custom-search").on("keyup", function () {
    table.search(this.value).draw(); // Trigger search when typing in custom search field
  });
  $("#custom-length-change").on("change", function () {
    table.page.len($("#custom-length-change").val()).draw();
  });

  // Link the export dropdown to the DataTable export buttons
  $(".dt-export-copy").on("click", function (e) {
    e.preventDefault();
    table.button(0).trigger(); // Trigger Copy
  });
  $(".dt-export-csv").on("click", function (e) {
    e.preventDefault();
    table.button(1).trigger(); // Trigger CSV
  });
  $(".dt-export-excel").on("click", function (e) {
    e.preventDefault();
    table.button(2).trigger(); // Trigger Excel
  });
  $(".dt-export-pdf").on("click", function (e) {
    e.preventDefault();
    table.button(3).trigger(); // Trigger PDF
  });
  $(".dt-export-print").on("click", function (e) {
    e.preventDefault();
    table.button(4).trigger(); // Trigger Print
  });

  $("#refresh-report").on("submit", function (e) {
    e.preventDefault();
    const formData = $(this).serialize(); // Gather form data
    $.ajax({
      url: "/admin/order-report/refreshOrderInsights",
      method: "POST",
      dataType: "JSON",
      data: formData,
      beforeSend: function () {
        $("#refresh-report-btn").text("Loading...");
        $("#refresh-report-btn").attr("disabled", true);
      },
      success: function (data) {
        $("#totalOrders").text(data.order_summary.total_orders);
        $("#totalRevenue").text(
          data.order_summary.country.currency_symbol +
            data.order_summary.total_revenue
        );
        $("#totalDiscounts").text(
          data.order_summary.country.currency_symbol +
            data.order_summary.total_discounts
        );
        $("#totalRefunds").text(data.order_summary.total_refunds);
        $(".report-date").text(data.order_summary.report_date);
        table.clear().destroy();

        let topProductsHtml = "";
        data.order_summary.top_products.forEach((product) => {
          topProductsHtml += `<tr><td>${product.product_name}</td><td>${product.total_sold}</td></tr>`;
        });
        $("#topProducts").html(topProductsHtml);

        // Reinitialize DataTable after data is inserted
        table = $("#top_selling_product_table").DataTable({
          paging: true,
          lengthChange: false,
          ordering: true,
          info: true,
          autoWidth: true,
          responsive: true,
          pageLength: 6,

          buttons: [
            {
              extend: "copy",
              title: "Export Data",
            },
            {
              extend: "csv",
              title: "Export Data",
            },
            {
              extend: "excel",
              title: "Export Data",
            },
            {
              extend: "pdf",
              title: "Export Data",
            },
            {
              extend: "print",
              title: "Export Data",
            },
          ],
        });

        $("#aiInsights").html(data.ai_insights);
        $("#refresh-report-btn").removeAttr("disabled");
        $("#refresh-report-btn").text("Refresh Report");
      },
    });
  });
});
