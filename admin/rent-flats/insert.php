<?php
include('../connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve POST data and assign to variables
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $flatno = $_POST['flatno'];
    $card_number = $_POST['id_number'];
    $card_image_name = $_FILES['id_image']['name']; // Original file name with extension
    $charge_paid = $_POST['charge_paid'];
    $amount = $_POST['charge_amount']; // Assuming 'charge_amount' is the correct field name

    // Replace whitespace with underscores in full_name for filename, keeping extension
    $filename = str_replace(' ', '_', $full_name) . '.' . pathinfo($card_image_name, PATHINFO_EXTENSION);

    // Directory where images will be stored
    $target_dir = "../../assets/images/id_card_images/";

    // Ensure the target directory exists; create it if it doesn't
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Full path to where the uploaded image will be stored
    $target_file = $target_dir . basename($filename);

    // Move uploaded image file to the specified location
    if (move_uploaded_file($_FILES["id_image"]["tmp_name"], $target_file)) {
        // File successfully uploaded, now insert data into database

        // Construct the SQL query
        $query = "INSERT INTO `tbl_rent_flats` (`flatno`, `full_name`, `phone`, `charge_paid`, `amount`, `id_number`, `id_image`, `insert_at`)
                  VALUES ('$flatno', '$full_name', '$phone', '$charge_paid', '$amount', '$card_number', '$filename', NOW())";

        // Execute the query
        $result = mysqli_query($connection, $query);

        // Check if insertion was successful
        if ($result) {
            
            echo json_encode(['status' => 'success', 'message' => 'Your Data Added Successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error inserting data: ' . mysqli_error($connection)]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error uploading file']);
    }
}
?>
