<?php
include('../connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve POST data and assign to variables
    $flatno = $_POST['flatno']; // Unique identifier for the record to update
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $card_number = $_POST['id_number'];
    $charge_paid = $_POST['charge_paid'];
    $amount = $_POST['charge_amount']; // Assuming 'charge_amount' is the correct field name

    // Directory where images will be stored
    $target_dir = "../../assets/images/id_card_images/";

    // Ensure the target directory exists; create it if it doesn't
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Retrieve the current image name from the database
    $query = "SELECT `id_image` FROM `tbl_rent_flats` WHERE `flatno` = '$flatno'";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $old_image = $row['id_image'];
    } else {
        $old_image = null;
    }

    // Prepare the filename and file path
    $filename = null;
    if (!empty($_FILES['id_image']['name'])) {
        // Original file name with extension
        $card_image_name = $_FILES['id_image']['name'];
        // Replace whitespace with underscores in full_name for filename, keeping extension
        $filename = str_replace(' ', '_', $full_name) . '.' . pathinfo($card_image_name, PATHINFO_EXTENSION);
        // Full path to where the uploaded image will be stored
        $target_file = $target_dir . basename($filename);

        // Move uploaded image file to the specified location
        if (move_uploaded_file($_FILES["id_image"]["tmp_name"], $target_file)) {
            // Delete the old image file if it exists
            if ($old_image && file_exists($target_dir . $old_image)) {
                unlink($target_dir . $old_image);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error uploading file']);
            exit;
        }
    }

    // Construct the SQL query
    if ($filename) {
        $query = "UPDATE `tbl_rent_flats` 
                  SET 
                      `full_name` = '$full_name', 
                      `phone` = '$phone', 
                      `charge_paid` = '$charge_paid', 
                      `amount` = '$amount', 
                      `id_number` = '$card_number', 
                      `id_image` = '$filename' 
                  WHERE `flatno` = '$flatno'";
    } else {
        $query = "UPDATE `tbl_rent_flats` 
                  SET 
                      `full_name` = '$full_name', 
                      `phone` = '$phone', 
                      `charge_paid` = '$charge_paid', 
                      `amount` = '$amount', 
                      `id_number` = '$card_number' 
                  WHERE `flatno` = '$flatno'";
    }

    // Execute the query
    $result = mysqli_query($connection, $query);

    // Check if the update was successful
    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Your Data Updated Successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error updating data: ' . mysqli_error($connection)]);
    }
}
?>
