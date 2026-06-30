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

  if (national_identity_card.files.length > 0) {
    national_identity_card.files.forEach((file) =>
      formData.append("national_identity_card[]", file)
    );
  }

  if (driving_license.files.length > 0) {
    driving_license.files.forEach((file) =>
      formData.append("driving_license[]", file)
    );
  }
  var name = $("#name").val().trim();
  var mobile = $("#mobile").val().trim();
  var city_id = $("#deliverable_area_id").val();
  var bonus_type = $("#bonus_type").val();
  var bonus_percentage = $("#bonus_percentage").val();

  // Name validation
  var nameRegex = /^[A-Za-z][A-Za-z\s]*$/;
  if (name === "") {
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

  // Mobile number validation
  if (mobile === "") {
    toastr.error("Mobile number is required", "Admin says");
    return false;
  }

  // City ID validation
  if (city_id === "") {
    toastr.error("City is required", "Admin says");
    return false;
  }

  if (bonus_type == 1) {
    if (bonus_percentage === "") {
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
  $.ajax({
    url: "/admin/delivery_boy/update",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (response) {
      if (response.success) {
        toastr.success(response.message, "Admin says");
      } else {
        toastr.error(response.message, "Admin says");
      }
    },
    error: function (xhr) {
      // Handle errors
      toastr.error("An error occurred. Please try again.");
    },
  });
});

function onMobileChange() {
  var mob = /^[6-9]{1}[0-9]{9}$/;

  if (mob.test($("#mobile").val()) == false) {
    toastr.error("Enter Valid Mobile Number", "Admin says");
    return false;
  }
  return true;
}
