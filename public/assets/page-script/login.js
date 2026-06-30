

document.getElementById('toggle-password').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const passwordIcon = document.getElementById('password-icon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordIcon.classList.remove('fi-rr-eye');
        passwordIcon.classList.add('fi-rr-eye-crossed'); // Update this class if your icon library has an eye-crossed or "hide" icon
    } else {
        passwordInput.type = 'password';
        passwordIcon.classList.remove('fi-rr-eye-crossed');
        passwordIcon.classList.add('fi-rr-eye');
    }
});