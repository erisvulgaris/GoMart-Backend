$("input[data-bootstrap-switch]").each(function () {
  $(this).bootstrapSwitch("state");
});
Dropzone.autoDiscover = false;
const imagesDropzone = new Dropzone("#images-dropzone", {
  url: "#",
  autoProcessQueue: false,
  acceptedFiles: ".jpeg,.jpg,.png, .webp, .gif",
  uploadMultiple: true,
  addRemoveLinks: true,
  clickable: ".dropzone-clickable-area", // Targeting the inner container
});

function changeURL(url) {
  history.pushState(null, null, url);
}

$("#mailSettingForm").on("submit", function (e) {
  e.preventDefault();
  const formData = $(this).serializeArray();
  const jsonData = {};

  // Map form fields to the JSON structure
  formData.forEach((field) => {
    jsonData[field.name] = field.value;
  });
  $.ajax({
    url: "/admin/setting/updateSetting",
    method: "POST",
    dataType: "JSON",
    data: {
      mail_config: JSON.stringify(jsonData),
    },
    success: function (response) {
      if (response.success) {
        toastr.success(response.message, "Admin says");
      } else {
        toastr.error(response.message, "Admin says");
      }
    },
    error: function (err) {
      console.error("Error submitting form:", err);
    },
  });
});
$("#googleMapSettingForm").on("submit", function (e) {
  e.preventDefault();
  $("input[data-bootstrap-switch]").each(function () {
    if (!$(this).prop("checked")) {
      $(this).attr("value", "0").prop("checked", true);
    } else {
      $(this).attr("value", "1").prop("checked", true);
    }
  });

  const formData = $(this).serialize(); // Gather form data
  $.ajax({
    url: "/admin/setting/updateSetting",
    method: "POST",
    dataType: "JSON",
    data: formData,
    success: function (response) {
      if (response.success) {
        toastr.success(response.message, "Admin says");
      } else {
        toastr.error(response.message, "Admin says");
      }
    },
    error: function (err) {
      console.error("Error submitting form:", err);
    },
  });
});
$("#loginSettingForm").on("submit", function (e) {
  e.preventDefault();
  $("input[data-bootstrap-switch]").each(function () {
    if (!$(this).prop("checked")) {
      $(this).attr("value", "0").prop("checked", true);
    } else {
      $(this).attr("value", "1").prop("checked", true);
    }
  });

  var googleLoginData = {
    status: $("#google_login_status").val(),
    client_id: $("#google_login_client_id").val(),
    client_secret: $("#google_login_client_secret").val(),
    login_medium: $("#google_login_medium").val(),
  };
  var appleLoginData = {
    status: $("#apple_login_status").val(),
    login_medium: "apple",
  };
  var directLogin = $("#direct_login").val();
  var phone_login = $("#phone_login").val();
  var socialLoginArray = [googleLoginData, appleLoginData];
  var postData = {
    direct_login: directLogin,
    phone_login: phone_login,
    social_login: JSON.stringify(socialLoginArray),
  };
  $.ajax({
    url: "/admin/setting/updateSetting",
    method: "POST",
    dataType: "JSON",
    data: postData,
    success: function (response) {
      if (response.success) {
        toastr.success(response.message, "Admin says");
      } else {
        toastr.error(response.message, "Admin says");
      }
    },
    error: function (err) {
      console.error("Error submitting form:", err);
    },
  });
});
$("#notificationSettingForm").on("submit", function (e) {
  e.preventDefault();
  // Remove all hidden inputs for status (to avoid duplicates)

  $("input[data-bootstrap-switch]").each(function () {
    if (!$(this).prop("checked")) {
      $(this).attr("value", "0").prop("checked", true);
    } else {
      $(this).attr("value", "1").prop("checked", true);
    }
  });

  const formData = $(this).serialize(); // Gather form data
  $.ajax({
    url: "/admin/setting/updateSetting",
    method: "POST",
    dataType: "JSON",
    data: formData,
    success: function (response) {
      if (response.success) {
        toastr.success(response.message, "Admin says");
      } else {
        toastr.error(response.message, "Admin says");
      }
    },
    error: function (err) {
      console.error("Error submitting form:", err);
    },
  });
});
$("#socialLinkForm").on("submit", function (e) {
  e.preventDefault();

  $("input[data-bootstrap-switch]").each(function () {
    if (!$(this).prop("checked")) {
      $(this).attr("value", "0").prop("checked", true);
    } else {
      $(this).attr("value", "1").prop("checked", true);
    }
  });

  // Create FormData object
  const formData = new FormData();
  const formFields = $(this).serializeArray(); // Gather all form data
  const jsonData = []; // Use an array to hold the final structure

  // Build JSON structure
  const tempData = {};
  formFields.forEach((field) => {
    const [name, key] = field.name.split("_"); // Extract name and key (e.g., facebook_status)
    if (!tempData[name]) {
      tempData[name] = {
        name: name.charAt(0).toUpperCase() + name.slice(1),
      }; // Initialize with a name field
    }
    tempData[name][key] = field.value; // Assign the key-value pair
  });

  // Convert tempData object into an array of objects
  for (const key in tempData) {
    jsonData.push(tempData[key]);
  }

  formData.append("social_link", JSON.stringify(jsonData));

  $.ajax({
    url: "/admin/setting/updateSetting",
    method: "POST",
    dataType: "JSON",
    data: formData,
    processData: false, // Prevent jQuery from automatically transforming the data
    contentType: false, // Set contentType to false for FormData
    success: function (response) {
      if (response.success) {
        toastr.success(response.message, "Admin says");
      } else {
        toastr.error(response.message, "Admin says");
      }
    },
    error: function (err) {
      console.error("Error submitting form:", err);
    },
  });
});
$("#firebaseSettingForm").on("submit", function (e) {
  e.preventDefault();

  const formData = $(this).serializeArray();
  const jsonData = {};

  // Map form fields to the JSON structure
  formData.forEach((field) => {
    if (field.name != "firebase_admin_json_file_content") {
      jsonData[field.name] = field.value;
    }
  });
  var firebase_admin_json_file_content = $(
    "#firebase_admin_json_file_content"
  ).val();
  $.ajax({
    url: "/admin/setting/updateSetting",
    method: "POST",
    dataType: "JSON",
    data: {
      fcm_credentials: JSON.stringify(jsonData),
      firebase_admin_json_file_content: firebase_admin_json_file_content,
    },
    success: function (response) {
      if (response.success) {
        toastr.success(response.message, "Admin says");
      } else {
        toastr.error(response.message, "Admin says");
      }
    },
    error: function (err) {
      console.error("Error submitting form:", err);
    },
  });
});
$("#chatgptSettingForm").on("submit", function (e) {
  e.preventDefault();
  $("input[data-bootstrap-switch]").each(function () {
    if (!$(this).prop("checked")) {
      $(this).attr("value", "0").prop("checked", true);
    } else {
      $(this).attr("value", "1").prop("checked", true);
    }
  });
  const chatgpt_status = $("#chatgpt_status").val();
  const chatgpt_api_key = $("#chatgpt_api_key").val();
  const twak_live_chat_status = $("#twak_live_chat_status").val();
  const twak_live_chat_widget_code = $("#twak_live_chat_widget_code").val();
  // Prepare data for submission
  const formData = {
    chatgpt_status: chatgpt_status,
    chatgpt_api_key: chatgpt_api_key,
    twak_live_chat_status: twak_live_chat_status,
    twak_live_chat_widget_code: twak_live_chat_widget_code,
  };
  $.ajax({
    url: "/admin/setting/updateSetting",
    method: "POST",
    dataType: "JSON",
    data: formData,
    success: function (response) {
      if (response.success) {
        toastr.success(response.message, "Admin says");
      } else {
        toastr.error(response.message, "Admin says");
      }
    },
    error: function (err) {
      console.error("Error submitting form:", err);
    },
  });
});
$("#otherSettingForm").on("submit", function (e) {
  e.preventDefault();
  // Remove all hidden inputs for status (to avoid duplicates)

  $("input[data-bootstrap-switch]").each(function () {
    if (!$(this).prop("checked")) {
      $(this).attr("value", "0").prop("checked", true);
    } else {
      $(this).attr("value", "1").prop("checked", true);
    }
  });

  // $("input[name$='_hidden']").remove();
  // $("input[data-custom-switch]").each(function() {
  //     const name = $(this).attr("name");
  //     const isChecked = $(this).prop("checked");
  //     if (!isChecked) {
  //         $(this).after(`<input type="hidden" name="${name}" value="0">`);
  //     }
  //     $(this).val("1");
  // });
  const formData = $(this).serialize(); // Gather form data
  $.ajax({
    url: "/admin/setting/updateSetting",
    method: "POST",
    dataType: "JSON",
    data: formData,
    success: function (response) {
      if (response.success) {
        toastr.success(response.message, "Admin says");
      } else {
        toastr.error(response.message, "Admin says");
      }
    },
    error: function (err) {
      console.error("Error submitting form:", err);
    },
  });
});

