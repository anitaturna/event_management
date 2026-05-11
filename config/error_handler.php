<?php
// ১. ব্রাউজারে ইউজারকে এরর দেখানো বন্ধ করা (যাতে সাইট হ্যাক না হয়)
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

// ২. এরর লগিং চালু করা
ini_set('log_errors', 1);

// ৩. লগ ফাইলের লোকেশন সেট করা
$log_folder = __DIR__ . '/../logs';
$log_file = $log_folder . '/system_errors.log';

// লগ ফোল্ডার না থাকলে স্বয়ংক্রিয়ভাবে তৈরি করবে
if (!file_exists($log_folder)) {
    mkdir($log_folder, 0777, true);
}

ini_set('error_log', $log_file);

// ৪. ডাটাবেজ এররগুলোকেও লগ ফাইলে পাঠানোর জন্য Exception অন করা
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>