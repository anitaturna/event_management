<?php 
include('../../config/db_connect.php'); 
if(!isset($_SESSION['user_id'])) { header('location:../auth/login.php'); exit(); }
if(!isset($_GET['booking_id'])) { header('location:../../index.php'); exit(); }

$booking_id = (int)$_GET['booking_id'];
$sql = "SELECT b.*, u.name as uname, p.name as pkg_name, e.name as event_name, e.category
        FROM bookings b 
        JOIN users u ON b.user_id=u.id 
        JOIN packages p ON b.package_id=p.id
        JOIN events e ON p.event_id=e.id
        WHERE b.id=$booking_id AND b.user_id={$_SESSION['user_id']}";
$res = mysqli_query($conn, $sql);
if(!$res||mysqli_num_rows($res)==0) { echo "<p style='text-align:center;padding:60px'>Booking not found.</p>"; exit(); }
$booking = mysqli_fetch_assoc($res);

// Vendors for this booking
$vend_res = mysqli_query($conn, "SELECT bv.price, v.name, v.service_type FROM booking_vendors bv JOIN vendors v ON bv.vendor_id=v.id WHERE bv.booking_id=$booking_id");
?>
<?php include('../../includes/header.php'); ?>
<?php include('../../includes/navbar.php'); ?>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<style>
body{font-family:'Inter',sans-serif;background:#f8fafc}
.wrap{max-width:900px;margin:50px auto;padding:0 20px;display:grid;grid-template-columns:1fr 380px;gap:25px;align-items:start}
.card{background:#fff;border-radius:16px;padding:28px;border:1px solid #e2e8f0}
h2{font-family:'Playfair Display',serif;font-size:1.6rem;color:#0f172a;margin:0 0 20px}
.summary-row{display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid #f1f5f9;font-size:14px}
.summary-row:last-child{border-bottom:none}
.total-row{display:flex;justify-content:space-between;padding:16px 0;font-size:18px;font-weight:800;color:#0f172a;border-top:2px solid #e2e8f0;margin-top:10px}
.total-row span:last-child{color:#2563eb}
.method-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:20px}
.method-label{border:2px solid #e2e8f0;border-radius:10px;padding:14px;cursor:pointer;text-align:center;transition:.2s}
.method-label:hover{border-color:#2563eb}
.method-label input{display:none}
.method-label.selected,.method-label:has(input:checked){border-color:#2563eb;background:#eff6ff}
.method-icon{font-size:24px;margin-bottom:5px;display:block}
.method-name{font-size:13px;font-weight:700;color:#0f172a}
.form-input{width:100%;padding:12px;border:1px solid #e2e8f0;border-radius:8px;font-family:inherit;font-size:14px;box-sizing:border-box;margin-bottom:16px;transition:.2s}
.form-input:focus{outline:none;border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.1)}
.pay-btn{width:100%;padding:15px;background:#2563eb;color:#fff;border:none;border-radius:10px;font-size:16px;font-weight:700;cursor:pointer;transition:.2s;display:flex;align-items:center;justify-content:center;gap:8px}
.pay-btn:hover{background:#1d4ed8;transform:translateY(-1px);box-shadow:0 4px 12px rgba(37,99,235,.3)}
.secure-badge{display:flex;align-items:center;gap:6px;color:#64748b;font-size:12px;justify-content:center;margin-top:12px}
@media(max-width:768px){.wrap{grid-template-columns:1fr}}
</style>
<div class="wrap">
    <!-- Order Summary -->
    <div class="card">
        <h2><i class="ti ti-receipt" style="color:#2563eb"></i> Order Summary</h2>
        <div class="summary-row">
            <span style="color:#64748b">Customer</span>
            <span style="font-weight:600"><?php echo htmlspecialchars($booking['uname']); ?></span>
        </div>
        <div class="summary-row">
            <span style="color:#64748b">Booking ID</span>
            <span style="font-weight:600">#<?php echo $booking_id; ?></span>
        </div>
        <div class="summary-row">
            <span style="color:#64748b">Event</span>
            <span style="font-weight:600"><?php echo htmlspecialchars($booking['event_name']); ?></span>
        </div>
        <div class="summary-row">
            <span style="color:#64748b">Package</span>
            <span style="font-weight:600"><?php echo htmlspecialchars($booking['pkg_name']); ?></span>
        </div>
        <div class="summary-row">
            <span style="color:#64748b">Event Date</span>
            <span style="font-weight:600"><?php echo date('d M Y', strtotime($booking['event_date'])); ?></span>
        </div>
        <?php while($vd=mysqli_fetch_assoc($vend_res)): ?>
        <div class="summary-row">
            <span style="color:#64748b"><?php echo ucfirst($vd['service_type']); ?> - <?php echo htmlspecialchars($vd['name']); ?></span>
            <span>৳ <?php echo number_format($vd['price']); ?></span>
        </div>
        <?php endwhile; ?>
        <div class="total-row">
            <span>Total Amount</span>
            <span>৳ <?php echo number_format($booking['total_price']); ?></span>
        </div>
    </div>

    <!-- Payment Form -->
    <div class="card">
        <h2><i class="ti ti-lock" style="color:#2563eb"></i> Secure Payment</h2>
        <form action="process_payment.php" method="POST">
            <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
            <input type="hidden" name="amount" value="<?php echo $booking['total_price']; ?>">
            
            <p style="font-size:13px;font-weight:700;color:#64748b;text-transform:uppercase;margin-bottom:12px">Select Payment Method</p>
            <div class="method-grid">
                <label class="method-label">
                    <input type="radio" name="method" value="bkash" required>
                    <span class="method-icon">💜</span>
                    <span class="method-name">bKash</span>
                </label>
                <label class="method-label">
                    <input type="radio" name="method" value="nagad">
                    <span class="method-icon">🧡</span>
                    <span class="method-name">Nagad</span>
                </label>
                <label class="method-label">
                    <input type="radio" name="method" value="card">
                    <span class="method-icon">💳</span>
                    <span class="method-name">Card</span>
                </label>
                <label class="method-label">
                    <input type="radio" name="method" value="cash">
                    <span class="method-icon">💵</span>
                    <span class="method-name">Cash</span>
                </label>
            </div>

            <label style="font-size:13px;font-weight:700;color:#64748b;text-transform:uppercase;display:block;margin-bottom:8px">Transaction ID</label>
            <input type="text" name="transaction_id" class="form-input" placeholder="Enter Transaction ID / Reference No." required>

            <button type="submit" name="pay_now" class="pay-btn">
                <i class="ti ti-lock"></i> Pay ৳ <?php echo number_format($booking['total_price']); ?>
            </button>
            <div class="secure-badge">
                <i class="ti ti-shield-check"></i> 256-bit SSL encrypted payment
            </div>
        </form>
    </div>
</div>
<script>
document.querySelectorAll('.method-label').forEach(label => {
    label.addEventListener('click', () => {
        document.querySelectorAll('.method-label').forEach(l => l.classList.remove('selected'));
        label.classList.add('selected');
    });
});
</script>
<?php include('../../includes/footer.php'); ?>
