<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('SITEURL')) {
    define('SITEURL', 'http://localhost/event_management/');
}
define('LOCALHOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'event_management');

$conn = mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$db_select = mysqli_select_db($conn, DB_NAME);

if (!$db_select) {
    die("Database failed: " . mysqli_error($conn));
}
?>