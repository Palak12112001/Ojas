<?php
// Include the database connection file
include('../connection.php');

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

// Check received search term
$search = isset($_POST['search']) ? $_POST['search'] : '';

// Check if the search term needs sanitization
if (!ctype_alnum($search) && containsSpecialChars($search)) {
    // If it contains non-alphanumeric characters, sanitize it
    $search = removeWhitespaceAndSpecialChars($search);
} else {
    $search;
}

// SQL query to filter tbl_flat_holders based on search term
$query = "SELECT * FROM tbl_flat_holders 
          WHERE full_name LIKE ? OR phone LIKE ? OR flatno LIKE ?";
$stmt = $connection->prepare($query);

// Bind parameters and execute the query
$search_term = "%$search%";
$stmt->bind_param("sss", $search_term, $search_term, $search_term);
$stmt->execute();

// Check for errors
if ($stmt->errno) {
    $response['status'] = 'error';
    $response['message'] = 'Database error: ' . $stmt->error;
    echo json_encode($response);
    exit;
}

// Get result set
$result = $stmt->get_result();

// Fetch data as associative array
$data = [];
while ($row = $result->fetch_assoc()) {
    // Format flat number and phone number
    $flat_number = $row['flatno'];
    $flatNumber = removeWhitespaceAndSpecialChars($flat_number);
    $flat_part1 = substr($flatNumber, 0, 1);
    $flat_part2 = substr($flatNumber, 1);
    $flatno = $flat_part1 . '-' . $flat_part2;

    $number = $row['phone'];
    $part1 = substr($number, 0, 5);
    $part2 = substr($number, 5);
    $phone = $part1 . ' ' . $part2;

    // Prepare the processed row
    $processed_row = $row;
    $processed_row['flatno'] = $flatno;
    $processed_row['phone'] = $phone;
    $processed_row['flat_number'] = $flat_number;
    $processed_row['number'] = $number;

    $data[] = $processed_row;
}

// Prepare JSON response
$response = [];
if (count($data) > 0) {
    $response['status'] = 'success';
    $response['data'] = $data;
} else {
    $response['status'] = 'error';
    $response['message'] = 'No results found';
}

// Return JSON response
echo json_encode($response);

// Close the statement and database connection
$stmt->close();
$connection->close();
?>
