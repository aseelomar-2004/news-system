<?php
require_once 'config.php';

// التحقق مما إذا كان المستخدم مسجلاً للدخول
if (!isLoggedIn()) {
    // إذا لم يكن مسجلاً، يتم توجيهه إلى صفحة تسجيل الدخول
    redirect('login.php');
}
?>
