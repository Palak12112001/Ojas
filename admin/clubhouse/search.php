<?php
include('../connection.php');
 // Function to remove whitespace and hyphens
 function removeWhitespaceAndHyphens($string)
 {
     // Remove whitespace and hyphens
     $result = str_replace([' ', '-'], '', $string);
     return $result;
 }

if (isset($_POST['search'])) {
    $search = strtolower($_POST['search']); // Convert the search term to lowercase

    // Map specific search terms to corresponding database values for units and roles
    $unitMapping = [
        'unit-1' => '0',
        'unit-2' => '1',
        'both' => '2'
    ];

    $roleMapping = [
        'upcoming' => '0',
        'past' => '1',
        'current' => '2'
    ];

    // Initialize the base query
    $baseQuery = "
       SELECT `id`, `flat_number`, `full_name`, `phone`, `units`, `price`, `card_number`, `card_image`, `committee_member_name`, `booking_date`, `role`,`elec_unit_data`,`elec_total_units`,`totalprice` FROM `tbl_clubhouse_booking`
        WHERE 
    ";

    $query = "";
    $formattedDate = "";
    $isDateFormat = false;

    // Check if the search term is in the unit mapping array
    if (array_key_exists($search, $unitMapping)) {
        $searchValue = $unitMapping[$search];
        $query = $baseQuery . "units = '$searchValue'";
    }
    // Check if the search term is in the role mapping array
    elseif (array_key_exists($search, $roleMapping)) {
        $searchValue = $roleMapping[$search];
        $query = $baseQuery . "role = '$searchValue'";
    } else {
        // Check if the search term matches any date formats
        if (preg_match('/^\d{1,2}-\d{1,2}-\d{2}$/', $search) || preg_match('/^\d{1,2}\/\d{2}$/', $search) || preg_match('/^\d{4}$/', $search) || preg_match('/^\d{1,2}\/\d{4}$/', $search)) {
            $day='';
            $month='';
            $formattedDate='';
            $year='';
            $isDateFormat = true;
            // Split the search term into parts based on possible formats
            if (strpos($search, '/') !== false) {
                $dateParts = explode('/', $search);
                $day = isset($dateParts[0]) ? str_pad($dateParts[0], 2, '0', STR_PAD_LEFT) : '';
                $month = isset($dateParts[1]) ? str_pad($dateParts[1], 2, '0', STR_PAD_LEFT) : '';
                $year = isset($dateParts[2]) ? $dateParts[2] : '';
            } elseif (strpos($search, '-') !== false) {
                $dateParts = explode('-', $search);
                $day = isset($dateParts[0]) ? str_pad($dateParts[0], 2, '0', STR_PAD_LEFT) : '';
                $month = isset($dateParts[1]) ? str_pad($dateParts[1], 2, '0', STR_PAD_LEFT) : '';
                $year = isset($dateParts[2]) ? $dateParts[2] : '';
            } else {
                $year = $search;
            }

            // Construct the formatted date based on available parts
            if (!empty($year) && strlen($year) === 2) {
                // Year provided in YY format
                $formattedDate = '20' . $year . '-' . $month . '-' . $day;
            } elseif (!empty($year) && strlen($year) === 4 && !empty($month)) {
                // Year provided in YYYY format with month
                $formattedDate = $year . '-' . $month;
            }
        }

        // If a date format was detected, construct the query accordingly
        if (!empty($formattedDate)) {
            if (strlen($formattedDate) === 7) {
                // MM/YYYY format
                $query = $baseQuery . "DATE_FORMAT(booking_date, '%m/%Y') = '$formattedDate'";
            } else {
                // Other date formats
                $query = $baseQuery . "YEAR(booking_date) = '$year' OR DATE_FORMAT(booking_date, '%d/%m') = '$day/$month' OR DATE_FORMAT(booking_date, '%Y-%m') = '$formattedDate'";
            }
        }
    }

    // If no specific search conditions are met, search across all relevant fields using LOWER()
    if (empty($query)) {
        $query = $baseQuery . "
            LOWER(flat_number) LIKE '%$search%' OR
            LOWER(units) LIKE '%$search%' OR
            LOWER(price) LIKE '%$search%' OR
            LOWER(card_number) LIKE '%$search%' OR
            DATE_FORMAT(booking_date, '%d/%m/%Y') LIKE '%$search%' OR
            LOWER(full_name) LIKE '%$search%' OR
            LOWER(phone) LIKE '%$search%' OR
            LOWER(committee_member_name) LIKE '%$search%' OR
            LOWER(role) LIKE '%$search%' OR
            LOWER(totalprice) LIKE '%$search%'";
    }

    // Execute the query
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
