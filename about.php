<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>عن المعرض - Artio</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="style.css">
    
    <style>
        /* تنسيقات خاصة بصفحة "عن المعرض" */
        .about-container {
            max-width: 800px;
            margin: 0 auto;
            background: var(--card-bg);
            padding: 50px 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px var(--card-shadow);
            border: 1px solid var(--border-color);
            text-align: right; /* محاذاة النص لليمين لسهولة القراءة */
            line-height: 1.8;
            font-size: 18px;
            color: var(--text-color);
        }
        
        .about-container h2 {
            text-align: center;
            color: #3498db;
            font-size: 28px;
            margin-bottom: 30px;
        }

        .about-text {
            margin-bottom: 20px;
        }

        .features {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 40px;
            text-align: center;
        }

        .feature-box {
            background: var(--filter-bg);
            color: var(--filter-text);
            padding: 20px;
            border-radius: 12px;
            flex: 1;
            min-width: 200px;
            font-weight: bold;
            transition: transform 0.3s ease;
        }

        .feature-box:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 40px;
            margin-bottom: 10px;
            display: block;
        }
    </style>
</head>
<body>

    <h1>📖 عن معرض Artio</h1>

    <div class="top-bar">
        <a href="index.php" class="clear-btn">⬅️ العودة للرئيسية</a>
        <button id="themeToggle" class="theme-toggle">🌙</button>
    </div>

    <div class="about-container">
        <h2>أهلاً بك في عالم الفن والإبداع ✨</h2>
        
        <p class="about-text">
            معرض <strong>Artio</strong> ليس مجرد موقع إلكتروني، بل هو مساحة رقمية صُممت بشغف لتجمع بين سحر الألوان وعمق المدارس الفنية المختلفة. نحن نؤمن بأن الفن لغة عالمية لا تحتاج إلى ترجمة.
        </p>
        
        <p class="about-text">
            تم تأسيس هذا المعرض ليكون منصة رائدة تتيح لعشاق الفن استكشاف لوحات من مختلف العصور والمدارس الفنية، من عصر النهضة الكلاسيكي إلى السريالية الغامضة، وحتى ما بعد الانطباعية.
        </p>

        <div class="features">
            <div class="feature-box">
                <span class="feature-icon">🌟</span>
                لوحات حصرية ومختارة
            </div>
            <div class="feature-box">
                <span class="feature-icon">🎓</span>
                تنوع المدارس الفنية
            </div>
            <div class="feature-box">
                <span class="feature-icon">🚀</span>
                تجربة مستخدم عصرية
            </div>
        </div>
    </div>

    <script>
        // كود الوضع الليلي للحفاظ على تناسق الموقع
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