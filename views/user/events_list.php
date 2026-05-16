<?php 
include('../../config/db_connect.php'); 
include('../../includes/header.php'); 
include('../../includes/navbar.php'); 

$category_filter = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
$where = "WHERE status='active'";
if($category_filter) $where .= " AND category='$category_filter'";

$total_res = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM events WHERE status='active'");
$total_events = mysqli_fetch_assoc($total_res)['cnt'];
?>

<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

<style>
:root {
    --gold:     #c9a84c;
    --gold-lt:  #f0d48a;
    --gold-dk:  #8a6d2f;
    --obsidian: #0a0a0f;
    --charcoal: #12121a;
    --surface:  #16161f;
    --mist:     #f4f2ee;
    --fog:      #6b6b7a;
    --border:   rgba(255,255,255,0.06);
    --border-lt:rgba(0,0,0,0.07);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: 'Outfit', sans-serif;
    background: var(--obsidian);
    color: rgba(255,255,255,0.85);
    -webkit-font-smoothing: antialiased;
}

/* ── HERO ── */
.list-hero {
    background: var(--obsidian);
    padding: 72px 24px 0;
    position: relative;
    overflow: hidden;
}
.list-hero::before {
    content: '';
    position: absolute;
    top: -120px; left: 50%;
    transform: translateX(-50%);
    width: 700px; height: 300px;
    background: radial-gradient(ellipse, rgba(201,168,76,0.10) 0%, transparent 65%);
    pointer-events: none;
}
/* diagonal gold accent line */
.list-hero::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent 0%, var(--gold) 50%, transparent 100%);
    opacity: 0.35;
}
.hero-inner {
    max-width: 1240px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 6px;
    padding-bottom: 52px;
    position: relative;
    z-index: 2;
}
.hero-eyebrow {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 4px;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 6px;
}
.hero-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(2.4rem, 5vw, 3.6rem);
    font-weight: 700;
    color: #fff;
    line-height: 1.05;
}
.hero-title em {
    font-style: italic;
    color: var(--gold-lt);
}
.hero-sub {
    font-size: 14px;
    color: rgba(255,255,255,0.35);
    font-weight: 300;
    margin-top: 6px;
    letter-spacing: 0.2px;
}
.result-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(201,168,76,0.08);
    border: 1px solid rgba(201,168,76,0.22);
    border-radius: 100px;
    padding: 6px 18px;
    font-size: 12px;
    font-weight: 600;
    color: var(--gold-lt);
    margin-top: 14px;
}
.result-pill i { font-size: 14px; }

/* ── CONTROLS BAR ── */
.controls-bar {
    position: sticky;
    top: 58px; /* matches updated navbar height */
    z-index: 200;
    background: rgba(10,10,15,0.96);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border-bottom: 1px solid var(--border);
    padding: 12px 24px;
}
.controls-bar::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(201,168,76,0.3), transparent);
}
.controls-inner {
    max-width: 1240px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    flex-wrap: wrap;
}
.filter-tabs {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}
.tab {
    padding: 6px 16px;
    border-radius: 100px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid rgba(255,255,255,0.1);
    color: rgba(255,255,255,0.5);
    background: transparent;
    transition: all 0.2s;
    letter-spacing: 0.2px;
    display: flex;
    align-items: center;
    gap: 5px;
    white-space: nowrap;
}
.tab i { font-size: 13px; }
.tab:hover {
    border-color: rgba(201,168,76,0.5);
    color: var(--gold-lt);
    background: rgba(201,168,76,0.06);
}
.tab.active {
    background: var(--gold);
    color: var(--obsidian);
    border-color: var(--gold);
    font-weight: 700;
}
.tab .tab-count {
    background: rgba(0,0,0,0.15);
    font-size: 10px;
    font-weight: 700;
    padding: 1px 6px;
    border-radius: 100px;
}
.tab.active .tab-count { background: rgba(0,0,0,0.2); }

