$('#view_seller').dataTable({
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": true,
    "responsive": true,
    ajax: {
        url: "/admin/seller/list/view/top",
        type: "POST",
        dataType: "json",
        dataSrc: "data",
    },
});
$('#view_order').dataTable({
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": true,
    "responsive": true,
    ajax: {
        url: "/admin/orders/list/20",
        type: "POST",
        dataType: "json",
        dataSrc: "data",
    },
});