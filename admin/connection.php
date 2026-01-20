<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection parameters
$servername = "localhost";
$username = "root";  // replace with your database username
$password = "";  // replace with your database password
$dbname = "ojas";    // replace with your database name

// Create connection
$connection = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get current date
$current_date = date('Y-m-d');

// Update role to 2 where booking_date is today
$query_today = "UPDATE tbl_clubhouse_booking SET role = '2' WHERE booking_date = '$current_date'";
if (!mysqli_query($connection, $query_today)) {
    echo "Error updating record: " . mysqli_error($connection);
}

// Update role to 0 where booking_date is a future date
$query_future = "UPDATE tbl_clubhouse_booking SET role = '0' WHERE booking_date > '$current_date'";
if (!mysqli_query($connection, $query_future)) {
    echo "Error updating record: " . mysqli_error($connection);
}

// Update role to 1 where booking_date is a past date
$query_past = "UPDATE tbl_clubhouse_booking SET role = '1' WHERE booking_date < '$current_date'";
if (!mysqli_query($connection, $query_past)) {
    echo "Error updating record: " . mysqli_error($connection);
}


// Your additional code goes here

?>
