<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Omah Outdoor | Sewa Alat Mudah & Cepat</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏕️</text></svg>">
    <!-- CSS Internal & Google Fonts -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-hero">
        <div class="container nav-wrapper">
            <a href="index.php" class="logo">🏕️ OMAH <span>OUTDOOR</span></a>
            <ul class="nav-links">
                <?php if(isset($_SESSION['email'])) : ?>
                    <!-- Tampilan Menu Kalau SUDAH Login -->
                    <li><a href="keunggulan.php">Keunggulan</a></li>
                    <li><a href="katalog.php">Katalog</a></li>
                    <li><a href="riwayat_order.php">Riwayat Order</a></li>
                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
                        <li><a href="dashboard_admin.php" class="btn-login">🛠️ Dashboard Admin</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php" class="btn-logout">Logout</a></li>
                <?php else : ?>
                    <li><a href="login.php" class="btn-login">Login / Daftar</a></li>
                <?php endif; ?>
            </ul>
            <button class="hamburger" onclick="toggleMenu()" aria-label="Toggle Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>

    <!-- Hero Section (Cover Depan) -->
    <header id="beranda" class="hero">
        <div class="container hero-content">
            <h1>Sewa Perlengkapan Outdoor Praktis & Efisien</h1>
            <p>Tinggalkan cara manual! Omah Outdoor hadir untuk memudahkan pengalaman sewa alat camping Anda tanpa ribet, tanpa antre, dan bebas double booking.</p>
            <div class="hero-buttons">

                <?php if(isset($_SESSION['email'])) : ?>
                    <!-- Tombol Kalau SUDAH Login (Langsung pindah halaman) -->
                    <a href="katalog.php" class="btn-primary">Lihat Katalog</a>
                    <a href="keunggulan.php" class="btn-secondary">Cara Kerja</a>
                <?php else : ?>
                    <!-- Tombol Kalau BELUM Login (Muncul Alert JS) -->
                    <a href="login.php" onclick="alert('Silakan Login atau Daftar akun terlebih dahulu untuk melihat Katalog kami!');" class="btn-primary">Lihat Katalog</a>
                    <a href="login.php" onclick="alert('Silakan Login atau Daftar akun terlebih dahulu untuk melihat Cara Kerja!');" class="btn-secondary">Cara Kerja</a>
                <?php endif; ?>

            </div>
        </div>
    </header>

    <!-- Footer -->
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

    <script src="assets/js/script.js?v=2"></script>
</body>
</html>
