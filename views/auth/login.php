<?php include('../../config/db_connect.php'); ?>
<?php include('../../includes/header.php'); ?>

<div style="background: #f1f2f6; min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Segoe UI', sans-serif;">
    <div style="background: white; width: 100%; max-width: 400px; padding: 40px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); text-align: center;">
        <h2 style="color: #6c5ce7; margin-bottom: 30px;">Sign In</h2>

        <?php 
        if(isset($_POST['submit'])){
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $input_password = $_POST['password'];

            // ইমেইল দিয়ে ইউজার খুঁজে বের করা
            $sql = "SELECT * FROM users WHERE email='$email'";
            $res = mysqli_query($conn, $sql);

            if(mysqli_num_rows($res) == 1){
                $row = mysqli_fetch_assoc($res);
                $db_password_hash = $row['password'];

                // হ্যাশ করা পাসওয়ার্ড চেক করা (সিকিউরিটি আপডেট)
                if(password_verify($input_password, $db_password_hash)){
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['role'] = $row['role'];
                    $_SESSION['user_name'] = $row['name'];

                    echo "<p style='color: #2ecc71; margin-bottom: 15px;'>Login Successful!</p>";
                    header('refresh:1; url='.SITEURL.'index.php');
                } else {
                    echo "<p style='color: #ff7675; margin-bottom: 15px;'>Invalid Password!</p>";
                }
            } else {
                echo "<p style='color: #ff7675; margin-bottom: 15px;'>User not found!</p>";
            }
        }
        ?>

        <form action="" method="POST">
            <div style="text-align: left; margin-bottom: 20px;">
                <label style="font-weight: 600;">Email Address</label>
                <input type="email" name="email" required style="width: 100%; padding: 12px; border: 1px solid #dfe6e9; border-radius: 8px;">
            </div>
            <div style="text-align: left; margin-bottom: 25px;">
                <label style="font-weight: 600;">Password</label>
                <input type="password" name="password" required style="width: 100%; padding: 12px; border: 1px solid #dfe6e9; border-radius: 8px;">
            </div>
            <button type="submit" name="submit" style="width: 100%; padding: 12px; background: #6c5ce7; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">Sign In</button>
        </form>
    </div>
</div>

<?php include('../../includes/footer.php'); ?>