function addFaq() {
  var question = $("#question").val().trim();
  var answer = $("#answer").val().trim();

  if (question === "") {
    toastr.error("Question is required", "Admin says");
    return false;
  }
  if (answer === "") {
    toastr.error("Answer is required", "Admin says");
    return false;
  }
  $.ajax({
    url: "/admin/faq/add",
    type: "POST",
    data: {
      question: question,
      answer: answer,
    },
    dataType: "json",
    success: function (response) {
      if (response.success == true) {
        toastr.success(response.message, "Admin says");
        $("#view_faq").DataTable().ajax.reload();
        $("#faqForm").trigger("reset");
      } else {
        toastr.error(response.message, "Admin says");
        return false;
      }
    },
  });
}

function updateFaq() {
  var question = $("#question").val().trim();
  var answer = $("#answer").val().trim();
  var edit_id = $("#edit_id").val();

  if (question === "") {
    toastr.error("Question is required", "Admin says");
    return false;
  }
  if (answer === "") {
    toastr.error("Answer is required", "Admin says");
    return false;
  }

  $.ajax({
    url: "/admin/faq/update",
    type: "POST",
    data: {
      question,
      answer,
      edit_id,
    },
    dataType: "json",
    success: function (response) {
      if (response.success == true) {
        toastr.success(response.message, "Admin says");

        function route() {
          location = "/admin/faq";
        }
        setTimeout(route, 2500);
      } else {
        toastr.error(response.message, "Admin says");
        return false;
      }
    },
  });
}

$(document).ready(function () {
  // Initialize the DataTable with export buttons
  var table = $("#view_faq").DataTable({
    paging: true,
    lengthChange: false,
    ordering: true,
    info: true,
    autoWidth: true,
    responsive: true,
    ajax: {
      url: "/admin/faq/list",
      type: "POST",
      dataType: "json",
      dataSrc: "data",
    },
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

  // Hide the original DataTable buttons
  table.buttons().container().hide();

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
});

function deletefaq(faq_id) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "error",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, proceed please!",
  }).then((result) => {
    $.ajax({
      url: "/admin/faq/delete",
      type: "POST",
      data: {
        faq_id,
      },
      dataType: "json",
      success: function (response) {
        if (response.success == true) {
          toastr.success(response.message, "Admin says");

          $("#view_faq").DataTable().ajax.reload();
        } else {
          toastr.error(response.message, "Admin says");
        }
      },

      error: function (e) {
        toastr.error("Error While deleting faq", "Admin says");
      },
    });
  });
}
