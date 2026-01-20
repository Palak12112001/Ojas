<?php
include('connection.php');
$title = "Rent Flats List";
$page = 'Rent Flats';
include("includes/header.php");
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- Page Content Holder -->
<div id="content" style="width:100%">
    <div class="content_area">
        <div class="page-title">Rent Flats List <a href="form-rent-flat.php" class="btn-add">Add</a></div>
        <div class="search-area">

            <div class="inputblock">
                <input type="text" class="form-control search" placeholder="Search Here..." name="search" value="" class="search">
                <button type="button" class="btn-search" name="submit" id="rent_flat_filter"><img src="../assets/images/search-icon.png" alt=""></button>
                <button type="button" class="btn-reset" name="reset" onclick="location.reload();"><img src="../assets/images/reset-icon.png" alt=""></button>
            </div>

        </div>


        <ul class="list-ui" id="rent_flats_results">
            <?php
            // Function to remove whitespace and hyphens
            function removeWhitespaceAndHyphens($string)
            {
                // Remove whitespace and hyphens
                $result = str_replace([' ', '-'], '', $string);
                return $result;
            }

            // Query to fetch flat holders
            $query = "SELECT * FROM `tbl_rent_flats`";
            $result = mysqli_query($connection, $query);

            if ($result) {

                while ($row = mysqli_fetch_assoc($result)) {
                    $flat_number = $row['flatno'];
                    $flatNumber = removeWhitespaceAndHyphens($flat_number);
                    $flat_part1 = substr($flatNumber, 0, 1);
                    $flat_part2 = substr($flatNumber, 1, 4);
                    $flatno = $flat_part1 . '-' . $flat_part2;
                    $number = $row['phone'];
                    $part1 = substr($number, 0, 5);
                    $part2 = substr($number, 5);
                    $phone = $part1 . '&nbsp;' . $part2;
                    $id_number = $row['id_number'];
                    $part1_id = substr($id_number, 0, 4);
                    $part2_id = substr($id_number, 4, 4);
                    $part3_id = substr($id_number, 8);
                    $card_number = $part1_id . '&nbsp;' . $part2_id . '&nbsp;' . $part3_id;
            ?>
                    <li>

                        <div class="rent-flat-block">
                            <div class="flat-number"><?php echo $flatno; ?></div>
                            <h2><?php echo ucwords($row['full_name']); ?></h2>
                            <p><?php echo $phone; ?></p>
                            <div class="d-flex">
                                <div class="info"><span>Amount</span> <?php echo isset($row['amount']) && $row['amount'] !== '' ? number_format($row['amount']) : '00'; ?>.Rs</div>
                                <div class="info"><span>Aadhar Card</span> <?php echo $card_number; ?></div>
                            </div>
                            <a href="tel:+91<?php echo $number; ?>" class="phone"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3.77762 11.9424C2.8296 10.2893 2.37185 8.93948 2.09584 7.57121C1.68762 5.54758 2.62181 3.57081 4.16938 2.30947C4.82345 1.77638 5.57323 1.95852 5.96 2.6524L6.83318 4.21891C7.52529 5.46057 7.87134 6.08139 7.8027 6.73959C7.73407 7.39779 7.26737 7.93386 6.33397 9.00601L3.77762 11.9424ZM3.77762 11.9424C5.69651 15.2883 8.70784 18.3013 12.0576 20.2224M12.0576 20.2224C13.7107 21.1704 15.0605 21.6282 16.4288 21.9042C18.4524 22.3124 20.4292 21.3782 21.6905 19.8306C22.2236 19.1766 22.0415 18.4268 21.3476 18.04L19.7811 17.1668C18.5394 16.4747 17.9186 16.1287 17.2604 16.1973C16.6022 16.2659 16.0661 16.7326 14.994 17.666L12.0576 20.2224Z" stroke="#fff" stroke-width="1.5" stroke-linejoin="round" />
                                    <path d="M14 6.83185C15.4232 7.43624 16.5638 8.57677 17.1682 10M14.654 2C18.1912 3.02076 20.9791 5.80852 22 9.34563" stroke="#fff" stroke-width="1.5" stroke-linecap="round" />
                                </svg></a>
                            <div class="action">
                                <a class="btn-icon" href="form-rent-flat.php?flatno=<?php echo htmlspecialchars($row['flatno']); ?>"><img src="../assets/images/edit-icon.png" alt=""></a>
                                <a href="#" data-href="rent-flats/delete-rent-flat.php?flatno=<?php echo htmlspecialchars($row['flatno']); ?>" class="delete-rent-flat"><img src="../assets/images/delete-icon.png" alt=""></a>
                            </div>
                    </li>
            <?php

                }
            } else {
                echo "Error: " . mysqli_error($connection);
            }

            // Close the connection
            mysqli_close($connection);
            ?>
        </ul>


    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php include('includes/footer.php'); ?>