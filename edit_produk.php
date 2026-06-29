<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header('Location: katalog.php'); exit; }

$id = (int)$_GET['id'];
if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga = (int)$_POST['harga_per_hari'];
    mysqli_query($conn, "UPDATE produk SET nama_produk='$nama', harga_per_hari=$harga WHERE id=$id");
    header('Location: katalog.php?status=sukses_edit'); exit;
}

$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM produk WHERE id=$id"));
?>
<!DOCTYPE html>
<html>
<head><title>Edit Produk</title></head>
<body>
    <div class="container">
        <h2>Edit Perlengkapan</h2>
        <form method="POST">
            <input type="text" name="nama_produk" value="<?= htmlspecialchars($data['nama_produk']) ?>" required>
            <input type="number" name="harga_per_hari" value="<?= $data['harga_per_hari'] ?>" required>
            <button type="submit" name="update">💾 Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>