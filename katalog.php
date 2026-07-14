<?php
session_start();
require_once 'koneksi.php';

$is_logged_in = isset($_SESSION['email']);

// Ambil semua produk dari database
$result_produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY id ASC");
$db_error      = !$result_produk ? mysqli_error($conn) : null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Alat | Omah Outdoor</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏕️</text></svg>">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <style>
        /* Badge status pojok kanan atas card */
        .product-badge {
            position: absolute;
            top: 14px; right: 14px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            z-index: 2;
        }
        .badge-tersedia { background: #22c55e; color: #fff; }
        .badge-disewa   { background: #ef4444; color: #fff; }

        /* Stok info di bawah harga */
        .stok-info {
            display: inline-block;
            padding: 4px 12px; border-radius: 20px;
            font-size: 12px; font-weight: 600;
            margin: 6px 0 14px;
        }
        .stok-ada   { background: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; }
        .stok-habis { background: #fdecea; color: #c62828; border: 1px solid #ef9a9a; }

        /* Tombol disabled */
        .btn-check.disabled {
            background: #94a3b8 !important;
            cursor: not-allowed !important;
            pointer-events: none;
            opacity: 0.7;
        }

        /* Gambar produk fallback */
        .product-img {
            height: 200px;
            background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
            display: flex; align-items: center; justify-content: center;
            font-size: 48px; overflow: hidden;
        }

        .product-img img {
            width: 100%; height: 100%; object-fit: cover;
        }

        /* Error DB */
        .db-error-box {
            text-align: center; padding: 60px 20px;
            grid-column: 1 / -1;
            background: #fee2e2; border-radius: 14px; color: #991b1b;
        }

        /* Empty state */
        .empty-katalog {
            text-align: center; padding: 80px 20px;
            grid-column: 1 / -1; color: #64748b;
        }
        .empty-katalog .empty-icon { font-size: 60px; margin-bottom: 16px; }
        .empty-katalog h3 { font-family: 'Poppins', sans-serif; color: #1b4332; margin-bottom: 8px; }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="container nav-wrapper">
        <a href="index.php" class="logo">🏕️ OMAH <span>OUTDOOR</span></a>

        <ul class="nav-links">
            <?php if ($is_logged_in) : ?>
                <li><a href="index.php">Beranda</a></li>
                <li><a href="keunggulan.php">Keunggulan</a></li>
                <li><a href="katalog.php" class="active">Katalog</a></li>
                <li><a href="riwayat_order.php">Riwayat Order</a></li>
                <li><a href="profil.php">👤 Profil</a></li>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
                    <li><a href="dashboard_admin.php" class="btn-login">🛠️ Dashboard Admin</a></li>
                <?php endif; ?>
                <li><a href="logout.php" class="btn-logout">Logout</a></li>
            <?php else : ?>
                <li><a href="index.php">Beranda</a></li>
                <li><a href="login.php" class="btn-login">Login / Daftar</a></li>
            <?php endif; ?>
        </ul>

        <button class="hamburger" onclick="toggleMenu()" aria-label="Toggle Menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

<!-- PESAN ERROR jika produk sedang disewa -->
<?php if (isset($_GET['error']) && $_GET['error'] === 'produk_disewa') : ?>
<div style="background:#fee2e2;color:#991b1b;padding:14px 20px;text-align:center;font-weight:600;margin-top:70px;">
    ⚠️ Produk yang kamu pilih sedang disewa orang lain. Silakan pilih produk lain.
</div>
<?php endif; ?>

<!-- KATALOG SECTION -->
<section class="catalog" style="padding-top: <?= isset($_GET['error']) ? '20px' : '100px' ?>;">
    <div class="container">

        <!-- JUDUL SECTION -->
        <div class="section-title">
            <h2>Katalog Perlengkapan</h2>
            <p>Pilih alat terbaik untuk petualanganmu. Stok selalu terupdate secara real-time.</p>
        </div>

        <!-- GRID PRODUK -->
        <div class="product-grid">

            <?php if ($db_error) : ?>
                <div class="db-error-box">
                    <div style="font-size:48px;margin-bottom:12px;">⚠️</div>
                    <h3>Tabel produk belum ada</h3>
                    <p>Silakan re-import file <strong>omah_outdoor.sql</strong> terbaru ke phpMyAdmin.</p>
                </div>

            <?php elseif (mysqli_num_rows($result_produk) === 0) : ?>
                <div class="empty-katalog">
                    <div class="empty-icon">📦</div>
                    <h3>Belum Ada Produk</h3>
                    <p>Admin belum menambahkan produk ke katalog.</p>
                </div>

            <?php else : ?>
                <?php while ($produk = mysqli_fetch_assoc($result_produk)) :
                    $stok        = (int)($produk['jumlah_stok'] ?? 0);
                    $is_tersedia = ($stok > 0 && $produk['status'] === 'tersedia');
                ?>
                <div class="product-card">

                    <!-- Badge status pojok kanan atas -->
                    <div class="product-badge <?= $is_tersedia ? 'badge-tersedia' : 'badge-disewa' ?>">
                        <?= $is_tersedia ? 'Tersedia' : 'Disewa' ?>
                    </div>

                    <!-- Gambar produk -->
                    <div class="product-img">
                        <img src="assets/images/<?= htmlspecialchars($produk['gambar']) ?>"
                             alt="<?= htmlspecialchars($produk['nama_produk']) ?>"
                             onerror="this.parentElement.innerHTML='🏕️'">
                    </div>

                    <!-- Info produk -->
                    <div class="product-info">
                        <h3><?= htmlspecialchars($produk['nama_produk']) ?></h3>
                        <p class="price">Rp <?= number_format($produk['harga_per_hari'], 0, ',', '.') ?> / hari</p>

                        <span class="stok-info <?= $is_tersedia ? 'stok-ada' : 'stok-habis' ?>">
                            <?= $is_tersedia
                                ? '✅ Tersedia (' . $stok . ' unit)'
                                : '❌ Stok Habis / Disewa' ?>
                        </span>

                        <?php if ($is_logged_in) : ?>
                            <a href="<?= $is_tersedia ? 'pesan.php?produk_id=' . $produk['id'] : '#' ?>"
                               class="btn-check <?= !$is_tersedia ? 'disabled' : '' ?>">
                                <?= $is_tersedia ? 'Sewa Sekarang' : 'Tidak Tersedia' ?>
                            </a>
                        <?php else : ?>
                            <a href="login.php"
                               onclick="alert('Silakan Login terlebih dahulu untuk menyewa!')"
                               class="btn-check">
                                Sewa Sekarang
                            </a>
                        <?php endif; ?>
                    </div>

                </div>
                <?php endwhile; ?>
            <?php endif; ?>

        </div><!-- end product-grid -->
    </div>
</section>

<!-- FOOTER -->
<footer class="footer">
    <div class="container footer-content">
        <div class="footer-brand">
            <h3>OMAH OUTDOOR</h3>
            <p>Digitalisasi usaha rental alat outdoor untuk pengalaman yang lebih baik.</p>
        </div>
        <div class="footer-team">
            <h4>Developed by Kelompok 6</h4>
            <ul>
                <li>Zidan Maulana</li>
                <li>Bintang Putra D</li>
                <li>M Rasyid Murtado</li>
                <li>Arif Maulana</li>
                <li>Gesa Santiko G</li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2026 Omah Outdoor - Tugas Kelompok. All Rights Reserved.</p>
    </div>
</footer>

<script src="assets/js/script.js"></script>
</body>
</html>
