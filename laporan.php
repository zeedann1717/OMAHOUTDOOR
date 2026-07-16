<?php
/* ============================================================
   OMAH OUTDOOR - LAPORAN KEUANGAN RINGKAS (SIDEBAR LAMA FIX)
   ============================================================ */

session_start();
require_once 'koneksi.php';

// Keamanan: Hanya admin yang boleh melihat laporan
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: katalog.php'); 
    exit;
}

// 1. Hitung Total Omzet dari Transaksi yang Selesai
$query_omzet = "SELECT SUM(total_harga) AS total_omzet FROM orders WHERE status = 'selesai'";
$result_omzet = mysqli_query($conn, $query_omzet);
$row_omzet = mysqli_fetch_assoc($result_omzet);
$total_omzet = $row_omzet['total_omzet'] ?? 0;

// 2. Hitung Total Transaksi Sukses
$query_sukses = "SELECT COUNT(*) AS total_transaksi FROM orders WHERE status = 'selesai'";
$result_sukses = mysqli_query($conn, $query_sukses);
$row_sukses = mysqli_fetch_assoc($result_sukses);
$total_transaksi = $row_sukses['total_transaksi'] ?? 0;

// 3. Hitung Total Transaksi yang Dibatalkan/Gagal (Sebagai Data Tambahan)
$query_batal = "SELECT COUNT(*) AS total_batal FROM orders WHERE status = 'dibatalkan'";
$result_batal = mysqli_query($conn, $query_batal);
$row_batal = mysqli_fetch_assoc($result_batal);
$total_batal = $row_batal['total_batal'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan - Omah Outdoor</title>
    <link rel="stylesheet" href="assets/css/admin.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    
    <style>
        html, body {
            max-width: 100% !important;
            overflow-x: hidden !important;
        }

        .main-content {
            margin-left: 285px !important;
            width: calc(100% - 285px) !important;
            max-width: calc(100% - 285px) !important;
            padding: 40px !important;
            box-sizing: border-box !important;
        }

        /* Stats Grid - 3 Kolom Menyesuaikan Layout Ringkas Baru */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 20px;
        }

        .stat-box {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .icon-omzet { background: #EBF7EE; color: #16A34A; }
        .icon-transaksi { background: #E0F2FE; color: #0284C7; }
        .icon-batal { background: #FEE2E2; color: #DC2626; }

        .stat-info h3 {
            font-size: 13px;
            color: var(--text-light);
            margin: 0 0 5px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-info p {
            font-size: 22px;
            font-weight: 800;
            color: var(--primary);
            margin: 0;
        }

        .report-card-info {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: var(--shadow);
            margin-top: 30px;
        }
    </style>
</head>
<body>

    <!-- SIDEBAR KIRI (DENGAN FORMAT 6 MENU LAMA-BARU SINKRON) -->
    <aside class="sidebar">
        <div>
            <!-- UBAH HREF-NYA MENJADI index.php -->
<a href="index.php" class="logo">
    <div class="logo-icon"><i class="fa-solid fa-mountain-sun"></i></div>
    <div class="logo-text"><h2>OMAH</h2><span>OUTDOOR</span></div>
</a>
            
            <div class="sidebar-menu">
                <a href="dashboard_admin.php" class="menu"><i class="fa-solid fa-gauge-high"></i><span>Beranda</span></a>
                <a href="katalog.php" class="menu"><i class="fa-solid fa-images"></i><span>Lihat Katalog</span></a>
                <a href="kelola_produk.php" class="menu"><i class="fa-solid fa-box-open"></i><span>Kelola Produk</span></a>
                <a href="validasi_order.php" class="menu"><i class="fa-solid fa-clipboard-check"></i><span>Validasi Order</span></a>
                <a href="laporan.php" class="menu active"><i class="fa-solid fa-file-invoice"></i><span>Laporan</span></a>
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
        <div class="header-section" style="margin-bottom: 25px;">
            <h1 style="font-size: 28px; color: var(--primary); font-weight: 800;">📊 Laporan Keuangan Toko</h1>
            <p style="font-size: 14px; color: var(--text-light);">Ringkasan performa pendapatan rental Omah Outdoor secara *real-time* dari database.</p>
        </div>

        <!-- Rangkuman Kotak Statistik Pendapatan -->
        <div class="stats-grid">
            <!-- 1. Omzet Total -->
            <div class="stat-box">
                <div class="stat-icon icon-omzet"><i class="fa-solid fa-wallet"></i></div>
                <div class="stat-info">
                    <h3>Total Pendapatan</h3>
                    <p>Rp <?= number_format($total_omzet, 0, ',', '.') ?></p>
                </div>
            </div>
            
            <!-- 2. Transaksi Sukses -->
            <div class="stat-box">
                <div class="stat-icon icon-transaksi"><i class="fa-solid fa-circle-check"></i></div>
                <div class="stat-info">
                    <h3>Sewa Sukses</h3>
                    <p><?= $total_transaksi ?> Transaksi</p>
                </div>
            </div>

            <!-- 3. Transaksi Batal -->
            <div class="stat-box">
                <div class="stat-icon icon-batal"><i class="fa-solid fa-circle-xmark"></i></div>
                <div class="stat-info">
                    <h3>Sewa Dibatalkan</h3>
                    <p><?= $total_batal ?> Transaksi</p>
                </div>
            </div>
        </div>

        <!-- Card Note Penjelasan Ringkas -->
        <div class="report-card-info">
            <h2 style="font-size: 18px; color: var(--primary); margin: 0 0 10px 0;"><i class="fa-solid fa-circle-info"></i> Informasi Pembukuan</h2>
            <p style="font-size: 14px; color: var(--text-light); line-height: 1.6; margin: 0;">
                Data di atas dihitung secara otomatis berdasarkan transaksi yang telah diselesaikan oleh kasir melalui halaman <strong>Validasi Order</strong>. Data log rincian per-transaksi sepenuhnya disimpan dan dapat dipantau langsung oleh masing-masing pelanggan di halaman riwayat pesanan mereka sendiri.
            </p>
        </div>
    </main>

</body>
</html>