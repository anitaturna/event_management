<?php 
// 1. Start Output Buffering
ob_start();

// 2. Load Database & Constants (Handles session_start automatically)
require_once('../../config/db_connect.php'); 

// 3. Admin Access Control
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    $redirect_url = defined('SITEURL') ? SITEURL.'views/auth/login.php' : '../../views/auth/login.php';
    header('Location: ' . $redirect_url);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vendors | EventPro Admin</title>

    <!-- Icons & Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">

    <style>
        :root {
            --slate-900: #0f172a; --slate-800: #1e293b; --slate-500: #64748b;
            --blue-600: #2563eb; --bg-light: #f1f5f9;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg-light); color: var(--slate-800); line-height: 1.5; }
        
        .admin-shell { display: flex; min-height: 100vh; }
        
        /* Sidebar Styles */
        .sidebar { width: 260px; background: var(--slate-900); color: #fff; position: fixed; height: 100vh; z-index: 100; }
        .brand { padding: 25px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid var(--slate-800); }
        .brand-icon { width: 35px; height: 35px; background: var(--blue-600); border-radius: 8px; display: flex; align-items: center; justify-content: center; }
        .brand-text .name { font-family: 'Playfair Display', serif; font-size: 18px; font-weight: 700; }
        
        nav { padding: 20px 15px; }
        nav a { display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: #94a3b8; text-decoration: none; border-radius: 8px; font-size: 14px; margin-bottom: 5px; transition: 0.3s; }
        nav a:hover, nav a.active { background: var(--slate-800); color: #fff; }
        nav a.active { background: var(--blue-600); }

        /* Main Content */
        .main-content { margin-left: 260px; flex: 1; padding: 40px; }
        
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .page-title { font-family: 'Playfair Display', serif; font-size: 28px; font-weight: 700; }
        
        .btn-primary { background: var(--blue-600); color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 700; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; font-size: 14px; }
        .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); }

        /* Card & Table Styles */
        .card { background: #fff; padding: 25px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); overflow-x: auto; }
        
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th { background: #f8fafc; text-align: left; padding: 15px; font-size: 12px; text-transform: uppercase; color: var(--slate-500); border-bottom: 2px solid #f1f5f9; }
        .data-table td { padding: 15px; border-bottom: 1px solid #f1f5f9; font-size: 14px; vertical-align: middle; }
        
        .status-badge { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; display: inline-block; }
        .status-active { background: #dcfce7; color: #166534; }
        .status-inactive { background: #f1f5f9; color: #64748b; }
        
        .action-btn { color: var(--slate-500); text-decoration: none; font-size: 18px; margin-right: 12px; transition: 0.2s; display: inline-block; }
        .action-btn:hover { color: var(--blue-600); transform: scale(1.1); }
        .action-btn.delete:hover { color: #f87171; }

        .service-tag { background: #f8fafc; border: 1px solid #e2e8f0; padding: 4px 10px; border-radius: 6px; font-size: 12px; color: var(--slate-800); }

        @media (max-width: 1024px) {
            .sidebar { width: 70px; }
            .sidebar .brand-text, .sidebar span { display: none; }
            .main-content { margin-left: 70px; }
        }
    </style>
</head>
<body>

<div class="admin-shell">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon"><i class="ti ti-layout-dashboard"></i></div>
            <div class="brand-text"><p class="name">EventPro</p></div>
        </div>
        <nav>
            <a href="dashboard.php"><i class="ti ti-chart-pie"></i> <span>Dashboard</span></a>
            <a href="manage_events.php"><i class="ti ti-calendar-event"></i> <span>Events</span></a>
            <a href="manage_vendors.php" class="active"><i class="ti ti-building-store"></i> <span>Vendors</span></a>
            <a href="manage_bookings.php"><i class="ti ti-receipt"></i> <span>Bookings</span></a>
            <a href="../auth/logout.php" style="margin-top: 50px; color: #f87171;"><i class="ti ti-logout"></i> <span>Sign Out</span></a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="page-header">
            <div>
                <h1 class="page-title">Vendor Network</h1>
                <p style="color: var(--slate-500); font-size: 14px;">Manage your service providers and partners</p>
            </div>
            <a href="add_vendor.php" class="btn-primary">
                <i class="ti ti-plus"></i> Add New Vendor
            </a>
        </header>

        <!-- Data Table Card -->
        <div class="card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Vendor Profile</th>
                        <th>Service Type</th>
                        <th>Pricing (Start)</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $sql = "SELECT * FROM vendors ORDER BY id DESC";
                    $res = mysqli_query($conn, $sql);

                    if($res && mysqli_num_rows($res) > 0) {
                        while($vendor = mysqli_fetch_assoc($res)) { 
                            // Determine status badge class
                            $status_class = (strtolower($vendor['status']) == 'active') ? 'status-active' : 'status-inactive';
                            ?>
                            <tr>
                                <td>
                                    <div style="font-weight: 700; color: var(--slate-900);">
                                        <?php echo htmlspecialchars($vendor['name']); ?>
                                    </div>
                                    <div style="font-size: 11px; color: var(--slate-500);">
                                        ID: #VND-<?php echo $vendor['id']; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="service-tag">
                                        <?php echo htmlspecialchars($vendor['service_type']); ?>
                                    </span>
                                </td>
                                <td style="font-weight: 500;">
                                    ৳ <?php echo number_format($vendor['price'] ?? 0); ?>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 4px; color: #f59e0b; font-weight: 700;">
                                        <i class="ti ti-star-filled" style="font-size: 14px;"></i> 
                                        <?php echo htmlspecialchars($vendor['rating'] ?? 'N/A'); ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge <?php echo $status_class; ?>">
                                        <?php echo htmlspecialchars($vendor['status']); ?>
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <a href="edit_vendor.php?id=<?php echo $vendor['id']; ?>" class="action-btn" title="Edit">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <a href="delete_vendor.php?id=<?php echo $vendor['id']; ?>" class="action-btn delete" title="Delete" onclick="return confirm('Are you sure you want to remove this vendor?');">
                                        <i class="ti ti-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php 
                        } 
                    } else {
                        // Empty State
                        echo "<tr><td colspan='6' style='text-align:center; padding: 40px; color: var(--slate-500);'>No vendors found in the network. Click 'Add New Vendor' to get started.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php 
// Flush Output Buffer
ob_end_flush(); 
?>
</body>
</html>