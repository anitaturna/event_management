<?php 
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

            // রোল অনুযায়ী রিডাইরেক্ট (পাথগুলো আপনার ফোল্ডার অনুযায়ী চেক করে নিন)
            if($row['role'] == 'admin'){
                header('location:'.SITEURL.'views/admin/dashboard.php');
            } else {
                header('location:'.SITEURL.'index.php');
            }
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "No user found with this email!";
    }
}
?>

<div style="background: #f4f7f6; min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Inter', sans-serif;">
    <div style="background: white; width: 100%; max-width: 400px; padding: 40px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
        <h2 style="color: #2d3436; text-align: center; margin-bottom: 10px; font-weight: 700;">Corporate Login</h2>
        <p style="text-align: center; color: #636e72; margin-bottom: 30px; font-size: 14px;">Access your event management dashboard</p>

        <?php if(isset($error)) echo "<p style='color: #d63031; background: #fab1a033; padding: 10px; border-radius: 5px; text-align: center; font-size: 14px;'>$error</p>"; ?>

        <form action="" method="POST">
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #2d3436;">Email Address</label>
                <input type="email" name="email" required style="width: 100%; padding: 12px; border: 1px solid #dfe6e9; border-radius: 6px; box-sizing: border-box;">
            </div>
            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #2d3436;">Password</label>
                <input type="password" name="password" required style="width: 100%; padding: 12px; border: 1px solid #dfe6e9; border-radius: 6px; box-sizing: border-box;">
            </div>
            <button type="submit" name="submit" style="width: 100%; padding: 14px; background: #2d3436; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: 0.3s;">Sign In</button>
        </form>
    </div>
</div>