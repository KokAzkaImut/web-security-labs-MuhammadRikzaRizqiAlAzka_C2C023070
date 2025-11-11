<?php
// sqli_secure.php - IMPLEMENTASI AMAN (Menggunakan Prepared Statements)
session_start();
if (!isset($_SESSION['user_id'])) { 
    header('Location: login.php');
    exit;
}

// SIMULASI KONEKSI DB (Ganti dengan koneksi nyata Anda)
// Anggap $mysqli adalah objek koneksi MySQLi yang sukses.
// include 'db_config.php'; 
class MockDB {
    public $error = '';  // Tambah property error untuk simulasi
    public function prepare($sql) {
        return new MockStatement();
    }
}
class MockStatement {
    private $search_id;
    public function bind_param($types, &$param) {
        $this->search_id = $param; // 'i' (integer) atau 's' (string)
    }
    public function execute() {
        return true;
    }
    public function get_result() {
        if ($this->search_id == 1) {
            return function() { return ['username' => 'admin_simulasi', 'role' => 'administrator']; };
        }
        return function() { return null; };
    }
    public function close() {
        // Method ini diperlukan untuk simulasi mysqli
        return true;
    }
}
$mysqli = new MockDB(); 
// --- AKHIR SIMULASI ---

$pesan = '';
$hasil_pencarian = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_id = $_POST['user_id'];
    
    // 🛡️ KODE AMAN: Menggunakan Prepared Statements.
    // 1. Definisikan query dengan Placeholder (?)
    $sql = "SELECT username, role FROM users WHERE user_id = ?";
    
    // Cek apakah input mengandung karakter berbahaya (bukan sanitasi utama, tapi sebagai lapisan tambahan)
    if (!is_numeric($search_id) || $search_id <= 0) {
         $pesan = "Input ID pengguna harus berupa angka positif.";
    } else {
        // 2. Persiapkan statement
        $stmt = $mysqli->prepare($sql);
        
        if ($stmt === false) {
            $pesan = "Error preparing statement: " . $mysqli->error;
        } else {
            // 3. Bind parameter (hubungkan variabel PHP ke placeholder)
            // 'i' berarti parameter yang di-bind adalah Integer. Ini memastikan PHP memperlakukannya sebagai angka.
            $stmt->bind_param("i", $search_id);
            
            // 4. Eksekusi statement
            $stmt->execute();
            
            // 5. Ambil hasil
            // --- SIMULASI HASIL (tanpa koneksi DB sebenarnya) ---
            $simulasi_result = $stmt->get_result();
            // Karena ini simulasi, kita langsung ambil data dari object yang di-return
            $row = $simulasi_result();  // Panggil langsung karena get_result() mengembalikan closure
            if ($row) {
                $hasil_pencarian[] = $row;
                $pesan = "Data pengguna ditemukan (Simulasi).";
            } else {
                $pesan = "Pengguna tidak ditemukan (Simulasi).";
            }
            
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Praktik Aman: SQL Injection Mitigasi</title>
    <link rel="stylesheet" href="style.css">
    <style> .result-table { width: 100%; border-collapse: collapse; margin-top: 20px; } .result-table th, .result-table td { border: 1px solid #ddd; padding: 8px; text-align: left; } .result-table th { background-color: #f2f2f2; } </style>
</head>
<body>
    <header style="background-color: #28a745;">
        <h1>Lingkungan AMAN: SQL Injection Mitigasi ✅</h1>
    </header>
    
    <div class="container">
        <h2>Cari Pengguna Berdasarkan ID (Aman)</h2>
        <form method="POST">
            <label for="user_id">Masukkan User ID:</label>
            <input type="text" id="user_id" name="user_id" placeholder="Coba masukkan 1, lalu coba ' OR '1'='1" required>
            <button type="submit">Cari</button>
        </form>
        
        <?php if ($pesan): ?>
            <blockquote style="margin-top: 20px; border-left: 5px solid #007bff; padding: 10px; background-color: #e9f7ff;">
                <p><strong>Status:</strong> <?php echo $pesan; ?></p>
            </blockquote>
        <?php endif; ?>

        <?php if (!empty($hasil_pencarian)): ?>
            <h3>Hasil Pencarian</h3>
            <table class="result-table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($hasil_pencarian as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p style="color: green; font-weight: bold; margin-top: 15px;">**Pemberitahuan:** Meskipun Anda memasukkan payload SQLi seperti `1' OR '1'='1`, Prepared Statements menganggap seluruh input sebagai data string tunggal, sehingga query tetap aman dan hanya mencari ID pengguna dengan nilai tersebut (yang tidak akan ditemukan).</p>
        <?php endif; ?>
        
        <br><a href="sqli_praktik.php">&larr; Kembali ke Modul Praktik</a>
    </div>
</body>
</html>