.search-wrap {
    position: relative;
    flex-shrink: 0;
}
.search-wrap i.search-icon {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 15px;
    color: rgba(255,255,255,0.3);
    pointer-events: none;
}
.search-input {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 100px;
    padding: 7px 16px 7px 38px;
    font-size: 13px;
    font-family: 'Outfit', sans-serif;
    color: #fff;
    outline: none;
    width: 200px;
    transition: all 0.25s;
}
.search-input::placeholder { color: rgba(255,255,255,0.25); }
.search-input:focus {
    border-color: rgba(201,168,76,0.5);
    box-shadow: 0 0 0 3px rgba(201,168,76,0.08);
    width: 240px;
    background: rgba(255,255,255,0.07);
}
.sort-select {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 100px;
    padding: 7px 16px;
    font-size: 12px;
    font-family: 'Outfit', sans-serif;
    color: rgba(255,255,255,0.55);
    outline: none;
    cursor: pointer;
    transition: border-color 0.2s;
}
.sort-select:focus { border-color: rgba(201,168,76,0.5); }
.sort-select option { background: #1a1a26; color: #fff; }

/* ── GRID ── */
.page-wrap {
    max-width: 1240px;
    margin: 0 auto;
    padding: 40px 24px 100px;
}
.events-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 24px;
}

/* ── CARD ── */
.e-card {
    background: var(--surface);
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid var(--border);
    transition: transform 0.35s cubic-bezier(0.23,1,0.32,1), border-color 0.35s, box-shadow 0.35s;
    display: flex;
    flex-direction: column;
    position: relative;
}
.e-card:hover {
    transform: translateY(-8px);
    border-color: rgba(201,168,76,0.3);
    box-shadow: 0 24px 48px rgba(0,0,0,0.4), 0 0 0 1px rgba(201,168,76,0.1);
}

.e-img-wrap {
    position: relative;
    height: 220px;
    overflow: hidden;
}
.e-img {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.7s cubic-bezier(0.23,1,0.32,1);
    filter: brightness(0.85);
}
.e-card:hover .e-img { transform: scale(1.07); filter: brightness(0.95); }

.e-img-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(10,10,15,0.9) 0%, rgba(10,10,15,0.2) 50%, transparent 100%);
}

.e-cat {
    position: absolute;
    top: 14px; left: 14px;
    background: rgba(10,10,15,0.75);
    backdrop-filter: blur(8px);
    color: var(--gold-lt);
    padding: 4px 12px;
    border-radius: 100px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    border: 1px solid rgba(201,168,76,0.2);
}

.e-scarcity {
    position: absolute;
    top: 14px; right: 14px;
    background: rgba(185, 28, 28, 0.82);
    backdrop-filter: blur(8px);
    color: #fff;
    padding: 4px 10px;
    border-radius: 100px;
    font-size: 10px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 4px;
    animation: pulse-red 2.5s ease-in-out infinite;
}
@keyframes pulse-red {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.e-wish {
    position: absolute;
    bottom: 14px; right: 14px;
    width: 32px; height: 32px;
    border-radius: 50%;
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(6px);
    border: 1px solid rgba(255,255,255,0.15);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    color: rgba(255,255,255,0.5);
    transition: all 0.2s;
    z-index: 2;
}
.e-wish:hover { color: #f87171; border-color: rgba(248,113,113,0.4); transform: scale(1.1); }
.e-wish.wishlisted { color: #f87171; background: rgba(248,113,113,0.15); }

.e-body {
    padding: 20px 22px 22px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}
.e-meta {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    margin-bottom: 8px;
}
.e-rating {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: 600;
    color: var(--gold);
}
.e-rating i { font-size: 13px; }
.e-reviews { font-weight: 300; color: rgba(255,255,255,0.3); }

.e-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.5rem;
    font-weight: 700;
    color: #fff;
    line-height: 1.2;
    margin-bottom: 8px;
}
.e-desc {
    color: rgba(255,255,255,0.4);
    font-size: 13px;
    line-height: 1.65;
    flex-grow: 1;
    margin-bottom: 16px;
    font-weight: 300;
}

/* Availability bar */
.e-avail { margin-bottom: 16px; }
.e-avail-top {
    display: flex;
    justify-content: space-between;
    font-size: 10.5px;
    font-weight: 600;
    margin-bottom: 5px;
}
.e-avail-label { color: rgba(255,255,255,0.3); }
.e-avail-left { color: #f87171; }
.e-avail-bar {
    height: 2px;
    background: rgba(255,255,255,0.08);
    border-radius: 100px;
    overflow: hidden;
}
.e-avail-fill {
    height: 100%;
    border-radius: 100px;
    background: linear-gradient(90deg, var(--gold-dk), var(--gold));
}

.e-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    border-top: 1px solid rgba(255,255,255,0.06);
    padding-top: 14px;
}
.e-price-label {
    font-size: 9.5px;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: rgba(255,255,255,0.3);
    margin-bottom: 2px;
}
.e-price {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--gold-lt);
}

.e-btn {
    background: var(--gold);
    color: var(--obsidian);
    padding: 9px 18px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.2s;
    letter-spacing: 0.3px;
    white-space: nowrap;
    flex-shrink: 0;
    position: relative;
    overflow: hidden;
}
.e-btn::before {
    content: '';
    position: absolute;
    top: 0; left: -100%;
    width: 100%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.45s;
}
.e-btn:hover::before { left: 100%; }
.e-btn:hover { background: var(--gold-lt); transform: translateY(-1px); }

/* ── EMPTY STATE ── */
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 80px 20px;
}
.empty-state i { font-size: 52px; color: rgba(255,255,255,0.1); margin-bottom: 16px; display: block; }
.empty-state h3 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.6rem;
    color: rgba(255,255,255,0.7);
    margin-bottom: 8px;
}
.empty-state p { font-size: 14px; font-weight: 300; color: rgba(255,255,255,0.3); }

