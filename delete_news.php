<?php
require_once 'auth.php';

// التحقق من وجود معرف الخبر في الرابط
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $news_id = intval($_GET['id']);

    // تحديث حالة الخبر إلى "محذوف"
    $sql = "UPDATE news SET is_deleted = 1 WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $news_id);
        
        if ($stmt->execute()) {
            // إعادة التوجيه إلى لوحة التحكم مع رسالة نجاح
            redirect('dashboard.php?status=deleted');
        } else {
            echo "حدث خطأ ما. الرجاء المحاولة مرة أخرى.";
        }
        $stmt->close();
    }
} else {
    // إذا لم يتم توفير المعرف، أعد التوجيه
    redirect('dashboard.php');
}

$conn->close();
?>
