<?php
// Ensure that connection.php file contains your database connection details and connects to MySQL
include("../connection.php");

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize response array
    $response = array();

    // Validate and sanitize inputs
    $id = isset($_POST['id']) ? mysqli_real_escape_string($connection, $_POST['id']) : '';
    $fullName = isset($_POST['full_name']) ? mysqli_real_escape_string($connection, $_POST['full_name']) : '';
    $phoneNumber = isset($_POST['phone_number']) ? mysqli_real_escape_string($connection, $_POST['phone_number']) : '';
    $wing = isset($_POST['wing_name']) ? mysqli_real_escape_string($connection, $_POST['wing_name']) : '';
    $userName = isset($_POST['user_name']) ? mysqli_real_escape_string($connection, $_POST['user_name']) : '';

    // Fetch the current image path from the database
    $query = "SELECT image FROM tbl_committee_members WHERE id = '$id'";
    $result = mysqli_query($connection, $query);
    $currentImage = ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result)['image'] : '';

    // Check if a new image was uploaded
    if ($_FILES['image']['size'] > 0) {
        // Handle file upload
        $image = $_FILES['image']['name'];
        $imageExtension = pathinfo($image, PATHINFO_EXTENSION);
        // Save the image as the username with the correct extension
        $imageFileName = $userName . '.' . $imageExtension;
        $target_dir = "../../assets/images/committee-members-image/";

        // If the target directory doesn't exist, create it
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Define the target file path
        $target_file = $target_dir . basename($imageFileName);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // File upload success

            // Delete the old image if it exists and is different from the new one
            if (!empty($currentImage) && $currentImage != $imageFileName && file_exists($target_dir . $currentImage)) {
                unlink($target_dir . $currentImage);
            }

            // Update the image path to the new image
            $imagePath = $imageFileName;
        } else {
            // File upload failed
            $response['status'] = 'error';
            $response['message'] = 'Failed to upload image';
            echo json_encode($response);
            exit; // Stop further execution
        }
    } else {
        // No new image uploaded, retain the old image path
        $imagePath = $currentImage;
    }

    // Prepare the update query
    $updateQuery = "UPDATE `tbl_committee_members` SET `full_name`='$fullName', `phone`='$phoneNumber', `wing_name`='$wing', `user_name`='$userName', `image`='$imagePath' WHERE `id`='$id'";

    // Execute the update query
    if (mysqli_query($connection, $updateQuery)) {
        // Successful update
        $response['status'] = 'success';
        $response['message'] = 'Committee member updated successfully';
    } else {
        // Error in SQL query
        $response['status'] = 'error';
        $response['message'] = 'Failed to update committee member: ' . mysqli_error($connection);
    }
} else {
    // Method not allowed
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method';
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