/* ── REVEAL ── */
.reveal { opacity: 0; transform: translateY(24px); transition: opacity 0.55s ease, transform 0.55s ease; }
.reveal.visible { opacity: 1; transform: translateY(0); }

/* ── RESPONSIVE ── */
@media (max-width: 768px) {
    .events-grid { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
    .list-hero { padding: 48px 16px 0; }
    .controls-bar { top: 0; padding: 10px 16px; }
    .controls-inner { flex-direction: column; align-items: stretch; gap: 10px; }
    .filter-tabs { justify-content: flex-start; overflow-x: auto; flex-wrap: nowrap; padding-bottom: 2px; }
    .filter-tabs::-webkit-scrollbar { display: none; }
    .search-input, .search-input:focus { width: 100%; }
    .sort-select { width: 100%; }
    .page-wrap { padding: 24px 16px 80px; }
}
</style>

<!-- HERO -->
<div class="list-hero">
    <div class="hero-inner">
        <div class="hero-eyebrow">Curated Experiences</div>
        <h1 class="hero-title">
            <?php if($category_filter): ?>
                <em><?php echo htmlspecialchars($category_filter); ?></em> Events
            <?php else: ?>
                All Events <em>&</em> Packages
            <?php endif; ?>
        </h1>
        <p class="hero-sub">Handpicked by our expert team for every occasion, budget, and dream.</p>
        <div class="result-pill">
            <i class="ti ti-sparkles"></i>
            <?php echo $total_events; ?> events curated for you
        </div>
    </div>
</div>

<!-- CONTROLS -->
<div class="controls-bar">
    <div class="controls-inner">
        <div class="filter-tabs">
            <a href="events_list.php" class="tab <?php echo !$category_filter ? 'active' : ''; ?>">
                <i class="ti ti-layout-grid"></i> All
            </a>
            <?php
            $cats = mysqli_query($conn, "SELECT category, COUNT(*) as cnt FROM events WHERE status='active' GROUP BY category");
            while($c = mysqli_fetch_assoc($cats)) {
                $act = ($category_filter == $c['category']) ? 'active' : '';
                $icons = [
                    'Wedding'   => 'ti-heart',
                    'Corporate' => 'ti-briefcase',
                    'Birthday'  => 'ti-confetti',
                    'Concert'   => 'ti-music',
                    'Seminar'   => 'ti-presentation',
                ];
                $ico = $icons[$c['category']] ?? 'ti-calendar-event';
                echo "<a href='events_list.php?category={$c['category']}' class='tab $act'>
                        <i class='ti $ico'></i> ".ucfirst($c['category'])."
                        <span class='tab-count'>{$c['cnt']}</span>
                      </a>";
            }
            ?>
        </div>
        <div style="display:flex;gap:8px;align-items:center;flex-shrink:0;">
            <div class="search-wrap">
                <i class="ti ti-search search-icon"></i>
                <input type="text" class="search-input" id="searchInput" placeholder="Search events…" oninput="filterCards()">
            </div>
            <select class="sort-select" id="sortSelect" onchange="sortCards()">
                <option value="default">Latest first</option>
                <option value="name">Name A–Z</option>
            </select>
        </div>
    </div>
</div>

<!-- GRID -->
<div class="page-wrap">
    <div class="events-grid" id="eventsGrid">
    <?php
    $res = mysqli_query($conn, "SELECT * FROM events $where ORDER BY id DESC");
    $avail_pool  = [76,58,83,91,64,70,88,55];
    $review_pool = [142,89,213,67,178,95,231,44];
    $ei = 0;

    if($res && mysqli_num_rows($res) > 0):
        while($ev = mysqli_fetch_assoc($res)):
            $img  = (!empty($ev['image']))
                    ? "../../uploads/events/".$ev['image']
                    : "https://images.unsplash.com/photo-1511578314322-379afb476865?q=80&w=700";
            $pct  = $avail_pool[$ei % count($avail_pool)];
            $rev  = $review_pool[$ei % count($review_pool)];
            $left = 100 - $pct;
            $delay = ($ei % 4) * 80;
    ?>
    <div class="e-card reveal" style="transition-delay:<?php echo $delay; ?>ms"
         data-name="<?php echo strtolower(htmlspecialchars($ev['name'])); ?>"
         data-cat="<?php echo strtolower(htmlspecialchars($ev['category'])); ?>"
         data-id="<?php echo (int)$ev['id']; ?>">

        <div class="e-img-wrap">
            <div class="e-img-overlay"></div>
            <span class="e-cat"><?php echo htmlspecialchars($ev['category']); ?></span>

            <?php if($left <= 30): ?>
            <span class="e-scarcity"><i class="ti ti-flame"></i> <?php echo $left; ?>% left</span>
            <?php endif; ?>

            <img src="<?php echo htmlspecialchars($img); ?>" class="e-img"
                 alt="<?php echo htmlspecialchars($ev['name']); ?>"
                 onerror="this.src='https://images.unsplash.com/photo-1511578314322-379afb476865?q=80&w=700'">

            <button class="e-wish" onclick="toggleWish(this)" aria-label="Save to wishlist">
                <i class="ti ti-heart"></i>
            </button>
        </div>

        <div class="e-body">
            <div class="e-meta">
                <div class="e-rating">
                    <i class="ti ti-star"></i> 4.9
                    <span class="e-reviews">(<?php echo $rev; ?>)</span>
                </div>
            </div>

            <h3 class="e-title"><?php echo htmlspecialchars($ev['name']); ?></h3>
            <p class="e-desc"><?php echo htmlspecialchars(substr($ev['description'], 0, 105)); ?>…</p>

            <div class="e-avail">
                <div class="e-avail-top">
                    <span class="e-avail-label">Availability</span>
                    <span class="e-avail-left">Only <?php echo $left; ?>% slots left!</span>
                </div>
                <div class="e-avail-bar">
                    <div class="e-avail-fill" style="width:<?php echo $pct; ?>%"></div>
                </div>
            </div>

            <div class="e-footer">
                <div>
                    <div class="e-price-label">Starting From</div>
                    <div class="e-price">৳ Contact Us</div>
                </div>
                <a href="event_details.php?id=<?php echo $ev['id']; ?>" class="e-btn">
                    View &amp; Book <i class="ti ti-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    <?php
        $ei++;
        endwhile;
    else:
        echo "<div class='empty-state'>
                <i class='ti ti-calendar-off'></i>
                <h3>No Events Found</h3>
                <p>Try a different category or check back soon — we're always adding new packages.</p>
              </div>";
    endif;
    ?>
    </div>
</div>

<?php include('../../includes/footer.php'); ?>

<script>
(function(){
    const obs = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if(e.isIntersecting){ e.target.classList.add('visible'); obs.unobserve(e.target); }
        });
    }, { threshold: 0.06 });
    document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
})();

function filterCards(){
    const q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('.e-card').forEach(c => {
        const match = (c.dataset.name||'').includes(q) || (c.dataset.cat||'').includes(q);
        c.style.display = match ? '' : 'none';
    });
}

function sortCards(){
    const val  = document.getElementById('sortSelect').value;
    const grid = document.getElementById('eventsGrid');
    const cards = [...grid.querySelectorAll('.e-card')];
    if(val === 'name'){
        cards.sort((a,b) => (a.dataset.name||'').localeCompare(b.dataset.name||''));
    } else {
        cards.sort((a,b) => parseInt(b.dataset.id||0) - parseInt(a.dataset.id||0));
    }
    cards.forEach(c => grid.appendChild(c));
}

function toggleWish(btn){
    btn.classList.toggle('wishlisted');
    const i = btn.querySelector('i');
    i.className = btn.classList.contains('wishlisted') ? 'ti ti-heart' : 'ti ti-heart';
    btn.style.color = btn.classList.contains('wishlisted') ? '#f87171' : '';
}
</script>