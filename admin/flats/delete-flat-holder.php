<?php
// Include your database connection file
include('../connection.php');

// Assuming you have sanitized the input for security
$flatno = $_GET['flatno'];


// Delete entries from tbl_flat_holders
$sql_delete_flat_holders = "DELETE FROM tbl_flat_holders WHERE flatno = ?";
$stmt_flat_holders = $connection->prepare($sql_delete_flat_holders);
$stmt_flat_holders->bind_param("s", $flatno); // Assuming flatno is a string
$stmt_flat_holders->execute();
$stmt_flat_holders->close();

$old_image_sql = "select image from `tbl_vehicles` where flat_number = '$flatno'";
$old_image_result = mysqli_query($connection, $old_image_sql);
$old_image = mysqli_fetch_assoc($old_image_result);
while($old_image = mysqli_fetch_assoc($old_image_result)){
    if ($old_image['image'] !== null) {
        $old_image_path = "../../assets/images/vehicalImage/".$old_image['image'];
        // Check if the old image exists and delete it
        if (file_exists($old_image_path)) {
            unlink($old_image_path);
        }
     }
}

// Delete entries from tbl_vehicles
$sql_delete_vehicles = "DELETE FROM tbl_vehicles WHERE flat_number = ?";
$stmt_vehicles = $connection->prepare($sql_delete_vehicles);
$stmt_vehicles->bind_param("s", $flatno); // Assuming flatno is a string
$stmt_vehicles->execute();
$stmt_vehicles->close();

// Close database connection
$connection->close();

header("Location:../user.php"); 
exit();
?>
