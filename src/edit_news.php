<?php
require_once 'auth.php';

// التحقق من وجود معرف الخبر
if (!isset($_GET['id']) || empty($_GET['id'])) {
    redirect('dashboard.php');
}

$news_id = intval($_GET['id']);

// جلب بيانات الخبر الحالي
$sql = "SELECT * FROM news WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $news_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows != 1) {
    // لم يتم العثور على الخبر
    redirect('dashboard.php');
}
$news = $result->fetch_assoc();
$stmt->close();

// جلب الفئات
$categories_sql = "SELECT * FROM categories ORDER BY name ASC";
$categories_result = $conn->query($categories_sql);

$title = $news['title'];
$content = $news['content'];
$category_id = $news['category_id'];
$errors = [];

// معالجة النموذج عند الإرسال
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = trim($_POST['category_id']);

    if (empty($title)) $errors[] = "عنوان الخبر مطلوب.";
    if (empty($content)) $errors[] = "تفاصيل الخبر مطلوبة.";
    if (empty($category_id)) $errors[] = "الفئة مطلوبة.";

    if (empty($errors)) {
        // تحديث البيانات في قاعدة البيانات
        $sql_update = "UPDATE news SET title = ?, content = ?, category_id = ? WHERE id = ?";
        if ($stmt_update = $conn->prepare($sql_update)) {
            $stmt_update->bind_param("ssii", $title, $content, $category_id, $news_id);
            if ($stmt_update->execute()) {
                redirect('dashboard.php?status=updated');
            } else {
                $errors[] = "حدث خطأ أثناء تحديث الخبر.";
            }
            $stmt_update->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تعديل الخبر</title>
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
        <h2>تعديل الخبر</h2>
        
        <?php if (!empty($errors )): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?><p><?php echo $error; ?></p><?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="edit_news.php?id=<?php echo $news_id; ?>" method="post">
            <div class="form-group">
                <label for="title">عنوان الخبر</label>
                <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($title); ?>" required>
            </div>
            <div class="form-group">
                <label for="content">تفاصيل الخبر</label>
                <textarea name="content" id="content" class="form-control" rows="5" required><?php echo htmlspecialchars($content); ?></textarea>
            </div>
            <div class="form-group">
                <label for="category_id">الفئة</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    <option value="">-- اختر فئة --</option>
                    <?php if ($categories_result->num_rows > 0): ?>
                        <?php while ($category = $categories_result->fetch_assoc()): ?>
                            <option value="<?php echo $category['id']; ?>" <?php echo ($category_id == $category['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <p><strong>الصورة الحالية:</strong></p>
                <?php if (!empty($news['image'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($news['image']); ?>" width="150">
                <?php else: ?>
                    لا توجد صورة.
                <?php endif; ?>
                <small class="form-text text-muted">ملاحظة: تعديل الصورة غير مدعوم في هذا النموذج المبسط.</small>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="حفظ التعديلات">
            </div>
        </form>
    </div>
</body>
</html>
