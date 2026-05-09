<?php 
include('../../config/db_connect.php'); 
include('../../includes/header.php'); 

// অ্যাডমিন চেক: লগইন করা না থাকলে বা রোল 'admin' না হলে বের করে দিবে
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header('location:'.SITEURL.'views/auth/login.php');
    exit();
}
?>

<div style="display: flex; min-height: 100vh; background: #f4f7f6; font-family: 'Segoe UI', sans-serif;">
    
    <!-- Sidebar -->
    <div style="width: 250px; background: #2d3436; color: white; padding: 20px;">
        <h2 style="color: #6c5ce7; text-align: center; margin-bottom: 30px;">Admin Panel</h2>
        <ul style="list-style: none; padding: 0;">
            <li style="margin-bottom: 15px;"><a href="dashboard.php" style="color: #6c5ce7; text-decoration: none; font-weight: bold;">📊 Dashboard</a></li>
            <li style="margin-bottom: 15px;"><a href="manage_events.php" style="color: #dfe6e9; text-decoration: none;">📅 Manage Events</a></li>
            <li style="margin-bottom: 15px;"><a href="manage_vendors.php" style="color: #dfe6e9; text-decoration: none;">🤝 Manage Vendors</a></li>
            <li style="margin-bottom: 15px;"><a href="manage_users.php" style="color: #dfe6e9; text-decoration: none;">👥 Manage Users</a></li>
            <li style="margin-top: 50px;"><a href="../auth/logout.php" style="color: #ff7675; text-decoration: none; font-weight: bold;">🚪 Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div style="flex: 1; padding: 40px;">
        <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h1 style="color: #2d3436; margin: 0;">Admin Dashboard</h1>
            <div style="color: #636e72;">Welcome back, <strong><?php echo $_SESSION['user_name']; ?></strong></div>
        </header>

        <!-- Stats Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
            
            <!-- Total Bookings -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-left: 5px solid #fdcb6e;">
                <p style="color: #636e72; margin: 0; font-size: 14px; text-transform: uppercase;">Total Bookings</p>
                <?php 
                    $res = mysqli_query($conn, "SELECT count(id) as total FROM bookings");
                    $data = mysqli_fetch_assoc($res);
                    echo "<h2 style='margin: 10px 0 0; color: #2d3436;'>".$data['total']."</h2>";
                ?>
            </div>

            <!-- Total Revenue -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-left: 5px solid #00b894;">
                <p style="color: #636e72; margin: 0; font-size: 14px; text-transform: uppercase;">Total Revenue</p>
                <?php 
                    $res = mysqli_query($conn, "SELECT sum(amount) as total FROM payments WHERE status='success'");
                    $data = mysqli_fetch_assoc($res);
                    $revenue = $data['total'] ? number_format($data['total'], 2) : "0.00";
                    echo "<h2 style='margin: 10px 0 0; color: #2d3436;'>৳ ".$revenue."</h2>";
                ?>
            </div>

            <!-- Active Events -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-left: 5px solid #0984e3;">
                <p style="color: #636e72; margin: 0; font-size: 14px; text-transform: uppercase;">Active Events</p>
                <?php 
                    $res = mysqli_query($conn, "SELECT count(id) as total FROM events");
                    $data = mysqli_fetch_assoc($res);
                    echo "<h2 style='margin: 10px 0 0; color: #2d3436;'>".$data['total']."</h2>";
                ?>
            </div>

        </div>

        <!-- Recent Activities (Optional Placeholder) -->
        <div style="margin-top: 40px; background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <h3 style="color: #2d3436; margin-bottom: 20px;">Quick Actions</h3>
            <div style="display: flex; gap: 15px;">
                <a href="add_event.php" style="background: #6c5ce7; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-size: 14px;">+ Add New Event</a>
                <a href="manage_bookings.php" style="background: #dfe6e9; color: #2d3436; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-size: 14px;">View All Bookings</a>
            </div>
        </div>

    </div>
</div>

<?php include('../../includes/footer.php'); ?>