<?php
// logout.php
session_start();

// 1. Hapus semua variabel sesi yang terdaftar
$_SESSION = array();

// 2. Hancurkan cookie sesi jika ada.
// Ini sangat penting untuk memastikan sesi benar-benar hilang.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Hancurkan sesi
session_destroy();

// 4. Arahkan pengguna kembali ke halaman login (login.php)
header("Location: login.php");
exit;
?>