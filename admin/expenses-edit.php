<?php
// Include database connection
include("connection.php");
$page = 'Expense';
// Initialize variables
$title = "";
$amount = "";
$payment_mode = "";
$committee_member_id = "";

// Check if ID parameter exists
if (!isset($_GET['id'])) {
    echo "ID parameter is missing.";
    exit;
}

$id = mysqli_real_escape_string($connection, $_GET['id']);

// Fetch existing data
$select = "SELECT * FROM `tbl_expenses` WHERE `id`='$id'";
$query = mysqli_query($connection, $select);

if ($query && mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_assoc($query);
    $title = htmlspecialchars($row['title']);
    $amount = htmlspecialchars($row['amount']);
    $payment_mode = $row['payment_mode'];
    $committee_member_id = $row['committee_members_id'];
    $id_image = $row['image'];
} else {
    echo "No data found.";
    exit;
}
?>

<?php include("includes/header.php"); ?>
<link href="../assets/css/style.css?id=400" rel="stylesheet" type="text/css" />
<style>
    .error-message {
        color: red;
        font-size: 14px;
        margin-top: 5px;
    }
</style>

<div id="content" style="width:100%">
    <div class="content_area">
        <div class="page-title">Edit Expenses</div>
        <div class="form-area">
            <form method="POST" enctype="multipart/form-data" id="editExpensesForm">
                <div class="row">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="Title" name="title" value="<?php echo $title; ?>" required>
                            <div id="title_error" class="error-message"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="Amount" name="amount" value="<?php echo $amount; ?>" required>
                            <div id="amount_error" class="error-message"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <select class="form-select" name="payment_mode" id="payment_mode" required>
                                <option value="">Select Payment Mode</option>
                                <option value="0" <?php echo $payment_mode == '0' ? 'selected' : ''; ?>>Online</option>
                                <option value="1" <?php echo $payment_mode == '1' ? 'selected' : ''; ?>>Cash</option>
                            </select>
                            <div id="payment_mode_error" class="error-message"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <select class="form-select" name="committee_member_id" id="committee_member_id" required>
                                <option value="">Select Committee Member</option>
                                <?php
                                $query_members = "SELECT id, full_name FROM tbl_committee_members";
                                $result_members = mysqli_query($connection, $query_members);

                                if ($result_members && mysqli_num_rows($result_members) > 0) {
                                    while ($member = mysqli_fetch_assoc($result_members)) {
                                        $selected = $committee_member_id == $member['id'] ? 'selected' : '';
                                        echo "<option value='{$member['id']}' $selected>{$member['full_name']}</option>";
                                    }
                                }
                                ?>
                            </select>
                            <div id="committee_member_error" class="error-message"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-9">

                                <input type="file" class="form-control" name="image" id="image">
                                <div id="image_error" class="error-message"></div>
                            </div>
                            <div class="col-3 p-0">
                                <?php if (!empty($id_image)) : ?>
                                    <img src="assets/images/expense/<?php echo htmlspecialchars($id_image); ?>" alt="Aadhar Card Preview" class="img-thumbnail p-0 border-0" style="width: 50%; height:40px;">
                                <?php else : ?>
                                    <img src="assets/images/default.jpg" alt="" width="100%" class="img-thumbnail p-0 border-0">
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn-submit" name="submit">Submit</button>
                        <button type="button" class="btn-submit bg-danger border-0" name="reset" onclick="window.location.href='expenses-list.php';" >Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(document).ready(function() {
        $('#editExpensesForm').validate({
            rules: {
                title: {
                    required: true
                },
                amount: {
                    required: true,
                    number: true
                },
                payment_mode: {
                    required: true
                },
                committee_member_id: {
                    required: true
                }
            },
            messages: {
                title: {
                    required: "Please enter a title."
                },
                amount: {
                    required: "Please enter an amount.",
                    number: "Please enter a valid number."
                },
                payment_mode: {
                    required: "Please select a payment mode."
                },
                committee_member_id: {
                    required: "Please select a committee member."
                }
            },
            submitHandler: function(form) {
                var formData = new FormData(form);

                $.ajax({
                    url: "expenses/update.php?id=<?php echo $id; ?>",
                    method: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log("Server Response: ", response);
                        try {
                            var jsonResponse = JSON.parse(response);
                            if (jsonResponse.status === "success") {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: 'Expense updated successfully.',
                                    onClose: () => {
                                        window.location.href = 'expenses-list.php';
                                    }
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Failed to update expense. Please try again later.',
                                    footer: '<a href="">Why do I have this issue?</a>'
                                });
                            }
                        } catch (e) {
                            console.log("Error parsing response: ", e);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An unexpected error occurred. Please try again later.'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to communicate with the server. Please try again later.'
                        });
                    }
                });
            }
        });
    });
</script>