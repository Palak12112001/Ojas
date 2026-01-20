<?php
include("../connection.php");

$response = array('success' => false, 'error' => '');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 ;
    // Validate and sanitize inputs
    $title = mysqli_real_escape_string($connection, $_POST['title']);
    $amount = mysqli_real_escape_string($connection, $_POST['amount']);
    $payment_mode = mysqli_real_escape_string($connection, $_POST['payment_mode']);
    $name_id = mysqli_real_escape_string($connection, $_POST['Name']);


    // Validate inputs (basic example)
    if (empty($title)) {
        $response['error'] = "Title is required";
        echo json_encode($response);
        exit;
    }
    if (empty($amount) || !is_numeric($amount)) {
        $response['error'] = "Valid amount is required";
        echo json_encode($response);
        exit;
    }
    if (empty($name_id)) {
        $response['error'] = "Select a valid name";
        echo json_encode($response);
        exit;
    }

    // Handle file upload
    $image_path = NULL;
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "../../assets/images/expense/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file is an actual image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check === false) {
            $response['error'] = "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size (5MB max)
        if ($_FILES["image"]["size"] > 5000000) {
            $response['error'] = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            $response['error'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo json_encode($response);
            exit;
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = basename($_FILES["image"]["name"]);
            } else {
                $response['error'] = "Sorry, there was an error uploading your file.";
                echo json_encode($response);
                exit;
            }
        }
    }

    // Insert data into database
    if ($image_path) {
        $query = "INSERT INTO tbl_expenses (title, amount, payment_mode, committee_members_id, image) 
                  VALUES ('$title', '$amount', '$payment_mode', '$name_id', '$image_path')";
    } else {
        $query = "INSERT INTO tbl_expenses (title, amount, payment_mode, committee_members_id) 
                  VALUES ('$title', '$amount', '$payment_mode', '$name_id')";
    }

    if (mysqli_query($connection, $query)) {
        $response['success'] = true;
    } else {
        $response['error'] = "Error: " . $query . "<br>" . mysqli_error($connection);
    }
      // Return the response as JSON
echo json_encode($response);

    // Close database connection
    mysqli_close($connection);
}
?>