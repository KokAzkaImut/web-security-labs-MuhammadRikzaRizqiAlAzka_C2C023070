<?php
// xss_praktik.php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Praktik: Cross-Site Scripting (XSS) | AmanAh</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Modul Praktik: Cross-Site Scripting (XSS) 📝</h1>
        <p>Lab ini mendemonstrasikan bagaimana kode JavaScript berbahaya dapat disuntikkan ke halaman web.</p>
    </header>
    
    <div class="container">
        <h2>Pilih Lingkungan Praktik</h2>
        <p>Kerentanan XSS terjadi ketika aplikasi mencetak input pengguna langsung ke output HTML, memungkinkan skrip dieksekusi oleh browser korban.</p>

        <div style="display: flex; justify-content: space-around; margin-top: 30px;">
            
            <a href="xss_vulnerable.php" class="praktik-link vulnerable">
                <h3>Lingkungan RENTAN ❌</h3>
                <p>Fitur umpan balik pencarian di sini rentan terhadap **Reflected XSS**. Coba masukkan payload skrip dan lihat apakah ia dieksekusi.</p>
                <p style="font-weight: bold;">(Tujuan: Memahami penyuntikan skrip)</p>
            </a>

            <a href="xss_secure.php" class="praktik-link secure">
                <h3>Lingkungan AMAN ✅</h3>
                <p>Output pada fitur ini telah diperbaiki menggunakan **`htmlspecialchars()`**. Coba masukkan payload, dan lihat perbedaannya.</p>
                <p style="font-weight: bold;">(Tujuan: Mempelajari Escape on Output)</p>
            </a>
        </div>
        
        <br><br><a href="index.php">&larr; Kembali ke Beranda</a>
    </div>
</body>
</html>