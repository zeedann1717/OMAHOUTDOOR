<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keunggulan | Omah Outdoor</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="container nav-wrapper">
            <a href="index.php" class="logo">🏕️ OMAH <span>OUTDOOR</span></a>
            <ul class="nav-links">
                <!-- Logika Navbar Dinamis -->
                <?php if(isset($_SESSION['email'])) : ?>
                    <li><a href="index.php">Beranda</a></li>
                    <li><a href="keunggulan.php" style="font-weight: bold; color: #ff8c00;">Keunggulan</a></li>
                    <li><a href="katalog.php">Katalog</a></li>
                    <li><a href="logout.php" class="btn-login" style="background-color: #dc3545; color: white;">Logout</a></li>
                <?php else : ?>
                    <li><a href="index.php">Beranda</a></li>
                    <li><a href="login.php" class="btn-login">Login / Daftar</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Solusi Section (Keunggulan) -->
    <section id="solusi" class="features" style="padding-top: 100px;">
        <div class="container">
            <div class="section-title">
                <h2>Solusi Yang Kami Berikan</h2>
                <p>Mengatasi permasalahan sewa manual dengan sistem digital terpadu.</p>
            </div>
            
            <div class="feature-grid">
                <div class="feature-card" onclick="openModal('katalog')">
                    <div class="icon">📦</div>
                    <h3>Katalog & Stok Real-Time</h3>
                    <p>Cek ketersediaan alat secara akurat, langsung pesan tanpa nunggu balasan admin.</p>
                </div>
                <div class="feature-card" onclick="openModal('jadwal')">
                    <div class="icon">📅</div>
                    <h3>Pemesanan Terjadwal</h3>
                    <p>Sistem booking online pintar untuk mencegah double booking dan antrean panjang.</p>
                </div>
                <div class="feature-card" onclick="openModal('automasi')">
                    <div class="icon">⚙️</div>
                    <h3>Automasi Administrasi</h3>
                    <p>Perhitungan biaya sewa dan denda keterlambatan dihitung otomatis oleh sistem.</p>
                </div>
                <div class="feature-card" onclick="openModal('pickup')">
                    <div class="icon">⚡</div>
                    <h3>Layanan Cepat (Pick-up)</h3>
                    <p>Pesan online, bayar, lalu datang ke toko hanya untuk mengambil barang.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Struktur Modal (Pop-up) -->
    <div id="featureModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <div id="modal-body"></div>
        </div>
    </div>

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
    </footer>

    <script src="assets/js/script.js?v=3"></script>
</body>
</html>