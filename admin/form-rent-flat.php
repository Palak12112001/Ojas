<?php
// Include the file for establishing database connection
include('connection.php');
$page = 'Rent Flats';
// Initialize variables for form fields
$full_name = '';
$phone = '';
$flatno = '';
$id_number = '';
$id_image = ''; // This will hold the filename of the uploaded image
$charge_paid = '';
$charge_amount = '';

// Check if flat number is provided in the URL for edit mode
if (isset($_GET['flatno'])) {
    // Sanitize input
    $flatno = mysqli_real_escape_string($connection, $_GET['flatno']);

    // Query to fetch data from tbl_rent_flats
    $query = "SELECT * FROM tbl_rent_flats WHERE flatno = '$flatno'";
    $result = mysqli_query($connection, $query);

    // Check if the query executed successfully and returned at least one row
    if ($result && mysqli_num_rows($result) > 0) {
        // Fetch the row
        $row = mysqli_fetch_assoc($result);

        // Assign values to variables for form fields
        $full_name = htmlspecialchars($row['full_name']);
        $phone = htmlspecialchars($row['phone']);
        $id_number = htmlspecialchars($row['id_number']);
        $id_image = htmlspecialchars($row['id_image']); // Assuming this is the filename stored in the database
        $charge_paid = htmlspecialchars($row['charge_paid']);
        $charge_amount = htmlspecialchars($row['amount']);
    }
}

// Determine the page title based on whether flatno is set in URL
$title = isset($_GET['flatno']) ? "Edit Rent Flat" : "Add Rent Flat";

// Include the header file
include("includes/header.php");
?>

<!-- Page Content Holder -->
<div id="content" style="width:100%">
    <div class="content_area">
        <div class="page-title"><?php echo $title; ?></div>
        <div class="form-area">
            <?php if (isset($_GET['flatno'])) : ?>
                <!-- Edit Form -->
                <form class="editRentFlatForm" method="post" action="rent-flats/update.php" enctype="multipart/form-data">
                    <input type="hidden" name="flatno" value="<?php echo $flatno; ?>">
                <?php else : ?>
                    <!-- Add Form -->
                    <form class="addRentFlatForm" method="post" action="rent-flats/insert.php" enctype="multipart/form-data">
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="inputlabel">Full Name</label>
                                <input type="text" class="form-control" placeholder="Full Name" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="inputlabel">Phone Number</label>
                                <input type="text" class="form-control" placeholder="Phone Number" name="phone" value="<?php echo htmlspecialchars($phone); ?>" maxlength="10" oninput="this.value = this.value.replace(/\D/g, '');">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="inputlabel">Flat Number</label>
                                <input type="text" class="form-control" id="flatnoInput" placeholder="Flat Number" name="flatno" value="<?php echo htmlspecialchars($flatno); ?>" <?php echo isset($_GET['flatno']) ? 'readonly' : ''; ?> oninput="this.value = this.value.toUpperCase();">
                                <span class="text-danger small" id="flatno-error"></span>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="inputlabel">Aadhar Card Number</label>
                                <input type="text" class="form-control" placeholder="Aadhar Card Number" name="id_number" value="<?php echo htmlspecialchars($id_number); ?>" maxlength="12" oninput="this.value = this.value.replace(/\D/g, '');">
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-12" id="chargePaidCol">
                                    <div class="mb-3">
                                        <label class="inputlabel">Charge</label>
                                        <select class="form-select" name="charge_paid" id="chargePaidSelect">
                                            <option value="0" <?php echo $charge_paid == '0' ? 'selected' : ''; ?>>No</option>
                                            <option value="1" <?php echo $charge_paid == '1' ? 'selected' : ''; ?>>Yes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <div class="mb-3" id="chargeAmountField" style="<?php echo $charge_paid == '1' ? '' : 'display: none;'; ?>">
                                        <label class="inputlabel">Charge Amount</label>
                                        <input type="text" class="form-control" placeholder="Charge Amount" name="charge_amount" value="<?php echo htmlspecialchars($charge_amount); ?>" oninput="this.value = this.value.replace(/\D/g, '');">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="inputlabel">Aadhar Card Photo</label>
                                <input type="file" class="form-control" name="id_image" id="id_image">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <?php if (!empty($id_image)) : ?>
                                <div class="img-block">
                                    <img src="../assets/images/id_card_images/<?php echo htmlspecialchars($id_image); ?>" alt="Aadhar Card Preview" class="img-thumbnail p-0 border-0" >
                                </div>
                            <?php else : ?>
                                <div class="img-block">
                                    <img src="../assets/images/default_id_card.png" alt="Aadhar Card Preview" class="img-thumbnail p-0 border-0" >
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-lg-12">
                            <button type="submit" class="btn-submit" name="submit">Submit</button>
                            <button type="button" class="btn-submit bg-danger border-0" name="reset" onclick="window.location.href='rent-flats.php';" >Cancel</button>
                        </div>
                    </div>
                    </form>

        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>