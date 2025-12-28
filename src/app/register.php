<?php
require_once(__DIR__ . "/../config/config.php");


// إذا كان المستخدم مسجلاً دخوله بالفعل، يتم توجيهه إلى لوحة التحكم
if (isLoggedIn()) {
    redirect('dashboard.php');
}

$name = $email = $password = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // التحقق من المدخلات
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

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
        $errors[] = "يجب أن تتكون كلمة المرور من 6 أحرف على الأقل.";
    }

    // التحقق مما إذا كان البريد الإلكتروني مستخدماً من قبل
    if (empty($errors)) {
        $sql = "SELECT id FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows == 1) {
                $errors[] = "هذا البريد الإلكتروني مسجل بالفعل.";
            }
            $stmt->close();
        }
    }

    // إذا لم تكن هناك أخطاء، يتم إدراج المستخدم في قاعدة البيانات
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // تشفير كلمة المرور
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sss", $name, $email, $hashed_password);
            if ($stmt->execute()) {
                redirect('login.php?status=registered');
            } else {
                $errors[] = "حدث خطأ ما. الرجاء المحاولة مرة أخرى.";
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
    <title>إنشاء حساب جديد</title>
   
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .wrapper { width: 400px; padding: 20px; margin: 100px auto; background: #fff; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1 ); }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>إنشاء حساب جديد</h2>
        <p>الرجاء ملء هذا النموذج لإنشاء حساب.</p>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>الاسم</label>
                <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
            </div>
            <div class="form-group">
                <label>البريد الإلكتروني</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
            </div>
            <div class="form-group">
                <label>كلمة المرور</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="إنشاء حساب">
            </div>
            <p>لديك حساب بالفعل؟ <a href="login.php">سجل الدخول الآن</a>.</p>
        </form>
    </div>
</body>
</html>
