<?php
$host = "localhost";
$user = "root";
$password = "";

// الاتصال بخادم MySQL بدون تحديد قاعدة بيانات
$conn = mysqli_connect($host, $user, $password);

if (!$conn) {
    die("فشل الاتصال بالخادم: " . mysqli_connect_error());
}

// 1. إنشاء قاعدة البيانات إذا لم تكن موجودة
$sql_create_db = "CREATE DATABASE IF NOT EXISTS art_gallery";
if (mysqli_query($conn, $sql_create_db)) {
    echo "✅ تم إنشاء قاعدة البيانات 'art_gallery' بنجاح.<br>";
} else {
    echo "❌ خطأ في إنشاء قاعدة البيانات: " . mysqli_error($conn) . "<br>";
}

// 2. اختيار قاعدة البيانات للعمل عليها
mysqli_select_db($conn, "art_gallery");

// 3. إنشاء جدول اللوحات الفنية
$sql_create_table = "CREATE TABLE IF NOT EXISTS artworks (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    artist VARCHAR(255) NOT NULL,
    image_url TEXT NOT NULL,
    art_style VARCHAR(100) NOT NULL
)";

if (mysqli_query($conn, $sql_create_table)) {
    echo "✅ تم إنشاء جدول اللوحات 'artworks' بنجاح.<br>";
}

// 4. إدخال اللوحات التجريبية (فقط إذا كان الجدول فارغاً)
$check_empty = mysqli_query($conn, "SELECT * FROM artworks");
if (mysqli_num_rows($check_empty) == 0) {
    $sql_insert = "INSERT INTO artworks (title, artist, image_url, art_style) VALUES
    ('The Starry Night', 'Vincent van Gogh', 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/ea/Van_Gogh_-_Starry_Night_-_Google_Art_Project.jpg/800px-Van_Gogh_-_Starry_Night_-_Google_Art_Project.jpg', 'Post-Impressionism'),
    ('The Persistence of Memory', 'Salvador Dalí', 'https://upload.wikimedia.org/wikipedia/en/d/dd/The_Persistence_of_Memory.jpg', 'Surrealism'),
    ('Mona Lisa', 'Leonardo da Vinci', 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/ec/Mona_Lisa%2C_by_Leonardo_da_Vinci%2C_from_C2RMF_retouched.jpg/687px-Mona_Lisa%2C_by_Leonardo_da_Vinci%2C_from_C2RMF_retouched.jpg', 'Renaissance')";
    
    if (mysqli_query($conn, $sql_insert)) {
        echo "✅ تم إدخال اللوحات الفنية بنجاح.<br>";
    }
} else {
    echo "ℹ️ اللوحات الفنية موجودة مسبقاً في الجدول.<br>";
}

echo "<h3>🎉 كل شيء جاهز الآن! يمكنك العودة إلى ملف index.php لتشغيل الموقع.</h3>";
?>