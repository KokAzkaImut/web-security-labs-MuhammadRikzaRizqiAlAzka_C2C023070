<?php
// bac_vulnerable.php - SENGJAJA RENTAN untuk EDUKASI!
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

// ID pengguna yang sedang login (Asumsi pengguna biasa, bukan admin)
$current_user_id = $_SESSION['user_id'] = 101; 
$_SESSION['username'] = "user_biasa";

// ID pengguna yang datanya ingin dilihat. Ini berasal dari URL.
$user_id_view = isset($_GET['id']) ? (int)$_GET['id'] : $current_user_id;

// SIMULASI DATA PENGGUNA (seolah-olah diambil dari DB)
$data_pengguna_simulasi = [
    101 => ['nama' => 'User Biasa', 'email' => 'biasa@example.com', 'role' => 'Standard', 'rahasia' => 'Ini data milik 101'],
    102 => ['nama' => 'User Penting', 'email' => 'penting@example.com', 'role' => 'Premium', 'rahasia' => 'Data Rahasia Milik User 102'],
    999 => ['nama' => 'Admin Utama', 'email' => 'admin@example.com', 'role' => 'Administrator', 'rahasia' => 'Data Super Rahasia Admin!']
];

$user_data = $data_pengguna_simulasi[$user_id_view] ?? null;

// 🛑 VULNERABLE CODE: Tidak ada cek otorisasi!
// Siapa pun dapat mengakses data apa pun hanya dengan mengganti `?id=`.
$status_akses = "Anda melihat profil ID: " . $user_id_view;
if ($user_id_view != $current_user_id) {
    $status_akses .= " ⚠️ **IDOR BERHASIL** (Anda berhasil melihat data pengguna lain!).";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>BAC Rentan | AmanAh</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header style="background-color: #dc3545;">
        <h1>Lingkungan RENTAN: Insecure Direct Object Reference (IDOR) 🧪</h1>
    </header>
    
    <div class="container">
        <h2>Data Profil Pengguna (ID: <?php echo $user_id_view; ?>)</h2>
        <p style="margin-top: 0;">Anda login sebagai: **<?php echo $_SESSION['username']; ?>** (ID: **<?php echo $current_user_id; ?>**)</p>
        
        <p>Akses ke data: `bac_vulnerable.php?id=<?php echo $user_id_view; ?>`</p>
        <p style="color: red; font-weight: bold;"><?php echo $status_akses; ?></p>
        
        <?php if ($user_data): ?>
            <ul>
                <li>Nama: **<?php echo $user_data['nama']; ?>**</li>
                <li>Email: <?php echo $user_data['email']; ?></li>
                <li>Role: <?php echo $user_data['role']; ?></li>
                <li>Data Rahasia: **<?php echo $user_data['rahasia']; ?>**</li>
            </ul>
        <?php else: ?>
            <p>Pengguna tidak ditemukan.</p>
        <?php endif; ?>

        <p style="margin-top: 20px;">**Tips Eksploitasi:** Coba ubah URL menjadi `bac_vulnerable.php?id=999` atau `bac_vulnerable.php?id=102`.</p>
        
        <br><a href="bac_praktik.php">&larr; Kembali</a>
    </div>
</body>
</html>