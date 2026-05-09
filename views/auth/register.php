<?php include('../../config/db_connect.php'); ?>
<?php include('../../includes/header.php'); ?>

<div style="background: #f1f2f6; min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Segoe UI', sans-serif; padding: 40px 0;">
    <div style="background: white; width: 100%; max-width: 500px; padding: 40px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); text-align: center;">
        <h2 style="color: #6c5ce7; margin-bottom: 10px; font-size: 28px;">Create an Account</h2>
        
        <?php 
        if(isset($_POST['register'])){
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $plain_password = $_POST['password']; // আসল পাসওয়ার্ড
            $phone = mysqli_real_escape_string($conn, $_POST['phone']);
            $address = mysqli_real_escape_string($conn, $_POST['address']);

            // পাসওয়ার্ড হ্যাশ করা (সিকিউরিটি আপডেট)
            $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

            $check_email = "SELECT * FROM users WHERE email='$email'";
            $res_check = mysqli_query($conn, $check_email);

            if(mysqli_num_rows($res_check) > 0) {
                echo "<p style='color: #ff7675; margin-bottom: 15px;'>Email already exists!</p>";
            } else {
                // হ্যাশ করা পাসওয়ার্ড ডাটাবেসে সেভ করা
                $sql = "INSERT INTO users (name, email, password, phone, address, role, created_at) 
                        VALUES ('$name', '$email', '$hashed_password', '$phone', '$address', 'user', NOW())";
                
                if(mysqli_query($conn, $sql)){
                    $user_id = mysqli_insert_id($conn);
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['role'] = 'user';
                    $_SESSION['user_name'] = $name;

                    echo "<div style='background: #dff9fb; padding: 15px; border-radius: 8px; border: 1px solid #2ecc71; margin-bottom: 20px;'>
                            <p style='color: #27ae60; margin: 0;'>Welcome, $name! Security Verified.</p>
                          </div>";

                    header('refresh:2; url='.SITEURL.'index.php');
                }
            }
        }
        ?>

        <form action="" method="POST" style="text-align: left;">
            <!-- ... বাকি ইনপুট ফিল্ডগুলো আগের মতোই থাকবে ... -->
            <div style="margin-bottom: 15px;">
                <label style="font-weight: 600;">Full Name</label>
                <input type="text" name="name" required style="width: 100%; padding: 12px; border: 1px solid #dfe6e9; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="font-weight: 600;">Email</label>
                <input type="email" name="email" required style="width: 100%; padding: 12px; border: 1px solid #dfe6e9; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="font-weight: 600;">Phone</label>
                <input type="text" name="phone" required style="width: 100%; padding: 12px; border: 1px solid #dfe6e9; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="font-weight: 600;">Address</label>
                <textarea name="address" style="width: 100%; padding: 12px; border: 1px solid #dfe6e9; border-radius: 8px;"></textarea>
            </div>
            <div style="margin-bottom: 25px;">
                <label style="font-weight: 600;">Password</label>
                <input type="password" name="password" required style="width: 100%; padding: 12px; border: 1px solid #dfe6e9; border-radius: 8px;">
            </div>
            <button type="submit" name="register" style="width: 100%; padding: 12px; background: #6c5ce7; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">Register & Start Planning</button>
        </form>
    </div>
</div>

<?php include('../../includes/footer.php'); ?>