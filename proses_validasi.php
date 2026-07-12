<?php
/* ============================================================
   OMAH OUTDOOR - BACKEND PROSES VALIDASI (FIX REDIRECT LINK)
   ============================================================ */
require_once 'cek_admin.php';
require_once 'koneksi.php';

$action     = $_POST['action'] ?? '';
$kode_order = mysqli_real_escape_string($conn, $_POST['kode_order'] ?? '');

if (empty($kode_order)) {
    header('Location: validasi_order.php');
    exit;
}

// 1. Aksi Konfirmasi Terima Sewa
if ($action === 'konfirmasi') {
    $res = mysqli_query($conn, "SELECT produk_id FROM orders WHERE kode_order='$kode_order'");
    if ($row = mysqli_fetch_assoc($res)) {
        mysqli_query($conn, "UPDATE orders SET status='dikonfirmasi' WHERE kode_order='$kode_order'");
        mysqli_query($conn, "UPDATE produk SET status='disewa' WHERE id=" . $row['produk_id']);
    }
    header('Location: validasi_order.php?status=dikonfirmasi');
    exit;
}

// 2. Aksi Barang Dikembalikan (Selesai)
if ($action === 'selesai') {
    $res = mysqli_query($conn, "SELECT produk_id FROM orders WHERE kode_order='$kode_order'");
    if ($row = mysqli_fetch_assoc($res)) {
        mysqli_query($conn, "UPDATE orders SET status='selesai' WHERE kode_order='$kode_order'");
        mysqli_query($conn, "UPDATE produk SET status='tersedia' WHERE id=" . $row['produk_id']);
    }
    header('Location: validasi_order.php?status=selesai');
    exit;
}

// 3. Aksi Batalkan / Tolak Pesanan
if ($action === 'batalkan') {
    mysqli_query($conn, "UPDATE orders SET status='dibatalkan' WHERE kode_order='$kode_order'");
    header('Location: validasi_order.php?status=dibatalkan');
    exit;
}

header('Location: validasi_order.php');
exit;