<?php
// session.php - Optimasi untuk InfinityFree

// Set session save path ke folder yang bisa ditulis (SEBELUM session_start)
$session_path = ini_get('session.save_path');
if (empty($session_path) || !is_writable($session_path)) {
    session_save_path('/tmp');
}

// Set cookie lifetime 1 tahun
$session_lifetime = 31536000; // 1 tahun

// Set cookie parameters (SEBELUM session_start)
session_set_cookie_params([
    'lifetime' => $session_lifetime,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);

// Atur session timeout di server (SEBELUM session_start)
ini_set('session.gc_maxlifetime', $session_lifetime);
ini_set('session.cookie_lifetime', $session_lifetime);

// Start session - NOW after all configurations
session_start();

// Cek apakah user sudah login
if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}

// Regenerate session ID untuk keamanan (setiap 24 jam)
if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} else if (time() - $_SESSION['CREATED'] > 86400) {
    session_regenerate_id(true);
    $_SESSION['CREATED'] = time();
}
?>