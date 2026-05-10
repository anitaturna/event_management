<?php
// ============================================================
// AUTO SETUP - Event Management System
// Visit: http://localhost/event_management/setup.php
// ============================================================

// DB credentials
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'event_management_db';

$conn = mysqli_connect($host, $user, $pass);
$errors = [];
$success = [];

if ($conn) {
    // Create database
    if (mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci")) {
        $success[] = "✅ Database <strong>$dbname</strong> created/verified";
        mysqli_select_db($conn, $dbname);

        // Run SQL
        $sql_file = file_get_contents(__DIR__ . '/event_management_db.sql');
        // Split by semicolon
        $queries = array_filter(array_map('trim', explode(';', $sql_file)));
        $q_count = 0;
        foreach ($queries as $q) {
            if (!empty($q) && stripos($q, '--') !== 0) {
                if (mysqli_query($conn, $q)) $q_count++;
                else {
                    $err = mysqli_error($conn);
                    if (!empty($err) && strpos($err, 'Duplicate entry') === false && strpos($err, 'already exists') === false) {
                        $errors[] = "⚠️ Query issue: " . $err;
                    }
                }
            }
        }
        $success[] = "✅ Tables created successfully ($q_count queries executed)";
        $success[] = "✅ Admin account ready: <strong>anitaturna345@gmail.com</strong> / password: <strong>admin123</strong>";
    } else {
        $errors[] = "❌ Could not create database: " . mysqli_error($conn);
    }
} else {
    $errors[] = "❌ Cannot connect to MySQL: " . mysqli_connect_error();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Setup - Event Management</title>
<style>
body{font-family:'Segoe UI',sans-serif;background:#f1f5f9;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0}
.box{background:#fff;border-radius:16px;padding:40px;max-width:550px;width:100%;box-shadow:0 10px 30px rgba(0,0,0,.1)}
h1{color:#0f172a;font-size:24px;margin:0 0 8px}
p{color:#64748b;margin:0 0 25px;font-size:14px}
.msg{padding:12px 16px;border-radius:8px;margin-bottom:10px;font-size:14px}
.ok{background:#dcfce7;border:1px solid #86efac;color:#166534}
.err{background:#fee2e2;border:1px solid #fca5a5;color:#991b1b}
.btn{display:inline-block;margin-top:20px;padding:12px 28px;background:#2563eb;color:#fff;border-radius:8px;text-decoration:none;font-weight:700;font-size:15px}
.warn{background:#fef9c3;border:1px solid #fde047;color:#713f12;padding:12px 16px;border-radius:8px;font-size:13px;margin-top:20px}
</style>
</head>
<body>
<div class="box">
    <h1>🎉 Event Management Setup</h1>
    <p>Setting up your database automatically...</p>
    <?php foreach($success as $s): ?>
        <div class="msg ok"><?php echo $s; ?></div>
    <?php endforeach; ?>
    <?php foreach($errors as $e): ?>
        <div class="msg err"><?php echo $e; ?></div>
    <?php endforeach; ?>
    <?php if(empty($errors)): ?>
        <div class="warn">⚠️ Setup complete! Delete <code>setup.php</code> after use for security.</div>
        <a href="index.php" class="btn">🚀 Go to Website</a>
    <?php endif; ?>
</div>
</body>
</html>
