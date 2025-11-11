<?php
// sqli_praktik.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Praktik: SQL Injection</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Modul Praktik: SQL Injection (SQLi) 🛡️</h1>
        <p>Tujuan: Pahami bagaimana input pengguna yang tidak disanitasi dapat mengubah query SQL database.</p>
    </header>
    
    <div class="container" style="text-align: center;">
        <h2>Pilih Lingkungan Uji</h2>
        
        <p>Gunakan lingkungan ini hanya untuk tujuan edukasi dan pengujian etis. **DILARANG** mencoba eksploitasi di sistem lain!</p>
        
        <div style="display: flex; justify-content: space-around; margin-top: 30px;">
            <a href="sqli_vulnerable.php" class="praktik-link vulnerable">
                <h3>🧪 Lingkungan RENTAN (Vulnerable)</h3>
                <p>Coba eksploitasi kerentanan SQL Injection di sini.</p>
            </a>
            
            <a href="sqli_secure.php" class="praktik-link secure">
                <h3>✅ Lingkungan AMAN (Secure)</h3>
                <p>Lihat bagaimana kode yang sama diperbaiki menggunakan Prepared Statements.</p>
            </a>
        </div>
        
        <br><br><a href="index.php">&larr; Kembali ke Beranda</a>
    </div>
</body>
</html>