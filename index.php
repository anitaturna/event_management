<?php 
include('config/db_connect.php'); 
include('includes/header.php'); 
include('includes/navbar.php'); 
?>

<!-- Premium Fonts & Icons -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

<style>
    :root {
        --primary: #2563eb;
        --primary-hover: #1d4ed8;
        --dark: #0f172a;
        --dark-light: #1e293b;
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

    h1, h2, h3 {
        font-family: 'Playfair Display', serif;
        color: var(--dark);
    }

    /* --- New Carousel Styles --- */
    .carousel-container {
        position: relative;
        width: 100%;
        height: 550px;
        border-radius: 24px;
        overflow: hidden;
        margin: 40px 0 60px;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.15);
    }

    .carousel-slide {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background-size: cover;
        background-position: center;
        opacity: 0;
        transition: opacity 1s ease-in-out;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: var(--white);
        z-index: 0;
    }

    .carousel-slide.active {
        opacity: 1;
        z-index: 1;
    }

    .carousel-content {
        max-width: 800px;
        padding: 0 20px;
        transform: translateY(30px);
        opacity: 0;
        transition: all 0.8s ease 0.3s; /* Delays text animation slightly after slide fades in */
    }

    .carousel-slide.active .carousel-content {
        transform: translateY(0);
        opacity: 1;
    }

    .hero-title {
        font-size: clamp(2.5rem, 5vw, 4.5rem);
        font-weight: 700;
        margin-bottom: 20px;
        line-height: 1.1;
        color: #ffffff !important; /* লেখাটিকে জোরপূর্বক সাদা করবে */
        text-shadow: 0 4px 15px rgba(0, 0, 0, 0.8); /* ডার্ক শ্যাডো, যেন ইমেজে লেখা না হারায় */
    }

    .hero-subtitle {
        font-size: 1.15rem;
        color: #f8fafc !important; /* উজ্জ্বল সাদাটে কালার */
        max-width: 650px;
        margin: 0 auto 35px;
        font-weight: 300;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.9);
    }

    .btn-primary {
        background: var(--primary);
        color: var(--white);
        padding: 16px 40px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-block;
        font-size: 1.1rem;
        border: 1px solid var(--primary);
    }

    .btn-primary:hover {
        background: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
    }

    .carousel-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        width: 50px; height: 50px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px;
        cursor: pointer;
        z-index: 5;
        transition: 0.3s;
    }

    .carousel-btn:hover { background: var(--primary); border-color: var(--primary); }
    .carousel-btn.prev { left: 20px; }
    .carousel-btn.next { right: 20px; }

    /* Section Headers */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 40px;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 15px;
    }

    .section-title { font-size: 2rem; font-weight: 700; margin: 0; }
    .view-all { color: var(--primary); text-decoration: none; font-weight: 600; font-size: 0.95rem; display: flex; align-items: center; gap: 5px; transition: 0.2s; }
    .view-all:hover { color: var(--dark); gap: 8px; }

    /* Category Cards */
    .category-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 80px; }
    .category-card { background: var(--white); padding: 35px 20px; text-align: center; border-radius: 16px; text-decoration: none; color: var(--dark); border: 1px solid #e2e8f0; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .category-card:hover { border-color: var(--primary); transform: translateY(-8px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05); }
    .cat-icon { width: 60px; height: 60px; background: var(--light); color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 28px; transition: 0.3s; }
    .category-card:hover .cat-icon { background: var(--primary); color: var(--white); }

    /* Event Cards */
    .event-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px; margin-bottom: 100px; }
    .event-card { background: var(--white); border-radius: 20px; overflow: hidden; border: 1px solid #e2e8f0; transition: all 0.3s ease; display: flex; flex-direction: column; }
    .event-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08); }
    .event-img-wrap { width: 100%; height: 240px; overflow: hidden; position: relative; }
    .event-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; }
    .event-card:hover .event-img { transform: scale(1.08); }
    .event-badge { position: absolute; top: 20px; left: 20px; background: rgba(255,255,255,0.95); color: var(--primary); padding: 6px 14px; border-radius: 30px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
    .event-content { padding: 30px; flex-grow: 1; display: flex; flex-direction: column; }
    .event-title { font-size: 1.4rem; margin: 0 0 10px; line-height: 1.3; }
    .event-desc { color: var(--gray); font-size: 0.95rem; margin-bottom: 25px; flex-grow: 1; }
    .btn-outline { display: block; text-align: center; background: transparent; color: var(--dark); border: 1px solid #e2e8f0; padding: 12px; border-radius: 10px; text-decoration: none; font-weight: 600; transition: 0.3s; }
    .event-card:hover .btn-outline { background: var(--dark); color: var(--white); border-color: var(--dark); }

    @media (max-width: 768px) {
        .carousel-container { height: 400px; padding: 0; margin: 20px 0; border-radius: 16px; }
        .hero-title { font-size: 2rem; }
        .section-header { flex-direction: column; align-items: flex-start; gap: 15px; }
    }
</style>

<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
    
    <!-- Hero Carousel Section -->
    <header class="carousel-container">
        
        <!-- Slide 1: Main Event -->
        <div class="carousel-slide active" style="background-image: linear-gradient(rgba(15, 23, 42, 0.6), rgba(15, 23, 42, 0.7)), url('https://images.unsplash.com/photo-1511578314322-379afb476865?q=80&w=1600');">
            <div class="carousel-content">
                <h1 class="hero-title">Curating Unforgettable Experiences</h1>
                <p class="hero-subtitle">Seamlessly planning weddings, corporate galas, and private celebrations with uncompromising excellence and attention to detail.</p>
                <a href="#featured" class="btn-primary">Explore Our Portfolio</a>
            </div>
        </div>

        <!-- Slide 2: Wedding -->
        <div class="carousel-slide" style="background-image: linear-gradient(rgba(15, 23, 42, 0.5), rgba(15, 23, 42, 0.7)), url('https://images.unsplash.com/photo-1519225421980-715cb0215aed?q=80&w=1600');">
            <div class="carousel-content">
                <h1 class="hero-title">Your Dream Wedding Awaits</h1>
                <p class="hero-subtitle">Turn your special day into a timeless memory with our bespoke wedding planning services.</p>
                <a href="views/user/events_list.php?category=wedding" class="btn-primary" style="background: transparent; backdrop-filter: blur(5px);">View Weddings</a>
            </div>
        </div>

        <!-- Slide 3: Corporate -->
        <div class="carousel-slide" style="background-image: linear-gradient(rgba(15, 23, 42, 0.6), rgba(15, 23, 42, 0.8)), url('https://images.unsplash.com/photo-1505373877841-8d25f7d46678?q=80&w=1600');">
            <div class="carousel-content">
                <h1 class="hero-title">Elevate Your Corporate Galas</h1>
                <p class="hero-subtitle">Professional end-to-end event management tailored to enhance your brand's prestige.</p>
                <a href="views/user/events_list.php?category=corporate" class="btn-primary" style="background: var(--white); color: var(--dark); border-color: var(--white);">Corporate Packages</a>
            </div>
        </div>

        <!-- Carousel Navigation Controls -->
        <button class="carousel-btn prev" onclick="moveSlide(-1)"><i class="ti ti-chevron-left"></i></button>
        <button class="carousel-btn next" onclick="moveSlide(1)"><i class="ti ti-chevron-right"></i></button>
    </header>

    <!-- Categories Section -->
    <section>
        <div class="section-header">
            <h2 class="section-title">Specialized Services</h2>
            <span style="color: var(--gray); font-size: 0.95rem;">Tailored to your vision</span>
        </div>
        
        <div class="category-grid">
            <?php 
            $sql = "SELECT DISTINCT category FROM events WHERE status='active'";
            $res = mysqli_query($conn, $sql);
            
            $iconMap = [
                'wedding' => 'ti-rings',
                'corporate' => 'ti-briefcase',
                'birthday' => 'ti-cake',
                'private' => 'ti-glass-full',
                'festival' => 'ti-confetti'
            ];

            if($res && mysqli_num_rows($res) > 0) {
                while($row = mysqli_fetch_assoc($res)) {
                    $cat = htmlspecialchars($row['category']);
                    $iconClass = $iconMap[strtolower($cat)] ?? 'ti-star';
                    
                    echo "
                    <a href='views/user/events_list.php?category={$cat}' class='category-card'>
                        <div class='cat-icon'><i class='ti {$iconClass}'></i></div>
                        <h3 style='margin:0; font-size: 1.1rem; font-family: Inter, sans-serif;'>".ucfirst($cat)."</h3>
                    </a>";
                }
            }
            ?>
        </div>
    </section>

    <!-- Featured Events Section -->
    <section id="featured">
        <div class="section-header">
            <h2 class="section-title">Featured Portfolio</h2>
            <a href="views/user/events_list.php" class="view-all">View Full Gallery <i class="ti ti-arrow-right"></i></a>
        </div>

        <div class="event-grid">
            <?php 
            $sql2 = "SELECT * FROM events WHERE status='active' ORDER BY id DESC LIMIT 6";
            $res2 = mysqli_query($conn, $sql2);
            
            if($res2 && mysqli_num_rows($res2) > 0) {
                while($event = mysqli_fetch_assoc($res2)) { 
                    $img = (!empty($event['image'])) ? "uploads/events/".$event['image'] : "https://images.unsplash.com/photo-1511578314322-379afb476865?q=80&w=600";
            ?>
                <div class="event-card">
                    <div class="event-img-wrap">
                        <span class="event-badge"><?php echo htmlspecialchars($event['category']); ?></span>
                        <img src="<?php echo $img; ?>" class="event-img" alt="<?php echo htmlspecialchars($event['name']); ?>" onerror="this.src='https://images.unsplash.com/photo-1511578314322-379afb476865?q=80&w=600'">
                    </div>
                    <div class="event-content">
                        <h3 class="event-title"><?php echo htmlspecialchars($event['name']); ?></h3>
                        <p class="event-desc">
                            <?php echo htmlspecialchars(substr($event['description'], 0, 95)); ?>...
                        </p>
                        <a href="views/user/event_details.php?id=<?php echo $event['id']; ?>" class="btn-outline">View Details</a>
                    </div>
                </div>
            <?php 
                } 
            } else {
                echo "<p style='grid-column: 1/-1; text-align: center; color: var(--gray); padding: 40px;'>No events are currently featured. Check back soon!</p>";
            }
            ?>
        </div>
    </section>
</div>

<!-- Carousel JavaScript -->
<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.carousel-slide');
    let slideInterval;

    function showSlide(index) {
        slides.forEach(slide => slide.classList.remove('active'));
        
        // Ensure index wraps around properly
        if(index >= slides.length) currentSlide = 0;
        else if(index < 0) currentSlide = slides.length - 1;
        else currentSlide = index;
        
        slides[currentSlide].classList.add('active');
    }

    function moveSlide(direction) {
        showSlide(currentSlide + direction);
        resetInterval(); // Reset timer when user clicks manually
    }

    function resetInterval() {
        clearInterval(slideInterval);
        slideInterval = setInterval(() => { moveSlide(1); }, 6000); // Auto-slide every 6 seconds
    }

    // Start auto-slider
    resetInterval();
</script>

<?php include('includes/footer.php'); ?>