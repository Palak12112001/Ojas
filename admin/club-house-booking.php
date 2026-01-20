<?php
include("connection.php");
$title = "Club House Booking";
$page = 'Club House Booking';
include("includes/header.php");
include('common-function.php');
?>
<style>

</style>
<div id="content" style="width:100%">
    <div class="content_area">
        <div class="page-title">Club House Booking<a href="club-house-booking-form.php" class="btn-add">Book</a></div>
        <div class="Details">
            <div class="row md-8">
                <div class="col-4 pd-8">
                    <div data-role="2" class="filter-role">
                        <div class="subtitle"><span><?php echo getTotalCurrentRole($connection); ?></span>Current</div>
                    </div>
                </div>
                <div class="col-4 pd-8">
                    <div data-role="0" class="filter-role">
                        <div class="subtitle"><span><?php echo getTotalUpcomingRole($connection); ?></span>Upcoming</div>
                    </div>
                </div>
                <div class="col-4 pd-8">
                    <div data-role="1" class="filter-role">
                        <div class="subtitle"><span><?php echo getTotalPastRole($connection); ?></span>Past</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="search-area">

            <div class="inputblock">
                <input type="text" class="form-control search" placeholder="Search Here..." name="search" value="" id='search'>
                <button type="button" class="btn-search" name="submit" id="clubhouse_booking_filter"><img src="../assets/images/search-icon.png" alt=""></button>
                <button type="button" class="btn-reset" name="reset" onclick="location.reload();"><img src="../assets/images/reset-icon.png" alt=""></button>
            </div>
        </div>
        <ul class="list-ui" id="club_house_book">
            <?php
            // Function to remove whitespace and hyphens
            function removeWhitespaceAndHyphens($string)
            {
                // Remove whitespace and hyphens
                $result = str_replace([' ', '-'], '', $string);
                return $result;
            }

            $query = 'SELECT * FROM `tbl_clubhouse_booking`';
            $result = mysqli_query($connection, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                $flat_number = $row['flat_number'];
                $flatNumber = removeWhitespaceAndHyphens($flat_number);
                $flat_part1 = substr($flatNumber, 0, 1);
                $flat_part2 = substr($flatNumber, 1, 4);
                $flatno = $flat_part1 . '-' . $flat_part2;
                $committee_member = $row['committee_member_name'];
                $full_name = $row['full_name'];
                $number = $row['phone'];
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
                $unit = '';
                if ($row['units'] === '0') {
                    $unit = 'unit-1';
                } elseif ($row['units'] === '1') {
                    $unit = 'unit-2';
                } elseif ($row['units'] === '2') {
                    $unit = 'both';
                }
                $formatted_booking_date = date('d/m/Y', strtotime($row['booking_date']));
                // Format price to 0,000 format
                $formatted_price = number_format($row['price'], 0, ',');
                $part1 = substr($number, 0, 5);
                $part2 = substr($number, 5);
                $phone = $part1 . '&nbsp;' . $part2;
                $total_units = $row['elec_total_units'];
                $total_unitprice = 15 * $total_units;
                $totalprice = $total_unitprice + $row['price'];
                $formatted_unit_price = number_format($total_unitprice);
                $formatted_total_price = number_format($totalprice);
            ?>
                <li>
                    <div class="booking-area">
                        <div class="flat-number"><?php echo $flatno; ?></div>
                        <h2><?php echo ucwords($full_name); ?></h2>
                        <p><?php echo $phone; ?></p>
                        <div class="d-flex">
                            <div class="info"><span>Unit</span><?php echo ucfirst($unit); ?></div>
                            <div class="info"><span>Price</span><?php echo  $formatted_price . '&nbsp;+&nbsp;' . $formatted_unit_price . '&nbsp;=&nbsp;' . $formatted_total_price; ?> Rs</div>
                            <div class="info-full"><span>Date</span><?php echo $formatted_booking_date; ?></div>
                            <div class="info-full"><span>Committee Member</span><?php echo $committee_member; ?></div>
                        </div>
                        <span class="badge <?php echo $bg_color; ?>"><?php echo ucfirst($role); ?></span>
                        <a href="tel:+91<?php echo $number; ?>" class="phone"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.77762 11.9424C2.8296 10.2893 2.37185 8.93948 2.09584 7.57121C1.68762 5.54758 2.62181 3.57081 4.16938 2.30947C4.82345 1.77638 5.57323 1.95852 5.96 2.6524L6.83318 4.21891C7.52529 5.46057 7.87134 6.08139 7.8027 6.73959C7.73407 7.39779 7.26737 7.93386 6.33397 9.00601L3.77762 11.9424ZM3.77762 11.9424C5.69651 15.2883 8.70784 18.3013 12.0576 20.2224M12.0576 20.2224C13.7107 21.1704 15.0605 21.6282 16.4288 21.9042C18.4524 22.3124 20.4292 21.3782 21.6905 19.8306C22.2236 19.1766 22.0415 18.4268 21.3476 18.04L19.7811 17.1668C18.5394 16.4747 17.9186 16.1287 17.2604 16.1973C16.6022 16.2659 16.0661 16.7326 14.994 17.666L12.0576 20.2224Z" stroke="#fff" stroke-width="1.5" stroke-linejoin="round" />
                                <path d="M14 6.83185C15.4232 7.43624 16.5638 8.57677 17.1682 10M14.654 2C18.1912 3.02076 20.9791 5.80852 22 9.34563" stroke="#fff" stroke-width="1.5" stroke-linecap="round" />
                            </svg></a>
                        <?php if ($isAdmin) : ?>
                            <div class="action">
                                <a href="club-house-booking-form.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn-icon"><img src="../assets/images/edit-icon.png" alt="Edit"></a>
                                <a href="#" data-href="clubhouse/delete.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn-icon delete-clubhouse-booking"><img src="../assets/images/delete-icon.png" alt="Delete"></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </li>
            <?php
            }
            ?>
        </ul>

    </div>
</div>
<?php include('includes/footer.php'); ?>
