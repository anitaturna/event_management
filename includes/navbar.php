<nav style="background: #6c5ce7; padding: 15px 0; box-shadow: 0 2px 10px rgba(0,0,0,0.1); font-family: 'Segoe UI', sans-serif;">
    <div style="width: 90%; max-width: 1200px; margin: auto; display: flex; justify-content: space-between; align-items: center;">
        
        <!-- Logo -->
        <a href="<?php echo SITEURL; ?>" style="color: white; text-decoration: none; font-size: 22px; font-weight: bold; letter-spacing: 1px;">
            EVENT<span style="color: #a29bfe;">PRO</span>
        </a>

        <!-- Menu Links -->
        <div style="display: flex; gap: 25px; align-items: center;">
            <a href="<?php echo SITEURL; ?>" style="color: white; text-decoration: none; font-size: 15px;">Home</a>
            <a href="<?php echo SITEURL; ?>views/user/events_list.php" style="color: white; text-decoration: none; font-size: 15px;">Events</a>

            <?php if(isset($_SESSION['user_id'])): ?>
                
                <!-- সবার জন্য কমন: My Bookings -->
                <a href="<?php echo SITEURL; ?>views/user/my_bookings.php" style="color: white; text-decoration: none; font-size: 15px;">My Bookings</a>

                <!-- এডমিন হলে এই লিঙ্কগুলো দেখাবে -->
                <?php if($_SESSION['role'] == 'admin'): ?>
                    <a href="<?php echo SITEURL; ?>views/admin/dashboard.php" style="color: #ffeaa7; text-decoration: none; font-size: 15px; font-weight: bold;">Admin Panel</a>
                <?php endif; ?>

                <!-- ভেন্ডর হলে এই লিঙ্কটি দেখাবে -->
                <?php if($_SESSION['role'] == 'vendor'): ?>
                    <a href="<?php echo SITEURL; ?>views/admin/manage_vendors.php" style="color: #55efc4; text-decoration: none; font-size: 15px; font-weight: bold;">Vendor Dashboard</a>
                <?php endif; ?>

                <!-- Logout Button -->
                <a href="<?php echo SITEURL; ?>views/auth/logout.php" style="background: #ff7675; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-size: 14px; font-weight: bold;">Logout</a>
            
            <?php else: ?>
                <!-- লগইন করা না থাকলে Login এবং Register দেখাবে -->
                <a href="<?php echo SITEURL; ?>views/auth/login.php" style="color: white; text-decoration: none; font-size: 15px;">Login</a>
                <a href="<?php echo SITEURL; ?>views/auth/register.php" style="border: 1px solid white; color: white; padding: 7px 15px; border-radius: 5px; text-decoration: none; font-size: 14px;">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>