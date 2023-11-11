    function togglePasswordVisibility() {
    var passwordInput = document.getElementById('password');
    var toggleIcon = document.querySelector('.toggle-password');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.textContent = '🙈'; // Change to 'Hide' icon
    } else {
        passwordInput.type = 'password';
        toggleIcon.textContent = '👁'; // Change to 'Show' icon
    }
}


    function togglePasswordVerifyVisibility() {
    var passwordInput = document.getElementById('verify_password');
    var toggleIcon = document.querySelector('.toggle-password');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.textContent = '🙈'; // Change to 'Hide' icon
    } else {
        passwordInput.type = 'password';
        toggleIcon.textContent = '👁'; // Change to 'Show' icon
    }
}
