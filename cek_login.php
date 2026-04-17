<?php
// cek_login.php - Dengan deteksi HTTPS
session_start();

require_once 'koneksi.php';

$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = $_POST['password'];
$remember = isset($_POST['remember']) ? true : false;

// Deteksi apakah pakai HTTPS
$is_secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';

// Set session lifetime berdasarkan remember me
if ($remember) {
    $session_lifetime = 31536000; // 1 tahun
} else {
    $session_lifetime = 28800; // 8 jam
}

// Set cookie parameters dengan deteksi secure
session_set_cookie_params([
    'lifetime' => $session_lifetime,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => $is_secure, // otomatis true jika pakai HTTPS
    'httponly' => true,
    'samesite' => 'Lax'
]);

ini_set('session.gc_maxlifetime', $session_lifetime);
ini_set('session.cookie_lifetime', $session_lifetime);

// Query user
$query = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    
    // Verifikasi password dengan bcrypt
    if (password_verify($password, $user['password'])) {
        $_SESSION['login'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['CREATED'] = time();
        
        session_regenerate_id(true);
        
        header("Location: index.php");
        exit;
    }
}

header("Location: login.php?error=login_failed");
exit;
?>