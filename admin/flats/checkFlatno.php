<?php
include('../connection.php');

header('Content-Type: application/json');

$response = array('status' => '', 'message' => '', 'errors' => '');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['flatno']) && !empty($_POST['flatno'])) {
        $flatno = $_POST['flatno'];

        // Prepare SQL statement using prepared statements
        $stmt = mysqli_prepare($connection, "SELECT * FROM tbl_flat_holders WHERE flatno = ?");
        
        if ($stmt) {
            // Bind parameters
            mysqli_stmt_bind_param($stmt, 's', $flatno);

            // Execute the statement
            mysqli_stmt_execute($stmt);

            // Get the result
            $result = mysqli_stmt_get_result($stmt);

            if ($result) {
                // Check if any results found
                $exists = mysqli_num_rows($result) > 0;

                // Free result set
                mysqli_free_result($result);

                // Handle existence based on $exists
                if ($exists) {
                    $response['status'] = 'success';
                    $response['message'] = "Flat is not available.";
                } else {
                    $response['status'] = 'error';
                    $response['message'] = "Flat number $flatno does not exist in the table.";
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = "Error: Could not get result set.";
            }

            // Close the statement
            mysqli_stmt_close($stmt);
        } else {
            $response['status'] = 'error';
            $response['message'] = "Error: Could not prepare SQL statement.";
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = "Error: Flat number is required.";
    }
} else {
    $response['status'] = 'error';
    $response['message'] = "Error: Invalid request method.";
}

// Close the connection
mysqli_close($connection);

echo json_encode($response);
?>
