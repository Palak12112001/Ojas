<?php
header('Content-Type: application/json');
include('../connection.php');
// Include the validation file
include('../validations/flatholderValidation.php'); 
//include file to add vehicals
include('insertVehical.php');
// Function to remove whitespace and hyphens
function removeWhitespaceAndSpecialChars($string)
{
    // Remove whitespace and special characters except alphanumeric
    $result = preg_replace('/[^a-zA-Z0-9]/', '', $string);
    return $result;
}

/* 
Initialize response array
This array will be used to store the status and message that will be sent back as a JSON response.
*/
$response = array('status' => '', 'message' => '', 'errors' => array());

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $bike1 = isset($_POST['bike1']) ? trim($_POST['bike1']) : '';
    $bike2 = isset($_POST['bike2']) ? trim($_POST['bike2']) : '';
    $car = isset($_POST['car']) ? trim($_POST['car']) : '';
    
    $flat_number = isset($_POST['flatno']) ? trim($_POST['flatno']) : '';
    $flatno = removeWhitespaceAndSpecialChars($flat_number);
    $error = validateFullName($full_name);
     // Images (assuming these are uploaded files)
     $vehical1_image = isset($_FILES['bike1_image']) ? $_FILES['bike1_image']: null;
     $vehical2_image = isset($_FILES['bike2_image']) ? $_FILES['bike2_image'] : null;
     $vehical3_image = isset($_FILES['car_image']) ? $_FILES['car_image'] : null;
    
     $vehical1_type = '0';
     $vehical2_type = '0';
     $vehical3_type = '1';
     $vehicle = [
        '$bike1' => ['vehical'=>$bike1,'image' => $vehical1_image, 'type' => $vehical1_type],
        'bike2' => ['vehical'=>$bike2,'image' => $vehical2_image, 'type' => $vehical2_type],
        'car' => ['vehical'=>$car,'image' => $vehical3_image, 'type' => $vehical3_type]
    ];
    if ($error) {
        $response['errors']['full_name'] = $error;
    }

    $error = validatePhone($phone);
    if ($error) {
        $response['errors']['phone'] = $error;
    }

  

    // Check for validation errors
    if (!empty($response['errors'])) {
        $response['status'] = 'error';
        $response['message'] = 'error here';
    } else {
        // No validation errors, proceed with the query
        $query = "INSERT INTO tbl_flat_holders(`full_name`, `phone`, `flatno`) VALUES ('$full_name','$phone','$flatno')";
        $result = mysqli_query($connection, $query);
        if ($result) {
            // Create an instance of the class containing addVehical() method
            $vehicleManager = new VehicleManager();

            // Call the addVehical() method
            $vehicleManager->addVehical($flatno, $vehicle);
            $response['status'] = 'success';
            $response['message'] = 'Your Data Added Successfully';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error In Insert Data';
        }
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Error In Server Method';
}

echo json_encode($response);
?>
