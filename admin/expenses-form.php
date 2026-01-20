<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    include("connection.php");
    $title = "Ojas Aura";
    $page = 'Expense';
    include("includes/header.php");
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.19.3/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.19.3/additional-methods.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link href="../assets/css/style.css?id=400" rel="stylesheet" type="text/css" />
    <link href="../assets/css/custom.css?id=400" rel="stylesheet" type="text/css" />
    <style>
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div id="content" style="width:100%">
        <div class="content_area">
            <div class="page-title">Add Expenses</div>
            <div class="form-area">
                <form method="POST" enctype="multipart/form-data" id="expensesForm">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="inputlabel">Title</label>
                                <input type="text" class="form-control" placeholder="Title" name="title" required>
                                <div id="title_error" class="error-message"></div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="inputlabel">Amount</label>
                                <input type="text" class="form-control" placeholder="Amount" name="amount" required>
                                <div id="amount_error" class="error-message"></div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="inputlabel">Payment Mode</label>
                                <select class="form-select" name="payment_mode" id="payment_mode" required>
                                    <option value="">Select Payment Mode</option>
                                    <option value="0">Online</option>
                                    <option value="1">Cash</option>
                                </select>
                                <div id="payment_mode_error" class="error-message"></div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="inputlabel">Committee Member</label>
                                <select class="form-select" name="Name" id="Name" required>
                                    <option value="">Select Name</option>
                                    <!-- PHP code to dynamically populate options -->
                                    <?php
                                    $query = "SELECT id, full_name FROM tbl_committee_members";
                                    $result = mysqli_query($connection, $query);
                                    if (!$result) {
                                        die("Database query failed.");
                                    }
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $id = $row['id'];
                                        $full_name = $row['full_name'];
                                        echo "<option value='$id'>$full_name</option>";
                                    }
                                    ?>
                                </select>
                                <div id="name_error" class="error-message"></div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="inputlabel">Committee Member</label>
                                <input type="file" class="form-control" name="image" id="image">
                                <div id="image_error" class="error-message"></div>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="img-block">
                                <img src="../assets/images/default.jpg" alt="" width="100%" class="img-thumbnail p-0 border-0">
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
</body>
</html>
