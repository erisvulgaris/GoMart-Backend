// Helper function to generate address card HTML
function generateAddressHTML(address, isActive) {
  const iconClass =
    address.address_type === "Home"
      ? "fi fi-rr-home"
      : address.address_type === "Work"
      ? "fi fi-rr-building"
      : "fi fi-rr-marker";

  const bgColor = isActive
    ? "bg-[#FFF4F1] border-red-400"
    : "bg-[#F7F7F7] border-[#F7F7F7]";

  return `<div class="w-full flex ${bgColor} py-2 px-2 rounded-lg cursor-pointer border mb-2 md:mb-0 address-card ${
    isActive ? "active" : ""
  }" onclick="setActiveAddress(this, ${address.id})">
            <div class="w-4/5 pr-4" onclick="event.stopPropagation(); setActiveAddress(this.parentElement, ${
              address.id
            });">
                <span class="text-base font-medium capitalize">
                    <i class="${iconClass}"></i>
                    ${address.address_type}:
                </span>
                <span class="text-base font-medium capitalize mb-1">${
                  address.user_name
                }</span>
                <span class="block text-sm leading-6">${
                  address.user_mobile
                }</span>
                <span class="block text-sm leading-6">${
                  address.user_email || ""
                }</span>
                <span class="block text-sm leading-6">${address.flat},</span>
                <span class="block text-sm leading-6">${address.address},</span>
                <span class="block text-sm leading-6">${address.area}, ${
    address.city
  },</span>
                <span class="block text-sm leading-6">${address.state},</span>
                <span class="block text-sm leading-6">${address.pincode}</span>
            </div>
            <div class="w-1/5 flex justify-end items-end">
                <i class="fi fi-rr-trash text-red-500 text-lg" onclick="event.stopPropagation(); deleteAddress(${
                  address.id
                });"></i>
            </div>
        </div>`;
}

function closeAddressPopup() {
  addressModal.classList.add("hidden");
  document.body.classList.remove("modal-open");
}

function openAddressPopup() {
  if (!addressModal) {
    console.error("Modal element not found.");
    return;
  }

  addressModal.classList.remove("hidden");
  document.body.classList.add("modal-open");

  // Retrieve location data from localStorage
  const locationData = JSON.parse(localStorage.getItem("location"));

  if (!locationData || !locationData.lat || !locationData.lng) {
    console.error("Location data is missing or incomplete in localStorage.");
    return;
  }

  latitude = locationData.lat;
  longitude = locationData.lng;

  const currLocation = {
    lat: locationData.lat,
    lng: locationData.lng,
  };

  // Initialize map
  const map = new google.maps.Map(document.getElementById("map"), {
    zoom: 15,
    center: currLocation,
  });

  // Place a draggable marker at the current location
  const marker = new google.maps.Marker({
    position: currLocation,
    map: map,
    title: "Your location",
    draggable: true,
  });

  // Fetch delivery area status
  fetchIsInDeliveryArea(locationData.lat, locationData.lng);

  // Event listener to update latitude and longitude after moving the marker
  marker.addListener("dragend", function (event) {
    const newLat = event.latLng.lat();
    const newLng = event.latLng.lng();

    latitude = newLat;
    longitude = newLng;

    fetchIsInDeliveryArea(newLat, newLng);
  });
}

function fetchAddress(lat, lng) {
  const geocoder = new google.maps.Geocoder();
  const latlng = {
    lat,
    lng,
  };

  document.getElementById("address").value;

  geocoder.geocode(
    {
      location: latlng,
    },
    (results, status) => {
      console.log(results);
      map_address = results[0].formatted_address;
      if (status === "OK" && results[0]) {
        // Set full address
        document.getElementById("address").value = "";
        document.getElementById("area").value = "";
        document.getElementById("city").value = "";
        document.getElementById("state").value = "";
        document.getElementById("pincode").value = "";

        let fullAddress = results[0].formatted_address;
        let area = "",
          city = "",
          state = "",
          pincode = "";
        // Populate locationData with address components
        results[0].address_components.forEach((component) => {
          const types = component.types;
          if (types.includes("sublocality") || types.includes("neighborhood")) {
            area = component.long_name;
            document.getElementById("area").value = area;
          } else if (types.includes("locality")) {
            city = component.long_name;
            document.getElementById("city").value = city;
          } else if (types.includes("administrative_area_level_1")) {
            state = component.long_name;
            document.getElementById("state").value = state;
          } else if (types.includes("postal_code")) {
            pincode = component.long_name;
            document.getElementById("pincode").value = pincode;
          }
        });

        [area, city, state, pincode].forEach((val) => {
          if (val && fullAddress.includes(val)) {
            fullAddress = fullAddress
              .replace(val, "")
              .replace(/,+/g, ",")
              .trim();
          }
        });

        // Clean up extra spaces and commas
        fullAddress = fullAddress
          .replace(/^,|,$/g, "")
          .replace(/\s+,/g, ",")
          .replace(/,\s+/g, ", ")
          .trim();

        // Set cleaned address
        document.getElementById("address").value = fullAddress;
      }
    }
  );
}

