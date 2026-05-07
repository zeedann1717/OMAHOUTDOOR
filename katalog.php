<?php
session_start();
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
                <!-- Produk 1 -->
                <div class="product-card">
                    <div class="product-badge">Tersedia</div>
                    <div class="product-img">
                        <img src="assets/images/Tenda Dome Kap. 4.jpg" alt="Tenda Dome" onerror="this.style.display='none'">
                    </div>
                    <div class="product-info">
                        <h3>Tenda Dome Kap. 4</h3>
                        <p class="price">Rp 35.000 / hari</p>
                        <a href="#" class="btn-check">Sewa Sekarang</a>
                    </div>
                </div>

                <!-- Produk 2 -->
                <div class="product-card">
                    <div class="product-badge">Tersedia</div>
                    <div class="product-img">
                        <img src="assets/images/Carrier 60L.jpg" alt="Tas Carrier" onerror="this.style.display='none'">
                    </div>
                    <div class="product-info">
                        <h3>Carrier 60L</h3>
                        <p class="price">Rp 25.000 / hari</p>
                        <a href="#" class="btn-check">Sewa Sekarang</a>
                    </div>
                </div>

                <!-- Produk 3 -->
                <div class="product-card">
                    <div class="product-badge busy">Disewa</div>
                    <div class="product-img">
                        <img src="assets/images/Sleeping Bag Polar Bulu.jpeg" alt="Sleeping Bag" onerror="this.style.display='none'">
                    </div>
                    <div class="product-info">
                        <h3>Sleeping Bag Polar Bulu</h3>
                        <p class="price">Rp 10.000 / hari</p>
                        <a href="#" class="btn-check">Cek Ketersediaan</a>
                    </div>
                </div>

                <!-- Produk 4 -->
                <div class="product-card">
                    <div class="product-badge">Tersedia</div>
                    <div class="product-img">
                        <img src="assets/images/Sepatu Gunung.jpg" alt="Sepatu Gunung" onerror="this.style.display='none'">
                    </div>
                    <div class="product-info">
                        <h3>Sepatu Gunung</h3>
                        <p class="price">Rp 20.000 / hari</p>
                        <a href="#" class="btn-check">Sewa Sekarang</a>
                    </div>
                </div>

                <!-- Produk 5 -->
                <div class="product-card">
                    <div class="product-badge">Tersedia</div>
                    <div class="product-img">
                        <img src="assets/images/Kompor Portable.jpg" alt="Kompor Portable" onerror="this.style.display='none'">
                    </div>
                    <div class="product-info">
                        <h3>Kompor Portable</h3>
                        <p class="price">Rp 15.000 / hari</p>
                        <a href="login.php" class="btn-check">Sewa Sekarang</a>
                    </div>
                </div>

                <!-- Produk 6 -->
                <div class="product-card">
                    <div class="product-badge">Tersedia</div>
                    <div class="product-img">
                        <img src="assets/images/Matras Foil Aluminium.jpg" alt="Matras Foil" onerror="this.style.display='none'">
                    </div>
                    <div class="product-info">
                        <h3>Matras Foil Aluminium</h3>
                        <p class="price">Rp 5.000 / hari</p>
                        <a href="login.php" class="btn-check">Sewa Sekarang</a>
                    </div>
                </div>
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
