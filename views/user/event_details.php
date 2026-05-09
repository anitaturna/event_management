<?php include('../../config/db_connect.php'); ?>
<?php include('../../includes/header.php'); ?>
<?php include('../../includes/navbar.php'); ?>

<div class="container">
    <?php 
    $event_id = $_GET['id'];
    $sql = "SELECT * FROM events WHERE id = $event_id";
    $res = mysqli_query($conn, $sql);
    $event = mysqli_fetch_assoc($res);
    ?>
    <h1><?php echo $event['name']; ?></h1>
    <p><?php echo $event['description']; ?></p>

    <h3>Select a Package:</h3>
    <div class="package-grid" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:20px;">
        <?php 
        $sql2 = "SELECT * FROM packages WHERE event_id = $event_id";
        $res2 = mysqli_query($conn, $sql2);
        while($pkg = mysqli_fetch_assoc($res2)){ ?>
            <div style="border:1px solid #ddd; padding:15px; border-radius:10px;">
                <h4><?php echo $pkg['name']; ?></h4>
                <p>Price: <strong><?php echo $pkg['price']; ?> BDT</strong></p>
                <p><?php echo $pkg['included_services']; ?></p>
                <a href="booking_form.php?package_id=<?php echo $pkg['id']; ?>" class="btn" style="background:#2ecc71; color:white; padding:5px 15px; text-decoration:none;">Book Now</a>
            </div>
        <?php } ?>
    </div>
</div>