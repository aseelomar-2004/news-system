<?php
require_once 'auth.php';

// جلب جميع الأخبار المحذوفة
$sql = "SELECT news.*, categories.name AS category_name, users.name AS user_name 
        FROM news 
        JOIN categories ON news.category_id = categories.id 
        JOIN users ON news.user_id = users.id 
        WHERE news.is_deleted = 1 
        ORDER BY news.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الأخبار المحذوفة</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="dashboard.php">نظام الأخبار</a>
        <ul class="navbar-nav mr-auto">
            <li class="nav-item"><a class="nav-link" href="dashboard.php">العودة للرئيسية</a></li>
        </ul>
    </nav>

    <div class="container mt-4">
        <h2>الأخبار المحذوفة</h2>
        <p>هنا قائمة بجميع الأخبار التي تم حذفها.</p>

        <table class="table table-bordered table-striped">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>عنوان الخبر</th>
                    <th>الفئة</th>
                    <th>الناشر</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0 ): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">لا توجد أخبار محذوفة لعرضها.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php $conn->close(); ?>
