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

// 4. Add Vendor Logic
if(isset($_POST['add_vendor'])) {
    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $stype   = mysqli_real_escape_string($conn, $_POST['service_type']);
    $desc    = mysqli_real_escape_string($conn, $_POST['description']);
    $price   = (float)$_POST['price'];
    $phone   = mysqli_real_escape_string($conn, $_POST['phone']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $status  = 'active';

    $image_name = '';
    if(!empty($_FILES['image']['name'])) {
        $image_name = time().'_'.str_replace(' ','_',$_FILES['image']['name']);
        if(!is_dir('../../uploads/vendors/')) {
            mkdir('../../uploads/vendors/', 0777, true);
        }
        move_uploaded_file($_FILES['image']['tmp_name'], '../../uploads/vendors/'.$image_name);
    }

    $sql = "INSERT INTO vendors (name, service_type, description, price, phone, email, image, status, created_at) 
            VALUES ('$name', '$stype', '$desc', $price, '$phone', '$email', '$image_name', '$status', NOW())";
    
    if(mysqli_query($conn, $sql)) {
        $_SESSION['msg'] = "Vendor added successfully!";
        header('location:manage_vendors.php'); 
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Vendor | EventPro Admin</title>
    
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
            margin-left: 260px; /* Width of sidebar */
            flex: 1; 
            padding: 40px; 
            width: calc(100% - 260px);
        }

        .page-header { 
            display: flex; 
            align-items: center; 
            gap: 15px; 
            margin-bottom: 30px; 
        }
        
        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 700;
            color: var(--dark);
        }

        .back-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            color: var(--slate-500);
            text-decoration: none;
            transition: 0.2s;
        }
        .back-btn:hover {
            color: var(--primary);
            border-color: var(--primary);
            background: #eff6ff;
        }

        /* Form Styling */
        .card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            padding: 40px;
            max-width: 800px;
        }

        .form-group { margin-bottom: 22px; }
        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: var(--slate-500);
            text-transform: uppercase;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-family: inherit;
            font-size: 14px;
            transition: 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

        .btn-primary {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 15px 35px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
        }

        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        @media (max-width: 992px) {
            .admin-content { margin-left: 0; width: 100%; padding: 20px; }
            .grid2 { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <div class="admin-shell">
        <!-- Sidebar Inclusion -->
        <?php include('sidebar.php'); ?>

        <main class="admin-content">
            <div class="page-header">
                <a href="manage_vendors.php" class="back-btn" title="Back to List">
                    <i class="ti ti-arrow-left"></i>
                </a>
                <h1 class="page-title">Onboard New Vendor</h1>
            </div>

            <div class="card">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Business / Vendor Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Royal Catering Services" required>
                    </div>

                    <div class="form-group">
                        <label>Service Category</label>
                        <select name="service_type" class="form-control" required>
                            <option value="">-- Select Specialty --</option>
                            <option value="Photographer">Photographer</option>
                            <option value="Decorator">Decorator</option>
                            <option value="Caterer">Caterer</option>
                            <option value="DJ & Sound">DJ & Sound System</option>
                            <option value="Florist">Florist</option>
                            <option value="Videographer">Videographer</option>
                            <option value="Makeup Artist">Makeup Artist</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Service Description</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Briefly describe what this vendor offers..."></textarea>
                    </div>

                    <div class="grid2">
                        <div class="form-group">
                            <label>Starting Price (BDT)</label>
                            <input type="number" name="price" class="form-control" placeholder="e.g. 25000" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>Contact Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="+880 1XXX-XXXXXX">
                        </div>
                    </div>

                    <div class="grid2">
                        <div class="form-group">
                            <label>Official Email</label>
                            <input type="email" name="email" class="form-control" placeholder="vendor@domain.com">
                        </div>
                        <div class="form-group">
                            <label>Profile / Brand Image</label>
                            <input type="file" name="image" class="form-control" style="padding: 9px;">
                        </div>
                    </div>

                    <div style="margin-top: 10px;">
                        <button type="submit" name="add_vendor" class="btn-primary">
                            <i class="ti ti-plus"></i> Confirm & Add Vendor
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <?php ob_end_flush(); ?>
</body>
</html>