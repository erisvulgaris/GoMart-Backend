const otpInputs = document.querySelectorAll(".otp-input");

otpInputs.forEach((input, index) => {
  input.addEventListener("input", () => {
    if (input.value.length === 1 && index < otpInputs.length - 1) {
      otpInputs[index + 1].focus();
    }
  });

  input.addEventListener("keydown", (e) => {
    if (e.key === "Backspace" && index > 0 && input.value === "") {
      otpInputs[index - 1].focus();
    }
  });
});

// Form submit handling with fetch API
document
  .getElementById("otpForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault();
    const guestId = localStorage.getItem("guest_id");

    // Get OTP values from input fields
    let otp = "";
    for (let i = 1; i <= 6; i++) {
      otp += document.getElementById("otp" + i).value;
    }

    // Check if the OTP is 6 digits
    if (otp.length !== 6 || isNaN(otp)) {
      document.getElementById("otpError").classList.remove("hidden");
      return;
    }

    // Hide error message if OTP is valid
    document.getElementById("otpError").classList.add("hidden");

    // Send OTP to the backend via Fetch API
    try {
      const response = await fetch("/signupOtp", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          otp: otp,
          guest_id: guestId,
        }),
      });

      const result = await response.json();
      const messageDiv = document.getElementById("message");
      messageDiv.textContent = result.message;
      messageDiv.className =
        result.status === "success"
          ? "text-green-700 text-sm mt-1"
          : "text-red-500 text-sm mt-1";

      if (result.status === "success") {
        location.href = "/";
      }
    } catch (error) {
      const messageDiv = document.getElementById("message");
      messageDiv.textContent = "Error to verify OTP. Please try again later.";
      messageDiv.className = "text-red-500 text-sm mt-1";
    }
  });
