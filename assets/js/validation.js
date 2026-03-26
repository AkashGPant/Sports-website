document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const inputs = form.querySelectorAll('input, textarea');

            // Clear previous errors
            clearErrors(form);

            inputs.forEach(input => {
                if (input.hasAttribute('required') && !input.value.trim()) {
                    showError(input, 'This field is required');
                    isValid = false;
                } else if (input.type === 'email' && input.value.trim()) {
                    if (!validateEmail(input.value)) {
                        showError(input, 'Please enter a valid email address');
                        isValid = false;
                    }
                } else if (input.id === 'password' && input.value.length > 0 && input.value.length < 6) {
                    showError(input, 'Password must be at least 6 characters');
                    isValid = false;
                } else if (input.id === 'confirm_password') {
                    const password = form.querySelector('#password');
                    if (password && input.value !== password.value) {
                        showError(input, 'Passwords do not match');
                        isValid = false;
                    }
                }
            });

            if (!isValid) {
                e.preventDefault();
            }
        });
    });

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email.toLowerCase());
    }

    function showError(input, message) {
        input.classList.add('input-error');
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.innerText = message;
        errorDiv.style.color = '#e74c3c';
        errorDiv.style.fontSize = '0.8rem';
        errorDiv.style.marginTop = '0.3rem';
        input.parentElement.appendChild(errorDiv);
    }

    function clearErrors(form) {
        const errors = form.querySelectorAll('.error-message');
        const inputs = form.querySelectorAll('.input-error');
        errors.forEach(err => err.remove());
        inputs.forEach(input => input.classList.remove('input-error'));
    }
});
