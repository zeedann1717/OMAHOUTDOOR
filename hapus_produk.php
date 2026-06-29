<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header('Location: katalog.php'); exit; }

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    mysqli_query($conn, "DELETE FROM produk WHERE id = $id");
    header('Location: katalog.php?status=sukses_hapus');
} else { header('Location: katalog.php'); }
?>