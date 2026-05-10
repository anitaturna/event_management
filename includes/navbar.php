<!-- Premium Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

<style>
    :root {
        --nav-bg: #0f172a;
        --nav-accent: #2563eb;
        --nav-text: #94a3b8;
        --nav-hover: #ffffff;
    }

    .main-nav {
        background: rgba(15, 23, 42, 0.98);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        padding: 0;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
        font-family: 'Inter', sans-serif;
        position: sticky;
        top: 0;
        z-index: 1000;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .main-nav .nav-inner {
        width: 95%;
        max-width: 1300px;
        margin: auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 75px;
    }

    /* Brand Logo Style */
    .nav-brand {
        color: #fff;
        text-decoration: none;
        font-size: 22px;
        font-weight: 700;
        letter-spacing: 0.5px;
        font-family: 'Playfair Display', serif;
        transition: 0.3s ease;
        text-transform: uppercase;
    }

    .nav-brand span {
        color: var(--nav-accent);
    }

    .nav-links {
        display: flex;
        gap: 5px;
        align-items: center;
    }

    /* Professional Navigation Items */
    .nav-item {
        color: var(--nav-text);
        text-decoration: none;
        font-size: 13px;
        padding: 10px 18px;
        border-radius: 8px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .nav-item:hover {
        color: var(--nav-hover);
        background: rgba(255, 255, 255, 0.05);
    }

    /* Special Highlight for Panels */
    .admin-link {
        color: #fbbf24 !important;
        background: rgba(251, 191, 36, 0.08);
        border: 1px solid rgba(251, 191, 36, 0.15);
    }

    .vendor-link {
        color: #34d399 !important;
        background: rgba(52, 211, 153, 0.08);
        border: 1px solid rgba(52, 211, 153, 0.15);
    }

    /* Action Buttons */
    .btn-login {
        border: 1px solid #334155;
        color: #e2e8f0 !important;
        margin-left: 10px;
    }

    .btn-reg {
        background: var(--nav-accent);
        color: #fff !important;
        border: none;
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.2);
    }

    .btn-reg:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
    }

    .btn-logout {
        color: #f87171 !important;
        font-weight: 600;
    }

    /* Profile Section Adjustment */
    .user-profile-section {
        display: flex;
        align-items: center;
        padding-left: 15px;
        border-left: 1px solid #334155;
        margin-left: 10px;
    }

    .user-name {
        color: #f1f5f9;
        font-size: 13px;
        font-weight: 600;
        margin-right: 15px;
        max-width: 150px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    @media (max-width: 1024px) {
        .nav-inner { height: 65px; }
        .user-name { display: none; }
        .user-profile-section { border: none; padding: 0; }
        .nav-item { padding: 8px 12px; font-size: 11px; }
    }
</style>

<nav class="main-nav">
    <div class="nav-inner">
        <!-- Application Name -->
        <a href="<?php echo SITEURL; ?>" class="nav-brand">
            AT ROYAL <span>EVENTS</span>
        </a>
        
        <div class="nav-links">
            <!-- Everyone can see Home and Events -->
            <a href="<?php echo SITEURL; ?>" class="nav-item">Home</a>
            <a href="<?php echo SITEURL; ?>views/user/events_list.php" class="nav-item">Events</a>
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <!-- Registered User Content -->
                <a href="<?php echo SITEURL; ?>views/user/my_bookings.php" class="nav-item">My Bookings</a>
                
                <?php if($_SESSION['role'] == 'admin'): ?>
                    <a href="<?php echo SITEURL; ?>views/admin/dashboard.php" class="nav-item admin-link">Admin Panel</a>
                <?php elseif($_SESSION['role'] == 'vendor'): ?>
                    <a href="<?php echo SITEURL; ?>views/admin/manage_vendors.php" class="nav-item vendor-link">Vendor Panel</a>
                <?php endif; ?>

                <div class="user-profile-section">
                    <span class="user-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    <a href="<?php echo SITEURL; ?>views/auth/logout.php" class="nav-item btn-logout">Logout</a>
                </div>
                
            <?php else: ?>
                <!-- Guests view Login/Register -->
                <a href="<?php echo SITEURL; ?>views/auth/login.php" class="nav-item btn-login">Login</a>
                <a href="<?php echo SITEURL; ?>views/auth/register.php" class="nav-item btn-reg">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>