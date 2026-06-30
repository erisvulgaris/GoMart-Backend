$("input[data-bootstrap-switch]").each(function () {
  $(this).bootstrapSwitch("state");
});

function updatePermission() {
  var permissionJson = [];
  var select_permission_id;
  var can_view, can_add, can_edit, can_delete, role_id;

  // Loop over elements dynamically based on category IDs
  $('input[type="hidden"][id^="select_permission_id_"]').each(function () {
    var category_id = $(this).attr("id").split("_")[3];
    select_permission_id = $(this).val();
    can_view = $("#" + category_id + "_can_view").is(":checked") ? 1 : 0;
    can_add = $("#" + category_id + "_can_add").is(":checked") ? 1 : 0;
    can_edit = $("#" + category_id + "_can_edit").is(":checked") ? 1 : 0;
    can_delete = $("#" + category_id + "_can_delete").is(":checked") ? 1 : 0;
    role_id = $("#editid").val();

    permissionJson.push({
      can_view: can_view,
      can_add: can_add,
      can_edit: can_edit,
      can_delete: can_delete,
      select_permission_id: select_permission_id,
      category_id: category_id,
      role_id:role_id
    });
  });

  var permissionEncodedJson = JSON.stringify(permissionJson);
  console.log(permissionEncodedJson);

  $.ajax({
    url: "update",
    type: "POST",
    data: {
      permissionEncodedJson,
    },
    dataType: "json",
    success: function (response) {
      if (response.success == true) {
        toastr.success(response.message, "Admin says");
      } else {
        toastr.error(response.message, "Admin says");
      }
    },
  });
}
