<?php 
// 1. Session & DB Connection
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('../../config/db_connect.php'); 
include('../../includes/header.php'); 
include('../../includes/navbar.php');

// 2. Validate Event ID
if(!isset($_GET['id']) || empty($_GET['id'])) { 
    header('Location: events_list.php'); 
    exit(); 
}

$event_id = (int)mysqli_real_escape_string($conn, $_GET['id']);

// 3. Fetch Event Data
$res = mysqli_query($conn, "SELECT * FROM events WHERE id=$event_id AND status='active'");

if(!$res || mysqli_num_rows($res) == 0) { 
    echo "<div style='text-align:center; padding: 100px 20px; font-family: Inter, sans-serif;'>
            <h2 style='color:#0f172a; font-family: Playfair Display, serif;'>Event Not Found</h2>
            <p style='color:#64748b; margin-bottom: 20px;'>The event you are looking for does not exist or has been removed.</p>
            <a href='events_list.php' style='background:#2563eb; color:white; padding: 10px 20px; border-radius: 8px; text-decoration:none; font-weight:600;'>Browse Events</a>
          </div>"; 
    include('../../includes/footer.php'); 
    exit(); 
}

$event = mysqli_fetch_assoc($res);
$img = (!empty($event['image'])) ? "../../uploads/events/".$event['image'] : "https://images.unsplash.com/photo-1511578314322-379afb476865?q=80&w=1200";

// 4. Fetch Reviews & Gallery
$rev_res = mysqli_query($conn, "SELECT r.*, u.name as uname FROM reviews r JOIN users u ON r.user_id=u.id WHERE r.event_id=$event_id ORDER BY r.created_at DESC");
$avg_rating_r = mysqli_query($conn, "SELECT AVG(rating) as avg_r FROM reviews WHERE event_id=$event_id");
$avg_r = mysqli_fetch_assoc($avg_rating_r)['avg_r'];

