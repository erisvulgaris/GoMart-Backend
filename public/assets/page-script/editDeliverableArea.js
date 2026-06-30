

// Function to clear all polygons (original and new)
function clearMap() {
    if (originalPolygon) {
        originalPolygon.setMap(null); // Remove the original polygon
    }
    drawnPolygons.forEach(function(polygon) {
        polygon.setMap(null); // Remove all drawn polygons
    });
    drawnPolygons = [];
    
    // $("#vertices").val("");
}

function restoreOriginalMap() {
    if (originalPolygon) {
        originalPolygon.setMap(map);
    }
}

function removeNewPolygons() {
    drawnPolygons.forEach(function(polygon) {
        polygon.setMap(null); // Remove each newly drawn polygon
    });
    drawnPolygons = []; // Reset the array of drawn polygons
}

// Function to collect all boundary points and send them to the server
function uploadBoundaryPoints() {
    var allPoints = [];

    drawnPolygons.forEach(function(polygon) { 
        var path = polygon.getPath(); // Get the path of the polygon
        var polygonPoints = [];

        for (var i = 0; i < path.getLength(); i++) {
            var latLng = path.getAt(i);
            polygonPoints.push({
                lat: latLng.lat(),
                lng: latLng.lng()
            });
        }

        allPoints.push(polygonPoints); // Add polygon points to the list
    });

    // Log points to console for verification
    console.log('All Boundary Points:', allPoints);


}

$("#delivery_charge_method").on('change', function() {
    if ($(this).val() == 'fixed_charge') {
        $('#method_id').html('<label for="fixed_charge"> Fixed Delivery Charges<span class="text-danger text-sm">*</span></label> <input type="number" name="fixed_charge" id="fixed_charge" placeholder="Global Flat Charges" min="0" max="999999999" step="any" class="form-control" fdprocessedid="qer9w" autocomplete="off">')
    } else if ($(this).val() == 'per_km_charge') {
        $('#method_id').html('<label for="fixed_charge">Per Kilometer Delivery Charge<span class="text-danger text-sm">*</span></label> <input type="number" name="per_km_charge" id="per_km_charge" placeholder="Per Kilometer Delivery Charge" min="0" max="999999999" class="form-control" fdprocessedid="kqih5p" autocomplete="off">')
    } else if ($(this).val() == 'range_wise_charges') {
        $('#method_id').html(`<div class="form-group col-sm-12"><label>Range Wise Delivery Charges<span class="text-danger text-sm">* </span><span class="text-secondary text-sm">(Set Proper ranges for delivery charge. Do not repeat the range value to next range. For e.g. 1-3,4-6)</span></label> <div class="form-group row range-row"><div class="col-sm-1 index-label">1.</div><div class="col-sm-3"><input type="number" name="from_range[]" placeholder="From Range" min="0" max="999999999" class="form-control from-range"></div><div class="col-sm-1 btn btn-secondary">To</div> <div class="col-sm-3"><input type="number" name="to_range[]" placeholder="To Range" min="0" max="999999999" class="form-control to-range"></div> <div class="col-sm-3"><input type="number" name="price[]" placeholder="Price" min="0" max="999999999" class="form-control price"></div> <div class="col-sm-1"><a class="btn btn-primary add-row" title="Add Row" style="cursor: pointer;"><i class="fi fi-tr-add fi-2x" style="font-size: 15px;"></i></a></div> </div> </div>`);

    }
})

// Attach click event handler for adding rows
$('#method_id').off('click', '.add-row').on('click', '.add-row', function() {
    var newRowIndex = $('.range-row').length + 1;
    var newRow = `<div class="form-group row range-row">
                <div class="col-sm-1 index-label"></div>
                <div class="col-sm-3"><input type="number" name="from_range[]" placeholder="From Range" min="0" max="999999999" class="form-control from-range"></div>
                <div class="col-sm-1 btn btn-secondary">To</div> 
                <div class="col-sm-3"><input type="number" name="to_range[]" placeholder="To Range" min="0" max="999999999" class="form-control to-range"></div>
                <div class="col-sm-3"><input type="number" name="price[]" placeholder="Price" min="0" max="999999999" class="form-control price"></div> 
                <div class="col-sm-1"><a class="btn btn-danger remove-row" title="Remove Row" style="cursor: pointer;"><i class="fi fi-tr-trash-xmark fi-2x" style="font-size: 15px;"></i></a></div>
              </div>`;
    $(this).closest('.range-row').after(newRow);
    updateRowIndices();
});

// Attach click event handler for removing rows
$('#method_id').off('click', '.remove-row').on('click', '.remove-row', function() {
    $(this).closest('.range-row').remove();
    updateRowIndices();
});
// Function to update row indices
function updateRowIndices() {
    $('.range-row').each(function(index) {
        $(this).find('.index-label').text(index + 1 + '.');
    });
}

