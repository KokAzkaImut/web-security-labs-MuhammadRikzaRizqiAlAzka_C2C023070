<?php
// bac_praktik.php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Praktik: Access Control (IDOR) | AmanAh</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Modul Praktik: Broken Access Control (BAC) / IDOR 🔑</h1>
        <p>Lab ini mendemonstrasikan bagaimana pengguna dapat mengakses resource yang seharusnya tidak mereka miliki.</p>
    </header>
    
    <div class="container">
        <h2>Pilih Lingkungan Praktik</h2>
        <p>Broken Access Control terjadi ketika server tidak memverifikasi apakah pengguna yang terotentikasi memiliki izin yang benar untuk mengakses suatu resource, seringkali hanya dengan memodifikasi ID di URL (IDOR).</p>

        <div style="display: flex; justify-content: space-around; margin-top: 30px;">
            
            <a href="bac_vulnerable.php?id=<?php echo $_SESSION['user_id'] ?? 101; ?>" class="praktik-link vulnerable">
                <h3>Lingkungan RENTAN ❌</h3>
                <p>Halaman ini menampilkan detail profil. Coba ubah parameter `id` di URL untuk melihat data pengguna lain.</p>
                <p style="font-weight: bold;">(Tujuan: Mengeksploitasi IDOR)</p>
            </a>

            <a href="bac_secure.php?id=<?php echo $_SESSION['user_id'] ?? 101; ?>" class="praktik-link secure">
                <h3>Lingkungan AMAN ✅</h3>
                <p>Halaman ini selalu memverifikasi kepemilikan data sebelum menampilkannya. Coba serang, dan akses akan ditolak.</p>
                <p style="font-weight: bold;">(Tujuan: Mempelajari Cek Otorisasi)</p>
            </a>
        </div>
        
        <br><br><a href="index.php">&larr; Kembali ke Beranda</a>
    </div>
</body>
</html>
