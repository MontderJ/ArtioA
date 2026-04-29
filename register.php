<?php
$conn = mysqli_connect("localhost", "root", "", "art_gallery");

if (isset($_POST['register'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT); // تشفير كلمة المرور

    $sql = "INSERT INTO users (username, password) VALUES ('$user', '$pass')";
    if (mysqli_query($conn, $sql)) {
        header("Location: login.php?msg=success");
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل حساب جديد - Artio</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-box { max-width: 400px; margin: 100px auto; background: var(--card-bg); padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px var(--card-shadow); }
        input { width: 100%; padding: 12px; margin: 10px 0; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-color); box-sizing: border-box;}
        .auth-btn { width: 100%; background: #27ae60; color: white; border: none; padding: 12px; border-radius: 8px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>إنشاء حساب مدير 🔐</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="اسم المستخدم" required>
            <input type="password" name="password" placeholder="كلمة المرور" required>
            <button type="submit" name="register" class="auth-btn">تسجيل الحساب</button>
        </form>
        <p><a href="login.php" style="color: var(--text-color);">لديك حساب بالفعل؟ سجل دخولك</a></p>
    </div>
</body>
</html>