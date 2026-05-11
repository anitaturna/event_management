<?php

// ============================
// ERROR REPORTING
// ============================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ============================
// OUTPUT BUFFERING + SESSION
// ============================
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================
// DATABASE CONNECTION
// ============================
include('../../config/db_connect.php');

// Check DB Connection
if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

// ============================
// LOGIN LOGIC
// ============================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // ============================
    // GET FORM DATA
    // ============================
    $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $password = trim($_POST['password']);

    // ============================
    // VALIDATION
    // ============================
    if (empty($email) || empty($password)) {

        $error = "Please fill in all fields!";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Invalid email format!";

    } else {

        // ============================
        // CHECK USER
        // ============================
        $sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";

        $result = mysqli_query($conn, $sql);

        if (!$result) {

            $error = "SQL Error: " . mysqli_error($conn);

        } elseif (mysqli_num_rows($result) == 1) {

            $row = mysqli_fetch_assoc($result);

            // ============================
            // VERIFY PASSWORD
            // ============================
            if (password_verify($password, $row['password'])) {

                // ============================
                // SET SESSION
                // ============================
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['user_name'] = $row['name'];

                // ============================
                // REDIRECT BASED ON ROLE
                // ============================
                if ($row['role'] == 'admin') {

                    header("Location: " . SITEURL . "views/admin/dashboard.php");
                    exit();

                } else {

                    header("Location: " . SITEURL . "views/user/my_bookings.php");
                    exit();
                }

            } else {

                $error = "Incorrect password!";
            }

        } else {

            $error = "No account found with this email!";
        }
    }
}

// ============================
// HEADER INCLUDE
// ============================
include('../../includes/header.php');

?>

<style>

:root{
    --primary:#2563eb;
    --dark:#0f172a;
    --slate:#64748b;
    --bg:#f8fafc;
}

.auth-wrapper{
    background:var(--bg);
    min-height:80vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:40px 20px;
    font-family:'Inter',sans-serif;
}

.auth-card{
    background:white;
    width:100%;
    max-width:420px;
    padding:45px;
    border-radius:20px;
    box-shadow:0 15px 35px rgba(15,23,42,0.05);
    border:1px solid #e2e8f0;
}

.brand-logo{
    text-align:center;
    font-size:24px;
    font-weight:700;
    color:var(--dark);
    margin-bottom:30px;
    letter-spacing:1px;
}

.brand-logo span{
    color:var(--primary);
}

.form-group{
    margin-bottom:20px;
}

.form-group label{
    display:block;
    font-size:12px;
    font-weight:700;
    text-transform:uppercase;
    margin-bottom:8px;
    color:var(--dark);
}

.form-control{
    width:100%;
    padding:13px 15px;
    border:1px solid #e2e8f0;
    border-radius:10px;
    font-size:14px;
    box-sizing:border-box;
    transition:0.3s;
}

.form-control:focus{
    outline:none;
    border-color:var(--primary);
    box-shadow:0 0 0 4px rgba(37,99,235,0.1);
}

.btn-auth{
    width:100%;
    padding:15px;
    border:none;
    border-radius:10px;
    background:var(--dark);
    color:white;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
    margin-top:10px;
}

.btn-auth:hover{
    background:var(--primary);
    transform:translateY(-1px);
}

.alert{
    padding:12px;
    border-radius:10px;
    margin-bottom:20px;
    font-size:14px;
    text-align:center;
    font-weight:600;
}

.alert-error{
    background:#fff1f2;
    color:#be123c;
    border:1px solid #fecdd3;
}

</style>

<div class="auth-wrapper">

    <div class="auth-card">

        <div class="brand-logo">
            AT ROYAL <span>EVENTS</span>
        </div>

        <!-- ERROR MESSAGE -->
        <?php
        if (isset($error)) {
            echo "<div class='alert alert-error'>$error</div>";
        }
        ?>

        <!-- LOGIN FORM -->
        <form method="POST" action="">

            <div class="form-group">
                <label>Email Address</label>

                <input
                    type="email"
                    name="email"
                    required
                    class="form-control"
                    placeholder="name@example.com"
                >
            </div>

            <div class="form-group">
                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    required
                    class="form-control"
                    placeholder="••••••••"
                >
            </div>

            <button type="submit" class="btn-auth">
                Sign In
            </button>

        </form>

        <div
            style="
                text-align:center;
                margin-top:25px;
                font-size:14px;
                color:var(--slate);
            "
        >
            New to AT Royal?

            <a
                href="register.php"
                style="
                    color:var(--primary);
                    text-decoration:none;
                    font-weight:600;
                "
            >
                Create Account
            </a>
        </div>

    </div>

</div>

<?php include('../../includes/footer.php'); ?>