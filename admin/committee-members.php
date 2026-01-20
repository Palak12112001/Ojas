<?php
include("connection.php");
$title = "Committee Members";
$page = 'Committee Members';
include("includes/header.php");

// Fetch data from the database
$sql = "SELECT * FROM tbl_committee_members";
$result = mysqli_query($connection, $sql);

?>

<!-- Page Content Holder -->
<div id="content" style="width:100%">
    <div class="content_area">
        <div class="page-title">Committee Member List  <?php if ($isAdmin) : ?><a href="committee-members-form.php" class="btn-add">Add</a><?php endif; ?></div>
        <ul class="member-list">
            <?php
            if (mysqli_num_rows($result) > 0) {
                // Output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                    <li>
                        <div class="member-block">
                            <div class="photo">
                                <img src="../assets/images/committee-members-image/<?php echo ($row['image']); ?>" alt="committee-member" >

                                <div class="wing"><?php echo ($row['wing_name']) ?></div>

                                <?php if ($isAdmin) : ?>
                                    <div class="btn-area">
                                        <a href="edit-committee-members.php?id=<?php echo $row['id']; ?>" class="btn-icon">
                                            <img src="../assets/images/edit-icon.png" alt="Edit">
                                        </a>
                                        <a href="#" class="btn-icon btn-delete deleteCommittee" data-member-id="<?php echo $row['id']; ?>">
                                            <img src="../assets/images/delete-icon.png" alt="Delete">
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="name"><?php echo ($row['full_name']); ?></div>
                            <p><?php echo ($row['phone']); ?></p>
                            <a class="phone" href="tel:+91<?php echo ($row['phone']); ?>"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3.77762 11.9424C2.8296 10.2893 2.37185 8.93948 2.09584 7.57121C1.68762 5.54758 2.62181 3.57081 4.16938 2.30947C4.82345 1.77638 5.57323 1.95852 5.96 2.6524L6.83318 4.21891C7.52529 5.46057 7.87134 6.08139 7.8027 6.73959C7.73407 7.39779 7.26737 7.93386 6.33397 9.00601L3.77762 11.9424ZM3.77762 11.9424C5.69651 15.2883 8.70784 18.3013 12.0576 20.2224M12.0576 20.2224C13.7107 21.1704 15.0605 21.6282 16.4288 21.9042C18.4524 22.3124 20.4292 21.3782 21.6905 19.8306C22.2236 19.1766 22.0415 18.4268 21.3476 18.04L19.7811 17.1668C18.5394 16.4747 17.9186 16.1287 17.2604 16.1973C16.6022 16.2659 16.0661 16.7326 14.994 17.666L12.0576 20.2224Z" stroke="#000" stroke-width="1.5" stroke-linejoin="round"></path><path d="M14 6.83185C15.4232 7.43624 16.5638 8.57677 17.1682 10M14.654 2C18.1912 3.02076 20.9791 5.80852 22 9.34563" stroke="#000" stroke-width="1.5" stroke-linecap="round"></path></svg></a>
                        </div>
                    </li>
            <?php
                }
            } else {
                echo "No committee members found.";
            }
            ?>
        </ul>
    </div>
</div>

<?php include('includes/footer.php'); ?>