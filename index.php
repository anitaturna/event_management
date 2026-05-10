<?php 
include('config/db_connect.php'); 
include('includes/header.php'); 
include('includes/navbar.php'); 
?>

<!-- Premium Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

<style>
    :root {
        --primary: #2563eb;
        --primary-hover: #1d4ed8;
        --dark: #0f172a;
        --gray: #64748b;
        --light: #f8fafc;
        --white: #ffffff;
    }

    body { 
        font-family: 'Inter', sans-serif; 
        background-color: var(--light); 
        color: var(--dark); 
        margin: 0;
        line-height: 1.6;
    }

    h1, h2, h3 { font-family: 'Playfair Display', serif; }

    /* --- Full Width Carousel Implementation --- */
    .hero-carousel {
        width: 100%;
        height: 85vh;
        position: relative;
        overflow: hidden;
        background: #000;
        margin-bottom: 60px;
    }

    .carousel-inner {
        width: 100%;
        height: 100%;
        position: relative;
    }

    .carousel-item {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        visibility: hidden;
        transition: opacity 1.2s cubic-bezier(0.4, 0, 0.2, 1), visibility 1.2s;
        z-index: 1;
    }

    .carousel-item.active {
        opacity: 1;
        visibility: visible;
        z-index: 2;
    }

    .carousel-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(0.55); /* Darkened for text readability */
    }

    .hero-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -40%);
        text-align: center;
        color: var(--white);
        width: 90%;
        max-width: 900px;
        z-index: 10;
        transition: all 0.8s ease 0.4s;
        opacity: 0;
    }

    .carousel-item.active .hero-overlay {
        transform: translate(-50%, -50%);
        opacity: 1;
    }

    .hero-overlay h1 {
        font-size: clamp(2.5rem, 6vw, 4.8rem);
        font-weight: 700;
        margin-bottom: 20px;
        line-height: 1.1;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .hero-overlay p {
        font-size: 1.25rem;
        margin-bottom: 35px;
        font-weight: 300;
        color: #e2e8f0;
    }

    /* Indicators (Dots) */
    .carousel-dots {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 12px;
        z-index: 20;
    }

    .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        cursor: pointer;
        transition: 0.4s;
    }

    .dot.active {
        background: var(--primary);
        width: 35px;
        border-radius: 10px;
    }

    /* --- Sections Styling --- */
    .section-container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 40px;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 15px;
    }

    .section-title { font-size: 2.2rem; font-weight: 700; margin: 0; }

    /* Category Cards */
    .category-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 80px; }
    .category-card { 
        background: var(--white); padding: 40px 20px; text-align: center; border-radius: 16px; 
        text-decoration: none; color: var(--dark); border: 1px solid #e2e8f0; transition: 0.3s;
    }
    .category-card:hover { transform: translateY(-5px); border-color: var(--primary); box-shadow: 0 15px 30px rgba(0,0,0,0.05); }
    .cat-icon { font-size: 32px; color: var(--primary); margin-bottom: 15px; display: block; }

    /* Event Cards */
    .event-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px; margin-bottom: 100px; }
    .event-card { background: var(--white); border-radius: 20px; overflow: hidden; border: 1px solid #e2e8f0; transition: 0.3s; display: flex; flex-direction: column; }
    .event-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08); }
    .event-img-wrap { width: 100%; height: 250px; overflow: hidden; position: relative; }
    .event-img { width: 100%; height: 100%; object-fit: cover; transition: 0.6s; }
    .event-card:hover .event-img { transform: scale(1.1); }
    .event-badge { position: absolute; top: 20px; left: 20px; background: var(--white); color: var(--primary); padding: 6px 14px; border-radius: 30px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; }
    .event-content { padding: 30px; flex-grow: 1; }
    .btn-outline { display: block; text-align: center; border: 1px solid #e2e8f0; padding: 12px; border-radius: 12px; text-decoration: none; color: var(--dark); font-weight: 600; transition: 0.3s; margin-top: 20px; }
    .event-card:hover .btn-outline { background: var(--dark); color: var(--white); border-color: var(--dark); }

    .btn-primary-hero {
        background: var(--primary); color: white; padding: 16px 45px; border-radius: 50px; text-decoration: none; font-weight: 600; display: inline-block; transition: 0.3s;
    }
    .btn-primary-hero:hover { background: var(--primary-hover); transform: scale(1.05); }

    @media (max-width: 768px) {
        .hero-carousel { height: 60vh; }
        .hero-overlay h1 { font-size: 2.2rem; }
    }
</style>

<!-- Full-Width Hero Carousel -->
<header class="hero-carousel">
    <div class="carousel-inner">
        <!-- Slide 1 -->
        <div class="carousel-item active">
            <img src="https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&w=1600&q=80" alt="Luxury Events">
            <div class="hero-overlay">
                <h1>AT ROYAL EVENTS</h1>
                <p>Curating premium experiences for life's most majestic moments.</p>
                <a href="#featured" class="btn-primary-hero">View Portfolio</a>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-item">
            <img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=1600&q=80" alt="Royal Weddings">
            <div class="hero-overlay">
                <h1>Grand Weddings</h1>
                <p>Luxury defined in every detail of your special day.</p>
                <a href="views/user/events_list.php?category=Wedding" class="btn-primary-hero">Wedding Tiers</a>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="carousel-item">
            <img src="https://images.unsplash.com/photo-1505373877841-8d25f7d46678?auto=format&fit=crop&w=1600&q=80" alt="Corporate Galas">
            <div class="hero-overlay">
                <h1>Corporate Excellence</h1>
                <p>Bespoke event management for grand brand success.</p>
                <a href="views/user/events_list.php?category=Corporate" class="btn-primary-hero">Learn More</a>
            </div>
        </div>
    </div>

    <!-- Indicator Dots -->
    <div class="carousel-dots">
        <div class="dot active" onclick="jumpToSlide(0)"></div>
        <div class="dot" onclick="jumpToSlide(1)"></div>
        <div class="dot" onclick="jumpToSlide(2)"></div>
    </div>
</header>

<div class="section-container">
    <!-- Categories Section -->
    <section>
        <div class="section-header">
            <h2 class="section-title">Specialized Services</h2>
            <span style="color: var(--gray); font-size: 0.95rem;">Royal touch for every occasion</span>
        </div>
        
        <div class="category-grid">
            <?php 
            $sql = "SELECT DISTINCT category FROM events WHERE status='active'";
            $res = mysqli_query($conn, $sql);
            $iconMap = ['wedding'=>'ti-rings', 'corporate'=>'ti-briefcase', 'birthday'=>'ti-cake', 'festival'=>'ti-confetti'];

            if($res && mysqli_num_rows($res) > 0) {
                while($row = mysqli_fetch_assoc($res)) {
                    $cat = htmlspecialchars($row['category']);
                    $icon = $iconMap[strtolower($cat)] ?? 'ti-star';
                    echo "
                    <a href='views/user/events_list.php?category={$cat}' class='category-card'>
                        <i class='ti {$icon} cat-icon'></i>
                        <h3 style='margin:0; font-size: 1.1rem; font-family: Inter;'>".ucfirst($cat)."</h3>
                    </a>";
                }
            }
            ?>
        </div>
    </section>

    <!-- Featured Portfolio -->
    <section id="featured">
        <div class="section-header">
            <h2 class="section-title">Latest Masterpieces</h2>
            <a href="views/user/events_list.php" style="color:var(--primary); text-decoration:none; font-weight:600;">View All Gallery</a>
        </div>

        <div class="event-grid">
            <?php 
            $sql2 = "SELECT * FROM events WHERE status='active' ORDER BY id DESC LIMIT 6";
            $res2 = mysqli_query($conn, $sql2);
            if($res2 && mysqli_num_rows($res2) > 0) {
                while($event = mysqli_fetch_assoc($res2)) { 
                    $img = (!empty($event['image'])) ? "uploads/events/".$event['image'] : "https://via.placeholder.com/600x400";
            ?>
                <div class="event-card">
                    <div class="event-img-wrap">
                        <span class="event-badge"><?php echo htmlspecialchars($event['category']); ?></span>
                        <img src="<?php echo $img; ?>" class="event-img" alt="Event">
                    </div>
                    <div class="event-content">
                        <h3 style="font-size:1.3rem; margin:0;"><?php echo htmlspecialchars($event['name']); ?></h3>
                        <p style="color:var(--gray); font-size:0.9rem; margin-top:10px;">
                            <?php echo htmlspecialchars(substr($event['description'], 0, 90)); ?>...
                        </p>
                        <a href="views/user/event_details.php?id=<?php echo $event['id']; ?>" class="btn-outline">Explore Event</a>
                    </div>
                </div>
            <?php 
                } 
            }
            ?>
        </div>
    </section>
</div>

<!-- Carousel Logic -->
<script>
    let current = 0;
    const slides = document.querySelectorAll('.carousel-item');
    const dots = document.querySelectorAll('.dot');

    function showSlide(index) {
        slides.forEach(s => s.classList.remove('active'));
        dots.forEach(d => d.classList.remove('active'));
        
        if(index >= slides.length) current = 0;
        else if(index < 0) current = slides.length - 1;
        else current = index;

        slides[current].classList.add('active');
        dots[current].classList.add('active');
    }

    function jumpToSlide(index) {
        showSlide(index);
    }

    // Auto-advance
    setInterval(() => {
        showSlide(current + 1);
    }, 5000);
</script>

<?php include('includes/footer.php'); ?>