<?php 
ob_start(); 
include('../../config/db_connect.php'); 
include('../../includes/header.php'); 

if(isset($_POST['register'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // ইমেইল অলরেডি আছে কি না চেক
    $check_email = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
    
    if(mysqli_num_rows($check_email) > 0) {
        $error = "This email is already registered!";
    } else {
        $sql = "INSERT INTO users (name, email, password, phone, role, created_at) 
                VALUES ('$name', '$email', '$password', '$phone', 'user', NOW())";
        
        if(mysqli_query($conn, $sql)){
            // রেজিস্ট্রেশনের পর অটো লগইন (সেশন সেট)
            $_SESSION['user_id'] = mysqli_insert_id($conn);
            $_SESSION['role'] = 'user';
            $_SESSION['user_name'] = $name;

            // সরাসরি ইউজার ড্যাশবোর্ড (My Bookings) এ রিডাইরেক্ট
            header('location:'.SITEURL.'views/user/my_bookings.php');
            exit();
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>

<style>
    :root { --primary: #2563eb; --dark: #0f172a; --slate: #64748b; --bg: #f8fafc; }
    .auth-wrapper { background: var(--bg); min-height: 85vh; display: flex; align-items: center; justify-content: center; font-family: 'Inter', sans-serif; padding: 40px 20px; }
    .auth-card { background: white; width: 100%; max-width: 450px; padding: 45px; border-radius: 20px; box-shadow: 0 15px 35px rgba(15, 23, 42, 0.05); border: 1px solid #e2e8f0; }
    .brand-logo { text-align: center; font-family: 'Playfair Display', serif; font-size: 22px; font-weight: 700; color: var(--dark); text-transform: uppercase; margin-bottom: 25px; letter-spacing: 1px; }
    .brand-logo span { color: var(--primary); }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; font-size: 11px; font-weight: 700; color: var(--dark); text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px; }
    .form-control { width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 14px; box-sizing: border-box; }
    .btn-auth { width: 100%; padding: 15px; background: var(--dark); color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: 0.3s; margin-top: 15px; }
    .btn-auth:hover { background: var(--primary); transform: translateY(-1px); }
    .alert { padding: 12px; border-radius: 8px; font-size: 13px; text-align: center; margin-bottom: 20px; background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }
</style>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="brand-logo">AT ROYAL <span>EVENTS</span></div>
        <?php if(isset($error)) echo "<div class='alert'>$error</div>"; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" required class="form-control" placeholder="Enter your name">
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required class="form-control" placeholder="email@example.com">
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" required class="form-control" placeholder="01XXXXXXXXX">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required class="form-control" placeholder="••••••••">
            </div>
            <button type="submit" name="register" class="btn-auth">Create Account</button>
        </form>
        
        <div style="text-align: center; margin-top: 25px; font-size: 14px; color: var(--slate);">
            Already have an account? <a href="login.php" style="color: var(--primary); text-decoration: none; font-weight: 600;">Sign In</a>
        </div>
    </div>
</div>

<?php include('../../includes/footer.php'); ?>