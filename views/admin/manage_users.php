<?php
ob_start();
require_once('../../config/db_connect.php');
if(!isset($_SESSION['role'])||$_SESSION['role']!=='admin'){header('location:'.SITEURL.'views/auth/login.php');exit();}

// Role change
if(isset($_GET['role']) && isset($_GET['id'])) {
    $uid = (int)$_GET['id'];
    $role = mysqli_real_escape_string($conn, $_GET['role']);
    $allowed = ['user','admin','vendor'];
    if(in_array($role,$allowed)) mysqli_query($conn,"UPDATE users SET role='$role' WHERE id=$uid");
    header('location:manage_users.php'); exit();
}
// Delete
if(isset($_GET['delete'])) {
    $uid=(int)$_GET['delete'];
    if($uid != $_SESSION['user_id']) mysqli_query($conn,"DELETE FROM users WHERE id=$uid");
    header('location:manage_users.php'); exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users | EventPro Admin</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
<style>
:root{--slate-900:#0f172a;--slate-800:#1e293b;--slate-500:#64748b;--blue-600:#2563eb;--bg-light:#f1f5f9}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'DM Sans',sans-serif;background:var(--bg-light);color:var(--slate-800)}
.admin-shell{display:flex;min-height:100vh}
.sidebar{width:260px;background:var(--slate-900);color:#fff;position:fixed;height:100vh;z-index:100}
.brand{padding:25px;display:flex;align-items:center;gap:12px;border-bottom:1px solid var(--slate-800)}
.brand-icon{width:35px;height:35px;background:var(--blue-600);border-radius:8px;display:flex;align-items:center;justify-content:center}
.brand-text .name{font-family:'Playfair Display',serif;font-size:18px;font-weight:700}
nav{padding:20px 15px}
nav a{display:flex;align-items:center;gap:12px;padding:12px 15px;color:#94a3b8;text-decoration:none;border-radius:8px;font-size:14px;margin-bottom:5px;transition:.3s}
nav a:hover,nav a.active{background:var(--slate-800);color:#fff}
nav a.active{background:var(--blue-600)}
.main-content{margin-left:260px;flex:1;padding:40px}
.page-title{font-family:'Playfair Display',serif;font-size:28px;font-weight:700;margin-bottom:25px}
.card{background:#fff;border-radius:12px;border:1px solid #e2e8f0;overflow-x:auto}
.data-table{width:100%;border-collapse:collapse}
.data-table th{background:#f8fafc;padding:15px 20px;font-size:12px;text-transform:uppercase;color:var(--slate-500);font-weight:700;text-align:left;border-bottom:2px solid #f1f5f9}
.data-table td{padding:16px 20px;border-bottom:1px solid #f8fafc;font-size:14px;vertical-align:middle}
.role-badge{padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;text-transform:uppercase}
.role-admin{background:#fde68a;color:#92400e}
.role-user{background:#dbeafe;color:#1e40af}
.role-vendor{background:#d1fae5;color:#065f46}
.action-btn{padding:5px 10px;border-radius:6px;text-decoration:none;font-size:11px;font-weight:700;margin-right:4px;display:inline-block;transition:.2s}
</style>
</head>
<body>
<div class="admin-shell">
<aside class="sidebar">
    <div class="brand">
        <div class="brand-icon"><i class="ti ti-layout-dashboard"></i></div>
        <div class="brand-text"><p class="name">EventPro</p></div>
    </div>
    <nav>
        <a href="dashboard.php"><i class="ti ti-chart-pie"></i> <span>Dashboard</span></a>
        <a href="manage_events.php"><i class="ti ti-calendar-event"></i> <span>Events</span></a>
        <a href="manage_packages.php"><i class="ti ti-package"></i> <span>Packages</span></a>
        <a href="manage_vendors.php"><i class="ti ti-building-store"></i> <span>Vendors</span></a>
        <a href="manage_bookings.php"><i class="ti ti-receipt"></i> <span>Bookings</span></a>
        <a href="manage_users.php" class="active"><i class="ti ti-users"></i> <span>Users</span></a>
        <a href="../auth/logout.php" style="margin-top:50px;color:#f87171"><i class="ti ti-logout"></i> <span>Sign Out</span></a>
    </nav>
</aside>
<main class="main-content">
    <h1 class="page-title">User Management</h1>
    <div class="card">
    <table class="data-table">
        <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Joined</th><th>Actions</th></tr></thead>
        <tbody>
        <?php
        $res=mysqli_query($conn,"SELECT * FROM users ORDER BY id DESC");
        while($u=mysqli_fetch_assoc($res)):
        ?>
        <tr>
            <td style="color:var(--slate-500)">#<?php echo $u['id']; ?></td>
            <td style="font-weight:700"><?php echo htmlspecialchars($u['name']); ?></td>
            <td style="color:var(--slate-500)"><?php echo htmlspecialchars($u['email']); ?></td>
            <td><?php echo htmlspecialchars($u['phone']??'—'); ?></td>
            <td><span class="role-badge role-<?php echo $u['role']; ?>"><?php echo $u['role']; ?></span></td>
            <td style="color:var(--slate-500);font-size:12px"><?php echo date('d M Y', strtotime($u['created_at'])); ?></td>
            <td>
                <?php if($u['id'] != $_SESSION['user_id']): ?>
                <?php if($u['role']!='admin'): ?>
                <a href="?role=admin&id=<?php echo $u['id']; ?>" class="action-btn" style="background:#fde68a;color:#92400e" onclick="return confirm('Make admin?')">→ Admin</a>
                <?php endif; ?>
                <?php if($u['role']!='vendor'): ?>
                <a href="?role=vendor&id=<?php echo $u['id']; ?>" class="action-btn" style="background:#d1fae5;color:#065f46">→ Vendor</a>
                <?php endif; ?>
                <?php if($u['role']!='user'): ?>
                <a href="?role=user&id=<?php echo $u['id']; ?>" class="action-btn" style="background:#dbeafe;color:#1e40af">→ User</a>
                <?php endif; ?>
                <a href="?delete=<?php echo $u['id']; ?>" class="action-btn" style="background:#fee2e2;color:#991b1b" onclick="return confirm('Delete this user?')"><i class="ti ti-trash"></i></a>
                <?php else: ?>
                <span style="color:#94a3b8;font-size:12px">(You)</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    </div>
</main>
</div>
<?php ob_end_flush(); ?>
</body>
</html>
