<?php
ob_start();
session_start();
require_once('../../config/db_connect.php');

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header('location:'.SITEURL.'views/auth/login.php');
    exit();
}

$log_file = __DIR__ . '/../../logs/system_errors.log';

// লগ ফাইল ক্লিয়ার করার লজিক
if(isset($_POST['clear_logs'])){
    file_put_contents($log_file, ""); // ফাইলটি খালি করে দেবে
    header('location:error_logs.php?msg=cleared');
    exit();
}

$log_contents = "No errors logged yet. System is running smoothly!";
if(file_exists($log_file) && filesize($log_file) > 0){
    // সবচেয়ে নতুন এররগুলো আগে দেখানোর জন্য
    $lines = file($log_file);
    $lines = array_reverse($lines); 
    $log_contents = implode("", $lines);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Error Logs | AT Royal Events</title>
    <?php include('../../includes/header.php'); ?>
    <style>
        .log-container {
            background: #1e293b;
            color: #34d399;
            padding: 20px;
            border-radius: 10px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            height: 500px;
            overflow-y: auto;
            white-space: pre-wrap;
            border: 1px solid #334155;
            box-shadow: inset 0 2px 10px rgba(0,0,0,0.5);
        }
        .btn-clear {
            background: #ef4444; color: white; border: none; padding: 10px 20px;
            border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s;
        }
        .btn-clear:hover { background: #dc2626; }
    </style>
</head>
<body>
    <div class="admin-shell">
        <?php include('sidebar.php'); ?>
        <main class="admin-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div>
                    <h1 style="font-family: 'Playfair Display'; font-size: 28px;">System Error Logs</h1>
                    <p style="color: #64748b; font-size: 14px;">Track background errors, database failures, and bugs.</p>
                </div>
                <form method="POST">
                    <button type="submit" name="clear_logs" class="btn-clear" onclick="return confirm('Clear all error logs?')">
                        <i class="ti ti-trash"></i> Clear Logs
                    </button>
                </form>
            </div>

            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'cleared'): ?>
                <div style="background: #dcfce7; color: #166534; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 13px;">
                    Error logs have been cleared successfully.
                </div>
            <?php endif; ?>

            <div class="log-container">
<?php echo htmlspecialchars($log_contents); ?>
            </div>
        </main>
    </div>
</body>
</html>