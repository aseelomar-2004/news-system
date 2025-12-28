<?php
// بدء الجلسة (Session) لتخزين بيانات المستخدم بعد تسجيل الدخول
session_start();

// إعدادات قاعدة البيانات
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // اسم مستخدم قاعدة البيانات (غالباً root)
define('DB_PASSWORD', ''); // كلمة مرور قاعدة البيانات (غالباً فارغة في XAMPP)
define('DB_NAME', 'news_system');

// محاولة الاتصال بقاعدة البيانات
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// التحقق من نجاح الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// ضبط الترميز إلى UTF-8 لدعم اللغة العربية
$conn->set_charset("utf8mb4");

// دالة للتحقق مما إذا كان المستخدم مسجلاً للدخول
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// دالة لإعادة التوجيه إلى صفحة أخرى
function redirect($url) {
    header("Location: " . $url);
    exit();
}
?>
