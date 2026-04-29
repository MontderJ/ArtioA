<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "art_gallery");

if (isset($_POST['login'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$user'");
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($pass, $row['password'])) { // التحقق من كلمة المرور المشفرة
            $_SESSION['admin_user'] = $row['username'];
            header("Location: index.php");
        } else {
            $error = "كلمة المرور خاطئة!";
        }
    } else {
        $error = "المستخدم غير موجود!";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول - Artio</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-box { max-width: 400px; margin: 100px auto; background: var(--card-bg); padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px var(--card-shadow); }
        input { width: 100%; padding: 12px; margin: 10px 0; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-color); box-sizing: border-box;}
        .auth-btn { width: 100%; background: #3498db; color: white; border: none; padding: 12px; border-radius: 8px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>تسجيل الدخول 🔑</h2>
        <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="اسم المستخدم" required>
            <input type="password" name="password" placeholder="كلمة المرور" required>
            <button type="submit" name="login" class="auth-btn">دخول</button>
        </form>
    </div>
</body>
</html>