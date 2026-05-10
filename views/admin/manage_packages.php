<?php
ob_start();
require_once('../../config/db_connect.php');
if(!isset($_SESSION['role'])||$_SESSION['role']!=='admin'){header('location:'.SITEURL.'views/auth/login.php');exit();}

// Delete
if(isset($_GET['delete'])) { $id=(int)$_GET['delete']; mysqli_query($conn,"DELETE FROM packages WHERE id=$id"); header('location:manage_packages.php'); exit(); }

// Add
if(isset($_POST['add_pkg'])) {
    $eid  = (int)$_POST['event_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price= (float)$_POST['price'];
    $details = mysqli_real_escape_string($conn, $_POST['details']);
    $services= mysqli_real_escape_string($conn, $_POST['included_services']);
    mysqli_query($conn,"INSERT INTO packages (event_id,name,price,details,included_services,created_at) VALUES ($eid,'$name',$price,'$details','$services',NOW())");
    header('location:manage_packages.php'); exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Packages | EventPro Admin</title>
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
.grid-layout{display:grid;grid-template-columns:340px 1fr;gap:25px;align-items:start}
.card{background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:25px}
.form-group{margin-bottom:18px}
.form-group label{display:block;font-size:12px;font-weight:700;color:var(--slate-500);text-transform:uppercase;margin-bottom:8px}
.form-control{width:100%;padding:10px 12px;border:1px solid #e2e8f0;border-radius:8px;font-family:inherit;font-size:14px}
.btn-primary{background:var(--blue-600);color:#fff;border:none;padding:12px;width:100%;border-radius:8px;font-weight:700;cursor:pointer}
.data-table{width:100%;border-collapse:collapse}
.data-table th{background:#f8fafc;padding:12px 15px;font-size:12px;text-transform:uppercase;color:var(--slate-500);font-weight:700;text-align:left;border-bottom:2px solid #f1f5f9}
.data-table td{padding:14px 15px;border-bottom:1px solid #f8fafc;font-size:14px}
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
        <a href="manage_packages.php" class="active"><i class="ti ti-package"></i> <span>Packages</span></a>
        <a href="manage_vendors.php"><i class="ti ti-building-store"></i> <span>Vendors</span></a>
        <a href="manage_bookings.php"><i class="ti ti-receipt"></i> <span>Bookings</span></a>
        <a href="manage_users.php"><i class="ti ti-users"></i> <span>Users</span></a>
        <a href="../auth/logout.php" style="margin-top:50px;color:#f87171"><i class="ti ti-logout"></i> <span>Sign Out</span></a>
    </nav>
</aside>
<main class="main-content">
    <h1 class="page-title">Package Management</h1>
    <p style="color:var(--slate-500);font-size:14px;margin-bottom:25px">Create pricing packages for each event</p>
    <div class="grid-layout">
        <div class="card">
            <h3 style="margin-bottom:20px;font-size:17px"><i class="ti ti-plus" style="color:var(--blue-600)"></i> Add Package</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Event</label>
                    <select name="event_id" class="form-control" required>
                        <option value="">-- Select Event --</option>
                        <?php $ev=mysqli_query($conn,"SELECT id,name FROM events WHERE status='active' ORDER BY name"); while($e=mysqli_fetch_assoc($ev)) echo "<option value='{$e['id']}'>".htmlspecialchars($e['name'])."</option>"; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Package Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Gold Package" required>
                </div>
                <div class="form-group">
                    <label>Price (BDT)</label>
                    <input type="number" name="price" class="form-control" placeholder="50000" step="0.01" required>
                </div>
                <div class="form-group">
                    <label>Details</label>
                    <textarea name="details" class="form-control" rows="2" placeholder="Short description..."></textarea>
                </div>
                <div class="form-group">
                    <label>Included Services</label>
                    <textarea name="included_services" class="form-control" rows="3" placeholder="Decoration, Catering, Photography..."></textarea>
                </div>
                <button type="submit" name="add_pkg" class="btn-primary">Save Package</button>
            </form>
        </div>
        <div class="card" style="padding:0;overflow-x:auto">
            <table class="data-table">
                <thead><tr><th>Package</th><th>Event</th><th>Price</th><th>Services</th><th>Actions</th></tr></thead>
                <tbody>
                <?php
                $pkgs=mysqli_query($conn,"SELECT p.*,e.name as ename FROM packages p JOIN events e ON p.event_id=e.id ORDER BY p.id DESC");
                if($pkgs&&mysqli_num_rows($pkgs)>0):while($pk=mysqli_fetch_assoc($pkgs)):
                ?>
                <tr>
                    <td style="font-weight:700"><?php echo htmlspecialchars($pk['name']); ?></td>
                    <td style="color:var(--slate-500)"><?php echo htmlspecialchars($pk['ename']); ?></td>
                    <td style="font-weight:700;color:var(--blue-600)">৳ <?php echo number_format($pk['price']); ?></td>
                    <td style="font-size:12px;color:var(--slate-500)"><?php echo htmlspecialchars(substr($pk['included_services'],0,60)); ?>...</td>
                    <td><a href="?delete=<?php echo $pk['id']; ?>" style="color:#f87171;font-size:18px;text-decoration:none" onclick="return confirm('Delete this package?')"><i class="ti ti-trash"></i></a></td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--slate-500)">No packages yet.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
</div>
<?php ob_end_flush(); ?>
</body>
</html>
