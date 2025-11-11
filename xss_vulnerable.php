<?php
// xss_vulnerable.php - SENGJAJA RENTAN untuk EDUKASI!
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$query = isset($_GET['q']) ? $_GET['q'] : '';
$pesan_error = '';

if ($query) {
    // 🛑 VULNERABLE CODE: Input pengguna ($query) langsung dicetak ke HTML tanpa escaping.
    // Jika Attacker memasukkan: <script>alert('XSS berhasil!');</script>
    // Script tersebut akan dieksekusi oleh browser korban.
    $pesan_error = "Hasil pencarian untuk: " . $query . " tidak ditemukan."; 
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>XSS Rentan | AmanAh</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header style="background-color: #dc3545;">
        <h1>Lingkungan RENTAN: Reflected XSS 🧪</h1>
    </header>
    
    <div class="container">
        <h2>Simulasi Pencarian</h2>
        <form action="" method="GET">
            <label for="q">Cari Materi:</label>
            <input type="text" id="q" name="q" placeholder="Coba masukkan payload XSS di sini" required>
            <button type="submit">Cari</button>
        </form>
        
        <?php if ($pesan_error): ?>
            <blockquote style="margin-top: 20px; border-left: 5px solid #dc3545; padding: 10px; background-color: #ffe9ec;">
                <p><strong>Pesan Sistem:</strong> <?php echo $pesan_error; ?></p>
            </blockquote>
        <?php endif; ?>

        <p style="margin-top: 20px;">**Tips Eksploitasi:** Coba masukkan `123<script>alert('XSS Berhasil!')</script>` ke kolom pencarian dan lihat apa yang terjadi.</p>
        
        <br><a href="xss_praktik.php">&larr; Kembali</a>
    </div>
</body>
</html>