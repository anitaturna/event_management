<?php include('../../config/db_connect.php'); ?>
<?php include('../../includes/header.php'); ?>
<?php 
if(!isset($_SESSION['user_id'])) header('location:../auth/login.php');
$pkg_id = $_GET['package_id'];
?>

<div class="container">
    <h2>Complete Your Booking</h2>
    <form action="process_booking.php" method="POST">
        <input type="hidden" name="package_id" value="<?php echo $pkg_id; ?>">
        
        <label>Event Date:</label>
        <input type="date" name="event_date" required><br><br>

        <label>Add Extra Vendors (Optional):</label><br>
        <?php 
        $v_sql = "SELECT * FROM vendors WHERE status='active'";
        $v_res = mysqli_query($conn, $v_sql);
        while($vendor = mysqli_fetch_assoc($v_res)){
            echo "<input type='checkbox' name='vendors[]' value='".$vendor['id']."'> ".$vendor['name']." (".$vendor['service_type']." - ".$vendor['price']." BDT)<br>";
        }
        ?>
        <br>
        <button type="submit" name="submit_booking" style="background:#6c5ce7; color:white; padding:10px;">Confirm & Pay</button>
    </form>
</div>