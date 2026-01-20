<?php
// Assuming this PHP script is expenses-list.php

// Include the database connection
include('connection.php');
$page = 'Expense';
// Set page title
$title = "Ojas Aura";


// Include header
include("includes/header.php");

// Initialize search variable
$search = '';

// Process search input if submitted via GET method
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($connection, $_GET['search']);
}

$payment_modetMapping = [
    'online' => '0',
    'Online' => '0',
    'cash' => '1',
    'Cash' => '1'
];

// Construct the base query
$baseQuery = "SELECT 
    e.*, 
    c.full_name AS member_name 
FROM 
    `tbl_expenses` AS e 
LEFT JOIN 
    `tbl_committee_members` AS c 
ON 
    e.committee_members_id = c.id
WHERE 
    e.title LIKE '%$search%'
    OR c.full_name LIKE '%$search%'
    OR e.amount LIKE '%$search%'
    OR e.insert_at LIKE '%$search%'";

// Check if the search term is in the payment mode mapping array
if (array_key_exists($search, $payment_modetMapping)) {
    $payment_search = $payment_modetMapping[$search];
    $baseQuery .= " OR e.payment_mode = '$payment_search'";
}

// Complete the query with ordering
$query = $baseQuery . " ORDER BY e.insert_at DESC";

$result = mysqli_query($connection, $query);

// Check for query execution errors
if (!$result) {
    die("Database query failed: " . mysqli_error($connection));
}
?>


<!-- Include FancyBox CSS -->

