<?php
session_start();
include('../connection.php');
include('committee_login.php');

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set the content type to application/json
header('Content-Type: application/json');

// Initialize response array
$response = array('success' => false, 'message' => 'Invalid request');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check admin login
    $stmt_admin = $connection->prepare("SELECT * FROM tbl_admin WHERE user_name = ?");
    if ($stmt_admin) {
        $stmt_admin->bind_param("s", $username);
        $stmt_admin->execute();
        $result_admin = $stmt_admin->get_result();

        if ($result_admin && mysqli_num_rows($result_admin) > 0) {
            $row_admin = $result_admin->fetch_assoc();
            $hashed_password_admin = $row_admin['password'];

            if (password_verify($password, $hashed_password_admin)) {
                $_SESSION['admin'] = $username;
                $response = array('success' => true, 'message' => 'Admin login successful', 'role' => 'admin');
            } else {
                $response = checkCommittee($username, $password);
                if ($response['success']) {
                    $_SESSION['committee_member'] = $username;
                    $response['role'] = 'committee_member';
                }
            }
        } else {
            $response = checkCommittee($username, $password);
            if ($response['success']) {
                $_SESSION['committee_member'] = $username;
                $response['role'] = 'committee_member';
            }
        }

        $stmt_admin->close();
    } else {
        $response = array('success' => false, 'message' => 'Database query error');
    }
}

echo json_encode($response);
?>
