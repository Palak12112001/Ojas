$(document).ready(function () { 
    $('#id_image').on('change', function() {
        var input = this;

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('.img-block .img-thumbnail').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    });
    $('#flat_number').select2();
    $('.flat_number').on('change', function() {
        var flat_number = $(this).val();
        $.ajax({
            type: 'post',
            url: 'clubhouse/get-flat-details.php',
            data: { flat_number: flat_number },
            dataType: 'json',
            success: function(data) {
                if (data.status =='success') {
                    var details = data.data;
                    
    
                    // Assuming details is an array with one element (if multiple are returned, adjust accordingly)
                    if (details.length > 0) {
                        var fullName = details[0].full_name;
                        var phone = details[0].phone;
                        
                        // Set values in respective input fields
                        $('.full_name').val(fullName);
                        $('.phone').val(phone);
                    } else {
                        console.log('No data found');
                    }
                } else {
                    console.log('Error fetching data');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ' + status + ' - ' + error);
            }
        });
    });
 
    function checkAvailability() {
        var unit = $('.units').val();
        var booking_date = $('.booking_date').val();

        if (unit && booking_date) {
            $.ajax({
                url: 'clubhouse/checkdate.php', // Replace with the actual path to your PHP script
                method: 'POST',
                data: {
                    unit: unit,
                    booking_date: booking_date
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'error') {
                        $('#booking_date-error').text(response.message);
                       
                    } else {
                        $('#booking_date-error').text('');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        }
    }

    $('.booking_date, .units').on('change', function() {
        checkAvailability();
    });



    
    // Validate the form
    $(".addBookedClubHouseForm, .editBookedClubHouseForm").validate({
        rules: {
            flat_number: {
                required: true
            },
            units: {
                required: true
            },
            booking_date: {
                required: true
            },
            price: {
                required: true,
                digits: true
            },
            card_number: {
                required: true,
                digits: true,
                minlength: 12,
                maxlength: 12
            },
            committee_member: {
                required: true
            }
        },
        messages: {
            flat_number: {
                required: "Please select a flat number."
            },
            units: {
                required: "Please select units."
            },
            booking_date: {
                required: "Please select booking date."
            },
            price: {
                required: "Please enter price.",
                digits: "Please enter only digits."
            },
            card_number: {
                required: "Please enter Aadhar card number.",
                digits: "Please enter only digits.",
                minlength: "Aadhar card number must be exactly 12 digits.",
                maxlength: "Aadhar card number must be exactly 12 digits."
            },
            committee_member: {
                required: "Please select a committee member."
            }
        },
        // Submit form via AJAX
       submitHandler: function(form) {
    // Check if all error spans are empty
    var allErrorsEmpty = true;
    $('span.text-danger[id$="-error"]').each(function() {
        if ($(this).text().trim() !== '') {
            allErrorsEmpty = false;
            return false; // Exit the loop
        }
    });

    if (allErrorsEmpty) {
        // Proceed with AJAX request
        var formData = new FormData(form);
        //  Log formData to console
        for (var pair of formData.entries()) {
            console.log(pair[0] + ', ' + pair[1]);
        }
        $.ajax({
            url: $(form).attr('action'),
            type: $(form).attr('method'),
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                console.log(response); // Log response for debugging
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
                        window.location.href = "club-house-booking.php";
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
            },
            error: function(xhr, status, error) {
                console.log(xhr);
                console.log(status);
                console.log(error);
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
    } else {
        // Display error message if any error span is not empty
        Swal.fire({
            title: "Validation Error!",
            text: "Please correct the highlighted errors before submitting the form.",
            icon: "warning",
            confirmButtonText: "OK",
            customClass: {
                popup: 'swal-bg-black', // Apply black background class
                title: 'swal-text-white', // Apply white text class for title
                content: 'swal-text-white' // Apply white text class for content
            }
        });
    }
    
    return false; // Prevent default form submit
}
});
    // delete rent flat
$('.delete-clubhouse-booking').on('click', function() {
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

// filter table status wise
$('.filter-role').on('click', function() {
    var status = $(this).data('role');
    $.ajax({
        type: 'POST',
        url: 'clubhouse/filter-role.php',
        data: { status: status },
        dataType: 'json',
        success: function(response) {
            console.log(response);
            var tableBody = $('#club_house_book');
            tableBody.empty(); // Clear previous table rows

            if (response.status === "success") {
                response.response.forEach(function(clubhouse) {
                    var row = `
                        <li>
                            <div class="booking-area">
                                <div class="flat-number">${clubhouse.flat_number}</div>
                                <h2>${clubhouse.full_name}</h2>
                                <p>${clubhouse.phone}</p>
                                <div class="d-flex">
                                    <div class="info"><span>Unit</span>${clubhouse.unit}</div>
                                    <div class="info"><span>Price</span>${clubhouse.price} + ${clubhouse.total_units_price} = ${clubhouse.total_price} Rs</div>
                                    <div class="info-full"><span>Date</span>${clubhouse.booking_date}</div>
                                    <div class="info-full"><span>Committee Member</span>${clubhouse.committee_member}</div>
                                </div>
                                <span class="badge ${clubhouse.bg_color}">${clubhouse.role}</span>
                                <a href="tel:+91${clubhouse.phone}" class="phone">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3.77762 11.9424C2.8296 10.2893 2.37185 8.93948 2.09584 7.57121C1.68762 5.54758 2.62181 3.57081 4.16938 2.30947C4.82345 1.77638 5.57323 1.95852 5.96 2.6524L6.83318 4.21891C7.52529 5.46057 7.87134 6.08139 7.8027 6.73959C7.73407 7.39779 7.26737 7.93386 6.33397 9.00601L3.77762 11.9424ZM3.77762 11.9424C5.69651 15.2883 8.70784 18.3013 12.0576 20.2224M12.0576 20.2224C13.7107 21.1704 15.0605 21.6282 16.4288 21.9042C18.4524 22.3124 20.4292 21.3782 21.6905 19.8306C22.2236 19.1766 22.0415 18.4268 21.3476 18.04L19.7811 17.1668C18.5394 16.4747 17.9186 16.1287 17.2604 16.1973C16.6022 16.2659 16.0661 16.7326 14.994 17.666L12.0576 20.2224Z" stroke="#fff" stroke-width="1.5" stroke-linejoin="round"/>
                                        <path d="M14 6.83185C15.4232 7.43624 16.5638 8.57677 17.1682 10M14.654 2C18.1912 3.02076 20.9791 5.80852 22 9.34563" stroke="#fff" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                </a>
                                ${ window.isAdmin  ? `
                                    <div class="action">
                                        <a href="#" class="btn-icon"><img src="../assets/images/edit-icon.png" alt="Edit"></a>
                                        <a href="#" class="btn-icon delete"><img src="../assets/images/delete-icon.png" alt="Delete"></a>
                                    </div>
                                ` : ''}
                            </div>
                        </li>
                    `;
                    tableBody.append(row);
                });
            } else {
                tableBody.append('<li><div class="text-center"><h2>No results found</h2></div></li>');
            }
        },
        error: function(xhr, status, error) {
            console.log(xhr);
            console.log(status);
            console.log(error);
            console.error('AJAX Error: ' + status + ' ' + error);
        }
    });
});


// table search
$('#clubhouse_booking_filter').on('click',function(){
    var search = $('#search').val();
    $.ajax({
        type: 'POST',
        url: 'clubhouse/search.php',
        data: {search: search},
        success: function(response) {
            console.log(response);
            var tableBody = $('#club_house_book');
            tableBody.empty(); // Clear previous table rows

            if (response.status === "success") {
                response.response.forEach(function(clubhouse) {
                    var row = `
                        <li>
                            <div class="booking-area">
                                <div class="flat-number">${clubhouse.flat_number}</div>
                                <h2>${clubhouse.full_name}</h2>
                                <p>${clubhouse.phone}</p>
                                <div class="d-flex">
                                    <div class="info"><span>Unit</span>${clubhouse.unit}</div>
                                    <div class="info"><span>Price</span>${clubhouse.price} + ${clubhouse.total_units_price} = ${clubhouse.total_price} Rs</div>
                                    <div class="info-full"><span>Date</span>${clubhouse.booking_date}</div>
                                    <div class="info-full"><span>Committee Member</span>${clubhouse.committee_member}</div>
                                </div>
                                <span class="badge ${clubhouse.bg_color}">${clubhouse.role}</span>
                                <a href="tel:+91${clubhouse.phone}" class="phone">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3.77762 11.9424C2.8296 10.2893 2.37185 8.93948 2.09584 7.57121C1.68762 5.54758 2.62181 3.57081 4.16938 2.30947C4.82345 1.77638 5.57323 1.95852 5.96 2.6524L6.83318 4.21891C7.52529 5.46057 7.87134 6.08139 7.8027 6.73959C7.73407 7.39779 7.26737 7.93386 6.33397 9.00601L3.77762 11.9424ZM3.77762 11.9424C5.69651 15.2883 8.70784 18.3013 12.0576 20.2224M12.0576 20.2224C13.7107 21.1704 15.0605 21.6282 16.4288 21.9042C18.4524 22.3124 20.4292 21.3782 21.6905 19.8306C22.2236 19.1766 22.0415 18.4268 21.3476 18.04L19.7811 17.1668C18.5394 16.4747 17.9186 16.1287 17.2604 16.1973C16.6022 16.2659 16.0661 16.7326 14.994 17.666L12.0576 20.2224Z" stroke="#fff" stroke-width="1.5" stroke-linejoin="round"/>
                                        <path d="M14 6.83185C15.4232 7.43624 16.5638 8.57677 17.1682 10M14.654 2C18.1912 3.02076 20.9791 5.80852 22 9.34563" stroke="#fff" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                </a>
                                ${ window.isAdmin  ? `
                                    <div class="action">
                                        <a href="#" class="btn-icon"><img src="../assets/images/edit-icon.png" alt="Edit"></a>
                                        <a href="#" class="btn-icon delete"><img src="../assets/images/delete-icon.png" alt="Delete"></a>
                                    </div>
                                ` : ''}
                            </div>
                        </li>
                    `;
                    tableBody.append(row);
                });
            } else {
                tableBody.append('<li><div class="text-center"><h2>No results found</h2></div></li>');
            }
        },
        error: function(xhr, status, error) {
            console.log(xhr);
            console.log(status);
            console.log(error);
            console.error('AJAX Error: ' + status + error);
        }
});
})
})