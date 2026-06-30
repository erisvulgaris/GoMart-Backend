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

document.addEventListener("DOMContentLoaded", function () {
  const googleLoginLink = document.getElementById("login_with_google_link");
  const guestId = localStorage.getItem("guest_id");

  if (guestId && googleLoginLink) {
    const currentHref = new URL(googleLoginLink.getAttribute("href"));
    const state = JSON.stringify({
      guest_id: guestId,
    });

    // Set state without encoding twice
    currentHref.searchParams.set("state", state);
    googleLoginLink.setAttribute("href", currentHref.toString());
  }
});

document
  .querySelector("form")
  .addEventListener("submit", async function (event) {
    event.preventDefault();

    // Clear all previous error messages and styles
    document.querySelectorAll(".text-sm").forEach((errorDiv) => {
      errorDiv.classList.add("hidden");
      errorDiv.textContent = "";
    });
    document.querySelectorAll("input").forEach((input) => {
      input.parentElement.classList.remove("border-red-500");
    });

    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();

    let hasError = false;

    // Validation logic with individual error messages

    if (!/\S+@\S+\.\S+/.test(email)) {
      document.getElementById("emailError").textContent =
        "Please enter a valid email address.";
      document.getElementById("emailError").classList.remove("hidden");
      document
        .getElementById("email")
        .parentElement.classList.add("border-red-500");
      hasError = true;
    }

    if (password.length < 6) {
      document.getElementById("passwordError").textContent =
        "Password must be at least 6 characters.";
      document.getElementById("passwordError").classList.remove("hidden");
      document
        .getElementById("password")
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

    let guest_id = localStorage.getItem("guest_id");

    // Submit form data using the Fetch API
    try {
      const response = await fetch("/login", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          email,
          password,
          guest_id,
        }),
      });

      const result = await response.json();

      // Display the message
      const messageDiv = document.getElementById("message");
      messageDiv.textContent = result.message;
      messageDiv.className =
        result.status === "success"
          ? "text-green-700 text-sm mt-1"
          : "text-red-500 text-sm mt-1";

      // Redirect if signup is successful
      if (result.status === "success") {
        location.href = "/";
      }
    } catch (error) {
      const messageDiv = document.getElementById("message");
      messageDiv.textContent = "Error Login. Please try again later.";
      messageDiv.className = "text-red-500 text-sm mt-1";
    }
  });
