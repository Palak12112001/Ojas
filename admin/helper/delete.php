<?php
// Include database connection
require '../connection.php';

// Check if 'id' is set in POST request
if (isset($_POST['id'])) {
    // POST the member ID from POST request
    $Id = $_POST['id'];

    // Prepare the SQL delete statement
    $sql = "DELETE FROM tbl_helpers WHERE id = $Id";

    // Execute the query
    if (mysqli_query($connection, $sql)) {
        $response = array('status' => 'success', 'message' => 'Committee member deleted successfully.');
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to delete committee member.');
    }
} else {
    $response = array('status' => 'error', 'message' => 'Invalid request. Member ID is required.');
}

// Close the database connection
mysqli_close($connection);

// Return JSON response
echo json_encode($response);
?>