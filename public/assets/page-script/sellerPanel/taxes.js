$(document).ready(function() {
    // Initialize the DataTable with export buttons
    var table = $("#view_taxes").DataTable({
        paging: true,
        lengthChange: false,
        ordering: true,
        info: true,
        autoWidth: true,
        responsive: true,
        ajax: {
            url: "/seller/taxes/list",
            type: "POST",
            dataType: "json",
            dataSrc: "data",
        },
        buttons: [{
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

    // Hide the original DataTable buttons
    table.buttons().container().hide();

    $(".dataTables_filter").hide();

    $("#custom-search").on("keyup", function() {
        table.search(this.value).draw(); // Trigger search when typing in custom search field
    });
    $("#custom-length-change").on("change", function() {
        table.page.len($("#custom-length-change").val()).draw();
    });

    // Link the export dropdown to the DataTable export buttons
    $(".dt-export-copy").on("click", function(e) {
        e.preventDefault();
        table.button(0).trigger(); // Trigger Copy
    });
    $(".dt-export-csv").on("click", function(e) {
        e.preventDefault();
        table.button(1).trigger(); // Trigger CSV
    });
    $(".dt-export-excel").on("click", function(e) {
        e.preventDefault();
        table.button(2).trigger(); // Trigger Excel
    });
    $(".dt-export-pdf").on("click", function(e) {
        e.preventDefault();
        table.button(3).trigger(); // Trigger PDF
    });
    $(".dt-export-print").on("click", function(e) {
        e.preventDefault();
        table.button(4).trigger(); // Trigger Print
    });
});