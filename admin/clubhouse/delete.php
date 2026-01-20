<?php
// Include your database connection file
include('../connection.php');

// Assuming you have sanitized the input for security
$id = $_GET['id'];

// Retrieve the image path from the database
$sql_select_image = "SELECT card_image FROM tbl_clubhouse_booking WHERE id = $id";
$result = mysqli_query($connection, $sql_select_image);
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $image_path = $row['card_image'];
}

// Construct the full path to the image file
$image_full_path = '../../assets/images/id_card_images/club_house_booking' . $image_path;

// Delete the image file from the folder
if ($image_path && file_exists($image_full_path)) {
    unlink($image_full_path);
}

// Delete entries from tbl_rent_flats
$sql_delete_flat_holders = "DELETE FROM `tbl_clubhouse_booking` WHERE id = $id";
mysqli_query($connection, $sql_delete_flat_holders);


// Close database connection
$connection->close();

header("Location:../club-house-booking.php"); 
exit();
?>
