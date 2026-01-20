$(document).ready(function () {
    $('.flatno').on('input', function () {
        // Get the value from the input field
        var flatno = $(this).val();

        // Remove all whitespace and special characters
        flatno = flatno.replace(/\s+/g, '').replace(/[^a-zA-Z0-9]/g, '');
        console.log(flatno);
        // Perform AJAX request
        $.ajax({
            url: 'flats/checkFlatno.php',
            method: 'POST',
            data: { flatno: flatno },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    $('#flatno-error').text(response.message);
                } else {
                    $('#flatno-error').text('');
                }
            },
            error: function (xhr, status, error) {
                console.log('AJAX Error:', status, error);
            }
        });
    });
    $('#bike1').on('input', function() {
        if ($(this).val() !== '') {
            $('input.bike1').removeAttr('disabled');
        } else {
            $('input.bike1').attr('disabled', 'disabled');
        }
    });
    $('#bike2').on('input', function() {
        if ($(this).val() !== '') {
            $('input.bike2').removeAttr('disabled');
        } else {
            $('input.bike2').attr('disabled', 'disabled');
        }
    });
    $('#car').on('input', function() {
        if ($(this).val() !== '') {
            $('input.car').removeAttr('disabled');
        } else {
            $('input.car').attr('disabled', 'disabled');
        }
    });
    $('.vehical_image').on('change',function(){
        var id=$(this).data('image');
        var file = $(this)[0].files[0];
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#'+id).attr('src', e.target.result);
            }
            reader.readAsDataURL(file);    
    })

    $('.removeImg').on('click',function(){
        var imgId = $(this).data('img');
       
        $('#'+imgId).attr('src','../assets/images/default.jpg');
    })

