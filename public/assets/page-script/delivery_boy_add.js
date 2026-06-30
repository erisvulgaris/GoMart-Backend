$("#bonus_type").on("change", function () {
  var bonus_type = $(this).val();
  if (bonus_type == 1) {
    $("#commission_div").css("display", "flex");
  } else {
    $("#commission_div").css("display", "none");
  }
});
Dropzone.autoDiscover = false;

// Main File Dropzone
const national_identity_card = new Dropzone("#national_identity_card", {
  url: "#",
  autoProcessQueue: false, // Hold file upload until submit
  maxFiles: 1,
  acceptedFiles: ".jpeg,.jpg,.png,.webp, .pdf",
  addRemoveLinks: true,
  clickable: ".dropzone-national_identity_card", // Targeting the inner container
});

// Images Dropzone
const driving_license = new Dropzone("#driving_license", {
  url: "#",
  autoProcessQueue: false,
  acceptedFiles: ".jpeg,.jpg,.png, .webp, .pdf",
  uploadMultiple: true,
  addRemoveLinks: true,
  clickable: ".dropzone-driving_license", // Targeting the inner container
});
$("#submitBtn").on("click", function (e) {
  e.preventDefault();
  var formData = new FormData($("#deliveryBoyForm")[0]);

  var name = $("#name").val().trim();
  var password = $("#password").val().trim();
  var mobile = $("#mobile").val().trim();
  var city_id = $("#deliverable_area_id").val();
  var bonus_type = $("#bonus_type").val();

  // Name validation
  var nameRegex = /^[A-Za-z][A-Za-z\s]*$/;
  if (name == "") {
    toastr.error("Name is required", "Admin says");
    return false;
  }
  if (!nameRegex.test(name)) {
    toastr.error(
      "Name must start with an alphabet and contain only letters and spaces",
      "Admin says"
    );
    return false;
  }

  // Password validation
  if (password == "") {
    toastr.error("Password is required", "Admin says");
    return false;
  }
  if (password.length < 6) {
    toastr.error(
      "Password length should be at least 6 characters",
      "Admin says"
    );
    return false;
  }

  // Mobile number validation
  if (mobile == "") {
    toastr.error("Mobile number is required", "Admin says");
    return false;
  }

  // City ID validation
  if (city_id == "") {
    toastr.error("City is required", "Admin says");
    return false;
  }

  if (bonus_type == 1) {
    var bonus_percentage = $("#bonus_percentage").val();

    if (bonus_percentage == "") {
      toastr.error("Bonus percentage is required", "Admin says");
      return false;
    }
    if (isNaN(bonus_percentage) || bonus_percentage <= 0) {
      toastr.error(
        "Bonus percentage must be a valid positive number",
        "Admin says"
      );
      return false;
    }
  }
  if (national_identity_card.files.length > 0) {
    national_identity_card.files.forEach((file) =>
      formData.append("national_identity_card[]", file)
    );
  } else {
    toastr.error("Select National ID", "Admin says");
    return false;
  }

  if (driving_license.files.length > 0) {
    driving_license.files.forEach((file) =>
      formData.append("driving_license[]", file)
    );
  } else {
    toastr.error("Select Driving License", "Admin says");
    return false;
  }
  $.ajax({
    url: "/admin/delivery_boy/add",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (response) {
      if (response.success) {
        toastr.success(response.message, "Admin says");
        $("#deliveryBoyForm").trigger("reset");
        national_identity_card.removeAllFiles(true);

        driving_license.removeAllFiles(true);
      } else {
        toastr.error(response.message, "Admin says");
      }
    },
    error: function (xhr) {
      toastr.error("An error occurred. Please try again.");
    },
  });
});
