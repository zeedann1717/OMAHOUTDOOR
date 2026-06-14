<?php
session_start();
require_once 'koneksi.php';

// Cek apakah ada ID produk di URL (jika tidak ada, tendang balik ke katalog)
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Pilih barang dulu dari katalog!'); window.location='katalog.php';</script>";
    exit;
}

$id_produk = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil data produk spesifik dari database
$query = mysqli_query($conn, "SELECT * FROM produk WHERE id = '$id_produk'");
$produk = mysqli_fetch_assoc($query);

// Jika ID barang tidak ada di database
if (!$produk) {
    echo "<script>alert('Barang tidak ditemukan!'); window.location='katalog.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Penyewaan - <?= htmlspecialchars($produk['nama_produk']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body style="font-family: 'Inter', sans-serif; background-color: #f4f7f6; padding: 40px 20px;">

    <div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <h2 style="color: #1a472a; margin-top: 0; margin-bottom: 25px; border-bottom: 2px solid #e8f5e9; padding-bottom: 10px;">
            Detail Penyewaan
        </h2>
        
        <div style="display: flex; gap: 20px; margin-bottom: 30px; align-items: center; background: #fafafa; padding: 15px; border-radius: 8px;">
            <img src="assets/images/<?= htmlspecialchars($produk['gambar']) ?>" alt="<?= htmlspecialchars($produk['nama_produk']) ?>" style="width: 120px; height: 120px; object-fit: cover; border-radius: 8px;">
            <div>
                <h3 style="margin: 0 0 10px 0; font-size: 1.2rem;"><?= htmlspecialchars($produk['nama_produk']) ?></h3>
                <p style="margin: 0; font-size: 1.1rem; color: #e63946; font-weight: 700;">
                    Rp <?= number_format($produk['harga_per_hari'], 0, ',', '.') ?> <span style="font-size: 0.9rem; color: #666; font-weight: normal;">/ hari</span>
                </p>
            </div>
        </div>

        <form action="proses_transaksi.php" method="POST">
            <input type="hidden" name="id_produk" value="<?= $produk['id'] ?>">
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 500; margin-bottom: 8px;">Tanggal Pinjam:</label>
                <input type="date" name="tanggal_pinjam" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; font-family: 'Inter', sans-serif;">
            </div>
            
            <div style="margin-bottom: 30px;">
                <label style="display: block; font-weight: 500; margin-bottom: 8px;">Tanggal Kembali:</label>
                <input type="date" name="tanggal_kembali" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; font-family: 'Inter', sans-serif;">
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" style="flex: 1; background-color: #2e7d32; color: white; padding: 12px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 1rem;">
                    Proses Sewa
                </button>
                <a href="katalog.php" style="padding: 12px 20px; background-color: #e0e0e0; color: #333; text-decoration: none; border-radius: 6px; font-weight: 500; text-align: center;">
                    Batal
                </a>
            </div>
        </form>
    </div>

</body>
</html>