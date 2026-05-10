<?php 
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../../config/db_connect.php'); 

// ১. অ্যাডমিন চেক
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header('location:'.SITEURL.'views/auth/login.php');
    exit();
}

// ২. ডাটা রিট্রিভ করা
if(isset($_GET['id'])){
    $id = mysqli_real_escape_string($conn, $_GET['id']);
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

// ৩. আপডেট লজিক
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
            // পুরাতন ইমেজ ডিলিট করা (যদি থাকে)
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
    <style>
        :root {
            --slate-900: #0f172a; --slate-800: #1e293b; --slate-500: #64748b;
            --blue-600: #2563eb; --bg-light: #f1f5f9;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg-light); color: var(--slate-800); }
        
        .admin-shell { display: flex; min-height: 100vh; }
        .main-content { flex: 1; padding: 40px; display: flex; justify-content: center; align-items: flex-start; }
        
        .edit-card { background: #fff; width: 100%; max-width: 600px; padding: 40px; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); }
        .back-link { display: inline-flex; align-items: center; gap: 8px; color: var(--slate-500); text-decoration: none; font-size: 14px; margin-bottom: 25px; transition: 0.2s; }
        .back-link:hover { color: var(--blue-600); }
        
        h1 { font-family: 'Playfair Display', serif; font-size: 24px; margin-bottom: 30px; }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 12px; font-weight: 700; color: var(--slate-500); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px; }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 10px; font-family: inherit; font-size: 14px; transition: 0.2s; }
        .form-control:focus { border-color: var(--blue-600); outline: none; box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1); }
        
        .current-img-preview { margin-top: 10px; width: 100px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0; }
        
        .btn-update { background: var(--blue-600); color: white; border: none; padding: 14px 25px; width: 100%; border-radius: 10px; font-weight: 700; cursor: pointer; transition: 0.3s; margin-top: 10px; }
        .btn-update:hover { background: #1d4ed8; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); }
    </style>
</head>
<body>

<div class="admin-shell">
    <main class="main-content">
        <div class="edit-card">
            <a href="manage_events.php" class="back-link"><i class="ti ti-arrow-left"></i> Back to Events</a>
            
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
                    </select>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($event['description']); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Update Banner Image</label>
                    <input type="file" name="image" class="form-control">
                    <input type="hidden" name="current_image" value="<?php echo $event['image']; ?>">
                    
                    <?php if($event['image'] != ""): ?>
                        <div style="margin-top: 10px;">
                            <p style="font-size: 11px; color: var(--slate-500);">Current Image:</p>
                            <img src="../../uploads/events/<?php echo $event['image']; ?>" class="current-img-preview">
                        </div>
                    <?php endif; ?>
                </div>

                <button type="submit" name="update_event" class="btn-update">Save Changes</button>
            </form>
        </div>
    </main>
</div>

</body>
</html>
<?php ob_end_flush(); ?>