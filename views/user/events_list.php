<?php 
include('../../config/db_connect.php'); 
include('../../includes/header.php'); 
include('../../includes/navbar.php'); 

$category_filter = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
$where = "WHERE status='active'";
if($category_filter) $where .= " AND category='$category_filter'";
?>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<style>
body{font-family:'Inter',sans-serif;background:#f8fafc}
.page-wrap{max-width:1200px;margin:0 auto;padding:40px 20px}
.page-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:30px;flex-wrap:wrap;gap:15px}
.page-title{font-family:'Playfair Display',serif;font-size:2rem;color:#0f172a}
.filter-tabs{display:flex;gap:10px;flex-wrap:wrap}
.tab{padding:8px 18px;border-radius:30px;text-decoration:none;font-size:13px;font-weight:600;border:1px solid #e2e8f0;color:#64748b;background:#fff;transition:.2s}
.tab:hover,.tab.active{background:#2563eb;color:#fff;border-color:#2563eb}
.events-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:28px}
.e-card{background:#fff;border-radius:18px;overflow:hidden;border:1px solid #e2e8f0;transition:all .3s ease;display:flex;flex-direction:column}
.e-card:hover{transform:translateY(-6px);box-shadow:0 20px 40px rgba(0,0,0,.08)}
.e-img-wrap{position:relative;height:220px;overflow:hidden}
.e-img{width:100%;height:100%;object-fit:cover;transition:transform .5s}
.e-card:hover .e-img{transform:scale(1.06)}
.e-cat{position:absolute;top:15px;left:15px;background:rgba(255,255,255,.95);color:#2563eb;padding:5px 12px;border-radius:20px;font-size:11px;font-weight:700;text-transform:uppercase}
.e-body{padding:24px;flex-grow:1;display:flex;flex-direction:column}
.e-title{font-family:'Playfair Display',serif;font-size:1.25rem;color:#0f172a;margin:0 0 10px}
.e-desc{color:#64748b;font-size:14px;line-height:1.6;flex-grow:1;margin-bottom:20px}
.e-btn{display:block;text-align:center;background:#0f172a;color:#fff;padding:12px;border-radius:10px;text-decoration:none;font-weight:600;font-size:14px;transition:.2s}
.e-btn:hover{background:#2563eb}
</style>
<div class="page-wrap">
    <div class="page-top">
        <h1 class="page-title">All Events</h1>
        <div class="filter-tabs">
            <a href="events_list.php" class="tab <?php echo !$category_filter?'active':''; ?>">All</a>
            <?php
            $cats=mysqli_query($conn,"SELECT DISTINCT category FROM events WHERE status='active'");
            while($c=mysqli_fetch_assoc($cats)){
                $act=($category_filter==$c['category'])?'active':'';
                echo "<a href='events_list.php?category={$c['category']}' class='tab $act'>".ucfirst($c['category'])."</a>";
            }
            ?>
        </div>
    </div>
    <div class="events-grid">
    <?php
    $res=mysqli_query($conn,"SELECT * FROM events $where ORDER BY id DESC");
    if($res&&mysqli_num_rows($res)>0){
        while($ev=mysqli_fetch_assoc($res)){
            $img=(!empty($ev['image']))?"../../uploads/events/".$ev['image']:"https://images.unsplash.com/photo-1511578314322-379afb476865?q=80&w=600";
            echo "<div class='e-card'>
                    <div class='e-img-wrap'>
                        <span class='e-cat'>".htmlspecialchars($ev['category'])."</span>
                        <img src='$img' class='e-img' alt='".htmlspecialchars($ev['name'])."' onerror=\"this.src='https://images.unsplash.com/photo-1511578314322-379afb476865?q=80&w=600'\">
                    </div>
                    <div class='e-body'>
                        <h3 class='e-title'>".htmlspecialchars($ev['name'])."</h3>
                        <p class='e-desc'>".htmlspecialchars(substr($ev['description'],0,100))."...</p>
                        <a href='event_details.php?id={$ev['id']}' class='e-btn'>View Details &amp; Book</a>
                    </div>
                  </div>";
        }
    } else {
        echo "<p style='grid-column:1/-1;text-align:center;color:#64748b;padding:60px'>No events found.</p>";
    }
    ?>
    </div>
</div>
<?php include('../../includes/footer.php'); ?>
