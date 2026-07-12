<?php
/* ============================================================
   OMAH OUTDOOR - VALIDASI ORDER MODERN (SIDEBAR 6 MENU)
   ============================================================ */

session_start();
require_once 'koneksi.php';

// Keamanan: Hanya admin yang boleh masuk ke halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: katalog.php'); 
    exit;
}

// Logika Proses Konfirmasi Status (Jika tombol diklik)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    
    if ($action === 'konfirmasi') {
        $update = "UPDATE orders SET status = 'dikonfirmasi' WHERE id = $id";
        mysqli_query($conn, $update);
    } elseif ($action === 'selesai') {
        $update = "UPDATE orders SET status = 'selesai' WHERE id = $id";
        mysqli_query($conn, $update);
    } elseif ($action === 'batal') {
        $update = "UPDATE orders SET status = 'dibatalkan' WHERE id = $id";
        mysqli_query($conn, $update);
    }
    header("Location: validasi_order.php");
    exit;
}

// Ambil data order digabung dengan nama produk & nama user
$query_order = "SELECT o.*, p.nama_produk, u.nama AS nama_penyewa 
                FROM orders o 
                JOIN produk p ON o.produk_id = p.id 
                JOIN users u ON o.user_id = u.id 
                ORDER BY o.id DESC";
$result_order = mysqli_query($conn, $query_order);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Order - Omah Outdoor</title>
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

        .table-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: var(--shadow, 0 4px 20px rgba(0,0,0,0.05));
            width: 100%;
            box-sizing: border-box;
            margin-top: 25px;
        }

        .table-responsive {
            width: 100% !important;
            box-sizing: border-box;
            border-radius: 12px;
            border: 1px solid #EDF0EC;
            margin-top: 20px;
            overflow-x: auto;
        }

        .admin-table {
            width: 100% !important;
            border-collapse: collapse;
            text-align: left;
        }

        .admin-table th, .admin-table td {
            padding: 16px 15px;
            font-size: 14px;
            vertical-align: middle;
        }

        .admin-table th {
            background: #F8FAF8;
            color: #6B7280;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 12px;
        }

        .admin-table td {
            border-bottom: 1px solid #EDF0EC;
            color: #374151;
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 50px;
            font-weight: 700;
            display: inline-block;
        }
        .status-pending { background: #FFF9E6; color: #D97706; }
        .status-dikonfirmasi { background: #EBF7EE; color: #16A34A; }
        .status-selesai { background: #E0F2FE; color: #0284C7; }
        .status-dibatalkan { background: #FEE2E2; color: #DC2626; }

        /* Action Buttons */
        .btn-action {
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-right: 5px;
            transition: 0.2s;
        }
        .btn-confirm { background-color: #16A34A; color: white; }
        .btn-confirm:hover { background-color: #15803D; }
        .btn-finish { background-color: #0284C7; color: white; }
        .btn-finish:hover { background-color: #0369A1; }
        .btn-cancel { background-color: #DC2626; color: white; }
        .btn-cancel:hover { background-color: #B91C1C; }
        
        .txt-done {
            color: #9CA3AF;
            font-style: italic;
            font-size: 13px;
        }
    </style>
</head>
<body>

    <!-- SIDEBAR KIRI (SINKRON DENGAN 6 MENU ANDA) -->
    <aside class="sidebar">
        <div>
            <a href="dashboard_admin.php" class="logo">
                <div class="logo-icon"><i class="fa-solid fa-mountain-sun"></i></div>
                <div class="logo-text"><h2>OMAH</h2><span>OUTDOOR</span></div>
            </a>
            
            <div class="sidebar-menu">
                <a href="dashboard_admin.php" class="menu"><i class="fa-solid fa-gauge-high"></i><span>Beranda</span></a>
                <a href="katalog.php" class="menu"><i class="fa-solid fa-images"></i><span>Lihat Katalog</span></a>
                <a href="kelola_produk.php" class="menu"><i class="fa-solid fa-box-open"></i><span>Kelola Produk</span></a>
                <a href="validasi_order.php" class="menu active"><i class="fa-solid fa-clipboard-check"></i><span>Validasi Order</span></a>
                <a href="laporan.php" class="menu"><i class="fa-solid fa-file-invoice"></i><span>Laporan</span></a>
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

    <!-- KONTEN UTAMA DASHBOARD -->
    <main class="main-content">
        <div class="header-section">
            <h1 style="font-size: 28px; color: #2D4A3E; font-weight: 800;">🔍 Validasi & Verifikasi Order</h1>
            <p style="font-size: 14px; color: #6B7280;">Pantau, setujui, dan atur pembaruan status penyewaan alat perlengkapan outdoor di sini.</p>
        </div>

        <!-- Tabel Kemasan Kartu Modern -->
        <div class="table-card">
            <h2 style="font-size: 18px; color: #2D4A3E; margin: 0;"><i class="fa-solid fa-list-check"></i> Antrean Transaksi Masuk</h2>
            
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Kode Order</th>
                            <th>Penyewa</th>
                            <th>Alat Outdoor</th>
                            <th>Durasi</th>
                            <th>Total Tagihan</th>
                            <th>Status</th>
                            <th style="text-align: center;">Aksi Kendali</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (mysqli_num_rows($result_order) > 0):
                            while ($row = mysqli_fetch_assoc($result_order)): 
                                $status_raw = strtolower($row['status']);
                        ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($row['kode_order']) ?></strong></td>
                            <td><?= htmlspecialchars($row['nama_penyewa']) ?></td>
                            <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                            <td><?= $row['durasi_hari'] ?> Hari</td>
                            <td><strong>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></strong></td>
                            <td>
                                <span class="status-badge status-<?= $status_raw ?>">
                                    <?= ucfirst($row['status']) ?>
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <?php if ($status_raw === 'pending'): ?>
                                    <a href="validasi_order.php?action=konfirmasi&id=<?= $row['id'] ?>" class="btn-action btn-confirm" onclick="return confirm('Setujui pesanan ini?')"><i class="fa-solid fa-check"></i> Setujui</a>
                                    <a href="validasi_order.php?action=batal&id=<?= $row['id'] ?>" class="btn-action btn-cancel" onclick="return confirm('Batalkan pesanan ini?')"><i class="fa-solid fa-xmark"></i> Tolak</a>
                                
                                <?php elseif ($status_raw === 'dikonfirmasi'): ?>
                                    <a href="validasi_order.php?action=selesai&id=<?= $row['id'] ?>" class="btn-action btn-finish" onclick="return confirm('Selesaikan sewa? Pastikan barang sudah kembali.')"><i class="fa-solid fa-box-open"></i> Selesai Sewa</a>
                                
                                <?php else: ?>
                                    <span class="txt-done"><i class="fa-solid fa-circle-minus"></i> Selesai di-Audit</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php 
                            endwhile; 
                        else:
                        ?>
                        <tr>
                            <td colspan="7" style="text-align:center; padding: 40px; color: #6B7280;">Tidak ada pesanan masuk dalam catatan sistem.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>