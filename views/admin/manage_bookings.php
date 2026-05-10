<?php
ob_start();
require_once('../../config/db_connect.php');
if(!isset($_SESSION['role'])||$_SESSION['role']!=='admin'){header('location:'.SITEURL.'views/auth/login.php');exit();}

// Status update
if(isset($_GET['action']) && isset($_GET['id'])) {
    $bid = (int)$_GET['id'];
    $action = $_GET['action'];
    $allowed = ['approved','completed','cancelled'];
    if(in_array($action, $allowed)) {
        mysqli_query($conn, "UPDATE bookings SET status='$action' WHERE id=$bid");
    }
    header('location:manage_bookings.php'); exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Bookings | EventPro Admin</title>
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
.page-title{font-family:'Playfair Display',serif;font-size:28px;font-weight:700;margin-bottom:5px}
.card{background:#fff;border-radius:12px;border:1px solid #e2e8f0;overflow-x:auto;padding:0}
.data-table{width:100%;border-collapse:collapse}
.data-table th{background:#f8fafc;padding:15px 20px;font-size:12px;text-transform:uppercase;color:var(--slate-500);font-weight:700;text-align:left;border-bottom:2px solid #f1f5f9}
.data-table td{padding:16px 20px;border-bottom:1px solid #f8fafc;font-size:14px;vertical-align:middle}
.status-badge{padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;text-transform:uppercase}
.status-pending{background:#fef9c3;color:#854d0e}
.status-approved{background:#dcfce7;color:#166534}
.status-completed{background:#dbeafe;color:#1e40af}
.status-cancelled{background:#fee2e2;color:#991b1b}
.action-btn{padding:6px 12px;border-radius:6px;text-decoration:none;font-size:12px;font-weight:700;transition:.2s;display:inline-block;margin-right:5px}
.btn-approve{background:#dcfce7;color:#166534}
.btn-complete{background:#dbeafe;color:#1e40af}
.btn-cancel{background:#fee2e2;color:#991b1b}
.filter-row{display:flex;gap:10px;padding:20px;border-bottom:1px solid #f1f5f9;flex-wrap:wrap}
.filter-btn{padding:7px 16px;border-radius:20px;text-decoration:none;font-size:13px;font-weight:600;border:1px solid #e2e8f0;color:#64748b;background:#fff;transition:.2s}
.filter-btn.active,.filter-btn:hover{background:var(--blue-600);color:#fff;border-color:var(--blue-600)}
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
        <a href="manage_bookings.php" class="active"><i class="ti ti-receipt"></i> <span>Bookings</span></a>
        <a href="manage_users.php"><i class="ti ti-users"></i> <span>Users</span></a>
        <a href="../auth/logout.php" style="margin-top:50px;color:#f87171"><i class="ti ti-logout"></i> <span>Sign Out</span></a>
    </nav>
</aside>
<main class="main-content">
    <h1 class="page-title">Booking Management</h1>
    <p style="color:var(--slate-500);font-size:14px;margin-bottom:25px">Review, approve or cancel customer bookings</p>

    <?php
    $filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';
    $where = $filter ? "WHERE b.status='$filter'" : '';
    $statuses = ['','pending','approved','completed','cancelled'];
    $labels   = ['All','Pending','Approved','Completed','Cancelled'];
    ?>
    <div class="card">
        <div class="filter-row">
        <?php foreach($statuses as $i=>$s):
            $isActive = ($filter==$s) ? 'active' : '';
            $url = $s ? "manage_bookings.php?status=$s" : "manage_bookings.php";
            echo "<a href='$url' class='filter-btn $isActive'>{$labels[$i]}</a>";
        endforeach; ?>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Customer</th>
                    <th>Event & Package</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $sql = "SELECT b.*, u.name as uname, u.email, p.name as pkg_name, e.name as event_name
                    FROM bookings b
                    JOIN users u ON b.user_id=u.id
                    JOIN packages p ON b.package_id=p.id
                    JOIN events e ON p.event_id=e.id
                    $where
                    ORDER BY b.id DESC";
            $res = mysqli_query($conn, $sql);
            if($res && mysqli_num_rows($res)>0):
                while($row=mysqli_fetch_assoc($res)):
            ?>
            <tr>
                <td style="font-weight:700;color:#94a3b8">#<?php echo $row['id']; ?></td>
                <td>
                    <div style="font-weight:700"><?php echo htmlspecialchars($row['uname']); ?></div>
                    <div style="font-size:11px;color:var(--slate-500)"><?php echo htmlspecialchars($row['email']); ?></div>
                </td>
                <td>
                    <div style="font-weight:600"><?php echo htmlspecialchars($row['event_name']); ?></div>
                    <div style="font-size:12px;color:var(--slate-500)"><?php echo htmlspecialchars($row['pkg_name']); ?></div>
                </td>
                <td><?php echo date('d M Y', strtotime($row['event_date'])); ?></td>
                <td style="font-weight:700;color:var(--blue-600)">৳ <?php echo number_format($row['total_price']); ?></td>
                <td><span class="status-badge status-<?php echo $row['status']; ?>"><?php echo $row['status']; ?></span></td>
                <td>
                    <?php if($row['status']=='pending'): ?>
                        <a href="?action=approved&id=<?php echo $row['id']; ?>" class="action-btn btn-approve">Approve</a>
                        <a href="?action=cancelled&id=<?php echo $row['id']; ?>" class="action-btn btn-cancel" onclick="return confirm('Cancel this booking?')">Cancel</a>
                    <?php elseif($row['status']=='approved'): ?>
                        <a href="?action=completed&id=<?php echo $row['id']; ?>" class="action-btn btn-complete">Mark Complete</a>
                    <?php else: ?>
                        <span style="color:#94a3b8;font-size:13px">—</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; else: ?>
            <tr><td colspan="7" style="text-align:center;padding:50px;color:var(--slate-500)">No bookings found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
</div>
<?php ob_end_flush(); ?>
</body>
</html>
