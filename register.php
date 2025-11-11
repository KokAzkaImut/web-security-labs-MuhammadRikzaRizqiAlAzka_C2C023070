<?php
// register.php
session_start();

// Jika user sudah login, redirect ke halaman utama
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require_once 'db_config.php'; // Sertakan konfigurasi database

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Tentukan role default (user biasa)
    $role = 'user'; 

    if (empty($username) || empty($password)) {
        $message = "Username dan password harus diisi.";
        $message_type = 'error';
    } else if (strlen($password) < 6) {
        // Validasi kekuatan password minimal
        $message = "Password minimal harus 6 karakter.";
        $message_type = 'error';
    } else {
        // 1. HASHING PASSWORD: Ubah password menjadi hash yang aman
        // menggunakan algoritma Bcrypt (default PHP)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            // 2. CEK KETERSEDIAAN USERNAME
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
            $stmt->execute(['username' => $username]);
            
            if ($stmt->rowCount() > 0) {
                $message = "Username sudah digunakan. Silakan pilih username lain.";
                $message_type = 'error';
            } else {
                // 3. INSERT DATA AMAN (Menggunakan Prepared Statement)
                $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
                $stmt->execute([
                    'username' => $username,
                    'password' => $hashed_password, // Simpan hash, BUKAN password plaintext
                    'role' => $role
                ]);

                $message = "Registrasi Berhasil! Anda sekarang dapat Login.";
                $message_type = 'success';
                // Opsional: Redirect ke halaman login
                // header('Location: login.php?registered=1');
                // exit;
            }

        } catch (PDOException $e) {
            $message = "Terjadi kesalahan database. Silakan coba lagi. (ERR: " . $e->getMessage() . ")";
            $message_type = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - AmanAh Portal</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: var(--dark-bg);
            color: #333;
        }
        .register-box {
            width: 100%;
            max-width: 450px;
            padding: 30px;
            background: var(--light-bg);
            border-radius: 10px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.5);
            border-top: 5px solid var(--secondary-color);
        }
        .register-box h2 {
            text-align: center;
            color: var(--secondary-color);
            margin-bottom: 25px;
            font-size: 1.8em;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1em;
        }
        .btn-register {
            width: 100%;
            padding: 12px;
            background-color: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn-register:hover {
            background-color: #00a0b2;
        }
        .message {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="register-box">
        <h2>Registrasi Akun Baru</h2>
        
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $message_type; ?>"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <small style="color: #6c757d;">Minimal 6 karakter.</small>
            </div>
            <button type="submit" class="btn-register">Daftar</button>
        </form>
        <p style="text-align: center; margin-top: 20px;"><a href="login.php" style="color: var(--primary-color);">Sudah punya akun? Login di sini.</a></p>
    </div>
</body>
</html>