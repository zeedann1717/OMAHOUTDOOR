<?php
session_start();
require_once 'koneksi.php';

// Pastikan ada ID yang dikirim lewat URL (misal: edit_produk.php?id=1)
$id_produk = isset($_GET['id']) ? $_GET['id'] : 0;

// Ambil data produk yang mau diedit dari database
// Sesuaikan nama tabel 'produk' dan primary key 'id' dengan database lu
$query = mysqli_query($conn, "SELECT * FROM produk WHERE id = '$id_produk'");
$data = mysqli_fetch_assoc($query);

// Proses kalau tombol simpan ditekan
if (isset($_POST['simpan'])) {
    $nama_baru = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga_baru = (int)$_POST['harga_per_hari'];
    $stok_baru = (int)$_POST['jumlah_stok']; // Ini tangkapan dari form stok baru

    // Query update ke database (pastikan nama kolom 'jumlah_stok' sesuai dengan di database lu)
    $update = mysqli_query($conn, "UPDATE produk SET 
                            nama_produk = '$nama_baru', 
                            harga_per_hari = '$harga_baru', 
                            jumlah_stok = '$stok_baru' 
                            WHERE id = '$id_produk'");

    if ($update) {
        header('Location: katalog.php?status=sukses_edit');
        exit;
    } else {
        $pesan_error = "Gagal menyimpan perubahan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - OMAHOUTDOOR</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f4; padding: 20px; }
        .edit-container { max-width: 500px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input[type="text"], input[type="number"] { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn-submit { background-color: #4a6fa5; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-submit:hover { background-color: #355382; }
    </style>
</head>
<body>

    <div class="edit-container">
        <h2>Edit Perlengkapan</h2>

        <?php if(isset($pesan_error)): ?>
            <p style="color: red;"><?php echo $pesan_error; ?></p>
        <?php endif; ?>

        <?php if($data): ?>
        <form action="" method="POST">
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama_produk" value="<?php echo htmlspecialchars($data['nama_produk']); ?>" required>
            </div>

            <div class="form-group">
                <label>Harga per Hari (Rp)</label>
                <input type="number" name="harga_per_hari" value="<?php echo $data['harga_per_hari']; ?>" required>
            </div>

            <div class="form-group">
                <label>Jumlah Stok</label>
                <!-- Ini form baru buat ngatur stok -->
                <input type="number" name="jumlah_stok" value="<?php echo isset($data['jumlah_stok']) ? $data['jumlah_stok'] : 0; ?>" required>
            </div>

            <button type="submit" name="simpan" class="btn-submit">💾 Simpan Perubahan</button>
            <a href="katalog.php" style="margin-left: 10px; text-decoration: none; color: #555;">Batal</a>
        </form>
        <?php else: ?>
            <p>Data produk tidak ditemukan.</p>
            <a href="katalog.php">Kembali ke Katalog</a>
        <?php endif; ?>
    </div>

</body>
</html>