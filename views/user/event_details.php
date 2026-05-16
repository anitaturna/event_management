<?php 
if(session_status() === PHP_SESSION_NONE) session_start();
require_once('../../config/db_connect.php'); 
include('../../includes/header.php'); 
include('../../includes/navbar.php');

if(!isset($_GET['id']) || empty($_GET['id'])){
    header('Location: events_list.php'); exit();
}

$event_id = (int)$_GET['id'];
$res = mysqli_query($conn, "SELECT * FROM events WHERE id=$event_id AND status='active'");

if(!$res || mysqli_num_rows($res) == 0){
    ?>
    <div style="text-align:center;padding:120px 20px;font-family:'Outfit',sans-serif;background:#0a0a0f;min-height:80vh;display:flex;flex-direction:column;align-items:center;justify-content:center;">
        <i class="ti ti-calendar-off" style="font-size:56px;color:rgba(255,255,255,0.12);margin-bottom:20px;display:block;"></i>
        <h2 style="font-family:'Cormorant Garamond',serif;font-size:2rem;color:#fff;margin-bottom:10px;">Event Not Found</h2>
        <p style="color:rgba(255,255,255,0.4);font-size:14px;margin-bottom:28px;">This event does not exist or has been removed.</p>
        <a href="events_list.php" style="background:#c9a84c;color:#0a0a0f;padding:10px 28px;border-radius:8px;text-decoration:none;font-weight:700;font-size:13px;">Browse Events</a>
    </div>
    <?php
    include('../../includes/footer.php'); exit();
}

$event = mysqli_fetch_assoc($res);
$img   = (!empty($event['image'])) ? "../../uploads/events/".$event['image'] : "https://images.unsplash.com/photo-1511578314322-379afb476865?q=80&w=1400";

$rev_res = mysqli_query($conn, "SELECT r.*, u.name as uname FROM reviews r JOIN users u ON r.user_id=u.id WHERE r.event_id=$event_id ORDER BY r.created_at DESC");
$avg_r_q = mysqli_query($conn, "SELECT AVG(rating) as avg_r, COUNT(*) as cnt FROM reviews WHERE event_id=$event_id");
$avg_row = mysqli_fetch_assoc($avg_r_q);
$avg_r   = $avg_row['avg_r'] ? (float)$avg_row['avg_r'] : 0;
$rev_cnt = (int)$avg_row['cnt'];

$gal_res = mysqli_query($conn, "SELECT * FROM gallery WHERE event_id=$event_id LIMIT 6");
?>

<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

<style>
:root {
    --gold:     #c9a84c;
    --gold-lt:  #f0d48a;
    --gold-dk:  #8a6d2f;
    --obsidian: #0a0a0f;
    --surface:  #14141c;
    --surface2: #1a1a26;
    --border:   rgba(255,255,255,0.07);
    --fog:      rgba(255,255,255,0.45);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: 'Outfit', sans-serif;
    background: var(--obsidian);
    color: rgba(255,255,255,0.85);
    -webkit-font-smoothing: antialiased;
}

/* ── HERO BANNER ── */
.detail-hero {
    position: relative;
    height: 480px;
    overflow: hidden;
}
.detail-hero-img {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
    filter: brightness(0.45);
    transition: transform 8s ease;
    animation: slow-zoom 8s ease forwards;
}
@keyframes slow-zoom {
    from { transform: scale(1.06); }
    to   { transform: scale(1); }
}
.detail-hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, var(--obsidian) 0%, rgba(10,10,15,0.3) 50%, transparent 100%);
}
.detail-hero-content {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    padding: 0 24px 40px;
    max-width: 1240px;
    margin: 0 auto;
}
.detail-hero-inner {
    max-width: 1240px;
    margin: 0 auto;
}
.detail-cat-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(201,168,76,0.12);
    border: 1px solid rgba(201,168,76,0.3);
    border-radius: 100px;
    padding: 5px 14px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--gold-lt);
    margin-bottom: 14px;
}
.detail-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(2.2rem, 5vw, 3.8rem);
    font-weight: 700;
    color: #fff;
    line-height: 1.05;
    margin-bottom: 14px;
}
.detail-rating-row {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
}
.stars { display: flex; gap: 3px; }
.star-full { color: var(--gold); font-size: 16px; }
.star-empty { color: rgba(255,255,255,0.2); font-size: 16px; }
.rating-val { font-size: 15px; font-weight: 700; color: var(--gold-lt); }
.rating-count { font-size: 13px; color: var(--fog); }

