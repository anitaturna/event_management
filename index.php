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

    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, var(--dark) 0%, var(--dark-light) 100%);
        color: var(--white);
        border-radius: 24px;
        padding: 100px 40px;
        text-align: center;
        margin: 40px 0 60px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.15);
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%; left: -50%; width: 200%; height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 60%);
        pointer-events: none;
    }

    .hero-title {
        font-size: clamp(2.5rem, 5vw, 4rem);
        font-weight: 700;
        margin-bottom: 20px;
        line-height: 1.1;
    }

    .hero-subtitle {
        font-size: 1.125rem;
        color: #94a3b8;
        max-width: 600px;
        margin: 0 auto 40px;
        font-weight: 300;
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
    }

    .btn-primary:hover {
        background: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
    }

    /* Section Headers */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 40px;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 15px;
    }

    .section-title {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }

    .view-all {
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: 0.2s;
    }
    .view-all:hover { color: var(--dark); gap: 8px; }

    /* Category Cards */
    .category-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 80px;
    }

    .category-card {
        background: var(--white);
        padding: 35px 20px;
        text-align: center;
        border-radius: 16px;
        text-decoration: none;
        color: var(--dark);
        border: 1px solid #e2e8f0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .category-card:hover {
        border-color: var(--primary);
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
    }

    .cat-icon {
        width: 60px;
        height: 60px;
        background: var(--light);
        color: var(--primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 28px;
        transition: 0.3s;
    }
    .category-card:hover .cat-icon { background: var(--primary); color: var(--white); }

    /* Event Cards */
    .event-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 30px;
        margin-bottom: 100px;
    }

    .event-card {
        background: var(--white);
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .event-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
    }

    .event-img-wrap {
        width: 100%;
        height: 240px;
        overflow: hidden;
        position: relative;
    }

    .event-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .event-card:hover .event-img {
        transform: scale(1.08);
    }

    .event-badge {
        position: absolute;
        top: 20px; left: 20px;
        background: rgba(255,255,255,0.95);
        color: var(--primary);
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .event-content {
        padding: 30px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .event-title {
        font-size: 1.4rem;
        margin: 0 0 10px;
        line-height: 1.3;
    }

    .event-desc {
        color: var(--gray);
        font-size: 0.95rem;
        margin-bottom: 25px;
        flex-grow: 1;
    }

    .btn-outline {
        display: block;
        text-align: center;
        background: transparent;
        color: var(--dark);
        border: 1px solid #e2e8f0;
        padding: 12px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: 0.3s;
    }

    .event-card:hover .btn-outline {
        background: var(--dark);
        color: var(--white);
        border-color: var(--dark);
    }

    @media (max-width: 768px) {
        .hero-section { padding: 60px 20px; margin: 20px 0; border-radius: 16px; }
        .section-header { flex-direction: column; align-items: flex-start; gap: 15px; }
    }
</style>

<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
    
    <!-- Hero Section -->
    <header class="hero-section">
        <h1 class="hero-title">Curating Unforgettable Experiences</h1>
        <p class="hero-subtitle">Seamlessly planning weddings, corporate galas, and private celebrations with uncompromising excellence and attention to detail.</p>
        <a href="#featured" class="btn-primary">Explore Our Portfolio</a>
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
            
            // Map common categories to elegant icons
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
                    $iconClass = $iconMap[strtolower($cat)] ?? 'ti-star'; // Default star icon
                    
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

<?php include('includes/footer.php'); ?>