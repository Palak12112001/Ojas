<?php
include('../connection.php');

$wing = $_POST['wing'];
$type = $_POST['type'];

// Function to remove whitespace and special characters except alphanumeric
function removeWhitespaceAndSpecialChars($string)
{
    // Remove whitespace and special characters except alphanumeric
    return preg_replace('/[^a-zA-Z0-9]/', '', $string);
}

if ($type === 'flats') {
    $query = "SELECT * FROM `tbl_flat_holders` WHERE LEFT(flatno, 1) = '$wing' ORDER BY CAST(SUBSTRING(flatno, 2) AS UNSIGNED) ASC";
} elseif ($type === 'bikes') {
    $query = "SELECT fh.full_name, fh.phone, v.flat_number, GROUP_CONCAT(v.bike) as bikes
    FROM tbl_flat_holders fh
    JOIN tbl_vehicles v ON fh.flatno = v.flat_number
    WHERE v.flag = '0' and LEFT(v.flat_number, 1) = '$wing'
    GROUP BY v.flat_number
    HAVING COUNT(v.bike) = 2
    ORDER BY CAST(SUBSTRING(v.flat_number, 2) AS UNSIGNED) ASC";
} elseif ($type === 'cars') {
    $query = "SELECT fh.full_name, fh.phone, v.flat_number, v.car
    FROM tbl_flat_holders fh
    JOIN tbl_vehicles v ON fh.flatno = v.flat_number
    WHERE v.flag = '1' and LEFT(v.flat_number, 1) = '$wing' ORDER BY CAST(SUBSTRING(v.flat_number, 2) AS UNSIGNED) ASC";
} else {
    echo "Invalid Type";
    exit;
}

$result = mysqli_query($connection, $query);

// Initialize an empty response array
$response = [];

