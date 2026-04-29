<?php
$host = "localhost";
$user = "root";
$password = "";
$db_name = "art_gallery";
$conn = mysqli_connect($host, $user, $password, $db_name);

if (!$conn) {
    die("فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الفنانون - Artio</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="style.css">
    
    <style>
        /* تنسيقات إضافية خاصة ببطاقات الفنانين فقط */
        .artist-card {
            background: var(--card-bg);
            padding: 30px 20px;
            border-radius: 16px;
            box-shadow: 0 10px 25px var(--card-shadow);
            width: 220px;
            text-align: center;
            transition: all 0.4s ease;
            border: 1px solid var(--border-color);
            text-decoration: none; /* إزالة الخط من تحت الرابط */
            display: block;
            color: var(--text-color);
        }
        .artist-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px var(--card-hover);
            border-color: #3498db; /* يتغير لون الإطار عند تمرير الماوس */
        }
        .artist-icon {
            font-size: 50px;
            margin-bottom: 15px;
        }
        .artist-name {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 12px;
        }
        .art-count {
            background: var(--filter-bg);
            color: var(--filter-text);
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
        }
    </style>
</head>
<body>

    <h1>👨‍🎨 فنانو معرض Artio</h1>

    <div class="top-bar">
        <a href="index.php" class="clear-btn">⬅️ العودة للرئيسية</a>
        <button id="themeToggle" class="theme-toggle">🌙</button>
    </div>

    <div class="gallery">
        <?php
        // سحر الـ SQL: نطلب منه جلب أسماء الفنانين (بدون تكرار) وحساب عدد اللوحات لكل فنان
        $sql = "SELECT artist, COUNT(id) as art_count FROM artworks GROUP BY artist ORDER BY artist ASC";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                // نجعل البطاقة بأكملها عبارة عن رابط ينقلنا للرئيسية ويبحث عن اسم هذا الفنان تلقائياً
                echo "<a href='index.php?search=" . urlencode($row['artist']) . "' class='artist-card'>";
                echo "<div class='artist-icon'>🎨</div>";
                echo "<div class='artist-name'>" . htmlspecialchars($row['artist']) . "</div>";
                echo "<div class='art-count'>" . $row['art_count'] . " لوحات</div>";
                echo "</a>";
            }
        } else {
            echo "<p style='font-size: 18px; color: #e74c3c; font-weight: bold;'>لا يوجد فنانون مسجلون بعد! 🧐</p>";
        }
        ?>
    </div>

    <script>
        // كود الوضع الليلي ليعمل في هذه الصفحة أيضاً ويتذكر اختيارك
        const themeToggleBtn = document.getElementById('themeToggle');
        const body = document.body;

        if (localStorage.getItem('theme') === 'dark') {
            body.classList.add('dark-mode');
            themeToggleBtn.innerText = '☀️';
        }

        themeToggleBtn.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            if (body.classList.contains('dark-mode')) {
                localStorage.setItem('theme', 'dark');
                themeToggleBtn.innerText = '☀️';
            } else {
                localStorage.setItem('theme', 'light');
                themeToggleBtn.innerText = '🌙';
            }
        });
    </script>

</body>
</html>