<?php 
ob_start();
// 1. Session & DB Connection
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('../../config/db_connect.php'); 

// 2. Admin Access Security
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header('location:'.SITEURL.'views/auth/login.php');
    exit();
}

// 3. Data Retrieval
if(isset($_GET['id'])){
    $id = (int)mysqli_real_escape_string($conn, $_GET['id']);
    $res = mysqli_query($conn, "SELECT * FROM events WHERE id=$id");
    
    if(mysqli_num_rows($res) == 1){
        $event = mysqli_fetch_assoc($res);
    } else {
        header('location:manage_events.php');
        exit();
    }
} else {
    header('location:manage_events.php');
    exit();
}

// 4. Update Logic
if(isset($_POST['update_event'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $current_image = $_POST['current_image'];
    
    $image_name = $current_image;

    if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != ""){
        $image_name = time().'_'.str_replace(' ', '_', $_FILES['image']['name']);
        $source_path = $_FILES['image']['tmp_name'];
        $destination_path = "../../uploads/events/".$image_name;
        
        if(move_uploaded_file($source_path, $destination_path)){
            // Delete old image if exists
            if($current_image != "" && file_exists("../../uploads/events/".$current_image)){
                unlink("../../uploads/events/".$current_image);
            }
        }
    }

    $sql = "UPDATE events SET 
            name='$name', 
            category='$category', 
            description='$description', 
            image='$image_name' 
            WHERE id=$id";

    if(mysqli_query($conn, $sql)){
        $_SESSION['msg'] = "Event updated successfully!";
        header('location:manage_events.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event | EventPro Admin</title>
    
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
            margin-left: 260px; /* Matching your sidebar width */
            flex: 1; 
            padding: 40px; 
            width: calc(100% - 260px);
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .edit-card { 
            background: #fff; 
            width: 100%; 
            max-width: 700px; 
            padding: 40px; 
            border-radius: 16px; 
            border: 1px solid #e2e8f0; 
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); 
        }

        .back-link { 
            display: inline-flex; 
            align-items: center; 
            gap: 8px; 
            color: var(--slate-500); 
            text-decoration: none; 
            font-size: 14px; 
            margin-bottom: 25px; 
            transition: 0.2s; 
            font-weight: 500;
        }
        .back-link:hover { color: var(--primary); }
        
        h1 { 
            font-family: 'Playfair Display', serif; 
            font-size: 28px; 
            font-weight: 700;
            margin-bottom: 30px; 
            color: var(--dark);
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
            border-color: var(--primary); 
            outline: none; 
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1); 
        }
        
        .current-img-preview { 
            margin-top: 10px; 
            width: 120px; 
            height: 75px; 
            object-fit: cover; 
            border-radius: 8px; 
            border: 1px solid #e2e8f0; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .btn-update { 
            background: var(--primary); 
            color: white; 
            border: none; 
            padding: 15px 25px; 
            width: 100%; 
            border-radius: 10px; 
            font-weight: 600; 
            cursor: pointer; 
            transition: 0.3s; 
            margin-top: 10px; 
            font-size: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-update:hover { 
            background: #1d4ed8; 
            transform: translateY(-1px); 
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); 
        }

        @media (max-width: 992px) {
            .admin-content { margin-left: 0; width: 100%; padding: 20px; }
        }
    </style>
</head>
<body>

<div class="admin-shell">
    <!-- Sidebar Inclusion -->
    <?php include('sidebar.php'); ?>

    <main class="admin-content">
        <div class="edit-card">
            <a href="manage_events.php" class="back-link">
                <i class="ti ti-arrow-left"></i> Back to Event Portfolio
            </a>
            
            <h1>Edit Event Details</h1>
            
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Event Title</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($event['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <select name="category" class="form-control">
                        <option value="Wedding" <?php if($event['category'] == 'Wedding') echo 'selected'; ?>>Wedding</option>
                        <option value="Corporate" <?php if($event['category'] == 'Corporate') echo 'selected'; ?>>Corporate</option>
                        <option value="Birthday" <?php if($event['category'] == 'Birthday') echo 'selected'; ?>>Birthday</option>
                        <option value="Concert" <?php if($event['category'] == 'Concert') echo 'selected'; ?>>Concert</option>
                        <option value="Festival" <?php if($event['category'] == 'Festival') echo 'selected'; ?>>Festival</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="5"><?php echo htmlspecialchars($event['description']); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Update Banner Image</label>
                    <input type="file" name="image" class="form-control" style="padding: 10px;">
                    <input type="hidden" name="current_image" value="<?php echo $event['image']; ?>">
                    
                    <?php if($event['image'] != ""): ?>
                        <div style="margin-top: 15px; padding: 15px; background: #f8fafc; border-radius: 10px; border: 1px dashed #cbd5e1;">
                            <p style="font-size: 11px; font-weight:700; color: var(--slate-500); text-transform: uppercase; margin-bottom: 8px;">Active Banner:</p>
                            <img src="../../uploads/events/<?php echo $event['image']; ?>" class="current-img-preview" onerror="this.src='https://via.placeholder.com/120x75?text=Missing'">
                        </div>
                    <?php endif; ?>
                </div>

                <button type="submit" name="update_event" class="btn-update">
                    <i class="ti ti-device-floppy"></i> Save Changes
                </button>
            </form>
        </div>
    </main>
</div>

</body>
</html>
<?php ob_end_flush(); ?>