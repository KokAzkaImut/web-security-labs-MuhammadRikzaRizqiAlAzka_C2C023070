<?php
// sqli_vulnerable.php - SENGJAJA RENTAN untuk EDUKASI!
session_start();
if (!isset($_SESSION['user_id'])) { 
    header('Location: login.php');
    exit;
}

// Koneksi ke database (Asumsi $conn sudah terdefinisi)
// Perlu disiapkan database dummy untuk praktik ini.
// include 'db_config.php'; 

$pesan = '';
$hasil_pencarian = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_id = $_POST['user_id'];
    
    // 🛑 VULNERABLE CODE: Input pengguna langsung dimasukkan ke query SQL.
    $sql = "SELECT username, role FROM users WHERE user_id = '$search_id'";
    
    // Perhatikan: Attacker bisa memasukkan input seperti: ' OR '1'='1
    // Yang akan membuat query menjadi: 
    // SELECT username, role FROM users WHERE user_id = '' OR '1'='1'
    
    // Jalankan query (Asumsi $conn->query() ada)
    // $result = $conn->query($sql);
    
    // --- SIMULASI HASIL (tanpa koneksi DB sebenarnya) ---
    if ($search_id === "1") {
        $hasil_pencarian[] = ['username' => 'admin_simulasi', 'role' => 'administrator'];
        $pesan = "Data pengguna ditemukan (Simulasi).";
    } elseif (strpos($search_id, "' OR '1'='1") !== false) {
        $hasil_pencarian[] = ['username' => 'admin_simulasi', 'role' => 'administrator'];
        $hasil_pencarian[] = ['username' => 'user_biasa', 'role' => 'basic'];
        $pesan = "⚠️ Eksploitasi SQLi berhasil! Input Anda mengubah query dan mengembalikan semua data pengguna (Simulasi).";
    } else {
        $pesan = "Pengguna tidak ditemukan (Simulasi).";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lingkungan RENTAN: SQL Injection Demo</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .result-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        .result-table th, 
        .result-table td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left; 
        }
        .result-table th { 
            background-color: #f2f2f2; 
        }
        header {
            background-color: #dc3545 !important;
            color: white;
            padding: 1em;
            margin-bottom: 2em;
        }
        .warning-box {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 1em;
            margin-bottom: 1em;
            border-radius: 4px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 8px;
            margin-right: 10px;
            width: 200px;
        }
        button {
            padding: 8px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1>⚠️ Lingkungan RENTAN: SQL Injection</h1>
    </header>
    
    <div class="container">
        <div class="warning-box">
            <strong>Peringatan:</strong> Halaman ini sengaja dibuat rentan terhadap SQL Injection untuk tujuan edukasi.
            JANGAN gunakan kode seperti ini di produksi!
        </div>

        <h2>Cari Pengguna Berdasarkan ID (Rentan)</h2>
        <form method="POST">
            <label for="user_id">Masukkan User ID:</label>
            <input type="text" id="user_id" name="user_id" 
                   placeholder="Coba: 1 atau ' OR '1'='1" required>
            <button type="submit">Cari</button>
        </form>
        
        <?php if (!empty($pesan)): ?>
            <div class="alert <?php echo (strpos($pesan, 'SQLi') !== false) ? 'alert-danger' : 'alert-info'; ?>">
                <?php echo htmlspecialchars($pesan); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($hasil_pencarian)): ?>
            <table class="result-table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($hasil_pencarian as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['role']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div style="margin-top: 20px;">
            <h3>Query yang Dijalankan:</h3>
            <pre><?php echo htmlspecialchars($sql ?? ''); ?></pre>
        </div>
    </div>
</body>
</html>