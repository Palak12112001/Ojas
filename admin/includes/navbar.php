<?php
session_start(); // Ensure session is started at the beginning

// Check if either admin or committee member session is set
if (isset($_SESSION['admin'])) {
    $username = htmlspecialchars($_SESSION['admin']);
    // You can set additional variables or perform other tasks specific to admin role
    $isAdmin = true; // Flag to identify admin
} elseif (isset($_SESSION['committee_member'])) {
    $username = htmlspecialchars($_SESSION['committee_member']);
    // You can set additional variables or perform other tasks specific to committee member role
    $isAdmin = false; // Not admin, committee member
} else {
    // Redirect to index.php if no valid session found
    header('Location: index.php');
    exit;
}

// Example to get the first letter of username for the circle code
$first_letter = htmlspecialchars(substr($username, 0, 1));
?>
<div class="wrapper">
    <div class="header-area">
        <div class="logo-icon">
            <img src="../assets/images/logo.png" alt="">
        </div>

        <div class="right">
            <div class="dropdown">
                <button type="button" class="user-menu dropdown-toggle" data-mdb-toggle="dropdown" aria-expanded="false">
                    <span class="mt-2"><?php echo $username; ?></span>
                   
                    <div class="circle-code">
                        <?php echo htmlspecialchars($first_letter); ?>
                        <span class="online"></span>
                    </div>
                              
            </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    
                    <li class="<?php echo (!$isAdmin ? '' : 'd-none'); ?>"><a class="dropdown-item" href="profile.php">Profile</a></li>
                    <li><a class="dropdown-item" href="form-helper.php">Help & Support</a></li>
                    <li><a class="dropdown-item" href="login/logout.php">Sign Out</a></li>
                </ul>
            </div>
            <button type="button" name="button" class="btn-menu"><span></span><span></span><span></span></button>
        </div>
    </div>
