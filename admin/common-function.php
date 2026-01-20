<?php

function getTotalFlatHolders($connection) {
    $sql = "SELECT COUNT(*) AS total_count FROM tbl_flat_holders";
    return executeQuery($connection, $sql);
}

function getTotalRentFlats($connection) {
    $sql = "SELECT COUNT(*) AS total_count FROM tbl_rent_flats";
    return executeQuery($connection, $sql);
}

function getTotalBikes($connection) {
    $sql = "SELECT COUNT(*) AS total_count FROM tbl_vehicles WHERE flag='0' AND bike <> ''";
    return executeQuery($connection, $sql);
}


function getTotalCars($connection) {
    $sql = "SELECT COUNT(*) AS total_count FROM tbl_vehicles WHERE flag='1'AND car <> ''";
    return executeQuery($connection, $sql);
}

function executeQuery($connection, $sql) {
    $result = mysqli_query($connection, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['total_count'];
    } else {
        return 0;
    }
}

function getTotalAmount($connection)
{
    $sql = "SELECT SUM(amount) AS total FROM tbl_expenses";
    $result = mysqli_query($connection, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    } else {
        echo "Error: " . mysqli_error($connection);
        return 0;
    }
}
function getTotalCurrentRole($connection) {
    $sql = "SELECT COUNT(*) AS total_count FROM tbl_clubhouse_booking WHERE role='2'";
    return executeQuery($connection, $sql);
}
function getTotalUpcomingRole($connection) {
    $sql = "SELECT COUNT(*) AS total_count FROM tbl_clubhouse_booking WHERE role='0'";
    return executeQuery($connection, $sql);
}
function getTotalPastRole($connection) {
    $sql = "SELECT COUNT(*) AS total_count FROM tbl_clubhouse_booking WHERE role='1'";
    return executeQuery($connection, $sql);
}

function getTotalRoles($connection) {
    $totalCurrentRole = getTotalCurrentRole($connection);
    $totalUpcomingRole = getTotalUpcomingRole($connection);
    return $totalCurrentRole + $totalUpcomingRole;
}

function getTotalClubHouseBookingAmount($connection)
{
    $sql = "SELECT SUM(totalprice) AS total FROM tbl_clubhouse_booking";
    $result = mysqli_query($connection, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    } else {
        echo "Error: " . mysqli_error($connection);
        return 0;
    }
}
function getTotalAmountRentFlats($connection)
{
    $sql = "SELECT SUM(amount) AS total FROM tbl_rent_flats";
    $result = mysqli_query($connection, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    } else {
        echo "Error: " . mysqli_error($connection);
        return 0;
    }
}

function getTotalPendingComplaintStatus($connection) {
    $sql = "SELECT COUNT(*) AS total_count FROM tbl_complaints where status = '0'";
    return executeQuery($connection, $sql);
}
function getTotalResolvedComplaintStatus($connection) {
    $sql = "SELECT COUNT(*) AS total_count FROM tbl_complaints where status = '1'";
    return executeQuery($connection, $sql);
}
?>
