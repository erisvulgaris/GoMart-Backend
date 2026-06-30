$("#delivery_charge_method").on("change", function () {
  if ($(this).val() == "fixed_charge") {
    $("#method_id").html(
      '<label for="fixed_charge"> Fixed Delivery Charges<span class="text-danger text-sm">*</span></label> <input type="number" name="fixed_charge" id="fixed_charge" placeholder="Global Flat Charges" min="0" max="999999999" step="any" class="form-control" fdprocessedid="qer9w" autocomplete="off">'
    );
  } else if ($(this).val() == "per_km_charge") {
    $("#method_id").html(
      '<label for="fixed_charge">Per Kilometer Delivery Charge<span class="text-danger text-sm">*</span></label> <input type="number" name="per_km_charge" id="per_km_charge" placeholder="Per Kilometer Delivery Charge" min="0" max="999999999" class="form-control" fdprocessedid="kqih5p" autocomplete="off">'
    );
  } else if ($(this).val() == "range_wise_charges") {
    $("#method_id").html(
      `<div class="form-group col-sm-12"><label>Range Wise Delivery Charges<span class="text-danger text-sm">* </span><span class="text-secondary text-sm">(Set Proper ranges for delivery charge. Do not repeat the range value to next range. For e.g. 1-3,4-6)</span></label> <div class="form-group row range-row"><div class="col-sm-1 index-label">1.</div><div class="col-sm-3"><input type="number" name="from_range[]" placeholder="From Range" min="0" max="999999999" class="form-control from-range"></div><div class="col-sm-1 btn btn-secondary">To</div> <div class="col-sm-3"><input type="number" name="to_range[]" placeholder="To Range" min="0" max="999999999" class="form-control to-range"></div> <div class="col-sm-3"><input type="number" name="price[]" placeholder="Price" min="0" max="999999999" class="form-control price"></div> <div class="col-sm-1"><a class="btn btn-primary add-row" title="Add Row" style="cursor: pointer;"><i class="fi fi-tr-add fi-2x" style="font-size: 15px;"></i></a></div> </div> </div>`
    );
  }
});

// Attach click event handler for adding rows
$("#method_id")
  .off("click", ".add-row")
  .on("click", ".add-row", function () {
    var newRowIndex = $(".range-row").length + 1;
    var newRow = `<div class="form-group row range-row">
              <div class="col-sm-1 index-label"></div>
              <div class="col-sm-3"><input type="number" name="from_range[]" placeholder="From Range" min="0" max="999999999" class="form-control from-range"></div>
              <div class="col-sm-1 btn btn-secondary">To</div> 
              <div class="col-sm-3"><input type="number" name="to_range[]" placeholder="To Range" min="0" max="999999999" class="form-control to-range"></div>
              <div class="col-sm-3"><input type="number" name="price[]" placeholder="Price" min="0" max="999999999" class="form-control price"></div> 
              <div class="col-sm-1"><a class="btn btn-danger remove-row" title="Remove Row" style="cursor: pointer;"><i class="fi fi-tr-trash-xmark fi-2x" style="font-size: 15px;"></i></a></div>
            </div>`;
    $(this).closest(".range-row").after(newRow);
    updateRowIndices();
  });

// Attach click event handler for removing rows
$("#method_id")
  .off("click", ".remove-row")
  .on("click", ".remove-row", function () {
    $(this).closest(".range-row").remove();
    updateRowIndices();
  });
// Function to update row indices
function updateRowIndices() {
  $(".range-row").each(function (index) {
    $(this)
      .find(".index-label")
      .text(index + 1 + ".");
  });
}

