<?php
// db_config.php

// 1. Definisikan konstanta koneksi dengan PORT 3307
define('DB_HOST', 'localhost');
define('DB_PORT', '3307'); // Port baru
define('DB_NAME', 'amanah');
define('DB_USER', 'root');
define('DB_PASS', '');

// 2. Coba buat koneksi PDO
try {
    // Koneksi menggunakan port yang ditentukan
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    
    // Set mode error ke Exception (wajib untuk pengembangan)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set Fetch Mode Default ke Associative Array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Tampilkan pesan error jika koneksi gagal (HANYA UNTUK DEV)
    die("Koneksi Database Gagal: " . $e->getMessage() . 
        "<br>Pastikan MySQL berjalan di port **3307** dan database **" . DB_NAME . "** tersedia."
    );
}

/**
 * Catatan:
 * Pastikan tabel 'users' sudah dibuat.
 * INSERT INTO users (username, password, role) VALUES 
 * ('admin', '$2y$10$7R9.R0Hk7oHh0x.P.xW/yOuBw7mY3.I.j0YfTq8s5A4.I.j0YfTq8s5A4.', 'admin'); // Password: admin123
 */
?>