<!-- Page Content Holder -->
<div id="content" style="width:100%">
    <div class="content_area">
        <div class="page-title">List Expenses<a href="expenses-form.php" class="btn-add">Add</a></div>
        <div class="search-area">
            <form method="GET" action="expenses-list.php">

                <div class="inputblock">
                    <input type="text" class="form-control search" placeholder="Search Here..." name="search" value="">
                    <button type="submit" class="btn-search" name="submit"><img src="../assets/images/search-icon.png" alt=""></button>
                    <button type="button" class="btn-reset" name="reset" onclick="window.location.href = '<?php echo $_SERVER['PHP_SELF']; ?>'"><img src="../assets/images/reset-icon.png" alt=""></button>
                </div>
                <!-- </div> -->
            </form>
        </div>

        <ul class="list-ui expenses-table">
            <?php
            // Check if there are results
            if (mysqli_num_rows($result) > 0) {
                $counter = 1;
                // Loop through each row of data
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                    <li>
                        <div class="expenses-block">
                            <?php if ($isAdmin) : ?>
                                <div class="action">
                                    <a href="expenses-edit.php?id=<?php echo $row['id'];?>" class="btn-icon"><img src="../assets/images/edit-icon.png" alt="Edit"></a>
                                    <a href="#"  data-id='<?php echo $row['id'];?>' class="btn-icon delete deleteexpenses"><img src="../assets/images/delete-icon.png" alt="Delete"></a>
                                </div>
                            <?php endif; ?>
                            <h2><?php echo ucwords($row['member_name']); ?></h2>
                            <div class="d-flex">
                                <div class="info-full"><span>Title</span><?php echo ucwords($row['title']); ?></div>
                                <div class="info"><span>Payment Mode</span><?php echo ($row['payment_mode'] == 1) ? 'Cash' : 'Online'; ?></div>
                                <div class="info"><span>Amount</span><?php echo number_format($row['amount'],2); ?> Rs</div>
                            </div>
                            <a data-fancybox='gallery' href="../assets/images/expense/<?php echo $row['image']; ?>" class="bill-photo">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <mask id="mask0_731_6352" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="24" height="24">
                                        <rect width="24" height="24" fill="#d5a85d"></rect>
                                    </mask>
                                    <g mask="url(#mask0_731_6352)">
                                        <path d="M8.25 11.75V10.25H15.75V11.75H8.25ZM8.25 7.75V6.25H15.75V7.75H8.25ZM6 14.25H13.5C13.9448 14.25 14.3563 14.3462 14.7345 14.5385C15.1128 14.7308 15.434 15.0026 15.698 15.3538L18 18.354V4.30775C18 4.21792 17.9712 4.14417 17.9135 4.0865C17.8558 4.02883 17.7821 4 17.6923 4H6.30775C6.21792 4 6.14417 4.02883 6.0865 4.0865C6.02883 4.14417 6 4.21792 6 4.30775V14.25ZM6.30775 20H17.3673L14.5173 16.2712C14.3916 16.1058 14.2419 15.9775 14.0682 15.8865C13.8946 15.7955 13.7052 15.75 13.5 15.75H6V19.6923C6 19.7821 6.02883 19.8558 6.0865 19.9135C6.14417 19.9712 6.21792 20 6.30775 20ZM17.6923 21.5H6.30775C5.80258 21.5 5.375 21.325 5.025 20.975C4.675 20.625 4.5 20.1974 4.5 19.6923V4.30775C4.5 3.80258 4.675 3.375 5.025 3.025C5.375 2.675 5.80258 2.5 6.30775 2.5H17.6923C18.1974 2.5 18.625 2.675 18.975 3.025C19.325 3.375 19.5 3.80258 19.5 4.30775V19.6923C19.5 20.1974 19.325 20.625 18.975 20.975C18.625 21.325 18.1974 21.5 17.6923 21.5Z" fill="#d5a85d"></path>
                                    </g>
                                </svg>
                            </a>
                        </div>
                    </li>
            <?php
                }
            }
            ?>
        </ul>

        <!-- // <table class="table expenses-table">
        //     <thead>
        //         <tr>
        //             <th>No.</th>
        //             <th>Name</th>
        //             <th>Title</th>
        //             <th>Amount</th>
        //             <th>Payment Mode</th>
        //             <th>Image</th>
        //             <?php if ($isAdmin) : ?>
        //                 <th>Actions</th>
        //             <?php endif; ?>
        //         </tr>
        //     </thead>
        //     <tbody>
        //         <?php
                    //         // Check if there are results
                    //         if (mysqli_num_rows($result) > 0) {
                    //             $counter = 1;
                    //             // Loop through each row of data
                    //             while ($row = mysqli_fetch_assoc($result)) {
                    //                 echo "<tr>";
                    //                 echo "<td>{$counter}</td>";
                    //                 echo "<td>{$row['member_name']}</td>"; // Display committee member name
                    //                 echo "<td>{$row['title']}</td>"; // Display title of expense
                    //                 echo "<td>{$row['amount']} Rs</td>"; // Display amount of expense
                    //                 echo "<td>";
                    //                 // Displaying payment mode based on the stored value
                    //                 if ($row['payment_mode'] == 0) {
                    //                     echo "Online";
                    //                 } elseif ($row['payment_mode'] == 1) {
                    //                     echo "Cash";
                    //                 } else {
                    //                     echo "Unknown";
                    //                 }
                    //                 echo "</td>";

                    //                 // Display image with FancyBox
                    //                 echo "<td>";
                    //                 if (!empty($row['image'])) {
                    //                     echo "<a data-fancybox='gallery' href='assets/images/expense/{$row['image']}' data-caption='Caption #{$counter}'>";
                    //                     echo "<img src='assets/images/expense/{$row['image']}' alt='Expense Image' style='width: 50px; height: 50px;' />";
                    //                     echo "</a>";
                    //                 } else {
                    //                     echo "<a data-fancybox='gallery' href='assets/images/expense/default.jpg' data-caption='Caption:default.jpg'>";
                    //                     echo "<img src='assets/images/default.jpg' alt='Expense Image' style='width: 50px; height: 50px;' />";
                    //                     echo "</a>";
                    //                 }
                    //                 echo "</td>";

                    //                 // Check if $isAdmin is true
                    //                 if ($isAdmin) {
                    //                     echo "<td>";
                    //                     echo "<a href='expenses-edit.php?id={$row['id']}' class='btn-icon'><img src='assets/images/edit-icon.png' alt='Edit'></a>";
                    //                     // Delete button with confirmation
                    //                     echo "<a class='deleteexpenses' data-id='{$row['id']}'><img src='assets/images/delete-icon.png'></a>";
                    //                     echo "</td>";
                    //                 }

                    //                 echo "</tr>";
                    //                 $counter++;
                    //             }
                    //         } else {
                    //             // If no results found
                    //             echo "<tr><td colspan='7' class='text-center'><h2>No results found</h2></td></tr>";
                    //         }
                    //         
                    ?>
        //     </tbody>
        // </table> -->
    </div>
</div>

<!-- Include FancyBox JS -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>

<?php include('includes/footer.php'); ?>