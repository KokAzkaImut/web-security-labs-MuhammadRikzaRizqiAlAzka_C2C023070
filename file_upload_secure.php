<?php
// file_upload_secure.php - IMPLEMENTASI AMAN (Validasi Multi-Lapisan)
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$upload_dir = 'uploads_secure/';
$pesan = '';

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['fileToUpload'])) {
    $file = $_FILES['fileToUpload'];
    $file_name_original = basename($file['name']);
    
    // Tentukan ekstensi yang aman dan gunakan nama berkas unik
    $file_ext = strtolower(pathinfo($file_name_original, PATHINFO_EXTENSION));
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
    
    // 🛡️ KODE AMAN 1: Buat Nama Berkas Unik
    // Mencegah penyerang mengetahui lokasi berkas mereka dan mencegah path traversal.
    $new_file_name = uniqid('upload_') . '.' . $file_ext;
    $target_file = $upload_dir . $new_file_name;
    
    $uploadOk = 1;

    // 🛡️ KODE AMAN 2: Batasi Ukuran Berkas (misal: maks 5MB)
    if ($file['size'] > 5000000) { 
        $pesan = "<span style='color: red;'>❌ Gagal: Ukuran berkas terlalu besar. Maksimal 5MB.</span>";
        $uploadOk = 0;
    }

    // 🛡️ KODE AMAN 3: Validasi Ekstensi dan MIME Type
    // Periksa ekstensi (Front-end check)
    if (!in_array($file_ext, $allowed_ext)) {
        $pesan = "<span style='color: red;'>❌ Gagal: Hanya ekstensi .jpg, .png, .gif yang diizinkan.</span>";
        $uploadOk = 0;
    }
    
    // Periksa TIPE MIME menggunakan fungsi fstat/finfo (Back-end check)
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    // Daftar MIME Type yang diizinkan untuk gambar
    $allowed_mime = ['image/jpeg', 'image/png', 'image/gif'];

    if (!in_array($mime_type, $allowed_mime)) {
        $pesan = "<span style='color: red;'>❌ Gagal: Tipe MIME berkas yang diunggah tidak valid (MIME: {$mime_type}).</span>";
        $uploadOk = 0;
    }
    
    // 🛡️ KODE AMAN 4: Content Check (Jika file adalah gambar, gunakan getimagesize)
    if ($uploadOk && !getimagesize($file["tmp_name"])) {
        $pesan = "<span style='color: red;'>❌ Gagal: Berkas bukan gambar yang valid.</span>";
        $uploadOk = 0;
    }

    // Pindahkan berkas hanya jika semua cek aman
    if ($uploadOk) {
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            $pesan = "<span style='color: green;'>✅ Berkas <b>" . htmlspecialchars($file_name_original) . "</b> berhasil diunggah dengan nama aman <b>" . $new_file_name . "</b>!</span>";
        } else {
            $pesan = "<span style='color: red;'>❌ Gagal mengunggah berkas. Cek izin folder.</span>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Upload Aman | AmanAh</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header style="background-color: #28a745;">
        <h1>Lingkungan AMAN: File Upload Mitigasi ✅</h1>
    </header>
    
    <div class="container">
        <h2>Unggah Foto Profil (Aman)</h2>
        <p>Aplikasi ini menggunakan validasi multi-lapisan untuk memastikan hanya berkas gambar yang aman yang diunggah.</p>
        
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="fileToUpload">Pilih Berkas:</label>
            <input type="file" id="fileToUpload" name="fileToUpload" required>
            <button type="submit">Unggah Berkas</button>
        </form>
        
        <?php if ($pesan): ?>
            <blockquote style="margin-top: 20px; border-left: 5px solid #28a745; padding: 10px; background-color: #e6ffed;">
                <?php echo $pesan; ?>
            </blockquote>
        <?php endif; ?>

        <p style="color: green; font-weight: bold; margin-top: 15px;">**Pemberitahuan:** Sistem ini mengamankan unggahan dengan: 1) Membuat nama berkas unik, 2) Memeriksa ukuran, 3) Memverifikasi tipe MIME dan ekstensi, dan 4) Memastikan berkas adalah gambar yang valid.</p>
        
        <br><a href="file_upload_praktik.php">&larr; Kembali</a>
    </div>
</body>
</html>