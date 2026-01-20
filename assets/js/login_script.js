$(document).ready(function() {
    $("#btn-sign-in").on('click', function(e) {
        e.preventDefault();
        var username = $('#username').val().trim();
        var password = $('#password').val().trim();

        // Clear previous error messages
        $('.error-message').text('');

        // Simple validation checks
        if (username === '') {
            showError('username', 'Username is required.');
            return;
        }
        if (password === '') {
            showError('password', 'Password is required.');
            return;
        }

        // Proceed with AJAX request if all validations pass
        $.ajax({
            url: 'login/login.php',
            method: 'POST',
            data: {
                username: username,
                password: password
            },
            // dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'dashboard.php'; // Redirect to common dashboard
                        }
                    });
                } else {
                    showError('username', response.message); // Show error below username field
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                console.log(status);
                console.log(error);
            }
        });
    });

    function showError(field, message) {
        $('#' + field + 'Error').text(message); // Show error message below the corresponding field
    }

    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function () {
        // Toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        // Toggle the eye / eye-slash icon
        this.classList.toggle('fa-eye-slash');
        this.classList.toggle('fa-eye');
    });
});
