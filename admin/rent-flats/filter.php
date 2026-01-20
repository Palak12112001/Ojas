<?php
// Include the database connection file
include('../connection.php');
 // Function to remove whitespace and hyphens
 function removeWhitespaceAndHyphens($string)
 {
     // Remove whitespace and hyphens
     $result = str_replace([' ', '-'], '', $string);
     return $result;
 }

// Helper function to format the card number
function formatCardNumber($number) {
    return trim(chunk_split($number, 4, ' '));
}

// Check if the search term is set and not empty
if (isset($_POST['search']) && !empty($_POST['search'])) {
    // Get the search term from POST data
    $search = $_POST['search'];

    // SQL query to filter tbl_flat_holders based on search term
    $query = "SELECT * FROM tbl_rent_flats
              WHERE flatno LIKE ? OR full_name LIKE ? OR phone LIKE ? OR amount LIKE ? OR id_number LIKE ?";
    $stmt = $connection->prepare($query);

    if ($stmt) {
        // Bind parameters and execute the query
        $search_term = "%$search%";
        $stmt->bind_param("sssss", $search_term, $search_term, $search_term, $search_term, $search_term);
        $stmt->execute();

        // Get result set
        $result = $stmt->get_result();

        // Fetch data as associative array
        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                // Format the id_number
                if (isset($row['id_number'])) {
                    $row['id_number'] = formatCardNumber($row['id_number']);
                    $row['full_name']=ucwords($row['full_name']);
                    $flat_number=$row['flatno'];
                    $flatNumber = removeWhitespaceAndHyphens($flat_number);
                    $flat_part1 = substr($flatNumber, 0, 1);
                    $flat_part2 = substr($flatNumber, 1, 4);
                    $flatno = $flat_part1 . '-' . $flat_part2;
                    $row['flatno']=$flatno;
                    $row['amount']=number_format($row['amount']);
                    $row['flat_number']=$flat_number;
                }
                $data[] = $row;
            }
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

        // Close the statement
        $stmt->close();
    } else {
        // Handle statement preparation error
        $response = [
            'status' => 'error',
            'message' => 'Error preparing query: ' . $connection->error
        ];
        echo json_encode($response);
    }

    // Close the database connection
    $connection->close();
} else {
    // Handle missing search term
    $response = [
        'status' => 'error',
        'message' => 'Search term is required'
    ];
    echo json_encode($response);
}
?>
