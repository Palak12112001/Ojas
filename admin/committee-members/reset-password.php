<?php
include('../connection.php'); // Include your database connection file

$response = ['status' => 'error', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);
    $user_id =trim($_POST['id']); // Assumes the user ID is stored in session

    // Validate inputs
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $response['message'] = 'All fields are required.';
    } elseif ($new_password !== $confirm_password) {
        $response['message'] = 'New password and confirm password do not match.';
    } elseif (strlen($new_password) < 6) {
        $response['message'] = 'Password must be at least 6 characters long.';
    } else {
        // Check current password
        $query = "SELECT password FROM `tbl_committee_members` WHERE id = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        $stmt->close();

        if (!password_verify($current_password, $hashed_password)) {
            $response['message'] = 'Current password is incorrect.';
        } else {
            // Update with new password
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_query = "UPDATE `tbl_committee_members` SET password = ? WHERE id = ?";
            $update_stmt = $connection->prepare($update_query);
            $update_stmt->bind_param("si", $new_hashed_password, $user_id);

            if ($update_stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Password reset successfully.';
            } else {
                $response['message'] = 'Failed to update password. Please try again later.';
            }

            $update_stmt->close();
        }
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
