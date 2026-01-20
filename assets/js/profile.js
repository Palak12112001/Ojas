$(document).ready(function () {
    $('#editImage').on('click', function () {
        $('#image').click();
    });
    
    $('#image').on('change', function () {
        var file = this.files[0];
        var reader = new FileReader();
        reader.onload = function (e) {
            $('.imgPreview').attr('src', e.target.result);
        }
        reader.readAsDataURL(file);
    });

    $('#reset-password-button').on('click', function () {
        $('#passwordDiv').hide();
        $('#resetpasswordform').show();
    });

    $('.toggle-password').click(function () {
        const inputId = $(this).data('id');
        const $passwordInput = $('#' + inputId);
        const type = $passwordInput.attr('type') === 'password' ? 'text' : 'password';
        $passwordInput.attr('type', type);
        $(this).toggleClass('fa-eye-slash fa-eye');
    });

    // Validate Profile Update Form
    $('#updateProfile').validate({
        rules: {
            full_name: {
                required: true,
                minlength: 3
            },
            phone_number: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10
            },
        },
        messages: {
            full_name: {
                required: "Please enter your full name",
                minlength: "Full name must be at least 3 characters long"
            },
            phone_number: {
                required: "Please enter your phone number",
                digits: "Please enter a valid phone number",
                minlength: "Phone number must be 10 digits long",
                maxlength: "Phone number must be 10 digits long"
            },
        },
        errorPlacement: function(error, element) {
            const errorId = element.attr("name") + "Error";
            $("#" + errorId).html(error);
        },
        submitHandler: function (form) {
            var formData = new FormData(form);
            $.ajax({
                url: "committee-members/update.php",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    console.log("Server Response: ", response); 
                    try {
                        response = typeof response === "string" ? JSON.parse(response) : response;
                        if (response.status === "success") {
                            Swal.fire({
                                title: "Success!",
                                text: response.message,
                                icon: "success",
                                confirmButtonText: "OK",
                            }).then(function () {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire("Error!", response.message, "error");
                        }
                    } catch (e) {
                        console.log("Error parsing response: ", e);
                        Swal.fire("Error!", "Failed to update data. Please try again later.", "error");
                    }
                }
            });
        }
    });

    // Validate Reset Password Form
    $('#resetpasswordform').validate({
        rules: {
            current_password: {
                required: true,
                minlength: 6
            },
            new_password: {
                required: true,
                minlength: 6
            },
            confirm_password: {
                required: true,
                equalTo: "#new_password"
            }
        },
        messages: {
            current_password: {
                required: "Please enter your current password",
                minlength: "Password must be at least 6 characters long"
            },
            new_password: {
                required: "Please enter a new password",
                minlength: "Password must be at least 6 characters long"
            },
            confirm_password: {
                required: "Please confirm your new password",
                equalTo: "Passwords do not match"
            }
        },
        submitHandler: function (form) {
            var formData = $(form).serialize();
            $.ajax({
                url: "committee-members/reset-password.php",
                method: "POST",
                data: formData,
                success: function (response) {
                    console.log("Server Response: ", response);
                    try {
                        response = typeof response === "string" ? JSON.parse(response) : response;
                        if (response.status === "success") {
                            Swal.fire({
                                title: "Success!",
                                text: response.message,
                                icon: "success",
                                confirmButtonText: "OK",
                            }).then(function () {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire("Error!", response.message, "error");
                        }
                    } catch (e) {
                        console.log("Error parsing response: ", e);
                        Swal.fire("Error!", "Failed to reset password. Please try again later.", "error");
                    }
                },error:function(xhr){
                    console.log(xhr);
                }
            });
        }
    });
});