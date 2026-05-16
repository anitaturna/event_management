<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

<style>
:root {
    --nav-bg:       rgba(10, 10, 15, 0.95);
    --nav-border:   rgba(201, 168, 76, 0.12);
    --gold:         #c9a84c;
    --gold-light:   #f0d48a;
    --nav-text:     rgba(255,255,255,0.7);
    --nav-hover:    rgba(255,255,255,1);
    --nav-muted:    rgba(255,255,255,0.4);
}

.main-navbar *, .main-navbar *::before, .main-navbar *::after {
    box-sizing: border-box;
    margin: 0; padding: 0;
}

.main-navbar {
    position: sticky;
    top: 0;
    z-index: 9999;
    width: 100%;
    background: var(--nav-bg);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--nav-border);
    font-family: 'Outfit', sans-serif;
    transition: background 0.3s ease;
}

.main-navbar::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--gold), transparent);
    opacity: 0.5;
}

.nav-container {
    max-width: 1350px;
    margin: 0 auto;
    padding: 0 20px;
    height: 58px;              /* reduced from 72px */
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

/* ===== LOGO ===== */
.nav-logo {
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
    transition: opacity 0.2s;
}
.nav-logo:hover { opacity: 0.85; }

.nav-logo-icon {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    background: linear-gradient(135deg, var(--gold), #8a6d2f);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    color: #0a0a0f;
    font-weight: 700;
    font-family: 'Cormorant Garamond', serif;
    flex-shrink: 0;
}
.nav-logo-text {
    font-family: 'Cormorant Garamond', serif;
    font-size: 20px;
    font-weight: 700;
    color: #fff;
    letter-spacing: 0.5px;
    line-height: 1;
}
.nav-logo-text span { color: var(--gold); }

/* ===== NAV LINKS ===== */
.nav-links {
    display: flex;
    align-items: center;
    gap: 2px;
    flex: 1;
    justify-content: center;
}

.nav-link {
    position: relative;
    text-decoration: none;
    color: var(--nav-text);
    font-size: 13px;
    font-weight: 500;
    letter-spacing: 0.3px;
    padding: 6px 12px;         /* reduced from 8px 16px */
    border-radius: 6px;
    transition: color 0.2s, background 0.2s;
    display: flex;
    align-items: center;
    gap: 5px;
    white-space: nowrap;
}
.nav-link i { font-size: 14px; }
.nav-link:hover {
    color: var(--nav-hover);
    background: rgba(255,255,255,0.05);
}

.nav-link.active { color: var(--gold-light); }
.nav-link.active::after {
    content: '';
    position: absolute;
    bottom: 2px; left: 50%;
    transform: translateX(-50%);
    width: 14px; height: 1.5px;
    background: var(--gold);
    border-radius: 10px;
}

/* ===== RIGHT SECTION ===== */
.nav-right {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

/* ===== ADMIN/VENDOR PILLS ===== */
.badge-admin {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: rgba(251,191,36,0.1);
    border: 1px solid rgba(251,191,36,0.25);
    color: #fbbf24;
    font-size: 12px;
    font-weight: 600;
    padding: 5px 11px;         /* reduced from 6px 14px */
    border-radius: 100px;
    text-decoration: none;
    transition: all 0.2s;
    white-space: nowrap;
}
.badge-admin:hover {
    background: rgba(251,191,36,0.18);
    color: #fde68a;
}
.badge-vendor {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: rgba(52,211,153,0.1);
    border: 1px solid rgba(52,211,153,0.25);
    color: #34d399;
    font-size: 12px;
    font-weight: 600;
    padding: 5px 11px;
    border-radius: 100px;
    text-decoration: none;
    transition: all 0.2s;
    white-space: nowrap;
}
.badge-vendor:hover {
    background: rgba(52,211,153,0.18);
    color: #6ee7b7;
}

/* ===== USER CLUSTER ===== */
.user-cluster {
    display: flex;
    align-items: center;
    gap: 8px;
    padding-left: 12px;
    border-left: 1px solid rgba(255,255,255,0.08);
}
.user-avatar {
    width: 30px; height: 30px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--gold), #8a6d2f);
    color: #0a0a0f;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 12px;
    text-transform: uppercase;
    flex-shrink: 0;
    border: 1.5px solid rgba(201,168,76,0.3);
}
.user-meta { display: flex; flex-direction: column; }
.user-name {
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    line-height: 1.2;
    max-width: 110px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.user-role {
    color: var(--nav-muted);
    font-size: 10px;
    font-weight: 500;
    text-transform: capitalize;
    letter-spacing: 0.5px;
}
.btn-logout {
    display: flex;
    align-items: center;
    gap: 4px;
    background: rgba(248,113,113,0.08);
    border: 1px solid rgba(248,113,113,0.2);
    color: #f87171;
    font-size: 12px;
    font-weight: 600;
    padding: 5px 11px;
    border-radius: 100px;
    text-decoration: none;
    transition: all 0.2s;
    white-space: nowrap;
}
.btn-logout:hover {
    background: rgba(248,113,113,0.15);
    color: #fca5a5;
}

/* ===== AUTH BUTTONS ===== */
.btn-nav-login {
    color: var(--nav-text);
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    padding: 6px 14px;
    border-radius: 6px;
    border: 1px solid rgba(255,255,255,0.1);
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 5px;
    white-space: nowrap;
}
.btn-nav-login:hover {
    color: white;
    border-color: rgba(255,255,255,0.25);
    background: rgba(255,255,255,0.05);
}

.btn-nav-register {
    display: flex;
    align-items: center;
    gap: 6px;
    background: var(--gold);
    color: #0a0a0f;
    font-size: 13px;
    font-weight: 700;
    padding: 7px 16px;         /* reduced from 9px 20px */
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.25s;
    letter-spacing: 0.2px;
    position: relative;
    overflow: hidden;
    white-space: nowrap;
}
.btn-nav-register::before {
    content: '';
    position: absolute;
    top: 0; left: -100%;
    width: 100%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
    transition: left 0.5s ease;
}
.btn-nav-register:hover::before { left: 100%; }
.btn-nav-register:hover {
    background: var(--gold-light);
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(201,168,76,0.35);
}

/* ===== MOBILE MENU TOGGLE ===== */
.nav-mobile-toggle {
    display: none;
    background: none;
    border: 1px solid rgba(255,255,255,0.12);
    color: var(--nav-text);
    border-radius: 6px;
    padding: 5px 8px;
    cursor: pointer;
    font-size: 18px;
    line-height: 1;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}
.nav-mobile-toggle:hover {
    color: white;
    border-color: rgba(255,255,255,0.25);
    background: rgba(255,255,255,0.05);
}

/* ===== MOBILE DRAWER ===== */
.nav-mobile-drawer {
    display: none;
    flex-direction: column;
    background: var(--nav-bg);
    border-top: 1px solid var(--nav-border);
    padding: 10px 20px 14px;
    gap: 2px;
}
.nav-mobile-drawer.open { display: flex; }

.nav-mobile-link {
    text-decoration: none;
    color: var(--nav-text);
    font-size: 14px;
    font-weight: 500;
    padding: 9px 12px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: color 0.2s, background 0.2s;
}
.nav-mobile-link i { font-size: 16px; }
.nav-mobile-link:hover {
    color: white;
    background: rgba(255,255,255,0.05);
}

.nav-mobile-divider {
    height: 1px;
    background: rgba(255,255,255,0.07);
    margin: 6px 0;
}

.nav-mobile-user {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 12px;
}
.nav-mobile-badges {
    display: flex;
    flex-direction: column;
    gap: 6px;
    padding: 0 4px;
}

/* ===== RESPONSIVE BREAKPOINTS ===== */

/* Hide center links and show toggle below 960px */
@media (max-width: 960px) {
    .nav-links { display: none; }
    .nav-mobile-toggle { display: flex; }

    /* Hide panel badges from desktop right section on mobile */
    .badge-admin, .badge-vendor { display: none; }

    /* Hide login text button — show only register */
    .btn-nav-login { display: none; }
}

/* Stack / trim further on small phones */
@media (max-width: 480px) {
    .nav-container { padding: 0 14px; gap: 8px; }
    .user-meta { display: none; }
    .nav-logo-text { font-size: 17px; }

    /* On very small screens show only icon text in register button */
    .btn-nav-register .btn-register-label { display: none; }
}
</style>

<nav class="main-navbar">
    <div class="nav-container">

        <!-- LOGO -->
        <a href="<?php echo SITEURL; ?>" class="nav-logo">
            <div class="nav-logo-icon">A</div>
            <div class="nav-logo-text">AT Royal <span>Events</span></div>
        </a>

        <!-- CENTER LINKS (desktop) -->
        <div class="nav-links">
            <a href="<?php echo SITEURL; ?>" class="nav-link">
                <i class="ti ti-home"></i> Home
            </a>
            <a href="<?php echo SITEURL; ?>views/user/events_list.php" class="nav-link">
                <i class="ti ti-calendar-event"></i> Events
            </a>
            <?php if(isset($_SESSION['user_id'])): ?>
            <a href="<?php echo SITEURL; ?>views/user/my_bookings.php" class="nav-link">
                <i class="ti ti-receipt"></i> My Bookings
            </a>
            <?php endif; ?>
        </div>

        <!-- RIGHT SECTION -->
        <div class="nav-right">

            <?php if(isset($_SESSION['user_id'])): ?>

                <!-- Panel badges (desktop only — hidden on mobile via CSS) -->
                <?php if($_SESSION['role'] == 'admin'): ?>
                <a href="<?php echo SITEURL; ?>views/admin/dashboard.php" class="badge-admin">
                    <i class="ti ti-layout-dashboard"></i> Admin Panel
                </a>
                <?php elseif($_SESSION['role'] == 'vendor'): ?>
                <a href="<?php echo SITEURL; ?>views/admin/manage_vendors.php" class="badge-vendor">
                    <i class="ti ti-briefcase"></i> Vendor Panel
                </a>
                <?php endif; ?>

                <!-- User Cluster -->
                <div class="user-cluster">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                    </div>
                    <div class="user-meta">
                        <div class="user-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
                        <div class="user-role"><?php echo htmlspecialchars($_SESSION['role']); ?></div>
                    </div>
                    <a href="<?php echo SITEURL; ?>views/auth/logout.php" class="btn-logout">
                        <i class="ti ti-logout"></i> Logout
                    </a>
                </div>

            <?php else: ?>

                <a href="<?php echo SITEURL; ?>views/auth/login.php" class="btn-nav-login">
                    <i class="ti ti-user"></i> Login
                </a>
                <a href="<?php echo SITEURL; ?>views/auth/register.php" class="btn-nav-register">
                    <i class="ti ti-sparkles"></i>
                    <span class="btn-register-label">Get Started</span>
                </a>

            <?php endif; ?>

            <!-- Mobile menu toggle (visible <960px) -->
            <button
                class="nav-mobile-toggle"
                aria-label="Toggle menu"
                onclick="this.closest('.main-navbar').querySelector('.nav-mobile-drawer').classList.toggle('open')"
            >
                <i class="ti ti-menu-2"></i>
            </button>

        </div>
    </div>

    <!-- MOBILE DRAWER -->
    <div class="nav-mobile-drawer">

        <a href="<?php echo SITEURL; ?>" class="nav-mobile-link">
            <i class="ti ti-home"></i> Home
        </a>
        <a href="<?php echo SITEURL; ?>views/user/events_list.php" class="nav-mobile-link">
            <i class="ti ti-calendar-event"></i> Events
        </a>

        <?php if(isset($_SESSION['user_id'])): ?>

            <a href="<?php echo SITEURL; ?>views/user/my_bookings.php" class="nav-mobile-link">
                <i class="ti ti-receipt"></i> My Bookings
            </a>

            <div class="nav-mobile-divider"></div>

            <!-- User info row -->
            <div class="nav-mobile-user">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                </div>
                <div>
                    <div class="user-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
                    <div class="user-role"><?php echo htmlspecialchars($_SESSION['role']); ?></div>
                </div>
            </div>

            <!-- Panel badge in drawer -->
            <div class="nav-mobile-badges">
                <?php if($_SESSION['role'] == 'admin'): ?>
                <a href="<?php echo SITEURL; ?>views/admin/dashboard.php" class="badge-admin" style="display:inline-flex; align-self:flex-start;">
                    <i class="ti ti-layout-dashboard"></i> Admin Panel
                </a>
                <?php elseif($_SESSION['role'] == 'vendor'): ?>
                <a href="<?php echo SITEURL; ?>views/admin/manage_vendors.php" class="badge-vendor" style="display:inline-flex; align-self:flex-start;">
                    <i class="ti ti-briefcase"></i> Vendor Panel
                </a>
                <?php endif; ?>

                <a href="<?php echo SITEURL; ?>views/auth/logout.php" class="btn-logout" style="align-self:flex-start;">
                    <i class="ti ti-logout"></i> Logout
                </a>
            </div>

        <?php else: ?>

            <div class="nav-mobile-divider"></div>
            <a href="<?php echo SITEURL; ?>views/auth/login.php" class="nav-mobile-link">
                <i class="ti ti-user"></i> Login
            </a>
            <a href="<?php echo SITEURL; ?>views/auth/register.php" class="nav-mobile-link" style="color: var(--gold);">
                <i class="ti ti-sparkles"></i> Get Started
            </a>

        <?php endif; ?>

    </div>
</nav>