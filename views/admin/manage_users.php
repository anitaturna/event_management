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

// 4. Role change logic
if(isset($_GET['role']) && isset($_GET['id'])) {
    $uid = (int)$_GET['id'];
    $role = mysqli_real_escape_string($conn, $_GET['role']);
    $allowed = ['user','admin','vendor'];
    if(in_array($role, $allowed)) {
        mysqli_query($conn, "UPDATE users SET role='$role' WHERE id=$uid");
    }
    header('location:manage_users.php'); 
    exit();
}

// 5. Delete User logic
if(isset($_GET['delete'])) {
    $uid = (int)$_GET['delete'];
    // Prevent self-deletion
    if($uid != $_SESSION['user_id']) {
        mysqli_query($conn, "DELETE FROM users WHERE id=$uid");
    }
    header('location:manage_users.php'); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management | EventPro Admin</title>
    
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
            margin-left: 240px; /* Sidebar width matching the new compact size */
            flex: 1; 
            padding: 40px; 
            width: calc(100% - 240px);
        }

        .page-header { margin-bottom: 30px; }
        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 700;
            color: var(--dark);
        }

        /* Table Card Styling */
        .card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        /* Table Design */
        .table-responsive { overflow-x: auto; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th {
            background: #f8fafc;
            padding: 15px 20px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--slate-500);
            font-weight: 700;
            text-align: left;
            border-bottom: 2px solid #f1f5f9;
        }

        .data-table td {
            padding: 18px 20px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
            vertical-align: middle;
        }

        /* Role Badges */
        .role-badge {
            padding: 4px 10px;
            border-radius: 30px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            display: inline-block;
        }
        .role-admin { background: #fef3c7; color: #92400e; }
        .role-user { background: #dbeafe; color: #1e40af; }
        .role-vendor { background: #d1fae5; color: #065f46; }

        /* Action Buttons */
        .action-group { display: flex; gap: 5px; flex-wrap: wrap; }
        .action-btn {
            padding: 5px 10px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 10px;
            font-weight: 700;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            border: 1px solid transparent;
        }
        
        .btn-promote { background: #fff; border-color: #e2e8f0; color: var(--slate-500); }
        .btn-promote:hover { border-color: var(--primary); color: var(--primary); }
        
        .btn-delete { background: #fee2e2; color: #ef4444; }
        .btn-delete:hover { background: #ef4444; color: #fff; }

        @media (max-width: 992px) {
            .admin-content { margin-left: 0; width: 100%; padding: 20px; }
        }
    </style>
</head>
<body>

    <div class="admin-shell">
        <!-- Sidebar Inclusion (Corrected filename) -->
        <?php include('sidebar.php'); ?>

        <main class="admin-content">
            <header class="page-header">
                <h1 class="page-title">User Control Center</h1>
                <p style="color:var(--slate-500); font-size:14px;">Manage system access, adjust roles, and monitor registered members</p>
            </header>

            <div class="card">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Profile Info</th>
                                <th>Contact</th>
                                <th>Current Role</th>
                                <th>Joined Date</th>
                                <th style="text-align: right;">System Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $res = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
                            if($res && mysqli_num_rows($res) > 0):
                                while($u = mysqli_fetch_assoc($res)):
                            ?>
                            <tr>
                                <td style="font-weight:700; color:#94a3b8">#<?php echo $u['id']; ?></td>
                                <td>
                                    <div style="font-weight:700; color: var(--dark);"><?php echo htmlspecialchars($u['name']); ?></div>
                                    <div style="font-size:11px; color:var(--slate-500)"><?php echo htmlspecialchars($u['email']); ?></div>
                                </td>
                                <td>
                                    <div style="font-size:12px;"><i class="ti ti-phone" style="font-size:13px; color:var(--slate-500)"></i> <?php echo htmlspecialchars($u['phone'] ?? 'N/A'); ?></div>
                                </td>
                                <td>
                                    <span class="role-badge role-<?php echo $u['role']; ?>">
                                        ● <?php echo $u['role']; ?>
                                    </span>
                                </td>
                                <td style="color:var(--slate-500); font-size:12px;">
                                    <?php echo date('d M, Y', strtotime($u['created_at'])); ?>
                                </td>
                                <td style="text-align: right;">
                                    <div class="action-group" style="justify-content: flex-end;">
                                        <?php if($u['id'] != $_SESSION['user_id']): ?>
                                            
                                            <?php if($u['role'] != 'admin'): ?>
                                                <a href="?role=admin&id=<?php echo $u['id']; ?>" class="action-btn btn-promote" onclick="return confirm('Promote this user to Administrator?')">
                                                    <i class="ti ti-shield-chevron"></i> Admin
                                                </a>
                                            <?php endif; ?>

                                            <?php if($u['role'] != 'vendor'): ?>
                                                <a href="?role=vendor&id=<?php echo $u['id']; ?>" class="action-btn btn-promote">
                                                    <i class="ti ti-building-store"></i> Vendor
                                                </a>
                                            <?php endif; ?>

                                            <?php if($u['role'] != 'user'): ?>
                                                <a href="?role=user&id=<?php echo $u['id']; ?>" class="action-btn btn-promote">
                                                    <i class="ti ti-user"></i> User
                                                </a>
                                            <?php endif; ?>

                                            <a href="?delete=<?php echo $u['id']; ?>" class="action-btn btn-delete" onclick="return confirm('Permanently delete this account?')">
                                                <i class="ti ti-trash"></i>
                                            </a>

                                        <?php else: ?>
                                            <span style="color:var(--primary); font-weight:700; font-size:11px; padding-right:10px;">
                                                <i class="ti ti-user-check"></i> Active Session
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr>
                                <td colspan="6" style="text-align:center; padding:50px; color:var(--slate-500)">
                                    <i class="ti ti-users-minus" style="font-size: 32px; display: block; margin-bottom: 10px;"></i>
                                    No users found.
                                </td>
                            </tr>
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