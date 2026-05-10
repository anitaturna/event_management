<?php 
// 1. Start Output Buffering (Prevents "Headers already sent" errors)
ob_start();

// 2. Load Database & Constants
// This file now handles session_start() and $conn automatically
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
    <title>Corporate Dashboard | EventPro</title>

    <!-- Icons & Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">

    <style>
        :root {
            --slate-900: #0f172a;
            --slate-800: #1e293b;
            --slate-500: #64748b;
            --blue-600: #2563eb;
            --bg-light: #f1f5f9;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg-light); color: var(--slate-800); line-height: 1.5; }

        .admin-shell { display: flex; min-height: 100vh; }

        /* Sidebar Styling */
        .sidebar { width: 260px; background: var(--slate-900); color: #fff; position: fixed; height: 100vh; z-index: 100; border-right: 1px solid var(--slate-800); }
        .brand { padding: 25px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid var(--slate-800); }
        .brand-icon { width: 35px; height: 35px; background: var(--blue-600); border-radius: 8px; display: flex; align-items: center; justify-content: center; }
        .brand-text .name { font-family: 'Playfair Display', serif; font-size: 18px; font-weight: 700; }
        .brand-text .sub { font-size: 10px; color: var(--slate-500); text-transform: uppercase; letter-spacing: 1.5px; }

        nav { padding: 20px 15px; }
        .nav-label { font-size: 11px; color: var(--slate-500); text-transform: uppercase; padding: 15px 10px 10px; font-weight: 700; letter-spacing: 1px; }
        nav a { display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: #94a3b8; text-decoration: none; border-radius: 8px; transition: 0.3s; font-size: 14px; margin-bottom: 5px; }
        nav a:hover, nav a.active { background: var(--slate-800); color: #fff; }
        nav a.active { background: var(--blue-600); color: #fff; }

        .sidebar-footer { position: absolute; bottom: 0; width: 100%; padding: 20px; border-top: 1px solid var(--slate-800); }
        .logout-btn { color: #f87171; text-decoration: none; display: flex; align-items: center; gap: 10px; font-size: 14px; }

        /* Main Content */
        .main-content { margin-left: 260px; flex: 1; padding: 40px; }
        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; border-bottom: 1px solid #e2e8f0; padding-bottom: 20px; }
        .page-title { font-family: 'Playfair Display', serif; font-size: 28px; font-weight: 700; }
        .top-badges { display: flex; gap: 10px; }
        .badge-box { background: #fff; border: 1px solid #e2e8f0; padding: 8px 15px; border-radius: 8px; font-size: 13px; color: var(--slate-500); display: flex; align-items: center; gap: 8px; }

        /* KPI Cards */
        .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .kpi-card { background: #fff; padding: 25px; border-radius: 12px; border: 1px solid #e2e8f0; position: relative; }
        .kpi-card.blue { border-left: 5px solid var(--blue-600); }
        .kpi-card.orange { border-left: 5px solid #f59e0b; }
        .kpi-card.green { border-left: 5px solid #10b981; }
        .kpi-label { font-size: 12px; color: var(--slate-500); text-transform: uppercase; font-weight: 700; }
        .kpi-value { font-size: 32px; font-weight: 700; margin-top: 10px; }
        .kpi-card i { position: absolute; right: 20px; top: 50%; transform: translateY(-50%); font-size: 40px; opacity: 0.1; }

        /* Bottom Grid */
        .bottom-grid { display: grid; grid-template-columns: 1.5fr 1fr 1fr; gap: 20px; }
        .panel { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 25px; }
        .panel-title { font-size: 16px; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }

        /* Action Buttons */
        .action-link { display: flex; align-items: center; gap: 15px; background: #f8fafc; padding: 15px; border-radius: 10px; text-decoration: none; color: inherit; border: 1px solid #e2e8f0; transition: 0.2s; margin-bottom: 10px; }
        .action-link:hover { transform: translateX(5px); border-color: var(--blue-600); }
        .action-icon { width: 40px; height: 40px; background: #fff; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--blue-600); border: 1px solid #e2e8f0; }

        /* Health Panel */
        .health-panel { background: var(--slate-900); color: #fff; }
        .progress-group { margin-bottom: 18px; }
        .progress-label { display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 5px; color: #94a3b8; }
        .progress-bg { height: 6px; background: #334155; border-radius: 10px; overflow: hidden; }
        .progress-fill { height: 100%; border-radius: 10px; }

        @media (max-width: 1100px) {
            .bottom-grid { grid-template-columns: 1fr; }
            .sidebar { width: 70px; }
            .sidebar .brand-text, .sidebar .nav-label, .sidebar span { display: none; }
            .main-content { margin-left: 70px; }
        }
    </style>
</head>
<body>

<div class="admin-shell">
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon"><i class="ti ti-layout-dashboard"></i></div>
            <div class="brand-text">
                <p class="name">EventPro</p>
                <p class="sub">Admin Suite</p>
            </div>
        </div>
        <nav>
            <p class="nav-label">Core</p>
            <a href="dashboard.php" class="active"><i class="ti ti-chart-pie"></i> <span>Dashboard</span></a>
            <a href="manage_events.php"><i class="ti ti-calendar-event"></i> <span>Events</span></a>
            <a href="manage_packages.php"><i class="ti ti-packages"></i> <span>Packages</span></a> <!-- Added Package Option -->
            <a href="manage_vendors.php"><i class="ti ti-building-store"></i> <span>Vendors</span></a>
            
            <p class="nav-label">Operations</p>
            <a href="manage_bookings.php"><i class="ti ti-receipt"></i> <span>Bookings</span></a>
            <a href="manage_users.php"><i class="ti ti-users"></i> <span>User Control</span></a>
        </nav>
        <div class="sidebar-footer">
            <a href="../auth/logout.php" class="logout-btn"><i class="ti ti-logout"></i> <span>Sign Out</span></a>
        </div>
    </aside>

    <main class="main-content">
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

        <div class="kpi-grid">
            <!-- Total Bookings -->
            <div class="kpi-card orange">
                <i class="ti ti-ticket"></i>
                <p class="kpi-label">Total Bookings</p>
                <div class="kpi-value">
                    <?php 
                    $res = mysqli_query($conn, "SELECT COUNT(id) as total FROM bookings");
                    $data = $res ? mysqli_fetch_assoc($res) : null;
                    echo number_format($data['total'] ?? 0);
                    ?>
                </div>
            </div>

            <!-- Gross Revenue -->
            <div class="kpi-card green">
                <i class="ti ti-currency-taka"></i>
                <p class="kpi-label">Gross Revenue</p>
                <div class="kpi-value">
                    ৳ <?php 
                    $res = mysqli_query($conn, "SELECT SUM(amount) as total FROM payments WHERE status='success'");
                    $data = $res ? mysqli_fetch_assoc($res) : null;
                    echo number_format($data['total'] ?? 0);
                    ?>
                </div>
            </div>

            <!-- Active Events -->
            <div class="kpi-card blue">
                <i class="ti ti-device-analytics"></i>
                <p class="kpi-label">Active Events</p>
                <div class="kpi-value">
                    <?php 
                    $res = mysqli_query($conn, "SELECT COUNT(id) as total FROM events");
                    $data = $res ? mysqli_fetch_assoc($res) : null;
                    echo number_format($data['total'] ?? 0);
                    ?>
                </div>
            </div>
        </div>

        <div class="bottom-grid">
            <!-- Quick Actions -->
            <div class="panel">
                <h3 class="panel-title"><i class="ti ti-rocket"></i> Quick Actions</h3>
                <a href="manage_events.php" class="action-link">
                    <div class="action-icon"><i class="ti ti-plus"></i></div>
                    <div>
                        <p style="font-weight:700; font-size:14px;">Add New Event</p>
                        <p style="font-size:11px; color:var(--slate-500);">Launch new campaign</p>
                    </div>
                </a>
                <a href="manage_packages.php" class="action-link"> <!-- Added Add Package Quick Action -->
                    <div class="action-icon"><i class="ti ti-box-padding"></i></div>
                    <div>
                        <p style="font-weight:700; font-size:14px;">Add Package</p>
                        <p style="font-size:11px; color:var(--slate-500);">Create event pricing</p>
                    </div>
                </a>
                <a href="manage_bookings.php" class="action-link">
                    <div class="action-icon"><i class="ti ti-search"></i></div>
                    <div>
                        <p style="font-weight:700; font-size:14px;">Audit Bookings</p>
                        <p style="font-size:11px; color:var(--slate-500);">Verify transactions</p>
                    </div>
                </a>
            </div>

            <!-- Recent Events -->
            <div class="panel">
                <h3 class="panel-title"><i class="ti ti-history"></i> Recent Events</h3>
                <?php 
                $q = "SELECT name, category FROM events ORDER BY id DESC LIMIT 4";
                $r = mysqli_query($conn, $q);
                if($r && mysqli_num_rows($r) > 0):
                    while($row = mysqli_fetch_assoc($r)): ?>
                        <div style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
                            <div>
                                <p style="font-size: 13px; font-weight: 700;"><?php echo htmlspecialchars($row['name']); ?></p>
                                <p style="font-size: 11px; color: var(--slate-500);"><?php echo htmlspecialchars($row['category']); ?></p>
                            </div>
                            <span style="font-size:10px; background:#dcfce7; color:#166534; padding:2px 8px; border-radius:10px; font-weight:700;">Active</span>
                        </div>
                    <?php endwhile; 
                else: ?>
                    <p style="font-size:12px; color:var(--slate-500);">No recent events.</p>
                <?php endif; ?>
            </div>

            <!-- System Health -->
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
                <div style="margin-top:20px; font-size:11px; color:#64748b; text-align:center; border-top:1px solid #334155; padding-top:15px;">
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