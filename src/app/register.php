<?php
require_once __DIR__ . '/../config/config.php';

// إذا كان المستخدم مسجلاً دخوله بالفعل
if (isLoggedIn()) {
    redirect('/app/dashboard.php');
}

$name = $email = $password = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // جلب البيانات
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // التحقق من المدخلات
    if (empty($name)) {
        $errors[] = "الرجاء إدخال الاسم.";
    }

    if (empty($email)) {
        $errors[] = "الرجاء إدخال البريد الإلكتروني.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "البريد الإلكتروني غير صالح.";
    }

    if (empty($password)) {
        $errors[] = "الرجاء إدخال كلمة المرور.";
    } elseif (strlen($password) < 6) {
        $errors[] = "كلمة المرور يجب أن تكون 6 أحرف على الأقل.";
    }

    // التحقق من عدم تكرار البريد الإلكتروني
    if (empty($errors)) {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "هذا البريد الإلكتروني مستخدم بالفعل.";
        }
        $stmt->close();
    }

    // إدخال المستخدم
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            redirect('/auth/login.php?status=registered');
        } else {
            $errors[] = "حدث خطأ أثناء إنشاء الحساب.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إنشاء حساب</title>
    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

<div class="container mt-5" style="max-width: 500px;">
    <h3 class="mb-4 text-center">إنشاء حساب جديد</h3>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p class="mb-1"><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label>الاسم</label>
            <input type="text" name="name" class="form-control"
                   value="<?= htmlspecialchars($name) ?>" required>
        </div>

        <div class="form-group">
            <label>البريد الإلكتروني</label>
            <input type="email" name="email" class="form-control"
                   value="<?= htmlspecialchars($email) ?>" required>
        </div>

        <div class="form-group">
            <label>كلمة المرور</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block">
            إنشاء الحساب
        </button>
    </form>

    <div class="mt-3 text-center">
        <a href="/auth/login.php">لديك حساب؟ تسجيل الدخول</a>
    </div>
</div>

</body>
</html>
