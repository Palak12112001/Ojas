
$(document).ready(function() {
      // Initially hide or show charge amount field based on selected value
      $('#chargePaidSelect').on('change', function() {
        if ($(this).val() == 1) {
            $('#chargePaidCol').removeClass('col-12').addClass('col-5');
            $('#chargeAmountField').show();
        } else {
            $('#chargePaidCol').removeClass('col-5').addClass('col-12');
            $('#chargeAmountField').hide();
        }
    });

    // Trigger change event on page load to initialize field visibility
    $('#chargePaidSelect').trigger('change');
    $('#id_image').on('change', function() {
        var input = this;

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('.img-thumbnail').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    });
// Custom method to check file size
$.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param);
}, 'File size must be less than {0}');

// Custom method to check letters with space
$.validator.addMethod('letterswithspace', function (value, element) {
    return this.optional(element) || /^[a-zA-Z\s]+$/i.test(value);
}, 'Please enter only letters and spaces');

$(".addRentFlatForm, .editRentFlatForm").each(function() {
    $(this).validate({
        rules: {
            full_name: {
                required: true,
                minlength: 2,
                letterswithspace: true
            },
            phone: {
                required: true,
                minlength: 10,
                digits: true
            },
            flatno: {
                required: true,
                minlength: 2
            },
            id_number: {
                required: true,
                minlength: 12,
                digits: true
            },
            id_image: {
                required: function(element) {
                    return $(element).closest('form').hasClass('addRentFlatForm');
                },
                extension: "jpg|jpeg|png",
                filesize: 5 * 1024 * 1024 // Maximum file size in bytes (5MB)
            },
            charge_amount: {
                required: function(element) {
                    return $("#chargePaidSelect").val() == '1';
                },
                digits: true
            }
        },
        messages: {
            full_name: {
                required: "Please enter your full name",
                minlength: "Full name must be at least 2 characters long",
                letterswithspace: "Please enter only letters and spaces"
            },
            phone: {
                required: "Please enter your phone number",
                minlength: "Phone number must be at least 10 digits long",
                digits: "Please enter a valid phone number"
            },
            flatno: {
                required: "Please enter flat number",
                minlength: "Flat number must be at least 2 characters long"
            },
            id_number: {
                required: "Please enter your Aadhar card number",
                minlength: "Aadhar card number must be 12 digits long",
                digits: "Please enter a valid Aadhar card number"
            },
            id_image: {
                required: "Please upload an image of your Aadhar card",
                extension: "Please upload only JPG, JPEG, or PNG images",
                filesize: "File size must be less than 5 MB"
            },
            charge_amount: {
                required: "Please enter charge amount",
                digits: "Charge amount must be a valid number"
            }
        },
        // Form submission handler
        submitHandler: function(form) {
            var formData = new FormData(form);
            $.ajax({
                url: $(form).attr('action'),
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType:'json',
                success: function(response) {
                    console.log(response);
                    if (response.status === "success") {
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
                            window.location.href = "rent-flats.php";
                        });
                    } else if (response.status === 'error' && response.errors) {
                        // Clear previous errors
                        $(".text-danger").text("");
            
                        // Display errors next to respective fields
                        if (response.errors.full_name) {
                            $('#full_name-error').text(response.errors.full_name);
                        }
                        if (response.errors.phone) {
                            $('#phone-error').text(response.errors.phone);
                        }
                        // Handle other fields similarly
            
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
                    console.log(error);
                    Swal.fire({
                        title: "Error!",
                        text: "Failed to update data. Please try again later.",
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
            return false; // Prevent form from submitting via normal means
        }
    });
});

// delete rent flat
$('.delete-rent-flat').on('click', function() {
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
   // check flat is boked or not
   $('#flatnoInput').on('input', function() {
    var flatno = $(this).val();
     // Remove all whitespace and special characters
     flatno = flatno.replace(/\s+/g, '').replace(/[^a-zA-Z0-9]/g, '');
     console.log(flatno);
    $.ajax({
        url: 'rent-flats/check-flats.php',
        method: 'POST',
        data: { flatno: flatno },
        dataType: 'json',
        success: function(response) {
            
            if (response.status === 'success') {
                $('#flatno-error').text(response.message);
            } else {
                $('#flatno-error').text('');
                
            }
        },
        error: function(xhr, status, error) {
            console.log('AJAX Error:', status, error);
        }
    });
});

$('#rent_flat_filter').on('click', function() {
    var search = $('.search').val();
    $.ajax({
        type: 'POST',
        url: 'rent-flats/filter.php', // Adjust URL to your PHP script handling flat holder filter
        data: {search: search},
        dataType: 'json',
        success: function(response) {
            console.log(response);
            var tableBody = $('#rent_flats_results');
            tableBody.empty();
            if (response.status === "success") {
                response.data.forEach(function(rentflat, index) {
                    var row = '<li>' +
                        '<div class="rent-flat-block">' +
                        '<div class="flat-number">' + rentflat.flatno + '</div>' +
                        '<h2>' + rentflat.full_name + '</h2>' +
                        '<p>' + rentflat.phone + '</p>' +
                        '<div class="d-flex">' +
                        '<div class="info"><span>Amount</span>' + (rentflat.amount !== '' ? rentflat.amount : '00') + '.Rs</div>' +
                        '<div class="info"><span>Aadhar Card</span>' + rentflat.id_number + '</div>' +
                        '</div>' +
                        '<a href="tel:+91' + rentflat.phone.replace(/\s/g, '') + '" class="phone">' +
                        '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">' +
                        '<path d="M3.77762 11.9424C2.8296 10.2893 2.37185 8.93948 2.09584 7.57121C1.68762 5.54758 2.62181 3.57081 4.16938 2.30947C4.82345 1.77638 5.57323 1.95852 5.96 2.6524L6.83318 4.21891C7.52529 5.46057 7.87134 6.08139 7.8027 6.73959C7.73407 7.39779 7.26737 7.93386 6.33397 9.00601L3.77762 11.9424ZM3.77762 11.9424C5.69651 15.2883 8.70784 18.3013 12.0576 20.2224M12.0576 20.2224C13.7107 21.1704 15.0605 21.6282 16.4288 21.9042C18.4524 22.3124 20.4292 21.3782 21.6905 19.8306C22.2236 19.1766 22.0415 18.4268 21.3476 18.04L19.7811 17.1668C18.5394 16.4747 17.9186 16.1287 17.2604 16.1973C16.6022 16.2659 16.0661 16.7326 14.994 17.666L12.0576 20.2224Z" stroke="#fff" stroke-width="1.5" stroke-linejoin="round"/>' +
                        '<path d="M14 6.83185C15.4232 7.43624 16.5638 8.57677 17.1682 10M14.654 2C18.1912 3.02076 20.9791 5.80852 22 9.34563" stroke="#fff" stroke-width="1.5" stroke-linecap="round"/>' +
                        '</svg></a>' +
                        '<div class="action">' +
                        '<a class="btn-icon" href="form-rent-flat.php?flatno=' + rentflat.flat_number + '"><img src="../assets/images/edit-icon.png" alt=""></a>' +
                        '<a class="btn-icon" ="#" data-href="rent-flats/delete-rent-flat.php?flatno=' + rentflat.flat_number + '" class="delete-rent-flat"><img src="../assets/images/delete-icon.png" alt=""></a>' +
                        '</div>' +
                        '</li>';
                    tableBody.append(row);
                });
            } else {
                tableBody.append('<li><div class="text-center"><h2>No results found</h2></div></li>');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error: ' + status + error);
        }
    });
});

});

