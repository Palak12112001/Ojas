<?php
include('../connection.php'); // Include the database connection

function checkCommittee($username, $password) {
    global $connection; // Access the global connection variable

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $connection->prepare("SELECT * FROM tbl_committee_members WHERE user_name = ?");
    if ($stmt) {
        $stmt->bind_param("s", $username); // Bind username parameter
        $stmt->execute(); // Execute SQL query
        $result = $stmt->get_result(); // Get result set

        if ($result && mysqli_num_rows($result) > 0) {
            $row = $result->fetch_assoc(); // Fetch row from result set
            $hashed_password = $row['password']; // Get hashed password from database

            // Verify password using password_verify function
            if (password_verify($password, $hashed_password)) {
                // Start PHP session
                $_SESSION['committee_member'] = $username; // Set session variable
                return array('success' => true, 'message' => 'Committee member login successful');
            } else {
                return array('success' => false, 'message' => 'Invalid committee username or password');
            }
        } else {
            return array('success' => false, 'message' => 'Invalid committee username or password');
        }

        $stmt->close(); // Close SQL statement
    } else {
        return array('success' => false, 'message' => 'Database query error');
    }
}
?>
