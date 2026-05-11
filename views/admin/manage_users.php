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

// --- New Profile Creation Logic ---
if(isset($_POST['create_profile'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check_email = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
    if(mysqli_num_rows($check_email) > 0) {
        $error_msg = "Email already exists!";
    } else {
        $sql = "INSERT INTO users (name, email, password, phone, address, role, created_at) 
                VALUES ('$name', '$email', '$password', '$phone', '$address', '$role', NOW())";
        if(mysqli_query($conn, $sql)){
            header('location:manage_users.php?success=1');
            exit();
        }
    }
}

// 4. Role change logic
if(isset($_GET['role']) && isset($_GET['id'])) {
    $uid = (int)$_GET['id'];
    $role = mysqli_real_escape_string($conn, $_GET['role']);
    $allowed = ['user','admin','vendor'];
    
    if(in_array($role, $allowed)) {
        if($uid != $_SESSION['user_id']) {
            mysqli_query($conn, "UPDATE users SET role='$role' WHERE id=$uid");
        }
    }
    header('location:manage_users.php'); 
    exit();
}

// 5. Delete User logic
if(isset($_GET['delete'])) {
    $uid = (int)$_GET['delete'];
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
    <title>Manage Users | AT Royal Events</title>
    
    <!-- Icons & Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    
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

        .admin-shell { display: flex; min-height: 100vh; }
        .admin-content { margin-left: 240px; flex: 1; padding: 40px; width: calc(100% - 240px); }

        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .page-title { font-family: 'Playfair Display', serif; font-size: 32px; font-weight: 700; color: var(--dark); }

        /* Button Style */
        .btn-create {
            background: var(--dark); color: white; padding: 12px 24px; border-radius: 10px;
            text-decoration: none; font-weight: 600; display: inline-flex; align-items: center;
            gap: 8px; border: none; cursor: pointer; transition: 0.3s; font-size: 14px;
        }
        .btn-create:hover { background: var(--primary); transform: translateY(-2px); }

        .card { background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); overflow: hidden; }
        .table-responsive { overflow-x: auto; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th { background: #f8fafc; padding: 15px 20px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: var(--slate-500); font-weight: 700; text-align: left; border-bottom: 2px solid #f1f5f9; }
        .data-table td { padding: 18px 20px; border-bottom: 1px solid #f1f5f9; font-size: 13px; vertical-align: middle; }

        .role-badge { padding: 4px 10px; border-radius: 30px; font-size: 10px; font-weight: 700; text-transform: uppercase; display: inline-block; }
        .role-admin { background: #fef3c7; color: #92400e; }
        .role-user { background: #dbeafe; color: #1e40af; }
        .role-vendor { background: #d1fae5; color: #065f46; }

        .action-group { display: flex; gap: 5px; flex-wrap: wrap; }
        .action-btn { padding: 5px 10px; border-radius: 6px; text-decoration: none; font-size: 10px; font-weight: 700; transition: 0.2s; display: inline-flex; align-items: center; gap: 4px; border: 1px solid transparent; }
        .btn-promote { background: #fff; border-color: #e2e8f0; color: var(--slate-500); }
        .btn-promote:hover { border-color: var(--primary); color: var(--primary); }
        .btn-delete { background: #fee2e2; color: #ef4444; }
        .btn-delete:hover { background: #ef4444; color: #fff; }

        /* --- Modal Styles --- */
        .modal { display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); }
        .modal-content { background: white; width: 95%; max-width: 500px; margin: 60px auto; border-radius: 20px; padding: 35px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); position: relative; animation: slideDown 0.3s ease-out; }
        @keyframes slideDown { from { transform: translateY(-30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .close-modal { position: absolute; top: 20px; right: 20px; font-size: 24px; cursor: pointer; color: var(--slate-500); border: none; background: none; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 8px; color: var(--dark); letter-spacing: 0.5px; }
        .form-control { width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 14px; font-family: 'Inter', sans-serif; }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1); }

        @media (max-width: 992px) { .admin-content { margin-left: 0; width: 100%; padding: 20px; } }
    </style>
</head>
<body>

    <div class="admin-shell">
        <?php include('sidebar.php'); ?>

        <main class="admin-content">
            <header class="page-header">
                <div>
                    <h1 class="page-title">AT Royal User Control</h1>
                    <p style="color:var(--slate-500); font-size:14px;">Modify system permissions and monitor registered members</p>
                </div>
                <button class="btn-create" onclick="openModal()">
                    <i class="ti ti-user-plus"></i> Create Profile
                </button>
            </header>

            <div class="card">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Member Profile</th>
                                <th>Location & Contact</th>
                                <th>Role</th>
                                <th>Joined</th>
                                <th style="text-align: right;">Administrative Actions</th>
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
                                    <div style="font-size:12px; margin-bottom: 2px;">
                                        <i class="ti ti-phone" style="font-size:13px; color:var(--primary)"></i> 
                                        <?php echo htmlspecialchars($u['phone'] ?? 'No Phone'); ?>
                                    </div>
                                    <div style="font-size:11px; color:var(--slate-500);">
                                        <i class="ti ti-map-pin" style="font-size:12px;"></i>
                                        <?php echo htmlspecialchars($u['address'] ?? 'Not Set'); ?>
                                    </div>
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
                                            
                                            <a href="?role=admin&id=<?php echo $u['id']; ?>" class="action-btn btn-promote" <?php echo ($u['role'] == 'admin') ? 'style="display:none"' : ''; ?> onclick="return confirm('Change user role to Admin?')">
                                                <i class="ti ti-shield-check"></i> Admin
                                            </a>

                                            <a href="?role=vendor&id=<?php echo $u['id']; ?>" class="action-btn btn-promote" <?php echo ($u['role'] == 'vendor') ? 'style="display:none"' : ''; ?> onclick="return confirm('Change user role to Vendor?')">
                                                <i class="ti ti-building-store"></i> Vendor
                                            </a>

                                            <a href="?role=user&id=<?php echo $u['id']; ?>" class="action-btn btn-promote" <?php echo ($u['role'] == 'user') ? 'style="display:none"' : ''; ?> onclick="return confirm('Change user role to Standard User?')">
                                                <i class="ti ti-user"></i> User
                                            </a>

                                            <a href="?delete=<?php echo $u['id']; ?>" class="action-btn btn-delete" onclick="return confirm('Permanently remove this user?')">
                                                <i class="ti ti-trash"></i>
                                            </a>

                                        <?php else: ?>
                                            <span style="color:var(--primary); font-weight:700; font-size:11px; padding-right:10px;">
                                                <i class="ti ti-user-check"></i> Current Admin
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr>
                                <td colspan="6" style="text-align:center; padding:50px; color:var(--slate-500)">No members found.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Create Profile Modal -->
    <div id="createModal" class="modal">
        <div class="modal-content">
            <button class="close-modal" onclick="closeModal()">&times;</button>
            <h2 style="font-family:'Playfair Display'; margin-bottom:5px;">New Profile</h2>
            <p style="font-size:13px; color:var(--slate-500); margin-bottom:25px;">Create a new staff or user account</p>
            
            <form action="" method="POST">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" required class="form-control" placeholder="John Doe">
                </div>
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required class="form-control" placeholder="john@example.com">
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" placeholder="017xxxxxxxx">
                    </div>
                </div>
                <div class="form-group">
                    <label>Assign Role</label>
                    <select name="role" class="form-control">
                        <option value="user">User / Client</option>
                        <option value="vendor">Vendor / Partner</option>
                        <option value="admin">Admin / Manager</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required class="form-control" placeholder="••••••••">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" class="form-control" placeholder="Dhaka, Bangladesh">
                </div>
                <button type="submit" name="create_profile" class="btn-create" style="width:100%; justify-content:center; margin-top:10px;">
                    Create Account
                </button>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('createModal');
        function openModal() { modal.style.display = "block"; }
        function closeModal() { modal.style.display = "none"; }
        window.onclick = function(event) { if (event.target == modal) closeModal(); }
    </script>

    <?php ob_end_flush(); ?>
</body>
</html>