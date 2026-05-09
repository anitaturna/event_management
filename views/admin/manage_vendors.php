<?php 
// দুই ধাপ পেছনে গিয়ে config/db_connect.php খুঁজে বের করা
include('../../config/db_connect.php'); 

// চেক করা - ইউজার লগইন করা কি না এবং সে এডমিন কি না
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('location:../../views/auth/login.php');
    exit();
}
?>

<!-- এরপর আপনার HTML এবং টেবিল কোড শুরু হবে -->

<div class="container">
    <h2>Manage Vendors</h2>
    <a href="add_vendor.php" class="btn">Add New Vendor</a>
    
    <table border="1" width="100%" cellpadding="10" style="border-collapse: collapse; margin-top: 20px;">
        <tr style="background: #eee;">
            <th>Name</th>
            <th>Service Type</th>
            <th>Price</th>
            <th>Rating</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php 
        $sql = "SELECT * FROM vendors";
        $res = mysqli_query($conn, $sql);
        while($vendor = mysqli_fetch_assoc($res)) {
            echo "<tr>
                <td>{$vendor['name']}</td>
                <td>{$vendor['service_type']}</td>
                <td>{$vendor['price']} BDT</td>
                <td>{$vendor['rating']} / 5.0</td>
                <td>{$vendor['status']}</td>
                <td>
                    <a href='edit_vendor.php?id={$vendor['id']}'>Edit</a> | 
                    <a href='delete_vendor.php?id={$vendor['id']}' style='color:red;'>Delete</a>
                </td>
            </tr>";
        }
        ?>
    </table>
</div>