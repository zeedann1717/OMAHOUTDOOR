<?php
session_start();
require_once 'koneksi.php';

// Pastikan koneksi sukses
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

$is_logged_in = isset($_SESSION['email']);

// 1. Ambil data produk dengan pengecekan apakah tabel ada
$query = "SELECT * FROM produk ORDER BY id ASC";
$result_produk = mysqli_query($conn, $query);

// Jika query gagal (misal tabel belum dibuat), simpan error ke variabel
$db_error = !$result_produk ? mysqli_error($conn) : null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Katalog Alat | Omah Outdoor</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .stok-info { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; margin: 5px 0; }
        .stok-ada { background: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; }
        .stok-habis { background: #fdecea; color: #c62828; border: 1px solid #ef9a9a; }
        .btn-admin { margin-top: 10px; display: flex; justify-content: center; gap: 10px; }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="container nav-wrapper">
        <a href="index.php" class="logo">🏕️ OMAH <span>OUTDOOR</span></a>
        
        <ul class="nav-links" style="display: flex; list-style: none; gap: 20px; align-items: center; margin: 0; padding: 0;">
            <li><a href="index.php">Beranda</a></li>
            <li><a href="keunggulan.php">Keunggulan</a></li>
            <li><a href="katalog.php" style="font-weight: bold; border-bottom: 2px solid #fff;">Katalog</a></li>
            <li><a href="riwayat_order.php">Riwayat Order</a></li>
            
            <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
                <li><a href="kasir.php" style="color: #ffcc80;">Kasir</a></li>
                <li><a href="tambah_produk.php" style="color: #ffcc80;">+ Produk</a></li>
            <?php endif; ?>

            <li><a href="logout.php" class="btn-logout" style="background: #e63946; padding: 8px 15px; border-radius: 5px; color: white; text-decoration: none;">Logout</a></li>
        </ul>

        <button class="hamburger" onclick="toggleMenu()">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

<section class="catalog" style="padding-top: 120px;">
    <div class="container">
        <div class="product-grid">
            
            <?php if ($db_error) : ?>
                <div style="text-align:center; color:red; grid-column:1/-1;">
                    <h3>⚠️ Error Database</h3>
                    <p>Pesan: <?= htmlspecialchars($db_error) ?></p>
                    <p>Pastikan tabel <strong>'produk'</strong> sudah dibuat di database <strong>'omah_outdoor'</strong>.</p>
                </div>
            <?php elseif (mysqli_num_rows($result_produk) === 0) : ?>
                <p style="text-align:center; grid-column:1/-1;">Belum ada produk tersedia.</p>
            <?php else : ?>
                
                <?php while ($produk = mysqli_fetch_assoc($result_produk)) : ?>
                <div class="product-card">
                    <img src="assets/images/<?= htmlspecialchars($produk['gambar']) ?>" alt="Produk" style="width:100%; height:200px; object-fit:cover;">
                    <div class="product-info">
                        <h3><?= htmlspecialchars($produk['nama_produk']) ?></h3>
                        <p>Rp <?= number_format($produk['harga_per_hari'], 0, ',', '.') ?> / hari</p>
                        
                        <p class="stok-info <?= strtolower($produk['status']) === 'tersedia' ? 'stok-ada' : 'stok-habis' ?>">
                            <?= ucfirst($produk['status']) ?>
                        </p>

                        <a href="<?= strtolower($produk['status']) === 'tersedia' ? 'pesan.php?produk_id='.$produk['id'] : '#' ?>" 
                           class="btn-check <?= strtolower($produk['status']) !== 'tersedia' ? 'disabled' : '' ?>">
                            <?= strtolower($produk['status']) === 'tersedia' ? 'Sewa Sekarang' : 'Sedang Disewa' ?>
                        </a>

                        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
                            <div class="btn-admin">
                                <a href="edit_produk.php?id=<?= $produk['id'] ?>">✏️ Edit</a>
                                <a href="hapus_produk.php?id=<?= $produk['id'] ?>" onclick="return confirm('Hapus?')">🗑️ Hapus</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
                
            <?php endif; ?>
        </div>
    </div>
</section>

</body>
</html>