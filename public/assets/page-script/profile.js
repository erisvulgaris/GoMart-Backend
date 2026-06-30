$(document).ready(function () {
  $("#resetPasswordForm").submit(function (event) {
    event.preventDefault();

    var formData = $(this).serialize();

    $.ajax({
      type: "POST",
      url: "/admin/change_pass/update",
      data: formData,
      dataType: "json",
      success: function (response) {
        if (response.success) {
          toastr.success(response.message);
        } else {
          var errorMessages = response.message;
          var errorText = "";

          // Iterate over each error in the message object
          for (var key in errorMessages) {
            if (errorMessages.hasOwnProperty(key)) {
              errorText += errorMessages[key] + "\n"; // Append each error message
            }
          }
          toastr.error(response.message);
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error: " + status + " - " + error);
      },
    });
  });
});
