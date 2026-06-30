document.querySelector('form').addEventListener('submit', async function(event) {
    event.preventDefault();

    // Clear all previous error messages and styles
    document.querySelectorAll('.text-sm').forEach(errorDiv => {
        errorDiv.classList.add('hidden');
        errorDiv.textContent = "";
    });
    document.querySelectorAll('input').forEach(input => {
        input.parentElement.classList.remove('border-red-500');
    });

    const email = document.getElementById('email').value.trim();

    let hasError = false;

    // Validation logic with individual error messages
    if (!/\S+@\S+\.\S+/.test(email)) {
        document.getElementById('emailError').textContent = "Please enter a valid email address.";
        document.getElementById('emailError').classList.remove('hidden');
        document.getElementById('email').parentElement.classList.add('border-red-500');
        hasError = true;
    }

    if (hasError) {
        return;
    }

    // Remove error styles if all fields are correct
    document.querySelectorAll('input').forEach(input => {
        input.parentElement.classList.remove('border-red-500');
        input.parentElement.classList.add('border-gray-300');
    });

    // Submit form data using the Fetch API
    try {
        const response = await fetch('/resetPassword', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                email
            }),
        });

        const result = await response.json();

        // Display the message
        const messageDiv = document.getElementById('message');
        messageDiv.textContent = result.message;
        messageDiv.className = result.status === 'success' ? "text-green-700 text-sm mt-2 mb-6" : "text-red-500 text-sm mt-2 mb-6";

    } catch (error) {
        const messageDiv = document.getElementById('message');
        messageDiv.textContent = "Error Resting Password. Please try again later.";
        messageDiv.className = "text-red-500 text-sm mt-2 mb-6";
    }
});