function selectAddressType(addressType) {
  // Update the hidden input value
  document.getElementById("address_type").value = addressType;

  // Get all the location div elements
  const locationDivs = document.querySelectorAll(".flex.space-x-4 > div");

  // Iterate over the divs and apply/remove classes based on selection
  locationDivs.forEach((div) => {
    if (div.textContent.trim() === addressType) {
      // Add selected styles
      div.classList.add("border-green-700", "bg-green-100", "shadow-md");
      div.classList.remove("border-gray-300");
    } else {
      // Reset unselected styles
      div.classList.remove("border-green-700", "bg-green-100", "shadow-md");
      div.classList.add("border-gray-300");
    }
  });
}

async function setActiveAddress(card, address_id) {
  // Remove 'active' class from all address cards
  const allAddressCards = document.querySelectorAll(".address-card");
  allAddressCards.forEach((addressCard) => {
    addressCard.classList.remove("bg-[#FFF4F1]", "border-red-400"); // Deactivate previous address
  });

  // Add 'active' class to the clicked card
  card.classList.add("bg-[#FFF4F1]", "border-red-400"); // Activate clicked address

  try {
    const response = await fetch("/activeAddress", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        address_id,
      }),
    });

    const result = await response.json();
    console.log(result);

    // Handle success or error response
    if (result.status === "success") {
      const subtotal = document.getElementsByClassName("subtotal");
      const taxTotal = document.getElementsByClassName("taxTotal");
      const deliveryCharge = document.getElementsByClassName("deliveryCharge");
      const grand_total = document.getElementsByClassName("grand_total");

      const wallet = JSON.parse(localStorage.getItem("wallet")) || {
        wallet_applied: 0,
        remaining_wallet_balance: 0,
      };

      const appliedCoupon = JSON.parse(
        localStorage.getItem("appliedCoupon")
      ) || {
        coupon_amount: 0,
      };

      Array.from(subtotal).forEach((element) => {
        element.innerText = result.subTotal;
      });
      Array.from(taxTotal).forEach((element) => {
        element.innerText = result.taxTotal;
      });
      Array.from(deliveryCharge).forEach((element) => {
        element.innerText = result.deliveryCharge;
      });

      Array.from(grand_total).forEach((element) => {
        const grandTotal =
          (parseFloat(result.subTotal) || 0) +
          (parseFloat(result.taxTotal) || 0) +
          (parseFloat(result.deliveryCharge) || 0) -
          (parseFloat(appliedCoupon.coupon_amount) || 0) -
          (parseFloat(wallet.wallet_applied) || 0);

        // Round to 2 decimal places
        element.innerText = grandTotal.toFixed(2);
      });
      showToast(result.message, "success");
    } else {
      showToast(result.message, "error");
    }
  } catch (error) {
    console.log(error);
  }
}

async function deleteAddress(address_id) {
  // Logic to handle the deletion of the address
  try {
    const response = await fetch("/deleteAddress", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        address_id,
      }),
    });

    const result = await response.json();

    // Handle success or error response
    if (result.status === "success") {
      fetchAddressList();
      showToast(result.message, "success");
    } else {
      showToast(result.message, "error");
    }
  } catch (error) {
    console.log(error);
  }
}
window.addEventListener("DOMContentLoaded", () => {
  fetchAddressList();
});

function fetchIsInDeliveryArea(lat, lng) {
  fetch("/fetchIsInDeliveryArea", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      lat: lat,
      lng: lng,
    }),
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((result) => {
      const areaNotFoundElements =
        document.getElementsByClassName("areaNotFound");
      const addressFormElements =
        document.getElementsByClassName("addressForm");

      if (result.status === "success") {
        fetchAddress(lat, lng);
        // Hide elements with the 'areaNotFound' class
        for (let element of areaNotFoundElements) {
          element.classList.add("hidden");
        }
        // Show elements with the 'addressForm' class by removing the 'hidden' class
        for (let element of addressFormElements) {
          element.classList.remove("hidden");
        }

        // Display success message or handle successful area validation
        console.log("Delivery is available in this area.");
        showToast(result.message, "success");
      } else {
        // Hide elements with the 'areaNotFound' class
        for (let element of addressFormElements) {
          element.classList.add("hidden");
        }
        // Show elements with the 'addressForm' class by removing the 'hidden' class
        for (let element of areaNotFoundElements) {
          element.classList.remove("hidden");
        }

        // Display error message for non-deliverable area
        console.log("We are not available at this location.");
        showToast(result.message, "error");
      }
    })
    .catch((error) => {
      console.error("Error checking delivery area:", error);
    });
}
