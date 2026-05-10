<?php 
// ১. সেশন এবং আউটপুট বাফারিং শুরু
ob_start(); 
include('../../config/db_connect.php'); 
include('../../includes/header.php'); 

if(isset($_POST['submit'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $res = mysqli_query($conn, $sql);

    if(mysqli_num_rows($res) == 1){
        $row = mysqli_fetch_assoc($res);
        if(password_verify($password, $row['password'])){
            // সেশন সেট করা
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['user_name'] = $row['name'];

            // রোল অনুযায়ী রিডাইরেক্ট
            if($row['role'] == 'admin'){
                header('location:'.SITEURL.'views/admin/dashboard.php');
            } else {
                // সাধারণ ইউজারকে তার ড্যাশবোর্ড (My Bookings) এ পাঠানো
                header('location:'.SITEURL.'views/user/my_bookings.php');
            }
            exit();
        } else {
            $error = "Incorrect password! Please try again.";
        }
    } else {
        $error = "No account found with this email!";
    }
}
?>

<style>
    :root { --primary: #2563eb; --dark: #0f172a; --slate: #64748b; --bg: #f8fafc; }
    .auth-wrapper { background: var(--bg); min-height: 80vh; display: flex; align-items: center; justify-content: center; font-family: 'Inter', sans-serif; padding: 40px 20px; }
    .auth-card { background: white; width: 100%; max-width: 400px; padding: 45px; border-radius: 20px; box-shadow: 0 15px 35px rgba(15, 23, 42, 0.05); border: 1px solid #e2e8f0; }
    .brand-logo { text-align: center; font-family: 'Playfair Display', serif; font-size: 22px; font-weight: 700; color: var(--dark); text-transform: uppercase; margin-bottom: 30px; letter-spacing: 1px; }
    .brand-logo span { color: var(--primary); }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 12px; font-weight: 700; color: var(--dark); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px; }
    .form-control { width: 100%; padding: 13px 15px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 14px; box-sizing: border-box; transition: 0.3s; }
    .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1); }
    .btn-auth { width: 100%; padding: 15px; background: var(--dark); color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: 0.3s; margin-top: 10px; }
    .btn-auth:hover { background: var(--primary); transform: translateY(-1px); }
    .alert { padding: 12px; border-radius: 8px; font-size: 13px; text-align: center; margin-bottom: 20px; background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }
</style>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="brand-logo">AT ROYAL <span>EVENTS</span></div>
        <?php if(isset($error)) echo "<div class='alert'>$error</div>"; ?>
        <form method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required class="form-control" placeholder="name@example.com">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required class="form-control" placeholder="••••••••">
            </div>
            <button type="submit" name="submit" class="btn-auth">Sign In</button>
        </form>
        <div style="text-align: center; margin-top: 25px; font-size: 14px; color: var(--slate);">
            New to AT Royal? <a href="register.php" style="color: var(--primary); text-decoration: none; font-weight: 600;">Create Account</a>
        </div>
    </div>
</div>

<?php include('../../includes/footer.php'); ?>