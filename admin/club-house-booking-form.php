<?php
// Include the file for establishing database connection
include('connection.php');
$page = 'Club House Booking';
$title = isset($_GET['id']) ? "Edit Flat Holder" : "Add Flat Holder";

// Include the header file
include("includes/header.php");

// Initialize variables for form field values
$flat_number = '';
$full_name = '';
$phone = '';
$units = '';
$booking_date = '';
$price = '';
$card_number = '';
$id_image = '';
$committee_member = '';
$unit1_old = '';
$unit2_old = '';
$unit1_end = '';
$unit2_end = '';
$totalprice = '';
$totalUnits = '';

// Check if editing existing record
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch data from database based on flatno
    $query = "SELECT * FROM tbl_clubhouse_booking WHERE id = $id";
    $result = mysqli_query($connection, $query);


    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $flat_number = $row['flat_number'];

        $full_name = $row['full_name'];
        $phone = $row['phone'];
        $units = $row['units'];
        $booking_date = $row['booking_date'];
        $price = $row['price'];
        $card_number = $row['card_number'];
        $id_image = $row['card_image'];
        $committee_member_id = $row['committee_member_id'];
        $committee_member = $row['full_name'];
        $elec_unit_data=$row['elec_unit_data'];
        if($elec_unit_data != null){
            $unit_data = json_decode($elec_unit_data);
            $unit1_old = $unit_data->unit1_old_unit;
            $unit2_old = $unit_data->unit2_old_unit;
            $unit1_end = $unit_data->unit1_end_unit;
            $unit2_end = $unit_data->unit2_end_unit;
            $unit1_totalUnits=$unit_data->unit1_totalUnits;
            $unit2_totalUnits=$unit_data->unit2_totalUnits;
        }
     
    } else {
        echo "id  not found.";
        exit;
    }
}
?>
<!-- Page Content Holder -->
<div id="content" style="width:100%">
    <div class="content_area">
        <div class="page-title"><?php echo isset($_GET['id']) ? 'Edit Booked ClubHouse' : 'Booking Club House'; ?></div>
        <div class="form-area">
            <?php if (isset($_GET['id'])) : ?>
                <!-- Edit Form -->
                <form class="editBookedClubHouseForm" method="post" action="clubhouse/edit.php">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                <?php else : ?>
                    <!-- Add Form -->
                    <form class="addBookedClubHouseForm" method="post" action="clubhouse/insert.php">
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-lg-4">
                            <?php if (isset($_GET['id'])) : ?>

                                <div class="mb-3">
                                    <input type="text" class="form-control bg-transparent flat_number" readonly placeholder="flat_number" name="flat_number" value="<?php echo $flat_number; ?>">
                                    <span class="text-danger small" id="flat_number-error"></span>
                                </div>
                            <?php else : ?>
                                <div class="mb-3">
                                    <label class="inputlabel">Flat Number</label>
                                    <select name="flat_number" id="flat_number" class="d-inline w-100 form-select bg-transparent flat_number py-2 valid" aria-invalid="false">
                                        <option value="">Select Flat Number</option>
                                        <?php
                                        $query = "SELECT id, flatno FROM tbl_flat_holders";
                                        $result = mysqli_query($connection, $query);
                                        if ($result) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $flatno_option = htmlspecialchars($row['flatno']);
                                                $selected = ($flat_number == $flatno_option) ? 'selected' : '';
                                                echo "<option value='{$flatno_option}' class='text-dark' {$selected}>{$flatno_option}</option>";
                                            }
                                            mysqli_free_result($result);
                                        } else {
                                            echo "Error fetching flat numbers: " . mysqli_error($connection);
                                        }
                                        ?>
                                    </select>
                                    <span class="text-danger small" id="flat_number-error"></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="inputlabel">Full Name</label>
                                <input type="text" class="form-control bg-transparent full_name" placeholder="Full Name" name="full_name" value="<?php echo $full_name; ?>" <?php echo isset($_GET['id']) ? 'readonly' : ''; ?>>
                                <span class="text-danger small" id="full_name-error"></span>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="inputlabel">Phone Number</label>
                                <input type="text" class="form-control bg-transparent phone" placeholder="Phone Number" name="phone" value="<?php echo $phone; ?>" maxlength="10" oninput="this.value = this.value.replace(/\D/g, '');" <?php echo isset($_GET['id']) ? 'readonly' : ''; ?>>
                                <span class="text-danger small" id="phone-error"></span>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="inputlabel">Unit</label>
                                <select name="units" class="d-inline w-100 form-select bg-transparent units py-2">
                                    <option value="" class="text-dark">Select Units</option>
                                    <option value="0" class="text-dark" <?php if ($units == '0') echo 'selected'; ?>>Unit-1</option>
                                    <option value="1" class="text-dark" <?php if ($units == '1') echo 'selected'; ?>>Unit-2</option>
                                    <option value="2" class="text-dark" <?php if ($units == '2') echo 'selected'; ?>>Both</option>
                                </select>
                                <span class="text-danger small" id="units-error"></span>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="inputlabel">Booking Date</label>
                                <input type="date" class="form-control bg-transparent booking_date" placeholder="Booking Date" name="booking_date" value="<?php echo $booking_date; ?>" min="<?php echo date('Y-m-d'); ?>">
                                <span class="text-danger" id="booking_date-error"></span>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="inputlabel">Price</label>
                                <input type="text" class="form-control bg-transparent price" placeholder="Price" name="price" value="<?php echo $price; ?>" oninput="this.value = this.value.replace(/\D/g, '');">
                                <span class="text-danger small" id="price-error"></span>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="inputlabel">Aadhar Card Photo</label>
                                <input type="file" class="form-control" name="id_image" id="id_image">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="inputlabel">Aadhar Card Number</label>
                                <input type="text" class="form-control bg-transparent card_number" placeholder="Adhar card number" name="card_number" maxlength="12" value="<?php echo $card_number; ?>" oninput="this.value = this.value.replace(/\D/g, '');">
                                <span class="text-danger small" id="card_number-error"></span>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="inputlabel">Committee Member</label>
                                <select name="committee_member" class="d-inline w-100 form-select bg-transparent committee-member py-2">
                                    <option value="" class="text-dark">Select Committee Member</option>
                                    <?php
                                    $query = "SELECT id,full_name FROM `tbl_committee_members`";
                                    $result = mysqli_query($connection, $query);
                                    if ($result) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $committee_id = $row['id'];
                                            $committee_name = htmlspecialchars($row['full_name']);
                                            $selected = ($committee_member_id == $committee_id) ? 'selected' : '';
                                            echo "<option value='{$committee_id}' class='text-dark' {$selected}>{$committee_name}</option>";
                                        }
                                        mysqli_free_result($result);
                                    } else {
                                        echo "Error fetching committee members: " . mysqli_error($connection);
                                    }
                                    ?>
                                </select>

                            </div>
                        </div>

                        <div class="col-lg-4">
                            <?php if (!empty($id_image)) : ?>
                                <div class="img-block">
                                    <img src="../assets/images/id_card_images/club_house_booking/<?php echo htmlspecialchars($id_image); ?>" alt="Aadhar Card Preview" class="img-thumbnail p-0 border-0">
                                </div>
                            <?php else : ?>
                                <div class="img-block">
                                    <img src="../assets/images/default_id_card.png" alt="Aadhar Card Preview" class="img-thumbnail p-0 border-0">
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-8 <?php  echo (isset($_GET['id'])) ? '' : 'd-none'; ?>">

                            <div class="row  <?php if ($units == 1) echo 'd-none'; ?>">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="inputlabel">Unit1 old unit</label>
                                        <input type="text" class="form-control" name="unit1_old" id="unit1_old" value="<?php echo $unit1_old;?>">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="inputlabel">Unit1 End units</label>
                                        <input type="text" class="form-control" name="unit1_end" id="unit1_end" value="<?php echo $unit1_end;?>">
                                    </div>
                                </div>
                            </div>


                            <div class="row <?php if ($units == 0) echo 'd-none'; ?>">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="inputlabel">Unit2 old unit</label>
                                        <input type="text" class="form-control" name="unit2_old" id="unit2_old" value="<?php echo $unit2_old;?>">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="inputlabel">Unit2 End units</label>
                                        <input type="text" class="form-control" name="unit2_end" id="unit2_end" value="<?php echo $unit2_end;?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-end ">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <p><strong>Unit1 Total Units:&nbsp;&nbsp;<span id='unit1_difference'><?php echo $unit1_totalUnits;?></span></strong></p>
                                        <p><strong>Uni2 Total Units:&nbsp;&nbsp;<span id='unit2_difference'><?php echo $unit2_totalUnits;?></span></strong></p>
                                        <p><strong>Total Units:&nbsp;&nbsp;<span id='total_units'>0</span></strong></p>

                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <p><strong>Total Units price:&nbsp;&nbsp;<span id='total_units_price'>0</span></strong></p>
                                        <p><strong>price:&nbsp;&nbsp;<span id='price'></span></strong></p>

                                        <p><strong>Total price:&nbsp;&nbsp;<span id='total_price'>0</span></strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <button type="submit" class="btn-submit" name="submit">Submit</button>
                            <button type="button" class="btn-submit bg-danger border-0" name="reset" onclick="window.location.href='club-house-booking.php';">Cancel</button>
                        </div>
                    </div>
                    </form>
        </div>
    </div>
