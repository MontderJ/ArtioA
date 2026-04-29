<?php
// الاتصال بقاعدة البيانات
$host = "localhost";
$user = "root";
$password = "";
$db_name = "art_gallery";
$conn = mysqli_connect($host, $user, $password, $db_name);

$message = "";

// استقبال البيانات عند الضغط على زر الإضافة
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $artist = mysqli_real_escape_string($conn, $_POST['artist']);
    $art_style = mysqli_real_escape_string($conn, $_POST['art_style']);

    // --- إعدادات رفع الصورة ---
    $target_dir = "uploads/"; // المجلد الذي أنشأناه
    $file_name = basename($_FILES["image_file"]["name"]); // اسم الملف الأصلي
    $unique_file_name = time() . "_" . $file_name; // إضافة وقت للاسم لمنع تكرار الأسماء
    $target_file = $target_dir . $unique_file_name; // المسار النهائي
    
    // التحقق من أن الملف المرفوع هو صورة فعلاً
    $check = getimagesize($_FILES["image_file"]["tmp_name"]);
    if($check !== false) {
        // نقل الصورة من جهازك إلى مجلد uploads
        if (move_uploaded_file($_FILES["image_file"]["tmp_name"], $target_file)) {
            
            // الصورة الآن في السيرفر، نحفظ مسارها في قاعدة البيانات
            $sql = "INSERT INTO artworks (title, artist, image_url, art_style) VALUES ('$title', '$artist', '$target_file', '$art_style')";

            if (mysqli_query($conn, $sql)) {
                $message = "<div class='success-msg'>✅ تم رفع الصورة وإضافة اللوحة بنجاح! <a href='index.php'>شاهد المعرض</a></div>";
            } else {
                $message = "<div class='error-msg'>❌ حدث خطأ في قاعدة البيانات.</div>";
            }
        } else {
            $message = "<div class='error-msg'>❌ حدث خطأ أثناء نقل الصورة للمجلد.</div>";
        }
    } else {
        $message = "<div class='error-msg'>❌ يرجى التأكد من رفع ملف صورة صحيح.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة لوحة جديدة (رفع صورة)</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f9; color: #333; margin: 0; padding: 20px; display: flex; flex-direction: column; align-items: center; }
        h1 { color: #2c3e50; }
        .form-container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        .form-group { margin-bottom: 15px; text-align: right; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="file"], select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; font-family: inherit; }
        button { width: 100%; padding: 12px; background-color: #e74c3c; color: white; border: none; border-radius: 6px; font-size: 16px; font-weight: bold; cursor: pointer; transition: background 0.3s; margin-top: 10px; }
        button:hover { background-color: #c0392b; }
        .success-msg { color: #27ae60; background: #e8f8f5; padding: 10px; border-radius: 6px; margin-bottom: 15px; border: 1px solid #27ae60; text-align: center; }
        .error-msg { color: #c0392b; background: #fdedec; padding: 10px; border-radius: 6px; margin-bottom: 15px; border: 1px solid #c0392b; text-align: center; }
        .back-link { margin-top: 20px; text-decoration: none; color: #34495e; font-weight: bold; }
    </style>
</head>
<body>

    <h1>🖼️ إضافة لوحة من جهازك</h1>

    <div class="form-container">
        <?php echo $message; ?>

        <form method="POST" action="add_art.php" enctype="multipart/form-data">
            <div class="form-group">
                <label>اسم اللوحة:</label>
                <input type="text" name="title" required placeholder="مثال: ليلة النجوم">
            </div>
            
            <div class="form-group">
                <label>اسم الفنان:</label>
                <input type="text" name="artist" required placeholder="مثال: فان جوخ">
            </div>
            
            <div class="form-group">
                <label>اختر صورة اللوحة من جهازك:</label>
                <input type="file" name="image_file" accept="image/*" required>
            </div>
            
            <div class="form-group">
                <label>النمط الفني:</label>
                <select name="art_style" required>
                    <option value="">-- اختر النمط الفني --</option>
                    <option value="Surrealism">سريالي (Surrealism)</option>
                    <option value="Post-Impressionism">ما بعد الانطباعية (Post-Impressionism)</option>
                    <option value="Renaissance">عصر النهضة (Renaissance)</option>
                    <option value="Expressionism">تعبيري (Expressionism)</option>
                    <option value="Cubism">تكعيبي (Cubism)</option>
                </select>
            </div>
            
            <button type="submit">رفع وإضافة اللوحة</button>
        </form>
    </div>

    <a href="index.php" class="back-link">⬅️ العودة للمعرض</a>

</body>
</html>