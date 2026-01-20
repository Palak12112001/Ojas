</div>
<!-- Js -->
<script src="../assets/js/jquery-3.1.1.js"></script>
<script src="../assets/js/mdb.min.js"></script>
<script src="../assets/js/all_script.js"></script>

<!-- sweet alert cdn -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- validation link  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>

<?php
if ($page == 'Flat Holder' || $page == 'Bike List' || $page == 'Car List') {
    echo '<script src="../assets/js/flatsScript.js"></script>';
} elseif ($page == 'Rent Flats') {
    echo '<script src="../assets/js/rentflatScript.js"></script>';
} elseif ($page == 'Club House Booking') {
    echo '<script src="../assets/js/club_house_script.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>';
} elseif ($page == 'Expense') {
    echo '<script src="../assets/js/expenses.js"></script>';
} elseif ($page == 'Diesel') {
    echo '<script src="../assets/js/dieselScript.js"></script>';
} elseif ($page == 'Committee Members') {
    echo '<script src="../assets/js/committee_script.js"></script>';
}elseif ($page == 'Help & Support') {
    echo '<script src="../assets/js/helper.js"></script>';
}elseif($page == 'Profile'){
    echo '<script src="../assets/js/profile.js"></script>';
}elseif($page == 'Complaints'){
    echo '<script src="../assets/js/complaintsScript.js"></script>';
}
?>
 <script>
    var isAdmin = <?php echo json_encode($isAdmin); ?>;
    window.isAdmin = isAdmin;
</script>
</body>
</html>
