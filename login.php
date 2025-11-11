<?php
// login.php
session_start();

// 1. Cek: Jika user sudah login, redirect ke halaman utama (index.php)
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Sertakan konfigurasi database
require_once 'db_config.php';

$error_message = ''; // Variabel untuk menyimpan pesan error

// 2. Proses form ketika metode POST digunakan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil dan bersihkan input dari user
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validasi dasar
    if (empty($username) || empty($password)) {
        $error_message = "Username dan password harus diisi.";
    } else {
        try {
            // 3. Gunakan Prepared Statement untuk mencegah SQL Injection
            $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE username = :username");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // 4. Verifikasi password hash menggunakan password_verify()
                if (password_verify($password, $user['password'])) {
                    
                    // Otentikasi BERHASIL. Buat Sesi.
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role']; // Simpan role untuk Access Control di halaman lain

                    // Redirect ke halaman utama
                    header('Location: index.php');
                    exit;
                } else {
                    // Password salah
                    $error_message = "Username atau Password salah.";
                }
            } else {
                // Username tidak ditemukan
                $error_message = "Username atau Password salah.";
            }

        } catch (PDOException $e) {
            // Error database (catat di log, jangan tampilkan ke user di produksi)
            $error_message = "Terjadi kesalahan database. Silakan coba lagi. (ERR: " . $e->getCode() . ")";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AmanAh Portal</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Mengatur tata letak halaman */
        body {
            display: flex;
            flex-direction: column; /* Mengubah arah flex ke kolom */
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: var(--dark-bg);
            color: #333;
            margin: 0; /* Pastikan tidak ada margin default */
        }
        /* Style untuk Box Login utama */
        .login-box {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            background: var(--light-bg);
            border-radius: 12px; /* Lebih rounded */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); /* Bayangan lebih halus */
            border-top: 5px solid var(--primary-color);
            margin-bottom: 20px; /* Jarak dari tautan registrasi */
        }
        .login-box h2 {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 30px; /* Jarak lebih besar */
            font-size: 2em;
            font-weight: 700;
        }
        /* Style untuk grup form */
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #000; /* Force label text to black for username/password */
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px; /* Input lebih rounded */
            box-sizing: border-box;
            font-size: 1em;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .form-group input:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(0, 150, 167, 0.2); /* Ring focus yang lebih baik */
            outline: none;
        }
        /* Style untuk tombol Login */
        .btn-login {
            width: 100%;
            padding: 14px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.15em;
            font-weight: bold;
            letter-spacing: 0.5px;
            transition: background-color 0.3s, transform 0.2s;
        }
        .btn-login:hover {
            background-color: var(--link-hover);
            transform: translateY(-2px);
        }
        /* Style untuk pesan error */
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 25px;
            border: 1px solid #f5c6cb;
            text-align: center;
            font-weight: 500;
        }
        /* Style untuk teks demo akun */
        .demo-info {
            text-align: center; 
            font-size: 0.9em; 
            margin-top: 20px; 
            color: #6c757d;
        }
        /* Style untuk tautan registrasi di luar box */
        .register-link-container {
            text-align: center;
        }
        .register-link-container a {
            color: var(--secondary-color);
            font-weight: bold;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 6px;
            transition: color 0.3s, background-color 0.3s;
        }
        .register-link-container a:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Akses AmanAh Portal</h2>
        
        <?php if (!empty($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-login">Login</button>
        </form>
    </div>

    <!-- Tautan Registrasi yang lebih rapi -->
    <div class="register-link-container">
        <a href="register.php">Buat Akun Baru (Registrasi)</a>
    </div>
</body>
</html>
