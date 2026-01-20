<?php
include('../connection.php');

header('Content-Type: application/json');

$response = array('status' => '', 'message' => '', 'errors' => '');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['booking_date']) || empty($_POST['booking_date'])) {
        $response['status'] = 'error';
        $response['message'] = "Error: Booking date is required.";
        echo json_encode($response);
        exit;
    }
    
    if (isset($_POST['unit']) && isset($_POST['booking_date'])) {
        $unit = $_POST['unit'];
        $booking_date = $_POST['booking_date'];

        // Prepare SQL statement to check for bookings on the given date
        $stmt = mysqli_prepare($connection, "SELECT units FROM `tbl_clubhouse_booking` WHERE booking_date = ?");
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $booking_date);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result) {
                $unitsBooked = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $unitsBooked[] = $row['units'];
                }
                mysqli_free_result($result);

                // Check conditions
                $errorMessage = '';
                $bothUnitsBooked = in_array('2', $unitsBooked) || (in_array('0', $unitsBooked) && in_array('1', $unitsBooked));

                if ($bothUnitsBooked) {
                    $errorMessage = "Both units are already booked on $booking_date.";
                } elseif ($unit == '0' && in_array('0', $unitsBooked)) {
                    $errorMessage = "Unit-1 is already booked on $booking_date.";
                } elseif ($unit == '1' && in_array('1', $unitsBooked)) {
                    $errorMessage = "Unit-2 is already booked on $booking_date.";
                } elseif ($unit == '2') {
                    if (in_array('0', $unitsBooked) && in_array('1', $unitsBooked)) {
                        $errorMessage = "Both units are already booked on $booking_date.";
                    } elseif (in_array('0', $unitsBooked)) {
                        $errorMessage = "Unit-1 is booked and Unit-2 is available on $booking_date.";
                    } elseif (in_array('1', $unitsBooked)) {
                        $errorMessage = "Unit-2 is booked and Unit-1 is available on $booking_date.";
                    }
                }

                if ($errorMessage) {
                    $response['status'] = 'error';
                    $response['message'] = $errorMessage;
                } else {
                    $response['status'] = 'success';
                    $response['message'] = "The unit(s) are available on $booking_date.";
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
        $response['message'] = "Error: Unit and booking date are required.";
    }
} else {
    $response['status'] = 'error';
    $response['message'] = "Error: Invalid request method.";
}

// Close the connection
mysqli_close($connection);

echo json_encode($response);
?>
