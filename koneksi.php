<?php
$koneksi = mysqli_connect("sql301.infinityfree.com", "if0_41421698", "JtWEmypwMgGnPL", "if0_41421698_jurnal_trading"); 

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>