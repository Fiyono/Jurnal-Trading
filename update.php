<?php
session_start();

// Cek login
if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}

require_once 'koneksi.php';

// Ambil data dari form
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$tanggal = isset($_POST['tanggal']) ? mysqli_real_escape_string($koneksi, $_POST['tanggal']) : '';
$session1 = isset($_POST['session1']) ? mysqli_real_escape_string($koneksi, $_POST['session1']) : 'skip';
$session2 = isset($_POST['session2']) ? mysqli_real_escape_string($koneksi, $_POST['session2']) : 'skip';
$session3 = isset($_POST['session3']) ? mysqli_real_escape_string($koneksi, $_POST['session3']) : 'skip';
$session4 = isset($_POST['session4']) ? mysqli_real_escape_string($koneksi, $_POST['session4']) : 'skip';
$session5 = isset($_POST['session5']) ? mysqli_real_escape_string($koneksi, $_POST['session5']) : 'skip';
$session6 = isset($_POST['session6']) ? mysqli_real_escape_string($koneksi, $_POST['session6']) : 'skip';
$note = isset($_POST['note']) ? mysqli_real_escape_string($koneksi, $_POST['note']) : '';

// Existing photos yang tidak dihapus
$existing_photos = isset($_POST['existing_photos']) ? $_POST['existing_photos'] : '';

// Photos yang akan dihapus
$removed_photos = isset($_POST['removed_photos']) ? explode(',', $_POST['removed_photos']) : [];

// Hapus file foto yang dihapus dari folder
foreach ($removed_photos as $photo) {
    $photo = trim($photo);
    if (!empty($photo) && file_exists($photo)) {
        unlink($photo);
    }
}

// Upload foto baru
$folder = "uploads/";
if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}

$new_photos = [];

if (isset($_FILES['new_fotos']) && !empty($_FILES['new_fotos']['name'][0])) {
    $total_files = count($_FILES['new_fotos']['name']);
    $max_files = 3;
    $max_size = 5 * 1024 * 1024;
    
    // Hitung total foto setelah update
    $current_photos = !empty($existing_photos) ? explode(',', $existing_photos) : [];
    $total_after = count($current_photos) + $total_files;
    
    if ($total_after > 3) {
        header("Location: edit.php?id=$id&error=Maksimal 3 foto. Saat ini " . count($current_photos) . " foto + $total_files foto baru = $total_after");
        exit;
    }
    
    for ($i = 0; $i < $total_files; $i++) {
        $file_name = $_FILES['new_fotos']['name'][$i];
        $file_tmp = $_FILES['new_fotos']['tmp_name'][$i];
        $file_size = $_FILES['new_fotos']['size'][$i];
        $file_error = $_FILES['new_fotos']['error'][$i];
        
        if ($file_error !== 0) {
            header("Location: edit.php?id=$id&error=Error upload file: " . $file_name);
            exit;
        }
        
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($ext, $allowed)) {
            header("Location: edit.php?id=$id&error=Format file $file_name harus JPG/PNG/GIF");
            exit;
        }
        
        if ($file_size > $max_size) {
            header("Location: edit.php?id=$id&error=File $file_name melebihi 5MB");
            exit;
        }
        
        $nama_baru = time() . '_' . $i . '_' . preg_replace('/[^a-zA-Z0-9.]/', '_', $file_name);
        $path = $folder . $nama_baru;
        
        if (move_uploaded_file($file_tmp, $path)) {
            $new_photos[] = $path;
        } else {
            header("Location: edit.php?id=$id&error=Upload GAGAL untuk file: " . $file_name);
            exit;
        }
    }
}

// Gabungkan semua foto
$all_photos = [];
if (!empty($existing_photos)) {
    $all_photos = explode(',', $existing_photos);
    $all_photos = array_filter(array_map('trim', $all_photos));
}
$all_photos = array_merge($all_photos, $new_photos);
$foto_paths = implode(',', $all_photos);

// Update database
$query = "UPDATE trades SET 
          tanggal = '$tanggal',
          session1 = '$session1',
          session2 = '$session2',
          session3 = '$session3',
          session4 = '$session4',
          session5 = '$session5',
          session6 = '$session6',
          foto = '$foto_paths',
          note = '$note'
          WHERE id = $id";

if (mysqli_query($koneksi, $query)) {
    header("Location: index.php?success=Data berhasil diperbarui");
    exit;
} else {
    header("Location: edit.php?id=$id&error=Gagal menyimpan: " . mysqli_error($koneksi));
    exit;
}
?>