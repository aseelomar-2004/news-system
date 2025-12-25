<?php
require_once 'config.php';

// إلغاء كافة متغيرات الجلسة
$_SESSION = [];

// تدمير الجلسة
session_destroy();

// إعادة التوجيه إلى صفحة تسجيل الدخول
redirect('login.php');
?>
