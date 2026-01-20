<?php
include ('../connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['full_name'];
    $phoneNumber = $_POST['phone_number'];
    $wing = $_POST['wing'];
    $userName = $_POST['user_name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Handle file upload
    $image = $_FILES['image']['name'];
    $imageExtension = pathinfo($image, PATHINFO_EXTENSION);
    $imageFileName = $userName . '.' . $imageExtension;
    $target_dir = "../../assets/images/committee-members-image/";

    // Ensure the target directory exists; create it if it doesn't
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Full path to where the uploaded image will be stored
    $target_file = $target_dir . $imageFileName;

    // Move uploaded image file to the specified location
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // File successfully uploaded, now insert data into database
        $sql = "INSERT INTO `tbl_committee_members` (`full_name`, `phone`, `wing_name`, `user_name`, `password`, `image`) 
                VALUES ('$fullName', '$phoneNumber', '$wing', '$userName', '$password', '$imageFileName')";

        // Execute the query
        $result = mysqli_query($connection, $sql);

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
