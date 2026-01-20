$(document).ready(function () {
  $('#commitee_image').on('change', function() {
    var input = this;

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('.img-thumbnail').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
});
  

  $(document).ready(function () {
    // Initialize form validation
    $("#addCommittee").validate({
      rules: {
        full_name: {
          required: true,
        },
        phone_number: {
          required: true,
        },
        wing: {
          required: true,
        },
        user_name: {
          required: true,
        },
        password: {
          required: true,
        },
        image: {
          required: true,
        },
      },
      messages: {
        full_name: {
          required: "Please enter full name",
        },
        phone_number: {
          required: "Please enter phone number",
        },
        wing: {
          required: "Please select a wing",
        },
        user_name: {
          required: "Please enter a user name",
        },
        password: {
          required: "Please enter a password",
        },
        image: {
          required: "Please upload an image",
        },
      },
      errorPlacement: function (error, element) {
        // Display error messages in the div with id '[field_name]_error'
        error.appendTo($("#" + element.attr("name") + "_error"));
      },
    });

    // Handle form submission with AJAX
    $("#addCommittee").on("submit", function (e) {
      e.preventDefault();

      // Check if form is valid
      if ($("#addCommittee").valid()) {
        var formData = new FormData(this);

        // AJAX request
        $.ajax({
          url: "committee-members/insert.php",
          method: "POST",
          data: formData,
          dataType: "json",
          contentType: false,
          processData: false,
          success: function (response) {
            if (response.status == "success") {
              // Show success message using SweetAlert
              Swal.fire({
                title: "Success!",
                text: response.message,
                icon: "success",
                confirmButtonText: "OK",
              }).then(function () {
                // Redirect to committee members page
                window.location.href = "committee-members.php";
              });
            } else {
              console.log(response); // Log response for debugging
            }
          },
          error: function (xhr, status, error) {
            console.error(xhr.responseText);
            // Handle error response
            Swal.fire({
              title: "Error!",
              text: "There was an error processing your request.",
              icon: "error",
              confirmButtonText: "OK",
            });
          },
        });
      }
    });
  });

  $(document).ready(function () {
    // Initialize form validation
    $("#editCommittee").validate({
      rules: {
        full_name: {
          required: true,
        },
        phone_number: {
          required: true,
        },
        wing: {
          required: true,
        },
        user_name: {
          required: true,
        },
      
        // image: {
        //   required: function (element) {
        //     return !$("#existing_image").val(); // Conditionally require image
        //   },
        // },
      },
      messages: {
        full_name: {
          required: "Please enter full name",
        },
        phone_number: {
          required: "Please enter phone number",
        },
        wing: {
          required: "Please select a wing",
        },
        user_name: {
          required: "Please enter a user name",
        },
       
        // image: {
        //   required: "Please upload an image",
        // },
      },
      errorPlacement: function (error, element) {
        // Display error messages in the div with id '[field_name]_error'
        error.appendTo($("#" + element.attr("name") + "_error"));
      },
      submitHandler: function (form) {
        // Handle form submission after validation success
        var formData = new FormData(form);
  
        $.ajax({
          url: "committee-members/update.php",
          method: "POST",
          data: formData,
          contentType: false,
          processData: false,
          success: function (response) {
            console.log("Server Response: ", response); // Debugging server response
            try {
              response = typeof response === "string" ? JSON.parse(response) : response;
              if (response.status === "success") {
                // Show success message using SweetAlert
                Swal.fire({
                  title: "Success!",
                  text: response.message,
                  icon: "success",
                  confirmButtonText: "OK",
                }).then(function () {
                  // Redirect to another page after success
                  window.location.href = "committee-members.php";
                });
              } else {
                // Show error message using SweetAlert
                Swal.fire("Error!", response.message, "error");
              }
            } catch (e) {
              console.log("Error parsing response: ", e);
              // Show generic error message using SweetAlert
              Swal.fire(
                "Error!",
                "Failed to update data. Please try again later.",
                "error"
              );
            }
          },
          error: function (xhr, status, error) {
            console.error("AJAX Error: ", xhr.responseText);
            // Show generic error message using SweetAlert
            Swal.fire(
              "Error!",
              "Failed to update data. Please try again later.",
              "error"
            );
          },
        });
      },
    });
  });
  
  // Delete committee member
$(document).on("click", ".deleteCommittee", function (e) {
  e.preventDefault();
  var memberId = $(this).data("member-id");
  swal({
    title: "Are you sure?",
    text: "Once deleted, you will not be able to recover this member!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      $.ajax({
        url: "committee-members/delete.php",
        method: "GET",
        data: { id: memberId },
        success: function (response) {
          console.log("Server Response: ", response); // Debugging server response
          try {
            response = typeof response === "string" ? JSON.parse(response) : response;
            if (response.status === "success") {
              // Show success message using SweetAlert
              Swal.fire({
                title: "Success!",
                text: response.message,
                icon: "success",
                confirmButtonText: "OK",
              }).then(function () {
                // Redirect to another page after success
                window.location.href = "committee-members.php";
              });
            } else {
              // Show error message using SweetAlert
              Swal.fire("Error!", response.message, "error");
            }
          } catch (e) {
            console.log("Error parsing response: ", e);
            // Show generic error message using SweetAlert
            Swal.fire(
              "Error!",
              "Failed to update data. Please try again later.",
              "error"
            );
          }
        },
        error: function (xhr, status, error) {
          console.error("AJAX Error: " + xhr.responseText);
          swal("Error!", "Failed to delete data.", "error");
        },
      });
    }
  });
});
});

  $('#phone_number').on('input', function() {
    var phoneNumber = $(this).val();
    phoneNumber = phoneNumber.replace(/[^0-9]/g,
        ''); 
    phoneNumber = phoneNumber.substring(0, 10); 
    $(this).val(phoneNumber); 
    var regex =
        /^\+?\d{10,14}$/;
});