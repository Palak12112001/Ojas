<?php
// Enable error reporting and display errors (for debugging purposes)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection file
include("../connection.php");

// Check if all required parameters are set
if (isset($_GET['id'], $_POST['title'], $_POST['amount'], $_POST['payment_mode'], $_POST['committee_member_id'])) {
    // Sanitize inputs
    $id = mysqli_real_escape_string($connection, $_GET['id']);
    $title = mysqli_real_escape_string($connection, $_POST['title']);
    $amount = mysqli_real_escape_string($connection, $_POST['amount']);
    $payment_mode = mysqli_real_escape_string($connection, $_POST['payment_mode']);
    $committee_member_id = mysqli_real_escape_string($connection, $_POST['committee_member_id']);

    // Initialize image variables
    $image = null;
    $image_status = 'no_change'; // Default status if no image update

    $image_path = NULL;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = '../../assets/images/expense/';
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $image_name = basename($_FILES['image']['name']);
        $image_path = $target_dir . $image_name;
    
        // Validate file type
        $imageFileType = strtolower(pathinfo($image_path, PATHINFO_EXTENSION));
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            echo json_encode(['status' => 'error', 'message' => 'Only JPG, JPEG, PNG & GIF files are allowed.']);
            exit;
        }
    
        // Move uploaded file to target directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            // File uploaded successfully
            $image = mysqli_real_escape_string($connection, $image_path); // Escape image path for SQL
    
            // Now update your database with $image path if necessary
    
            // Set status or message for successful update
            $image_status = 'updated';
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to upload image.']);
            exit;
        }
    }
    

    // Build update query
    $update_query = "UPDATE `tbl_expenses` SET `title`='$title', `amount`='$amount', `payment_mode`='$payment_mode', `committee_members_id`='$committee_member_id'";
    
    // Append image update to query if an image was uploaded
    if (isset($image)) {
        $update_query .= ", `image`='$image_name'";
    }

    $update_query .= " WHERE `id`='$id'";

    // Execute update query
    if (mysqli_query($connection, $update_query)) {
        // Construct response based on image update status
        if ($image_status === 'updated') {
            echo json_encode(['status' => 'success', 'message' => 'Record updated successfully with image.']);
        } else {
            echo json_encode(['status' => 'success', 'message' => 'Record updated successfully without changing the image.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error updating record: ' . mysqli_error($connection)]);
        // Log detailed error message
        error_log('MySQL Error: ' . mysqli_error($connection));
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing required parameters.']);
}
?>
