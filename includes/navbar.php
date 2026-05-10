<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<style>
.main-nav{background:linear-gradient(135deg,#0f172a,#1e293b);padding:0;box-shadow:0 2px 20px rgba(0,0,0,.2);font-family:'Inter',sans-serif;position:sticky;top:0;z-index:999}
.main-nav .nav-inner{width:90%;max-width:1200px;margin:auto;display:flex;justify-content:space-between;align-items:center;height:62px}
.nav-brand{color:#fff;text-decoration:none;font-size:20px;font-weight:800;letter-spacing:1px;display:flex;align-items:center;gap:8px}
.nav-brand span{color:#60a5fa}
.nav-links{display:flex;gap:5px;align-items:center}
.nav-links a{color:#94a3b8;text-decoration:none;font-size:14px;padding:8px 14px;border-radius:8px;transition:.2s;font-weight:500}
.nav-links a:hover{background:rgba(255,255,255,.1);color:#fff}
.nav-links .btn-login{border:1px solid #334155;color:#e2e8f0}
.nav-links .btn-reg{background:#2563eb;color:#fff;border:none}
.nav-links .btn-reg:hover{background:#1d4ed8}
.nav-links .btn-logout{background:#ef4444;color:#fff;border:none;font-weight:700}
.nav-links .btn-logout:hover{background:#dc2626}
.nav-links .admin-link{color:#fbbf24;font-weight:700}
.nav-links .vendor-link{color:#34d399;font-weight:700}
</style>

<nav class="main-nav">
    <div class="nav-inner">
        <a href="<?php echo SITEURL; ?>" class="nav-brand">
            <i class="ti ti-confetti"></i> EVENT<span>PRO</span>
        </a>
        <div class="nav-links">
            <a href="<?php echo SITEURL; ?>">Home</a>
            <a href="<?php echo SITEURL; ?>views/user/events_list.php">Events</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="<?php echo SITEURL; ?>views/user/my_bookings.php">My Bookings</a>
                <?php if($_SESSION['role']=='admin'): ?>
                    <a href="<?php echo SITEURL; ?>views/admin/dashboard.php" class="admin-link"><i class="ti ti-layout-dashboard"></i> Admin Panel</a>
                <?php elseif($_SESSION['role']=='vendor'): ?>
                    <a href="<?php echo SITEURL; ?>views/admin/manage_vendors.php" class="vendor-link"><i class="ti ti-building-store"></i> Vendor Dashboard</a>
                <?php endif; ?>
                <span style="color:#334155">|</span>
                <span style="color:#94a3b8;font-size:13px">Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a href="<?php echo SITEURL; ?>views/auth/logout.php" class="btn-logout"><i class="ti ti-logout"></i> Logout</a>
            <?php else: ?>
                <a href="<?php echo SITEURL; ?>views/auth/login.php" class="btn-login">Login</a>
                <a href="<?php echo SITEURL; ?>views/auth/register.php" class="btn-reg">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
