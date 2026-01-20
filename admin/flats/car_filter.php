<?php
// Include the database connection file
include('../connection.php');

// Get the search term
$search = $_POST['search'];

// Function to remove whitespace and special characters except alphanumeric
function removeWhitespaceAndSpecialChars($string)
{
    // Remove whitespace and special characters except alphanumeric
    $result = preg_replace('/[^a-zA-Z0-9]/', '', $string);
    return $result;
}

// Function to check if string contains special characters (excluding whitespace)
function containsSpecialChars($string) {
    // Use a regular expression to check for any special characters
    return preg_match('/[^A-Za-z0-9\s]/', $string);
}


// Check if the search term needs sanitization
if (!ctype_alnum($search) && containsSpecialChars($search)) {
    // If it contains non-alphanumeric characters, sanitize it
    $search = removeWhitespaceAndSpecialChars($search);
} else {
    $search;
}

// Perform the query for flats with one car entry
$query = "SELECT fh.full_name, fh.phone, v.flat_number, v.car
          FROM tbl_flat_holders fh
          JOIN tbl_vehicles v ON fh.flatno = v.flat_number
          WHERE v.flag = '1' 
            AND (fh.full_name LIKE ? OR fh.phone LIKE ? OR v.flat_number LIKE ? OR v.car LIKE ?)
            AND v.car IS NOT NULL
          GROUP BY v.flat_number
          HAVING COUNT(v.car) = 1";
$stmt = $connection->prepare($query);
$search_term = "%" . $search . "%";
$stmt->bind_param("ssss", $search_term, $search_term, $search_term, $search_term);
$stmt->execute();
$result = $stmt->get_result();

// Fetch all results as an associative array
$data = [];
if ($result) {
    $count = 1;
    while ($row = $result->fetch_assoc()) {
        $row['count'] = $count;
        $row['car'] = $row['car']; 
        if (empty($row['car'])) {
            continue;
        }
        $flat_number = $row['flat_number'];
        $flatNumber = removeWhitespaceAndSpecialChars($flat_number);
        $flat_part1 = substr($flatNumber, 0, 1);
        $flat_part2 = substr($flatNumber, 1);
        $flatno = $flat_part1 . '-' . $flat_part2;
    
       $row['flat_number']=$flatno;
        $data[] = $row;
        $count++;
    }
}

// Prepare the response
$response = [];
if (count($data) > 0) {
    $response['status'] = 'success';
    $response['data'] = $data;
} else {
    $response['status'] = 'error';
    $response['message'] = 'No results found';
}

// Return the response as JSON
echo json_encode($response);

// Close the connection
$stmt->close();
$connection->close();
?>
