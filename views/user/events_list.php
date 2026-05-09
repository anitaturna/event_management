<?php include('../../config/db_connect.php'); ?>
<?php include('../../includes/header.php'); ?>
<?php include('../../includes/navbar.php'); ?>

<h1>All Events</h1>
<div class="event-grid">
    <?php 
    $sql = "SELECT * FROM events WHERE status='active'";
    $res = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($res)){
        echo "<div><h3>".$row['name']."</h3><p>Category: ".$row['category']."</p>";
        echo "<a href='event_details.php?id=".$row['id']."'>View Details</a></div>";
    }
    ?>
</div>