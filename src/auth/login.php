<?php
require_once __DIR__ . '/../config/config.php';

// إذا كان المستخدم مسجلاً دخوله بالفعل
if (isLoggedIn()) {
    redirect('/app/dashboard.php');
}

$email = $password = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email)) {
        $errors[] = "الرجاء إدخال البريد الإلكتروني.";
    }
    if (empty($password)) {
        $errors[] = "الرجاء إدخال كلمة المرور.";
    }

    if (empty($errors)) {
        $sql = "SELECT id, name, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $name, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $name;
                redirect('/app/dashboard.php');
            } else {
                $errors[] = "كلمة المرور غير صحيحة.";
            }
        } else {
            $errors[] = "البريد الإلكتروني غير مسجل.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body style="background:#f8f9fa">
<div class="container mt-5" style="max-width:400px">
    <h3 class="text-center mb-3">تسجيل الدخول</h3>

    <?php if (isset($_GET['status']) && $_GET['status'] === 'registered'): ?>
        <div class="alert alert-success">تم إنشاء الحساب بنجاح</div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <div><?= $error ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label>البريد الإلكتروني</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>">
        </div>

        <div class="form-group">
            <label>كلمة المرور</label>
            <input type="password" name="password" class="form-control">
        </div>

        <button class="btn btn-primary btn-block">تسجيل الدخول</button>
        <p class="mt-3 text-center">
            ليس لديك حساب؟
            <a href="/app/register.php">إنشاء حساب</a>
        </p>
    </form>
</div>
</body>
</html>
