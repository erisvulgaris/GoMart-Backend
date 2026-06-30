$("input[data-bootstrap-switch]").each(function() {
    $(this).bootstrapSwitch('state');
})
Dropzone.autoDiscover = false;
const imagesDropzone = new Dropzone("#images-dropzone", {
    url: '#',
    autoProcessQueue: false,
    acceptedFiles: ".jpeg,.jpg,.png, .webp",
    uploadMultiple: true,
    addRemoveLinks: true,
    clickable: ".dropzone-clickable-area" // Targeting the inner container
});

function initAutocomplete() {
    const map = new google.maps.Map(document.getElementById("map"), {
        center: {
            lat: -33.8688,
            lng: 151.2195
        },
        zoom: 13,
        mapTypeId: "roadmap",
    });
    // Create the search box and link it to the UI element.
    var options = {
        types: ['(cities)']
    };
    const input = document.getElementById("pac-input");
    const searchBox = new google.maps.places.SearchBox(input, options);
    // const searchBox = new google.maps.places.Autocomplete(input, options); // get only city name in searchbox

    // map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
    // Bias the SearchBox results towards current map's viewport.
    map.addListener("bounds_changed", () => {
        searchBox.setBounds(map.getBounds());
    });

    let markers = [];

    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    searchBox.addListener("places_changed", () => {
        const places = searchBox.getPlaces();
        const place1 = places[0];



        if (places.length == 0) {
            return;
        }
        $("#city_name").val("");
        $("#latitude").val("");
        $("#longitude").val("");
        const parser = new DOMParser();
        const doc = parser.parseFromString(place1.adr_address, 'text/html');
        const locality = doc.querySelector('.locality').textContent;

        if (place1.name != locality) {
            alert("No City Found \ntry another keyword");
            $("#pac-input").val("");
            return;
        }

        // Clear out the old markers.
        markers.forEach((marker) => {
            marker.setMap(null);
        });
        markers = [];

        // For each place, get the icon, name and location.
        const bounds = new google.maps.LatLngBounds();




        places.forEach((place) => {
            if (!place.geometry || !place.geometry.location) {
                console.log("Returned place contains no geometry");
                return;
            }

            const icon = {
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(25, 25),
            };

            // Create a marker for each place.
            markers.push(
                new google.maps.Marker({
                    map,
                    icon,
                    title: place.name,
                    position: place.geometry.location,
                    anchorPoint: new google.maps.Point(0, -29),
                })
            );

            console.log(bounds.extend(place.geometry.location))
            var lng = place1.geometry.location.lng();
            var lat = place1.geometry.location.lat()

            $("#city_name").val(locality);
            $("#latitude").val(lat);
            $("#longitude").val(lng);


            // const Place_area = console.log(place1)

            if (place.geometry.viewport) {
                // Only geocodes have viewport.
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
        });
        map.fitBounds(bounds);
    });
}

function changeURL(url) {
    history.pushState(null, null, url);
}

window.initAutocomplete = initAutocomplete;
$("#storeSettingForm").on('submit', function(e) {
    e.preventDefault();

    const formData = new FormData();
    const formFields = $(this).serializeArray();
    const jsonData = {};

    // Map form fields to JSON structure
    formFields.forEach((field) => {
        if (field.name != 'business_name' && field.name != 'email' && field.name != 'phone' && field.name != 'company_gst') {
            jsonData[field.name] = field.value;
        } else {
            formData.append(field.name, field.value); // Append directly for business_name, email, and phone
        }
    });

    formData.append('address', JSON.stringify(jsonData));

    // Append logo file if available
    if (imagesDropzone.files.length > 0) {
        formData.append('logo', imagesDropzone.files[0]);
    } else {
        console.log("No additional files selected");
    }

    // Submit data via AJAX
    $.ajax({
        url: '/admin/setting/updateSetting',
        method: 'POST',
        dataType: 'JSON',
        processData: false, // Don't process FormData
        contentType: false, // Don't set content type header
        data: formData, // Use FormData
        success: function(response) {
            if (response.success) {
                toastr.success(response.message, "Admin says");
            } else {
                toastr.error(response.message, "Admin says");
            }
        },
        error: function(err) {
            console.error('Error submitting form:', err);
        }
    });
});
$("#countryForm").on('submit', function(e) {
    e.preventDefault();
    const formData = $(this).serialize(); // Gather form data
    $.ajax({
        url: '/admin/setting/countrySetting',
        method: 'POST',
        dataType: 'JSON',
        data: formData,
        success: function(response) {
            if (response.success) {
                toastr.success(response.message, "Admin says");
            } else {
                toastr.error(response.message, "Admin says");
            }
        },
        error: function(err) {
            console.error('Error submitting form:', err);
        }
    });
});
$("#orderForm").on('submit', function(e) {
    e.preventDefault();
    $("input[data-bootstrap-switch]").each(function() {
        if (!$(this).prop("checked")) {
            $(this).attr("value", "0").prop("checked", true);
        } else {
            $(this).attr("value", "1").prop("checked", true)
        }
    });

    const formData = new FormData();
    formData.append("home_delivery_status", JSON.stringify({
        id: $("#home_delivery_status_id").val(),
        title: $("#home_delivery_status_title").val(),
        description: $("#home_delivery_status_description").val(),
        image: $("#home_delivery_status_image").val(),
        status: $("#home_delivery_status_status").val()
    }));

    formData.append("schedule_delivery_status", JSON.stringify({
        id: $("#schedule_delivery_status_id").val(),
        title: $("#schedule_delivery_status_title").val(),
        description: $("#schedule_delivery_status_description").val(),
        image: $("#schedule_delivery_status_image").val(),
        status: $("#schedule_delivery_status_status").val()
    }));

    formData.append("takeaway_status", JSON.stringify({
        id: $("#takeaway_status_id").val(),
        title: $("#takeaway_status_title").val(),
        description: $("#takeaway_status_description").val(),
        image: $("#takeaway_status_image").val(),
        status: $("#takeaway_status_status").val()
    }));

    formData.append("additional_charge_name", $("#additional_charge_name").val());
    formData.append("additional_charge_status", $("#additional_charge_status").val());
    formData.append("additional_charge", $("#additional_charge").val());
    formData.append("order_delivery_verification", $("#order_delivery_verification").val());
    formData.append("live_tracking", $("#live_tracking").val());
    formData.append("minimum_order_amount", $("#minimum_order_amount").val());

    // Delivery charge tax
    formData.append("delivery_charge_tax_status", $("#delivery_charge_tax_status").val());
    $("input[name='delivery_charge_tax_ids[]']:checked").each(function() {
        formData.append("delivery_charge_tax_ids[]", $(this).val());
    });

    // Additional charge tax
    formData.append("additional_charge_tax_status", $("#additional_charge_tax_status").val());
    $("input[name='additional_charge_tax_ids[]']:checked").each(function() {
        formData.append("additional_charge_tax_ids[]", $(this).val());
    });

    $.ajax({
        url: '/admin/setting/updateSetting',
        method: 'POST',
        dataType: 'JSON',
        data: formData,
        processData: false, // Prevent jQuery from converting the data into a query string
        contentType: false, // Let the browser set the correct Content-Type
        success: function(response) {
            if (response.success) {
                toastr.success(response.message, "Admin says");
            } else {
                toastr.error(response.message, "Admin says");
            }
        },
        error: function(err) {
            console.error('Error submitting form:', err);
        }
    });
});
$("#deliveryBoyForm").on('submit', function(e) {
    e.preventDefault();
    $("input[data-bootstrap-switch]").each(function() {
        if (!$(this).prop("checked")) {
            $(this).attr("value", "0").prop("checked", true);
        } else {
            $(this).attr("value", "1").prop("checked", true)
        }
    });
    const formData = $(this).serialize(); // Gather form data
    $.ajax({
        url: '/admin/setting/updateSetting',
        method: 'POST',
        dataType: 'JSON',
        data: formData,
        success: function(response) {
            if (response.success) {
                toastr.success(response.message, "Admin says");
            } else {
                toastr.error(response.message, "Admin says");
            }
        },
        error: function(err) {
            console.error('Error submitting form:', err);
        }
    });
});
$("#appSettingForm").on('submit', function(e) {
    e.preventDefault();
    const formData = $(this).serialize(); // Gather form data
    $.ajax({
        url: '/admin/setting/updateSetting',
        method: 'POST',
        dataType: 'JSON',
        data: formData,
        success: function(response) {
            if (response.success) {
                toastr.success(response.message, "Admin says");
            } else {
                toastr.error(response.message, "Admin says");
            }
        },
        error: function(err) {
            console.error('Error submitting form:', err);
        }
    });
});
$("#frontendSettingForm").on('submit', function(e) {
    e.preventDefault();

    $("input[data-bootstrap-switch]").each(function() {
        if (!$(this).prop("checked")) {
            $(this).attr("value", "0").prop("checked", true);
        } else {
            $(this).attr("value", "1").prop("checked", true)
        }
    });

    const formData = $(this).serialize(); // Gather form data
    $.ajax({
        url: '/admin/setting/updateSetting',
        method: 'POST',
        dataType: 'JSON',
        data: formData,
        success: function(response) {
            if (response.success) {
                toastr.success(response.message, "Admin says");
            } else {
                toastr.error(response.message, "Admin says");
            }
        },
        error: function(err) {
            console.error('Error submitting form:', err);
        }
    });
});
$("#sellerSettingForm").on('submit', function(e) {
    e.preventDefault();
    $("input[data-bootstrap-switch]").each(function() {
        if (!$(this).prop("checked")) {
            $(this).attr("value", "0").prop("checked", true);
        } else {
            $(this).attr("value", "1").prop("checked", true)
        }
    });
    const formData = $(this).serialize(); // Gather form data
    $.ajax({
        url: '/admin/setting/updateSetting',
        method: 'POST',
        dataType: 'JSON',
        data: formData,
        success: function(response) {
            if (response.success) {
                toastr.success(response.message, "Admin says");
            } else {
                toastr.error(response.message, "Admin says");
            }
        },
        error: function(err) {
            console.error('Error submitting form:', err);
        }
    });
});
