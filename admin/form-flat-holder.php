<?php
// Include the file for establishing database connection
include('connection.php');
$page = 'Flat Holder';
if (isset($_GET['flatno'])) {
    // Set the page title
    $title = "Edit Flat Holder";
} else {
    // Set the page title
    $title = "ADD Flat Holder";
}

// Include the header file
include("includes/header.php");

// Initialize variables for form fields
$full_name = '';
$phone = '';
$flatno = '';
$bike1 = '';
$bike2 = '';
$car = '';
$flag_bike = '0';
$flag_car = '1';
$bike1_id = '';
$bike2_id = '';
$car_id = '';
$bike1_image = '';
$bike2_image = '';
$car_image = '';

// Check if flat number is provided in the URL
if (isset($_GET['flatno'])) {
    // Sanitize input
    $flatno = mysqli_real_escape_string($connection, $_GET['flatno']);

    // Query to fetch data from tbl_flat_holders
    $query = "SELECT * FROM tbl_flat_holders WHERE flatno = '$flatno'";
    $result = mysqli_query($connection, $query);

    // Check if the query executed successfully and returned at least one row
    if ($result && mysqli_num_rows($result) > 0) {
        // Fetch the row
        $row = mysqli_fetch_assoc($result);
        $full_name = $row['full_name'];
        $phone = $row['phone'];
    }

    // Fetch data from tbl_vehicles for bikes
    $query_bikes = "SELECT id, bike, image FROM tbl_vehicles WHERE flat_number = '$flatno' AND flag = '0'";
    $result_bikes = mysqli_query($connection, $query_bikes);

    // Iterate through the result set and assign bike numbers
    $bikes = mysqli_fetch_all($result_bikes, MYSQLI_ASSOC);
    if (count($bikes) > 0) {
        $bike1 = $bikes[0]['bike'];
        $bike1_id = $bikes[0]['id'];
        $bike1_image = $bikes[0]['image'];
    }
    if (count($bikes) > 1) {
        $bike2 = $bikes[1]['bike'];
        $bike2_id = $bikes[1]['id'];
        $bike2_image = $bikes[1]['image'];
    }

    // Fetch data from tbl_vehicles for car
    $query_car = "SELECT id, car, image FROM tbl_vehicles WHERE flat_number = '$flatno' AND flag = '1'";
    $result_car = mysqli_query($connection, $query_car);

    // Check if there is at least one car
    if (mysqli_num_rows($result_car) > 0) {
        $car_row = mysqli_fetch_assoc($result_car);
        $car = $car_row['car'];
        $car_id = $car_row['id'];
        $car_image = $car_row['image'];
    }
}
?>
<!-- Page Content Holder -->
<div id="content" style="width:100%">
    <div class="content_area">
        <div class="page-title"><?php echo isset($_GET['flatno']) ? 'Edit Flat Holder' : 'Add Flat Holder'; ?></div>
        <div class="form-area">
            <?php if (isset($_GET['flatno'])) : ?>
                <!-- Edit Form -->
                <form class="editFlatHolderForm" method="post" action="flats/update.php" enctype="multipart/form-data">
                    <input type="hidden" name="flatno" value="<?php echo $flatno; ?>">
                <?php else : ?>
                    <!-- Add Form -->
                    <form class="addFlatHolderForm" method="post" action="flats/insert.php" enctype="multipart/form-data">
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="inputlabel">Full Name</label>
                                <input type="text" class="form-control bg-transparent" placeholder="Full Name" name="full_name" value="<?php echo $full_name; ?>">
                                <span class="text-danger small" id="full_name-error"></span>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="inputlabel">Phone Number</label>
                                <input type="text" class="form-control bg-transparent" placeholder="Phone Number" name="phone" value="<?php echo $phone; ?>" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                <span class="text-danger small" id="phone-error"></span>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="inputlabel">Flat Number</label>
                                <input type="text" class="form-control bg-transparent flatno" placeholder="Flat Number" name="flatno" value="<?php echo $flatno; ?>" <?php echo isset($_GET['flatno']) ? 'readonly' : ''; ?> oninput="this.value = this.value.toUpperCase();">
                                <span class="text-danger small" id="flatno-error"></span>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="inputlabel">Bike Number 1</label>
                                <input type="text" id="bike1" class="form-control bg-transparent vehical" placeholder="Bike Number 1" name="bike1" id="bike1" value="<?php echo $bike1; ?>" data-id='<?php echo $bike1_id; ?>' data-type='<?php echo $flag_bike; ?>' oninput="this.value = this.value.toUpperCase();">
                                <span class="text-danger small" id="bike1-error"></span>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="inputlabel">Bike Number 2</label>
                                <input type="text" id="bike2" class="form-control bg-transparent vehical" placeholder="Bike Number 2" id="bike2" name="bike2" value="<?php echo $bike2; ?>" data-id='<?php echo $bike2_id; ?>' data-type='<?php echo $flag_bike; ?>' oninput="this.value = this.value.toUpperCase();">
                                <span class="text-danger small" id="bike2-error"></span>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="inputlabel">Car Number</label>
                                <input type="text" class="form-control bg-transparent vehical" placeholder="Car Number" id="car" name="car" value="<?php echo $car; ?>" data-id='<?php echo $car_id; ?>' data-type='<?php echo $flag_car; ?>' oninput="this.value = this.value.toUpperCase();">
                                <span class="text-danger small" id="car-error"></span>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="inputlabel">Bike1 Image</label>
                                <input type="file" class="form-control bg-transparent vehical_image bike1" data-image='bike1_image' name="bike1_image" value="<?php echo $bike1_image; ?>" data-id='<?php echo $bike1_id; ?>' data-type='<?php echo $flag_bike; ?>' <?php echo ($bike1 == '') ? 'disabled' : '' ?>>
                                <?php if ($bike1_image == '') { ?>
                                    <img src="../assets/images/default.jpg" alt="" id='bike1_image' width="150px" height="150px">
                                <?php } else { ?>
                                    <img src="../assets/images/vehicalImage/<?php echo $bike1_image ?>" alt="" id='bike1_image' width="150px" height="150px">
                                <?php } ?>
                                <span><img src="../assets/images/cross.png" alt="" class="removeImg" data-img="bike1_image" style="background-color: red;padding: 2px;border-radius: 50%;"></span>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="inputlabel">Bike2 Image</label>
                                <input type="file" class="form-control bg-transparent vehical_image bike2" data-image='bike2_image' name="bike2_image" value="<?php echo $bike2_image; ?>" data-id='<?php echo $bike2_id; ?>' data-type='<?php echo $flag_bike; ?>' <?php echo ($bike2 == '') ? 'disabled' : '' ?>>
                                <?php if ($bike2_image == '') { ?>
                                    <img src="../assets/images/default.jpg" alt="" id='bike2_image' width="150px" height="150px">
                                <?php } else { ?>
                                    <img src="../assets/images/vehicalImage/<?php echo $bike2_image ?>" alt="" id='bike2_image' width="150px" height="150px">
                                <?php } ?>
                                <span><img src="../assets/images/cross.png" alt="" class="removeImg" data-img="bike2_image" style="background-color: red;padding: 2px;border-radius: 50%;"></span>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="inputlabel">Car Image</label>
                                <input type="file" class="form-control bg-transparent vehical_image car" data-image='car_image' name="car_image" value="<?php echo $car_image; ?>" data-id='<?php echo $car_id; ?>' data-type='<?php echo $flag_car; ?>' <?php echo ($car == '') ? 'disabled' : '' ?>>

                                <?php if ($car_image == '') { ?>
                                    <img src="../assets/images/default.jpg" alt="" id='car_image' width="150px" height="150px">
                                <?php } else { ?>
                                    <img src="../assets/images/vehicalImage/<?php echo $car_image ?>" alt="" id='car_image' width="150px" height="150px">
                                <?php } ?>
                                <span><img src="../assets/images/cross.png" alt="" class="removeImg" data-img="car_image" style="background-color: red;padding: 2px;border-radius: 50%;"></span>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <button type="submit" class="btn-submit" name="submit">Submit</button>
                            <button type="button" class="btn-submit bg-danger border-0" name="reset" onclick="window.location.href='user.php';">Cancel</button>
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