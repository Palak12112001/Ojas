<?php
include('connection.php');
$title = 'Committee member Profile';
$page = 'Profile';
include("includes/header.php");

$query = "SELECT * FROM `tbl_committee_members` WHERE user_name='$username'";
$result = mysqli_query($connection, $query);
$row = mysqli_fetch_array($result);
$id = $row['id'];
$name = $row['full_name'];
$phone = $row['phone'];
$wing_name = $row['wing_name'];
$image = $row['image'];
$part1 = substr($phone, 0, 5);
$part2 = substr($phone, 5, 5);
$number = $part1 . '&nbsp;' . $part2;
?>
<!-- Page Content Holder -->
<div id="content" style="width:100%">
    <div class="content_area">
        <div class="page-title"><?php echo $title; ?></div>
        <div class="form-area">
            <div class="row ">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="card bg-dark">
                        <div class="row no-gutters">
                            <div class="col-md-4 d-flex justify-content-center align-items-center p-3">
                                <img src="../assets/images/committee-members-image/<?php echo $image; ?>" alt="" class="rounded" width="150px" height="150px">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h2 class="card-title font-weight-bold"><?php echo $name; ?></h2>
                                    <p class="card-text"><strong>Username:</strong> <?php echo $username; ?></p>
                                    <p class="card-text"><strong>Wing Name:</strong> <?php echo $wing_name; ?></p>
                                    <p class="card-text"><strong>Phone Number:</strong> +91 <?php echo $number; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-3">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="card bg-dark">
                        <form method="post" enctype="multipart/form-data" id="updateProfile">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <div class="row no-gutters align-items-center p-3">
                                <div class="col-md-3 text-center">
                                    <label for="image" class="text-white">Image:</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="file" name="image" id="image" class="form-control d-none">
                                    <img src="../assets/images/committee-members-image/<?php echo $image; ?>" alt="" class="rounded imgPreview" width="80px" height="80px">
                                    <div>
                                        <img src="../assets/images/edit-icon.png" alt="edit image" id="editImage" class="bg-dark rounded-circle position-absolute" style="height: 19px;padding: 2px;top: 10px;left: 274px;">
                                    </div>
                                </div>
                            </div>
                            <div class="row no-gutters align-items-center p-3">
                                <div class="col-md-3 text-center">
                                    <label for="full_name" class="text-white">Full Name:</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="full_name" id="full_name" class="form-control py-2" style="min-height:27px" value="<?php echo $name; ?>">
                                </div>
                            </div>
                            <div class="row no-gutters align-items-center p-3">
                                <div class="col-md-3 text-center">
                                    <label for="user_name" class="text-white">User Name:</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="user_name" id="user_name" class="form-control py-2" readonly style="min-height:27px" value="<?php echo $username; ?>">
                                </div>
                            </div>
                            <div class="row no-gutters align-items-center p-3">
                                <div class="col-md-3 text-center">
                                    <label for="wing_name" class="text-white">Wing Name:</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="wing_name" id="wing_name" class="form-control py-2" readonly style="min-height:27px" value="<?php echo $wing_name; ?>">
                                </div>
                            </div>
                            <div class="row no-gutters align-items-center p-3">
                                <div class="col-md-3 text-center">
                                    <label for="Phone" class="text-white">Phone:</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="phone_number" id="phone" class="form-control py-2" style="min-height:27px" value="<?php echo $phone; ?>" maxlength="10" oninput="this.value = this.value.replace(/\D/g, '');">
                                </div>
                            </div>
                            <div class="row no-gutters align-items-center p-3">
                                <button type="submit" class="btn-submit w-25 m-2 updateData" name="submit">Submit</button>
                                <button type="button" class="btn-submit bg-danger border border-danger w-25 m-2" name="reset" onclick="window.location.reload();">Cancel</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <div class="row my-3">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="card bg-dark">
                        <div class="row no-gutters align-items-center p-3 " id="passwordDiv">
                            <div class="col-md-8">
                                <h5 class="text-white">Password</h5>
                                <span>************</span>
                            </div>
                            <div class="col-md-4">
                                <button class="btn-submit" id="reset-password-button">Reset Password</button>
                            </div>
                        </div>
                        <form method="post" id="resetpasswordform" style="display: none;">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <div class="row no-gutters align-items-center p-3 ">
                                <div class="col-md-3 text-center">
                                    <label for="current_Password" class="text-white">Current Password:</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="password" name="current_password" id="current_password" class="form-control py-2" style="min-height:27px">
                                    <span><i class="far fa-eye-slash toggle-password" data-id="current_password" style="position: relative;top: -28px;right: -320px;"></i></span> <!-- Unique eye icon -->
                                    <div id="currentPasswordError" class="error-message"></div> <!-- Unique container for password error -->
                                </div>
                            </div>
                            <div class="row no-gutters align-items-center p-3 ">
                                <div class="col-md-3 text-center">
                                    <label for="new_Password" class="text-white">New Password:</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="password" name="new_password" id="new_password" class="form-control py-2" style="min-height:27px">
                                    <span><i class="far fa-eye-slash toggle-password" data-id="new_password" style="position: relative;top: -28px;right: -320px;"></i></span> <!-- Unique eye icon -->
                                    <div id="newPasswordError" class="error-message"></div> <!-- Unique container for password error -->
                                    
                                </div>
                            </div>
                            <div class="row no-gutters align-items-center p-3 ">
                                <div class="col-md-3 text-center">
                                    <label for="confirm_Password" class="text-white">Confirm Password:</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control py-2" style="min-height:27px">
                                    <span><i class="far fa-eye-slash toggle-password" data-id="confirm_password" style="position: relative;top: -28px;right: -320px;"></i></span> <!-- Unique eye icon -->
                                    <div id="confirmPasswordError" class="error-message"></div> <!-- Unique container for password error -->
                                    
                                </div>
                            </div>
                            <div class="row no-gutters align-items-center p-3">
                                <button type="submit" class="btn-submit w-25 m-2" name="submit">Update</button>
                                <button type="button" class="btn-submit bg-danger border border-danger w-25 m-2" name="reset" onclick="window.location.reload();">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>

<?php include('includes/footer.php'); ?>