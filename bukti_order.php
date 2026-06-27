<?php
require_once 'cek_login.php';
require_once 'koneksi.php';

$kode = mysqli_real_escape_string($conn, $_GET['kode'] ?? '');
if (!$kode) { header('Location: katalog.php'); exit; }

$query = "SELECT o.*, p.nama_produk, p.gambar, p.harga_per_hari, u.nama as nama_user, u.email, u.no_wa
          FROM orders o
          JOIN produk p ON o.produk_id = p.id
          JOIN users u ON o.user_id = u.id
          WHERE o.kode_order = '$kode' AND o.user_id = " . $_SESSION['id'];

$res = mysqli_query($conn, $query);
if (!$res || mysqli_num_rows($res) === 0) { header('Location: katalog.php'); exit; }
$order = mysqli_fetch_assoc($res);

$qr_data = urlencode('OMAH OUTDOOR | Kode: ' . $order['kode_order'] . ' | ' . $order['nama_produk'] . ' | ' . date('d/m/Y', strtotime($order['tanggal_mulai'])) . ' - ' . date('d/m/Y', strtotime($order['tanggal_selesai'])));
$qr_url  = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . $qr_data;

$status_label = [
    'pending'      => ['label' => '⏳ Menunggu Konfirmasi', 'class' => 'status-pending'],
    'dikonfirmasi' => ['label' => '✅ Dikonfirmasi',        'class' => 'status-konfirmasi'],
    'selesai'      => ['label' => '🎉 Selesai',             'class' => 'status-selesai'],
    'dibatalkan'   => ['label' => '❌ Dibatalkan',           'class' => 'status-batal'],
];
$st = $status_label[$order['status']] ?? $status_label['pending'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pesanan | Omah Outdoor</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/order.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="container nav-wrapper">
            <a href="index.php" class="logo">🏕️ OMAH <span>OUTDOOR</span></a>
            <ul class="nav-links">
                <li><a href="katalog.php">Katalog</a></li>
                <li><a href="riwayat_order.php">Riwayat Order</a></li>
                <li><a href="logout.php" class="btn-logout">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="bukti-container">

        <!-- HEADER SUKSES -->
        <div class="bukti-header">
            <div class="success-icon">✅</div>
            <h1>Pesanan Berhasil Dibuat!</h1>
            <p>Tunjukkan QR Code ini kepada kasir untuk konfirmasi pengambilan barang</p>
        </div>

        <div class="bukti-layout">

            <!-- QR CODE -->
            <div class="qr-card">
                <p class="qr-label">QR Code Pesanan</p>
                <div class="qr-wrap">
                    <img src="<?= $qr_url ?>" alt="QR Code <?= $order['kode_order'] ?>">
                </div>
                <div class="kode-order"><?= $order['kode_order'] ?></div>
                <span class="status-badge <?= $st['class'] ?>"><?= $st['label'] ?></span>
                <button onclick="window.print()" class="btn-print">🖨️ Print / Simpan</button>
            </div>

            <!-- DETAIL ORDER -->
            <div class="detail-card">
                <h2>📋 Detail Pesanan</h2>

                <div class="detail-produk">
                    <img src="assets/images/<?= htmlspecialchars($order['gambar']) ?>"
                         alt="<?= htmlspecialchars($order['nama_produk']) ?>"
                         onerror="this.style.display='none'">
                    <div>
                        <h3><?= htmlspecialchars($order['nama_produk']) ?></h3>
                        <p>Rp <?= number_format($order['harga_per_hari'], 0, ',', '.') ?> / hari</p>
                    </div>
                </div>

                <table class="detail-table">
                    <tr>
                        <td>Kode Order</td>
                        <td><strong><?= $order['kode_order'] ?></strong></td>
                    </tr>
                    <tr>
                        <td>Pemesan</td>
                        <td><?= htmlspecialchars($order['nama_user']) ?></td>
                    </tr>
                    <tr>
                        <td>No. WhatsApp</td>
                        <td><?= htmlspecialchars($order['no_wa'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td>Tanggal Mulai</td>
                        <td><?= date('d M Y', strtotime($order['tanggal_mulai'])) ?></td>
                    </tr>
                    <tr>
                        <td>Tanggal Selesai</td>
                        <td><?= date('d M Y', strtotime($order['tanggal_selesai'])) ?></td>
                    </tr>
                    <tr>
                        <td>Durasi</td>
                        <td><?= $order['durasi_hari'] ?> hari</td>
                    </tr>
                    <tr class="row-total">
                        <td>Total Harga</td>
                        <td><strong>Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></strong></td>
                    </tr>
                </table>

                <div class="order-instructions">
                    <h4>📌 Langkah Selanjutnya:</h4>
                    <ol>
                        <li>Simpan atau print halaman ini</li>
                        <li>Datang ke toko <strong>Omah Outdoor</strong></li>
                        <li>Tunjukkan QR Code kepada kasir</li>
                        <li>Kasir akan scan dan konfirmasi pesananmu</li>
                        <li>Ambil barang &amp; selamat berpetualang! 🏕️</li>
                    </ol>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