function isJSON(str) {
    try {
        JSON.parse(str);
        return true;
    } catch (e) {
        return false;
    }
}

$("#edit_deliverable_area").on("click", function(event) {
    event.preventDefault();
    var city_id = $("#city_id").val();
    var edit_id = $("#edit_id").val();

    var vertices = $("#vertices").val();
    var deliverable_area = $("#deliverable_area").val().trim();

    var time_to_travel = $("#time_to_travel").val();
    var min_amount_for_free_delivery = $("#min_amount_for_free_delivery").val();
    var base_delivery_time = $("#base_delivery_time").val();

    if (city_id == '') {
        toastr.error("Select City", "Admin says");
        return false;
    }
    var regex = /^[A-Za-z][A-Za-z\s]*$/;

    if (deliverable_area === "") {
        toastr.error("Deliverable area name is required", "Admin says");
        return false;
    }

    if (!regex.test(deliverable_area)) {
        toastr.error(
            "Deliverable area name must start with an alphabet and contain only letters and spaces",
            "Admin says"
        );
        return false;
    }

    if (time_to_travel == '') {
        toastr.error("Enter Time to travel 1 (km)/min", "Admin says");
        return false;
    }

    if (min_amount_for_free_delivery == '') {
        toastr.error("Minimum Amount for Free Delivery is Required", "Admin says");
        return false;
    }

    deliverable_area = deliverable_area.replace(/\b\w/g, function(char) {
        return char.toUpperCase();
    });


    var final_array = [];
    var final_array_web = [];
    var geolocation_type = "";
    var radius = "";

    var delivery_charge_method = $("#delivery_charge_method").val();

    if (delivery_charge_method == "fixed_charge") {
        var delivery_charge = $("#fixed_charge").val();
    } else if (delivery_charge_method == "per_km_charge") {
        var delivery_charge = $("#per_km_charge").val();
    } else if (delivery_charge_method == "range_wise_charges") {
        var delivery_charge = [];
        $(".range-row").each(function() {
            var fromRange = $(this).find(".from-range").val();
            var toRange = $(this).find(".to-range").val();
            var price = $(this).find(".price").val();
            if (fromRange && toRange && price) {
                delivery_charge.push({
                    from_range: fromRange,
                    to_range: toRange,
                    price: price,
                });
            }
        });
        delivery_charge = JSON.stringify(delivery_charge)
    } else {
        toastr.error("Select Delivery Charge Methods", "Admin says");
        return false;
    }

    console.log(delivery_charge)



    if (vertices) {
    var str1 = vertices.split("),");
    final_array = [];
    final_array_web = [];

    $.each(str1, function(index, item) {
        // Clean up and remove any remaining parentheses or spaces
        item = item.replace("(", "").replace(")", "").trim();
        var coords = item.split(",");
        var lat = parseFloat(coords[0]);
        var lng = parseFloat(coords[1]);

        if (!isNaN(lat) && !isNaN(lng)) {
            final_array.push({
                latitude: lat,
                longitude: lng
            });
            final_array_web.push({
                lat: lat,
                lng: lng
            });
        }
    });

    if (final_array.length === 0) {
        toastr.error("Please add boundary in map", "Admin says");
        return false;
    }

    $('input[name="city_outlines"]').val(JSON.stringify(final_array));
    $('input[name="city_outlines_web"]').val(JSON.stringify(final_array_web));
}
    Swal.fire({
        title: "Are You Sure !",
        text: "You won't be able to revert this!",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Update it!",
        showLoaderOnConfirm: true,
        preConfirm: function() {
            return new Promise((resolve, reject) => {
                $.ajax({
                        url: "/admin/deliverable-area/update",
                        type: "POST",
                        data: {
                            boundary_points: $('#city_outlines').val(),
                            boundary_points_web: $('#city_outlines_web').val(),
                            edit_city: city_id,
                            radius: radius,
                            geolocation_type: $('#geolocation_type').val(),
                            deliverable_area: deliverable_area,
                            time_to_travel: time_to_travel,
                            min_amount_for_free_delivery: min_amount_for_free_delivery,
                            delivery_charge_method: delivery_charge_method,
                            delivery_charge: delivery_charge,
                            edit_id: edit_id,
                            base_delivery_time:base_delivery_time
                        },
                        dataType: "json",
                    })
                    .done(function(response) {
                        if (response.success == true) {
                            toastr.success(response.message, "Admin says");

                            $("#vertices").val("");
                            setTimeout(function() {
                                location = '/admin/deliverable-area/view';
                            }, 2500);
                        } else {
                            toastr.error(response.message, "Admin says");

                        }
                    })
                    .fail(function(jqXHR) {
                        toastr.error("Something went wrong!", "Admin says");
                    });
            });
        },
        allowOutsideClick: false,
    });

});