<?php
require_once __DIR__ . '/../auth/auth.php';

// جلب جميع الأخبار غير المحذوفة
$sql = "SELECT news.*, categories.name AS category_name, users.name AS user_name 
        FROM news 
        JOIN categories ON news.category_id = categories.id 
        JOIN users ON news.user_id = users.id 
        WHERE news.is_deleted = 0 
        ORDER BY news.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم</title>
    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="/app/dashboard.php">نظام الأخبار</a>

    <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="/app/add_news.php">إضافة خبر</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/app/add_category.php">إضافة فئة</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/app/view_categories.php">عرض الفئات</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/app/deleted_news.php">الأخبار المحذوفة</a>
            </li>
        </ul>

        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/auth/logout.php">تسجيل الخروج</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-4">

    <h3>أهلاً بك، <?= htmlspecialchars($_SESSION['user_name']) ?> 👋</h3>
    <p>هذه هي لوحة التحكم الرئيسية.</p>

    <hr>

    <?php if (isset($_GET['status'])): ?>
        <?php if ($_GET['status'] === 'added'): ?>
            <div class="alert alert-success">تمت إضافة الخبر بنجاح.</div>
        <?php elseif ($_GET['status'] === 'updated'): ?>
            <div class="alert alert-success">تم تعديل الخبر بنجاح.</div>
        <?php elseif ($_GET['status'] === 'deleted'): ?>
            <div class="alert alert-success">تم حذف الخبر بنجاح.</div>
        <?php endif; ?>
    <?php endif; ?>

    <table class="table table-bordered table-striped mt-3">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>صورة</th>
                <th>العنوان</th>
                <th>الفئة</th>
                <th>الناشر</th>
                <th>التاريخ</th>
                <th>إجراءات</th>
            </tr>
        </thead>

        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td>
                        <?php if (!empty($row['image'])): ?>
                            <img src="/uploads/<?= htmlspecialchars($row['image']) ?>" width="80">
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['category_name']) ?></td>
                    <td><?= htmlspecialchars($row['user_name']) ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td>
                        <a href="/app/edit_news.php?id=<?= $row['id'] ?>"
                           class="btn btn-sm btn-info">تعديل</a>

                        <a href="/app/delete_news.php?id=<?= $row['id'] ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('هل أنت متأكد من الحذف؟')">
                           حذف
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center">لا توجد أخبار</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php $conn->close(); ?>
