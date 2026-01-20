$(document).ready(function () {
    // image change handling
    $('#image').on('change', function () {
        var input = this;

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.img-thumbnail').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    });
   // Define a custom validation method to check file size
$.validator.addMethod("filesize", function(value, element, param) {
    if (element.files.length > 0) {
        var fileSize = element.files[0].size;
        return this.optional(element) || (fileSize <= param);
    }
    return true;
}, "File must be less than {0} bytes");

$('.adddieseldataForm, .editdieseldataForm').validate({
    rules: {
        title: {
            required: true,
        },
        amount: {
            required: true,
            number: true,
        },
        image: {
            required: function(element) {
                return $(element).closest('form').hasClass('adddieseldataForm');
            },
            extension: "jpg|jpeg|png",
            filesize: 5 * 1024 * 1024 // Maximum file size in bytes (5MB)
        },
    },
    messages: {
        title: {
            required: "Please enter a title.",
        },
        amount: {
            required: "Please enter an amount.",
            number: "Please enter a valid number.",
        },
        image: {
            required: "Please select an image.",
            extension: "Please select an image of type jpg, jpeg, or png.",
            filesize: "File size must be less than 5MB.",
        }
    },
    submitHandler: function(form) {
        var formData = new FormData(form); // Serialize form data
        $.ajax({
            url: $(form).attr('action'), // Get form action dynamically
            type: 'POST',
            data: formData,
            processData: false, // Prevent jQuery from processing the data
            contentType: false, // Prevent jQuery from setting contentType
            dataType: 'json',
            success: function(response) {
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
                        window.location.href = "diesel-list.php";
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
        return false; // Prevent form submission
    }
});
$('.delete').on('click', function(e) {
    e.preventDefault();
    const href = $(this).data('href');

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

 // Date change handling
 $('#diesel_list_filter').on('click', function() {
    var startDate = $('.startDate').val();
    var endDate = $('.endDate').val();

    // Perform AJAX call to filter data based on selected date range
    filterData(startDate, endDate);
});

// AJAX function to fetch and display filtered data
function filterData(startDate, endDate) {
    // Example AJAX call
    $.ajax({
        url: 'diesel/filterDate.php', // Replace with your actual API endpoint
        method: 'POST',
        data: {
            startDate: startDate,
            endDate: endDate
        },
        success: function(response) {
            console.log(response);
            var tableBody = $('#diesel_data_result');
            tableBody.empty(); // Clear existing table rows
            
            if (response.status === "success") {
                
                response.data.forEach(function(row) {
                  
                    
                    var rowHtml = `
                        <li>
                            <div class="diesel-block">
                                <h2>${row.date}</h2>
                                <div class="info"><span>Amount</span>${row.amount} Rs</div>
                                <a data-fancybox='gallery' href="../assets/images/diesel/${row.image}" class="bill-photo">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <mask id="mask0_731_6352" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="24" height="24">
                                            <rect width="24" height="24" fill="#d5a85d"></rect>
                                        </mask>
                                        <g mask="url(#mask0_731_6352)">
                                            <path d="M8.25 11.75V10.25H15.75V11.75H8.25ZM8.25 7.75V6.25H15.75V7.75H8.25ZM6 14.25H13.5C13.9448 14.25 14.3563 14.3462 14.7345 14.5385C15.1128 14.7308 15.434 15.0026 15.698 15.3538L18 18.354V4.30775C18 4.21792 17.9712 4.14417 17.9135 4.0865C17.8558 4.02883 17.7821 4 17.6923 4H6.30775C6.21792 4 6.14417 4.02883 6.0865 4.0865C6.02883 4.14417 6 4.21792 6 4.30775V14.25ZM6.30775 20H17.3673L14.5173 16.2712C14.3916 16.1058 14.2419 15.9775 14.0682 15.8865C13.8946 15.7955 13.7052 15.75 13.5 15.75H6V19.6923C6 19.7821 6.02883 19.8558 6.0865 19.9135C6.14417 19.9712 6.21792 20 6.30775 20ZM17.6923 21.5H6.30775C5.80258 21.5 5.375 21.325 5.025 20.975C4.675 20.625 4.5 20.1974 4.5 19.6923V4.30775C4.5 3.80258 4.675 3.375 5.025 3.025C5.375 2.675 5.80258 2.5 6.30775 2.5H17.6923C18.1974 2.5 18.625 2.675 18.975 3.025C19.325 3.375 19.5 3.80258 19.5 4.30775V19.6923C19.5 20.1974 19.325 20.625 18.975 20.975C18.625 21.325 18.1974 21.5 17.6923 21.5Z" fill="#d5a85d"></path>
                                        </g>
                                    </svg>
                                </a>
                                ${window.isAdmin ? `
                                    <div class="action">
                                        <a href="diesel-form.php?id=${row.id}" class="btn-icon"><img src="../assets/images/edit-icon.png" alt="Edit"></a>
                                        <a href="#" data-href="diesel/delete.php?id=${row.id}" class="btn-icon delete deleteexpenses"><img src="../assets/images/delete-icon.png" alt="Delete"></a>
                                    </div>
                                ` : ''}
                            </div>
                        </li>
                    `;
                    tableBody.append(rowHtml);
                });

                // // Add the total amount row
                // var totalRow = `
                //     <tr>
                //         <td colspan='5' class="right">
                //             Total Diesel Amount: <span>${response.totalAmount} Rs</span>
                //         </td>
                //     </tr>
                // `;
                // tableBody.append(totalRow); // Append total amount row
            } else {
                tableBody.append('<li><div class="text-center"><h2>No results found</h2></div></li>');
            }
        },
        error: function(xhr, status, error) {
            console.log(xhr);
            // Handle error
            console.error('AJAX Error: ' + status + ' - ' + error);
        }
    });
}





});