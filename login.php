<?php
require_once 'config.php';

// إذا كان المستخدم مسجلاً دخوله بالفعل، يتم توجيهه إلى لوحة التحكم
if (isLoggedIn()) {
    redirect('dashboard.php');
}

$email = $password = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $name, $hashed_password);
                if ($stmt->fetch()) {
                    if (password_verify($password, $hashed_password)) {
                        // كلمة المرور صحيحة، ابدأ جلسة جديدة
                        $_SESSION['user_id'] = $id;
                        $_SESSION['user_name'] = $name;
                        redirect('dashboard.php');
                    } else {
                        $errors[] = "كلمة المرور التي أدخلتها غير صحيحة.";
                    }
                }
            } else {
                $errors[] = "لم يتم العثور على حساب بهذا البريد الإلكتروني.";
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body { background-color: #f8f9fa; }
        .wrapper { width: 400px; padding: 20px; margin: 100px auto; background: #fff; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1 ); }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>تسجيل الدخول</h2>
        <p>الرجاء ملء بيانات الاعتماد الخاصة بك لتسجيل الدخول.</p>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'registered'): ?>
            <div class="alert alert-success">تم إنشاء حسابك بنجاح. يمكنك الآن تسجيل الدخول.</div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>البريد الإلكتروني</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
            </div>
            <div class="form-group">
                <label>كلمة المرور</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="تسجيل الدخول">
            </div>
            <p>ليس لديك حساب؟ <a href="register.php">أنشئ حساباً الآن</a>.</p>
        </form>
    </div>
</body>
</html>
