<?php
ob_start();
require_once('../../config/db_connect.php');
if(!isset($_SESSION['role'])||$_SESSION['role']!=='admin'){header('location:'.SITEURL.'views/auth/login.php');exit();}

if(isset($_POST['add_vendor'])) {
    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $stype   = mysqli_real_escape_string($conn, $_POST['service_type']);
    $desc    = mysqli_real_escape_string($conn, $_POST['description']);
    $price   = (float)$_POST['price'];
    $phone   = mysqli_real_escape_string($conn, $_POST['phone']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $status  = 'active';

    $image_name = '';
    if(!empty($_FILES['image']['name'])) {
        $image_name = time().'_'.str_replace(' ','_',$_FILES['image']['name']);
        if(!is_dir('../../uploads/vendors/')) mkdir('../../uploads/vendors/',0777,true);
        move_uploaded_file($_FILES['image']['tmp_name'], '../../uploads/vendors/'.$image_name);
    }

    mysqli_query($conn,"INSERT INTO vendors (name,service_type,description,price,phone,email,image,status,created_at) VALUES ('$name','$stype','$desc',$price,'$phone','$email','$image_name','$status',NOW())");
    header('location:manage_vendors.php'); exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Vendor | EventPro Admin</title>
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
.card{background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:35px;max-width:650px}
.form-group{margin-bottom:20px}
.form-group label{display:block;font-size:12px;font-weight:700;color:var(--slate-500);text-transform:uppercase;margin-bottom:8px}
.form-control{width:100%;padding:11px 13px;border:1px solid #e2e8f0;border-radius:8px;font-family:inherit;font-size:14px;transition:.2s}
.form-control:focus{outline:none;border-color:var(--blue-600);box-shadow:0 0 0 3px rgba(37,99,235,.1)}
.grid2{display:grid;grid-template-columns:1fr 1fr;gap:15px}
.btn-primary{background:var(--blue-600);color:#fff;border:none;padding:13px 30px;border-radius:8px;font-weight:700;cursor:pointer;font-size:15px;transition:.2s}
.btn-primary:hover{opacity:.9}
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
        <a href="manage_vendors.php" class="active"><i class="ti ti-building-store"></i> <span>Vendors</span></a>
        <a href="manage_bookings.php"><i class="ti ti-receipt"></i> <span>Bookings</span></a>
        <a href="manage_users.php"><i class="ti ti-users"></i> <span>Users</span></a>
        <a href="../auth/logout.php" style="margin-top:50px;color:#f87171"><i class="ti ti-logout"></i> <span>Sign Out</span></a>
    </nav>
</aside>
<main class="main-content">
    <div style="display:flex;align-items:center;gap:15px;margin-bottom:25px">
        <a href="manage_vendors.php" style="color:var(--slate-500);text-decoration:none"><i class="ti ti-arrow-left" style="font-size:20px"></i></a>
        <h1 class="page-title" style="margin:0">Add New Vendor</h1>
    </div>
    <div class="card">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Vendor Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. Moments Photography" required>
            </div>
            <div class="form-group">
                <label>Service Type</label>
                <select name="service_type" class="form-control" required>
                    <option value="">-- Select Type --</option>
                    <option value="photographer">Photographer</option>
                    <option value="decorator">Decorator</option>
                    <option value="caterer">Caterer</option>
                    <option value="dj">DJ</option>
                    <option value="florist">Florist</option>
                    <option value="videographer">Videographer</option>
                    <option value="makeup_artist">Makeup Artist</option>
                    <option value="sound_system">Sound System</option>
                </select>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Brief description of services..."></textarea>
            </div>
            <div class="grid2">
                <div class="form-group">
                    <label>Starting Price (BDT)</label>
                    <input type="number" name="price" class="form-control" placeholder="15000" step="0.01" required>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" placeholder="+880 1XXX-XXXXXX">
                </div>
            </div>
            <div class="grid2">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="vendor@email.com">
                </div>
                <div class="form-group">
                    <label>Profile Image</label>
                    <input type="file" name="image" class="form-control" style="padding:8px">
                </div>
            </div>
            <button type="submit" name="add_vendor" class="btn-primary"><i class="ti ti-plus"></i> Add Vendor</button>
        </form>
    </div>
</main>
</div>
<?php ob_end_flush(); ?>
</body>
</html>
