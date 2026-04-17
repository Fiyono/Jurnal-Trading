<?php
// Mulai session
session_start();

// Cek login
if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}

// Include koneksi database
require_once 'koneksi.php';

// 🔥 VALIDASI INPUT DASAR
$tanggal  = isset($_POST['tanggal']) ? mysqli_real_escape_string($koneksi, $_POST['tanggal']) : '';
$note     = isset($_POST['note']) ? mysqli_real_escape_string($koneksi, $_POST['note']) : '';
$session1 = isset($_POST['session1']) ? mysqli_real_escape_string($koneksi, $_POST['session1']) : 'skip';
$session2 = isset($_POST['session2']) ? mysqli_real_escape_string($koneksi, $_POST['session2']) : 'skip';
$session3 = isset($_POST['session3']) ? mysqli_real_escape_string($koneksi, $_POST['session3']) : 'skip';
$session4 = isset($_POST['session4']) ? mysqli_real_escape_string($koneksi, $_POST['session4']) : 'skip';
$session5 = isset($_POST['session5']) ? mysqli_real_escape_string($koneksi, $_POST['session5']) : 'skip'; // ✅ TAMBAHKAN INI

// Validasi tanggal
if (empty($tanggal)) {
    header("Location: tambah.php?error=Tanggal harus diisi");
    exit;
}

// =====================
// 🔥 UPLOAD MULTIPLE FOTO (Max 3, Max 5MB)
// =====================
$uploaded_files = [];

// Folder upload
$folder = "uploads/";

// Auto buat folder jika belum ada
if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}

// Cek apakah ada file yang diupload
if (isset($_FILES['fotos']) && !empty($_FILES['fotos']['name'][0])) {
    
    $total_files = count($_FILES['fotos']['name']);
    $max_files = 3;
    $max_size = 5 * 1024 * 1024; // 5MB
    
    // Validasi jumlah file
    if ($total_files > $max_files) {
        header("Location: tambah.php?error=Maksimal upload $max_files foto");
        exit;
    }
    
    for ($i = 0; $i < $total_files; $i++) {
        $file_name = $_FILES['fotos']['name'][$i];
        $file_tmp = $_FILES['fotos']['tmp_name'][$i];
        $file_size = $_FILES['fotos']['size'][$i];
        $file_error = $_FILES['fotos']['error'][$i];
        
        // Cek error upload
        if ($file_error !== 0) {
            header("Location: tambah.php?error=Error upload file: " . $file_name);
            exit;
        }
        
        // Validasi ekstensi
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($ext, $allowed)) {
            header("Location: tambah.php?error=Format file $file_name harus JPG/PNG/GIF!");
            exit;
        }
        
        // Validasi ukuran file (max 5MB)
        if ($file_size > $max_size) {
            header("Location: tambah.php?error=File $file_name melebihi 5MB!");
            exit;
        }
        
        // 🔥 rename biar aman (anti spasi & duplikat)
        $nama_baru = time() . '_' . $i . '_' . preg_replace('/[^a-zA-Z0-9.]/', '_', $file_name);
        $path = $folder . $nama_baru;
        
        // Upload file
        if (move_uploaded_file($file_tmp, $path)) {
            $uploaded_files[] = $path;
        } else {
            header("Location: tambah.php?error=Upload GAGAL untuk file: " . $file_name);
            exit;
        }
    }
}

// Gabungkan semua path foto dengan koma
$foto_paths = implode(',', $uploaded_files);

// =====================
// 🔥 SIMPAN KE DATABASE (DENGAN SESSION5)
// =====================
$query = "INSERT INTO trades 
(tanggal, session1, session2, session3, session4, session5, foto, note) 
VALUES 
('$tanggal', '$session1', '$session2', '$session3', '$session4', '$session5', '$foto_paths', '$note')"; // ✅ TAMBAHKAN session5

$result = mysqli_query($koneksi, $query);

if (!$result) {
    header("Location: tambah.php?error=Gagal menyimpan: " . mysqli_error($koneksi));
    exit;
}

// =====================
// 🔥 REDIRECT KE TAMBAH DENGAN PARAMETER SUCCESS
// =====================
header("Location: tambah.php?success=Data trading berhasil ditambahkan!");
exit;
?>