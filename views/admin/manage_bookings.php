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

// 4. Status Update Logic
if(isset($_GET['action']) && isset($_GET['id'])) {
    $bid = (int)$_GET['id'];
    $action = mysqli_real_escape_string($conn, $_GET['action']);
    $allowed = ['approved','completed','cancelled'];
    if(in_array($action, $allowed)) {
        mysqli_query($conn, "UPDATE bookings SET status='$action' WHERE id=$bid");
    }
    header('location:manage_bookings.php'); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings | EventPro Admin</title>
    
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

        /* Sidebar Layout Integration */
        .admin-shell { display: flex; min-height: 100vh; }
        
        .admin-content { 
            margin-left: 260px; /* Width of vertical sidebar */
            flex: 1; 
            padding: 40px; 
            width: calc(100% - 260px);
        }

        .page-header {
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

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

        .filter-row {
            display: flex;
            gap: 10px;
            padding: 20px;
            background: #fff;
            border-bottom: 1px solid #f1f5f9;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 8px 18px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid #e2e8f0;
            color: var(--slate-500);
            background: #fff;
            transition: 0.2s;
        }

        .filter-btn.active, .filter-btn:hover {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }

        /* Table Design */
        .table-responsive { overflow-x: auto; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th {
            background: #f8fafc;
            padding: 18px 20px;
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

        /* Status Badges */
        .status-badge {
            padding: 5px 12px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            display: inline-block;
        }
        .status-pending { background: #fef9c3; color: #854d0e; }
        .status-approved { background: #dcfce7; color: #166534; }
        .status-completed { background: #dbeafe; color: #1e40af; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }

        /* Payment Tag */
        .pay-info {
            font-size: 11px;
            color: var(--primary);
            background: #eff6ff;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 600;
            display: inline-block;
            margin-top: 4px;
        }

        /* Action Buttons */
        .action-btn {
            padding: 8px 14px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .btn-approve { background: #dcfce7; color: #166534; }
        .btn-approve:hover { background: #166534; color: #fff; }
        
        .btn-complete { background: #dbeafe; color: #1e40af; }
        .btn-complete:hover { background: #1e40af; color: #fff; }
        
        .btn-cancel { background: #fee2e2; color: #991b1b; }
        .btn-cancel:hover { background: #991b1b; color: #fff; }

        @media (max-width: 1100px) {
            .admin-content { margin-left: 0; width: 100%; padding: 20px; }
            .page-header { flex-direction: column; align-items: flex-start; gap: 10px; }
        }
    </style>
</head>
<body>

    <div class="admin-shell">
        <!-- Sidebar Inclusion -->
        <?php include('sidebar.php'); ?>

        <main class="admin-content">
            <header class="page-header">
                <div>
                    <h1 class="page-title">Booking Management</h1>
                    <p style="color:var(--slate-500); font-size:14px;">Review payments and manage event reservations</p>
                </div>
                <div style="background: #fff; padding: 10px 20px; border-radius: 12px; border: 1px solid #e2e8f0; font-weight: 600;">
                    <i class="ti ti-calendar-event"></i> <?php echo date('d M, Y'); ?>
                </div>
            </header>

            <?php
            $filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';
            $where = $filter ? "WHERE b.status='$filter'" : '';
            $statuses = ['','pending','approved','completed','cancelled'];
            $labels   = ['All','Pending','Approved','Completed','Cancelled'];
            ?>

            <div class="card">
                <div class="filter-row">
                    <?php foreach($statuses as $i=>$s):
                        $isActive = ($filter==$s) ? 'active' : '';
                        $url = $s ? "manage_bookings.php?status=$s" : "manage_bookings.php";
                        echo "<a href='$url' class='filter-btn $isActive'>{$labels[$i]}</a>";
                    endforeach; ?>
                </div>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Client & Payment</th>
                                <th>Service Plan</th>
                                <th>Event Date</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th style="text-align: center;">Management</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Joining with payments to show TrxID and Method
                        $sql = "SELECT b.*, u.name as uname, u.email, p.name as pkg_name, e.name as event_name, 
                                pay.transaction_id, pay.method as pay_method, pay.amount as paid_advance
                                FROM bookings b
                                JOIN users u ON b.user_id=u.id
                                JOIN packages p ON b.package_id=p.id
                                JOIN events e ON p.event_id=e.id
                                LEFT JOIN payments pay ON b.id = pay.booking_id
                                $where
                                ORDER BY b.id DESC";
                        
                        $res = mysqli_query($conn, $sql);
                        if($res && mysqli_num_rows($res) > 0):
                            while($row = mysqli_fetch_assoc($res)):
                        ?>
                        <tr>
                            <td style="font-weight:700; color:#94a3b8">#<?php echo $row['id']; ?></td>
                            <td>
                                <div style="font-weight:700; color: var(--dark);"><?php echo htmlspecialchars($row['uname']); ?></div>
                                <div style="font-size:11px; color:var(--slate-500)"><?php echo htmlspecialchars($row['email']); ?></div>
                                <?php if($row['transaction_id']): ?>
                                    <div class="pay-info">
                                        <i class="ti ti-receipt"></i> <?php echo $row['pay_method']; ?>: <?php echo $row['transaction_id']; ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="font-weight:600; color: var(--primary);"><?php echo htmlspecialchars($row['event_name']); ?></div>
                                <div style="font-size:12px; color:var(--slate-500)"><?php echo htmlspecialchars($row['pkg_name']); ?></div>
                            </td>
                            <td style="font-weight: 500;"><?php echo date('d M, Y', strtotime($row['event_date'])); ?></td>
                            <td>
                                <div style="font-weight:800; color: var(--dark)">৳ <?php echo number_format($row['total_price']); ?></div>
                                <div style="font-size:10px; color: #10b981;">Adv: ৳<?php echo number_format($row['paid_advance']); ?></div>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo $row['status']; ?>">
                                    ● <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <div style="display: flex; gap: 5px; justify-content: center;">
                                    <?php if($row['status'] == 'pending'): ?>
                                        <a href="?action=approved&id=<?php echo $row['id']; ?>" class="action-btn btn-approve" title="Approve">
                                            <i class="ti ti-check"></i>
                                        </a>
                                        <a href="?action=cancelled&id=<?php echo $row['id']; ?>" class="action-btn btn-cancel" onclick="return confirm('Cancel this booking?')" title="Cancel">
                                            <i class="ti ti-x"></i>
                                        </a>
                                    <?php elseif($row['status'] == 'approved'): ?>
                                        <a href="?action=completed&id=<?php echo $row['id']; ?>" class="action-btn btn-complete" title="Mark Complete">
                                            <i class="ti ti-circle-check"></i> Complete
                                        </a>
                                    <?php else: ?>
                                        <span style="color:#cbd5e1; font-size:12px;">Archived</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr>
                            <td colspan="7" style="text-align:center; padding:60px; color:var(--slate-500)">
                                <i class="ti ti-database-off" style="font-size: 40px; display:block; margin-bottom:10px;"></i>
                                No booking records found.
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