$gal_res = mysqli_query($conn, "SELECT * FROM gallery WHERE event_id=$event_id LIMIT 6");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($event['name']); ?> | EventPro</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        :root { --blue: #2563eb; --blue-hover: #1d4ed8; --dark: #0f172a; --gray: #64748b; --bg: #f8fafc; --white: #ffffff; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--dark); line-height: 1.6; margin: 0; }
        
        .container { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }
        
        /* Layout Grid */
        .event-layout { display: grid; grid-template-columns: 1fr 400px; gap: 40px; }
        
        /* Main Content */
        .event-banner { width: 100%; height: 450px; object-fit: cover; border-radius: 20px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.08); }
        .category-tag { display: inline-block; background: #eff6ff; color: var(--blue); padding: 6px 16px; border-radius: 30px; font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 15px; letter-spacing: 1px; }
        .event-title { font-family: 'Playfair Display', serif; font-size: clamp(2.5rem, 4vw, 3.5rem); margin: 0 0 10px; line-height: 1.1; }
        .rating-summary { display: flex; align-items: center; gap: 8px; color: #f59e0b; margin-bottom: 30px; font-weight: 600; font-size: 15px; }
        
        .description-card { background: var(--white); padding: 35px; border-radius: 16px; border: 1px solid #e2e8f0; margin-bottom: 30px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); }
        .section-title { font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 700; margin: 0 0 20px; display: flex; align-items: center; gap: 10px; color: var(--dark); }

        /* Sidebar & Packages */
        .sidebar { position: relative; }
        .sidebar-inner { background: var(--white); border-radius: 16px; border: 1px solid #e2e8f0; padding: 30px; position: sticky; top: 30px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); }
        
        .pkg-box { border: 2px solid #f1f5f9; border-radius: 12px; padding: 25px; margin-bottom: 20px; transition: all 0.3s ease; position: relative; overflow: hidden; }
        .pkg-box:hover { border-color: var(--blue); box-shadow: 0 10px 20px rgba(37,99,235,0.05); transform: translateY(-3px); }
        .pkg-name { font-size: 1.2rem; font-weight: 700; margin-bottom: 8px; color: var(--dark); }
        .pkg-price { font-size: 1.8rem; font-weight: 700; color: var(--blue); margin-bottom: 20px; font-family: 'Playfair Display', serif; }
        
        .pkg-list { font-size: 14px; color: var(--gray); margin-bottom: 25px; list-style: none; padding: 0; }
        .pkg-list li { margin-bottom: 10px; display: flex; align-items: flex-start; gap: 10px; line-height: 1.4; }
        .pkg-list li i { color: #10b981; font-size: 18px; margin-top: 2px; flex-shrink: 0; }

        .btn-book { display: block; width: 100%; text-align: center; background: var(--dark); color: var(--white); padding: 14px; border-radius: 10px; text-decoration: none; font-weight: 600; transition: 0.3s; font-size: 15px; }
        .btn-book:hover { background: var(--blue); box-shadow: 0 10px 15px -3px rgba(37,99,235,0.2); }

        /* Gallery Grid */
        .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 15px; }
        .gallery-grid img { width: 100%; height: 120px; object-fit: cover; border-radius: 10px; transition: 0.3s; border: 1px solid #e2e8f0; }
        .gallery-grid img:hover { transform: scale(1.05); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }

        @media (max-width: 992px) {
            .event-layout { grid-template-columns: 1fr; }
            .sidebar-inner { position: static; }
        }
    </style>
</head>
<body>

<div class="container">
    <img src="<?php echo htmlspecialchars($img); ?>" class="event-banner" alt="Event Banner">

    <div class="event-layout">
        <!-- Left: Main Content -->
        <div class="main-content">
            <span class="category-tag"><?php echo htmlspecialchars($event['category']); ?></span>
            <h1 class="event-title"><?php echo htmlspecialchars($event['name']); ?></h1>
            
            <div class="rating-summary">
                <i class="ti ti-star-filled"></i> 
                <span><?php echo $avg_r ? number_format($avg_r, 1) . ' / 5.0' : 'New Event (No ratings yet)'; ?></span>
            </div>

            <div class="description-card">
                <h3 class="section-title"><i class="ti ti-info-circle" style="color:var(--blue);"></i> About this Event</h3>
                <p style="color: var(--gray); white-space: pre-line; font-size: 1.05rem;">
                    <?php echo htmlspecialchars($event['description']); ?>
                </p>
            </div>

            <?php if($gal_res && mysqli_num_rows($gal_res) > 0): ?>
            <div class="description-card">
                <h3 class="section-title"><i class="ti ti-photo" style="color:var(--blue);"></i> Event Gallery</h3>
                <div class="gallery-grid">
                    <?php while($g = mysqli_fetch_assoc($gal_res)): ?>
                        <img src="../../uploads/gallery/<?php echo htmlspecialchars($g['image']); ?>" alt="Gallery Image">
                    <?php endwhile; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="description-card">
                <h3 class="section-title"><i class="ti ti-message-circle-2" style="color:var(--blue);"></i> Client Testimonials</h3>
                <?php if($rev_res && mysqli_num_rows($rev_res) > 0): 
                    while($rv = mysqli_fetch_assoc($rev_res)): ?>
                    <div style="border-bottom: 1px solid #f1f5f9; padding: 20px 0;">
                        <div style="font-weight: 600; font-size: 15px; color: var(--dark);"><?php echo htmlspecialchars($rv['uname']); ?></div>
                        <div style="color: #f59e0b; font-size: 13px; margin: 5px 0 10px;">
                            <?php echo str_repeat('★', (int)$rv['rating']) . str_repeat('☆', 5 - (int)$rv['rating']); ?>
                        </div>
                        <p style="font-size: 14px; color: var(--gray); margin:0; font-style: italic;">"<?php echo htmlspecialchars($rv['comment']); ?>"</p>
                    </div>
                <?php endwhile; else: ?>
                    <p style="color: var(--gray); font-size: 14px; background: #f8fafc; padding: 20px; border-radius: 8px; text-align: center;">No reviews yet. Share your experience after booking!</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right: Sidebar Packages -->
        <div class="sidebar">
            <div class="sidebar-inner">
                <h3 class="section-title" style="margin-bottom: 25px;"><i class="ti ti-packages" style="color:var(--blue);"></i> Select a Package</h3>
                
                <?php 
                $pkg_query = "SELECT * FROM packages WHERE event_id = $event_id ORDER BY price ASC";
                $pkgs = mysqli_query($conn, $pkg_query);
                
                if($pkgs && mysqli_num_rows($pkgs) > 0):
                    while($pkg = mysqli_fetch_assoc($pkgs)): ?>
                    
                    <div class="pkg-box">
                        <div class="pkg-name"><?php echo htmlspecialchars($pkg['name']); ?></div>
                        <div class="pkg-price">৳ <?php echo number_format($pkg['price']); ?></div>
                        
                        <ul class="pkg-list">
                            <?php 
                            $services = array_map('trim', explode(',', $pkg['included_services']));
                            foreach($services as $service): 
                                if(!empty($service)): ?>
                                    <li><i class="ti ti-circle-check"></i> <span><?php echo htmlspecialchars($service); ?></span></li>
                            <?php endif; endforeach; ?>
                        </ul>

                        <?php if(isset($_SESSION['user_id'])): ?>
                            <a href="booking_form.php?package_id=<?php echo $pkg['id']; ?>" class="btn-book">Select & Book</a>
                        <?php else: ?>
                            <a href="../auth/login.php" class="btn-book" style="background: var(--gray);">Login to Book</a>
                        <?php endif; ?>
                    </div>

                <?php endwhile; else: ?>
                    <div style="background: #f8fafc; padding: 30px 20px; border-radius: 10px; text-align: center; border: 1px dashed #cbd5e1;">
                        <i class="ti ti-box-off" style="font-size: 30px; color: #94a3b8; margin-bottom: 10px; display: block;"></i>
                        <p style="color: var(--gray); font-size: 14px; margin: 0;">No packages available for this event currently.</p>
                    </div>
                <?php endif; ?>

                <div style="margin-top: 25px; padding: 15px; background: #eff6ff; border-radius: 10px; font-size: 12px; color: var(--blue); border: 1px solid #bfdbfe; display: flex; align-items: center; gap: 10px;">
                    <i class="ti ti-shield-check-filled" style="font-size: 24px;"></i> 
                    <span>100% Secure Booking & Verified Professional Vendors.</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../../includes/footer.php'); ?>
</body>
</html>