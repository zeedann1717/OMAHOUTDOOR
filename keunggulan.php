<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keunggulan | Omah Outdoor</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏕️</text></svg>">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Poppins:wght@600;700&display=swap" rel="stylesheet">

    <style>
        .modal {
            display: none; /* Awalnya sembunyi */
            position: fixed; /* Melayang di layar */
            z-index: 9999; /* Di depan elemen lain */
            left: 0;
            top: 0;
            width: 100%;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.6); /* Background gelap transparan */
            align-items: center; /* Vertikal tengah */
            justify-content: center; /* Horizontal tengah */
        }

        .modal-content {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 450px;
            position: relative;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            text-align: center;
            animation: munculPopUp 0.3s ease-out;
        }

        .close-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 28px;
            font-weight: bold;
            color: #666;
            cursor: pointer;
            transition: 0.2s;
        }

        .close-btn:hover {
            color: #ff8c00;
        }

        #modal-body h3 {
            color: #1a237e;
            margin-bottom: 15px;
            font-size: 22px;
        }

        #modal-body p {
            color: #555;
            line-height: 1.6;
            font-size: 15px;
        }

        @keyframes munculPopUp {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="container nav-wrapper">
            <a href="index.php" class="logo">🏕️ OMAH <span>OUTDOOR</span></a>
            <ul class="nav-links">
                <?php if(isset($_SESSION['email'])) : ?>
                    <li><a href="index.php">Beranda</a></li>
                    <li><a href="keunggulan.php" class="active">Keunggulan</a></li>
                    <li><a href="katalog.php">Katalog</a></li>
                    <li><a href="riwayat_order.php">Riwayat Order</a></li>
                                        <li><a href="profil.php">👤 Profil</a></li>
                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
                        <li><a href="dashboard_admin.php" class="btn-login">🛠️ Dashboard Admin</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php" class="btn-logout">Logout</a></li>
                <?php else : ?>
                    <li><a href="index.php">Beranda</a></li>
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

    <div id="featureModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <div id="modal-body"></div>
        </div>
    </div>

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