if ($type === 'flats') {
    while ($row = mysqli_fetch_assoc($result)) {
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

        $response[] = '<li>
                        <div class="flat-block">
                            <div class="flat-number">' . $flatno . '</div>
                            <h2>' . ucwords($row['full_name']) . '</h2>
                            <p>' . $phone . '</p>
                            <a href="tel:+91' . $number . '" class="phone">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3.77762 11.9424C2.8296 10.2893 2.37185 8.93948 2.09584 7.57121C1.68762 5.54758 2.62181 3.57081 4.16938 2.30947C4.82345 1.77638 5.57323 1.95852 5.96 2.6524L6.83318 4.21891C7.52529 5.46057 7.87134 6.08139 7.8027 6.73959C7.73407 7.39779 7.26737 7.93386 6.33397 9.00601L3.77762 11.9424ZM3.77762 11.9424C5.69651 15.2883 8.70784 18.3013 12.0576 20.2224M12.0576 20.2224C13.7107 21.1704 15.0605 21.6282 16.4288 21.9042C18.4524 22.3124 20.4292 21.3782 21.6905 19.8306C22.2236 19.1766 22.0415 18.4268 21.3476 18.04L19.7811 17.1668C18.5394 16.4747 17.9186 16.1287 17.2604 16.1973C16.6022 16.2659 16.0661 16.7326 14.994 17.666L12.0576 20.2224Z" stroke="#fff" stroke-width="1.5" stroke-linejoin="round"/>
                                    <path d="M14 6.83185C15.4232 7.43624 16.5638 8.57677 17.1682 10M14.654 2C18.1912 3.02076 20.9791 5.80852 22 9.34563" stroke="#fff" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                            </a>
                            <div class="action">
                                <a href="form-flat-holder.php?flatno=' . htmlspecialchars($row['flatno']) . '" class="btn-icon"><img src="../assets/images/edit-icon.png" alt="Edit"></a>
                                <a href="#" data-href="flats/delete-flat-holder.php?flatno=' . $flat_number . '" class="btn-icon delete"><img src="../assets/images/delete-icon.png" alt="Delete"></a>
                            </div>
                        </div>
                    </li>';
    }
} elseif ($type === 'bikes') {
    while ($row = mysqli_fetch_assoc($result)) {
        // Format flat number and phone number
        $flat_number = $row['flat_number'];
        $flatNumber = removeWhitespaceAndSpecialChars($flat_number);
        $flat_part1 = substr($flatNumber, 0, 1);
        $flat_part2 = substr($flatNumber, 1);
        $flatno = $flat_part1 . '-' . $flat_part2;
    
        $number = $row['phone'];
        $part1 = substr($number, 0, 5);
        $part2 = substr($number, 5);
        $phone = $part1 . ' ' . $part2;
    
        $bikes = explode(',', $row['bikes']);

        // // Add bike information
        if (!empty($bikes[0]) && !empty($bikes[1])) {
            $bike = '<div class="bike"><img src="../assets/images/bike.svg" alt="">' . htmlspecialchars($bikes[0]) . '</div><div class="bike"><img src="../assets/images/bike.svg" alt="">' . htmlspecialchars($bikes[1]) . '</div>';
        } elseif (!empty($bikes[0])) {
            $bike = '<div class="bike"><img src="../assets/images/bike.svg" alt="">' . htmlspecialchars($bikes[0]) . '</div>';
        } elseif (!empty($bikes[1])) {
            $bike = '<div class="bike"><img src="../assets/images/bike.svg" alt="">' . htmlspecialchars($bikes[1]) . '</div>';
        }
    
$response[]='<li>
                        <div class="flat-block bike-list">
                            <div class="flat-number">'.  $flatno .'</div>
                            <h2>' . ucwords($row['full_name']) . '</h2>
                            <p>'. $phone .'</p>
                            '.$bike.'
                            <a href="tel:+919428261279" class="phone">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3.77762 11.9424C2.8296 10.2893 2.37185 8.93948 2.09584 7.57121C1.68762 5.54758 2.62181 3.57081 4.16938 2.30947C4.82345 1.77638 5.57323 1.95852 5.96 2.6524L6.83318 4.21891C7.52529 5.46057 7.87134 6.08139 7.8027 6.73959C7.73407 7.39779 7.26737 7.93386 6.33397 9.00601L3.77762 11.9424ZM3.77762 11.9424C5.69651 15.2883 8.70784 18.3013 12.0576 20.2224M12.0576 20.2224C13.7107 21.1704 15.0605 21.6282 16.4288 21.9042C18.4524 22.3124 20.4292 21.3782 21.6905 19.8306C22.2236 19.1766 22.0415 18.4268 21.3476 18.04L19.7811 17.1668C18.5394 16.4747 17.9186 16.1287 17.2604 16.1973C16.6022 16.2659 16.0661 16.7326 14.994 17.666L12.0576 20.2224Z" stroke="#fff" stroke-width="1.5" stroke-linejoin="round"></path>
                                    <path d="M14 6.83185C15.4232 7.43624 16.5638 8.57677 17.1682 10M14.654 2C18.1912 3.02076 20.9791 5.80852 22 9.34563" stroke="#fff" stroke-width="1.5" stroke-linecap="round"></path>
                                </svg>
                            </a>
                        </div>
                    </li>';
               
               
    }
    
} elseif ($type === 'cars') {
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['car'] !== '') {
            // Format flat number and phone number
            $flat_number = $row['flat_number'];
            $flatNumber = removeWhitespaceAndSpecialChars($flat_number);
            $flat_part1 = substr($flatNumber, 0, 1);
            $flat_part2 = substr($flatNumber, 1);
            $flatno = $flat_part1 . '-' . $flat_part2;

            $number = $row['phone'];
            $part1 = substr($number, 0, 5);
            $part2 = substr($number, 5);
            $phone = $part1 . ' ' . $part2;

            $response[] = '<li>
                            <div class="flat-block car-list">
                                <div class="flat-number">' . $flatno . '</div>
                                <h2>' . ucwords($row['full_name']) . '</h2>
                                <p>' . $phone . '</p>
                                <div class="car"><img src="../assets/images/car.svg" alt="">' . htmlspecialchars($row['car']) . '</div>
                                <a href="tel:+91' . $number . '" class="phone">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3.77762 11.9424C2.8296 10.2893 2.37185 8.93948 2.09584 7.57121C1.68762 5.54758 2.62181 3.57081 4.16938 2.30947C4.82345 1.77638 5.57323 1.95852 5.96 2.6524L6.83318 4.21891C7.52529 5.46057 7.87134 6.08139 7.8027 6.73959C7.73407 7.39779 7.26737 7.93386 6.33397 9.00601L3.77762 11.9424ZM3.77762 11.9424C5.69651 15.2883 8.70784 18.3013 12.0576 20.2224M12.0576 20.2224C13.7107 21.1704 15.0605 21.6282 16.4288 21.9042C18.4524 22.3124 20.4292 21.3782 21.6905 19.8306C22.2236 19.1766 22.0415 18.4268 21.3476 18.04L19.7811 17.1668C18.5394 16.4747 17.9186 16.1287 17.2604 16.1973C16.6022 16.2659 16.0661 16.7326 14.994 17.666L12.0576 20.2224Z" stroke="#fff" stroke-width="1.5" stroke-linejoin="round"/>
                                        <path d="M14 6.83185C15.4232 7.43624 16.5638 8.57677 17.1682 10M14.654 2C18.1912 3.02076 20.9791 5.80852 22 9.34563" stroke="#fff" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                </a>
                            </div>
                        </li>';
        }
    }
}

// Return the response as JSON
echo json_encode($response);

// Close statement and database connection
mysqli_close($connection);
?>
