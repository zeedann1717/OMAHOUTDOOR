<?php
require_once 'cek_admin.php';
require_once 'koneksi.php';

$action     = $_POST['action'] ?? '';
$kode_order = mysqli_real_escape_string($conn, $_POST['kode_order'] ?? '');

if ($action === 'konfirmasi' && $kode_order) {
    // Ambil produk_id dulu
    $res = mysqli_query($conn, "SELECT produk_id FROM orders WHERE kode_order='$kode_order'");
    $row = mysqli_fetch_assoc($res);
    // FIX 1: Baru di sini produk berubah jadi 'disewa' (bukan saat order dibuat)
    mysqli_query($conn, "UPDATE orders SET status='dikonfirmasi' WHERE kode_order='$kode_order'");
    mysqli_query($conn, "UPDATE produk SET status='disewa' WHERE id=" . $row['produk_id']);
    header('Location: validasi_order.php?kode=' . $kode_order . '&pesan=dikonfirmasi');
    exit;
}

if ($action === 'selesai' && $kode_order) {
    // Ambil produk_id dulu
    $res = mysqli_query($conn, "SELECT produk_id FROM orders WHERE kode_order='$kode_order'");
    $row = mysqli_fetch_assoc($res);
    // Update order selesai + produk kembali tersedia
    mysqli_query($conn, "UPDATE orders SET status='selesai' WHERE kode_order='$kode_order'");
    mysqli_query($conn, "UPDATE produk SET status='tersedia' WHERE id=" . $row['produk_id']);
    header('Location: validasi_order.php?kode=' . $kode_order . '&pesan=selesai');
    exit;
}

if ($action === 'batalkan' && $kode_order) {
    $res = mysqli_query($conn, "SELECT produk_id FROM orders WHERE kode_order='$kode_order'");
    $row = mysqli_fetch_assoc($res);
    mysqli_query($conn, "UPDATE orders SET status='dibatalkan' WHERE kode_order='$kode_order'");
    mysqli_query($conn, "UPDATE produk SET status='tersedia' WHERE id=" . $row['produk_id']);
    header('Location: validasi_order.php?kode=' . $kode_order . '&pesan=dibatalkan');
    exit;
}

header('Location: validasi_order.php');
exit;
?>
