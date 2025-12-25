<?php
require_once 'auth.php';
$name = ""; $error = $success = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    if (empty($name)) { $error = "اسم الفئة مطلوب."; }
    else {
        $sql = "INSERT INTO categories (name) VALUES (?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $name);
            if ($stmt->execute()) { $success = "تمت إضافة الفئة بنجاح!"; $name = ""; } 
            else { $error = "حدث خطأ."; }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head><meta charset="UTF-8">
    <title>إضافة فئة</title>
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <a class="navbar-brand" href="dashboard.php">العودة للرئيسية</a>
    </nav>
    <div class="container mt-4">
        <h2>إضافة فئة جديدة</h2>
        <?php if($error ){echo "<div class='alert alert-danger'>$error</div>";} if($success){echo "<div class='alert alert-success'>$success</div>";} ?>
        <form action="" method="post">
            <div class="form-group">
                <label>اسم الفئة</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>" required>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="إضافة">
            </div>
        </form>
    </div>
</body>
</html>
