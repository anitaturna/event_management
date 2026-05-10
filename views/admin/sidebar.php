<?php 
$current_page = basename($_SERVER['PHP_SELF']); 
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

<style>
    :root {
        --side-bg: #0f172a;
        --side-hover: #1e293b;
        --side-active: #2563eb;
        --side-text: #94a3b8;
    }
    .admin-sidebar {
        width: 240px;
        background: var(--side-bg);
        color: #fff;
        position: fixed;
        top: 0; left: 0;
        height: 100vh;
        z-index: 1000;
        overflow-y: auto;
        border-right: 1px solid rgba(255,255,255,0.05);
        font-family: 'Inter', sans-serif;
        display: flex;
        flex-direction: column;
    }
    .brand-section {
        padding: 20px 15px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    .brand-icon {
        width: 30px; height: 35px;
        background: var(--side-active);
        border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
    }
    .brand-text h2 { margin: 0; font-size: 16px; font-weight: 700; }
    .brand-text p { margin: 0; font-size: 9px; color: var(--side-text); text-transform: uppercase; }

    .nav-section {
        padding: 15px 15px 5px;
        font-size: 10px;
        color: var(--side-text);
        text-transform: uppercase;
        font-weight: 700;
        opacity: 0.6;
    }
    .side-nav { padding: 0 10px; display: flex; flex-direction: column; gap: 2px; }
    .side-link {
        display: flex; align-items: center; gap: 10px;
        padding: 8px 12px;
        color: var(--side-text);
        text-decoration: none;
        border-radius: 6px;
        font-size: 13px;
        transition: 0.2s;
    }
    .side-link:hover, .side-link.active { background: var(--side-active); color: #fff; }
    .side-link i { font-size: 16px; }

    /* সাব-লিঙ্ক শুধু তখনই দেখাবে যখন আপনি ওই পেজে থাকবেন */
    .sub-link {
        padding: 6px 12px 6px 38px;
        font-size: 12px;
        opacity: 0.8;
        display: none; /* ডিফল্ট হাইড */
    }
    .active-sub { display: block !important; color: #fff; }

    .logout-section { margin-top: auto; padding: 15px; border-top: 1px solid rgba(255,255,255,0.05); }
    .logout-btn { 
        display: flex; align-items: center; gap: 8px; color: #f87171; 
        text-decoration: none; font-size: 13px; font-weight: 600; 
    }
</style>

<aside class="admin-sidebar">
    <div class="brand-section">
        <div class="brand-icon"><i class="ti ti-layout-dashboard"></i></div>
        <div class="brand-text"><h2>EventPro</h2><p>Admin Suite</p></div>
    </div>

    <div class="nav-section">Core</div>
    <div class="side-nav">
        <a href="dashboard.php" class="side-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
            <i class="ti ti-chart-pie"></i> <span>Dashboard</span>
        </a>
    </div>

    <div class="nav-section">Events</div>
    <div class="side-nav">
        <a href="manage_events.php" class="side-link <?php echo ($current_page == 'manage_events.php') ? 'active' : ''; ?>">
            <i class="ti ti-calendar-event"></i> <span>Manage Events</span>
        </a>
        <?php if($current_page == 'edit_event.php'): ?>
        <a href="#" class="side-link sub-link active-sub">
            <i class="ti ti-edit"></i> <span>Editing Event...</span>
        </a>
        <?php endif; ?>
        
        <a href="manage_packages.php" class="side-link <?php echo ($current_page == 'manage_packages.php') ? 'active' : ''; ?>">
            <i class="ti ti-packages"></i> <span>Packages</span>
        </a>
    </div>

    <div class="nav-section">Vendors</div>
    <div class="side-nav">
        <a href="manage_vendors.php" class="side-link <?php echo ($current_page == 'manage_vendors.php') ? 'active' : ''; ?>">
            <i class="ti ti-building-store"></i> <span>Vendor List</span>
        </a>
        <a href="add_vendor.php" class="side-link <?php echo ($current_page == 'add_vendor.php') ? 'active' : ''; ?>">
            <i class="ti ti-plus"></i> <span>Add Vendor</span>
        </a>
    </div>

    <div class="nav-section">Operations</div>
    <div class="side-nav">
        <a href="manage_bookings.php" class="side-link <?php echo ($current_page == 'manage_bookings.php') ? 'active' : ''; ?>">
            <i class="ti ti-receipt"></i> <span>Bookings</span>
        </a>
        <a href="manage_users.php" class="side-link <?php echo ($current_page == 'manage_users.php') ? 'active' : ''; ?>">
            <i class="ti ti-users"></i> <span>User Control</span>
        </a>
    </div>

    <div class="logout-section">
        <a href="../auth/logout.php" class="logout-btn"><i class="ti ti-logout"></i> Logout</a>
    </div>
</aside>