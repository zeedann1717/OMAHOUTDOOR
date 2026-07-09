<?php
require_once 'cek_admin.php';
require_once 'koneksi.php';

$produk_list   = mysqli_query($conn, "SELECT * FROM produk ORDER BY id ASC");
$total_produk  = mysqli_num_rows($produk_list);

// Ambil data produk untuk form edit jika ada ?edit=id
$edit_data = null;
if (isset($_GET['edit'])) {
    $edit_id   = (int) $_GET['edit'];
    $res_edit  = mysqli_query($conn, "SELECT * FROM produk WHERE id=$edit_id");
    $edit_data = mysqli_fetch_assoc($res_edit);
}

// Pesan notifikasi
$pesan = $_GET['pesan'] ?? '';
$notif = '';
$notif_class = '';
if ($pesan === 'tambah_sukses') { $notif = '✅ Produk berhasil ditambahkan!'; $notif_class = 'notif-sukses'; }
elseif ($pesan === 'edit_sukses') { $notif = '✅ Produk berhasil diupdate!'; $notif_class = 'notif-sukses'; }
elseif ($pesan === 'hapus_sukses') { $notif = '🗑️ Produk berhasil dihapus!'; $notif_class = 'notif-hapus'; }
elseif ($pesan === 'gagal') { $notif = '❌ Terjadi kesalahan, coba lagi.'; $notif_class = 'notif-gagal'; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk | Omah Outdoor</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
</head>
<body class="admin-body">

    <aside class="sidebar">
        <div class="sidebar-brand">
            <span class="brand-icon">🏕️</span>
            <div>
                <h2>OMAH</h2>
                <span>OUTDOOR</span>
            </div>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard_admin.php" class="nav-item">
                <span class="nav-icon">📊</span> Dashboard
            </a>
            <a href="#section-users" class="nav-item" onclick="window.location='dashboard_admin.php#section-users'">
                <span class="nav-icon">👥</span> Data User
            </a>
            <a href="kelola_produk.php" class="nav-item active">
                <span class="nav-icon">📦</span> Kelola Produk
            </a>
            <a href="katalog.php" class="nav-item">
                <span class="nav-icon">🛍️</span> Lihat Katalog
            </a>
            <a href="index.php" class="nav-item">
                <span class="nav-icon">🏠</span> Ke Beranda
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="logout.php" class="btn-sidebar-logout">
                <span>🚪</span> Logout
            </a>
        </div>
    </aside>

    <main class="admin-main">

        <header class="admin-topbar">
            <div class="topbar-left">
                <h1 class="page-title">Kelola Produk</h1>
                <p class="page-subtitle">Tambah, edit, atau hapus produk katalog</p>
            </div>
            <div class="topbar-right">
                <div class="admin-badge">
                    <span class="badge-avatar">👤</span>
                    <div>
                        <p class="badge-name"><?= htmlspecialchars($_SESSION['nama'] ?? 'Admin') ?></p>
                        <span class="badge-role">Administrator</span>
                    </div>
                </div>
            </div>
        </header>

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
                        <?php endif; ?>
                        <input type="file" name="gambar" accept="image/*">
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-simpan">
                            <?= $edit_data ? '💾 Simpan Perubahan' : '➕ Tambah Produk' ?>
                        </button>
                        <?php if ($edit_data) : ?>
                        <a href="kelola_produk.php" class="btn-batal">✖ Batal</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="table-section" style="flex:1; min-width:0;">
                <div class="table-header">
                    <h2>📋 Daftar Produk</h2>
                    <span class="table-count"><?= $total_produk ?> produk</span>
                </div>
                <div class="table-wrapper">
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
                                <td><?= $no++ ?></td>
                                <td>
                                    <img src="assets/images/<?= htmlspecialchars($p['gambar']) ?>"
                                         alt="<?= htmlspecialchars($p['nama_produk']) ?>"
                                         class="tbl-img"
                                         onerror="this.src='assets/images/no-image.png'; this.onerror=null;">
                                </td>
                                <td><strong><?= htmlspecialchars($p['nama_produk']) ?></strong></td>
                                <td>Rp <?= number_format($p['harga_per_hari'], 0, ',', '.') ?></td>
                                <td style="<?= $stok_qty == 0 ? 'color: red; font-weight: bold;' : '' ?>">
                                    <?= $stok_qty ?> Unit
                                </td>
                                <td>
                                    <span class="status-badge <?= $p['status'] === 'disewa' ? 'disewa' : 'tersedia' ?>">
                                        <?= $p['status'] === 'disewa' ? '🔴 Disewa' : '🟢 Tersedia' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="aksi-btns">
                                        <a href="kelola_produk.php?edit=<?= $p['id'] ?>" class="btn-edit">✏️ Edit</a>
                                        <a href="proses_produk.php?action=hapus&id=<?= $p['id'] ?>"
                                           class="btn-hapus"
                                           onclick="return confirm('Yakin hapus produk ini?')">🗑️ Hapus</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div></main>

</body>
</html>