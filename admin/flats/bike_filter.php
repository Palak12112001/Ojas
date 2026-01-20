<?php
include('../connection.php');

// Check if search term is provided and sanitize
$search = isset($_POST['search']) ? trim($_POST['search']) : '';
if (empty($search)) {
    $response = [
        'status' => 'error',
        'message' => 'Search term is required'
    ];
    echo json_encode($response);
    exit;
}
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

// Construct the SQL query with placeholders
$query = "
SELECT fh.full_name, fh.phone, fh.flatno, GROUP_CONCAT(v.bike SEPARATOR ',') AS bikes
FROM tbl_flat_holders fh
JOIN tbl_vehicles v ON fh.flatno = v.flat_number
WHERE v.flag = '0'
AND fh.flatno IN (
    SELECT DISTINCT fh.flatno
    FROM tbl_flat_holders fh
    JOIN tbl_vehicles v ON fh.flatno = v.flat_number
    WHERE v.flag = '0'
    AND (fh.full_name LIKE ?
         OR fh.phone LIKE ?
         OR fh.flatno LIKE ?
         OR v.bike LIKE ?)
)
GROUP BY fh.full_name, fh.phone, fh.flatno;
";

// Prepare and bind parameters
$stmt = $connection->prepare($query);
$search_term = "%" . $search . "%";
$stmt->bind_param("ssss", $search_term, $search_term, $search_term, $search_term);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Fetch all results as an associative array
$data = [];

while ($row = $result->fetch_assoc()) {
    // Split bikes into bike1 and bike2
    $bikes = explode(',', $row['bikes']);
    $bike1 = isset($bikes[0]) ? trim($bikes[0]) : '';
    $bike2 = isset($bikes[1]) ? trim($bikes[1]) : '';

    // Skip the row if both bikes are empty
    if (empty($bike1) && empty($bike2)) {
        continue;
    }
    $flat_number = $row['flatno'];
    $flatNumber = removeWhitespaceAndSpecialChars($flat_number);
    $flat_part1 = substr($flatNumber, 0, 1);
    $flat_part2 = substr($flatNumber, 1);
    $flatno = $flat_part1 . '-' . $flat_part2;

   $row['flatno']=$flatno;
    $row['bike1'] = $bike1;
    $row['bike2'] = $bike2;
    $data[] = $row;
 
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

// Close the statement and connection
$stmt->close();
$connection->close();
?>
