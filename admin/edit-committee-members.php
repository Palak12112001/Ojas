<?php
include("connection.php");
$title = "Edit Committee Members";
$page = 'Committee Members';
include("includes/header.php");

// Fetch existing data
$row = [];
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $select = "SELECT * FROM `tbl_committee_members` WHERE `id`='$id'";
    $query = mysqli_query($connection, $select);
    if ($query && mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
    } else {
        echo "No data found.";
    }
} else {
    echo "ID parameter is missing.";
}
?>

<!-- Page Content Holder -->
<div id="content" style="width:100%">
    <div class="content_area">
        <div class="page-title">Edit Committee Members</div>
        <div class="form-area">
            <form id="editCommittee" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <input type="text" name="id" value="<?php echo isset($row['id']) ? ($row['id']) : ''; ?>" class="d-none">
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="Full Name" name="full_name" value="<?php echo isset($row['full_name']) ? ($row['full_name']) : ''; ?>">
                            <div id="full_name_error" class="error-message"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="Phone Number" name="phone_number" value="<?php echo isset($row['phone']) ? ($row['phone']) : ''; ?>">
                            <div id="phone_number_error" class="error-message"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <select class="form-select" name="wing_name">
                                <option value="A" <?php echo (isset($row['wing_name']) && $row['wing_name'] == 'A') ? 'selected' : ''; ?>>A</option>
                                <option value="B" <?php echo (isset($row['wing_name']) && $row['wing_name'] == 'B') ? 'selected' : ''; ?>>B</option>
                                <option value="C" <?php echo (isset($row['wing_name']) && $row['wing_name'] == 'C') ? 'selected' : ''; ?>>C</option>
                                <option value="D" <?php echo (isset($row['wing_name']) && $row['wing_name'] == 'D') ? 'selected' : ''; ?>>D</option>
                                <option value="E" <?php echo (isset($row['wing_name']) && $row['wing_name'] == 'E') ? 'selected' : ''; ?>>E</option>
                                <option value="F" <?php echo (isset($row['wing_name']) && $row['wing_name'] == 'F') ? 'selected' : ''; ?>>F</option>
                            </select>
                            <div id="wing_error" class="error-message"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="User Name" name="user_name" value="<?php echo isset($row['user_name']) ? ($row['user_name']) : ''; ?>">
                            <div id="user_name_error" class="error-message"></div>
                        </div>
                    </div>
                   
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-9">
                                <div class="mb-3">
                                    <input type="file" class="form-control" name="image" id="commitee_image">
                                </div>
                            </div>
                            <div class="col-3">
                                <img src="../assets/images/committee-members-image/<?php echo isset($row['image']) ? ($row['image']) : ''; ?>" alt="" width="100%" class="img-thumbnail p-0 border-0">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn-submit editCommittee" name="button">Submit</button>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>