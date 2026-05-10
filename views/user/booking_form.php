<?php 
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('../../config/db_connect.php'); 
include('../../includes/header.php'); 
include('../../includes/navbar.php');

// 1. Login Check
if(!isset($_SESSION['user_id'])) { 
    header('Location: ../auth/login.php'); 
    exit(); 
}

// 2. Validate Package ID
if(!isset($_GET['package_id']) || empty($_GET['package_id'])) { 
    header('Location: events_list.php'); 
    exit(); 
}

$package_id = (int)mysqli_real_escape_string($conn, $_GET['package_id']);
$user_id = (int)$_SESSION['user_id'];

// Fetch Package & Event Info
$query = "SELECT p.*, e.name as event_name, e.id as event_id 
          FROM packages p 
          JOIN events e ON p.event_id = e.id 
          WHERE p.id = $package_id";
$res = mysqli_query($conn, $query);

if(!$res || mysqli_num_rows($res) == 0){
    echo "<div style='max-width: 600px; margin: 100px auto; text-align:center; padding: 40px; background: #fff; border-radius: 12px; border: 1px solid #e2e8f0;'>
            <h2 style='font-family: Playfair Display, serif; color: #0f172a;'>Invalid Package</h2>
            <p style='color: #64748b;'>The selected package could not be found.</p>
            <a href='events_list.php' style='color: #2563eb; text-decoration: none; font-weight: 600;'>Return to Events</a>
          </div>";
    include('../../includes/footer.php');
    exit();
}

$pkg_data = mysqli_fetch_assoc($res);
$total_price = (float)$pkg_data['price'];
$advance_amount = round($total_price * 0.30, 2); // 30% Advance Calculation

