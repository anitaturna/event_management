<?php 
ob_start();
include('../../config/db_connect.php');

if(!isset($_SESSION['user_id'])) {
    header('location:../auth/login.php');
    exit();
}

if(!isset($_POST['submit_booking'])) {
    header('location:../../index.php');
    exit();
}

$user_id    = $_SESSION['user_id'];
$package_id = (int)$_POST['package_id'];
$event_date = mysqli_real_escape_string($conn, $_POST['event_date']);
$start_time = isset($_POST['start_time']) ? mysqli_real_escape_string($conn, $_POST['start_time']) : null;
$end_time   = isset($_POST['end_time'])   ? mysqli_real_escape_string($conn, $_POST['end_time'])   : null;
$vendors    = isset($_POST['vendors']) ? $_POST['vendors'] : [];

// Package price নেওয়া
$pkg_res  = mysqli_query($conn, "SELECT price FROM packages WHERE id=$package_id");
$pkg_data = mysqli_fetch_assoc($pkg_res);
$total    = $pkg_data['price'];

// Vendor prices যোগ করা
foreach ($vendors as $vid) {
    $vid = (int)$vid;
    $v_res  = mysqli_query($conn, "SELECT price FROM vendors WHERE id=$vid");
    $v_data = mysqli_fetch_assoc($v_res);
    $total += $v_data['price'];
}

// Booking insert
$st = $start_time ? "'$start_time'" : 'NULL';
$et = $end_time   ? "'$end_time'"   : 'NULL';
$sql = "INSERT INTO bookings (user_id, package_id, event_date, start_time, end_time, total_price, status, created_at) 
        VALUES ($user_id, $package_id, '$event_date', $st, $et, $total, 'pending', NOW())";

if(mysqli_query($conn, $sql)) {
    $booking_id = mysqli_insert_id($conn);

    // booking_vendors insert
    foreach ($vendors as $vid) {
        $vid    = (int)$vid;
        $v_res  = mysqli_query($conn, "SELECT price FROM vendors WHERE id=$vid");
        $v_data = mysqli_fetch_assoc($v_res);
        $vprice = $v_data['price'];
        mysqli_query($conn, "INSERT INTO booking_vendors (booking_id, vendor_id, price) VALUES ($booking_id, $vid, $vprice)");
    }

    header("location:payment.php?booking_id=$booking_id");
    exit();
} else {
    echo "Booking failed: " . mysqli_error($conn);
}
ob_end_flush();
?>
