<?php
$host = "localhost";
$user = "root";
$password = "";
$db_name = "art_gallery";
$conn = mysqli_connect($host, $user, $password, $db_name);

$message = "";

// 1. جلب البيانات القديمة للوحة لكي نعرضها في النموذج
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT * FROM artworks WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    $artwork = mysqli_fetch_assoc($result);
    
    if (!$artwork) {
        die("اللوحة غير موجودة!");
    }
}

// 2. تحديث البيانات عند الضغط على زر "حفظ التعديلات"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $artist = mysqli_real_escape_string($conn, $_POST['artist']);
    $art_style = mysqli_real_escape_string($conn, $_POST['art_style']);

    $sql = "UPDATE artworks SET title='$title', artist='$artist', art_style='$art_style' WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {
        // إذا نجح التحديث، نرجعه للصفحة الرئيسية فوراً
        header("Location: index.php");
        exit();
    } else {
        $message = "<div class='error-msg'>❌ حدث خطأ: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تعديل بيانات اللوحة</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f9; color: #333; margin: 0; padding: 20px; display: flex; flex-direction: column; align-items: center; }
        h1 { color: #2980b9; }
        .form-container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        .form-group { margin-bottom: 15px; text-align: right; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; font-family: inherit; }
        button { width: 100%; padding: 12px; background-color: #2980b9; color: white; border: none; border-radius: 6px; font-size: 16px; font-weight: bold; cursor: pointer; transition: background 0.3s; margin-top: 10px; }
        button:hover { background-color: #21618c; }
        .back-link { margin-top: 20px; text-decoration: none; color: #34495e; font-weight: bold; }
    </style>
</head>
<body>

    <h1>✏️ تعديل بيانات اللوحة</h1>

    <div class="form-container">
        <?php echo $message; ?>

        <form method="POST" action="edit_art.php">
            <input type="hidden" name="id" value="<?php echo $artwork['id']; ?>">
            
            <div class="form-group">
                <label>اسم اللوحة:</label>
                <input type="text" name="title" required value="<?php echo $artwork['title']; ?>">
            </div>
            
            <div class="form-group">
                <label>اسم الفنان:</label>
                <input type="text" name="artist" required value="<?php echo $artwork['artist']; ?>">
            </div>
            
            <div class="form-group">
                <label>النمط الفني:</label>
                <select name="art_style" required>
                    <option value="Surrealism" <?php if($artwork['art_style'] == 'Surrealism') echo 'selected'; ?>>سريالي</option>
                    <option value="Post-Impressionism" <?php if($artwork['art_style'] == 'Post-Impressionism') echo 'selected'; ?>>ما بعد الانطباعية</option>
                    <option value="Renaissance" <?php if($artwork['art_style'] == 'Renaissance') echo 'selected'; ?>>عصر النهضة</option>
                    <option value="Expressionism" <?php if($artwork['art_style'] == 'Expressionism') echo 'selected'; ?>>تعبيري</option>
                    <option value="Cubism" <?php if($artwork['art_style'] == 'Cubism') echo 'selected'; ?>>تكعيبي</option>
                </select>
            </div>
            
            <button type="submit">حفظ التعديلات</button>
        </form>
    </div>

    <a href="index.php" class="back-link">⬅️ إلغاء والعودة للمعرض</a>

</body>
</html>