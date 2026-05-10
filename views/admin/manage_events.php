<?php 
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('../../config/db_connect.php'); 

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header('location:'.SITEURL.'views/auth/login.php');
    exit();
}

// ৩. ইভেন্ট ডিলিট লজিক
if(isset($_GET['delete_id'])){
    $delete_id = (int)$_GET['delete_id'];
    $get_img = mysqli_query($conn, "SELECT image FROM events WHERE id=$delete_id");
    $img_data = mysqli_fetch_assoc($get_img);
    if($img_data && $img_data['image'] != "" && file_exists("../../uploads/events/".$img_data['image'])){
        unlink("../../uploads/events/".$img_data['image']);
    }
    mysqli_query($conn, "DELETE FROM events WHERE id=$delete_id");
    header('location:manage_events.php'); exit();
}

// ৪. ইভেন্ট অ্যাড লজিক
if(isset($_POST['add_event'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $image_name = "";
    if(!empty($_FILES['image']['name'])){
        $image_name = time().'_'.str_replace(' ', '_', $_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "../../uploads/events/".$image_name);
    }
    mysqli_query($conn, "INSERT INTO events (name, category, description, image, status) VALUES ('$name', '$category', '$description', '$image_name', 'active')");
    header('location:manage_events.php'); exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events | EventPro Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #2563eb; --dark: #0f172a; --slate-500: #64748b; --bg-light: #f8fafc; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: var(--bg-light); color: var(--dark); line-height: 1.6; }
        .admin-shell { display: flex; min-height: 100vh; }
        .admin-content { margin-left: 240px; flex: 1; padding: 30px; width: calc(100% - 240px); }
        .page-title { font-family: 'Playfair Display', serif; font-size: 28px; font-weight: 700; margin-bottom: 25px; }
        .grid-container { display: grid; grid-template-columns: 350px 1fr; gap: 25px; align-items: start; }
        .card { background: #fff; padding: 25px; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 11px; font-weight: 700; color: var(--slate-500); text-transform: uppercase; margin-bottom: 5px; }
        .form-control { width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; }
        .btn-primary { background: var(--primary); color: white; border: none; padding: 12px; width: 100%; border-radius: 8px; font-weight: 600; cursor: pointer; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th { background: #f8fafc; text-align: left; padding: 12px 15px; font-size: 11px; text-transform: uppercase; color: var(--slate-500); border-bottom: 2px solid #f1f5f9; }
        .data-table td { padding: 12px 15px; border-bottom: 1px solid #f1f5f9; font-size: 13px; vertical-align: middle; }
        .action-btn { color: var(--slate-500); text-decoration: none; font-size: 18px; margin-right: 10px; }
        .status-badge { background: #dcfce7; color: #166534; padding: 3px 10px; border-radius: 20px; font-size: 10px; font-weight: 700; }
        @media (max-width: 1024px) { .admin-content { margin-left: 0; width: 100%; } .grid-container { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="admin-shell">
        <?php include('sidebar.php'); ?>
        <main class="admin-content">
            <h1 class="page-title">Event Portfolio</h1>
            <div class="grid-container">
                <div class="card">
                    <h3 style="margin-bottom: 15px; font-size: 16px;"><i class="ti ti-plus"></i> New Event</h3>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-group"><label>Event Title</label><input type="text" name="name" class="form-control" required></div>
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category" class="form-control">
                                <option value="Wedding">Wedding</option>
                                <option value="Corporate">Corporate</option>
                                <option value="Birthday">Birthday</option>
                                <option value="Concert">Concert</option>
                            </select>
                        </div>
                        <div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="3"></textarea></div>
                        <div class="form-group"><label>Image</label><input type="file" name="image" class="form-control"></div>
                        <button type="submit" name="add_event" class="btn-primary">Publish</button>
                    </form>
                </div>

                <div class="card" style="padding: 0; overflow: hidden;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Cover</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th style="text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $res = mysqli_query($conn, "SELECT * FROM events ORDER BY id DESC");
                            if($res && mysqli_num_rows($res) > 0) {
                                while($row = mysqli_fetch_assoc($res)){ ?>
                                    <tr>
                                        <td><img src="../../uploads/events/<?php echo $row['image']; ?>" style="width: 50px; height: 35px; object-fit: cover; border-radius: 4px;" onerror="this.src='https://via.placeholder.com/50x35'"></td>
                                        <td><div style="font-weight: 600;"><?php echo htmlspecialchars($row['name']); ?></div></td>
                                        <td><?php echo $row['category']; ?></td>
                                        <td><span class="status-badge">● <?php echo $row['status']; ?></span></td>
                                        <td style="text-align: right;">
                                            <a href="edit_event.php?id=<?php echo $row['id']; ?>" class="action-btn"><i class="ti ti-edit"></i></a>
                                            <a href="manage_events.php?delete_id=<?php echo $row['id']; ?>" class="action-btn" style="color: #f87171;" onclick="return confirm('Delete this event?');"><i class="ti ti-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php } 
                            } else {
                                echo "<tr><td colspan='5' style='text-align:center; padding: 50px; color: var(--slate-500);'>
                                    <i class='ti ti-archive' style='font-size: 32px; display: block; margin-bottom: 10px;'></i>
                                    Your portfolio is currently empty.
                                </td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
<?php ob_end_flush(); ?>