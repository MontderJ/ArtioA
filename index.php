<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$db_name = "art_gallery";
$conn = mysqli_connect($host, $user, $password, $db_name);

if (!$conn) {
    die("فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error());
}

$search_term = "";
if (isset($_GET['search'])) {
    $search_term = mysqli_real_escape_string($conn, $_GET['search']);
}

// جلب عدد اللوحات لكل تصنيف لعرضها على أزرار الفلتر
$counts = [];
$count_result = mysqli_query($conn, "SELECT art_style, COUNT(*) as cnt FROM artworks GROUP BY art_style");
while ($c = mysqli_fetch_assoc($count_result)) {
    $counts[$c['art_style']] = $c['cnt'];
}
$total_count = array_sum($counts);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>معرض الفنون الاحترافي - Artio</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- ========== القائمة الجانبية ========== -->
    <div id="sidebar" class="sidebar">
        <a href="javascript:void(0)" class="close-btn" id="closeSidebar">&times;</a>
        <div class="sidebar-content">
            <h2>لوحة التحكم 🎨</h2>
            <a href="index.php">🏠 الرئيسية</a>
            <a href="about.php">📖 عن المعرض</a>
            <a href="artists.php">👨‍🎨 الفنانون</a>
            <a href="#" id="showSavedBtn">🔖 اللوحات المحفوظة</a>
            <a href="contact.php">✉️ تواصل معنا</a>
            <hr style="border-color: var(--border-color); margin: 10px 0; opacity: 0.5;">
            <?php if(isset($_SESSION['admin_user'])): ?>
                <a href="logout.php" style="color: #e74c3c;">🚪 تسجيل الخروج</a>
            <?php else: ?>
                <a href="login.php" style="color: #3498db;">🔑 تسجيل الدخول للإدارة</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- ========== العنوان الرئيسي ========== -->
    <h1>🎨 Artio</h1>

    <!-- ========== الشريط العلوي ========== -->
    <div class="top-bar">
        <button id="menuToggle" class="menu-toggle">☰</button>
        <button id="themeToggle" class="theme-toggle">🌙</button>
        <?php if(isset($_SESSION['admin_user'])): ?>
            <a href="add_art.php" class="add-btn">➕ إضافة لوحة جديدة</a>
        <?php endif; ?>
        <form method="GET" action="index.php" class="search-form">
            <input type="text" name="search" class="search-input"
                   placeholder="ابحث باسم اللوحة أو الفنان..."
                   value="<?php echo htmlspecialchars($search_term); ?>">
            <button type="submit" class="search-btn">🔍 بحث</button>
            <?php if ($search_term != ""): ?>
                <a href="index.php" class="clear-btn">❌</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- ========== أزرار الفلتر مع عداد اللوحات ========== -->
    <div class="filters">
        <button class="filter-btn" data-filter="all">
            عرض الكل <span class="filter-count"><?php echo $total_count; ?></span>
        </button>
        <button class="filter-btn" data-filter="Surrealism">
            سريالي <span class="filter-count"><?php echo $counts['Surrealism'] ?? 0; ?></span>
        </button>
        <button class="filter-btn" data-filter="Post-Impressionism">
            ما بعد الانطباعية <span class="filter-count"><?php echo $counts['Post-Impressionism'] ?? 0; ?></span>
        </button>
        <button class="filter-btn" data-filter="Renaissance">
            عصر النهضة <span class="filter-count"><?php echo $counts['Renaissance'] ?? 0; ?></span>
        </button>
    </div>

    <?php if ($search_term == ""): ?>
        <p id="welcomeMessage" style="font-size:22px; color:var(--text-color); margin-top:40px; font-weight:bold; width:100%; text-align:center; opacity:0.7; transition:all 0.3s ease;">
            👈 يرجى اختيار تصنيف من الفلاتر بالأعلى لعرض اللوحات...
        </p>
    <?php endif; ?>

    <!-- ========== المعرض ========== -->
    <div class="gallery">
        <?php
        if ($search_term != "") {
            $sql = "SELECT * FROM artworks WHERE title LIKE '%$search_term%' OR artist LIKE '%$search_term%' ORDER BY id DESC";
        } else {
            $sql = "SELECT * FROM artworks ORDER BY id DESC";
        }

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $display_style = ($search_term != "") ? "" : "style='display:none; opacity:0; transform:scale(0.8);'";
                $title   = htmlspecialchars($row['title']);
                $artist  = htmlspecialchars($row['artist']);
                $style   = htmlspecialchars($row['art_style']);
                $img_url = htmlspecialchars($row['image_url']);

                echo "<div class='art-card' data-style='$style' $display_style>";
                echo "<div class='art-image-wrapper'>";
                echo "<img src='$img_url' alt='$title' data-artist='$artist' data-style='$style' data-img='$img_url'>";
                echo "<div class='art-overlay'>";
                echo "<h3>$title</h3>";
                echo "<p>الفنان: $artist</p>";
                echo "<span class='style-badge-small'>$style</span>";
                echo "</div></div>";

                if (isset($_SESSION['admin_user'])) {
                    echo "<div class='actions'>";
                    echo "<a href='edit_art.php?id=" . $row['id'] . "' class='btn edit-btn'>✏️ تعديل</a>";
                    echo "<a href='delete_art.php?id=" . $row['id'] . "' class='btn delete-btn swal-delete'>🗑️ حذف</a>";
                    echo "</div>";
                }
                echo "</div>";
            }
        } else {
            echo "<p style='font-size:18px; color:#e74c3c; font-weight:bold; text-align:center; width:100%;'>لم يتم العثور على نتائج!</p>";
        }
        ?>
    </div>

    <!-- ========== نافذة تكبير الصورة (Modal) ========== -->
    <div id="imageModal" class="modal">
        <span class="close-modal">&times;</span>
        <div class="modal-body">
            <div class="modal-image-container">
                <img id="expandedImg" src="" alt="">
            </div>
            <div class="modal-info-container">
                <h2 id="modalTitle">عنوان اللوحة</h2>
                <p class="modal-artist">الفنان: <span id="modalArtist">اسم الفنان</span></p>
                <span id="modalStyle" class="style-badge-small">التصنيف</span>
                <div class="modal-description">
                    <p>هذا العمل الفني يعتبر من الإضافات المميزة للمعرض. يعكس مهارة الفنان في استخدام الألوان وتجسيد التفاصيل بدقة عالية.</p>
                </div>
                <div class="modal-buttons">
                    <button class="interaction-btn like-btn"  id="likeBtn">🤍 إعجاب</button>
                    <button class="interaction-btn share-btn" id="shareBtn">📤 مشاركة</button>
                    <button class="interaction-btn save-btn"  id="saveBtn">🔖 حفظ</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== لوحة اللوحات المحفوظة ========== -->
    <div id="savedPanel">
        <div class="saved-panel-body">
            <span class="close-saved" id="closeSaved">&times;</span>
            <h2>🔖 اللوحات المحفوظة</h2>
            <div id="savedGrid" class="saved-grid"></div>
        </div>
    </div>

    <!-- ========== زر العودة للأعلى ========== -->
    <button id="backToTop" title="العودة للأعلى">↑</button>

    <!-- ========== JavaScript ========== -->
    <script>

    // ===== الفلاتر =====
    const filterBtns   = document.querySelectorAll('.filter-btn');
    const artCards     = document.querySelectorAll('.art-card');
    const welcomeMsg   = document.getElementById('welcomeMessage');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            if (welcomeMsg) {
                welcomeMsg.style.opacity = '0';
                setTimeout(() => welcomeMsg.style.display = 'none', 300);
            }
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const val = btn.getAttribute('data-filter');
            artCards.forEach(card => {
                if (val === 'all' || card.getAttribute('data-style') === val) {
                    card.style.display = 'block';
                    setTimeout(() => { card.style.opacity = '1'; card.style.transform = 'scale(1)'; }, 50);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.8)';
                    setTimeout(() => card.style.display = 'none', 400);
                }
            });
        });
    });

    // ===== الوضع الليلي =====
    const themeBtn = document.getElementById('themeToggle');
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-mode');
        themeBtn.innerText = '☀️';
    }
    themeBtn.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        const isDark = document.body.classList.contains('dark-mode');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        themeBtn.innerText = isDark ? '☀️' : '🌙';
    });

    // ===== القائمة الجانبية =====
    document.getElementById('menuToggle').addEventListener('click', () => {
        document.getElementById('sidebar').style.width = "280px";
    });
    document.getElementById('closeSidebar').addEventListener('click', () => {
        document.getElementById('sidebar').style.width = "0";
    });

    // ===== حذف مع SweetAlert2 =====
    document.querySelectorAll('.swal-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            Swal.fire({
                title: 'هل أنت متأكد؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e53e3e',
                cancelButtonColor: '#718096',
                confirmButtonText: 'نعم، احذفها!',
                cancelButtonText: 'إلغاء'
            }).then(result => { if (result.isConfirmed) window.location.href = url; });
        });
    });

    // ===== زر العودة للأعلى =====
    const backToTopBtn = document.getElementById('backToTop');
    window.addEventListener('scroll', () => {
        backToTopBtn.classList.toggle('visible', window.scrollY > 300);
    });
    backToTopBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // ===== Modal =====
    const modal       = document.getElementById('imageModal');
    const expandedImg = document.getElementById('expandedImg');
    const modalTitle  = document.getElementById('modalTitle');
    const modalArtist = document.getElementById('modalArtist');
    const modalStyle  = document.getElementById('modalStyle');
    const likeBtn     = document.getElementById('likeBtn');
    const saveBtn     = document.getElementById('saveBtn');
    const shareBtn    = document.getElementById('shareBtn');

    let currentImage = {};

    document.querySelectorAll('.art-image-wrapper img').forEach(img => {
        img.addEventListener('click', function() {
            currentImage = {
                src:    this.src,
                title:  this.alt,
                artist: this.getAttribute('data-artist'),
                style:  this.getAttribute('data-style')
            };

            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('show'), 10);

            expandedImg.src      = currentImage.src;
            modalTitle.innerText = currentImage.title;
            modalArtist.innerText = currentImage.artist;
            modalStyle.innerText  = currentImage.style;

            // تحقق إذا محفوظة
            const saved = getSaved();
            const isSaved = saved.some(s => s.src === currentImage.src);
            saveBtn.innerHTML = isSaved ? '✅ محفوظة' : '🔖 حفظ';

            // تصفير الإعجاب
            likeBtn.classList.remove('liked');
            likeBtn.innerHTML = '🤍 إعجاب';
        });
    });

    likeBtn.addEventListener('click', function() {
        this.classList.toggle('liked');
        this.innerHTML = this.classList.contains('liked') ? '❤️ أعجبني' : '🤍 إعجاب';
    });

    // ===== حفظ اللوحات في localStorage =====
    function getSaved() {
        return JSON.parse(localStorage.getItem('savedArtworks') || '[]');
    }
    function setSaved(arr) {
        localStorage.setItem('savedArtworks', JSON.stringify(arr));
    }

    saveBtn.addEventListener('click', function() {
        const saved = getSaved();
        const exists = saved.findIndex(s => s.src === currentImage.src);
        if (exists === -1) {
            saved.push(currentImage);
            setSaved(saved);
            this.innerHTML = '✅ محفوظة';
            Swal.fire({ toast: true, position: 'bottom-end', icon: 'success', title: 'تم الحفظ!', showConfirmButton: false, timer: 1500 });
        } else {
            saved.splice(exists, 1);
            setSaved(saved);
            this.innerHTML = '🔖 حفظ';
            Swal.fire({ toast: true, position: 'bottom-end', icon: 'info', title: 'تم إزالة الحفظ', showConfirmButton: false, timer: 1500 });
        }
    });

    shareBtn.addEventListener('click', function() {
        if (navigator.share) {
            navigator.share({ title: currentImage.title, text: 'شاهد هذه اللوحة الرائعة في معرض Artio', url: window.location.href });
        } else {
            navigator.clipboard.writeText(window.location.href);
            Swal.fire({ toast: true, position: 'bottom-end', icon: 'success', title: 'تم نسخ الرابط!', showConfirmButton: false, timer: 1500 });
        }
    });

    // ===== عرض اللوحات المحفوظة =====
    function renderSavedPanel() {
        const saved = getSaved();
        const grid  = document.getElementById('savedGrid');
        if (saved.length === 0) {
            grid.innerHTML = '<p class="empty-saved">📭 لا توجد لوحات محفوظة بعد</p>';
        } else {
            grid.innerHTML = saved.map((item, i) => `
                <div class="saved-item">
                    <img src="${item.src}" alt="${item.title}">
                    <div class="saved-item-info">${item.title}</div>
                    <button class="remove-saved" data-index="${i}" title="إزالة">×</button>
                </div>
            `).join('');

            document.querySelectorAll('.remove-saved').forEach(btn => {
                btn.addEventListener('click', function() {
                    const arr = getSaved();
                    arr.splice(parseInt(this.getAttribute('data-index')), 1);
                    setSaved(arr);
                    renderSavedPanel();
                });
            });
        }
    }

    document.getElementById('showSavedBtn').addEventListener('click', (e) => {
        e.preventDefault();
        renderSavedPanel();
        document.getElementById('savedPanel').classList.add('show');
        document.getElementById('sidebar').style.width = "0";
    });

    document.getElementById('closeSaved').addEventListener('click', () => {
        document.getElementById('savedPanel').classList.remove('show');
    });

    // ===== إغلاق الـ Modal =====
    function closeModal() {
        modal.classList.remove('show');
        setTimeout(() => modal.style.display = 'none', 300);
    }

    document.querySelector('.close-modal').addEventListener('click', closeModal);
    modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

    </script>
</body>
</html>