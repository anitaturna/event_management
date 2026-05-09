<?php include('../../config/db_connect.php'); ?>
<div class="container">
    <h2>Manage Events</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Event Name" required>
        <select name="category">
            <option value="wedding">Wedding</option>
            <option value="birthday">Birthday</option>
            <option value="corporate">Corporate</option>
        </select>
        <textarea name="description" placeholder="Description"></textarea>
        <button type="submit" name="add_event">Add Event</button>
    </form>

    <hr>
    <table border="1" width="100%">
        <tr><th>ID</th><th>Name</th><th>Category</th><th>Status</th></tr>
        <?php 
        $res = mysqli_query($conn, "SELECT * FROM events");
        while($row = mysqli_fetch_assoc($res)){
            echo "<tr><td>".$row['id']."</td><td>".$row['name']."</td><td>".$row['category']."</td><td>".$row['status']."</td></tr>";
        }
        ?>
    </table>
</div>