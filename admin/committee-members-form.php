<?php
include("connection.php");
$title = "Add Committee Members";
$page = 'Committee Members';
include("includes/header.php");
?>
<style>
    .error-message {
        color: red;
        font-size: 14px;
        margin-top: 5px;
    }
</style>
<!-- Page Content Holder -->
<div id="content" style="width:100%">
    <div class="content_area">
        <div class="page-title">Add Committee Members</div>
        <div class="form-area">
            <form method="POST" action="committee-members/insert.php" enctype="multipart/form-data" id="addCommittee">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label class="inputlabel">Full Name</label>
                            <input type="text" class="form-control" placeholder="Full Name" name="full_name" value="" required>
                            <div id="full_name_error" class="error-message"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label class="inputlabel">Phone Number</label>
                            <input type="text" class="form-control" placeholder="Phone Number" name="phone_number" value="" required>
                            <div id="phone_number_error" class="error-message"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label class="inputlabel">Wing</label>
                            <select class="form-select" name="wing" required>
                                <option value="">Select Wing</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                                <option value="F">F</option>
                            </select>
                            <div id="wing_error" class="error-message"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label class="inputlabel">User Name</label>
                            <input type="text" class="form-control" placeholder="User Name" name="user_name" value="" maxlength="30" required>
                            <div id="user_name_error" class="error-message"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label class="inputlabel">Password</label>
                            <input type="password" class="form-control" placeholder="Password" name="password" value=""  maxlength="15"required>
                            <div id="password_error" class="error-message"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label class="inputlabel">Committee Member Photo</label>
                            <input type="file" class="form-control" name="image" id="commitee_image">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="img-block">
                            <img src="../assets/images/default.jpg" alt="" width="100%" class="img-thumbnail p-0 border-0">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn-submit" name="submit">Submit</button>
                        <button type="button" class="btn-submit bg-danger border-0" name="reset" onclick="window.location.href='committee-members.php';" >Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

