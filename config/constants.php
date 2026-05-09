<?php 
// 1. Safe Session Start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Define Constants (Only once!)
if (!defined('SITEURL')) {
    define('SITEURL', 'http://localhost/event_management/');
}
define('LOCALHOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'advanced_event_management');

// 3. Database Connection Logic
// It is better to handle the connection here so you only include one file
$conn = mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$db_select = mysqli_select_db($conn, DB_NAME);

if (!$db_select) {
    die("Database selection failed: " . mysqli_error($conn));
}
?>