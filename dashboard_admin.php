<?php
/* ============================================================
   OMAH OUTDOOR - DASHBOARD ADMIN (FULL PREMIUM COVER BANNER)
   ============================================================ */

session_start();
require_once 'koneksi.php';

// Keamanan: Hanya admin yang boleh masuk
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: katalog.php');
    exit;
}

// Mengambil data counter statis untuk dashboard
$query_user = "SELECT COUNT(*) AS total_user FROM users WHERE role = 'user'";
$res_user = mysqli_query($conn, $query_user);
$row_user = mysqli_fetch_assoc($res_user);

$query_produk = "SELECT COUNT(*) AS total_produk FROM produk";
$res_produk = mysqli_query($conn, $query_produk);
$row_produk = mysqli_fetch_assoc($res_produk);

$query_admin = "SELECT COUNT(*) AS total_admin FROM users WHERE role = 'admin'";
$res_admin = mysqli_query($conn, $query_admin);
$row_admin = mysqli_fetch_assoc($res_admin);

// Variabel ringkasan
$total_admin      = (int) $row_admin['total_admin'];
$total_user_biasa = (int) $row_user['total_user'];
$total_users      = $total_admin + $total_user_biasa;
$total_produk     = (int) $row_produk['total_produk'];

// Hitung order berdasarkan status
$query_order_stats = "SELECT status, COUNT(*) as total FROM orders GROUP BY status";
$res_order_stats   = mysqli_query($conn, $query_order_stats);
$order_stats = ['pending'=>0,'dikonfirmasi'=>0,'selesai'=>0,'dibatalkan'=>0];
if ($res_order_stats) {
    while ($row_os = mysqli_fetch_assoc($res_order_stats)) {
        $order_stats[$row_os['status']] = $row_os['total'];
    }
}
$total_order = array_sum($order_stats);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Omah Outdoor</title>
    <link rel="stylesheet" href="assets/css/admin.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        html, body {
            max-width: 100% !important;
            overflow-x: hidden !important;
            background-color: #F8FAF8;
        }

        .main-content {
            margin-left: 285px !important;
            width: calc(100% - 285px) !important;
            max-width: calc(100% - 285px) !important;
            padding: 40px !important;
            box-sizing: border-box !important;
        }

        /* 🏔️ FIX: BANNER MODERN DENGAN BACKGROUND GUNUNG FULL SCREEN */
        .welcome-banner {
            position: relative;
            width: 100%;
            min-height: 280px;
            border-radius: 24px;
            padding: 40px;
            box-sizing: border-box;
            display: flex;
            justify-content: space-between;
            align-items: center;
            overflow: hidden;

            /* Sisi kiri diberi gradasi gelap transparan agar teks kontras, sisi kanan memperlihatkan keindahan gunung secara penuh */
            background-image: linear-gradient(135deg, rgba(32, 61, 47, 0.92) 0%, rgba(41, 89, 67, 0.65) 55%, rgba(255, 255, 255, 0) 100%),
                              url('https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=1200&auto=format&fit=crop');
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            box-shadow: 0 12px 35px rgba(41, 89, 67, 0.15);
        }

        .banner-left {
            max-width: 65%;
            z-index: 2;
            color: #ffffff;
        }

        .badge-welcome {
            background: rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(4px);
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 15px;
            letter-spacing: 0.5px;
        }

        .banner-title {
            font-size: 38px;
            font-weight: 800;
            margin: 0 0 12px 0;
            color: #ffffff;
            line-height: 1.2;
        }

        .highlight {
            color: #E7C56A; /* Warna Emas Mewah */
        }

        .banner-desc {
            font-size: 14px;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.9);
            margin: 0 0 25px 0;
        }

        .banner-btns {
            display: flex;
            gap: 12px;
        }

        .btn-add, .btn-view {
            padding: 12px 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-add {
            background-color: #E7C56A;
            color: #2D4A3E;
        }
        .btn-add:hover { background-color: #dcb85c; transform: translateY(-2px); }

        .btn-view {
            background-color: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(4px);
        }
        .btn-view:hover { background-color: rgba(255, 255, 255, 0.25); transform: translateY(-2px); }

        /* 🪟 CARD DI KANAN: EFEK KACA (FROSTED GLASS) */
        .banner-right-card {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            width: 150px;
            text-align: center;
            color: #ffffff;
            z-index: 2;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        /* Layout Grid Statistik Bawah */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 30px;
        }

        .card-stat {
            background: white;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            display: flex;
            align-items: center;
            gap: 20px;
            border: 1px solid rgba(230, 235, 232, 0.5);
        }

        .stat-icon-box {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            background: #EDF0EC;
            color: #2D4A3E;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
    </style>
</head>
<body>

    <!-- SIDEBAR KIRI -->
    <aside class="sidebar">
        <div>
            <a href="dashboard_admin.php" class="logo">
                <div class="logo-icon"><i class="fa-solid fa-mountain-sun"></i></div>
                <div class="logo-text"><h2>OMAH</h2><span>OUTDOOR</span></div>
            </a>

            <div class="sidebar-menu">
                <a href="dashboard_admin.php" class="menu active"><i class="fa-solid fa-gauge-high"></i><span>Beranda</span></a>
                <a href="katalog.php" class="menu"><i class="fa-solid fa-images"></i><span>Lihat Katalog</span></a>
                <a href="kelola_produk.php" class="menu"><i class="fa-solid fa-box-open"></i><span>Kelola Produk</span></a>
                <a href="validasi_order.php" class="nav-item">
                    <span class="nav-icon">🔍</span> Validasi Order
                    <?php if ($order_stats['pending'] > 0) : ?>
                        <span style="background:#ef4444;color:#fff;border-radius:20px;padding:1px 8px;font-size:11px;font-weight:700;margin-left:auto;"><?= $order_stats['pending'] ?></span>
                    <?php endif; ?>
                </a>
                <a href="laporan.php" class="menu"><i class="fa-solid fa-file-invoice"></i><span>Laporan</span></a>
                <a href="pengaturan.php" class="menu"><i class="fa-solid fa-gear"></i><span>Pengaturan</span></a>
            </div>
        </div>

        <div>
            <div class="camp-card">
                <i class="fa-solid fa-tent"></i>
                <h4>Adventure Mode</h4>
                <p>Sistem Siap Digunakan</p>
            </div>
            <a href="logout.php" class="logout-btn"><i class="fa-solid fa-power-off"></i><span>Keluar</span></a>
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="main-content">

        <!-- BANNER DENGAN BACKGROUND GUNUNG FULL DAN TRANSPARAN OVERLAY -->
        <div class="welcome-banner">
            <div class="banner-left">
                <div class="badge-welcome">Selamat Datang Kembali</div>
                <h1 class="banner-title">Halo, <span class="highlight">Administrator!</span></h1>
                <p class="banner-desc">Kelola sistem administrasi penyewaan, inventaris alat camping, dan pantau pengguna terdaftar Omah Outdoor dengan cepat dan efisien dari satu panel khusus.</p>
                <div class="banner-btns">
                    <a href="kelola_produk.php" class="btn-add"><i class="fa-solid fa-plus"></i> Tambah Produk</a>
                    <a href="katalog.php" class="btn-view"><i class="fa-solid fa-globe"></i> Lihat Toko</a>
                </div>
            </div>

            <!-- Card Kanan Efek Kaca Transparan -->
            <div class="banner-right-card">
                <div style="font-size: 24px; color: #E7C56A; margin-bottom: 5px;"><i class="fa-solid fa-users"></i></div>
                <h2 style="font-size: 32px; margin: 0; font-weight: 800;"><?= $row_user['total_user'] ?></h2>
                <p style="font-size: 11px; margin: 5px 0 0 0; text-transform: uppercase; opacity: 0.7; font-weight: 700;">User Aktif</p>
            </div>
        </div>

        <!-- STATS CARDS BARIS 1: User & Produk -->
        <section class="stats-grid" style="margin-bottom:16px;">
            <div class="stat-card">
                <div class="stat-icon" style="background:#d1fae5;color:#065f46;">👥</div>
                <div class="stat-info">
                    <p class="stat-label">Total User</p>
                    <h2 class="stat-value"><?= $total_users ?></h2>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:#fef3c7;color:#92400e;">👑</div>
                <div class="stat-info">
                    <p class="stat-label">Total Admin</p>
                    <h2 class="stat-value"><?= $total_admin ?></h2>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:#dbeafe;color:#1e40af;">🙋</div>
                <div class="stat-info">
                    <p class="stat-label">User Biasa</p>
                    <h2 class="stat-value"><?= $total_user_biasa ?></h2>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:#ffe4e6;color:#9f1239;">📦</div>
                <div class="stat-info">
                    <p class="stat-label">Total Produk</p>
                    <h2 class="stat-value"><?= $total_produk ?></h2>
                </div>
            </div>
        </section>

        <!-- STATS CARDS BARIS 2: Order -->
        <section class="stats-grid" style="margin-bottom:32px;">
            <div class="stat-card">
                <div class="stat-icon" style="background:#f1f5f9;color:#475569;">🛒</div>
                <div class="stat-info">
                    <p class="stat-label">Total Order</p>
                    <h2 class="stat-value"><?= $total_order ?></h2>
                </div>
            </div>
            <div class="stat-card" style="border-left:4px solid #f59e0b;">
                <div class="stat-icon" style="background:#fef3c7;color:#92400e;">⏳</div>
                <div class="stat-info">
                    <p class="stat-label">Perlu Diproses</p>
                    <h2 class="stat-value" style="color:#d97706;"><?= $order_stats['pending'] ?></h2>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:#d1fae5;color:#065f46;">✅</div>
                <div class="stat-info">
                    <p class="stat-label">Dikonfirmasi</p>
                    <h2 class="stat-value"><?= $order_stats['dikonfirmasi'] ?></h2>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:#dbeafe;color:#1e40af;">🎉</div>
                <div class="stat-info">
                    <p class="stat-label">Selesai</p>
                    <h2 class="stat-value"><?= $order_stats['selesai'] ?></h2>
                </div>
            </div>
        </section>

        <!-- AREA TABEL MANAJEMEN PENGGUNA -->
        <div class="table-card" style="background: white; border-radius: 20px; padding: 24px; box-shadow: 0 12px 30px rgba(41, 89, 67, 0.05); margin-top: 30px;">
            <div class="table-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="font-size: 20px; color: #295943; font-weight: 700; margin: 0;">Manajemen Pengguna</h2>
                <span class="table-badge" style="padding: 6px 16px; border-radius: 50px; background: #EEF5F1; color: #295943; font-weight: 700; font-size: 12px;">Terbaru</span>
            </div>

            <div class="table-responsive" style="overflow-x: auto; border-radius: 12px; border: 1px solid #EDF0EC;">
                <table class="admin-table" style="width: 100%; border-collapse: collapse; min-width: 800px; text-align: left;">
                    <thead>
                        <tr style="background: #F8FAF8;">
                            <th style="padding: 14px 16px; font-size: 11px; text-transform: uppercase; color: #74817B; font-weight: 700; border-bottom: 1px solid #EDF0EC;">#</th>
                            <th style="padding: 14px 16px; font-size: 11px; text-transform: uppercase; color: #74817B; font-weight: 700; border-bottom: 1px solid #EDF0EC;">Nama Pengguna</th>
                            <th style="padding: 14px 16px; font-size: 11px; text-transform: uppercase; color: #74817B; font-weight: 700; border-bottom: 1px solid #EDF0EC;">Email</th>
                            <th style="padding: 14px 16px; font-size: 11px; text-transform: uppercase; color: #74817B; font-weight: 700; border-bottom: 1px solid #EDF0EC;">No. Whatsapp</th>
                            <th style="padding: 14px 16px; font-size: 11px; text-transform: uppercase; color: #74817B; font-weight: 700; border-bottom: 1px solid #EDF0EC;">Peran</th>
                            <th style="padding: 14px 16px; font-size: 11px; text-transform: uppercase; color: #74817B; font-weight: 700; border-bottom: 1px solid #EDF0EC;">Tanggal Bergabung</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding: 14px 16px; border-bottom: 1px solid #EDF0EC; font-size: 13px;">1</td>
                            <td style="padding: 14px 16px; border-bottom: 1px solid #EDF0EC; font-size: 13px;">
                                <div class="user-info" style="display: flex; align-items: center; gap: 10px;">
                                    <div class="avatar" style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #295943, #3D7A59); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px;">A</div>
                                    <strong>Arif Maulana</strong>
                                </div>
                            </td>
                            <td style="padding: 14px 16px; border-bottom: 1px solid #EDF0EC; font-size: 13px; color: #74817B;">Arif123@gmail.com</td>
                            <td style="padding: 14px 16px; border-bottom: 1px solid #EDF0EC; font-size: 13px; color: #74817B;">08888888888</td>
                            <td style="padding: 14px 16px; border-bottom: 1px solid #EDF0EC; font-size: 13px;"><span class="badge user" style="display: inline-block; padding: 4px 12px; border-radius: 50px; font-size: 11px; font-weight: 700; background: #E7EFFF; color: #2F62D6;">User</span></td>
                            <td style="padding: 14px 16px; border-bottom: 1px solid #EDF0EC; font-size: 13px; color: #74817B;">05 Jul 2026</td>
                        </tr>
                        <tr>
                            <td style="padding: 14px 16px; border-bottom: none; font-size: 13px;">2</td>
                            <td style="padding: 14px 16px; border-bottom: none; font-size: 13px;">
                                <div class="user-info" style="display: flex; align-items: center; gap: 10px;">
                                    <div class="avatar" style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #295943, #3D7A59); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px;">Z</div>
                                    <strong>Zidan Maulana</strong>
                                </div>
                            </td>
                            <td style="padding: 14px 16px; border-bottom: none; font-size: 13px; color: #74817B;">maulanazidan4420@gmail.com</td>
                            <td style="padding: 14px 16px; border-bottom: none; font-size: 13px; color: #74817B;">081234567890</td>
                            <td style="padding: 14px 16px; border-bottom: none; font-size: 13px;"><span class="badge admin" style="display: inline-block; padding: 4px 12px; border-radius: 50px; font-size: 11px; font-weight: 700; background: #DFF7E7; color: #227247;">Admin</span></td>
                            <td style="padding: 14px 16px; border-bottom: none; font-size: 13px; color: #74817B;">01 May 2026</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FOOTER DASHBOARD -->
        <div class="dashboard-footer" style="background: white; border-radius: 20px; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 12px 30px rgba(41, 89, 67, 0.05); margin-top: 30px;">
            <div class="footer-left">
                <h3 style="font-size: 16px; color: #295943; font-weight: 700; margin: 0;"><i class="fa-solid fa-mountain" style="color: #3D7A59; margin-right: 5px;"></i> Omah Outdoor</h3>
                <p style="font-size: 11px; color: #74817B; margin: 3px 0 0 0;">Sistem Administrasi Penyewaan Perlengkapan Outdoor.</p>
            </div>
            <div class="footer-right" style="display: flex; gap: 12px;">
                <div class="footer-item" style="padding: 8px 16px; background: #F8FAF7; border-radius: 10px; text-align: center; min-width: 80px; border: 1px solid #EDF0EC;">
                    <strong style="display: block; font-size: 16px; color: #23332B;"><?= $row_user['total_user'] ?></strong>
                    <span style="display: block; font-size: 10px; color: #74817B;">User</span>
                </div>
                <div class="footer-item" style="padding: 8px 16px; background: #F8FAF7; border-radius: 10px; text-align: center; min-width: 80px; border: 1px solid #EDF0EC;">
                    <strong style="display: block; font-size: 16px; color: #23332B;"><?= $row_produk['total_produk'] ?></strong>
                    <span style="display: block; font-size: 10px; color: #74817B;">Produk</span>
                </div>
                <div class="footer-item" style="padding: 8px 16px; background: #F8FAF7; border-radius: 10px; text-align: center; min-width: 80px; border: 1px solid #EDF0EC;">
                    <strong style="display: block; font-size: 16px; color: #23332B;"><?= $row_admin['total_admin'] ?></strong>
                    <span style="display: block; font-size: 10px; color: #74817B;">Admin</span>
                </div>
            </div>
        </div>

    </main>

</body>
</html>
