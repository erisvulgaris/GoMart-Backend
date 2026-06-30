function updateHighlight() {
    var highlights_id = $("#highlights_id").val();
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
    formData.append("highlights_id", highlights_id);
    formData.append("seller_id", sellerId);
    formData.append("title", title);
    formData.append("description", description);
    formData.append("media_type", mediaType);

    if (mediaType === "video") {
        formData.append("video", video);
    } else if (mediaType === "image") {
        if (highlightDropzone.files.length > 0) {
            highlightDropzone.files.forEach(file => formData.append("image[]", file));
        }
    }

    // AJAX Request
    $.ajax({
        url: "/admin/highlight/update",
        type: "POST",
        data: formData,
        dataType: "json",
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                toastr.success(response.message, "Admin says");
                function route() {
                    location = "/admin/highlight";
                }
                setTimeout(route, 2500);
            } else {
                toastr.error(response.message, "Admin says");
            }
        },
        error: function() {
            toastr.error("Something went wrong. Please try again.", "Admin says");
        }
    });
}



Dropzone.autoDiscover = false;

const highlightDropzone = new Dropzone("#highlights-image", {
    url: '#',
    autoProcessQueue: false, // Hold file upload until submit
    maxFiles: 1,
    acceptedFiles: ".jpeg,.jpg,.png,.webp",
    addRemoveLinks: true,
    clickable: ".dropzone-clickable-area" // Targeting the inner container
});

$("#media_type").on('change', function() {
    if ($("#media_type").val() == 'video') {
        $("#video-div").removeClass('d-none')
        $("#image-div").addClass('d-none')
    } else {
        $("#image-div").removeClass('d-none')
        $("#video-div").addClass('d-none')
    }
})