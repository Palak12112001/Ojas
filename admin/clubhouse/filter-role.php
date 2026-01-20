<?php
include('../connection.php');
 // Function to remove whitespace and hyphens
 function removeWhitespaceAndHyphens($string)
 {
     // Remove whitespace and hyphens
     $result = str_replace([' ', '-'], '', $string);
     return $result;
 }

if(isset($_POST['status'])){
    $status = $_POST['status'];

    $query = "SELECT * FROM `tbl_clubhouse_booking` WHERE role='$status'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        // Query failed
        $error = mysqli_error($connection);
        $response = [
            'status' => 'error',
            'message' => 'Database query failed: ' . $error
        ];
    } else {
        // Query successful, check if rows are fetched
        if (mysqli_num_rows($result) > 0) {
            $response = []; // Initialize an empty array to store response data
            $count = 1; // Initialize counter

            while ($row = mysqli_fetch_assoc($result)) {
                $flat_number = $row['flat_number'];
                $flatNumber = removeWhitespaceAndHyphens($flat_number);
                $flat_part1 = substr($flatNumber, 0, 1);
                $flat_part2 = substr($flatNumber, 1, 4);
                $flatno = $flat_part1 . '-' . $flat_part2;
                $committee_member = $row['committee_member_name'];
                $full_name=$row['full_name'];
                $number=$row['phone'];

                // Determine background color based on the role
                $bg_color = '';
                $role = "";
                if ($row['role'] === '0') {
                    $bg_color = 'badge-success';
                    $role = 'Upcoming';
                } elseif ($row['role'] === '1') {
                    $bg_color = 'badge-danger';
                    $role = 'Past';
                } elseif ($row['role'] === '2') {
                    $bg_color = 'badge-warning';
                    $role = 'Current';
                }

                // Determine unit
                $unit = '';
                if ($row['units'] === '0') {
                    $unit = 'unit-1';
                } elseif ($row['units'] === '1') {
                    $unit = 'unit-2';
                } elseif ($row['units'] === '2') {
                    $unit = 'both';
                }

                // Format booking date
                $formatted_booking_date = date('d/m/Y', strtotime($row['booking_date']));

                // Format price
                $formatted_price = number_format($row['price'], 0, ',');

                // Format phone number
                $part1 = substr($number, 0, 5);
                $part2 = substr($number, 5);
                $phone = $part1 . '&nbsp;' . $part2;
                $total_units=$row['elec_total_units'];
                $total_unitprice=15*$total_units;
                $totalprice = $total_unitprice + $row['price'];
                $formatted_unit_price=number_format($total_unitprice);
                $formatted_total_price=number_format($totalprice);
                // Prepare response object
                $response[] = [
                    'full_name' => $full_name,
                    'flat_number'=>$flatno,
                    'phone' => $phone,
                    'committee_member' => $committee_member,
                    'bg_color' => $bg_color,
                    'role' => $role,
                    'unit' => $unit,
                    'booking_date' => $formatted_booking_date,
                    'price' => $formatted_price,
                    'total_units_price'=>$formatted_unit_price,
                    'total_price'=>$formatted_total_price,
                ];
             
            } 

            // Return JSON response for successful query
            $response = [
                'status' => 'success',
                'response' => $response
            ];
        } else {
            // No rows found
            $response = [
                'status' => 'error',
                'message' => 'No data found'
            ];
        }
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);

    // Close the connection and exit
    mysqli_close($connection);
    exit;
}
?>
