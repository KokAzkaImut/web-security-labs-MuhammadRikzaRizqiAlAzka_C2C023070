<?php
// file_upload_praktik.php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Praktik: File Upload | AmanAh</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Modul Praktik: Vulnerable File Upload 📂</h1>
        <p>Lab ini mendemonstrasikan risiko mengizinkan unggahan file tanpa validasi multi-lapisan yang ketat.</p>
    </header>
    
    <div class="container">
        <h2>Pilih Lingkungan Praktik</h2>
        <p>Kerentanan File Upload terjadi ketika pembatasan tipe file (ekstensi, MIME Type) dan konten file diabaikan, memungkinkan penyerang menempatkan skrip sisi server (Web Shell).</p>

        <div style="display: flex; justify-content: space-around; margin-top: 30px;">
            
            <a href="file_upload_vulnerable.php" class="praktik-link vulnerable">
                <h3>Lingkungan RENTAN ❌</h3>
                <p>Fitur unggah file ini hanya memeriksa ekstensi secara longgar. Coba unggah file dengan ekstensi ganda atau Web Shell PHP.</p>
                <p style="font-weight: bold;">(Tujuan: Mengunggah Web Shell)</p>
            </a>

            <a href="file_upload_secure.php" class="praktik-link secure">
                <h3>Lingkungan AMAN ✅</h3>
                <p>Fitur ini menggunakan validasi MIME, konten (`getimagesize`), dan nama file unik. Coba serang, dan lihat cara kerjanya.</p>
                <p style="font-weight: bold;">(Tujuan: Mempelajari Validasi Multi-lapisan)</p>
            </a>
        </div>
        
        <br><br><a href="index.php">&larr; Kembali ke Beranda</a>
    </div>
</body>
</html>