<?php
require_once __DIR__ . '/../auth/auth.php';

// جلب جميع الفئات من قاعدة البيانات
$sql = "SELECT * FROM categories ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>عرض جميع الفئات</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">


</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="dashboard.php">نظام الأخبار</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="dashboard.php">العودة للرئيسية</a></li>
                <li class="nav-item"><a class="nav-link" href="add_category.php">إضافة فئة جديدة</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>عرض جميع الفئات</h2>
        <table class="table table-bordered table-striped mt-3">
            <thead class="thead-light">
                <tr>
                    <th>المعرف (ID )</th>
                    <th>اسم الفئة</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" class="text-center">لا توجد فئات لعرضها.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php $conn->close(); ?>

