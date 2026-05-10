<?php 
include('../../config/db_connect.php'); 
include('../../includes/header.php'); 
include('../../includes/navbar.php');
if(!isset($_SESSION['user_id'])) { header('location:../auth/login.php'); exit(); }
$u_id = (int)$_SESSION['user_id'];

// Payment success message
$success_msg = '';
if(isset($_SESSION['payment_success'])) { $success_msg = $_SESSION['payment_success']; unset($_SESSION['payment_success']); }
?>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<style>
body{font-family:'Inter',sans-serif;background:#f8fafc}
.wrap{max-width:1100px;margin:0 auto;padding:40px 20px}
h1{font-family:'Playfair Display',serif;font-size:2rem;color:#0f172a;margin-bottom:25px}
.alert-success{background:#dcfce7;border:1px solid #86efac;color:#166534;padding:15px 20px;border-radius:10px;margin-bottom:20px;font-weight:600}
.card{background:#fff;border-radius:16px;border:1px solid #e2e8f0;overflow:hidden}
table{width:100%;border-collapse:collapse}
thead th{background:#f8fafc;padding:15px 20px;font-size:12px;text-transform:uppercase;color:#64748b;font-weight:700;text-align:left;border-bottom:2px solid #f1f5f9}
tbody td{padding:18px 20px;border-bottom:1px solid #f8fafc;font-size:14px;vertical-align:middle}
tbody tr:last-child td{border-bottom:none}
tbody tr:hover{background:#fafbfc}
.status-badge{padding:5px 12px;border-radius:20px;font-size:11px;font-weight:700;text-transform:uppercase}
.status-pending{background:#fef9c3;color:#854d0e}
.status-approved{background:#dcfce7;color:#166534}
.status-completed{background:#dbeafe;color:#1e40af}
.status-cancelled{background:#fee2e2;color:#991b1b}
.pay-btn{background:#2563eb;color:#fff;padding:8px 18px;border-radius:8px;text-decoration:none;font-size:13px;font-weight:700;transition:.2s}
.pay-btn:hover{background:#1d4ed8}
.cancel-btn{background:#fff;color:#ef4444;border:1px solid #ef4444;padding:7px 15px;border-radius:8px;text-decoration:none;font-size:13px;font-weight:700;transition:.2s}
.cancel-btn:hover{background:#ef4444;color:#fff}
.empty-state{text-align:center;padding:60px;color:#64748b}
.empty-state i{font-size:50px;display:block;margin-bottom:15px;opacity:.3}
</style>
<div class="wrap">
    <h1>My Bookings</h1>
    <?php if($success_msg): ?>
    <div class="alert-success"><i class="ti ti-check"></i> <?php echo htmlspecialchars($success_msg); ?></div>
    <?php endif; ?>
    <div class="card">
    <?php
    $sql = "SELECT b.*, p.name as pkg_name, e.name as event_name, e.category 
            FROM bookings b 
            JOIN packages p ON b.package_id=p.id 
            JOIN events e ON p.event_id=e.id 
            WHERE b.user_id=$u_id 
            ORDER BY b.id DESC";
    $res = mysqli_query($conn, $sql);
    if($res && mysqli_num_rows($res)>0):
    ?>
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Event</th>
                <th>Package</th>
                <th>Date</th>
                <th>Total</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row=mysqli_fetch_assoc($res)): ?>
            <tr>
                <td style="font-weight:700;color:#64748b">#<?php echo $row['id']; ?></td>
                <td>
                    <div style="font-weight:600;color:#0f172a"><?php echo htmlspecialchars($row['event_name']); ?></div>
                    <div style="font-size:12px;color:#94a3b8"><?php echo ucfirst($row['category']); ?></div>
                </td>
                <td><?php echo htmlspecialchars($row['pkg_name']); ?></td>
                <td><?php echo date('d M Y', strtotime($row['event_date'])); ?></td>
                <td style="font-weight:700;color:#2563eb">৳ <?php echo number_format($row['total_price']); ?></td>
                <td><span class="status-badge status-<?php echo $row['status']; ?>"><?php echo $row['status']; ?></span></td>
                <td>
                    <?php if($row['status']=='pending'): ?>
                        <a href="payment.php?booking_id=<?php echo $row['id']; ?>" class="pay-btn">Pay Now</a>
                    <?php elseif($row['status']=='approved'): ?>
                        <span style="color:#16a34a;font-size:13px"><i class="ti ti-check-circle"></i> Confirmed</span>
                    <?php elseif($row['status']=='completed'): ?>
                        <a href="event_details.php?id=<?php echo $row['id']; ?>" style="color:#2563eb;font-size:13px;text-decoration:none"><i class="ti ti-star"></i> Review</a>
                    <?php else: ?>
                        <span style="color:#94a3b8;font-size:13px">—</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <div class="empty-state">
        <i class="ti ti-calendar-off"></i>
        <p>You have no bookings yet.</p>
        <a href="events_list.php" style="color:#2563eb;text-decoration:none;font-weight:600">Browse Events →</a>
    </div>
    <?php endif; ?>
    </div>
</div>
<?php include('../../includes/footer.php'); ?>
