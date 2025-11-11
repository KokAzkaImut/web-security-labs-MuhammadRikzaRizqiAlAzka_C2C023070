<?php
// xss_materi.php - MATERI TEORI: Cross-Site Scripting (XSS)
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Variabel Khusus Modul
$key_modul = 'xss';
$judul_modul = 'Cross-Site Scripting (XSS) 📝';
$sub_judul = 'Mitigasi dengan Escape on Output dan CSP';
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
                <p>XSS adalah kerentanan injeksi yang memungkinkan penyerang menyisipkan skrip sisi klien (biasanya JavaScript) ke halaman web. Ketika korban mengunjungi halaman tersebut, *browser* mereka mengeksekusi skrip jahat tersebut.</p>
                <p>Dampak utama: **Pencurian *Session Cookie*** (melalui `document.cookie`), pengalihan pengguna (*phishing*), dan mengubah tampilan halaman (DOM *manipulation*). 
</p>
            </article>

            <hr>

            <article>
                <h4>2. Tiga Tipe XSS Utama</h4>
                <ul>
                    <li>**Stored XSS (Paling Berbahaya):** *Payload* disimpan secara **permanen** di *database* (misalnya di kolom komentar atau profil) dan disajikan ke setiap pengguna yang melihat halaman tersebut.</li>
                    <li>**Reflected XSS:** *Payload* berasal dari *input* di URL atau formulir, dan **langsung dipantulkan kembali** ke *output* halaman (misalnya di pesan error atau kolom pencarian).</li>
                    <li>**DOM XSS:** Kerentanan terjadi sepenuhnya di sisi klien, ketika kode JavaScript memproses data dari DOM (misalnya `location.hash` atau `document.URL`) secara tidak aman tanpa *sanitasi*.</li>
                </ul>
            </article>
            
            <hr>

            <article>
                <h4>3. Prinsip Kunci: Escape on Output ✅</h4>
                <p>Mitigasi paling mendasar adalah **Escape on Output** (*HTML Encoding*). Prinsipnya: Perlakukan semua *input* pengguna sebagai **data** murni, bukan sebagai kode HTML atau JavaScript, saat ditampilkan di *browser*.</p>
                
                <h5>Contoh Rentan:</h5>
                <pre><code>// 🛑 RENTAN!
$input = $_GET['search'];
echo "Hasil pencarian untuk: " . $input; 
// Jika $input adalah &lt;script&gt;alert(1)&lt;/script&gt;, skrip akan dieksekusi.</code></pre>

                <h5>Contoh Aman (PHP PDO):</h5>
                <p>Di PHP, fungsi **`htmlspecialchars()`** mengubah karakter HTML khusus menjadi entitas HTML (misalnya `<` menjadi `&lt;`), sehingga *browser* menampilkannya sebagai teks biasa, bukan sebagai tag:</p>
                <pre><code>// 🛡️ KODE AMAN!
$input = $_GET['search'];
echo "Hasil pencarian untuk: " . htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
// Output ke browser: Hasil pencarian untuk: &lt;script&gt;alert(1)&lt;/script&gt;</code></pre>
            </article>

            <hr>

            <article>
                <h4>4. Mitigasi Mendalam (Defense in Depth)</h4>
                <p>Mitigasi modern tidak hanya mengandalkan *escaping* tetapi juga lapisan pertahanan tambahan:</p>
                <ul>
                    <li>**Content Security Policy (CSP):** Gunakan *HTTP Header* **CSP** untuk membatasi sumber yang diizinkan untuk memuat skrip, gambar, atau *font*. Ini dapat memblokir eksekusi skrip *inline* dan mencegah pemuatan skrip dari domain penyerang.</li>
                    <li>**HttpOnly Cookie Flag:** Set *flag* **HttpOnly** pada *cookie* sesi. Ini mencegah JavaScript klien (termasuk skrip XSS) untuk mengakses *cookie* tersebut via `document.cookie`.</li>
                    <li>**Sanitasi Sisi Server:** Gunakan *library* khusus (seperti **HTML Purifier**) untuk membersihkan atau menghapus tag HTML berbahaya jika Anda harus mengizinkan beberapa tag (misalnya `<b>` atau `<i>`) pada *input* pengguna.</li>
                </ul>
            </article>
            
            <div style="text-align: center; margin-top: 40px;">
                <a href="<?php echo $link_praktik; ?>" class="button-praktik" style="font-size: 1.2em;">Siap Menguji? Mulai Praktik Lab XSS &rarr;</a>
            </div>
            
        </div>
    </div>
</body>
</html>