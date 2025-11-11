<?php
// xss_secure.php - IMPLEMENTASI AMAN (Menggunakan Escape on Output)
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$query = isset($_GET['q']) ? $_GET['q'] : '';
$pesan_error = '';

if ($query) {
    // 🛡️ KODE AMAN: Gunakan htmlspecialchars() pada SEMUA data input sebelum output.
    // Fungsi ini akan mengubah < menjadi &lt; dan > menjadi &gt;, menetralkan skrip.
    $safe_query = htmlspecialchars($query, ENT_QUOTES, 'UTF-8');
    
    // Output yang aman
    $pesan_error = "Hasil pencarian untuk: " . $safe_query . " tidak ditemukan."; 
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>XSS Aman | AmanAh</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header style="background-color: #28a745;">
        <h1>Lingkungan AMAN: Reflected XSS Mitigasi ✅</h1>
    </header>
    
    <div class="container">
        <h2>Simulasi Pencarian (Aman)</h2>
        <form action="" method="GET">
            <label for="q">Cari Materi:</label>
            <input type="text" id="q" name="q" placeholder="Coba masukkan payload XSS di sini" required>
            <button type="submit">Cari</button>
        </form>
        
        <?php if ($pesan_error): ?>
            <blockquote style="margin-top: 20px; border-left: 5px solid #28a745; padding: 10px; background-color: #e6ffed;">
                <p><strong>Pesan Sistem:</strong> <?php echo $pesan_error; ?></p>
            </blockquote>
        <?php endif; ?>

        <p style="color: green; font-weight: bold; margin-top: 15px;">**Pemberitahuan:** Meskipun Anda memasukkan payload XSS, browser Anda tidak akan mengeksekusinya karena karakter `<` dan `>` sudah diubah menjadi entitas HTML (`&lt;` dan `&gt;`).</p>
        
        <br><a href="xss_praktik.php">&larr; Kembali</a>
    </div>
</body>
</html>