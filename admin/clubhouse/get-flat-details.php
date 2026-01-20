<?php
include('../connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $flatno = $_POST['flat_number'];
   
    // Ensure proper SQL query syntax by using single quotes inside double quotes
    $query = "SELECT full_name, phone FROM tbl_flat_holders WHERE flatno='$flatno'";
    $result = mysqli_query($connection, $query);
    $data = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        mysqli_free_result($result); // Free result set
    } else {
        // Handle SQL query error if any
        $response['status'] = 'error';
        $response['message'] = 'Error executing query: ' . mysqli_error($connection);
        echo json_encode($response);
        exit; // Exit script
    }

    // Prepare JSON response
    $response = [];
    if (count($data) > 0) {
        $response['status'] = 'success';
        $response['data'] = $data;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'No results found';
    }

    // Return JSON response
    echo json_encode($response);
}
?>
