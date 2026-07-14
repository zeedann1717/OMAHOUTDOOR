<?php
require_once 'cek_admin.php';
require_once 'koneksi.php';

// Ambil semua produk
$result       = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
$total_produk = mysqli_num_rows($result);

// Ambil data produk untuk form edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $edit_id   = (int) $_GET['edit'];
    $res_edit  = mysqli_query($conn, "SELECT * FROM produk WHERE id=$edit_id");
    $edit_data = mysqli_fetch_assoc($res_edit);
}

// Pesan notifikasi
$pesan = $_GET['pesan'] ?? '';
$notif = ''; $notif_class = '';
if ($pesan === 'tambah_sukses') { $notif = '✅ Produk berhasil ditambahkan!'; $notif_class = 'notif-sukses'; }
elseif ($pesan === 'edit_sukses')   { $notif = '✅ Produk berhasil diupdate!';    $notif_class = 'notif-sukses'; }
elseif ($pesan === 'hapus_sukses')  { $notif = '🗑️ Produk berhasil dihapus!';    $notif_class = 'notif-hapus';  }
elseif ($pesan === 'gagal')         { $notif = '❌ Terjadi kesalahan, coba lagi.'; $notif_class = 'notif-gagal';  }

$nama_admin = htmlspecialchars($_SESSION['nama'] ?? 'Administrator');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Omah Outdoor</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏕️</text></svg>">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        html, body { max-width: 100%; overflow-x: hidden; }

        .admin-main { margin-left: 260px; padding: 32px; min-height: 100vh; background: #f0f4f8; }

        /* Tabel */
        .table-responsive {
            width: 100%; overflow-x: auto;
            border-radius: 12px;
            border: 1px solid #EDF0EC;
            margin-top: 16px;
        }

        .admin-table {
            width: 100%; border-collapse: collapse;
            text-align: left; table-layout: fixed;
        }

        .admin-table th:nth-child(1) { width: 44px;  text-align: center; }
        .admin-table th:nth-child(2) { width: 70px;  text-align: center; }
        .admin-table th:nth-child(3) { width: auto; }
        .admin-table th:nth-child(4) { width: 110px; }
        .admin-table th:nth-child(5) { width: 80px;  text-align: center; }
        .admin-table th:nth-child(6) { width: 120px; text-align: center; }
        .admin-table th:nth-child(7) { width: 160px; text-align: center; }

        .admin-table th, .admin-table td {
            padding: 14px 12px;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .admin-table th {
            background: #F8FAF8; color: #74817B;
            font-weight: 700; font-size: 11px;
            text-transform: uppercase; letter-spacing: .4px;
        }

        .admin-table td { border-bottom: 1px solid #EDF0EC; vertical-align: middle; }
        .admin-table tbody tr:hover { background: #fafffe; }
        .admin-table tbody tr:last-child td { border-bottom: none; }

        .img-thumb {
            width: 46px; height: 46px;
            border-radius: 10px; object-fit: cover;
            display: inline-block; border: 1px solid #e2e8f0;
        }

        .img-placeholder {
            width: 46px; height: 46px;
            border-radius: 10px; background: #EAECE7;
            display: inline-flex; align-items: center;
            justify-content: center; color: #74817B;
            font-size: 18px;
        }

        /* Status badge */
        .badge-status {
            padding: 5px 12px; border-radius: 50px;
            font-size: 12px; font-weight: 700;
            display: inline-flex; align-items: center; gap: 5px;
        }
        .badge-tersedia { background: #DFF7E7; color: #227247; }
        .badge-disewa   { background: #FEE2E2; color: #DC2626; }

        /* Stok */
        .stok-ok  { color: #227247; font-weight: 700; }
        .stok-low { color: #DC2626; font-weight: 700; }

        /* Action buttons */
        .btn-action {
            padding: 7px 12px; border-radius: 8px; font-size: 12px;
            font-weight: 600; text-decoration: none;
            display: inline-flex; align-items: center; gap: 5px;
            transition: all .2s;
        }
        .btn-edit   { background: #E7EFFF; color: #2F62D6; }
        .btn-edit:hover { background: #2F62D6; color: #fff; }
        .btn-delete {
            background: #FEE2E2; color: #DC2626;
            border: none; cursor: pointer; margin-left: 4px;
        }
        .btn-delete:hover { background: #DC2626; color: #fff; }

        /* Form card */
        .form-card {
            background: #fff; border-radius: 16px;
            padding: 28px; box-shadow: 0 2px 10px rgba(0,0,0,.06);
            margin-bottom: 24px;
        }

        .form-card-title {
            font-size: 17px; font-weight: 700;
            color: #1b4332; margin-bottom: 20px;
            padding-bottom: 12px; border-bottom: 2px solid #f1f5f9;
        }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

        .form-group { margin-bottom: 0; }
        .form-group.full { grid-column: span 2; }

        .form-group label {
            display: block; font-size: 12px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .4px;
            color: #64748b; margin-bottom: 6px;
        }

        .form-group input,
        .form-group select {
            width: 100%; padding: 11px 14px;
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            font-size: 14px; font-family: 'Inter', sans-serif;
            background: #fafafa; outline: none;
            transition: border-color .2s; box-sizing: border-box;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #2d6a4f; background: #fff;
        }

        .form-group input[type="file"] {
            padding: 8px; font-size: 13px; background: #fff;
        }

        .preview-gambar { margin-bottom: 8px; }
        .preview-gambar img {
            width: 70px; height: 70px; object-fit: cover;
            border-radius: 10px; border: 2px solid #e2e8f0;
        }
        .preview-gambar small { display: block; font-size: 11px; color: #94a3b8; margin-top: 4px; }

        .form-actions { display: flex; gap: 10px; margin-top: 20px; }

        .btn-submit {
            padding: 11px 24px; border: none; border-radius: 10px;
            background: #1b4332; color: #fff; font-weight: 700;
            font-size: 14px; cursor: pointer;
            display: inline-flex; align-items: center; gap: 8px;
            transition: background .2s, transform .2s;
        }
        .btn-submit:hover { background: #2d6a4f; transform: translateY(-2px); }

        .btn-batal {
            padding: 11px 20px; border-radius: 10px;
            background: #f1f5f9; color: #64748b; font-weight: 600;
            font-size: 14px; text-decoration: none;
            display: inline-flex; align-items: center; gap: 6px;
            transition: background .2s;
        }
        .btn-batal:hover { background: #e2e8f0; }

        /* Notif */
        .notif-bar { padding: 14px 20px; border-radius: 10px; margin-bottom: 20px; font-weight: 600; font-size: 14px; }
        .notif-sukses { background: #d1fae5; color: #065f46; }
        .notif-hapus  { background: #fee2e2; color: #991b1b; }
        .notif-gagal  { background: #fef3c7; color: #92400e; }

        /* Table card */
        .table-card {
            background: #fff; border-radius: 16px;
            padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,.06);
        }

        .table-header {
            display: flex; justify-content: space-between;
            align-items: center; margin-bottom: 4px;
        }

        .table-header h2 { font-size: 17px; font-weight: 700; color: #1b4332; }

        .table-count {
            background: #EEF5F1; color: #1b4332;
            padding: 5px 14px; border-radius: 50px;
            font-weight: 700; font-size: 12px;
        }

        @media (max-width: 900px) {
            .form-grid { grid-template-columns: 1fr; }
            .form-group.full { grid-column: span 1; }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div>
            <a href="dashboard_admin.php" class="logo" style="display:flex;align-items:center;gap:10px;padding:24px 20px;text-decoration:none;">
                <div style="width:40px;height:40px;background:#95d5b2;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;">🏕️</div>
                <div>
                    <div style="font-family:'Poppins',sans-serif;font-weight:700;color:#fff;font-size:16px;line-height:1;">OMAH</div>
                    <div style="font-size:11px;color:#95d5b2;letter-spacing:1px;">OUTDOOR</div>
                </div>
            </a>

            <nav class="sidebar-nav" style="padding:0 16px;">
                <a href="dashboard_admin.php" class="nav-item">
                    <span class="nav-icon"><i class="fa-solid fa-gauge-high"></i></span> Dashboard
                </a>
                <a href="kelola_produk.php" class="nav-item active">
                    <span class="nav-icon"><i class="fa-solid fa-box-open"></i></span> Kelola Produk
                </a>
                <a href="validasi_order.php" class="nav-item">
                    <span class="nav-icon"><i class="fa-solid fa-clipboard-check"></i></span> Validasi Order
                </a>
                <a href="katalog.php" class="nav-item">
                    <span class="nav-icon"><i class="fa-solid fa-images"></i></span> Lihat Katalog
                </a>
                <a href="index.php" class="nav-item">
                    <span class="nav-icon"><i class="fa-solid fa-house"></i></span> Ke Beranda
                </a>
            </nav>
        </div>

        <div class="sidebar-footer">
            <a href="logout.php" class="btn-sidebar-logout">
                <i class="fa-solid fa-power-off"></i> Logout
            </a>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="admin-main">

        <!-- TOP BAR -->
        <header class="admin-topbar">
            <div class="topbar-left">
                <h1 class="page-title">Kelola Produk</h1>
                <p class="page-subtitle">Tambah, edit, atau hapus produk katalog</p>
            </div>
            <div class="topbar-right">
                <div class="admin-badge">
                    <span class="badge-avatar">👤</span>
                    <div>
                        <p class="badge-name"><?= $nama_admin ?></p>
                        <span class="badge-role">Administrator</span>
                    </div>
                </div>
            </div>
        </header>

        <?php if ($notif) : ?>
        <div class="notif-bar <?= $notif_class ?>"><?= $notif ?></div>
        <?php endif; ?>

        <!-- FORM TAMBAH / EDIT -->
        <div class="form-card">
            <h2 class="form-card-title">
                <?= $edit_data ? '<i class="fa-solid fa-pen"></i> Edit Produk' : '<i class="fa-solid fa-plus"></i> Tambah Produk Baru' ?>
            </h2>
            <form action="proses_produk.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="<?= $edit_data ? 'edit' : 'tambah' ?>">
                <?php if ($edit_data) : ?>
                <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
                <?php endif; ?>

                <div class="form-grid">
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
                               value="<?= $edit_data['jumlah_stok'] ?? 1 ?>"
                               placeholder="Contoh: 3">
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="tersedia" <?= (!$edit_data || $edit_data['status'] === 'tersedia') ? 'selected' : '' ?>>Tersedia</option>
                            <option value="disewa"   <?= ($edit_data && $edit_data['status'] === 'disewa') ? 'selected' : '' ?>>Disewa</option>
                        </select>
                    </div>

                    <div class="form-group full">
                        <label>Foto Produk <?= $edit_data ? '(kosongkan jika tidak ganti)' : '' ?></label>
                        <?php if ($edit_data && $edit_data['gambar']) : ?>
                        <div class="preview-gambar">
                            <img src="assets/images/<?= htmlspecialchars($edit_data['gambar']) ?>"
                                 alt="Foto saat ini" onerror="this.style.display='none'">
                            <small>Foto saat ini</small>
                        </div>
                        <?php endif; ?>
                        <input type="file" name="gambar" accept="image/*">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <?= $edit_data ? 'Simpan Perubahan' : 'Tambah Produk' ?>
                    </button>
                    <?php if ($edit_data) : ?>
                    <a href="kelola_produk.php" class="btn-batal">
                        <i class="fa-solid fa-xmark"></i> Batal
                    </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- TABEL PRODUK -->
        <div class="table-card">
            <div class="table-header">
                <h2><i class="fa-solid fa-list"></i> Daftar Katalog Alat Outdoor</h2>
                <span class="table-count"><?= $total_produk ?> Item Terdaftar</span>
            </div>

            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Foto</th>
                            <th>Nama Produk</th>
                            <th>Harga/Hari</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($total_produk > 0) : ?>
                        <?php $no = 1; while ($p = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td style="text-align:center;"><?= $no++ ?></td>
                            <td style="text-align:center;">
                                <?php if (!empty($p['gambar'])) : ?>
                                    <img src="assets/images/<?= htmlspecialchars($p['gambar']) ?>"
                                         class="img-thumb" alt="<?= htmlspecialchars($p['nama_produk']) ?>"
                                         onerror="this.style.display='none'">
                                <?php else : ?>
                                    <div class="img-placeholder"><i class="fa-solid fa-image"></i></div>
                                <?php endif; ?>
                            </td>
                            <td><strong><?= htmlspecialchars($p['nama_produk']) ?></strong></td>
                            <td>Rp <?= number_format($p['harga_per_hari'], 0, ',', '.') ?></td>
                            <td style="text-align:center;">
                                <span class="<?= $p['jumlah_stok'] > 0 ? 'stok-ok' : 'stok-low' ?>">
                                    <?= $p['jumlah_stok'] ?> Unit
                                </span>
                            </td>
                            <td style="text-align:center;">
                                <?php if ($p['status'] === 'tersedia') : ?>
                                    <span class="badge-status badge-tersedia">
                                        <i class="fa-solid fa-circle-check"></i> Tersedia
                                    </span>
                                <?php else : ?>
                                    <span class="badge-status badge-disewa">
                                        <i class="fa-solid fa-circle-minus"></i> Disewa
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align:center;">
                                <a href="kelola_produk.php?edit=<?= $p['id'] ?>" class="btn-action btn-edit">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                <a href="proses_produk.php?action=hapus&id=<?= $p['id'] ?>"
                                   class="btn-action btn-delete"
                                   onclick="return confirm('Yakin hapus produk ini?')">
                                    <i class="fa-solid fa-trash-can"></i> Hapus
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7" style="text-align:center;padding:50px;color:#94a3b8;">
                                <i class="fa-solid fa-box-open" style="font-size:40px;display:block;margin-bottom:12px;"></i>
                                Belum ada produk di katalog.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

</body>
</html>
