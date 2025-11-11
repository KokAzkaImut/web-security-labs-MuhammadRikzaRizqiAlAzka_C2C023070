<?php
// file_upload_materi.php - MATERI TEORI: Vulnerable File Upload
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Variabel Khusus Modul
$key_modul = 'file_upload';
$judul_modul = 'Vulnerable File Upload 📂';
$sub_judul = 'Prinsip Keamanan Unggahan File (Multi-Lapisan)';
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
                <h4>1. Penyebab Kerentanan File Upload</h4>
                <p>Fitur unggah file adalah jalur langsung bagi penyerang untuk menempatkan kode di server Anda. Kerentanan terjadi karena aplikasi gagal menerapkan validasi yang ketat pada file yang diterima.</p>
                <p>Penyebab umum: Hanya mengandalkan **validasi sisi klien** (yang mudah dilewati), hanya memeriksa **ekstensi file** secara longgar, atau tidak memverifikasi **tipe MIME** yang sebenarnya dari file.</p>
            </article>
            
            <hr>

            <article>
                <h4>2. Serangan Utama: Web Shell Upload 💥</h4>
                <p>Tujuan utama penyerang adalah mengunggah file **Web Shell** (skrip sisi server, seringkali PHP, seperti `shell.php` atau `cmd.php`).  Jika file ini dieksekusi oleh server, penyerang mendapatkan kemampuan untuk menjalankan perintah sistem operasi (Remote Code Execution) dan mengendalikan server Anda.</p>
                
                <h5>Contoh Payload Sederhana:</h5>
                <pre><code>// simple.php
&lt;?php
    // Fungsi yang sangat berbahaya jika dieksekusi
    echo system($_GET['cmd']);
?&gt;</code></pre>
                <p>Jika penyerang berhasil mengunggah dan mengakses file ini, mereka dapat menjalankan perintah seperti `ls` atau `whoami` melalui parameter URL `?cmd=ls`.</p>
            </article>
            
            <hr>

            <article>
                <h4>3. Pola Keamanan Wajib (Defense in Depth) ✅</h4>
                <p>Keamanan unggahan file harus diterapkan dalam beberapa lapisan (Multi-Lapisan) di **sisi server**, karena validasi sisi klien tidak pernah cukup.</p>
                
                <ul>
                    <li>**Whitelist Ekstensi:** Hanya izinkan ekstensi yang aman dan telah ditentukan (misalnya: `.jpg`, `.png`, `.pdf`). **Jangan** menggunakan *blacklist* (mencoba melarang yang berbahaya), karena mudah dilewati.</li>
                    <li>**Validasi Tipe MIME Server-side:** Gunakan fungsi PHP seperti **`finfo_file()`** atau ekstensi `mime_content_type()` untuk memverifikasi tipe file yang sebenarnya, bukan hanya mengandalkan nilai dari browser (`$_FILES['type']`).</li>
                    <li>**Ganti Nama File:** Gunakan nama **Acak (UUID/Hash)** alih-alih nama asli, untuk mencegah serangan *path traversal* dan menebak lokasi file.</li>
                    <li>**Batasi Eksekusi:** Simpan file di folder yang **TIDAK DAPAT** dieksekusi sebagai skrip (misalnya, nonaktifkan eksekusi PHP pada direktori unggahan melalui konfigurasi web server).</li>
                    <li>**Pemeriksaan Konten:** Untuk unggahan gambar, gunakan **`getimagesize()`** untuk memastikan file tersebut adalah gambar yang valid dan bukan skrip berbahaya yang disamarkan.</li>
                </ul>
            </article>

            <div style="text-align: center; margin-top: 40px;">
                <a href="<?php echo $link_praktik; ?>" class="button-praktik" style="font-size: 1.2em;">Siap Menguji? Mulai Praktik Lab File Upload &rarr;</a>
            </div>
            
        </div>
    </div>
</body>
</html>