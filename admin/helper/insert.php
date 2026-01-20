<?php
include("../connection.php");

if (isset($_POST['forms'])) {
    $forms = $_POST['forms'];

    // Truncate tbl_helpers table to remove existing data
    $queryTruncate = "TRUNCATE TABLE `tbl_helpers`";
    mysqli_query($connection, $queryTruncate);

    // Array to store inserted IDs for success response
    $insertedIds = [];

    foreach ($forms as $form) {
        $service = mysqli_real_escape_string($connection, $form[0]['value']);
        $name = mysqli_real_escape_string($connection, $form[1]['value']);
        $phone = mysqli_real_escape_string($connection, $form[2]['value']);

        // Check if all required fields are not empty
        if (!empty($service) && !empty($name) && !empty($phone)) {
            $query = "INSERT INTO `tbl_helpers` (`service`, `name`, `phone`) VALUES ('$service', '$name', '$phone')";
            $result = mysqli_query($connection, $query);
            if ($result) {
                // Retrieve last inserted ID if needed
                $insertedIds[] = mysqli_insert_id($connection);
            } else {
                // Handle insertion error
                echo json_encode(['status' => 'error', 'message' => 'Error inserting data into database']);
                exit;
            }
        }
    }

    // If all forms were successfully inserted
    echo json_encode(['status' => 'success', 'message'=>'Data Inserted successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No form data received']);
}
?>