// 3. Handle Booking Submission
if(isset($_POST['confirm_booking'])){
    $event_date = mysqli_real_escape_string($conn, $_POST['event_date']);
    $venue = mysqli_real_escape_string($conn, $_POST['venue']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $trx_id = mysqli_real_escape_string($conn, $_POST['trx_id']);
    $event_id = (int)$pkg_data['event_id'];

    // Insert into Bookings table
    $book_sql = "INSERT INTO bookings (user_id, event_id, package_id, event_date, venue, total_amount, status) 
                 VALUES ($user_id, $event_id, $package_id, '$event_date', '$venue', $total_price, 'pending')";
    
    if(mysqli_query($conn, $book_sql)){
        $booking_id = mysqli_insert_id($conn); 
        
        // Insert into Payments table (Advance Payment)
        $pay_sql = "INSERT INTO payments (booking_id, user_id, amount, method, trx_id, status) 
                    VALUES ($booking_id, $user_id, $advance_amount, '$payment_method', '$trx_id', 'success')";
        
        if(mysqli_query($conn, $pay_sql)){
            echo "<script>alert('Booking Confirmed! Your advance payment is under review.'); window.location.href='../user/my_bookings.php';</script>";
            exit();
        } else {
            $error = "Payment record failed. Please contact support.";
        }
    } else {
        $error = "Booking failed to process. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout | EventPro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        :root { --blue: #2563eb; --dark: #0f172a; --gray: #64748b; --bg: #f8fafc; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--dark); margin: 0; }
        
        .booking-wrapper { max-width: 1100px; margin: 60px auto; padding: 0 20px; }
        .page-title { font-family: 'Playfair Display', serif; font-size: 2.5rem; margin-bottom: 40px; text-align: center; color: var(--dark); }
        
        .grid-container { display: grid; grid-template-columns: 1.2fr 1fr; gap: 40px; align-items: start; }
        .card { background: #fff; padding: 35px; border-radius: 20px; border: 1px solid #e2e8f0; box-shadow: 0 10px 30px rgba(0,0,0,0.03); }
        
        .card-title { font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 700; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 10px; }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 12px; font-weight: 700; color: var(--gray); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
        .form-control { width: 100%; padding: 14px 16px; border: 1px solid #e2e8f0; border-radius: 10px; font-family: inherit; font-size: 15px; box-sizing: border-box; transition: 0.3s; background: var(--bg); }
        .form-control:focus { border-color: var(--blue); background: #fff; outline: none; box-shadow: 0 0 0 4px rgba(37,99,235,0.1); }
        
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 18px; font-size: 15px; color: var(--gray); }
        .summary-row strong { color: var(--dark); font-weight: 600; text-align: right; }
        .total-row { display: flex; justify-content: space-between; margin-top: 25px; padding-top: 25px; border-top: 2px dashed #cbd5e1; font-size: 1.2rem; font-weight: 800; color: var(--blue); font-family: 'Playfair Display', serif; }
        
        .pay-alert { background: #eff6ff; color: #1e40af; padding: 20px; border-radius: 12px; font-size: 14px; margin-bottom: 25px; line-height: 1.6; border: 1px solid #bfdbfe; }
        .pay-alert strong { display: flex; align-items: center; gap: 8px; font-size: 16px; margin-bottom: 8px; }

        .btn-submit { width: 100%; background: var(--blue); color: #fff; border: none; padding: 16px; border-radius: 10px; font-weight: 600; font-size: 16px; cursor: pointer; transition: 0.3s; margin-top: 15px; }
        .btn-submit:hover { background: #1d4ed8; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(37,99,235,0.2); }

        .error-msg { background: #fef2f2; border: 1px solid #fecaca; color: #ef4444; padding: 15px; border-radius: 10px; margin-bottom: 20px; text-align: center; font-weight: 500; }

        @media (max-width: 768px) { .grid-container { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<div class="booking-wrapper">
    <h1 class="page-title">Finalize Your Booking</h1>
    
    <?php if(isset($error)) echo "<div class='error-msg'><i class='ti ti-alert-circle'></i> $error</div>"; ?>

    <div class="grid-container">
        <!-- Left: Form -->
        <div class="card">
            <h2 class="card-title"><i class="ti ti-clipboard-data" style="color:var(--blue);"></i> Event Details & Payment</h2>
            
            <form action="" method="POST">
                <div class="form-group">
                    <label>Event Date</label>
                    <input type="date" name="event_date" class="form-control" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                </div>
                
                <div class="form-group">
                    <label>Venue / Location Address</label>
                    <textarea name="venue" class="form-control" rows="3" placeholder="Enter the full address for the event..." required></textarea>
                </div>

                <div class="pay-alert">
                    <strong><i class="ti ti-cash-banknotes"></i> Advance Payment Required</strong>
                    To lock in your date, a 30% advance of the total package price is required. Please send <strong>৳ <?php echo number_format($advance_amount); ?></strong> to our official merchant account and provide the Transaction ID below.
                </div>

                <div class="form-group">
                    <label>Payment Method</label>
                    <select name="payment_method" class="form-control" required>
                        <option value="">Select Method</option>
                        <option value="bKash">bKash (Merchant: 017XXXXXXX)</option>
                        <option value="Nagad">Nagad (Merchant: 017XXXXXXX)</option>
                        <option value="Bank">Bank Transfer (City Bank)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Transaction ID (TrxID)</label>
                    <input type="text" name="trx_id" class="form-control" placeholder="e.g. 8KSD93MDF" required>
                </div>

                <button type="submit" name="confirm_booking" class="btn-submit">Pay Advance & Confirm Booking</button>
            </form>
        </div>

        <!-- Right: Summary -->
        <div class="card" style="height: fit-content; background: #f8fafc; border: 2px solid #e2e8f0;">
            <h2 class="card-title"><i class="ti ti-receipt-2" style="color:var(--blue);"></i> Order Summary</h2>
            
            <div class="summary-row">
                <span>Event Category:</span>
                <strong><?php echo htmlspecialchars($pkg_data['event_name']); ?></strong>
            </div>
            
            <div class="summary-row">
                <span>Selected Package:</span>
                <strong><?php echo htmlspecialchars($pkg_data['name']); ?></strong>
            </div>
            
            <div style="margin: 25px 0; padding: 20px; background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 14px; color: var(--gray); line-height: 1.6;">
                <strong style="color: var(--dark); display:block; margin-bottom: 8px;">Included Services:</strong>
                <?php 
                $services = array_map('trim', explode(',', $pkg_data['included_services']));
                echo "<ul style='margin:0; padding-left: 20px;'>";
                foreach($services as $s) {
                    if(!empty($s)) echo "<li>" . htmlspecialchars($s) . "</li>";
                }
                echo "</ul>";
                ?>
            </div>

            <div class="summary-row">
                <span>Total Package Price:</span>
                <strong style="color: var(--dark); font-size: 16px;">৳ <?php echo number_format($total_price); ?></strong>
            </div>
            
            <div class="total-row">
                <span>Advance to Pay (30%):</span>
                <span>৳ <?php echo number_format($advance_amount); ?></span>
            </div>
            
            <div style="font-size: 12px; color: var(--gray); text-align: center; margin-top: 20px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <i class="ti ti-info-circle"></i> Remaining balance is due 3 days prior to the event.
            </div>
        </div>
    </div>
</div>

<?php include('../../includes/footer.php'); ?>
<?php ob_end_flush(); ?>
</body>
</html>