// Initialize form validation for both add and edit forms
$('.addFlatHolderForm, .editFlatHolderForm').validate({
    rules: {
        full_name: {
            required: true, // Full name is required
            letterswithspace: true // Custom method to allow letters and spaces only
        },
        phone: {
            required: true,   // Phone number is required
            digits: true,     // Phone number should contain only digits
            minlength: 10,    // Minimum length of 10 digits
            maxlength: 10     // Maximum length of 10 digits
        },
        flatno: {
            required: true // Flat number is required
        },
        bike1: {
            alphanumeric: true, // Should be alphanumeric
            maxlength: 10 // Maximum length of 10 characters
        },
        bike2: {
            alphanumeric: true, // Should be alphanumeric
            maxlength: 10 // Maximum length of 10 characters
        },
        car: {
            alphanumeric: true, // Should be alphanumeric
            maxlength: 10 // Maximum length of 10 characters
        }
    },
    messages: {
        full_name: {
            required: "Please enter full name.", // Error message for required field
            letterswithspace: "Full name should contain only letters and spaces." // Error message for custom validation
        },
        phone: {
            required: "Please enter phone number.", // Error message for required field
            digits: "Phone number should contain only digits.", // Error message for digits validation
            minlength: "Phone number should be exactly 10 digits long.", // Error message for minimum length
            maxlength: "Phone number should be exactly 10 digits long." // Error message for maximum length
        },
        flatno: {
            required: "Please enter flat number." // Error message for required field
        },
        bike1: {
            alphanumeric: "Bike1 number should be alphanumeric.", // Error message for alphanumeric validation
            maxlength: "Bike1 number should not exceed 10 characters." // Error message for maximum length
        },
        bike2: {
            alphanumeric: "Bike2 number should be alphanumeric.", // Error message for alphanumeric validation
            maxlength: "Bike2 number should not exceed 10 characters." // Error message for maximum length
        },
        car: {
            alphanumeric: "Car number should be alphanumeric.", // Error message for alphanumeric validation
            maxlength: "Car number should not exceed 10 characters." // Error message for maximum length
        }
    },
    submitHandler: function(form) {
        var formData = new FormData(form);

        // Additional data to append
        var bike1_id = $('#bike1').data('id');
        var bike2_id = $('#bike2').data('id');
        var car_id = $('#car').data('id');
        var bike1_type = $('#bike1').data('type');
        var bike2_type = $('#bike2').data('type');
        var car_type = $('#car').data('type');

        // Append additional data to formData
        formData.append('bike1_id', bike1_id);
        formData.append('bike2_id', bike2_id);
        formData.append('car_id', car_id);
        formData.append('bike1_type', bike1_type);
        formData.append('bike2_type', bike2_type);
        formData.append('car_type', car_type);

        $.ajax({
            url: $(form).attr('action'), // Get form action dynamically
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                $('#full_name-error').text(''); // Clear full name error
                $('#phone-error').text(''); // Clear phone number error
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
                        window.location.href = "user.php";
                    });
                } else if (response.status === 'error' && response.errors) {
                    if (response.errors.full_name) {
                        $('#full_name-error').text(response.errors.full_name); // Display full name error
                    }
                    if (response.errors.phone) {
                        $('#phone-error').text(response.errors.phone); // Display phone number error
                    }
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
            error: function(xhr, status, error) {
                console.log(xhr);
                console.log(status);

                Swal.fire({
                    title: "Error!",
                    text: "An error occurred while processing your request.",
                    icon: "error",
                    confirmButtonText: "OK",
                    customClass: {
                        popup: 'swal-bg-black', // Apply black background class
                        title: 'swal-text-white', // Apply white text class for title
                        content: 'swal-text-white' // Apply white text class for content
                    }
                });
            }
        });
        return false; // Prevent form submission
    }
});


    // Custom method for letters with spaces only
    $.validator.addMethod("letterswithspace", function (value, element) {
        return this.optional(element) || /^[a-zA-Z\s]*$/.test(value); // Allow letters and spaces only
    }, "Letters and spaces only, please.");

 


    $('#flat_holder_filter').on('click', function () {
        var search = $('.search').val();
        $.ajax({
            type: 'POST',
            url: 'flats/filter.php', // Adjust URL to your PHP script handling flat holder filter
            data: { search: search },
            dataType: 'json',
            success: function (response) {
                // console.log(response);
                var tableBody = $('#flat_holder_results');
                tableBody.empty();
                if (response.status === "success") {
                    response.data.forEach(function (flatHolder) {
                        var row = `
                        <li>
                            <div class="flat-block">
                                <div class="flat-number">${flatHolder.flatno}</div>
                                <h2>${flatHolder.full_name}</h2>
                                <p>${flatHolder.phone}</p>
                                <a href="tel:+91${flatHolder.number}" class="phone">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3.77762 11.9424C2.8296 10.2893 2.37185 8.93948 2.09584 7.57121C1.68762 5.54758 2.62181 3.57081 4.16938 2.30947C4.82345 1.77638 5.57323 1.95852 5.96 2.6524L6.83318 4.21891C7.52529 5.46057 7.87134 6.08139 7.8027 6.73959C7.73407 7.39779 7.26737 7.93386 6.33397 9.00601L3.77762 11.9424ZM3.77762 11.9424C5.69651 15.2883 8.70784 18.3013 12.0576 20.2224M12.0576 20.2224C13.7107 21.1704 15.0605 21.6282 16.4288 21.9042C18.4524 22.3124 20.4292 21.3782 21.6905 19.8306C22.2236 19.1766 22.0415 18.4268 21.3476 18.04L19.7811 17.1668C18.5394 16.4747 17.9186 16.1287 17.2604 16.1973C16.6022 16.2659 16.0661 16.7326 14.994 17.666L12.0576 20.2224Z" stroke="#fff" stroke-width="1.5" stroke-linejoin="round"/>
                                        <path d="M14 6.83185C15.4232 7.43624 16.5638 8.57677 17.1682 10M14.654 2C18.1912 3.02076 20.9791 5.80852 22 9.34563" stroke="#fff" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                </a>
                                <div class="action">
                                    <a href="form-flat-holder.php?flatno=${flatHolder.flat_number}" class="btn-icon">
                                        <img src="../assets/images/edit-icon.png" alt="Edit">
                                    </a>
                                    <a href="#" data-href="flats/delete-flat-holder.php?flatno=${flatHolder.flat_number}" class="btn-icon delete">
                                        <img src="../assets/images/delete-icon.png" alt="Delete">
                                    </a>
                                </div>
                            </div>
                        </li>
                        `;
                        tableBody.append(row);
                    });
                } else {
                    tableBody.append('<li><div class="text-center"><h2>No results found</h2></div></li>');
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error: ' + status + error);
            }
        });
    });


    $('#bike_filter').on('click', function () {
        var search = $('.search').val();
        $.ajax({
            type: 'POST',
            url: 'flats/bike_filter.php',
            data: { search: search },
            dataType: 'json', // Specify dataType as JSON
            success: function (response) {
                console.log(response);
                var tableBody = $('#bike_results');
                tableBody.empty(); // Clear previous table rows

                if (response.status === "success") {
                    response.data.forEach(function (bike) {
                        var flatNumber = bike.flatno;
                        var fullName = bike.full_name;
                        var phone = bike.phone;
                        var bikes = bike.bikes.split(',');

                        var row = '<li>';
                        row += '<div class="flat-block">';
                        row += '<div class="flat-number">' + flatNumber + '</div>';
                        row += '<h2>' + fullName + '</h2>';
                        row += '<p>' + phone + '</p>';

                        if (bikes[0] && bikes[1]) {
                            row += '<div class="bike"><img src="../assets/images/bike.svg" alt="">' + bikes[0] + '</div><div class="bike"><img src="../assets/images/bike.svg" alt="">' + bikes[1] + '</div>';
                        } else if (bikes[0]) {
                            row += '<div class="bike"><img src="../assets/images/bike.svg" alt="">' + bikes[0] + '</div>';
                        } else if (bikes[1]) {
                            row += '<div class="bike"><img src="../assets/images/bike.svg" alt="">' + bikes[1] + '</div>';
                        }

                        row += '<a href="tel:+91' + phone.replace(/\s/g, '') + '" class="phone"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">';
                        row += '<path d="M3.77762 11.9424C2.8296 10.2893 2.37185 8.93948 2.09584 7.57121C1.68762 5.54758 2.62181 3.57081 4.16938 2.30947C4.82345 1.77638 5.57323 1.95852 5.96 2.6524L6.83318 4.21891C7.52529 5.46057 7.87134 6.08139 7.8027 6.73959C7.73407 7.39779 7.26737 7.93386 6.33397 9.00601L3.77762 11.9424ZM3.77762 11.9424C5.69651 15.2883 8.70784 18.3013 12.0576 20.2224M12.0576 20.2224C13.7107 21.1704 15.0605 21.6282 16.4288 21.9042C18.4524 22.3124 20.4292 21.3782 21.6905 19.8306C22.2236 19.1766 22.0415 18.4268 21.3476 18.04L19.7811 17.1668C18.5394 16.4747 17.9186 16.1287 17.2604 16.1973C16.6022 16.2659 16.0661 16.7326 14.994 17.666L12.0576 20.2224Z" stroke="#fff" stroke-width="1.5" stroke-linejoin="round" />';
                        row += '<path d="M14 6.83185C15.4232 7.43624 16.5638 8.57677 17.1682 10M14.654 2C18.1912 3.02076 20.9791 5.80852 22 9.34563" stroke="#fff" stroke-width="1.5" stroke-linecap="round" />';
                        row += '</svg></a>';
                        row += '</div>';
                        row += '</li>';

                        tableBody.append(row);
                    });
                } else {
                    tableBody.append('<li><div class="text-center"><h2>No results found</h2></div></li>');
                }
            },
            error: function (xhr, status, error) {
                console.log(xhr);
                console.log(status);
                console.log(error);
                console.error('AJAX Error: ' + status + error);
            }
        });
    });

    $('#car_filter').on('click', function () {
        var search = $('.search').val();
        $.ajax({
            type: 'POST',
            url: 'flats/car_filter.php', // Adjust URL to your PHP script handling car filter
            data: { search: search },
            dataType: 'json',
            success: function (response) {
                var tableBody = $('#car_results');
                tableBody.empty();
                if (response.status === "success") {
                    response.data.forEach(function (car) {
                        var row = '<li>' +
                            '<div class="flat-block car-list">' +
                            '<div class="flat-number">' + car.flat_number + '</div>' +
                            '<h2>' + car.full_name + '</h2>' +
                            '<p>' + car.phone + '</p>' +
                            '<div class="car"><img src="../assets/images/car.svg" alt="">' + car.car + '</div>' +
                            '<a href="tel:+91' + car.phone.replace(/\s/g, '') + '" class="phone">' +
                            '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">' +
                            '<path d="M3.77762 11.9424C2.8296 10.2893 2.37185 8.93948 2.09584 7.57121C1.68762 5.54758 2.62181 3.57081 4.16938 2.30947C4.82345 1.77638 5.57323 1.95852 5.96 2.6524L6.83318 4.21891C7.52529 5.46057 7.87134 6.08139 7.8027 6.73959C7.73407 7.39779 7.26737 7.93386 6.33397 9.00601L3.77762 11.9424ZM3.77762 11.9424C5.69651 15.2883 8.70784 18.3013 12.0576 20.2224M12.0576 20.2224C13.7107 21.1704 15.0605 21.6282 16.4288 21.9042C18.4524 22.3124 20.4292 21.3782 21.6905 19.8306C22.2236 19.1766 22.0415 18.4268 21.3476 18.04L19.7811 17.1668C18.5394 16.4747 17.9186 16.1287 17.2604 16.1973C16.6022 16.2659 16.0661 16.7326 14.994 17.666L12.0576 20.2224Z" stroke="#fff" stroke-width="1.5" stroke-linejoin="round"/>' +
                            '<path d="M14 6.83185C15.4232 7.43624 16.5638 8.57677 17.1682 10M14.654 2C18.1912 3.02076 20.9791 5.80852 22 9.34563" stroke="#fff" stroke-width="1.5" stroke-linecap="round"/>' +
                            '</svg></a>' +
                            '</div>' +
                            '</li>';
                        tableBody.append(row);
                    });
                } else {
                    tableBody.append('<li><div class="text-center"><h2>No results found</h2></div></li>');
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error: ' + status + error);
            }
        });
    });

    $(document).on('click', '.delete', function (e) {
        e.preventDefault();
        var href = $(this).data('href');
        console.log(href);

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = href;
            }
        });
    });

    $(document).on('change', '.wing', function() {
        var wing = $(this).val();
        var type = $(this).data('type');
    
        $.ajax({
            url: 'flats/filter_flat_wing.php',
            type: 'POST',
            data: { wing: wing, type: type },
            dataType: 'json',
            success: function(response) {
                if(response.length > 0) {
                    $('.wing-result').empty(); // Clear the current list
                    response.forEach(function(item) {
                        $('.wing-result').append(item); // Append each item to the list
                    });
                } else {
                    $('.wing-result').html('<p>No results found</p>');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ' + status + error);
            }
        });
    });

})