$("#add_deliverable_area").on("click", function (event) {
  event.preventDefault();
  var city_id = $("#city_id").val();
  var vertices = $("#vertices").val();
  var deliverable_area = $("#deliverable_area").val().trim();
  var time_to_travel = $("#time_to_travel").val();
  var min_amount_for_free_delivery = $("#min_amount_for_free_delivery").val();
  var base_delivery_time = $("#base_delivery_time").val();

  if (city_id == "") {
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

  if (min_amount_for_free_delivery == "") {
    toastr.error("Minimum Amount for Free Delivery is Required", "Admin says");
    return false;
  }

  deliverable_area = deliverable_area.replace(/\b\w/g, function (char) {
    return char.toUpperCase();
  });

  if (time_to_travel == "") {
    toastr.error("Enter Time to travel 1 (km)/min", "Admin says");
    return false;
  }

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
    $(".range-row").each(function () {
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
    delivery_charge = JSON.stringify(delivery_charge);
  } else {
    toastr.error("Select Delivery Charge Methods", "Admin says");
    return false;
  }

  if (vertices && city_id) {
    console.log(vertices);
    if (isJSON(vertices) === true) {
      geolocation_type = "circle";
      var parse_json = JSON.parse(vertices);
      $.each(parse_json, function (index, item) {
        radius = item.radius;
        final_array.push({
          latitude: parseFloat(item.lat),
          longitude: parseFloat(item.long),
        });
        final_array_web.push({
          lat: parseFloat(item.lat),
          lng: parseFloat(item.long),
        });
      });
    } else {
      geolocation_type = "polygon";
      radius = "";
      var str1 = vertices.split("),");
      $.each(str1, function (index, item) {
        var str2 = item.replace("(", "");
        var str3 = str2.replace(")", "");
        final_array.push({
          latitude: parseFloat(str3.split(",")[0]),
          longitude: parseFloat(str3.split(",")[1]),
        });
        final_array_web.push({
          lat: parseFloat(str3.split(",")[0]),
          lng: parseFloat(str3.split(",")[1]),
        });
      });
    }
    if (JSON.stringify(final_array) == '[{"latitude":null,"longitude":null}]') {
      toastr.error("Please add boundry in map", "Admin says");

      return false;
    }
    $('input[name="city_outlines"]').val(JSON.stringify(final_array));
    $('input[name="city_outlines_web"]').val(JSON.stringify(final_array_web));

    Swal.fire({
      title: "Are You Sure !",
      text: "You won't be able to revert this!",
      type: "info",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, Save it!",
      showLoaderOnConfirm: true,
      preConfirm: function () {
        return new Promise((resolve, reject) => {
          $.ajax({
            url: "/admin/deliverable-area/add",
            type: "POST",
            data: {
              boundary_points: $('input[name="city_outlines"]').val(),
              boundary_points_web: $('input[name="city_outlines_web"]').val(),
              edit_city: city_id,
              radius: radius,
              geolocation_type: geolocation_type,
              deliverable_area: deliverable_area,
              time_to_travel: time_to_travel,
              min_amount_for_free_delivery: min_amount_for_free_delivery,
              delivery_charge_method: delivery_charge_method,
              delivery_charge: delivery_charge,
              base_delivery_time: base_delivery_time,
            },
            dataType: "json",
          })
            .done(function (response) {
              if (response.success == true) {
                Swal.fire({
                  icon: "success",
                  title: "Success",
                  text: response.message,
                  timer: 2000, // Auto-close after 2 seconds
                  showConfirmButton: false,
                });
                $("#myForm").trigger("reset");
              } else {
                Swal.fire({
                  icon: "error",
                  title: "Error",
                  text: response.message,
                  timer: 2000, // Auto-close after 2 seconds
                  showConfirmButton: false,
                });
              }
            })
            .fail(function (jqXHR) {
              toastr.error("Something went wrong!", "Admin says");
              Swal.fire({
                icon: "error",
                title: "Error",
                text: response.message,
                timer: 2000, // Auto-close after 2 seconds
                showConfirmButton: false,
              });
            });
        });
      },
      allowOutsideClick: true,
    });
  } else {
    toastr.error("Please, select Maps outlines!", "Admin says");
  }
});

// $("#city_id").on('change', function() {
//     $('#vertices').val("");
// })
var map; // Global declaration of the map
var lat_longs = new Array();
var markers = new Array();
var drawingManager;
var circle;
var circle_points = [];

function initMapBoundry() {
  var myLatlng = new google.maps.LatLng(21.1777, 79.657);
  var myOptions = {
    zoom: 13,
    center: myLatlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
  };
  map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
  drawingManager = new google.maps.drawing.DrawingManager({
    drawingMode: google.maps.drawing.OverlayType.POLYGON,
    drawingControl: true,
    drawingControlOptions: {
      position: google.maps.ControlPosition.TOP_CENTER,
      drawingModes: [
        google.maps.drawing.OverlayType.POLYGON,
        // google.maps.drawing.OverlayType.CIRCLE,
      ],
    },
    polygonOptions: {
      editable: true,
    },
    circleOptions: {
      fillColor: "#666666",
      fillOpacity: 0.5,
      strokeWeight: 1,
      clickable: true,
      editable: true,
      draggable: true,
      zIndex: 1,
    },
  });
  drawingManager.setMap(map);

  google.maps.event.addListener(
    drawingManager,
    "overlaycomplete",
    function (event) {
      var newShape = event.overlay;
      newShape.type = event.type;
    }
  );

  google.maps.event.addListener(
    drawingManager,
    "overlaycomplete",
    function (event) {
      if (event.type == "circle") {
        circle_points = [];
        var radius = event.overlay.getRadius();
        var lat = event.overlay.getCenter().lat();
        var long = event.overlay.getCenter().lng();
        circle_points.push({
          type: "circle",
          radius: radius,
          lat: lat,
          long: long,
        });
        $('input[name="city_outlines"]').val(JSON.stringify(circle_points));
        $("#vertices").val(JSON.stringify(circle_points));
      } else {
        overlayClickListener(event.overlay);
        $("#vertices").val(event.overlay.getPath().getArray());
      }
    }
  );
  google.maps.event.addListener(
    drawingManager,
    "overlaycomplete",
    function (event) {
      overlayRemoveListener(event.overlay, false);
    }
  );
}

function overlayClickListener(overlay) {
  google.maps.event.addListener(overlay, "mouseup", function (event) {
    $("#vertices").val(overlay.getPath().getArray());
  });
}

function overlayRemoveListener(
  overlay,
  is_restore = false,
  drawed_map = "",
  not_remove = false
) {
  if (is_restore == true) {
    document.getElementById("add-line").addEventListener("click", addLine);
  }
  document.getElementById("clear-line").addEventListener("click", clearLine);
  if (not_remove == false) {
    document
      .getElementById("remove-line")
      .addEventListener("click", removeLine);
  }

  function clearLine() {
    overlay.setMap(null);
    $("#vertices").val("");
  }

  function removeLine() {
    overlay.setMap(null);
    $("#vertices").val("");
  }

  function addLine() {
    if (drawed_map != "") {
      overlay.setMap(drawed_map);
    } else {
      overlay.setMap(map);
    }
  }
}

// if (window.location.href.indexOf("deliverable_area.php") > -1) {
//      google.maps.event.addDomListener(window, 'load', initMapBoundry() );
// }
window.onload = function () {
  initMapBoundry();
};
// window.initMapBoundry = initMapBoundry;

var map, marker;
var lat_longs = new Array();
var markers = new Array();
var drawingManager;
$(document).ready(function () {
  $(".target").on("change", function () {
    var coordinate = $("select option:selected").data("coordinate").split(",");
    map.setCenter(new google.maps.LatLng(coordinate[0], coordinate[1]));
    let latLong = {
      lat: coordinate[0],
      lng: coordinate[1],
    };
    marker = new google.maps.marker.AdvancedMarkerElement({
      position: {
        lat: parseFloat(coordinate[0]),
        lng: parseFloat(coordinate[1]),
      },
      map: map,
    });

    marker.setVisible(true);
    var geolocation_type = $("select option:selected").data("geolocation_type");
    var radius = $("select option:selected").data("radius");
    var boundary_points = $("select option:selected").data("boundary_points");
    var json_points = JSON.stringify(boundary_points);
    $("#vertices").val(`Boundary Points : ${json_points}  Radius: ${radius}`);
    if (geolocation_type) {
      if (geolocation_type === "polygon") {
        var bermudaTriangle = new google.maps.Polygon({
          paths: boundary_points,
          strokeColor: "#FF0000",
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: "#FF0000",
          fillOpacity: 0.35,
          editable: true,
          geodesic: true,
        });
        bermudaTriangle.setMap(map);
        overlayRemoveListener(bermudaTriangle, true, map, true);
      } else if (geolocation_type === "circle") {
        const cityCircle = new google.maps.Circle({
          strokeColor: "#FF0000",
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: "#FF0000",
          fillOpacity: 0.35,
          map,
          center: boundary_points[0],
          radius: Math.sqrt(radius) * 100,
        });
        overlayRemoveListener(cityCircle, true, map, true);
      }
    }
  });
});

function isJSON(something) {
  if (typeof something != "string") something = JSON.stringify(something);
  try {
    JSON.parse(something);
    return true;
  } catch (e) {
    return false;
  }
}
