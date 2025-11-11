<?php
// materi.php

// 1. Mulai Sesi
session_start();

// 2. Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, redirect ke halaman login
    header('Location: login.php');
    exit;
}

$modul_id = $_GET['modul'] ?? 'sqli';

// Data konten lengkap untuk setiap modul
$konten = [
    'sqli' => [
        'judul' => 'SQL Injection (SQLi) 🛡️',
        'sub_judul' => 'Pola Rentan dan Solusi Prepared Statements',
        'detail' => [
            'Definisi' => 'Kondisi di mana input pengguna dapat mengubah struktur perintah SQL yang dieksekusi oleh database. Dampak: Kebocoran data sensitif (password hash, data pengguna) dan manipulasi data.',
            'Kode Rentan' => 'Pola umum: Menggabungkan string input langsung ke query SQL.',
            'Kode Aman (Pencegahan)' => 'Menggunakan **Prepared Statements** untuk memisahkan kode SQL dari data input. Input diperlakukan sebagai data, bukan perintah.',
            'Contoh PHP PDO' => "
// KODE AMAN: Prepared Statement
\$stmt = \$pdo->prepare('SELECT password FROM users WHERE username = :user');
\$stmt->execute(['user' => \$username_input]);
\$user = \$stmt->fetch();
",
            'Mitigasi Tambahan' => 'Terapkan **Least Privilege** pada akun database dan selalu gunakan **Hashing Password** yang kuat (misalnya `password_hash()` di PHP).'
        ]
    ],
    
    'xss' => [
        'judul' => 'Cross-Site Scripting (XSS) 📝',
        'sub_judul' => 'Mitigasi dengan Escape on Output dan CSP',
        'detail' => [
            'Definisi' => 'Input tidak terpercaya disisipkan ke halaman web, menyebabkan browser korban mengeksekusi skrip berbahaya. Dampak: Pencurian cookie/sesi, deface, keylogging.',
            'Tipe XSS' => 'Stored XSS (permanen di DB), Reflected XSS (dari URL), dan DOM XSS (di sisi klien/JavaScript).',
            'Prinsip Kunci (Pencegahan)' => 'Selalu terapkan **Escape on Output** (HTML encoding) pada semua input pengguna saat ditampilkan.',
            'Contoh PHP' => "
// KODE AMAN: HTML Escape
echo htmlspecialchars(\$input_dari_pengguna, ENT_QUOTES, 'UTF-8'); 
",
            'Mitigasi Header' => 'Gunakan **Content Security Policy (CSP)** untuk membatasi sumber yang boleh dieksekusi. Set *cookie* dengan *flag* **HttpOnly** untuk mencegah JS mencuri sesi.'
        ]
    ],
    
    'file_upload' => [
        'judul' => 'Vulnerable File Upload 📂',
        'sub_judul' => 'Prinsip Keamanan Unggahan File',
        'detail' => [
            'Penyebab Rentan' => 'Tidak memvalidasi ekstensi file, tidak memverifikasi tipe **MIME** yang sebenarnya, hanya mengandalkan validasi klien.',
            'Serangan Utama' => '**Web Shell Upload** (mengunggah file PHP berbahaya untuk mendapatkan akses remote shell ke server).',
            'Pola Aman' => '1. **Whitelist Ekstensi** (Hanya izinkan `.jpg`, `.png`). 2. Gunakan `finfo_file()` untuk verifikasi Tipe MIME Server-side. 3. Simpan di **Luar Web Root**. 4. Ganti nama file menjadi **Acak/UUID**.'
        ]
    ],
    
    'bac' => [
        'judul' => 'Broken Access Control (BAC) / IDOR 🔑',
        'sub_judul' => 'Verifikasi Kepemilikan Data',
        'detail' => [
            'Definisi' => 'Kegagalan otorisasi setelah login. **IDOR** (*Insecure Direct Object Reference*) adalah bentuk BAC di mana penyerang mengubah ID objek (misalnya `?id=101` menjadi `?id=102`) untuk mengakses data pengguna lain.',
            'Prinsip Kunci (Pencegahan)' => 'Selalu lakukan **Server-side Authorization**. Setiap *query* harus memverifikasi kepemilikan data.',
            'Contoh Kode Aman (SQL)' => "
// Pastikan ID yang diminta benar-benar milik user yang sedang login
\$stmt = \$pdo->prepare('SELECT * FROM nilai WHERE id_nilai = :id AND user_id = :current_user');
\$stmt->execute(['id' => \$nilai_id, 'current_user' => \$_SESSION['user_id']]);
",
            'Strategi Tambahan' => 'Hindari menggunakan *ID auto-increment* yang mudah ditebak, gunakan **UUID** untuk objek sensitif.'
        ]
    ],
];

// Mendapatkan data materi berdasarkan modul_id
$materi = $konten[$modul_id] ?? null;

// Jika modul tidak ditemukan, redirect atau tampilkan error
if (!$materi) {
    header('Location: index.php'); // Redirect ke beranda
    exit;
}

// Catatan: Jika Anda ingin menggunakan PDO di halaman ini, Anda juga perlu
// menambahkan 'require_once 'db_config.php';' di bagian atas, meskipun saat ini
// PDO hanya digunakan sebagai contoh string kode.

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $materi['judul']; ?> - Portal Keamanan Web</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Portal Materi Keamanan Aplikasi Web</h1>
        <p style="margin: 5px 0 0; font-size: 0.9em;">Anda sedang melihat modul: <?php echo $materi['judul']; ?></p>
    </header>
    
    <div class="container">
        <nav>
            <ul>
                <li><a href="index.php">Beranda</a></li>
                <li><a href="logout.php" style="background-color: #dc3545; color: white; border-color: #dc3545;">Logout</a></li>
            </ul>
        </nav>
        
        <div class="materi-detail">
            <h2><?php echo $materi['judul']; ?></h2>
            <h3><?php echo $materi['sub_judul']; ?></h3>
            
            <?php foreach ($materi['detail'] as $key => $value): ?>
                <article>
                    <h4><?php echo $key; ?></h4>
                    <?php 
                        // Tampilkan sebagai <pre> jika mengandung kode/newline, selain itu sebagai <p>
                        if (strpos($value, 'SELECT') !== false || strpos($value, 'prepare') !== false || strpos($value, 'echo') !== false || strpos($value, 'KODE AMAN') !== false) {
                            echo '<pre>' . htmlspecialchars(trim($value)) . '</pre>';
                        } else {
                            echo '<p>' . nl2br($value) . '</p>';
                        }
                    ?>
                </article>
            <?php endforeach; ?>
            
            <p style="margin-top: 40px; text-align: center;">&larr; <a href="index.php">Kembali ke Daftar Modul</a></p>
        </div>
    </div>
</body>
</html>