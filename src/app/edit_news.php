<?php
require_once __DIR__ . '/../auth/auth.php';

// التحقق من وجود معرف الخبر
if (!isset($_GET['id']) || empty($_GET['id'])) {
    redirect('/app/dashboard.php');
}

$news_id = intval($_GET['id']);

// جلب بيانات الخبر
$sql = "SELECT * FROM news WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $news_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    redirect('/app/dashboard.php');
}

$news = $result->fetch_assoc();
$stmt->close();

// جلب الفئات
$categories_sql = "SELECT * FROM categories ORDER BY name ASC";
$categories_result = $conn->query($categories_sql);

$title       = $news['title'];
$content     = $news['content'];
$category_id = $news['category_id'];
$errors = [];

// معالجة التعديل
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title       = trim($_POST['title']);
    $content     = trim($_POST['content']);
    $category_id = trim($_POST['category_id']);

    if (empty($title))       $errors[] = "عنوان الخبر مطلوب.";
    if (empty($content))     $errors[] = "تفاصيل الخبر مطلوبة.";
    if (empty($category_id)) $errors[] = "الفئة مطلوبة.";

    if (empty($errors)) {

        $sql_update = "UPDATE news 
                       SET title = ?, content = ?, category_id = ?
                       WHERE id = ?";

        if ($stmt_update = $conn->prepare($sql_update)) {
            $stmt_update->bind_param("ssii", $title, $content, $category_id, $news_id);

            if ($stmt_update->execute()) {
                redirect('/app/dashboard.php?status=updated');
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
    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="/app/dashboard.php">نظام الأخبار</a>
    <ul class="navbar-nav mr-auto">
        <li class="nav-item">
            <a class="nav-link" href="/app/dashboard.php">العودة للرئيسية</a>
        </li>
    </ul>
</nav>

<div class="container mt-4">
    <h2>تعديل الخبر</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="/app/edit_news.php?id=<?= $news_id ?>" method="post">

        <div class="form-group">
            <label>عنوان الخبر</label>
            <input type="text" name="title" class="form-control"
                   value="<?= htmlspecialchars($title) ?>" required>
        </div>

        <div class="form-group">
            <label>تفاصيل الخبر</label>
            <textarea name="content" class="form-control" rows="5" required><?= htmlspecialchars($content) ?></textarea>
        </div>

        <div class="form-group">
            <label>الفئة</label>
            <select name="category_id" class="form-control" required>
                <option value="">-- اختر فئة --</option>

                <?php while ($category = $categories_result->fetch_assoc()): ?>
                    <option value="<?= $category['id'] ?>"
                        <?= ($category_id == $category['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endwhile; ?>

            </select>
        </div>

        <div class="form-group">
            <strong>الصورة الحالية:</strong><br>

            <?php if (!empty($news['image'])): ?>
                <img src="/uploads/<?= htmlspecialchars($news['image']) ?>" width="150">
            <?php else: ?>
                لا توجد صورة
            <?php endif; ?>

            <small class="form-text text-muted">
                تعديل الصورة غير مدعوم حالياً.
            </small>
        </div>

        <button type="submit" class="btn btn-primary">
            حفظ التعديلات
        </button>

    </form>
</div>

</body>
</html>
