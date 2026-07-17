<?php
// Tampilkan error biar kalau ada masalah kelihatan di layar
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'koneksi.php';

// Pastikan file ini hanya diakses lewat form (POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: katalog.php");
    exit;
}

// Cek session login
if (!isset($_SESSION['email'])) {
    die("Error: Anda belum login bre. Silakan login terlebih dahulu.");
}

// Ambil data
$id_produk = isset($_POST['id_produk']) ? mysqli_real_escape_string($conn, $_POST['id_produk']) : null;
$tgl_pinjam = isset($_POST['tanggal_pinjam']) ? mysqli_real_escape_string($conn, $_POST['tanggal_pinjam']) : null;
$tgl_kembali = isset($_POST['tanggal_kembali']) ? mysqli_real_escape_string($conn, $_POST['tanggal_kembali']) : null;
$email_user = $_SESSION['email'];

if (!$id_produk || !$tgl_pinjam || !$tgl_kembali) {
    die("Error: Data tidak lengkap. Pastikan ID produk dan tanggal terisi.");
}

// 1. Simpan ke tabel transaksi
$query = "INSERT INTO transaksi (id_produk, email_user, tgl_pinjam, tgl_kembali, status) 
          VALUES ('$id_produk', '$email_user', '$tgl_pinjam', '$tgl_kembali', 'pending')";

if (mysqli_query($conn, $query)) {
    // 2. Update status barang
    $update = mysqli_query($conn, "UPDATE produk SET status = 'disewa' WHERE id = '$id_produk'");

    if ($update) {
        echo "<script>alert('Pemesanan berhasil!'); window.location='katalog.php';</script>";
    } else {
        echo "Error Update Produk: " . mysqli_error($conn);
    }
} else {
    echo "Error Insert Transaksi: " . mysqli_error($conn);
}
?>