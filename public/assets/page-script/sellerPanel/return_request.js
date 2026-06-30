        $(document).ready(function() {
            $("#report_date").daterangepicker({
                startDate: moment().startOf('day'),
                endDate: moment().endOf('day'),
                maxDate: moment().endOf('day'), // Set today as the last selectable date
                locale: {
                    format: "MM/DD/YYYY",
                },
            });
            var table = $("#view_return_request_list").DataTable({
                dom: "Bfrtip",
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: true,
                responsive: true,
                ajax: {
                    url: "/seller/return-request/list",
                    type: "POST",
                    dataType: "json",
                    dataSrc: "data",
                    data: function(d) {
                        d.report_date = $("#report_date").val() || moment().format('MM/DD/YYYY') + ' - ' + moment().format('MM/DD/YYYY');
                        d.status = $("#status").val();
                    }
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
            $(".filter-product").on('change', function() {
                table.ajax.reload();
            })
            $("#updateRequestForm").on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData($("#updateRequestForm")[0]);;

                $.ajax({
                    url: '/seller/return-request/update', // Adjust the URL as needed
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message, "Admin says");
                            $("#updateRequestForm").trigger('reset')
                            table.ajax.reload();
                            $("#modal-default").modal('hide')
                        } else {
                            toastr.error(response.message, "Admin says");
                        }
                    },
                    error: function(xhr) {
                        // Handle errors
                        toastr.error('An error occurred. Please try again.');
                    }
                });
            })

        });

        function updateRequest(id) {
            $("#modal-default").modal('show')
            let delivery_boy_id = $("#delivery_boy_id");
            delivery_boy_id.empty(); // Clear existing options
            $.ajax({
                url: "/seller/return-request/view",
                type: "POST",
                data: {
                    return_request: id
                },
                dataType: "json",
                beforeSend: function() {
                    $(`#updateRequestForm`).trigger('reset');
                },
                success: function(response) {
                    if (response.success == true) {
                        delivery_boy_id.append(
                            `<option value="">Select Delivery Boy</option>`
                        );
                        $.each(response.delivery_boy, function(index, delivery_boy) {
                            delivery_boy_id.append(
                                `<option value="${delivery_boy.id}">${delivery_boy.name} (${delivery_boy.mobile})</option>`
                            );
                        });

                        $(`#status option`).prop('selected', false);
                        $(`#status option[value="${response.response.status}"]`).prop('selected', true);

                        $(`#request_id`).val(response.response.id);
                        $(`#reason`).val(response.response.reason);
                        $(`#remark`).val(response.response.remark);
                    } else {
                        toastr.error(response.message, "Admin says");
                        $("#modal-default").modal('hide')
                        $(`#updateRequestForm`).trigger('reset');

                    }
                },
            });
        }