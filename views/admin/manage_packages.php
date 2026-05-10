<?php
ob_start();
// 1. Load Database & Constants
require_once('../../config/db_connect.php');

// 2. Session Check
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 3. Admin Access Control
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header('location:'.SITEURL.'views/auth/login.php');
    exit();
}

// 4. Delete Package Logic
if(isset($_GET['delete'])) { 
    $id = (int)$_GET['delete']; 
    if(mysqli_query($conn, "DELETE FROM packages WHERE id=$id")) {
        $_SESSION['msg'] = "Package deleted successfully!";
    }
    header('location:manage_packages.php'); 
    exit(); 
}

// 5. Add Package Logic
if(isset($_POST['add_pkg'])) {
    $eid  = (int)$_POST['event_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price= (float)$_POST['price'];
    $details = mysqli_real_escape_string($conn, $_POST['details']);
    $services= mysqli_real_escape_string($conn, $_POST['included_services']);
    
    $sql = "INSERT INTO packages (event_id, name, price, details, included_services, created_at) 
            VALUES ($eid, '$name', $price, '$details', '$services', NOW())";
            
    if(mysqli_query($conn, $sql)) {
        $_SESSION['msg'] = "New package added successfully!";
    }
    header('location:manage_packages.php'); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Packages | EventPro Admin</title>
    
    <!-- Icons & Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #2563eb;
            --dark: #0f172a;
            --slate-500: #64748b;
            --bg-light: #f8fafc;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            font-family: 'Inter', sans-serif; 
            background: var(--bg-light); 
            color: var(--dark);
            line-height: 1.6;
        }

        /* Layout for Sidebar Integration */
        .admin-shell { display: flex; min-height: 100vh; }
        
        .admin-content { 
            margin-left: 260px; /* Matching sidebar width */
            flex: 1; 
            padding: 40px; 
            width: calc(100% - 260px);
        }

        .page-header { margin-bottom: 30px; }
        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 700;
            color: var(--dark);
        }

        .grid-layout {
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 30px;
            align-items: start;
        }

        /* Card Styling */
        .card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            padding: 30px;
        }

        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: var(--slate-500);
            text-transform: uppercase;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-family: inherit;
            font-size: 14px;
            transition: 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 14px;
            width: 100%;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        /* Table Design */
        .table-card { padding: 0; overflow: hidden; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th {
            background: #f8fafc;
            padding: 15px 20px;
            font-size: 12px;
            text-transform: uppercase;
            color: var(--slate-500);
            font-weight: 700;
            text-align: left;
            border-bottom: 2px solid #f1f5f9;
        }

        .data-table td {
            padding: 18px 20px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
            vertical-align: middle;
        }

        .price-tag {
            font-weight: 700;
            color: var(--primary);
            font-size: 15px;
        }

        .action-btn {
            color: #f87171;
            font-size: 20px;
            text-decoration: none;
            transition: 0.2s;
            display: inline-block;
        }
        .action-btn:hover { transform: scale(1.1); color: #ef4444; }

        @media (max-width: 1100px) {
            .grid-layout { grid-template-columns: 1fr; }
            .admin-content { margin-left: 0; width: 100%; padding: 20px; }
        }
    </style>
</head>
<body>

    <div class="admin-shell">
        <!-- Sidebar Inclusion -->
        <?php include('sidebar.php'); ?>

        <main class="admin-content">
            <header class="page-header">
                <h1 class="page-title">Package Management</h1>
                <p style="color:var(--slate-500); font-size:14px;">Define pricing and services for your event portfolio</p>
            </header>

            <div class="grid-layout">
                <!-- Add Package Form -->
                <div class="card">
                    <h3 style="margin-bottom: 25px; font-size: 1.2rem; font-family: 'Playfair Display', serif;">
                        <i class="ti ti-package-export" style="color:var(--primary)"></i> Create New Package
                    </h3>
                    <form method="POST">
                        <div class="form-group">
                            <label>Target Event</label>
                            <select name="event_id" class="form-control" required>
                                <option value="">-- Select Event --</option>
                                <?php 
                                $ev = mysqli_query($conn, "SELECT id, name FROM events WHERE status='active' ORDER BY name"); 
                                while($e = mysqli_fetch_assoc($ev)) {
                                    echo "<option value='{$e['id']}'>".htmlspecialchars($e['name'])."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Package Title</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Gold Package" required>
                        </div>
                        <div class="form-group">
                            <label>Base Price (BDT)</label>
                            <input type="number" name="price" class="form-control" placeholder="50000" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>Internal Details</label>
                            <textarea name="details" class="form-control" rows="2" placeholder="Private notes..."></textarea>
                        </div>
                        <div class="form-group">
                            <label>Included Services</label>
                            <textarea name="included_services" class="form-control" rows="4" placeholder="Decoration, Catering, Photography..."></textarea>
                        </div>
                        <button type="submit" name="add_pkg" class="btn-primary">
                            <i class="ti ti-device-floppy"></i> Save Package
                        </button>
                    </form>
                </div>

                <!-- Package List Table -->
                <div class="card table-card">
                    <div style="padding: 20px 30px; border-bottom: 1px solid #f1f5f9;">
                        <h3 style="font-size: 1.1rem;">Existing Packages</h3>
                    </div>
                    <div style="overflow-x: auto;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Package Info</th>
                                    <th>Event</th>
                                    <th>Price</th>
                                    <th>Services Preview</th>
                                    <th style="text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $pkgs = mysqli_query($conn, "SELECT p.*, e.name as ename FROM packages p JOIN events e ON p.event_id=e.id ORDER BY p.id DESC");
                            if($pkgs && mysqli_num_rows($pkgs) > 0):
                                while($pk = mysqli_fetch_assoc($pkgs)):
                            ?>
                            <tr>
                                <td>
                                    <div style="font-weight:700; color: var(--dark);"><?php echo htmlspecialchars($pk['name']); ?></div>
                                    <div style="font-size:11px; color: var(--slate-500);">#PKG-<?php echo $pk['id']; ?></div>
                                </td>
                                <td>
                                    <span style="background: #f1f5f9; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 500;">
                                        <?php echo htmlspecialchars($pk['ename']); ?>
                                    </span>
                                </td>
                                <td><span class="price-tag">৳ <?php echo number_format($pk['price']); ?></span></td>
                                <td style="font-size:13px; color:var(--slate-500); max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?php echo htmlspecialchars($pk['included_services']); ?>
                                </td>
                                <td style="text-align: right;">
                                    <a href="?delete=<?php echo $pk['id']; ?>" class="action-btn" title="Delete" onclick="return confirm('Are you sure you want to delete this package?')">
                                        <i class="ti ti-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr>
                                <td colspan="5" style="text-align:center; padding:50px; color:var(--slate-500)">
                                    <i class="ti ti-package-off" style="font-size: 32px; display: block; margin-bottom: 10px;"></i>
                                    No packages found.
                                </td>
                            </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <?php ob_end_flush(); ?>
</body>
</html>