<?php 
ob_start();
// সেশন চেক (যদি constants.php তে না থাকে)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../../config/db_connect.php'); 

// ১. অ্যাডমিন অ্যাক্সেস সিকিউরিটি
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header('location:'.SITEURL.'views/auth/login.php');
    exit();
}

// ২. ইভেন্ট ডিলিট লজিক (এটি নতুন যোগ করা হয়েছে)
if(isset($_GET['delete_id'])){
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    
    // ফোল্ডার থেকে ইমেজ ডিলিট করার জন্য নাম আনা
    $get_img = mysqli_query($conn, "SELECT image FROM events WHERE id=$delete_id");
    $img_data = mysqli_fetch_assoc($get_img);
    
    if($img_data && $img_data['image'] != ""){
        $path = "../../uploads/events/".$img_data['image'];
        if(file_exists($path)){
            unlink($path);
        }
    }

    $sql_delete = "DELETE FROM events WHERE id=$delete_id";
    if(mysqli_query($conn, $sql_delete)){
        $_SESSION['delete_success'] = "Event deleted successfully!";
        header('location:manage_events.php');
        exit();
    }
}

// ৩. ইভেন্ট অ্যাড লজিক
if(isset($_POST['add_event'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status = "active";

    $image_name = "";
    if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != ""){
        $image_name = time().'_'.str_replace(' ', '_', $_FILES['image']['name']);
        $source_path = $_FILES['image']['tmp_name'];
        $destination_path = "../../uploads/events/".$image_name;
        
        if(!is_dir('../../uploads/events/')) {
            mkdir('../../uploads/events/', 0777, true);
        }
        move_uploaded_file($source_path, $destination_path);
    }

    $sql = "INSERT INTO events (name, category, description, image, status) 
            VALUES ('$name', '$category', '$description', '$image_name', '$status')";
    
    if(mysqli_query($conn, $sql)){
        $_SESSION['add_success'] = "Event added successfully!";
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
    <title>Manage Events | EventPro Admin</title>
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
        
        /* Sidebar */
        .sidebar { width: 260px; background: var(--slate-900); color: #fff; position: fixed; height: 100vh; z-index: 100; border-right: 1px solid var(--slate-800); }
        .brand { padding: 25px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid var(--slate-800); }
        .brand-icon { width: 35px; height: 35px; background: var(--blue-600); border-radius: 8px; display: flex; align-items: center; justify-content: center; }
        .brand-text .name { font-family: 'Playfair Display', serif; font-size: 18px; font-weight: 700; color: #fff; }
        
        nav { padding: 20px 15px; }
        nav a { display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: #94a3b8; text-decoration: none; border-radius: 8px; font-size: 14px; margin-bottom: 5px; transition: 0.3s; }
        nav a:hover, nav a.active { background: var(--slate-800); color: #fff; }
        nav a.active { background: var(--blue-600); }

        /* Main Content Area */
        .main-content { margin-left: 260px; flex: 1; padding: 40px; }
        .page-header { margin-bottom: 30px; }
        .page-title { font-family: 'Playfair Display', serif; font-size: 28px; font-weight: 700; }

        .grid-container { display: grid; grid-template-columns: 350px 1fr; gap: 30px; align-items: start; }
        
        /* Forms & Cards */
        .card { background: #fff; padding: 25px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 13px; font-weight: 700; color: var(--slate-500); text-transform: uppercase; margin-bottom: 8px; }
        .form-control { width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-family: inherit; font-size: 14px; }
        .btn-primary { background: var(--blue-600); color: white; border: none; padding: 12px; width: 100%; border-radius: 8px; font-weight: 700; cursor: pointer; transition: 0.2s; }
        .btn-primary:hover { opacity: 0.9; }

        /* Data Table */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th { background: #f8fafc; text-align: left; padding: 15px; font-size: 12px; text-transform: uppercase; color: var(--slate-500); border-bottom: 2px solid #f1f5f9; }
        .data-table td { padding: 15px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        
        .status-badge { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; background: #dcfce7; color: #166534; }
        .action-btn { color: var(--slate-500); text-decoration: none; font-size: 20px; margin-right: 12px; transition: 0.2s; display: inline-block; }
        .action-btn:hover { color: var(--blue-600); }

        @media (max-width: 1024px) {
            .grid-container { grid-template-columns: 1fr; }
            .sidebar { width: 70px; }
            .sidebar .brand-text, .sidebar span { display: none; }
            .main-content { margin-left: 70px; }
        }
    </style>
</head>
<body>

<div class="admin-shell">
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon"><i class="ti ti-shield-check"></i></div>
            <div class="brand-text"><p class="name">EventPro</p></div>
        </div>
        <nav>
            <a href="dashboard.php"><i class="ti ti-layout-dashboard"></i> <span>Dashboard</span></a>
            <a href="manage_events.php" class="active"><i class="ti ti-calendar-event"></i> <span>Events</span></a>
            <a href="manage_vendors.php"><i class="ti ti-building-store"></i> <span>Vendors</span></a>
            <a href="manage_bookings.php"><i class="ti ti-receipt"></i> <span>Bookings</span></a>
            <a href="../auth/logout.php" style="margin-top: 50px; color: #f87171;"><i class="ti ti-logout"></i> <span>Sign Out</span></a>
        </nav>
    </aside>

    <main class="main-content">
        <header class="page-header">
            <h1 class="page-title">Event Portfolio</h1>
            <p style="color: var(--slate-500); font-size: 14px;">Manage and update your active event categories</p>
        </header>

        <div class="grid-container">
            <div class="card">
                <h3 style="margin-bottom: 20px; font-size: 18px;"><i class="ti ti-plus" style="color: var(--blue-600);"></i> New Event</h3>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Event Title</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Wedding Gala" required>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" class="form-control">
                            <option value="Wedding">Wedding</option>
                            <option value="Corporate">Corporate</option>
                            <option value="Birthday">Birthday</option>
                            <option value="Concert">Concert</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Brief info..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Banner Image</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                    <button type="submit" name="add_event" class="btn-primary">Publish Event</button>
                </form>
            </div>

            <div class="card" style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Cover</th>
                            <th>Title & ID</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $res = mysqli_query($conn, "SELECT * FROM events ORDER BY id DESC");
                        if(mysqli_num_rows($res) > 0) {
                            while($row = mysqli_fetch_assoc($res)){ ?>
                                <tr>
                                    <td>
                                        <img src="../../uploads/events/<?php echo $row['image']; ?>" 
                                             style="width: 50px; height: 35px; object-fit: cover; border-radius: 4px;" 
                                             onerror="this.src='https://via.placeholder.com/50x35'">
                                    </td>
                                    <td>
                                        <div style="font-weight: 700;"><?php echo htmlspecialchars($row['name']); ?></div>
                                        <div style="font-size: 11px; color: var(--slate-500);">#E-<?php echo $row['id']; ?></div>
                                    </td>
                                    <td><span style="background: #f1f5f9; padding: 4px 8px; border-radius: 6px; font-size: 12px;"><?php echo $row['category']; ?></span></td>
                                    <td><span class="status-badge">● <?php echo $row['status']; ?></span></td>
                                    <td>
                                        <a href="edit_event.php?id=<?php echo $row['id']; ?>" class="action-btn" title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <a href="manage_events.php?delete_id=<?php echo $row['id']; ?>" 
                                           class="action-btn" 
                                           style="color: #f87171;" 
                                           onclick="return confirm('Are you sure you want to delete this event?');">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php } 
                        } else {
                            echo "<tr><td colspan='5' style='text-align:center; padding: 30px;'>No events found.</td></tr>";
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