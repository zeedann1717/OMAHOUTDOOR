<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header('Location: katalog.php'); exit; }

$pesan = "";
if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga = (int)$_POST['harga_per_hari'];
    $jumlah = (int)$_POST['jumlah_stok'];
    $gambar = $_FILES['gambar']['name'];
    $tmp_name = $_FILES['gambar']['tmp_name'];

    if (move_uploaded_file($tmp_name, "assets/images/" . $gambar)) {
        for ($i = 1; $i <= $jumlah; $i++) {
            $nama_final = ($jumlah > 1) ? "$nama - Unit $i" : $nama;
            mysqli_query($conn, "INSERT INTO produk (nama_produk, harga_per_hari, gambar, status) VALUES ('$nama_final', $harga, '$gambar', 'tersedia')");
        }
        header('Location: katalog.php?status=sukses_tambah'); exit;
    } else { $pesan = "Gagal upload gambar!"; }
}
?>