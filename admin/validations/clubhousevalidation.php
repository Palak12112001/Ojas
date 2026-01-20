<?php
function isUnitBooked($connection, $units, $booking_date) {
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

            $bothUnitsBooked = in_array('2', $unitsBooked) || (in_array('0', $unitsBooked) && in_array('1', $unitsBooked));

            if ($bothUnitsBooked) {
                return "Both units are already booked on $booking_date.";
            } elseif ($units == '0' && in_array('0', $unitsBooked)) {
                return "Unit-1 is already booked on $booking_date.";
            } elseif ($units == '1' && in_array('1', $unitsBooked)) {
                return "Unit-2 is already booked on $booking_date.";
            } elseif ($units == '2') {
                if (in_array('0', $unitsBooked) && in_array('1', $unitsBooked)) {
                    return "Both units are already booked on $booking_date.";
                } elseif (in_array('0', $unitsBooked)) {
                    return "Unit-1 is booked and Unit-2 is available on $booking_date.";
                } elseif (in_array('1', $unitsBooked)) {
                    return "Unit-2 is booked and Unit-1 is available on $booking_date.";
                }
            }
        }
        mysqli_stmt_close($stmt);
    }

    return null;
}
?>
