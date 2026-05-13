<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

<style>

:root{
    --nav-bg:#0f172a;
    --nav-primary:#2563eb;
    --nav-hover:#3b82f6;
    --nav-text:#cbd5e1;
    --nav-muted:#94a3b8;
    --nav-border:rgba(255,255,255,0.08);
}

/* =========================
   NAVBAR
========================= */

.main-navbar{
    position:sticky;
    top:0;
    z-index:9999;
    width:100%;
    backdrop-filter:blur(16px);
    -webkit-backdrop-filter:blur(16px);
    background:rgba(15,23,42,0.92);
    border-bottom:1px solid var(--nav-border);
    font-family:'Inter',sans-serif;
}

.nav-container{
    width:94%;
    max-width:1350px;
    height:78px;
    margin:auto;
    display:flex;
    align-items:center;
    justify-content:space-between;
}

/* =========================
   LOGO
========================= */

.nav-logo{
    text-decoration:none;
    font-family:'Playfair Display',serif;
    font-size:28px;
    font-weight:700;
    color:#fff;
    letter-spacing:1px;
    transition:0.3s ease;
}

.nav-logo span{
    color:var(--nav-primary);
}

.nav-logo:hover{
    transform:translateY(-1px);
}

/* =========================
   NAV LINKS
========================= */

.nav-links{
    display:flex;
    align-items:center;
    gap:10px;
}

.nav-link{
    position:relative;
    text-decoration:none;
    color:var(--nav-text);
    font-size:13px;
    font-weight:600;
    text-transform:uppercase;
    letter-spacing:0.7px;
    padding:12px 18px;
    border-radius:12px;
    transition:all 0.3s ease;
}

.nav-link:hover{
    color:#fff;
    background:rgba(255,255,255,0.06);
}

/* Active Hover Line */

.nav-link::after{
    content:'';
    position:absolute;
    left:50%;
    bottom:6px;
    width:0%;
    height:2px;
    background:var(--nav-primary);
    transition:0.3s ease;
    transform:translateX(-50%);
    border-radius:10px;
}

.nav-link:hover::after{
    width:60%;
}

/* =========================
   SPECIAL BUTTONS
========================= */

.btn-login{
    border:1px solid rgba(255,255,255,0.1);
}

.btn-register{
    background:linear-gradient(
        135deg,
        #2563eb,
        #1d4ed8
    );
    color:#fff !important;
    box-shadow:0 8px 20px rgba(37,99,235,0.25);
}

.btn-register:hover{
    transform:translateY(-2px);
    background:linear-gradient(
        135deg,
        #3b82f6,
        #2563eb
    );
}

/* =========================
   ADMIN/VENDOR
========================= */

.admin-panel{
    color:#fbbf24 !important;
    background:rgba(251,191,36,0.08);
}

.vendor-panel{
    color:#34d399 !important;
    background:rgba(52,211,153,0.08);
}

/* =========================
   USER PROFILE
========================= */

.user-box{
    display:flex;
    align-items:center;
    gap:14px;
    margin-left:10px;
    padding-left:18px;
    border-left:1px solid rgba(255,255,255,0.08);
}

.user-avatar{
    width:40px;
    height:40px;
    border-radius:50%;
    background:linear-gradient(
        135deg,
        #2563eb,
        #1d4ed8
    );
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:700;
    font-size:14px;
    text-transform:uppercase;
}

.user-info{
    display:flex;
    flex-direction:column;
}

.user-name{
    color:#fff;
    font-size:13px;
    font-weight:600;
    line-height:1.2;
}

.user-role{
    color:var(--nav-muted);
    font-size:11px;
    text-transform:capitalize;
}

.logout-btn{
    color:#f87171 !important;
}

/* =========================
   RESPONSIVE
========================= */

@media(max-width:992px){

    .nav-container{
        height:auto;
        padding:18px 0;
        flex-direction:column;
        gap:20px;
    }

    .nav-links{
        flex-wrap:wrap;
        justify-content:center;
    }

    .user-box{
        border:none;
        padding:0;
        margin:0;
    }

}

@media(max-width:576px){

    .nav-link{
        padding:10px 14px;
        font-size:11px;
    }

    .nav-logo{
        font-size:22px;
    }

}

</style>

<nav class="main-navbar">

    <div class="nav-container">

        <!-- LOGO -->
        <a href="<?php echo SITEURL; ?>" class="nav-logo">
            AT ROYAL <span>EVENTS</span>
        </a>

        <!-- NAVIGATION -->
        <div class="nav-links">

            <a href="<?php echo SITEURL; ?>" class="nav-link">
                Home
            </a>

            <a href="<?php echo SITEURL; ?>views/user/events_list.php" class="nav-link">
                Events
            </a>

            <?php if(isset($_SESSION['user_id'])): ?>

                <a href="<?php echo SITEURL; ?>views/user/my_bookings.php" class="nav-link">
                    My Bookings
                </a>

                <?php if($_SESSION['role'] == 'admin'): ?>

                    <a href="<?php echo SITEURL; ?>views/admin/dashboard.php"
                       class="nav-link admin-panel">
                        Admin Panel
                    </a>

                <?php elseif($_SESSION['role'] == 'vendor'): ?>

                    <a href="<?php echo SITEURL; ?>views/admin/manage_vendors.php"
                       class="nav-link vendor-panel">
                        Vendor Panel
                    </a>

                <?php endif; ?>

                <!-- USER PROFILE -->
                <div class="user-box">

                    <div class="user-avatar">
                        <?php
                            echo strtoupper(substr($_SESSION['user_name'],0,1));
                        ?>
                    </div>

                    <div class="user-info">

                        <div class="user-name">
                            <?php
                                echo htmlspecialchars($_SESSION['user_name']);
                            ?>
                        </div>

                        <div class="user-role">
                            <?php
                                echo htmlspecialchars($_SESSION['role']);
                            ?>
                        </div>

                    </div>

                    <a href="<?php echo SITEURL; ?>views/auth/logout.php"
                       class="nav-link logout-btn">
                        Logout
                    </a>

                </div>

            <?php else: ?>

                <a href="<?php echo SITEURL; ?>views/auth/login.php"
                   class="nav-link btn-login">
                    Login
                </a>

                <a href="<?php echo SITEURL; ?>views/auth/register.php"
                   class="nav-link btn-register">
                    Register
                </a>

            <?php endif; ?>

        </div>

    </div>

</nav>