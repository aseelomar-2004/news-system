<?php
require_once __DIR__ . '/../auth/auth.php';
 // يتضمن config.php ويتحقق من تسجيل الدخول

// جلب جميع الأخبار غير المحذوفة من قاعدة البيانات مع اسم الفئة واسم المستخدم
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <style>
        .main-nav a { margin: 0 10px; }
        .welcome-msg { margin-bottom: 20px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="dashboard.php">نظام الأخبار</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="add_news.php">إضافة خبر</a></li>
                <li class="nav-item"><a class="nav-link" href="add_category.php">إضافة فئة</a></li>
                <li class="nav-item"><a class="nav-link" href="view_categories.php">عرض الفئات</a></li>
                <li class="nav-item"><a class="nav-link" href="deleted_news.php">الأخبار المحذوفة</a></li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">تسجيل الخروج</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="welcome-msg">
            <h3>أهلاً بك, <?php echo htmlspecialchars($_SESSION['user_name'] ); ?>!</h3>
            <p>هذه هي لوحة التحكم الرئيسية. من هنا يمكنك إدارة الأخبار والفئات.</p>
        </div>

        <hr>

        <h4>عرض جميع الأخبار</h4>
        
        <?php if (isset($_GET['status']) && $_GET['status'] == 'deleted'): ?>
            <div class="alert alert-success">تم حذف الخبر بنجاح.</div>
        <?php elseif (isset($_GET['status']) && $_GET['status'] == 'updated'): ?>
            <div class="alert alert-success">تم تعديل الخبر بنجاح.</div>
        <?php elseif (isset($_GET['status']) && $_GET['status'] == 'added'): ?>
            <div class="alert alert-success">تمت إضافة الخبر بنجاح.</div>
        <?php endif; ?>

        <table class="table table-bordered table-striped">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>صورة الخبر</th>
                    <th>عنوان الخبر</th>
                    <th>الفئة</th>
                    <th>الناشر</th>
                    <th>تاريخ النشر</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td>
                                <?php if (!empty($row['image'])): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="صورة الخبر" width="100">
                                <?php else: ?>
                                    لا توجد صورة
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                            <td>
                                <a href="edit_news.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">تعديل</a>
                                <a href="delete_news.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذا الخبر؟')">حذف</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">لا توجد أخبار لعرضها حالياً.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php $conn->close(); ?>
