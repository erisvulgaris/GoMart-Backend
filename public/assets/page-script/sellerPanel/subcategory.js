function updateSubCat() {
    var cat_id = $("#cat_id").val();
    var sub_cat_name = $("#sub_cat_name").val();
    var files = $("#sub_cat_img_webp")[0].currentSrc;
    var sub_cat_id = $("#sub_cat_id").val();

    if (cat_id == "" || sub_cat_name == "") {
        toastr.error(
            "Select Category & Enter Subcategory name is required",
            "Admin says"
        );
        return false;
    }

    $.ajax({
        url: "/admin/subcategory/update",
        type: "POST",
        data: {
            cat_id: cat_id,
            sub_cat_name: sub_cat_name,
            sub_cat_id: sub_cat_id,
            sub_cat_img: files,
        },
        dataType: "json",
        success: function(response) {
            if (response.success == true) {
                toastr.success("Sub Category updated", "Admin says");
                setTimeout(function() {
                    window.location.href = "/subcategory";
                }, 2000);
            } else {
                toastr.error("Something went wrong", "Admin says");
                return false;
            }
        },
    });
}

function addSubCat() {
    var cat_id = $("#cat_id").val();
    var sub_cat_name = $("#sub_cat_name").val();
    var files = $("#sub_cat_img_webp")[0].currentSrc;

    if (cat_id == "" || sub_cat_name == "") {
        toastr.error(
            "Select Category & Enter Subcategory name is required",
            "Admin says"
        );
        return false;
    }

    if (files.length == 0) {
        toastr.error("Subcategory image is required", "Admin says");
        return false;
    }

    $.ajax({
        url: "/admin/subcategory/add",
        type: "POST",
        data: {
            cat_id: cat_id,
            sub_cat_name: sub_cat_name,
            sub_cat_img: files,
        },
        dataType: "json",
        success: function(response) {
            if (response.success == true) {
                toastr.success("Sub Category added", "Admin says");
                $("#view_sub_category").DataTable().ajax.reload();

                location.reload();
            } else {
                toastr.error("file not uploaded", "Admin says");
                return false;
            }
        },
    });
}

function convertImage(event) {
    var fileName = document.getElementById("sub_cat_img").value;
    var idxDot = fileName.lastIndexOf(".") + 1;
    var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
    if (
        extFile == "jpeg" ||
        extFile == "png" ||
        extFile == "webp" ||
        extFile == "jpg"
    ) {
        const sub_cat_img_webp = document.querySelector("#sub_cat_img_webp");

        if (event.target.files.length > 0) {
            let src = URL.createObjectURL(event.target.files[0]);

            let canvas = document.createElement("canvas");
            let ctx = canvas.getContext("2d");
            let userImage = new Image();
            userImage.src = src;

            userImage.onload = function() {
                canvas.width = userImage.width;
                canvas.height = userImage.height;

                ctx.drawImage(userImage, 0, 0);

                let webpImage = canvas.toDataURL("image/webp", 0.8);
                sub_cat_img_webp.src = webpImage;
            };
        }
    } else {
        toastr.error("file not supported", "Admin says");
        return false;
        $("#sub_cat_img").val("");
    }
}

$("#view_sub_category").dataTable({
    paging: true,
    lengthChange: true,
    searching: true,
    ordering: true,
    info: true,
    autoWidth: true,
    responsive: true,
    ajax: {
        url: "/seller/subcategory/list",
        type: "POST",
        dataType: "json",
        dataSrc: "data",
    },
});