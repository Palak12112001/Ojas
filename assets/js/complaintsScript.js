$(function() {

    // Function to handle form submission
    $('#submit').on('click', function(e) {
        e.preventDefault();
        var formData = new FormData($('#complaintForm')[0]);
        $.ajax({
            url: 'admin/complaints/insert.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        confirmButtonText: "OK",
                        customClass: {
                            popup: 'swal-bg-black',
                            title: 'swal-text-white',
                            content: 'swal-text-white'
                        }
                    }).then(function () {
                        window.location.href = "index.php";
                    });
                } else {
                    Swal.fire({
                        title: "Error!",
                        text: response.message,
                        icon: "error",
                        confirmButtonText: "OK",
                        customClass: {
                            popup: 'swal-bg-black',
                            title: 'swal-text-white',
                            content: 'swal-text-white'
                        }
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Error:', textStatus, errorThrown);
            }
        });
    });

    // Function to handle filtering complaints
    $('#Complaints_filter').on('click', function() {
        var filter = $('.search').val();
        var url = 'complaints/filter.php';
        $.ajax({
            url: url,
            type: 'POST',
            data: {filter: filter},
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#complaintResult').empty();
              
                    response.data.forEach(function(complaint) {
                        appendComplaintRow(complaint);
                    });
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ' + status + error);
            }
        });
    });
// filter by wing name 
$(document).on('change', '.wing', function () {
    var wing = $(this).val();
    var url = 'complaints/wing_filter.php';
    $.ajax({
        url: url,
        type: 'POST',
        data: {wing: wing},
        dataType: 'json',
        success: function(response) {
            $('#complaintResult').empty();
            if (response.status === 'success') {
                response.data.forEach(function(complaint) {
                    appendComplaintRow(complaint);
                });
            } else {
                var rowHtml = '<tr><td colspan="5" class="text-center"><h2>No Record Found</h2></td></tr>';
                $('#complaintResult').append(rowHtml);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error: ' + status + error);
        }
    });
});
$('.filter-role').on('click', function() {
    var status = $(this).data('role');
    var url = 'complaints/filter_status.php';
    $.ajax({
        url: url,
        type: 'POST',
        data: {status: status},
        dataType: 'json',
        success: function(response) {
            $('#complaintResult').empty();
            if (response.status === 'success') {
                response.data.forEach(function(complaint) {
                    appendComplaintRow(complaint);
                });
            } else {
                var rowHtml = '<tr><td colspan="5" class="text-center"><h2>No Record Found</h2></td></tr>';
                $('#complaintResult').append(rowHtml);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error: ' + status + error);
        }
    });

});
    // Function to append a single complaint row
    function appendComplaintRow(complaint) {
        var wingName = ucfirst(complaint.wing_name);
        var details = ucfirst(complaint.detail);
        var image = complaint.image;
        var status = complaint.status;
        var statusClass = (status === 'Pending') ? 'badge-warning' : 'badge-success';
        var count = complaint.count;
        var rowHtml = '<tr>' +
            '<td>' + count + '</td>' +
            '<td>' + wingName + '</td>' +
            '<td>' + details + '</td>' +
            '<td>' +
                '<a data-fancybox="gallery" href="' + image + '" class="bill-photo">' +
                    '<img src="' + image + '" alt="" style="width:100px;height:100px;">' +
                '</a>' +
            '</td>' +
            '<td><span class="badge ' + statusClass + ' fs-6">' + status + '</span></td>' +
        '</tr>';
        
        $('#complaintResult').append(rowHtml);
    }

    // Function to capitalize the first letter of a string
    function ucfirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
    
});
