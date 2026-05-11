<?php

// ==========================
// ERROR REPORTING
// ==========================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ==========================
// SESSION START
// ==========================
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==========================
// DATABASE CONNECTION
// ==========================
include('../../config/db_connect.php');

// Check DB connection
if (!$conn) {
    die("
        <h3 style='color:red;text-align:center;'>
            Database Connection Failed:
            " . mysqli_connect_error() . "
        </h3>
    ");
}

// ==========================
// REGISTER LOGIC
// ==========================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Debug POST Data (optional)
    // echo "<pre>";
    // print_r($_POST);
    // echo "</pre>";

    // ==========================
    // GET FORM DATA
    // ==========================
    $name     = trim(mysqli_real_escape_string($conn, $_POST['name']));
    $email    = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $phone    = trim(mysqli_real_escape_string($conn, $_POST['phone']));
    $address  = trim(mysqli_real_escape_string($conn, $_POST['address']));
    $password = $_POST['password'];

    // ==========================
    // VALIDATION
    // ==========================
    if (
        empty($name) ||
        empty($email) ||
        empty($phone) ||
        empty($address) ||
        empty($password)
    ) {

        $error = "All fields are required!";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Invalid email format!";

    } else {

        // ==========================
        // CHECK EMAIL EXISTS
        // ==========================
        $check_sql = "SELECT id FROM users WHERE email='$email'";
        $check_result = mysqli_query($conn, $check_sql);

        if (!$check_result) {

            $error = "SQL Error: " . mysqli_error($conn);

        } elseif (mysqli_num_rows($check_result) > 0) {

            $error = "This email is already registered!";

        } else {

            // ==========================
            // HASH PASSWORD
            // ==========================
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // ==========================
            // INSERT USER
            // ==========================
            $insert_sql = "
                INSERT INTO users
                (
                    name,
                    email,
                    password,
                    phone,
                    address,
                    role,
                    created_at
                )
                VALUES
                (
                    '$name',
                    '$email',
                    '$hashed_password',
                    '$phone',
                    '$address',
                    'user',
                    NOW()
                )
            ";

            $insert_query = mysqli_query($conn, $insert_sql);

            // ==========================
            // INSERT SUCCESS
            // ==========================
            if ($insert_query) {

                $_SESSION['user_id'] = mysqli_insert_id($conn);
                $_SESSION['role'] = 'user';
                $_SESSION['user_name'] = $name;

                header("Location: ../user/my_bookings.php");
                exit();

            } else {

                $error = "Insert Failed: " . mysqli_error($conn);
            }
        }
    }
}

// ==========================
// HEADER INCLUDE
// ==========================
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
    min-height:90vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:40px 20px;
    font-family:'Inter',sans-serif;
}

.auth-card{
    background:white;
    width:100%;
    max-width:500px;
    padding:40px;
    border-radius:20px;
    box-shadow:0 15px 35px rgba(15,23,42,0.05);
    border:1px solid #e2e8f0;
}

.brand-logo{
    text-align:center;
    font-size:28px;
    font-weight:700;
    margin-bottom:25px;
    color:var(--dark);
}

.brand-logo span{
    color:var(--primary);
}

.form-group{
    margin-bottom:15px;
}

.form-group label{
    display:block;
    margin-bottom:6px;
    font-size:12px;
    font-weight:700;
    color:var(--dark);
    text-transform:uppercase;
}

.form-control{
    width:100%;
    padding:12px 15px;
    border:1px solid #e2e8f0;
    border-radius:10px;
    font-size:14px;
    box-sizing:border-box;
}

.form-control:focus{
    outline:none;
    border-color:var(--primary);
    box-shadow:0 0 0 4px rgba(37,99,235,0.1);
}

.btn-auth{
    width:100%;
    padding:15px;
    background:var(--dark);
    color:white;
    border:none;
    border-radius:10px;
    cursor:pointer;
    font-size:15px;
    font-weight:600;
    transition:0.3s;
    margin-top:15px;
}

.btn-auth:hover{
    background:var(--primary);
}

.alert{
    padding:15px;
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

        <!-- REGISTER FORM -->
        <form method="POST" action="">

            <div class="form-group">
                <label>Full Name</label>
                <input
                    type="text"
                    name="name"
                    class="form-control"
                    placeholder="Your full name"
                    required
                >
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">

                <div class="form-group">
                    <label>Email Address</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="name@example.com"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        placeholder="01XXXXXXXXX"
                        required
                    >
                </div>

            </div>

            <div class="form-group">
                <label>Address</label>
                <input
                    type="text"
                    name="address"
                    class="form-control"
                    placeholder="Street, City, Area"
                    required
                >
            </div>

            <div class="form-group">
                <label>Password</label>
                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="••••••••"
                    required
                >
            </div>

            <button type="submit" class="btn-auth">
                Create Royal Account
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
            Already have an account?

            <a
                href="login.php"
                style="
                    color:var(--primary);
                    text-decoration:none;
                    font-weight:600;
                "
            >
                Sign In
            </a>
        </div>

    </div>

</div>

<?php include('../../includes/footer.php'); ?>