</div>

<?php
// Include the footer file
include('includes/footer.php');
?>
<script>
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function updateTotals() {
        var price_val = $('.price').val();
        $('#price').text(formatNumber(price_val));
        // Calculate and display unit 1 difference
        var unit1_oldVal = parseFloat($('#unit1_old').val()) || 0;
        var unit1_endVal = parseFloat($('#unit1_end').val()) || 0;
        var unit1_difference = Math.abs(unit1_endVal - unit1_oldVal);
        $('#unit1_difference').text(unit1_difference);

        // Calculate and display unit 2 difference
        var unit2_oldVal = parseFloat($('#unit2_old').val()) || 0;
        var unit2_endVal = parseFloat($('#unit2_end').val()) || 0;
        var unit2_difference = Math.abs(unit2_endVal - unit2_oldVal);
        $('#unit2_difference').text(unit2_difference);

        // Calculate total difference
        var total_difference = unit1_difference + unit2_difference;
        $('#total_units').text(total_difference);

        // Calculate total difference price
        var total_difference_price = 15 * total_difference;
        $('#total_units_price').text(formatNumber(total_difference_price));

        // Calculate total price
        var price = parseFloat($('#price').text().replace(/,/g, '')) || 0; // Remove commas before parsing
        var total_price = total_difference_price + price;
        $('#total_price').text(formatNumber(total_price));
    }

    // Event listeners for input changes
    $('#unit1_old, #unit1_end, #unit2_old, #unit2_end').on('input', updateTotals);
    $('.price').on('input', updateTotals);
    // Initial calculation
    updateTotals();
</script>