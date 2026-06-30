function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('togglePasswordIcon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.replace('fi-tr-eye-alert', 'fi-tr-low-vision'); // Switch to 'eye-crossed' icon
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.replace('fi-tr-low-vision', 'fi-tr-eye-alert'); // Switch back to 'eye' icon
    }
}

function toggleConfirmPasswordVisibility() {
    const passwordInput = document.getElementById('confirmPassword');
    const toggleIcon = document.getElementById('toggleconfirmPasswordIcon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.replace('fi-tr-eye-alert', 'fi-tr-low-vision'); // Switch to 'eye-crossed' icon
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.replace('fi-tr-low-vision', 'fi-tr-eye-alert'); // Switch back to 'eye' icon
    }
}