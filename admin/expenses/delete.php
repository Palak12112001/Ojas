<?php
// Include database connection
require '../connection.php';

// Initialize response array
$response = array('status' => 'error', 'message' => 'Invalid request. Member ID is required.');

// Check if 'id' is set in GET request (since you're passing 'id' via GET in AJAX)
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Get the expense ID from GET request
    $id = $_GET['id'];

    // Use prepared statement to prevent SQL injection
    $sql = "DELETE FROM tbl_expenses WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            $response['status'] = 'success';
            $response['message'] = 'Expense deleted successfully.';
        } else {
            $response['message'] = 'Failed to delete expense.';
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        $response['message'] = 'Failed to prepare the SQL statement.';
    }
}

// Close the database connection
mysqli_close($connection);

// Return JSON response
echo json_encode($response);
?>
