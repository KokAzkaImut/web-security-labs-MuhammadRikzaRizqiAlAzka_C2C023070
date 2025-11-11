<?php
// sqli_materi.php - MATERI TEORI: SQL Injection (SQLi)
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Variabel Khusus Modul
$key_modul = 'sqli';
$judul_modul = 'SQL Injection (SQLi) 🛡️';
$sub_judul = 'Pola Rentan dan Solusi Prepared Statements';
$link_materi = $key_modul . '_materi.php';
$link_praktik = $key_modul . '_praktik.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teori: <?php echo $judul_modul; ?> - AmanAh Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header style="background-color: var(--primary-color);">
        <h1>AmanAh - Portal Materi Keamanan Aplikasi Web</h1>
        <p>Anda sedang melihat modul: <b><?php echo $judul_modul; ?></b></p>
    </header>
    
    <div class="container">
        <nav>
            <ul>
                <li><a href="index.php">Beranda</a></li>
                <li><a href="<?php echo $link_praktik; ?>" style="background-color: var(--success-color); border-color: var(--success-color); color: white;">Mulai Praktik Lab &rarr;</a></li>
                <li><a href="logout.php" style="background-color: #dc3545; color: white; border-color: #dc3545;">Logout</a></li>
            </ul>
        </nav>
        
        <div class="materi-detail">
            <h2 style="border-bottom: 2px solid var(--primary-color); padding-bottom: 10px;"><?php echo $judul_modul; ?></h2>
            <h3><?php echo $sub_judul; ?></h3>
            
            <article>
                <h4>1. Definisi & Dampak</h4>
                <p>SQL Injection terjadi ketika *input* pengguna **mengubah struktur perintah SQL** yang dieksekusi di *database*. Dampak dari SQLi adalah serius, mulai dari kebocoran data sensitif (seperti *hash password* atau informasi pengguna lain) hingga potensi **manipulasi** atau **penghapusan** data secara keseluruhan.</p>
                <p>SQL Injection sering diklasifikasikan sebagai salah satu kerentanan paling kritis dalam OWASP Top 10.</p>
            </article>
            
            <hr>

            <article>
                <h4>2. Pola Kode RENTAN (The Danger Zone) ⚠️</h4>
                <p>Kerentanan muncul saat menggunakan fungsi yang **menggabungkan *string*** (concatenation) untuk membangun *query* SQL, di mana karakter input pengguna dapat disalahartikan sebagai kode SQL. </p>

                <h5>Contoh Rentan:</h5>
                <pre><code>// 🛑 SANGAT RENTAN! Menggunakan variabel GET langsung.
$id = $_GET['id'];
$sql = "SELECT * FROM products WHERE product_id = " . $id;

// Serangan Umum:
// Penyerang memasukkan: 1 OR 1=1 -- 
// Query yang dieksekusi di DB menjadi:
// SELECT * FROM products WHERE product_id = 1 OR 1=1; 
// Hasil: Semua data produk akan dikembalikan!
</code></pre>
            </article>

            <hr>

            <article>
                <h4>3. Kode AMAN: Solusi Prepared Statements ✅</h4>
                <p>Pencegahan paling efektif adalah menggunakan **Prepared Statements** (atau Parameterized Queries). Dengan PDO di PHP, kita memisahkan **struktur *query* SQL** dari **data input** sebelum eksekusi.</p>
                
                <h5>Bagaimana Cara Kerjanya?</h5>
                <p>Database memproses *template query* terlebih dahulu, kemudian secara terpisah menyisipkan input pengguna **hanya sebagai data**. Database akan menolak setiap upaya *input* untuk ditafsirkan sebagai perintah SQL. </p>

                <h5>Contoh Aman (PHP PDO):</h5>
                <pre><code>// 🛡️ KODE AMAN! Menggunakan Placeholder (contoh ':id')
$stmt = $pdo->prepare('SELECT * FROM products WHERE product_id = :id');
// Data pengguna dilewatkan secara terpisah melalui array execute().
$stmt->execute([':id' => $id_input]); 
$result = $stmt->fetch();
</code></pre>
            </article>
            
            <hr>

            <article>
                <h4>4. Mitigasi Tambahan (Defense in Depth)</h4>
                <ul>
                    <li>Terapkan **Least Privilege**: Pastikan akun database aplikasi hanya memiliki hak akses minimum (misalnya, hanya `SELECT`, `INSERT`, `UPDATE` untuk tabel tertentu, bukan `DROP` atau hak Admin).</li>
                    <li>Lakukan **Validasi Input**: Meskipun Prepared Statements sudah aman, validasi tipe data (misalnya memastikan `id` adalah integer) adalah praktik yang baik.</li>
                    <li>Tutup pesan **Error Database** yang detail di lingkungan produksi, karena dapat memberikan informasi skema kepada penyerang.</li>
                </ul>
            </article>

            <div style="text-align: center; margin-top: 40px;">
                <a href="<?php echo $link_praktik; ?>" class="button-praktik" style="font-size: 1.2em;">Siap Menguji? Mulai Praktik Lab SQLi &rarr;</a>
            </div>
            
        </div>
    </div>
</body>
</html>