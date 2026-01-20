$(document).ready(function () {
  $('#image').on('change',function(){
    var input = this;

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('.img-thumbnail').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
  })
  $("#expensesForm").validate({
    rules: {
      title: {
        required: true,
      },
      amount: {
        required: true,
        number: true,
      },
      payment_mode: {
        required: true,
        digits: true,
        range: [0, 1],
      },
      Name: {
        required: true,
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
      payment_mode: {
        required: "Please select a payment mode.",
        // digits: "Please select a valid payment mode.",
        // range: "Please select a valid payment mode."
      },
      Name: {
        required: "Please select a name.",
      },
    },
    submitHandler: function (form) {
      var formData = new FormData(form);

      // AJAX request
      $.ajax({
        url: "expenses/insert.php",
        method: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (response) {
          if (response.success) {
            Swal.fire({
              title: "Success!",
              text: "Expense has been added successfully.",
              icon: "success",
              confirmButtonText: "OK",
            }).then(function () {
              window.location.href = "expenses-list.php";
            });
          } else {
            Swal.fire({
              title: "Error!",
              text: response.error,
              icon: "error",
              confirmButtonText: "OK",
            });
          }
        },
        error: function (xhr, status, error) {
          console.log(xhr.responseText);
          console.log(status);
          console.log(error);
          // Swal.fire({
          //     title: "Error!",
          //     text: "There was an error processing your request.",
          //     icon: "error",
          //     confirmButtonText: "OK"
          // });
        },
      });
    },
  });
});

// Delete deleteexpenses
$(document).on("click", ".deleteexpenses", function (e) {
  e.preventDefault();
  var memberId = $(this).data("id");

  // Show confirmation dialog using SweetAlert
  swal({
    title: "Are you sure?",
    text: "Once deleted, you will not be able to recover this member!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      // Send AJAX request to delete the expense
      $.ajax({
        url: "expenses/delete.php",
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
                window.location.href = "expenses-list.php";
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
