<?php
// الاتصال بقاعدة البيانات
$host = "localhost";
$user = "root";
$password = "";
$db_name = "art_gallery";
$conn = mysqli_connect($host, $user, $password, $db_name);

// التحقق من وجود رقم تعريفي (ID) للوحة المراد حذفها في الرابط
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // 1. نبحث عن مسار الصورة لكي نحذفها من مجلد uploads في جهازك
    $query = "SELECT image_url FROM artworks WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        $file_path = $row['image_url'];
        // إذا كانت الصورة مرفوعة في مجلدنا، قم بحذف الملف
        if (strpos($file_path, 'uploads/') !== false && file_exists($file_path)) {
            unlink($file_path); 
        }
    }

    // 2. حذف بيانات اللوحة من قاعدة البيانات
    $sql = "DELETE FROM artworks WHERE id = '$id'";
    mysqli_query($conn, $sql);
}

// 3. العودة فوراً إلى الصفحة الرئيسية بعد الحذف
header("Location: index.php");
exit();
?>