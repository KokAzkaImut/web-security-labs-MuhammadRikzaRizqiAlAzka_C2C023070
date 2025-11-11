<?php
// bac_materi.php - MATERI TEORI: Broken Access Control (BAC) / IDOR
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Variabel Khusus Modul
$key_modul = 'bac';
$judul_modul = 'Broken Access Control (BAC) / IDOR 🔑';
$sub_judul = 'Verifikasi Kepemilikan Data & Strategi Otorisasi';
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
                <h4>1. Definisi BAC dan IDOR</h4>
                <p><strong>Broken Access Control (BAC)</strong> adalah kegagalan aplikasi untuk menerapkan pembatasan akses berdasarkan peran atau kepemilikan. BAC masuk dalam kategori kerentanan keamanan tertinggi (OWASP Top 10).</p>
                <p><strong>IDOR (Insecure Direct Object Reference)</strong> adalah bentuk BAC yang paling umum. Ini terjadi ketika penyerang dapat mengubah parameter yang merujuk langsung ke objek internal (misalnya `?user_id=101` diubah menjadi `?user_id=102`) untuk mengakses, memodifikasi, atau menghapus data milik pengguna lain, karena aplikasi gagal memverifikasi kepemilikan data tersebut.</p>
                <p>Dampak: **Kebocoran data massal**, pengubahan data pengguna lain (privasi), dan ekskalasi hak akses.</p>
            </article>
            
            <hr>

            <article>
                <h4>2. Prinsip Kunci: Server-side Authorization ✅</h4>
                <p>Setiap kali resource diakses (melalui ID di URL, formulir, atau API), aplikasi harus menjalankan dua pemeriksaan: **Authentication** (apakah pengguna *login*?) dan **Authorization** (apakah pengguna ini **diizinkan** mengakses resource tersebut?).</p>
                <p>Ini disebut **Verifikasi Kepemilikan Data** (*Check Ownership*). 
</p>

                <h5>Kode AMAN: Verifikasi Kepemilikan di SQL</h5>
                <p>Solusi paling solid adalah memasukkan *user_id* pengguna yang sedang *login* (`$_SESSION['user_id']`) langsung ke dalam *query* SQL:</p>
                <pre><code>// 🛡️ KODE AMAN! Mencegah IDOR.
$current_user_id = $_SESSION['user_id'];
$nilai_id = $_GET['id_nilai']; // ID yang diminta pengguna

// Query hanya akan mengembalikan data jika id_nilai COCOK dengan id pengguna yang sedang login.
$stmt = $pdo->prepare('SELECT * FROM nilai 
    WHERE id_nilai = :id_nilai 
    AND user_id = :current_user_id');

$stmt->execute(['id_nilai' => $nilai_id, 'current_user_id' => $current_user_id]);
$data_nilai = $stmt->fetch();

if (!$data_nilai) {
    die("AKSES DITOLAK atau Data tidak ditemukan.");
}
</code></pre>
            </article>
            
            <hr>

            <article>
                <h4>3. Strategi Tambahan dan Pertimbangan</h4>
                <ul>
                    <li>**Gunakan UUID/GUID:** Hindari menggunakan ID *auto-increment* yang mudah ditebak (`1, 2, 3...`). Gunakan **UUID (Universally Unique Identifier)** sebagai referensi objek yang diekspos di URL untuk menyulitkan penyerang menebak ID objek lain.</li>
                    <li>**Cek Role/Hak Akses:** Selain kepemilikan, pastikan pengguna memiliki peran yang benar. Misalnya, hanya user dengan `role = 'admin'` yang boleh mengakses halaman administrasi.</li>
                    <li>**Default Deny:** Terapkan prinsip bahwa semua akses adalah terlarang secara *default*, kecuali secara eksplisit diizinkan.</li>
                </ul>
            </article>
            
            <div style="text-align: center; margin-top: 40px;">
                <a href="<?php echo $link_praktik; ?>" class="button-praktik" style="font-size: 1.2em;">Siap Menguji? Mulai Praktik Lab BAC &rarr;</a>
            </div>
            
        </div>
    </div>
</body>
</html>