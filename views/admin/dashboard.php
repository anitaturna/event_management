<?php 
// 1. Start Output Buffering
ob_start();

// 2. Load Database & Constants
require_once('../../config/db_connect.php'); 

// 3. Admin Access Control (Security)
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    $redirect_url = defined('SITEURL') ? SITEURL.'views/auth/login.php' : '../auth/login.php';
    header('Location: ' . $redirect_url);
    exit();
}

// 4. User Name Handling
$user_name = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Administrator';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Executive Dashboard | EventPro</title>

    <!-- Icons & Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --slate-900: #0f172a;
            --slate-800: #1e293b;
            --slate-500: #64748b;
            --blue-600: #2563eb;
            --bg-light: #f8fafc;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            font-family: 'Inter', sans-serif; 
            background: var(--bg-light); 
            color: var(--slate-800); 
            line-height: 1.5; 
        }

        /* 1. Dashboard Layout Structure */
        .admin-shell { 
            display: flex; 
            min-height: 100vh; 
        }

        /* মেইন কন্টেন্ট এলাকা (সাইডবারের জন্য ২৬০পিক্সেল জায়গা ছাড়া হয়েছে) */
        .admin-content { 
            margin-left: 260px; 
            flex: 1; 
            padding: 40px; 
            width: calc(100% - 260px);
            transition: all 0.3s ease;
        }

        .topbar { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 40px; 
            border-bottom: 1px solid #e2e8f0; 
            padding-bottom: 20px; 
        }
        
        .page-title { 
            font-family: 'Playfair Display', serif; 
            font-size: 32px; 
            font-weight: 700; 
            color: var(--slate-900);
        }
        
        .top-badges { display: flex; gap: 10px; }
        .badge-box { 
            background: #fff; 
            border: 1px solid #e2e8f0; 
            padding: 8px 15px; 
            border-radius: 8px; 
            font-size: 13px; 
            color: var(--slate-500); 
            display: flex; 
            align-items: center; 
            gap: 8px; 
        }

        /* KPI Cards */
        .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .kpi-card { background: #fff; padding: 25px; border-radius: 16px; border: 1px solid #e2e8f0; position: relative; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .kpi-card.blue { border-left: 5px solid var(--blue-600); }
        .kpi-card.orange { border-left: 5px solid #f59e0b; }
        .kpi-card.green { border-left: 5px solid #10b981; }
        .kpi-value { font-size: 32px; font-weight: 700; margin-top: 10px; color: var(--slate-900); }
        .kpi-card i { position: absolute; right: 20px; top: 50%; transform: translateY(-50%); font-size: 40px; opacity: 0.1; }

        /* Lower Grid Panels */
        .bottom-grid { display: grid; grid-template-columns: 1.5fr 1fr 1fr; gap: 20px; }
        .panel { background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 25px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .panel-title { font-size: 16px; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; color: var(--slate-900); }
        
        .action-link { display: flex; align-items: center; gap: 15px; background: #f8fafc; padding: 15px; border-radius: 10px; text-decoration: none; color: inherit; border: 1px solid #e2e8f0; transition: 0.2s; margin-bottom: 10px; }
        .action-link:hover { transform: translateX(5px); border-color: var(--blue-600); background: #fff; }
        .action-icon { width: 40px; height: 40px; background: #fff; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--blue-600); border: 1px solid #e2e8f0; }

        .health-panel { background: var(--slate-900); color: #fff; }
        .progress-group { margin-bottom: 18px; }
        .progress-label { display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 5px; color: #94a3b8; }
        .progress-bg { height: 6px; background: #334155; border-radius: 10px; overflow: hidden; }
        .progress-fill { height: 100%; border-radius: 10px; }

        /* Responsive Mobile Fix */
        @media (max-width: 1200px) {
            .bottom-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 992px) {
            .admin-content { margin-left: 0; width: 100%; padding: 20px; }
            .topbar { flex-direction: column; align-items: flex-start; gap: 15px; }
            .bottom-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <div class="admin-shell">
        
        <!-- ১. ভার্টিকাল সাইডবার ইনক্লুড (sidebar.php) -->
        <?php include('sidebar.php'); ?>

        <!-- ২. মেইন ড্যাশবোর্ড কন্টেন্ট -->
        <main class="admin-content">
            
            <!-- টপ বার -->
            <div class="topbar">
                <div>
                    <h1 class="page-title">Executive Dashboard</h1>
                    <p style="color: var(--slate-500); font-size: 14px;">Welcome back, <strong><?php echo $user_name; ?></strong></p>
                </div>
                <div class="top-badges">
                    <div class="badge-box"><i class="ti ti-calendar"></i> <?php echo date('D, d M Y'); ?></div>
                    <div class="badge-box">
                        <span style="color: #10b981;">●</span> Server Online
                    </div>
                </div>
            </div>

            <!-- পরিসংখ্যান কার্ডসমূহ -->
            <div class="kpi-grid">
                <div class="kpi-card orange">
                    <i class="ti ti-ticket"></i>
                    <p style="font-size: 12px; color: var(--slate-500); text-transform: uppercase; font-weight: 700;">Total Bookings</p>
                    <div class="kpi-value">
                        <?php 
                        $res = mysqli_query($conn, "SELECT COUNT(id) as total FROM bookings");
                        $data = mysqli_fetch_assoc($res);
                        echo number_format($data['total'] ?? 0);
                        ?>
                    </div>
                </div>

                <div class="kpi-card green">
                    <i class="ti ti-currency-taka"></i>
                    <p style="font-size: 12px; color: var(--slate-500); text-transform: uppercase; font-weight: 700;">Gross Revenue</p>
                    <div class="kpi-value">
                        ৳ <?php 
                        $res = mysqli_query($conn, "SELECT SUM(amount) as total FROM payments WHERE status='success'");
                        $data = mysqli_fetch_assoc($res);
                        echo number_format($data['total'] ?? 0);
                        ?>
                    </div>
                </div>

                <div class="kpi-card blue">
                    <i class="ti ti-device-analytics"></i>
                    <p style="font-size: 12px; color: var(--slate-500); text-transform: uppercase; font-weight: 700;">Active Events</p>
                    <div class="kpi-value">
                        <?php 
                        $res = mysqli_query($conn, "SELECT COUNT(id) as total FROM events WHERE status='active'");
                        $data = mysqli_fetch_assoc($res);
                        echo number_format($data['total'] ?? 0);
                        ?>
                    </div>
                </div>
            </div>

            <div class="bottom-grid">
                <!-- কুইক অ্যাকশন মেনু -->
                <div class="panel">
                    <h3 class="panel-title"><i class="ti ti-rocket"></i> Quick Actions</h3>
                    <a href="manage_events.php" class="action-link">
                        <div class="action-icon"><i class="ti ti-calendar-plus"></i></div>
                        <div>
                            <p style="font-weight:700; font-size:14px; color:var(--slate-900);">Manage Events</p>
                            <p style="font-size:11px; color:var(--slate-500);">Add or edit campaigns</p>
                        </div>
                    </a>
                    <a href="manage_packages.php" class="action-link">
                        <div class="action-icon"><i class="ti ti-box-padding"></i></div>
                        <div>
                            <p style="font-weight:700; font-size:14px; color:var(--slate-900);">Manage Packages</p>
                            <p style="font-size:11px; color:var(--slate-500);">Create event pricing</p>
                        </div>
                    </a>
                    <a href="manage_vendors.php" class="action-link">
                        <div class="action-icon"><i class="ti ti-building-store"></i></div>
                        <div>
                            <p style="font-weight:700; font-size:14px; color:var(--slate-900);">Vendor List</p>
                            <p style="font-size:11px; color:var(--slate-500);">Manage partners</p>
                        </div>
                    </a>
                </div>

                <!-- সাম্প্রতিক ইভেন্ট অ্যাক্টিভিটি -->
                <div class="panel">
                    <h3 class="panel-title"><i class="ti ti-history"></i> Recent Events</h3>
                    <?php 
                    $q = "SELECT name, category FROM events ORDER BY id DESC LIMIT 5";
                    $r = mysqli_query($conn, $q);
                    if($r && mysqli_num_rows($r) > 0):
                        while($row = mysqli_fetch_assoc($r)): ?>
                            <div style="padding: 12px 0; border-bottom: 1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
                                <div>
                                    <p style="font-size: 13px; font-weight: 700; color:var(--slate-900);"><?php echo htmlspecialchars($row['name']); ?></p>
                                    <p style="font-size: 11px; color: var(--slate-500);"><?php echo htmlspecialchars($row['category']); ?></p>
                                </div>
                                <span style="font-size:10px; background:#dcfce7; color:#166534; padding:2px 8px; border-radius:10px; font-weight:700;">Active</span>
                            </div>
                        <?php endwhile; 
                    else: ?>
                        <p style="font-size:13px; color:var(--slate-500); text-align:center; padding: 20px;">No recent events.</p>
                    <?php endif; ?>
                </div>

                <!-- সিস্টেম হেলথ প্যানেল -->
                <div class="panel health-panel">
                    <h3 class="panel-title" style="color:#fff;"><i class="ti ti-pulse"></i> System Health</h3>
                    <div class="progress-group">
                        <div class="progress-label"><span>Database Usage</span><span>14%</span></div>
                        <div class="progress-bg"><div class="progress-fill" style="width:14%; background:#10b981;"></div></div>
                    </div>
                    <div class="progress-group">
                        <div class="progress-label"><span>Server Load</span><span>28%</span></div>
                        <div class="progress-bg"><div class="progress-fill" style="width:28%; background:#2563eb;"></div></div>
                    </div>
                    <div class="progress-group">
                        <div class="progress-label"><span>Storage</span><span>45%</span></div>
                        <div class="progress-bg"><div class="progress-fill" style="width:45%; background:#fbbf24;"></div></div>
                    </div>
                    <div style="margin-top:25px; font-size:11px; color:#64748b; text-align:center; border-top:1px solid #334155; padding-top:15px;">
                        Last Refreshed: <?php echo date('H:i A'); ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

</body>
</html>
<?php 
ob_end_flush(); 
?>