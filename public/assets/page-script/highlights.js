function addHighlights() {
  var sellerId = $("#seller_id").val();
  var title = $("#title").val().trim();
  var description = $("#description").val().trim();
  var mediaType = $("#media_type").val();
  var video = $("#video").val().trim();
  var image = $("#highlights-image").get(0).dropzone.getAcceptedFiles();

  // Validation
  if (!sellerId) {
    toastr.error("Seller is required", "Admin says");
    return false;
  }
  if (title === "") {
    toastr.error("Title is required", "Admin says");
    return false;
  }
  if (description === "") {
    toastr.error("Description is required", "Admin says");
    return false;
  }
  if (!mediaType) {
    toastr.error("Please select a media type", "Admin says");
    return false;
  }
  if (mediaType === "video" && video === "") {
    toastr.error("Please enter a YouTube video link", "Admin says");
    return false;
  }

  // Prepare form data
  var formData = new FormData();
  formData.append("seller_id", sellerId);
  formData.append("title", title);
  formData.append("description", description);
  formData.append("media_type", mediaType);

  if (mediaType === "video") {
    formData.append("video", video);
  } else if (mediaType === "image") {
    if (highlightDropzone.files.length > 0) {
      highlightDropzone.files.forEach((file) =>
        formData.append("image[]", file)
      );
    } else {
      toastr.error("Select image", "Admin says");
      return false;
    }
  }

  // AJAX Request
  $.ajax({
    url: "/admin/highlight/add",
    type: "POST",
    data: formData,
    dataType: "json",
    processData: false,
    contentType: false,
    success: function (response) {
      if (response.success) {
        toastr.success(response.message, "Admin says");
        $("#highlightForm").trigger("reset");
        $("#media_type").val("").trigger("change"); // Reset media selection
        highlightDropzone.removeAllFiles(true);
        $("#video-div").addClass("d-none");
        $("#image-div").addClass("d-none");
      } else {
        toastr.error(response.message, "Admin says");
      }
    },
    error: function () {
      toastr.error("Something went wrong. Please try again.", "Admin says");
    },
  });
}

$(document).ready(function () {
  // Initialize the DataTable with export buttons
  var table = $("#view_highlights").DataTable({
    paging: true,
    lengthChange: false,
    ordering: true,
    info: true,
    autoWidth: true,
    responsive: true,
    ajax: {
      url: "/admin/highlight/list",
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

function deletehighlights(highlight_id) {
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
      url: "/admin/highlight/delete",
      type: "POST",
      data: {
        highlight_id,
      },
      dataType: "json",
      success: function (response) {
        if (response.success == true) {
          toastr.success(response.message, "Admin says");

          $("#view_highlights").DataTable().ajax.reload();
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

Dropzone.autoDiscover = false;

// Main File Dropzone
const highlightDropzone = new Dropzone("#highlights-image", {
  url: "#",
  autoProcessQueue: false, // Hold file upload until submit
  maxFiles: 1,
  acceptedFiles: ".jpeg,.jpg,.png,.webp",
  addRemoveLinks: true,
  clickable: ".dropzone-clickable-area", // Targeting the inner container
});

$("#media_type").on("change", function () {
  if ($("#media_type").val() == "video") {
    $("#video-div").removeClass("d-none");
    $("#image-div").addClass("d-none");
  } else {
    $("#image-div").removeClass("d-none");
    $("#video-div").addClass("d-none");
  }
});
