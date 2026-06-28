<?php
require_once 'cek_admin.php';
require_once 'koneksi.php';

$kode  = mysqli_real_escape_string($conn, $_GET['kode'] ?? '');
$pesan = $_GET['pesan'] ?? '';
$order = null;
$error = '';

if ($kode) {
    $query = "SELECT o.*, p.nama_produk, p.gambar,
                     COALESCE(NULLIF(u.nama,''), SUBSTRING_INDEX(u.email,'@',1)) as nama_user,
                     u.email, COALESCE(u.no_wa, '-') as no_wa
              FROM orders o
              JOIN produk p ON o.produk_id = p.id
              JOIN users u ON o.user_id = u.id
              WHERE o.kode_order = '$kode'";
    $res   = mysqli_query($conn, $query);
    if ($res && mysqli_num_rows($res) > 0) {
        $order = mysqli_fetch_assoc($res);
    } else {
        $error = 'Kode order tidak ditemukan!';
    }
}

// Ambil semua order untuk tabel
$all_orders = mysqli_query($conn, "SELECT o.*, p.nama_produk, COALESCE(NULLIF(u.nama,''), SUBSTRING_INDEX(u.email,'@',1)) as nama_user FROM orders o JOIN produk p ON o.produk_id=p.id JOIN users u ON o.user_id=u.id ORDER BY o.created_at DESC LIMIT 20");

$notif = '';
$notif_class = '';
if ($pesan === 'dikonfirmasi') { $notif = '✅ Order berhasil dikonfirmasi!'; $notif_class = 'notif-sukses'; }
elseif ($pesan === 'selesai')  { $notif = '🎉 Order selesai & produk kembali tersedia!'; $notif_class = 'notif-sukses'; }
elseif ($pesan === 'dibatalkan') { $notif = '❌ Order dibatalkan.'; $notif_class = 'notif-hapus'; }