$(document).ready(function () {
  $("#language_table").DataTable({
    ajax: {
      url: "/admin/setting/language/list",
      type: "POST",
      dataSrc: "data",
    },
    responsive: true,
    ordering: false,
  });
});

function makeLanguageDefault(id) {
  Swal.fire({
    title: "Are you sure?",
    text: "This will make the language default!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, make it default!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post(
        "/admin/setting/language/make-default",
        {
          id: id,
        },
        function (response) {
          if (response.success) {
            toastr.success(response.message);
            $("#language_table").DataTable().ajax.reload();
          } else {
            toastr.error(response.message);
          }
        }
      );
    }
  });
}

function changeStatus(id) {
  Swal.fire({
    title: "Change status?",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Yes, change it!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post(
        "/admin/setting/language/toggle-status",
        {
          id: id,
        },
        function (response) {
          if (response.success) {
            toastr.success(response.message);
            $("#language_table").DataTable().ajax.reload();
          } else {
            toastr.error(response.message);
          }
        }
      );
    }
  });
}

$("#languageSettingForm").on("submit", function (e) {
  e.preventDefault();
  // Remove all hidden inputs for status (to avoid duplicates)

  $("input[data-bootstrap-switch]").each(function () {
    if (!$(this).prop("checked")) {
      $(this).attr("value", "0").prop("checked", true);
    } else {
      $(this).attr("value", "1").prop("checked", true);
    }
  });

  const formData = $(this).serialize(); // Gather form data
  $.ajax({
    url: "/admin/setting/updateSetting",
    method: "POST",
    dataType: "JSON",
    data: formData,
    success: function (response) {
      if (response.success) {
        toastr.success(response.message, "Admin says");
      } else {
        toastr.error(response.message, "Admin says");
      }
    },
    error: function (err) {
      console.error("Error submitting form:", err);
    },
  });
});

$("#appMainHeaderForm").submit(function (e) {
  e.preventDefault();
  $("input[data-bootstrap-switch]").each(function () {
    if (!$(this).prop("checked")) {
      $(this).attr("value", "0").prop("checked", true);
    } else {
      $(this).attr("value", "1").prop("checked", true);
    }
  });
  const formData = new FormData();
  const formFields = $(this).serializeArray();

  // Map form fields to JSON structure
  formFields.forEach((field) => {
    formData.append(field.name, field.value); // Append directly for business_name, email, and phone
  });

  // Append logo file if available
  if (imagesDropzone.files.length > 0) {
    formData.append("main_header_banner_img", imagesDropzone.files[0]);
  } else {
    console.log("No additional files selected");
  }

  $.ajax({
    url: "/admin/setting/updateSetting",
    type: "POST",
    dataType: "JSON",
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
      alert("Error occurred while updating");
    },
  });
});
