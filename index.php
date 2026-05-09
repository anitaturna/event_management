<?php 
// ১. ডাটাবেস কানেকশন ইনক্লুড করা
include('config/db_connect.php'); 

// ২. হেডার এবং নেভিগেশন বার ইনক্লুড করা
include('includes/header.php'); 
include('includes/navbar.php'); 
?>

<div class="container">
    <!-- হিরো সেকশন -->
    <header class="hero-section" style="text-align: center; padding: 60px 20px; background: linear-gradient(135deg, #6c5ce7, #a29bfe); color: white; border-radius: 15px; margin-bottom: 40px;">
        <h1 style="font-size: 3rem; margin-bottom: 10px;">Welcome to Event Management</h1>
        <p style="font-size: 1.2rem; opacity: 0.9;">Book your dream wedding, birthday, or corporate event with ease.</p>
    </header>

    <!-- ক্যাটাগরি সেকশন -->
    <section class="categories">
        <h2 style="border-left: 5px solid #6c5ce7; padding-left: 15px; margin-bottom: 25px;">Browse by Category</h2>
        <div class="category-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 50px;">
            <?php 
            // ডায়াগ্রামের events টেবিল থেকে ইউনিক ক্যাটাগরি আনা হচ্ছে
            $sql = "SELECT DISTINCT category FROM events WHERE status='active'";
            $res = mysqli_query($conn, $sql);
            
            if($res && mysqli_num_rows($res) > 0) {
                while($row = mysqli_fetch_assoc($res)) {
                    $cat = htmlspecialchars($row['category']);
                    echo "<a href='views/user/events_list.php?category=$cat' class='cat-card' style='background: white; padding: 25px; text-align: center; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-decoration: none; color: #6c5ce7; font-weight: bold; transition: 0.3s; border: 1px solid #eee;'>
                            <span style='display: block; font-size: 1.1rem;'>$cat</span>
                          </a>";
                }
            } else {
                echo "<p>No categories found.</p>";
            }
            ?>
        </div>
    </section>

    <!-- ফিচারড ইভেন্ট সেকশন -->
    <section class="featured-events">
        <h2 style="border-left: 5px solid #6c5ce7; padding-left: 15px; margin-bottom: 25px;">Featured Events</h2>
        <div class="event-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            <?php 
            // লেটেস্ট ৬টি ইভেন্ট আনা হচ্ছে
            $sql2 = "SELECT * FROM events WHERE status='active' ORDER BY id DESC LIMIT 6";
            $res2 = mysqli_query($conn, $sql2);
            
            if($res2 && mysqli_num_rows($res2) > 0) {
                while($event = mysqli_fetch_assoc($res2)) { ?>
                    <div class="event-card" style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 20px rgba(0,0,0,0.08); transition: transform 0.3s;">
                        <!-- ইমেজ যদি না থাকে তবে একটি প্লেসহোল্ডার দেখাবে -->
                        <?php 
                            $img_path = "uploads/events/" . $event['image'];
                            $display_img = (!empty($event['image']) && file_exists($img_path)) ? $img_path : "https://via.placeholder.com/400x250?text=Event+Image";
                        ?>
                        <img src="<?php echo $display_img; ?>" alt="Event" style="width: 100%; height: 220px; object-fit: cover;">
                        
                        <div style="padding: 20px;">
                            <span style="font-size: 0.8rem; background: #f1f2f6; padding: 5px 10px; border-radius: 20px; color: #6c5ce7; font-weight: bold;"><?php echo htmlspecialchars($event['category']); ?></span>
                            <h3 style="margin: 15px 0 10px; color: #2d3436;"><?php echo htmlspecialchars($event['name']); ?></h3>
                            <p style="color: #636e72; font-size: 0.9rem; line-height: 1.5;">
                                <?php echo htmlspecialchars(substr($event['description'], 0, 100)); ?>...
                            </p>
                            <a href="views/user/event_details.php?id=<?php echo $event['id']; ?>" class="btn" style="display: inline-block; margin-top: 15px; background: #6c5ce7; color: white; padding: 10px 25px; border-radius: 8px; text-decoration: none; font-weight: 500;">View Details</a>
                        </div>
                    </div>
                <?php } 
            } else {
                echo "<p>No featured events available right now.</p>";
            }
            ?>
        </div>
    </section>
</div>

<?php 
// ৩. ফুটার ইনক্লুড করা (এরর হ্যান্ডলিংসহ)
if(file_exists('includes/footer.php')){
    include('includes/footer.php'); 
} else {
    echo "</div></body></html>"; // ফাইল না থাকলে ট্যাগগুলো ক্লোজ করে দিবে যাতে ডিজাইন না ভাঙে
}
?>