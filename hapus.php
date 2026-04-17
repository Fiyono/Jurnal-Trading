<?php
session_start();

// Cek login
if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}

require_once 'koneksi.php';

// Ambil ID dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Ambil data foto dari database sebelum dihapus
    $query = "SELECT foto FROM trades WHERE id = $id";
    $result = mysqli_query($koneksi, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $foto_paths = $row['foto'];
        
        // Hapus file foto dari folder uploads
        if (!empty($foto_paths)) {
            $photos = explode(',', $foto_paths);
            foreach ($photos as $photo) {
                $photo = trim($photo);
                if (!empty($photo) && file_exists($photo)) {
                    if (unlink($photo)) {
                        // File berhasil dihapus
                    }
                }
            }
        }
        
        // Hapus data dari database
        $delete_query = "DELETE FROM trades WHERE id = $id";
        if (mysqli_query($koneksi, $delete_query)) {
            header("Location: index.php?success=Data berhasil dihapus");
            exit;
        } else {
            header("Location: index.php?error=Gagal menghapus data: " . mysqli_error($koneksi));
            exit;
        }
    } else {
        header("Location: index.php?error=Data tidak ditemukan");
        exit;
    }
} else {
    header("Location: index.php?error=ID tidak valid");
    exit;
}
?>