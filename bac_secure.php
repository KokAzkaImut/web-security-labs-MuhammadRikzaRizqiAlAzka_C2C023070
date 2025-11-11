<?php
// bac_secure.php - IMPLEMENTASI AMAN (Memverifikasi Kepemilikan Data)
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

// ID pengguna yang sedang login (Asumsi pengguna biasa)
$current_user_id = $_SESSION['user_id'] = 101; 
$_SESSION['username'] = "user_biasa";
$_SESSION['role'] = "Standard"; // Role penting untuk otorisasi

// ID pengguna yang datanya ingin dilihat. Dari URL.
$user_id_view = isset($_GET['id']) ? (int)$_GET['id'] : $current_user_id;

// SIMULASI DATA PENGGUNA
$data_pengguna_simulasi = [
    101 => ['nama' => 'User Biasa', 'email' => 'biasa@example.com', 'role' => 'Standard', 'rahasia' => 'Ini data milik 101'],
    102 => ['nama' => 'User Penting', 'email' => 'penting@example.com', 'role' => 'Premium', 'rahasia' => 'Data Rahasia Milik User 102'],
    999 => ['nama' => 'Admin Utama', 'email' => 'admin@example.com', 'role' => 'Administrator', 'rahasia' => 'Data Super Rahasia Admin!']
];

// 🛡️ KODE AMAN: Cek Otorisasi!
$akses_diizinkan = false;
$status_akses = "Anda melihat profil ID: " . $user_id_view;

// Cek 1: Apakah pengguna mencoba melihat datanya sendiri?
if ($user_id_view == $current_user_id) {
    $akses_diizinkan = true;
    $status_akses .= " (Akses diizinkan: Melihat profil sendiri).";
} 
// Cek 2: Apakah pengguna adalah Administrator? (Jika ada peran admin)
// Tambahkan logika ini jika Anda ingin admin dapat melihat semua.
/* elseif ($_SESSION['role'] == "Administrator") {
    $akses_diizinkan = true;
    $status_akses .= " (Akses diizinkan: Sebagai Administrator).";
} 
*/
else {
    $status_akses = "🛑 **AKSES DITOLAK**! Anda tidak memiliki izin untuk melihat data pengguna ID: " . $user_id_view;
}

if ($akses_diizinkan) {
    $user_data = $data_pengguna_simulasi[$user_id_view] ?? null;
} else {
    $user_data = null;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>BAC Aman | AmanAh</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header style="background-color: #28a745;">
        <h1>Lingkungan AMAN: Broken Access Control Mitigasi ✅</h1>
    </header>
    
    <div class="container">
        <h2>Data Profil Pengguna</h2>
        <p style="margin-top: 0;">Anda login sebagai: **<?php echo $_SESSION['username']; ?>** (ID: **<?php echo $current_user_id; ?>**)</p>
        
        <p>Akses ke data: `bac_secure.php?id=<?php echo $user_id_view; ?>`</p>
        <p style="color: <?php echo ($akses_diizinkan ? 'green' : 'red'); ?>; font-weight: bold;"><?php echo $status_akses; ?></p>
        
        <?php if ($user_data): ?>
            <ul>
                <li>Nama: **<?php echo $user_data['nama']; ?>**</li>
                <li>Email: <?php echo $user_data['email']; ?></li>
                <li>Role: <?php echo $user_data['role']; ?></li>
                <li>Data Rahasia: **<?php echo $user_data['rahasia']; ?>**</li>
            </ul>
        <?php elseif ($akses_diizinkan): ?>
             <p>Pengguna ID **<?php echo $user_id_view; ?>** tidak ditemukan.</p>
        <?php endif; ?>

        <p style="color: green; font-weight: bold; margin-top: 15px;">**Pemberitahuan:** Meskipun Anda mengubah ID di URL, sistem akan memverifikasi bahwa ID tersebut milik Anda. Jika tidak, akses akan ditolak.</p>
        
        <br><a href="bac_praktik.php">&larr; Kembali</a>
    </div>
</body>
</html>