<?php
/* ============================================================
   OMAH OUTDOOR - KELOLA PRODUK (DESAIN ASLI & STRUKTUR ASSETS FIX)
   ============================================================ */

require_once 'cek_admin.php'; // Memastikan session admin aktif
require_once 'koneksi.php';   // Menghubungkan ke database

// Mengambil data produk dari database
$query = "SELECT * FROM produk ORDER BY id DESC";
$result = mysqli_query($conn, $query);
$total_produk = mysqli_num_rows($result);

// Mengambil nama admin untuk profile header
$nama_admin = htmlspecialchars($_SESSION['nama'] ?? 'Administrator');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Omah Outdoor</title>
    <link rel="stylesheet" href="assets/css/admin.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    
    <style>
        /* FIX TOTAL: Memaksa halaman pas layar tanpa scroll geser kanan */
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
            overflow-x: hidden !important;
        }

        /* Mempertahankan susunan ke bawah khas struktur lama Anda */
        .old-design-container {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 30px;
            box-sizing: border-box;
        }

        /* Tampilan Box putih Form & Tabel bawaan desain asli */
        .form-card, .table-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 30px;
            box-shadow: var(--shadow);
            width: 100%;
            box-sizing: border-box;
        }

        /* 2 Kolom Grid Form Atas */
        .form-grid-layout {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text);
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid #EDF0EC;
            background: #F8FAF8;
            font-family: inherit;
            font-size: 14px;
            color: var(--text);
            box-sizing: border-box;
        }

        .full-width-group {
            grid-column: span 2;
        }

        .btn-submit {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: var(--white);
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        /* TABLE AUTO-FIT (Mencegah Kepotong ke Kanan) */
        .table-responsive {
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box;
            border-radius: 16px;
            border: 1px solid #EDF0EC;
            margin-top: 20px;
        }

        .admin-table {
            width: 100% !important;
            border-collapse: collapse;
            text-align: left;
            table-layout: fixed; /* Kunci kolom agar membagi rata sisa ruang */
        }

        /* Pengaturan pembagian kolom tabel agar proporsional */
        .admin-table th:nth-child(1), .admin-table td:nth-child(1) { width: 45px; text-align: center; }
        .admin-table th:nth-child(2), .admin-table td:nth-child(2) { width: 85px; text-align: center; }
        .admin-table th:nth-child(3), .admin-table td:nth-child(3) { width: auto; } 
        .admin-table th:nth-child(4), .admin-table td:nth-child(4) { width: 150px; }
        .admin-table th:nth-child(5), .admin-table td:nth-child(5) { width: 130px; }
        .admin-table th:nth-child(6), .admin-table td:nth-child(6) { width: 190px; text-align: center; }

        .admin-table th, .admin-table td {
            padding: 16px 15px;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .admin-table th {
            background: #F8FAF8;
            color: var(--text-light);
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
        }

        .admin-table td {
            border-bottom: 1px solid #EDF0EC;
            vertical-align: middle;
        }

        /* UKURAN GAMBAR PAS */
        .img-thumb { 
            width: 48px; 
            height: 48px; 
            border-radius: 10px; 
            object-fit: cover; 
            display: inline-block;
        }

        .btn-action {
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-edit { background: #E7EFFF; color: #2F62D6; }
        .btn-edit:hover { background: #2F62D6; color: var(--white); }
        .btn-delete { background: #FEE2E2; color: #DC2626; margin-left: 4px; border: none; cursor: pointer; }
        .btn-delete:hover { background: #DC2626; color: var(--white); }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        .page-title h1 { font-size: 28px; color: var(--primary); font-weight: 800; }
        .page-title p { font-size: 14px; color: var(--text-light); }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div>
            <a href="dashboard_admin.php" class="logo">
                <div class="logo-icon">
                    <i class="fa-solid fa-mountain-sun"></i>
                </div>
                <div class="logo-text">
                    <h2>OMAH</h2>
                    <span>OUTDOOR</span>
                </div>
            </a>
            
            <!-- GANTI BLOK SIDEBAR MENU DI DASHBOARD_ADMIN.PHP DENGAN INI -->
<div class="sidebar-menu">
    <!-- Menu Beranda (Di-set ACTIVE karena kita sedang berada di halaman beranda/dashboard) -->
    <a href="dashboard_admin.php" class="menu active"><i class="fa-solid fa-gauge-high"></i><span>Beranda</span></a>
    
    <!-- Link ke Katalog -->
    <a href="katalog.php" class="menu"><i class="fa-solid fa-images"></i><span>Lihat Katalog</span></a>
    
    <!-- Link ke Kelola Produk -->
    <a href="kelola_produk.php" class="menu"><i class="fa-solid fa-box-open"></i><span>Kelola Produk</span></a>
    
    <!-- Link ke Validasi Order -->
    <a href="validasi_order.php" class="menu"><i class="fa-solid fa-clipboard-check"></i><span>Validasi Order</span></a>
    
    <!-- Link ke Laporan Keuangan -->
    <a href="laporan.php" class="menu"><i class="fa-solid fa-file-invoice"></i><span>Laporan</span></a>
</div>
        </div>

        <div>
            <div class="camp-card">
                <i class="fa-solid fa-tent"></i>
                <h4>Adventure Mode</h4>
                <p>Sistem Siap Digunakan</p>
            </div>
            <a href="logout.php" class="logout-btn">
                <i class="fa-solid fa-power-off"></i>
                <span>Keluar</span>
            </a>
        </div>
    </aside>

    <main class="admin-main">

        <header class="admin-topbar">
            <div class="topbar-left">
                <h1 class="page-title">Kelola Produk</h1>
                <p class="page-subtitle">Tambah, edit, atau hapus produk katalog</p>
            </div>
        </div>

        <?php if ($notif) : ?>
        <div class="notif-bar <?= $notif_class ?>"><?= $notif ?></div>
        <?php endif; ?>

        <div class="produk-layout">

            <div class="form-card">
                <h2 class="form-card-title">
                    <?= $edit_data ? '✏️ Edit Produk' : '➕ Tambah Produk Baru' ?>
                </h2>
                <form action="proses_produk.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="<?= $edit_data ? 'edit' : 'tambah' ?>">
                    <?php if ($edit_data) : ?>
                    <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label>Nama Produk</label>
                        <input type="text" name="nama_produk" required
                               value="<?= htmlspecialchars($edit_data['nama_produk'] ?? '') ?>"
                               placeholder="Contoh: Tenda Dome Kap. 4">
                    </div>

                    <div class="form-group">
                        <label>Harga / Hari (Rp)</label>
                        <input type="number" name="harga_per_hari" required min="0"
                               value="<?= $edit_data['harga_per_hari'] ?? '' ?>"
                               placeholder="Contoh: 35000">
                    </div>

                    <div class="form-group">
                        <label>Jumlah Stok (Unit)</label>
                        <input type="number" name="jumlah_stok" required min="0"
                               value="<?= isset($edit_data['jumlah_stok']) ? $edit_data['jumlah_stok'] : '1' ?>"
                               placeholder="Contoh: 5">
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="tersedia" <?= (!$edit_data || $edit_data['status'] === 'tersedia') ? 'selected' : '' ?>>Tersedia</option>
                            <option value="disewa" <?= ($edit_data && $edit_data['status'] === 'disewa') ? 'selected' : '' ?>>Disewa</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Foto Produk <?= $edit_data ? '(kosongkan jika tidak ganti)' : '' ?></label>
                        <?php if ($edit_data && $edit_data['gambar']) : ?>
                        <div class="preview-gambar">
                            <img src="assets/images/<?= htmlspecialchars($edit_data['gambar']) ?>"
                                 alt="Foto saat ini" onerror="this.style.display='none'">
                            <small>Foto saat ini</small>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-section" style="flex:1; min-width:0;">
                <div class="table-header">
                    <h2 style="font-size: 18px; color: var(--primary);"><i class="fa-solid fa-list"></i> Daftar Katalog Alat Outdoor</h2>
                    <span class="table-badge" style="background: #EEF5F1; color: var(--primary); padding: 6px 14px; border-radius: 50px; font-weight:700; font-size:12px;"><?= $total_produk; ?> Item Terdaftar</span>
                </div>
                
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Foto</th>
                                <th>Nama Produk</th>
                                <th>Harga/Hari</th>
                                <th>Stok</th> <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while ($p = mysqli_fetch_assoc($produk_list)) : 
                                $stok_qty = $p['jumlah_stok'] ?? 0;
                            ?>
                            <tr>
                                <td style="text-align: center;"><?= $no++; ?></td>
                                <td style="text-align: center;">
                                    <?php if(!empty($nama_gambar)): ?>
                                        <img src="assets/images/<?= htmlspecialchars($nama_gambar); ?>" class="img-thumb" alt="Foto">
                                    <?php else: ?>
                                        <div class="img-thumb" style="background: #EAECE7; display:flex; align-items:center; justify-content:center; color:#74817B;"><i class="fa-solid fa-image"></i></div>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?= htmlspecialchars($p['nama_produk']) ?></strong></td>
                                <td>Rp <?= number_format($p['harga_per_hari'], 0, ',', '.') ?></td>
                                <td style="<?= $stok_qty == 0 ? 'color: red; font-weight: bold;' : '' ?>">
                                    <?= $stok_qty ?> Unit
                                </td>
                                <td>
                                    <?php if (isset($row['status']) && strtolower($row['status']) === 'tersedia'): ?>
                                        <span class="badge admin" style="background:#DFF7E7; color:#227247; padding:6px 12px; font-size:12px; border-radius:50px; font-weight:700; display:inline-block;"><i class="fa-solid fa-circle-check"></i> Tersedia</span>
                                    <?php else: ?>
                                        <span class="badge user" style="background:#FEE2E2; color:#DC2626; padding:6px 12px; font-size:12px; border-radius:50px; font-weight:700; display:inline-block;"><i class="fa-solid fa-circle-minus"></i> Disewa</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <a href="edit_produk.php?id=<?= $row['id']; ?>" class="btn-action btn-edit">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                    <a href="hapus_produk.php?id=<?= $row['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                        <i class="fa-solid fa-trash-can"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php 
                                endwhile; 
                            else:
                            ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-light);">Belum ada data produk di katalog.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div></main>

</body>
</html>