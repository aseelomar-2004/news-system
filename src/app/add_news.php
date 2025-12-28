<?php
require_once __DIR__ . '/../auth/auth.php';


// جلب الفئات لعرضها في القائمة المنسدلة
$categories_sql = "SELECT * FROM categories ORDER BY name ASC";
$categories_result = $conn->query($categories_sql);

$title = $content = $category_id = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // جمع البيانات من النموذج
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = trim($_POST['category_id']);
    $user_id = $_SESSION['user_id'];
    $image_name = '';

    // التحقق من المدخلات
    if (empty($title)) $errors[] = "عنوان الخبر مطلوب.";
    if (empty($content)) $errors[] = "تفاصيل الخبر مطلوبة.";
    if (empty($category_id)) $errors[] = "يجب اختيار فئة للخبر.";

    // التعامل مع رفع الصورة
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        // التأكد من وجود مجلد uploads
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        $image_name = time() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // التحقق من أن الملف هو صورة
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            $errors[] = "الملف المرفق ليس صورة.";
        }

        // التحقق من امتدادات الصور المسموح بها
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $errors[] = "عذراً، فقط ملفات JPG, JPEG, PNG & GIF مسموح بها.";
        }

        // إذا لم تكن هناك أخطاء، حاول رفع الصورة
        if (empty($errors)) {
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $errors[] = "عذراً، حدث خطأ أثناء رفع الصورة.";
            }
        }
    }

    // إذا لم تكن هناك أخطاء، قم بإدراج الخبر في قاعدة البيانات
    if (empty($errors)) {
        $sql = "INSERT INTO news (title, content, category_id, user_id, image) VALUES (?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssiis", $title, $content, $category_id, $user_id, $image_name);
            if ($stmt->execute()) {
                redirect('dashboard.php?status=added');
            } else {
                $errors[] = "حدث خطأ أثناء حفظ الخبر. الرجاء المحاولة مرة أخرى.";
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
        <h2>إضافة خبر جديد</h2>
        <p>املأ النموذج التالي لإضافة خبر جديد إلى النظام.</p>

        <?php if (!empty($errors )): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
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
                <label for="image">صورة الخبر (اختياري)</label>
                <input type="file" name="image" id="image" class="form-control-file">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="إضافة الخبر">
            </div>
        </form>
    </div>
</body>
</html>
