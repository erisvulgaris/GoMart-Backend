function togglePasswordVisibility() {
  const passwordInput = document.getElementById("password");
  const toggleIcon = document.getElementById("togglePasswordIcon");

  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    toggleIcon.classList.replace("fi-tr-eye-alert", "fi-tr-low-vision"); // Switch to 'eye-crossed' icon
  } else {
    passwordInput.type = "password";
    toggleIcon.classList.replace("fi-tr-low-vision", "fi-tr-eye-alert"); // Switch back to 'eye' icon
  }
}

function toggleConfirmPasswordVisibility() {
  const passwordInput = document.getElementById("confirmPassword");
  const toggleIcon = document.getElementById("toggleconfirmPasswordIcon");

  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    toggleIcon.classList.replace("fi-tr-eye-alert", "fi-tr-low-vision"); // Switch to 'eye-crossed' icon
  } else {
    passwordInput.type = "password";
    toggleIcon.classList.replace("fi-tr-low-vision", "fi-tr-eye-alert"); // Switch back to 'eye' icon
  }
}

document
  .querySelector("form")
  .addEventListener("submit", async function (event) {
    event.preventDefault();

    let email = document.getElementById("email").value;
    let token = document.getElementById("token").value;

    // Clear all previous error messages and styles
    document.querySelectorAll(".text-sm").forEach((errorDiv) => {
      errorDiv.classList.add("hidden");
      errorDiv.textContent = "";
    });
    document.querySelectorAll("input").forEach((input) => {
      input.parentElement.classList.remove("border-red-500");
    });

    const password = document.getElementById("password").value.trim();
    const confirmPassword = document
      .getElementById("confirmPassword")
      .value.trim();

    let hasError = false;

    // Validation logic with individual error messages
    if (password.length < 6) {
      document.getElementById("passwordError").textContent =
        "Password must be at least 6 characters.";
      document.getElementById("passwordError").classList.remove("hidden");
      document
        .getElementById("password")
        .parentElement.classList.add("border-red-500");
      hasError = true;
    } else if (password !== confirmPassword) {
      document.getElementById("confirmPasswordError").textContent =
        "Passwords do not match.";
      document
        .getElementById("confirmPasswordError")
        .classList.remove("hidden");
      document
        .getElementById("confirmPassword")
        .parentElement.classList.add("border-red-500");
      hasError = true;
    }

    if (hasError) {
      return;
    }

    // Remove error styles if all fields are correct
    document.querySelectorAll("input").forEach((input) => {
      input.parentElement.classList.remove("border-red-500");
      input.parentElement.classList.add("border-gray-300");
    });

    // Submit form data using the Fetch API
    try {
      const response = await fetch("/resetPassword/link", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          email,
          password,
          confirmPassword,
          token,
        }),
      });

      const result = await response.json();

      // Display the message
      const messageDiv = document.getElementById("message");
      messageDiv.textContent = result.message;
      messageDiv.className =
        result.status === "success"
          ? "text-green-700 text-sm mt-2 mb-6"
          : "text-red-500 text-sm mt-2 mb-6";

      // Redirect if signup is successful
      if (result.status === "success") {
        location.href = "/login";
      }
    } catch (error) {
      const messageDiv = document.getElementById("message");
      messageDiv.textContent = "Error signing up. Please try again later.";
      messageDiv.className = "text-red-500 text-sm mt-2 mb-6";
    }
  });
