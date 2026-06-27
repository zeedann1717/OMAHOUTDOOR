<?php
session_start();
require_once 'koneksi.php';

// Cek dulu apakah tabel produk sudah ada
$cek_tabel     = mysqli_query($conn, "SHOW TABLES LIKE 'produk'");
$tabel_ada     = $cek_tabel && mysqli_num_rows($cek_tabel) > 0;
$result_produk = $tabel_ada ? mysqli_query($conn, "SELECT * FROM produk ORDER BY id ASC") : false;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Alat | Omah Outdoor</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
</head>
<style>
  body {
    background-color: #ffffff;
    background-image: url("https://www.transparenttextures.com/patterns/white-wall.png"),
                      url("https://www.transparenttextures.com/patterns/pinstriped-suit.png");
    background-attachment: fixed;
}

/* Tambahkan sedikit shadow pada teks judul agar lebih 'pop' */
.section-title h2 {
    text-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

/* Membuat judul lebih tegas */
.section-title h2 {
    font-weight: 700;
    letter-spacing: -0.5px;
    color: #1a1a1a;
}
    /* Jika Anda ingin menambahkan overlay gelap agar teks lebih mudah dibaca */
    body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.05); /* Hitam transparan 5% */
        z-index: -1;
    }
    .stok-info {
    display: inline-block;
    margin-top: 6px;
    margin-bottom: 4px;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.stok-ada {
    background-color: #e8f5e9;
    color: #2e7d32;
    border: 1px solid #a5d6a7;
}

.stok-habis {
    background-color: #fdecea;
    color: #c62828;
    border: 1px solid #ef9a9a;
}

.btn-check.disabled {
    background-color: #aaa !important;
    cursor: not-allowed !important;
    pointer-events: none;
    opacity: 0.7;
}
</style>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="container nav-wrapper">
            <a href="index.php" class="logo">🏕️ OMAH <span>OUTDOOR</span></a>
            <ul class="nav-links">
                <?php if(isset($_SESSION['email'])) : ?>
                    <li><a href="index.php">Beranda</a></li>
                    <li><a href="keunggulan.php">Keunggulan</a></li>
                    <li><a href="katalog.php" class="active">Katalog</a></li>
                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
                        <li><a href="dashboard_admin.php" class="btn-login">🛠️ Dashboard Admin</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php" class="btn-logout">Logout</a></li>
                <?php else : ?>
                    <li><a href="index.php">Beranda</a></li>
                    <li><a href="login.php" class="btn-login">Login / Daftar</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Katalog Section -->
    <section id="katalog" class="catalog" style="padding-top: 100px;">
        <div class="container">
            <div class="section-title">
                <h2>Katalog Perlengkapan</h2>
                <p>Pilih alat terbaik untuk petualanganmu. Stok selalu terupdate secara real-time.</p>
            </div>

            <div class="product-grid">
    <?php if (!$result_produk) : ?>
        <p style="text-align:center; color:#dc3545; grid-column: 1/-1;">
            ⚠️ Tabel produk belum ada. Silakan re-import file <strong>omah_outdoor.sql</strong> terbaru ke phpMyAdmin.
        </p>
    <?php elseif (mysqli_num_rows($result_produk) === 0) : ?>
        <p style="text-align:center; color:#64748b; grid-column: 1/-1;">Belum ada produk tersedia.</p>
    <?php else : ?>
        <?php while ($produk = mysqli_fetch_assoc($result_produk)) : ?>
        <div class="product-card">
            <div class="product-badge <?= $produk['status'] === 'disewa' ? 'busy' : '' ?>">
                <?= $produk['status'] === 'disewa' ? 'Disewa' : 'Tersedia' ?>
            </div>
            <div class="product-img">
                <img src="assets/images/<?= htmlspecialchars($produk['gambar']) ?>"
                     alt="<?= htmlspecialchars($produk['nama_produk']) ?>"
                     onerror="this.style.display='none'">
            </div>
           <div class="product-info">
    <h3><?= htmlspecialchars($produk['nama_produk']) ?></h3>

    <p class="price">
        Rp <?= number_format($produk['harga_per_hari'], 0, ',', '.') ?> / hari
    </p>

    <p class="stok-info <?= $produk['stok'] > 0 ? 'stok-ada' : 'stok-habis' ?>">
        <?= $produk['stok'] > 0 ? 'Stok tersedia: ' . $produk['stok'] : 'Stok habis' ?>
    </p>

    <a href="pesan.php?produk_id=<?= $produk['id'] ?>"
       class="btn-check <?= $produk['stok'] <= 0 ? 'disabled' : '' ?>">
        <?= $produk['stok'] <= 0 ? 'Stok Habis' : ($produk['status'] === 'disewa' ? 'Cek Ketersediaan' : 'Sewa Sekarang') ?>
    </a>
</div>
        </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container footer-content">
            <div class="footer-brand">
                <h3>OMAH OUTDOOR</h3>
                <p>Digitalisasi usaha rental alat outdoor untuk pengalaman yang lebih baik.</p>
            </div>
            <div class="footer-team">
                <h4>Developed by Kelompok 6</h4>
            </div>
        </div>
    </footer>

    <script src="assets/js/script.js?v=3"></script>
</body>
</html>
