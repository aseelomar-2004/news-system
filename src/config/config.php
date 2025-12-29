<?php
// بدء الجلسة (Session)
session_start();

// إعدادات قاعدة البيانات (Docker → XAMPP MySQL)
define('DB_SERVER', 'host.docker.internal');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'news_system');
// الاتصال بقاعدة البيانات
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// دعم UTF-8
$conn->set_charset("utf8mb4");

// التحقق من تسجيل الدخول
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// إعادة توجيه
function redirect($url) {
    header("Location: " . $url);
    exit();
}
?>
