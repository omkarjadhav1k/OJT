document.addEventListener('DOMContentLoaded', function () {
    var loginForm = document.getElementById('loginForm');
    var emailInput = document.getElementById('user_email');
    var passwordInput = document.getElementById('user_password');
    var togglePasswordBtn = document.getElementById('togglePassword');
    var forgotPasswordLink = document.getElementById('forgotPasswordLink');
    var clientAlert = document.getElementById('clientAlert');

    if (togglePasswordBtn && passwordInput) {
        togglePasswordBtn.addEventListener('click', function () {
            var isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            togglePasswordBtn.classList.toggle('is-visible', isPassword);
            togglePasswordBtn.setAttribute(
                'aria-label',
                isPassword ? 'Hide password' : 'Show password'
            );
        });
    }

    function showAlert(message, type) {
        if (!clientAlert) {
            return;
        }
        clientAlert.textContent = message;
        clientAlert.className = 'alert-box alert-' + type;
    }

    function clearFieldErrors() {
        var groups = document.querySelectorAll('.input-group.has-error');
        for (var i = 0; i < groups.length; i++) {
            groups[i].classList.remove('has-error');
        }
    }

    if (emailInput) {
        emailInput.addEventListener('input', function () {
            emailInput.closest('.input-group').classList.remove('has-error');
        });
    }

    if (passwordInput) {
        passwordInput.addEventListener('input', function () {
            passwordInput.closest('.input-group').classList.remove('has-error');
        });
    }

    if (loginForm) {
        loginForm.addEventListener('submit', function (event) {
            clearFieldErrors();

            var email = emailInput ? emailInput.value.trim() : '';
            var password = passwordInput ? passwordInput.value : '';
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email === '') {
                event.preventDefault();
                emailInput.closest('.input-group').classList.add('has-error');
                showAlert('Please enter your email address.', 'error');
                emailInput.focus();
                return;
            }

            if (!emailPattern.test(email)) {
                event.preventDefault();
                emailInput.closest('.input-group').classList.add('has-error');
                showAlert('Please enter a valid email address.', 'error');
                emailInput.focus();
                return;
            }

            if (password === '') {
                event.preventDefault();
                passwordInput.closest('.input-group').classList.add('has-error');
                showAlert('Please enter your password.', 'error');
                passwordInput.focus();
            }
        });
    }

    if (forgotPasswordLink) {
        forgotPasswordLink.addEventListener('click', function (event) {
            event.preventDefault();
            showAlert('Please contact your bakery administrator to reset your password.', 'info');
        });
    }
});
