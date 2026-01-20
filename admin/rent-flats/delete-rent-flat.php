<?php
// Include your database connection file
include('../connection.php');

// Assuming you have sanitized the input for security
$flatno = $_GET['flatno'];

// Retrieve the image path from the database
$sql_select_image = "SELECT id_image FROM tbl_rent_flats WHERE flatno = ?";
$stmt_select_image = $connection->prepare($sql_select_image);
$stmt_select_image->bind_param("s", $flatno); // Assuming flatno is a string
$stmt_select_image->execute();
$stmt_select_image->bind_result($image_path);
$stmt_select_image->fetch();
$stmt_select_image->close();

// Construct the full path to the image file
$image_full_path = '../../assets/images/id_card_images/' . $image_path;

// Delete the image file from the folder
if ($image_path && file_exists($image_full_path)) {
    unlink($image_full_path);
}

// Delete entries from tbl_rent_flats
$sql_delete_flat_holders = "DELETE FROM tbl_rent_flats WHERE flatno = ?";
$stmt_flat_holders = $connection->prepare($sql_delete_flat_holders);
$stmt_flat_holders->bind_param("s", $flatno); // Assuming flatno is a string
$stmt_flat_holders->execute();
$stmt_flat_holders->close();

// Close database connection
$connection->close();

header("Location:../rent-flats.php"); 
exit();
?>
