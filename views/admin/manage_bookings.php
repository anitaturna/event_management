<?php include('../../config/db_connect.php'); ?>
<table>
    <tr>
        <th>User</th>
        <th>Event Date</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php 
    $sql = "SELECT * FROM bookings";
    $res = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($res)){
        echo "<tr>
            <td>".$row['user_id']."</td>
            <td>".$row['event_date']."</td>
            <td>".$row['status']."</td>
            <td><a href='update_status.php?id=".$row['id']."'>Approve</a></td>
        </tr>";
    }
    ?>
</table>