<?php
// hash_password.php - Buat hash bcrypt dari password baru
$new_password = "S@yangfika_256"; // Ganti dengan password baru Anda

$hash = password_hash($new_password, PASSWORD_DEFAULT);
echo "Password: $new_password<br>";
echo "Hash bcrypt: $hash<br>";
echo "<br>Copy hash di atas ke database di kolom password";
?>