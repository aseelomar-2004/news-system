<?php
require_once __DIR__ . '/../auth/auth.php';

// جلب الفئات
$categories_sql = "SELECT * FROM categories ORDER BY name ASC";
$categories_result = $conn->query($categories_sql);

$title = "";
$content = "";
$category_id = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = trim($_POST['category_id']);
    $user_id = $_SESSION['user_id'];
    $image_name = "";

    // التحقق من المدخلات
    if (empty($title)) {
        $errors[] = "عنوان الخبر مطلوب.";
    }
    if (empty($content)) {
        $errors[] = "تفاصيل الخبر مطلوبة.";
    }
    if (empty($category_id)) {
        $errors[] = "يجب اختيار فئة للخبر.";
    }

    // رفع الصورة (اختياري)
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

        $upload_dir = __DIR__ . '/../uploads/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $target_path = $upload_dir . $image_name;

        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check === false) {
            $errors[] = "الملف المرفق ليس صورة.";
        }

        if (empty($errors)) {
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                $errors[] = "حدث خطأ أثناء رفع الصورة.";
            }
        }
    }

    // إدخال البيانات في قاعدة البيانات
    if (empty($errors)) {

        $sql = "INSERT INTO news (title, content, category_id, user_id, image)
                VALUES (?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param(
                "ssiis",
                $title,
                $content,
                $category_id,
                $user_id,
                $image_name
            );

            if ($stmt->execute()) {
                redirect('/app/dashboard.php?status=added');
            } else {
                $errors[] = "فشل حفظ الخبر.";
            }

            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة خبر جديد</title>
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
    <h2>إضافة خبر جديد</h2>
    <p>املأ النموذج التالي لإضافة خبر جديد.</p>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">

        <div class="form-group">
            <label>عنوان الخبر</label>
            <input type="text" name="title" class="form-control"
                   value="<?= htmlspecialchars($title) ?>" required>
        </div>

        <div class="form-group">
            <label>تفاصيل الخبر</label>
            <textarea name="content" class="form-control" rows="5"
                      required><?= htmlspecialchars($content) ?></textarea>
        </div>

        <div class="form-group">
            <label>الفئة</label>
            <select name="category_id" class="form-control" required>
                <option value="">-- اختر فئة --</option>
                <?php while ($cat = $categories_result->fetch_assoc()): ?>
                    <option value="<?= $cat['id'] ?>"
                        <?= ($category_id == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label>صورة الخبر (اختياري)</label>
            <input type="file" name="image" class="form-control-file">
        </div>

        <button type="submit" class="btn btn-primary">إضافة الخبر</button>
    </form>
</div>

</body>
</html>
