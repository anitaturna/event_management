<?php 
// 1. Start Output Buffering
ob_start();

// 2. Load Database & Constants
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

        /* Layout for Sidebar Integration */
        .admin-shell { display: flex; min-height: 100vh; }
        
        .admin-content { 
            margin-left: 240px; /* Matching your new compact sidebar width */
            flex: 1; 
            padding: 35px; 
            width: calc(100% - 240px);
        }
        
        .page-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 30px; 
        }
        
        .page-title { 
            font-family: 'Playfair Display', serif; 
            font-size: 32px; 
            font-weight: 700; 
            color: var(--dark);
        }
        
        .btn-primary { 
            background: var(--primary); 
            color: white; 
            border: none; 
            padding: 10px 22px; 
            border-radius: 10px; 
            font-weight: 600; 
            cursor: pointer; 
            text-decoration: none; 
            display: inline-flex; 
            align-items: center; 
            gap: 8px; 
            transition: 0.3s; 
            font-size: 13px; 
        }
        .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }

        .card { 
            background: #fff; 
            border-radius: 16px; 
            border: 1px solid #e2e8f0; 
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); 
            overflow: hidden; 
        }
        
        .table-responsive { overflow-x: auto; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th { 
            background: #f8fafc; 
            text-align: left; 
            padding: 15px 20px; 
            font-size: 11px; 
            text-transform: uppercase; 
            color: var(--slate-500); 
            border-bottom: 2px solid #f1f5f9; 
            font-weight: 700;
        }
        .data-table td { 
            padding: 15px 20px; 
            border-bottom: 1px solid #f1f5f9; 
            font-size: 13px; 
            vertical-align: middle; 
        }
        
        .status-badge { 
            padding: 4px 10px; 
            border-radius: 30px; 
            font-size: 10px; 
            font-weight: 700; 
            text-transform: uppercase; 
            display: inline-block; 
        }
        .status-active { background: #dcfce7; color: #166534; }
        .status-inactive { background: #f1f5f9; color: #64748b; }
        
        .action-btn { 
            color: var(--slate-500); 
            text-decoration: none; 
            font-size: 18px; 
            margin-right: 12px; 
            transition: 0.2s; 
            display: inline-block; 
        }
        .action-btn:hover { color: var(--primary); }
        .action-btn.delete:hover { color: #f87171; }

        .service-tag { 
            background: #eff6ff; 
            padding: 3px 8px; 
            border-radius: 6px; 
            font-size: 11px; 
            color: var(--primary); 
            font-weight: 600;
        }

        @media (max-width: 1024px) {
            .admin-content { margin-left: 0; width: 100%; }
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
                    <h1 class="page-title">Vendor Network</h1>
                    <p style="color: var(--slate-500); font-size: 14px;">Monitor and manage your verified service providers</p>
                </div>
                <a href="add_vendor.php" class="btn-primary">
                    <i class="ti ti-plus"></i> Add New Vendor
                </a>
            </header>

            <div class="card">
                <div class="table-responsive">
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
                                    $status_class = (strtolower($vendor['status']) == 'active') ? 'status-active' : 'status-inactive';
                                    ?>
                                    <tr>
                                        <td>
                                            <div style="font-weight: 700; color: var(--dark);"><?php echo htmlspecialchars($vendor['name']); ?></div>
                                            <div style="font-size: 11px; color: var(--slate-500);">ID: #VND-<?php echo $vendor['id']; ?></div>
                                        </td>
                                        <td><span class="service-tag"><?php echo htmlspecialchars($vendor['service_type']); ?></span></td>
                                        <td style="font-weight: 600;">৳ <?php echo number_format($vendor['price'] ?? 0); ?></td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 4px; color: #f59e0b; font-weight: 700;">
                                                <i class="ti ti-star-filled" style="font-size: 14px;"></i> 
                                                <?php echo htmlspecialchars($vendor['rating'] ?? '0.0'); ?>
                                            </div>
                                        </td>
                                        <td><span class="status-badge <?php echo $status_class; ?>">● <?php echo ucfirst(htmlspecialchars($vendor['status'])); ?></span></td>
                                        <td style="text-align: right;">
                                            <a href="edit_vendor.php?id=<?php echo $vendor['id']; ?>" class="action-btn" title="Edit"><i class="ti ti-edit"></i></a>
                                            <a href="delete_vendor.php?id=<?php echo $vendor['id']; ?>" class="action-btn delete" title="Delete" onclick="return confirm('Remove this vendor?');"><i class="ti ti-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php 
                                } 
                            } else {
                                // FIXED: Using single quotes inside echo double quotes to avoid Parse Error
                                echo "<tr><td colspan='6' style='text-align:center; padding: 60px; color: var(--slate-500);'>
                                    <i class='ti ti-building-off' style='font-size: 40px; display: block; margin-bottom: 10px;'></i>
                                    No vendors found in the network.
                                </td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <?php ob_end_flush(); ?>
</body>
</html>