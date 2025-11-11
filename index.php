<?php
// index.php (VERSI LENGKAP & TERBARU dengan Tautan Praktik)
session_start();

// Cek apakah user sudah login. Jika tidak, arahkan ke login.php
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$materi_list = [
    'sqli' => [
        'judul' => 'SQL Injection (SQLi) 🛡️',
        'deskripsi' => 'Eksploitasi yang terjadi saat input pengguna mengubah struktur perintah SQL. Pelajari pola kode rentan dan solusi Prepared Statements.'
    ],
    'xss' => [
        'judul' => 'Cross-Site Scripting (XSS) 📝',
        'deskripsi' => 'Kerentanan di mana skrip berbahaya disisipkan ke halaman web dan dieksekusi oleh browser korban. Kunci: Escape on Output.'
    ],
    'file_upload' => [
        'judul' => 'Vulnerable File Upload 📂',
        'deskripsi' => 'Fitur unggah yang tidak divalidasi dengan ketat, berpotensi disalahgunakan untuk mengunggah Web Shell atau file berbahaya.'
    ],
    'bac' => [
        'judul' => 'Broken Access Control (BAC) / IDOR 🔑',
        'deskripsi' => 'Kegagalan otorisasi yang memungkinkan pengguna mengakses data atau fungsi di luar batasan hak mereka (misalnya IDOR).'
    ]
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AmanAh - Portal Materi Keamanan Aplikasi Web</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>AmanAh - Portal Materi Keamanan Aplikasi Web</h1>
        <p style="margin: 5px 0 0; font-size: 0.9em;">Selamat datang, <b><?php echo htmlspecialchars($_SESSION['username']); ?></b>! (Role: <?php echo htmlspecialchars($_SESSION['role']); ?>)</p>
    </header>
    
    <div class="container">
        <nav>
            <ul>
                <li><a href="index.php">Beranda</a></li>
                <?php foreach ($materi_list as $key => $materi): 
                    // Tautan navigasi sekarang mengarah ke file materi teori
                    $link_name = str_replace(' ', '', str_replace(['(', ')', '🔑', '🛡️', '📝', '📂'], '', $materi['judul']));
                ?>
                    <li><a href="<?php echo $key; ?>_materi.php"><?php echo $link_name; ?></a></li>
                <?php endforeach; ?>
                <li><a href="logout.php" style="background-color: #dc3545; color: white; border-color: #dc3545;">Logout</a></li>
            </ul>
        </nav>
        
        <div class="materi-list">
            <h2>Daftar Modul Keamanan Utama</h2>
            
            <?php foreach ($materi_list as $key => $materi): ?>
                <article>
                    <h3><a href="<?php echo $key; ?>_materi.php"><?php echo $materi['judul']; ?></a></h3>
                    <p><?php echo $materi['deskripsi']; ?></p>
                    
                    <p>
                        <a href="<?php echo $key; ?>_praktik.php" class="button-praktik">Mulai Praktik Lab &rarr;</a>
                        
                        <a href="<?php echo $key; ?>_materi.php" class="button-materi">Baca Teori Materi</a> 
                    </p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>