/* ── BACK LINK ── */
.back-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: rgba(255,255,255,0.45);
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    transition: color 0.2s;
    padding: 20px 24px 0;
    max-width: 1240px;
    margin: 0 auto;
    display: flex;
}
.back-link:hover { color: var(--gold); }

/* ── LAYOUT ── */
.detail-wrap {
    max-width: 1240px;
    margin: 0 auto;
    padding: 36px 24px 100px;
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 32px;
    align-items: start;
}

/* ── CONTENT CARDS ── */
.content-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 28px 30px;
    margin-bottom: 20px;
}
.content-card:last-child { margin-bottom: 0; }
.card-heading {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.4rem;
    font-weight: 700;
    color: #fff;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 14px;
    border-bottom: 1px solid var(--border);
}
.card-heading i { font-size: 20px; color: var(--gold); }

/* About text */
.about-text {
    color: rgba(255,255,255,0.55);
    font-size: 14.5px;
    line-height: 1.8;
    font-weight: 300;
    white-space: pre-line;
}

/* Highlights grid */
.highlights-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 10px;
}
.highlight-item {
    background: var(--surface2);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.highlight-item i { font-size: 18px; color: var(--gold); flex-shrink: 0; }
.highlight-text { font-size: 12.5px; color: rgba(255,255,255,0.65); line-height: 1.35; }
.highlight-label { font-size: 10px; color: rgba(255,255,255,0.3); margin-bottom: 1px; }

/* Gallery */
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
}
.gallery-grid img {
    width: 100%; height: 130px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid var(--border);
    transition: all 0.3s;
    filter: brightness(0.8);
    cursor: pointer;
}
.gallery-grid img:hover { transform: scale(1.03); filter: brightness(1); border-color: rgba(201,168,76,0.3); }

/* Reviews */
.review-item {
    padding: 18px 0;
    border-bottom: 1px solid var(--border);
}
.review-item:last-child { border-bottom: none; padding-bottom: 0; }
.review-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}
.reviewer-info { display: flex; align-items: center; gap: 10px; }
.reviewer-avatar {
    width: 34px; height: 34px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--gold), var(--gold-dk));
    color: var(--obsidian);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
    flex-shrink: 0;
    text-transform: uppercase;
}
.reviewer-name { font-size: 13px; font-weight: 600; color: #fff; }
.reviewer-date { font-size: 11px; color: rgba(255,255,255,0.3); }
.review-stars { display: flex; gap: 2px; }
.review-stars .star-full { font-size: 13px; }
.review-stars .star-empty { font-size: 13px; }
.review-text {
    font-size: 13.5px;
    color: rgba(255,255,255,0.5);
    font-style: italic;
    line-height: 1.65;
    font-weight: 300;
    margin-top: 6px;
}
.no-reviews {
    text-align: center;
    padding: 30px 20px;
    background: var(--surface2);
    border-radius: 10px;
    border: 1px dashed rgba(255,255,255,0.1);
}
.no-reviews i { font-size: 32px; color: rgba(255,255,255,0.12); margin-bottom: 10px; display: block; }
.no-reviews p { font-size: 13px; color: rgba(255,255,255,0.3); }

/* ── SIDEBAR ── */
.sidebar-sticky {
    position: sticky;
    top: 80px;
}
.sidebar-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 26px;
}
.sidebar-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.3rem;
    font-weight: 700;
    color: #fff;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
    padding-bottom: 14px;
    border-bottom: 1px solid var(--border);
}
.sidebar-title i { color: var(--gold); font-size: 18px; }

