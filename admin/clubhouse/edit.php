<?php
include('../connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve POST data and assign to variables
    $id = $_POST['id'];
    $flat_number = $_POST['flat_number'];
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $units = $_POST['units'];
    $booking_date = $_POST['booking_date'];
    $price = $_POST['price'];
    $card_number = $_POST['card_number'];
    $card_image_name = $_FILES['id_image']['name'];
    $committee_member = $_POST['committee_member'];
    $unit1_old_unit = (int)$_POST['unit1_old'];
    $unit2_old_unit = (int)$_POST['unit2_old'];
    $unit1_end_unit = (int)$_POST['unit1_end'];
    $unit2_end_unit = (int)$_POST['unit2_end'];
    
    $unit1_totalUnits = abs($unit1_old_unit - $unit1_end_unit);
    $unit2_totalUnits = abs($unit2_old_unit - $unit2_end_unit);
    $totalunits = $unit1_totalUnits + $unit2_totalUnits;
    $totalunits_price = 15 * $totalunits;
    $totalprice = $totalunits_price + $price;

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
    // Directory where images will be stored
    $target_dir = "../../assets/images/id_card_images/club_house_booking/";

    // Ensure the target directory exists; create it if it doesn't
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Retrieve the current image name from the database
    $query = "SELECT `card_image` FROM `tbl_clubhouse_booking` WHERE `id` = '$id'";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $old_image = $row['card_image'];
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

    // Create a JSON object for unit data
    $unit_data = json_encode([
        'unit1_old_unit' => $unit1_old_unit,
        'unit2_old_unit' => $unit2_old_unit,
        'unit1_end_unit' => $unit1_end_unit,
        'unit2_end_unit' => $unit2_end_unit,
        'unit1_totalUnits'=>$unit1_totalUnits,
        'unit2_totalUnits'=>$unit2_totalUnits,
    ]);

    // Construct the SQL query
    if ($filename) {
        $query = "UPDATE `tbl_clubhouse_booking` SET `flat_number`='$flat_number',
        `full_name`='$full_name',`phone`='$phone',
        `units`='$units',`price`='$price',`card_number`='$card_number',
        `card_image`='$filename',`committee_member_id`='$committee_member',`committee_member_name`='$committee_name',`booking_date`='$booking_date', `elec_unit_data`='$unit_data',`elec_total_units`='$totalunits',`totalprice`=' $totalprice'
        WHERE `id`='$id'";
    } else {
        $query = "UPDATE `tbl_clubhouse_booking` SET `flat_number`='$flat_number',
        `full_name`='$full_name',`phone`='$phone',
        `units`='$units',`price`='$price',`card_number`='$card_number',
        `committee_member_id`='$committee_member',`committee_member_name`='$committee_name',`booking_date`='$booking_date', `elec_unit_data`='$unit_data',`elec_total_units`='$totalunits',`totalprice`=' $totalprice'
        WHERE `id`='$id'";
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

// Close the connection
mysqli_close($connection);
?>
