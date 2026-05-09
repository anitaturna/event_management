<?php include('../../config/db_connect.php'); ?>
<?php include('../../includes/header.php'); ?>
<?php include('../../includes/navbar.php'); ?>

<div class="container">
    <h2>My Bookings</h2>
    <table border="1" width="100%" cellpadding="10" style="border-collapse:collapse;">
        <tr>
            <th>Booking ID</th>
            <th>Event Date</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php 
        $u_id = $_SESSION['user_id'];
        $sql = "SELECT * FROM bookings WHERE user_id = $u_id ORDER BY id DESC";
        $res = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($res)){ ?>
            <tr>
                <td>#<?php echo $row['id']; ?></td>
                <td><?php echo $row['event_date']; ?></td>
                <td><?php echo $row['total_price']; ?> BDT</td>
                <td><?php echo $row['status']; ?></td>
                <td>
                    <?php if($row['status'] == 'pending'){ ?>
                        <a href="payment.php?booking_id=<?php echo $row['id']; ?>" style="color:blue;">Pay Now</a>
                    <?php } else { echo "No Action"; } ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>