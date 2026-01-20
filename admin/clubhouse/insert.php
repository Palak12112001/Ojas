<?php
include('../connection.php');
include('../validations/clubhousevalidation.php');  // Include the validation functions

$response = array('status' => '', 'message' => '', 'errors' => array());

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs (you may need to define these validation functions)
    $flat_number = mysqli_real_escape_string($connection, $_POST['flat_number']);
    $full_name = mysqli_real_escape_string($connection, $_POST['full_name']);
    $phone = mysqli_real_escape_string($connection, $_POST['phone']);
    $units = mysqli_real_escape_string($connection, $_POST['units']);
    $booking_date = mysqli_real_escape_string($connection, $_POST['booking_date']);
    $price = mysqli_real_escape_string($connection, $_POST['price']);
    $card_number = mysqli_real_escape_string($connection, $_POST['card_number']);
    $committee_member = mysqli_real_escape_string($connection, $_POST['committee_member']);

    // Validate the booking date and units using your validation function
    $errorMessage = isUnitBooked($connection, $units, $booking_date);
    if ($errorMessage) {
        $response['status'] = 'error';
        $response['message'] = $errorMessage;
        echo json_encode($response);
        exit;
    }

    // Fetch committee member name from database
    $committee_query = "SELECT full_name FROM `tbl_committee_members` WHERE id='$committee_member'";
    $committee_result = mysqli_query($connection, $committee_query);
    if (!$committee_result) {
        $response['status'] = 'error';
        $response['message'] = 'Error fetching committee member details: ' . mysqli_error($connection);
        echo json_encode($response);
        exit;
    }
    $committee_row = mysqli_fetch_assoc($committee_result);
    $committee_name = $committee_row['full_name'];

    // Replace whitespace with underscores in full_name for filename, keeping extension
    $card_image_name = $_FILES['id_image']['name'];
    $filename = str_replace(' ', '_', $full_name) . '.' . pathinfo($card_image_name, PATHINFO_EXTENSION);

    // Directory where images will be stored
    $target_dir = "../../assets/images/id_card_images/club_house_booking/";

    // Ensure the target directory exists; create it if it doesn't
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Full path to where the uploaded image will be stored
    $target_file = $target_dir . basename($filename);

    // Move uploaded image file to the specified location
    if (move_uploaded_file($_FILES["id_image"]["tmp_name"], $target_file)) {
        // File successfully uploaded, now insert data into database

        // Construct the SQL query with prepared statement
        $query = "INSERT INTO `tbl_clubhouse_booking` (`flat_number`, `full_name`, `phone`, `units`, `price`, `card_number`, `card_image`, `committee_member_id`, `committee_member_name`, `booking_date`,`totalprice`) VALUES 
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";
        
        // Prepare the statement
        $stmt = mysqli_prepare($connection, $query);
        if (!$stmt) {
            $response['status'] = 'error';
            $response['message'] = 'Error preparing statement: ' . mysqli_error($connection);
            echo json_encode($response);
            exit;
        }

        // Bind parameters to the prepared statement
        mysqli_stmt_bind_param($stmt, "ssssdssssss", $flat_number, $full_name, $phone, $units, $price, $card_number, $filename, $committee_member, $committee_name, $booking_date, $price);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['status' => 'success', 'message' => 'Your Data Added Successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error inserting data: ' . mysqli_stmt_error($stmt)]);
        }

        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error uploading file']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

// Close the connection
mysqli_close($connection);
?>
