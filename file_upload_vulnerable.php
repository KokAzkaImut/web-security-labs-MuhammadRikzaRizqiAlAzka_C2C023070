<?php
// file_upload_vulnerable.php - SENGJAJA RENTAN untuk EDUKASI!
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$upload_dir = 'uploads_vulnerable/';
$pesan = '';

// Pastikan folder ada dan dapat ditulis (permissions 777 atau 755)
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['fileToUpload'])) {
    $file = $_FILES['fileToUpload'];
    $file_name = basename($file['name']);
    $target_file = $upload_dir . $file_name;
    
    // 🛑 VULNERABLE CODE 1: Validasi hanya berdasarkan ekstensi
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    // Asumsi hanya mengizinkan gambar (jpg, png)
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
    
    // 🛑 VULNERABLE CODE 2: Kurangnya validasi tipe MIME dan ukuran
    
    if (!in_array($file_ext, $allowed_ext)) {
        // Cek ekstensi SANGAT MUDAH DILEWATI
        $pesan = "<span style='color: red;'>❌ Gagal: Hanya berkas gambar yang diizinkan (.jpg, .png, .gif).</span>";
    } else {
        // Berkas dipindahkan tanpa pengecekan konten yang ketat
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            $pesan = "<span style='color: green;'>✅ Berkas <b>" . htmlspecialchars($file_name) . "</b> berhasil diunggah!</span>";
            $pesan .= "<p>Anda dapat mengaksesnya di: <a href='" . $target_file . "' target='_blank'>" . $target_file . "</a></p>";
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
    <title>Upload Rentan | AmanAh</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header style="background-color: #dc3545;">
        <h1>Lingkungan RENTAN: Vulnerable File Upload 🧪</h1>
    </header>
    
    <div class="container">
        <h2>Unggah Foto Profil (Rentan)</h2>
        <p>Fitur ini seharusnya hanya menerima berkas gambar (.jpg/.png), tetapi memiliki implementasi validasi yang lemah.</p>
        
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="fileToUpload">Pilih Berkas:</label>
            <input type="file" id="fileToUpload" name="fileToUpload" required>
            <button type="submit">Unggah Berkas</button>
        </form>
        
        <?php if ($pesan): ?>
            <blockquote style="margin-top: 20px; border-left: 5px solid #dc3545; padding: 10px; background-color: #ffe9ec;">
                <?php echo $pesan; ?>
            </blockquote>
        <?php endif; ?>

        <p style="margin-top: 20px;">**Tips Eksploitasi:**</p>
        <ul>
            <li>Coba unggah berkas `.php` dengan konten `<?php echo 'Hello World'; ?>`.</li>
            <li>Coba gunakan ekstensi ganda, misalnya `shell.php.jpg`.</li>
            <li>Coba unggah berkas `.jpg` yang di dalamnya mengandung PHP Web Shell.</li>
        </ul>
        
        <br><a href="file_upload_praktik.php">&larr; Kembali</a>
    </div>
</body>
</html>