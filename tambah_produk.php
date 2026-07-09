<?php
// Fitur sakti buat nampilin error biar nggak nebak-nebak
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'koneksi.php';

// Cek apakah yang akses admin atau bukan
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
    header('Location: katalog.php'); 
    exit; 
}

$pesan = "";
if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga = (int)$_POST['harga_per_hari'];
    $jumlah = (int)$_POST['jumlah_stok'];
    $gambar = $_FILES['gambar']['name'];
    $tmp_name = $_FILES['gambar']['tmp_name'];

    // Pastikan folder tujuan upload tersedia
    if (move_uploaded_file($tmp_name, "assets/images/" . $gambar)) {
        for ($i = 1; $i <= $jumlah; $i++) {
            $nama_final = ($jumlah > 1) ? "$nama - Unit $i" : $nama;
            mysqli_query($conn, "INSERT INTO produk (nama_produk, harga_per_hari, gambar, status) VALUES ('$nama_final', $harga, '$gambar', 'tersedia')");
        }
        header('Location: katalog.php?status=sukses_tambah'); 
        exit;
    } else { 
        $pesan = "Gagal upload gambar! Pastikan folder assets/images/ sudah ada."; 
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - OMAHOUTDOOR</title>
    <style>
        /* Styling darurat biar form-nya rapi dan gampang dibaca, bisa dihapus kalau lu mau pake CSS lu sendiri */
        body { font-family: sans-serif; background-color: #f4f4f4; padding: 20px; }
        .form-container { max-width: 500px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input[type="text"], input[type="number"], input[type="file"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn-submit { background-color: #1e5631; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .btn-submit:hover { background-color: #143d22; }
        .alert { background-color: #ffcccc; color: red; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
    </style>
</head>
<body>

    <div class="form-container">
        <h2 style="text-align: center; color: #1e5631;">Tambah Alat Outdoor Baru</h2>

        <?php if($pesan != ""): ?>
            <div class="alert"><?php echo $pesan; ?></div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            
            <div class="form-group">
                <label for="nama_produk">Nama Produk (Contoh: Tenda Dome 4 Orang)</label>
                <input type="text" id="nama_produk" name="nama_produk" required>
            </div>

            <div class="form-group">
                <label for="harga_per_hari">Harga Sewa per Hari (Rp)</label>
                <input type="number" id="harga_per_hari" name="harga_per_hari" required>
            </div>

            <div class="form-group">
                <label for="jumlah_stok">Jumlah Stok (Unit)</label>
                <input type="number" id="jumlah_stok" name="jumlah_stok" value="1" min="1" required>
            </div>

            <div class="form-group">
                <label for="gambar">Upload Foto Produk</label>
                <input type="file" id="gambar" name="gambar" accept="image/*" required>
            </div>

            <button type="submit" name="simpan" class="btn-submit">+ Simpan Produk</button>
            <a href="katalog.php" style="display:inline-block; margin-left:10px; text-decoration:none; color:#555;">Kembali</a>
        </form>
    </div>

</body>
</html>