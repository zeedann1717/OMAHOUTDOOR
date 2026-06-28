<?php
require_once 'cek_login.php';
require_once 'koneksi.php';

$user_id = (int) $_SESSION['id'];

// Ambil semua order milik user yang login
$query  = "SELECT o.*, p.nama_produk, p.gambar, p.harga_per_hari
           FROM orders o
           JOIN produk p ON o.produk_id = p.id
           WHERE o.user_id = $user_id
           ORDER BY o.created_at DESC";
$result = mysqli_query($conn, $query);
$total  = $result ? mysqli_num_rows($result) : 0;

$status_label = [
    'pending'      => ['label' => '⏳ Menunggu Konfirmasi', 'class' => 'status-pending'],
    'dikonfirmasi' => ['label' => '✅ Dikonfirmasi',         'class' => 'status-konfirmasi'],
    'selesai'      => ['label' => '🎉 Selesai',              'class' => 'status-selesai'],
    'dibatalkan'   => ['label' => '❌ Dibatalkan',            'class' => 'status-batal'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Order | Omah Outdoor</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏕️</text></svg>">
    <link rel="stylesheet" href="assets/css/style.css?v=2">
    <link rel="stylesheet" href="assets/css/order.css?v=2">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
</head>
<body>

    <nav class="navbar">
        <div class="container nav-wrapper">
            <a href="index.php" class="logo">🏕️ OMAH <span>OUTDOOR</span></a>
            <ul class="nav-links">
                <li><a href="index.php">Beranda</a></li>
                <li><a href="katalog.php">Katalog</a></li>
                <li><a href="riwayat_order.php" class="active">Riwayat Order</a></li>
                <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
                    <li><a href="dashboard_admin.php" class="btn-login">🛠️ Dashboard Admin</a></li>
                <?php endif; ?>
                <li><a href="logout.php" class="btn-logout">Logout</a></li>
            </ul>
            <button class="hamburger" onclick="toggleMenu()" aria-label="Toggle Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>

    <div class="riwayat-container">

        <!-- HEADER -->
        <div class="riwayat-header">
            <h1>📜 Riwayat Pesanan</h1>
            <p>Halo, <strong><?= htmlspecialchars($_SESSION['nama'] ?? 'User') ?></strong>! Berikut semua pesanan kamu.</p>
        </div>

        <?php if ($total === 0) : ?>
        <!-- EMPTY STATE -->
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <h2>Belum Ada Pesanan</h2>
            <p>Kamu belum pernah memesan alat apapun. Yuk mulai sewa!</p>
            <a href="katalog.php" class="btn-pesan" style="display:inline-block; width:auto; padding: 12px 30px; text-decoration:none;">
                🛍️ Lihat Katalog
            </a>
        </div>

        <?php else : ?>

        <!-- STATS SINGKAT -->
        <div class="riwayat-stats">
            <?php
            $cnt = ['pending'=>0,'dikonfirmasi'=>0,'selesai'=>0,'dibatalkan'=>0];
            $tmp = mysqli_query($conn, "SELECT status, COUNT(*) as total FROM orders WHERE user_id=$user_id GROUP BY status");
            while($r = mysqli_fetch_assoc($tmp)) $cnt[$r['status']] = $r['total'];
            mysqli_data_seek($result, 0);
            ?>
            <div class="stat-item">
                <span class="stat-num"><?= $total ?></span>
                <span class="stat-lbl">Total Order</span>
            </div>
            <div class="stat-item">
                <span class="stat-num" style="color:#92400e;"><?= $cnt['pending'] ?></span>
                <span class="stat-lbl">Menunggu</span>
            </div>
            <div class="stat-item">
                <span class="stat-num" style="color:#065f46;"><?= $cnt['dikonfirmasi'] ?></span>
                <span class="stat-lbl">Dikonfirmasi</span>
            </div>
            <div class="stat-item">
                <span class="stat-num" style="color:#1e40af;"><?= $cnt['selesai'] ?></span>
                <span class="stat-lbl">Selesai</span>
            </div>
        </div>

        <!-- DAFTAR ORDER -->
        <div class="order-list">
            <?php while ($order = mysqli_fetch_assoc($result)) : ?>
            <?php $st = $status_label[$order['status']] ?? $status_label['pending']; ?>

            <div class="order-item">
                <!-- Foto Produk -->
                <div class="order-item-img">
                    <img src="assets/images/<?= htmlspecialchars($order['gambar']) ?>"
                         alt="<?= htmlspecialchars($order['nama_produk']) ?>"
                         onerror="this.style.display='none'">
                </div>

                <!-- Info Order -->
                <div class="order-item-info">
                    <div class="order-item-top">
                        <h3><?= htmlspecialchars($order['nama_produk']) ?></h3>
                        <span class="status-badge <?= $st['class'] ?>"><?= $st['label'] ?></span>
                    </div>
                    <p class="order-kode">🎫 <?= $order['kode_order'] ?></p>
                    <div class="order-item-meta">
                        <span>📅 <?= date('d M Y', strtotime($order['tanggal_mulai'])) ?> → <?= date('d M Y', strtotime($order['tanggal_selesai'])) ?></span>
                        <span>⏱️ <?= $order['durasi_hari'] ?> hari</span>
                        <span>💰 Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></span>
                    </div>
                    <p class="order-date">Dipesan: <?= date('d M Y, H:i', strtotime($order['created_at'])) ?></p>
                </div>

                <!-- Tombol Aksi -->
                <div class="order-item-action">
                    <?php if (in_array($order['status'], ['pending', 'dikonfirmasi'])) : ?>
                        <a href="bukti_order.php?kode=<?= $order['kode_order'] ?>" class="btn-lihat-qr">
                            📱 Lihat QR
                        </a>
                    <?php else : ?>
                        <a href="katalog.php" class="btn-pesan-lagi">
                            🔄 Pesan Lagi
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php endwhile; ?>
        </div>

        <?php endif; ?>

    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>
