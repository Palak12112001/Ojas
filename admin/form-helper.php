<?php
include("connection.php");
$title = "Help & Support";
$page = 'Help & Support';
include("includes/header.php");


// Fetch data from the database
$query = 'SELECT * FROM `tbl_helpers`';
$result = mysqli_query($connection, $query);

$data = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $data[] = $row;
    }
}
?>



<div id="content" style="width:100%">
        <div class="content_area">
            <div class="page-title">Help & Support</div>
            <div class="form-area">
                <?php
                if (mysqli_num_rows($result) > 0) {
                    foreach ($data as $row) {
                ?>
                        <form method="POST" id="ServiceForm_<?php echo $row['id']; ?>" class="service-form">
                            <div class="form-group" id="formGroup">
                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label class="inputlabel">Service</label>
                                            <input type="text" class="form-control edit-service" placeholder="Service" name="service" id="edit_service[<?php echo $row['id']; ?>]" required value='<?php echo $row['service']; ?>'>
                                            <div data-error="service" class="error-message"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label class="inputlabel">Helper Name</label>
                                            <input type="text" class="form-control edit-name" placeholder="Helper Name" name="name" id="edit_name[<?php echo $row['id']; ?>]" required value="<?php echo $row['name']; ?>">
                                            <div data-error="name" class="error-message"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label class="inputlabel">Phone</label>
                                            <input type="text" class="form-control edit-phone" placeholder="Phone Number" name="phone" id="edit_phone[<?php echo $row['id']; ?>]" value="<?php echo $row['phone']; ?>" maxlength="10" oninput="this.value = this.value.replace(/\D/g, '');">
                                            <div data-error="phone" class="error-message"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 d-flex align-items-center">
                                        <div class="mb-3">
                                            <a href="#" data-id='<?php echo $row['id']; ?>' class="btn btn-icon btn-delete"><img src="../assets/images/delete-icon.png" alt="Delete"></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                <?php
                    }
                }
                ?>
                <form method="POST" id="DefaultServiceForm" class="service-form <?php echo (mysqli_num_rows($result) > 0) ? 'd-none' : ''; ?>">
                    <div class="form-group" id="formGroup">
                        <div class="row justify-content-center" id='defaultRow'>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="inputlabel">Service</label>
                                    <input type="text" class="form-control default-service" placeholder="Service" name="service" id="default_service" required>
                                    <div data-error="service" class="error-message"></div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="inputlabel">Helper Name</label>
                                    <input type="text" class="form-control default-name" placeholder="Helper Name" name="name" id="default_name" required>
                                    <div data-error="name" class="error-message"></div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="inputlabel">Phone</label>
                                    <input type="text" class="form-control default-phone" placeholder="Phone Number" name="phone" id="default_phone" maxlength="10" oninput="this.value = this.value.replace(/\D/g, '');">
                                    <div data-error="phone" class="error-message"></div>
                                </div>
                            </div>
                            <div class="col-lg-3 d-flex align-items-center">
                                <div class="mb-3">
                                    <span class="btn addmore"><img src="../assets/images/plus-sign.png" alt="Add More"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="col-lg-12 mt-3">
                <button type="button" class="btn-submit me-2 submit" id="submitAllForms">Submit</button>
                <button type="button" class="btn-submit bg-danger border-0 me-2" name="reset" onclick="window.location.href='dashboard.php';">Cancel</button>
                <span class="btn btn-submit bg-info border border-info addmore <?php echo (mysqli_num_rows($result) > 0) ? '' : 'd-none'; ?> "><img src="../assets/images/plus-sign.png" alt="Add More"></span>
                
                </div>
            </div>
            
        </div>
    </div>
 
    <?php include('includes/footer.php'); ?>
   