/* Package boxes */
.pkg-box {
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 14px;
    transition: all 0.25s;
    position: relative;
    overflow: hidden;
    cursor: pointer;
}
.pkg-box::before {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 3px; height: 100%;
    background: var(--gold);
    opacity: 0;
    transition: opacity 0.2s;
}
.pkg-box:hover { border-color: rgba(201,168,76,0.3); background: rgba(201,168,76,0.03); }
.pkg-box:hover::before { opacity: 1; }
.pkg-box.featured {
    border-color: rgba(201,168,76,0.4);
    background: rgba(201,168,76,0.04);
}
.pkg-box.featured::before { opacity: 1; }
.pkg-popular {
    position: absolute;
    top: 12px; right: 12px;
    background: rgba(201,168,76,0.15);
    border: 1px solid rgba(201,168,76,0.3);
    color: var(--gold-lt);
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    padding: 2px 8px;
    border-radius: 100px;
}
.pkg-name {
    font-size: 14px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 4px;
}
.pkg-price {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.7rem;
    font-weight: 700;
    color: var(--gold-lt);
    margin-bottom: 14px;
}
.pkg-price span {
    font-family: 'Outfit', sans-serif;
    font-size: 12px;
    color: rgba(255,255,255,0.3);
    font-weight: 300;
}
.pkg-features {
    list-style: none;
    margin-bottom: 18px;
}
.pkg-features li {
    font-size: 12.5px;
    color: rgba(255,255,255,0.5);
    padding: 4px 0;
    display: flex;
    align-items: flex-start;
    gap: 8px;
    line-height: 1.4;
}
.pkg-features li i { color: #4ade80; font-size: 14px; margin-top: 1px; flex-shrink: 0; }

.btn-book {
    display: block;
    width: 100%;
    text-align: center;
    background: var(--gold);
    color: var(--obsidian);
    padding: 12px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 700;
    font-size: 13px;
    letter-spacing: 0.3px;
    transition: all 0.2s;
    position: relative;
    overflow: hidden;
}
.btn-book::before {
    content: '';
    position: absolute;
    top: 0; left: -100%;
    width: 100%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.45s;
}
.btn-book:hover::before { left: 100%; }
.btn-book:hover { background: var(--gold-lt); transform: translateY(-1px); }
.btn-book.login-btn {
    background: rgba(255,255,255,0.08);
    color: rgba(255,255,255,0.5);
    border: 1px solid var(--border);
}
.btn-book.login-btn:hover { background: rgba(255,255,255,0.12); color: #fff; }

/* No packages state */
.no-packages {
    text-align: center;
    padding: 28px 16px;
    background: var(--surface2);
    border-radius: 10px;
    border: 1px dashed rgba(255,255,255,0.1);
}
.no-packages i { font-size: 30px; color: rgba(255,255,255,0.12); margin-bottom: 8px; display: block; }
.no-packages p { font-size: 13px; color: rgba(255,255,255,0.3); }

/* Trust badge */
.trust-badge {
    margin-top: 16px;
    padding: 13px 16px;
    background: rgba(74,222,128,0.06);
    border: 1px solid rgba(74,222,128,0.15);
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 12px;
    color: rgba(74,222,128,0.8);
    line-height: 1.4;
}
.trust-badge i { font-size: 22px; flex-shrink: 0; }

/* Contact bar */
.contact-bar {
    margin-top: 14px;
    padding: 14px 16px;
    background: var(--surface2);
    border: 1px solid var(--border);
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.contact-row {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 12.5px;
    color: rgba(255,255,255,0.5);
}
.contact-row i { font-size: 15px; color: var(--gold); flex-shrink: 0; }
.contact-row a { color: var(--gold-lt); text-decoration: none; }
.contact-row a:hover { text-decoration: underline; }

/* ── RESPONSIVE ── */
@media (max-width: 1024px) {
    .detail-wrap { grid-template-columns: 1fr; }
    .sidebar-sticky { position: static; }
}
@media (max-width: 640px) {
    .detail-hero { height: 360px; }
    .detail-wrap { padding: 24px 16px 80px; gap: 20px; }
    .content-card { padding: 20px; }
    .gallery-grid { grid-template-columns: repeat(2, 1fr); }
    .highlights-grid { grid-template-columns: 1fr 1fr; }
}
</style>

<!-- BACK LINK -->
<a href="events_list.php" class="back-link" style="display:inline-flex;padding:20px 24px 0;max-width:1240px;margin:0 auto;">
    <i class="ti ti-arrow-left" style="font-size:15px;"></i> Back to Events
</a>

<!-- HERO -->
<div class="detail-hero">
    <img src="<?php echo htmlspecialchars($img); ?>"
         class="detail-hero-img" alt="<?php echo htmlspecialchars($event['name']); ?>"
         onerror="this.src='https://images.unsplash.com/photo-1511578314322-379afb476865?q=80&w=1400'">
    <div class="detail-hero-overlay"></div>
    <div class="detail-hero-content">
        <div class="detail-hero-inner">
            <div class="detail-cat-tag">
                <i class="ti ti-tag"></i>
                <?php echo htmlspecialchars($event['category']); ?>
            </div>
            <h1 class="detail-title"><?php echo htmlspecialchars($event['name']); ?></h1>
            <div class="detail-rating-row">
                <?php if($avg_r > 0): ?>
                <div class="stars">
                    <?php for($s=1;$s<=5;$s++): ?>
                        <?php if($s <= round($avg_r)): ?>
                            <i class="ti ti-star star-full"></i>
                        <?php else: ?>
                            <i class="ti ti-star star-empty"></i>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
                <span class="rating-val"><?php echo number_format($avg_r,1); ?> / 5.0</span>
                <span class="rating-count"><?php echo $rev_cnt; ?> review<?php echo $rev_cnt!=1?'s':''; ?></span>
                <?php else: ?>
                <span class="rating-count"><i class="ti ti-star" style="font-size:13px;margin-right:4px;"></i> New event — no reviews yet</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- MAIN LAYOUT -->
<div class="detail-wrap">

    <!-- LEFT COLUMN -->
    <div class="main-col">

        <!-- About -->
        <div class="content-card">
            <h3 class="card-heading"><i class="ti ti-info-circle"></i> About this event</h3>
            <p class="about-text"><?php echo htmlspecialchars($event['description']); ?></p>
        </div>

        <!-- Highlights -->
        <div class="content-card">
            <h3 class="card-heading"><i class="ti ti-list-check"></i> Event highlights</h3>
            <div class="highlights-grid">
                <?php if(!empty($event['date'])): ?>
                <div class="highlight-item">
                    <i class="ti ti-calendar"></i>
                    <div class="highlight-text">
                        <div class="highlight-label">Date</div>
                        <?php echo htmlspecialchars($event['date']); ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if(!empty($event['location'])): ?>
                <div class="highlight-item">
                    <i class="ti ti-map-pin"></i>
                    <div class="highlight-text">
                        <div class="highlight-label">Location</div>
                        <?php echo htmlspecialchars($event['location']); ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if(!empty($event['capacity'])): ?>
                <div class="highlight-item">
                    <i class="ti ti-users"></i>
                    <div class="highlight-text">
                        <div class="highlight-label">Capacity</div>
                        <?php echo htmlspecialchars($event['capacity']); ?> guests
                    </div>
                </div>
                <?php endif; ?>
                <?php if(!empty($event['duration'])): ?>
                <div class="highlight-item">
                    <i class="ti ti-clock"></i>
                    <div class="highlight-text">
                        <div class="highlight-label">Duration</div>
                        <?php echo htmlspecialchars($event['duration']); ?>
                    </div>
                </div>
                <?php endif; ?>
                <div class="highlight-item">
                    <i class="ti ti-category"></i>
                    <div class="highlight-text">
                        <div class="highlight-label">Category</div>
                        <?php echo htmlspecialchars($event['category']); ?>
                    </div>
                </div>
                <div class="highlight-item">
                    <i class="ti ti-shield-check"></i>
                    <div class="highlight-text">
                        <div class="highlight-label">Booking</div>
                        Verified &amp; Secure
                    </div>
                </div>
            </div>
        </div>

        <!-- Gallery -->
        <?php if($gal_res && mysqli_num_rows($gal_res) > 0): ?>
        <div class="content-card">
            <h3 class="card-heading"><i class="ti ti-photo"></i> Event gallery</h3>
            <div class="gallery-grid">
                <?php while($g = mysqli_fetch_assoc($gal_res)): ?>
                    <img src="../../uploads/gallery/<?php echo htmlspecialchars($g['image']); ?>"
                         alt="Gallery photo"
                         onerror="this.style.display='none'">
                <?php endwhile; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Reviews -->
        <div class="content-card">
            <h3 class="card-heading"><i class="ti ti-message-circle-2"></i> Client testimonials</h3>
            <?php if($rev_res && mysqli_num_rows($rev_res) > 0):
                while($rv = mysqli_fetch_assoc($rev_res)):
                    $initials = strtoupper(substr($rv['uname'],0,1));
                    $created  = isset($rv['created_at']) ? date('M j, Y', strtotime($rv['created_at'])) : '';
            ?>
            <div class="review-item">
                <div class="review-header">
                    <div class="reviewer-info">
                        <div class="reviewer-avatar"><?php echo $initials; ?></div>
                        <div>
                            <div class="reviewer-name"><?php echo htmlspecialchars($rv['uname']); ?></div>
                            <?php if($created): ?>
                            <div class="reviewer-date"><?php echo $created; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="review-stars">
                        <?php for($s=1;$s<=5;$s++): ?>
                            <?php if($s<=(int)$rv['rating']): ?>
                                <i class="ti ti-star star-full"></i>
                            <?php else: ?>
                                <i class="ti ti-star star-empty"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                </div>
                <p class="review-text">"<?php echo htmlspecialchars($rv['comment']); ?>"</p>
            </div>
            <?php endwhile;
            else: ?>
            <div class="no-reviews">
                <i class="ti ti-message-off"></i>
                <p>No reviews yet. Share your experience after booking!</p>
            </div>
            <?php endif; ?>
        </div>

    </div><!-- /main-col -->

    <!-- RIGHT SIDEBAR -->
    <div class="sidebar-sticky">
        <div class="sidebar-card">
            <h3 class="sidebar-title"><i class="ti ti-packages"></i> Select a package</h3>

            <?php
            $pkgs = mysqli_query($conn, "SELECT * FROM packages WHERE event_id=$event_id ORDER BY price ASC");
            if($pkgs && mysqli_num_rows($pkgs) > 0):
                $pi = 0;
                while($pkg = mysqli_fetch_assoc($pkgs)):
                    $is_featured = ($pi == 1); // middle package = featured
            ?>
            <div class="pkg-box <?php echo $is_featured ? 'featured' : ''; ?>">
                <?php if($is_featured): ?>
                <span class="pkg-popular">Most Popular</span>
                <?php endif; ?>
                <div class="pkg-name"><?php echo htmlspecialchars($pkg['name']); ?></div>
                <div class="pkg-price">
                    ৳ <?php echo number_format($pkg['price']); ?>
                    <span>BDT</span>
                </div>
                <ul class="pkg-features">
                    <?php
                    $services = array_filter(array_map('trim', explode(',', $pkg['included_services'])));
                    foreach($services as $svc): ?>
                    <li><i class="ti ti-circle-check"></i> <span><?php echo htmlspecialchars($svc); ?></span></li>
                    <?php endforeach; ?>
                </ul>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="booking_form.php?package_id=<?php echo $pkg['id']; ?>" class="btn-book">
                        <i class="ti ti-calendar-check" style="font-size:14px;margin-right:5px;"></i> Select &amp; Book
                    </a>
                <?php else: ?>
                    <a href="../auth/login.php" class="btn-book login-btn">
                        <i class="ti ti-lock" style="font-size:14px;margin-right:5px;"></i> Login to Book
                    </a>
                <?php endif; ?>
            </div>
            <?php
                    $pi++;
                endwhile;
            else: ?>
            <div class="no-packages">
                <i class="ti ti-box-off"></i>
                <p>No packages available for this event currently.</p>
            </div>
            <?php endif; ?>

            <!-- Trust badge -->
            <div class="trust-badge">
                <i class="ti ti-shield-check"></i>
                <span>100% secure booking &amp; verified professional vendors.</span>
            </div>

            <!-- Contact info -->
            <div class="contact-bar">
                <div class="contact-row">
                    <i class="ti ti-phone"></i>
                    <span>Need help? <a href="tel:+8801700000000">+880 17 0000 0000</a></span>
                </div>
                <div class="contact-row">
                    <i class="ti ti-mail"></i>
                    <span><a href="mailto:info@atroyalevents.com">info@atroyalevents.com</a></span>
                </div>
                <div class="contact-row">
                    <i class="ti ti-clock"></i>
                    <span>Sat–Thu, 9 AM – 9 PM</span>
                </div>
            </div>

        </div>
    </div><!-- /sidebar -->

</div><!-- /detail-wrap -->

<?php include('../../includes/footer.php'); ?>

<script>
/* Slow-zoom already via CSS animation on the hero image */
/* Reveal on scroll for content cards */
(function(){
    const obs = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if(e.isIntersecting){
                e.target.style.opacity = '1';
                e.target.style.transform = 'translateY(0)';
                obs.unobserve(e.target);
            }
        });
    }, { threshold: 0.06 });

    document.querySelectorAll('.content-card, .sidebar-card').forEach((el, i) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = `opacity 0.5s ease ${i*80}ms, transform 0.5s ease ${i*80}ms`;
        obs.observe(el);
    });
})();
</script>