<?php
header('Content-Type: application/json');
include('../connection.php');
include('updateVehical.php');

// Initialize response array
$response = array('status' => '', 'message' => '', 'errors' => array());

// Ensure the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $flatno = isset($_POST['flatno']) ? trim($_POST['flatno']) : '';
    $full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
    $phone = isset($_POST['phone']) ? preg_replace('/[^0-9]/', '', $_POST['phone']) : ''; // Remove extra spaces or signs

    // Vehicles data
    $vehical1 = isset($_POST['bike1']) ? trim($_POST['bike1']) : '';
    $vehical2 = isset($_POST['bike2']) ? trim($_POST['bike2']) : '';
    $vehical3 = isset($_POST['car']) ? trim($_POST['car']) : '';

    $vehical1_id = isset($_POST['bike1_id']) ? trim($_POST['bike1_id']) : '';
    $vehical2_id = isset($_POST['bike2_id']) ? trim($_POST['bike2_id']) : '';
    $vehical3_id = isset($_POST['car_id']) ? trim($_POST['car_id']) : '';

    $vehical1_type = isset($_POST['bike1_type']) ? trim($_POST['bike1_type']) : '';
    $vehical2_type = isset($_POST['bike2_type']) ? trim($_POST['bike2_type']) : '';
    $vehical3_type = isset($_POST['car_type']) ? trim($_POST['car_type']) : '';

    // Images (assuming these are uploaded files)
    $vehical1_image = isset($_FILES['bike1_image']) ? $_FILES['bike1_image'] : null;
    $vehical2_image = isset($_FILES['bike2_image']) ? $_FILES['bike2_image'] : null;
    $vehical3_image = isset($_FILES['car_image']) ? $_FILES['car_image'] : null;


    // Validate required fields
    if (empty($full_name)) {
        $response['errors']['full_name'] = 'Full Name is required.';
    }
    if (empty($phone)) {
        $response['errors']['phone'] = 'Phone number is required.';
    } elseif (!is_numeric($phone) || strlen($phone) != 10) {
        $response['errors']['phone'] = 'Invalid phone number. It must be 10 digits.';
    }

    // Validate vehicle numbers
    $vehicles = array($vehical1, $vehical2, $vehical3);
    $vehicle_types = array($vehical1_type, $vehical2_type, $vehical3_type);
    $vehicle_ids = array($vehical1_id, $vehical2_id, $vehical3_id);
    $vehicle_images = array($vehical1_image, $vehical2_image, $vehical3_image);


    // Check if there are validation errors
    if (empty($response['errors'])) {
        $query = "UPDATE `tbl_flat_holders` SET `full_name`='$full_name', `phone`='$phone' WHERE `flatno`='$flatno'";
        $result_vehical = mysqli_query($connection, $query);

        if ($result_vehical) {
            $result = updateVehicle($connection, $vehicle_ids, $vehicles, $vehicle_types,$flatno, $vehicle_images);
            if ($result['status'] === 'success') {
                $response['status'] = 'success';
                $response['message'] = "Updated successfully.";
            } else {
                $response['status'] = 'error';
                $response['message'] = "Error updating vehicle details. Please try again later.";
                $response['errors'] = $result['errors'];
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = "Failed to update vehicle details.";
        }
    }
}

// Output JSON response
echo json_encode($response);
?>