$status_label = [
    'pending'      => ['label' => '⏳ Pending',       'class' => 'status-pending'],
    'dikonfirmasi' => ['label' => '✅ Dikonfirmasi',   'class' => 'status-konfirmasi'],
    'selesai'      => ['label' => '🎉 Selesai',        'class' => 'status-selesai'],
    'dibatalkan'   => ['label' => '❌ Dibatalkan',      'class' => 'status-batal'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Order | Omah Outdoor</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/order.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
</head>
<body class="admin-body">

    <aside class="sidebar">
        <div class="sidebar-brand">
            <span class="brand-icon">🏕️</span>
            <div><h2>OMAH</h2><span>OUTDOOR</span></div>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard_admin.php" class="nav-item"><span class="nav-icon">📊</span> Dashboard</a>
            <a href="kelola_produk.php" class="nav-item"><span class="nav-icon">📦</span> Kelola Produk</a>
            <a href="validasi_order.php" class="nav-item active"><span class="nav-icon">🔍</span> Validasi Order</a>
            <a href="katalog.php" class="nav-item"><span class="nav-icon">🛍️</span> Lihat Katalog</a>
            <a href="index.php" class="nav-item"><span class="nav-icon">🏠</span> Ke Beranda</a>
        </nav>
        <div class="sidebar-footer">
            <a href="logout.php" class="btn-sidebar-logout"><span>🚪</span> Logout</a>
        </div>
    </aside>

    <main class="admin-main">
        <header class="admin-topbar">
            <div class="topbar-left">
                <h1 class="page-title">🔍 Validasi Order</h1>
                <p class="page-subtitle">Cek & konfirmasi pesanan dari pelanggan</p>
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

        <!-- FORM CARI KODE ORDER -->
        <div class="validasi-layout">
            <div class="form-card">
                <h2 class="form-card-title">🔎 Cari Kode Order</h2>
                <form method="GET" action="validasi_order.php">
                    <div class="form-group">
                        <label>Masukkan Kode Order</label>
                        <input type="text" name="kode" placeholder="ORD-20260501-XXXXXX"
                               value="<?= htmlspecialchars($kode) ?>"
                               style="text-transform:uppercase;" required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-simpan">🔍 Cari Order</button>
                    </div>
                </form>

                <?php if ($error) : ?>
                <div class="notif-bar notif-gagal" style="margin-top:16px;"><?= $error ?></div>
                <?php endif; ?>

                <!-- HASIL PENCARIAN -->
                <?php if ($order) : ?>
                <div class="hasil-order">
                    <h3>📋 Detail Order</h3>
                    <table class="detail-table">
                        <tr><td>Kode Order</td><td><strong><?= $order['kode_order'] ?></strong></td></tr>
                        <tr><td>Pemesan</td><td><?= htmlspecialchars($order['nama_user']) ?></td></tr>
                        <tr><td>No. WA</td><td><?= htmlspecialchars($order['no_wa'] ?? '-') ?></td></tr>
                        <tr><td>Produk</td><td><?= htmlspecialchars($order['nama_produk']) ?></td></tr>
                        <tr><td>Mulai</td><td><?= date('d M Y', strtotime($order['tanggal_mulai'])) ?></td></tr>
                        <tr><td>Selesai</td><td><?= date('d M Y', strtotime($order['tanggal_selesai'])) ?></td></tr>
                        <tr><td>Durasi</td><td><?= $order['durasi_hari'] ?> hari</td></tr>
                        <tr class="row-total"><td>Total</td><td><strong>Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></strong></td></tr>
                        <tr><td>Status</td>
                            <?php $st = $status_label[$order['status']] ?? ['label' => htmlspecialchars(ucfirst($order['status'] ?? '-')), 'class' => 'status-unknown']; ?>
                            <td><span class="status-badge <?= $st['class'] ?>"><?= $st['label'] ?></span></td>
                        </tr>
                    </table>

                    <!-- TOMBOL AKSI -->
                    <?php if ($order['status'] === 'pending') : ?>
                    <form method="POST" action="proses_validasi.php" style="margin-top:16px;">
                        <input type="hidden" name="kode_order" value="<?= $order['kode_order'] ?>">
                        <input type="hidden" name="action" value="konfirmasi">
                        <button type="submit" class="btn-simpan" style="width:100%;">✅ Konfirmasi — Barang Diambil</button>
                    </form>
                    <?php elseif ($order['status'] === 'dikonfirmasi') : ?>
                    <form method="POST" action="proses_validasi.php" style="margin-top:16px;">
                        <input type="hidden" name="kode_order" value="<?= $order['kode_order'] ?>">
                        <input type="hidden" name="action" value="selesai">
                        <button type="submit" class="btn-simpan" style="width:100%; background:#059669;">🎉 Tandai Selesai — Barang Dikembalikan</button>
                    </form>
                    <?php elseif ($order['status'] === 'selesai') : ?>
                    <div class="notif-bar notif-sukses" style="margin-top:16px;">🎉 Order ini sudah selesai!</div>
                    <?php elseif ($order['status'] === 'dibatalkan') : ?>
                    <div class="notif-bar notif-hapus" style="margin-top:16px;">❌ Order ini sudah dibatalkan.</div>
                    <?php endif; ?>

                    <?php if (in_array($order['status'], ['pending', 'dikonfirmasi'])) : ?>
                    <form method="POST" action="proses_validasi.php" style="margin-top:8px;"
                          onsubmit="return confirm('Yakin batalkan order ini?')">
                        <input type="hidden" name="kode_order" value="<?= $order['kode_order'] ?>">
                        <input type="hidden" name="action" value="batalkan">
                        <button type="submit" class="btn-hapus" style="width:100%; padding:10px;">❌ Batalkan Order</button>
                    </form>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- TABEL SEMUA ORDER TERBARU -->
            <div class="table-section" style="flex:1; min-width:0;">
                <div class="table-header">
                    <h2>📋 Order Terbaru</h2>
                </div>
                <div class="table-wrapper">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Kode Order</th>
                                <th>Pemesan</th>
                                <th>Produk</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($o = mysqli_fetch_assoc($all_orders)) : ?>
                            <?php $st2 = $status_label[$o['status']] ?? ['label' => htmlspecialchars(ucfirst($o['status'] ?? '-')), 'class' => 'status-unknown']; ?>
                            <tr>
                                <td><strong><?= $o['kode_order'] ?></strong></td>
                                <td><?= htmlspecialchars($o['nama_user']) ?></td>
                                <td><?= htmlspecialchars($o['nama_produk']) ?></td>
                                <td>Rp <?= number_format($o['total_harga'], 0, ',', '.') ?></td>
                                <td><span class="status-badge <?= $st2['class'] ?>"><?= $st2['label'] ?></span></td>
                                <td>
                                    <a href="validasi_order.php?kode=<?= $o['kode_order'] ?>" class="btn-edit">🔍 Cek</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
