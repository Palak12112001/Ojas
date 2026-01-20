$(document).ready(function () {
    // Remove row functionality
    $(document).on('click', '.removeRow', function (e) {
        e.preventDefault();  // Prevent default link behavior
        $(this).closest('.service-form').remove();
    });
    var rowIndex = 1;

    // Function to initialize validation for a form
    function initializeValidation(formId) {
        $(formId).validate({
            rules: {
                "service": {
                    required: true
                },
                "name": {
                    required: true
                },
                "phone": {
                    required: true,
                    minlength: 10,
                    maxlength: 10
                }
            },
            messages: {
                "service": {
                    required: "Service is required"
                },
                "name": {
                    required: "Helper Name is required"
                },
                "phone": {
                    required: "Phone is required",
                    minlength: "Invalid phone number",
                    maxlength: "Invalid phone number"
                }
            }
        })
    }

    // Initialize validation for existing forms
    $(".service-form").each(function () {
        initializeValidation($(this));
    });

    // Add new form row
    $(".addmore").on('click', function () {
        var newRow = `
        <form method="POST" id="ServiceForm_${rowIndex}" class="service-form">
            
                <div class="row justify-content-center">
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label class="inputlabel">Service</label>
                            <input type="text" class="form-control" placeholder="Service" name="service" required>
                            <div data-error="service" class="error-message"></div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label class="inputlabel">Helper Name</label>
                            <input type="text" class="form-control" placeholder="Helper Name" name="name" required>
                            <div data-error="name" class="error-message"></div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label class="inputlabel">Phone</label>
                            <input type="text" class="form-control" placeholder="Phone Number" name="phone" maxlength="10" oninput="this.value = this.value.replace(/\D/g, '');">
                            <div data-error="phone" class="error-message"></div>
                        </div>
                    </div>
                    <div class="col-lg-3 d-flex align-items-center">
                        <div class="mb-3">
                            <a href="#" class="btn btn-icon removeRow"><img src="../assets/images/delete-icon.png" alt="Delete"></a>
                        </div>
                    </div>
                </div>
            
        </form>`;
        $("#DefaultServiceForm").before(newRow);
        initializeValidation($("#ServiceForm_" + rowIndex)); // Initialize validation for the new form
        rowIndex++;
    });



    // Collect and submit data
    $("#submitAllForms").on('click', function () {
        var allValid = true;
        var formsData = [];

        // Validate each form
        $(".service-form").each(function () {
            if (!$(this).valid()) {
                allValid = false;
            } else {
                var formData = $(this).serializeArray();
                formsData.push(formData);
            }
        });

        if (allValid) {
            // All forms are valid, proceed with submission
            $.ajax({
                url: 'helper/insert.php',
                type: 'POST',
                data: { forms: formsData },
                dataType: 'json',
                success: function (response) {

                    if (response.status === 'success') {
                        Swal.fire({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            confirmButtonText: "OK",
                            customClass: {
                                popup: 'swal-bg-black', // Apply black background class
                                title: 'swal-text-white', // Apply white text class for title
                                content: 'swal-text-white' // Apply white text class for content
                            }
                        }).then(function () {
                            // Redirect to another page after success
                            window.location.href = "dashboard.php";
                        });
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: response.message,
                            icon: "error",
                            confirmButtonText: "OK",
                            customClass: {
                                popup: 'swal-bg-black', // Apply black background class
                                title: 'swal-text-white', // Apply white text class for title
                                content: 'swal-text-white' // Apply white text class for content
                            }
                        });
                    }

                }
            });
        }
    });
    $(document).on('click', '.btn-delete', function () {
        var id = $(this).data('id');
        $.ajax({
            type: "POST",
            url: 'helper/delete.php',
            data: { id: id },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    $('#ServiceForm_' + id).fadeOut();
                } else {
                    Swal.fire({
                        title: "Error!",
                        text: response.message,
                        icon: "error",
                        confirmButtonText: "OK",
                        customClass: {
                            popup: 'swal-bg-black', // Apply black background class
                            title: 'swal-text-white', // Apply white text class for title
                            content: 'swal-text-white' // Apply white text class for content
                        }
                    });
                }
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    title: "Error!",
                    text: "An error occurred while submitting the data.",
                    icon: "error",
                    confirmButtonText: "OK",
                    customClass: {
                        popup: 'swal-bg-black', // Apply black background class
                        title: 'swal-text-white', // Apply white text class for title
                        content: 'swal-text-white' // Apply white text class for content
                    }
                });
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        });
    });
});


