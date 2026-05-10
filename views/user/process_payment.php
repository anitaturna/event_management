<?php 
ob_start();
include('../../config/db_connect.php');

if(!isset($_SESSION['user_id'])) {
    header('location:../auth/login.php');
    exit();
}

if(!isset($_POST['pay_now'])) {
    header('location:../../index.php');
    exit();
}

$booking_id     = (int)$_POST['booking_id'];
$amount         = (float)$_POST['amount'];
$method         = mysqli_real_escape_string($conn, $_POST['method']);
$transaction_id = mysqli_real_escape_string($conn, $_POST['transaction_id']);

// Payment insert
$sql = "INSERT INTO payments (booking_id, amount, method, transaction_id, payment_date, status) 
        VALUES ($booking_id, $amount, '$method', '$transaction_id', NOW(), 'success')";

if(mysqli_query($conn, $sql)) {
    // Booking status update
    mysqli_query($conn, "UPDATE bookings SET status='approved' WHERE id=$booking_id");
    $_SESSION['payment_success'] = "Payment successful! Booking #$booking_id confirmed.";
    header('location:my_bookings.php');
    exit();
} else {
    echo "Payment failed: " . mysqli_error($conn);
}
ob_end_flush();
?>
