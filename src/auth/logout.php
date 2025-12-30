<?php
require_once(__DIR__ . "/../config/config.php");


// إلغاء كافة متغيرات الجلسة
$_SESSION = [];

// تدمير الجلسة
session_destroy();

// إعادة التوجيه إلى صفحة تسجيل الدخول
redirect('auth/login.php');
?>
