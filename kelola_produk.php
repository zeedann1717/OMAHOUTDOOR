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
if ($pesan === 'tambah_sukses') { $notif = '✅ Produk berhasil ditambahkan!';  $notif_class = 'notif-sukses'; }
elseif ($pesan === 'edit_sukses')  { $notif = '✅ Produk berhasil diupdate!';   $notif_class = 'notif-sukses'; }
elseif ($pesan === 'hapus_sukses') { $notif = '🗑️ Produk berhasil dihapus!';   $notif_class = 'notif-hapus';  }
elseif ($pesan === 'gagal')        { $notif = '❌ Terjadi kesalahan, coba lagi.'; $notif_class = 'notif-gagal';  }

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
        /* === NOTIFIKASI === */
        .notif-bar {
            padding: 14px 20px; border-radius: 12px;
            margin-bottom: 20px; font-weight: 600; font-size: 14px;
        }
        .notif-sukses { background: #d1fae5; color: #065f46; }
        .notif-hapus  { background: #fee2e2; color: #991b1b; }
        .notif-gagal  { background: #fef3c7; color: #92400e; }

        /* === PAGE HEADER === */
        .page-header {
            margin-bottom: 28px;
        }
        .page-header h1 {
            font-size: 26px; font-weight: 800;
            color: var(--primary); margin-bottom: 4px;
        }
        .page-header p { font-size: 13px; color: var(--text-light); }

        /* === FORM CARD === */
        .form-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 28px;
            box-shadow: var(--shadow);
            margin-bottom: 24px;
        }

        .form-card-title {
            font-size: 16px; font-weight: 700;
            color: var(--primary); margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 2px solid #F0F4F1;
            display: flex; align-items: center; gap: 8px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .fg { margin-bottom: 0; }
        .fg.full { grid-column: span 2; }

        .fg label {
            display: block; font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .5px;
            color: var(--text-light); margin-bottom: 7px;
        }

        .fg input,
        .fg select {
            width: 100%; padding: 11px 14px;
            border: 1.5px solid #EDF0EC;
            border-radius: 12px; font-size: 14px;
            font-family: 'Poppins', sans-serif;
            background: #F8FAF8; outline: none;
            color: var(--text);
            transition: border-color .2s, background .2s;
            box-sizing: border-box;
        }

        .fg input:focus, .fg select:focus {
            border-color: var(--primary);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(41,89,67,.08);
        }

        .fg input[type="file"] {
            padding: 9px; background: var(--white); cursor: pointer;
        }

        .preview-gambar { margin-bottom: 10px; }
        .preview-gambar img {
            width: 72px; height: 72px; object-fit: cover;
            border-radius: 12px; border: 2px solid #EDF0EC;
        }
        .preview-gambar small { display: block; font-size: 11px; color: #94a3b8; margin-top: 4px; }

        .form-actions { display: flex; gap: 10px; margin-top: 22px; }

        .btn-form-submit {
            padding: 11px 24px; border: none; border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: var(--white); font-weight: 700; font-size: 14px;
            font-family: 'Poppins', sans-serif; cursor: pointer;
            display: inline-flex; align-items: center; gap: 8px;
            transition: transform .2s, box-shadow .2s;
        }
        .btn-form-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(41,89,67,.2);
        }

        .btn-form-cancel {
            padding: 11px 20px; border-radius: 12px;
            background: #F0F4F1; color: var(--text-light);
            font-weight: 600; font-size: 14px;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: 8px;
            transition: background .2s;
        }
        .btn-form-cancel:hover { background: #E0E8E2; }

        /* === TABLE FIX === */
        .admin-table { table-layout: fixed; }
        .admin-table th:nth-child(1) { width: 44px;  text-align: center; }
        .admin-table th:nth-child(2) { width: 70px;  text-align: center; }
        .admin-table th:nth-child(3) { width: auto; }
        .admin-table th:nth-child(4) { width: 120px; }
        .admin-table th:nth-child(5) { width: 90px;  text-align: center; }
        .admin-table th:nth-child(6) { width: 130px; text-align: center; }
        .admin-table th:nth-child(7) { width: 170px; text-align: center; }

        .admin-table th, .admin-table td {
            overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        }

        /* Gambar thumbnail */
        .img-thumb {
            width: 48px; height: 48px; border-radius: 10px;
            object-fit: cover; border: 1px solid #EDF0EC;
            display: inline-block;
        }
        .img-placeholder {
            width: 48px; height: 48px; border-radius: 10px;
            background: #F0F4F1; display: inline-flex;
            align-items: center; justify-content: center;
            color: var(--text-light); font-size: 18px;
        }

        /* Status badge */
        .badge-tersedia {
            background: #DFF7E7; color: #227247;
            padding: 5px 12px; border-radius: 50px;
            font-size: 12px; font-weight: 700;
            display: inline-flex; align-items: center; gap: 5px;
        }
        .badge-disewa {
            background: #FEE2E2; color: #DC2626;
            padding: 5px 12px; border-radius: 50px;
            font-size: 12px; font-weight: 700;
            display: inline-flex; align-items: center; gap: 5px;
        }

        /* Stok warna */
        .stok-ok  { color: #227247; font-weight: 700; }
        .stok-low { color: #DC2626; font-weight: 700; }

        /* Action buttons */
        .btn-edit-tbl {
            padding: 7px 13px; border-radius: 8px; font-size: 12px;
            font-weight: 600; text-decoration: none;
            display: inline-flex; align-items: center; gap: 5px;
            background: #E7EFFF; color: #2F62D6;
            transition: all .2s;
        }
        .btn-edit-tbl:hover { background: #2F62D6; color: #fff; }

        .btn-del-tbl {
            padding: 7px 13px; border-radius: 8px; font-size: 12px;
            font-weight: 600; text-decoration: none;
            display: inline-flex; align-items: center; gap: 5px;
            background: #FEE2E2; color: #DC2626;
            border: none; cursor: pointer; margin-left: 4px;
            transition: all .2s;
        }
        .btn-del-tbl:hover { background: #DC2626; color: #fff; }

        @media (max-width: 900px) {
            .form-grid { grid-template-columns: 1fr; }
            .fg.full { grid-column: span 1; }
        }
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

            <div class="sidebar-menu">
                <a href="dashboard_admin.php" class="menu">
                    <i class="fa-solid fa-gauge-high"></i><span>Dashboard</span>
                </a>
                <a href="kelola_produk.php" class="menu active">
                    <i class="fa-solid fa-box-open"></i><span>Kelola Produk</span>
                </a>
                <a href="validasi_order.php" class="menu">
                    <i class="fa-solid fa-clipboard-check"></i><span>Validasi Order</span>
                </a>
                <a href="katalog.php" class="menu">
                    <i class="fa-solid fa-images"></i><span>Lihat Katalog</span>
                </a>
                <a href="index.php" class="menu">
                    <i class="fa-solid fa-house"></i><span>Ke Beranda</span>
                </a>
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

    <div class="main-content">

        <div class="page-header">
            <h1><i class="fa-solid fa-box-open"></i> Kelola Produk</h1>
            <p>Tambah, edit, atau hapus produk katalog sewa alat outdoor</p>
        </div>

        <?php if ($notif) : ?>
        <div class="notif-bar <?= $notif_class ?>"><?= $notif ?></div>
        <?php endif; ?>

        <div class="form-card">
            <div class="form-card-title">
                <?php if ($edit_data) : ?>
                    <i class="fa-solid fa-pen"></i> Edit Produk
                <?php else : ?>
                    <i class="fa-solid fa-plus"></i> Tambah Produk Baru
                <?php endif; ?>
            </div>

            <form action="proses_produk.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="<?= $edit_data ? 'edit' : 'tambah' ?>">
                <?php if ($edit_data) : ?>
                <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
                <?php endif; ?>

                <div class="form-grid">
                    <div class="fg">
                        <label>Nama Produk</label>
                        <input type="text" name="nama_produk" required
                               value="<?= htmlspecialchars($edit_data['nama_produk'] ?? '') ?>"
                               placeholder="Contoh: Tenda Dome Kap. 4">
                    </div>

                    <div class="fg">
                        <label>Harga / Hari (Rp)</label>
                        <input type="number" name="harga_per_hari" required min="0"
                               value="<?= $edit_data['harga_per_hari'] ?? '' ?>"
                               placeholder="Contoh: 35000">
                    </div>

                    <div class="fg">
                        <label>Jumlah Stok (Unit)</label>
                        <input type="number" name="jumlah_stok" required min="0"
                               value="<?= $edit_data['jumlah_stok'] ?? 1 ?>"
                               placeholder="Contoh: 3">
                    </div>

                    <div class="fg">
                        <label>Status</label>
                        <select name="status">
                            <option value="tersedia" <?= (!$edit_data || $edit_data['status'] === 'tersedia') ? 'selected' : '' ?>>✅ Tersedia</option>
                            <option value="disewa"   <?= ($edit_data && $edit_data['status'] === 'disewa') ? 'selected' : '' ?>>🔴 Disewa</option>
                        </select>
                    </div>

                    <div class="fg full">
                        <label>Foto Produk <?= $edit_data ? '(kosongkan jika tidak ganti)' : '' ?></label>
                        <?php if ($edit_data && !empty($edit_data['gambar'])) : ?>
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
                    <button type="submit" class="btn-form-submit">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <?= $edit_data ? 'Simpan Perubahan' : 'Tambah Produk' ?>
                    </button>
                    <?php if ($edit_data) : ?>
                    <a href="kelola_produk.php" class="btn-form-cancel">
                        <i class="fa-solid fa-xmark"></i> Batal
                    </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="table-card">
            <div class="table-header">
                <h2><i class="fa-solid fa-list"></i> Daftar Katalog Alat Outdoor</h2>
                <span class="table-badge"><?= $total_produk ?> Item Terdaftar</span>
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
                                         class="img-thumb"
                                         alt="<?= htmlspecialchars($p['nama_produk']) ?>"
                                         onerror="this.style.display='none'">
                                <?php else : ?>
                                    <div class="img-placeholder">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><strong><?= htmlspecialchars($p['nama_produk']) ?></strong></td>
                            <td>Rp <?= number_format($p['harga_per_hari'], 0, ',', '.') ?></td>
                            <td style="text-align:center;">
                                <?php 
                                $stok_sekarang = isset($p['jumlah_stok']) ? (int)$p['jumlah_stok'] : 0;
                                ?>
                                <span class="<?= $stok_sekarang > 0 ? 'stok-ok' : 'stok-low' ?>">
                                    <?= $stok_sekarang ?> Unit
                                </span>
                            </td>
                            <td style="text-align:center;">
                                <?php if ($p['status'] === 'tersedia') : ?>
                                    <span class="badge-tersedia">
                                        <i class="fa-solid fa-circle-check"></i> Tersedia
                                    </span>
                                <?php else : ?>
                                    <span class="badge-disewa">
                                        <i class="fa-solid fa-circle-minus"></i> Disewa
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align:center;">
                                <a href="kelola_produk.php?edit=<?= $p['id'] ?>" class="btn-edit-tbl">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                <a href="proses_produk.php?action=hapus&id=<?= $p['id'] ?>"
                                   class="btn-del-tbl"
                                   onclick="return confirm('Yakin hapus produk ini?')">
                                    <i class="fa-solid fa-trash-can"></i> Hapus
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7" style="text-align:center;padding:50px;color:var(--text-light);">
                                <i class="fa-solid fa-box-open" style="font-size:40px;display:block;margin-bottom:12px;opacity:.4;"></i>
                                Belum ada produk di katalog.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div></body>
</html>