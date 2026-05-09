<?php 
// ডায়াগ্রাম অনুযায়ী ডাটাবেস কানেকশন
include('../../config/db_connect.php'); 

// চেক করা - ইউজার লগইন করা কি না
if(!isset($_SESSION['user_id'])) {
    header('location:../auth/login.php');
    exit();
}

// বুকিং আইডি চেক করা
if(!isset($_GET['booking_id'])) {
    header('location: ../../index.php');
    exit();
}

$booking_id = mysqli_real_escape_string($conn, $_GET['booking_id']);

// বুকিং ডিটেইলস এবং ইউজারের নাম আনা (ডায়াগ্রামের টেবিল অনুযায়ী)
$sql = "SELECT b.total_price, u.name 
        FROM bookings b 
        JOIN users u ON b.user_id = u.id 
        WHERE b.id = $booking_id";

$res = mysqli_query($conn, $sql);

if(mysqli_num_rows($res) > 0) {
    $booking = mysqli_fetch_assoc($res);
} else {
    echo "Booking not found!";
    exit();
}
?>

<?php include('../../includes/header.php'); ?>
<?php include('../../includes/navbar.php'); ?>

<div class="container" style="margin-top: 50px;">
    <div class="payment-box" style="background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2>Complete Your Payment</h2>
        <hr>
        <p>Customer Name: <strong><?php echo $booking['name']; ?></strong></p>
        <p>Booking ID: <strong>#<?php echo $booking_id; ?></strong></p>
        <p style="font-size: 20px; color: #2ecc71;">Total Amount: <strong><?php echo $booking['total_price']; ?> BDT</strong></p>

        <form action="process_payment.php" method="POST">
            <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
            <input type="hidden" name="amount" value="<?php echo $booking['total_price']; ?>">
            
            <label><strong>Select Payment Method:</strong></label><br>
            <div style="margin: 15px 0;">
                <input type="radio" name="method" value="bkash" required> bKash &nbsp;&nbsp;
                <input type="radio" name="method" value="nagad"> Nagad &nbsp;&nbsp;
                <input type="radio" name="method" value="card"> Credit Card
            </div>
            
            <div class="form-group">
                <label>Transaction ID:</label><br>
                <input type="text" name="transaction_id" class="form-control" placeholder="Enter TrxID from bKash/Nagad" required style="width: 100%; padding: 10px; margin-top: 10px;">
            </div>
            
            <br>
            <button type="submit" name="pay_now" class="btn" style="width: 100%; background: #6c5ce7; color: #fff; border: none; padding: 12px; cursor: pointer;">Confirm Payment</button>
        </form>
    </div>
</div>

<?php include('../../includes/footer.php'); ?>