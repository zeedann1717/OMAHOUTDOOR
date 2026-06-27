<?php
require_once 'cek_login.php';
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: katalog.php');
    exit;
}

$user_id     = (int) $_SESSION['id'];
$produk_id   = (int) $_POST['produk_id'];
$tgl_mulai   = $_POST['tanggal_mulai'];
$tgl_selesai = $_POST['tanggal_selesai'];

// Validasi tanggal
if (empty($tgl_mulai) || empty($tgl_selesai)) {
    header('Location: pesan.php?produk_id=' . $produk_id . '&error=tanggal_kosong');
    exit;
}

if (strtotime($tgl_selesai) <= strtotime($tgl_mulai)) {
    header('Location: pesan.php?produk_id=' . $produk_id . '&error=tanggal_salah');
    exit;
}

// Ambil data produk
$res_produk = mysqli_query($conn, "SELECT * FROM produk WHERE id=$produk_id");
if (!$res_produk || mysqli_num_rows($res_produk) === 0) {
    header('Location: katalog.php');
    exit;
}
$produk = mysqli_fetch_assoc($res_produk);

// Hitung durasi dan total harga
$durasi = (int) ((strtotime($tgl_selesai) - strtotime($tgl_mulai)) / 86400);
$total  = $durasi * $produk['harga_per_hari'];

// Generate kode order unik: ORD-YYYYMMDD-XXXXXX
$kode_order = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));

// Simpan ke database
$query = "INSERT INTO orders (kode_order, user_id, produk_id, tanggal_mulai, tanggal_selesai, durasi_hari, total_harga, status)
          VALUES ('$kode_order', $user_id, $produk_id, '$tgl_mulai', '$tgl_selesai', $durasi, $total, 'pending')";

if (mysqli_query($conn, $query)) {
    // Update status produk jadi disewa
    mysqli_query($conn, "UPDATE produk SET status='disewa' WHERE id=$produk_id");
    header('Location: bukti_order.php?kode=' . $kode_order);
    exit;
} else {
    header('Location: pesan.php?produk_id=' . $produk_id . '&error=gagal');
    exit;
}
?>
