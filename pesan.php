<?php
require_once 'cek_login.php';
require_once 'koneksi.php';

$produk_id = (int)($_GET['produk_id'] ?? 0);
if (!$produk_id) { header('Location: katalog.php'); exit; }

$res    = mysqli_query($conn, "SELECT * FROM produk WHERE id=$produk_id");
if (!$res || mysqli_num_rows($res) === 0) { header('Location: katalog.php'); exit; }
$produk = mysqli_fetch_assoc($res);

if ($produk['status'] === 'disewa') {
    header('Location: katalog.php?error=sedang_disewa');
    exit;
}

$error = $_GET['error'] ?? '';
$pesan_error = '';
if ($error === 'tanggal_kosong') $pesan_error = 'Tanggal mulai dan selesai harus diisi!';
elseif ($error === 'tanggal_salah') $pesan_error = 'Tanggal selesai harus setelah tanggal mulai!';
elseif ($error === 'gagal') $pesan_error = 'Gagal menyimpan pesanan, coba lagi.';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Alat | Omah Outdoor</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/order.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="container nav-wrapper">
            <a href="index.php" class="logo">🏕️ OMAH <span>OUTDOOR</span></a>
            <ul class="nav-links">
                <li><a href="katalog.php">← Kembali ke Katalog</a></li>
                <li><a href="logout.php" class="btn-logout">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="order-container">
        <div class="order-card">
            <div class="order-header">
                <h1>📋 Form Pemesanan</h1>
                <p>Lengkapi data sewa kamu di bawah ini</p>
            </div>

            <!-- Info Produk -->
            <div class="produk-preview">
                <div class="produk-img-wrap">
                    <img src="assets/images/<?= htmlspecialchars($produk['gambar']) ?>"
                         alt="<?= htmlspecialchars($produk['nama_produk']) ?>"
                         onerror="this.style.display='none'">
                </div>
                <div class="produk-detail">
                    <h2><?= htmlspecialchars($produk['nama_produk']) ?></h2>
                    <p class="harga-produk">Rp <?= number_format($produk['harga_per_hari'], 0, ',', '.') ?> <span>/ hari</span></p>
                    <span class="badge-tersedia">🟢 Tersedia</span>
                </div>
            </div>

            <?php if ($pesan_error) : ?>
            <div class="alert-error">⚠️ <?= $pesan_error ?></div>
            <?php endif; ?>

            <!-- Form -->
            <form action="proses_pesan.php" method="POST" id="form-pesan">
                <input type="hidden" name="produk_id" value="<?= $produk['id'] ?>">

                <div class="form-row">
                    <div class="form-group">
                        <label>📅 Tanggal Mulai Sewa</label>
                        <input type="date" name="tanggal_mulai" id="tgl-mulai"
                               min="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>📅 Tanggal Selesai Sewa</label>
                        <input type="date" name="tanggal_selesai" id="tgl-selesai"
                               min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                    </div>
                </div>

                <!-- Preview Harga -->
                <div class="harga-preview" id="harga-preview" style="display:none;">
                    <div class="harga-row">
                        <span>Durasi Sewa</span>
                        <span id="durasi-text">-</span>
                    </div>
                    <div class="harga-row">
                        <span>Harga per Hari</span>
                        <span>Rp <?= number_format($produk['harga_per_hari'], 0, ',', '.') ?></span>
                    </div>
                    <div class="harga-row total">
                        <span>Total Harga</span>
                        <span id="total-text">-</span>
                    </div>
                </div>

                <button type="submit" class="btn-pesan">🛒 Konfirmasi Pesanan</button>
            </form>

            <p class="order-note">⚠️ Setelah memesan, tunjukkan QR Code ke kasir untuk konfirmasi pengambilan barang.</p>
        </div>
    </div>

    <script>
    const hargaPerHari = <?= $produk['harga_per_hari'] ?>;
    const tglMulai     = document.getElementById('tgl-mulai');
    const tglSelesai   = document.getElementById('tgl-selesai');
    const preview      = document.getElementById('harga-preview');
    const durasiText   = document.getElementById('durasi-text');
    const totalText    = document.getElementById('total-text');

    function hitungHarga() {
        if (!tglMulai.value || !tglSelesai.value) return;
        const mulai   = new Date(tglMulai.value);
        const selesai = new Date(tglSelesai.value);
        const durasi  = Math.round((selesai - mulai) / 86400000);
        if (durasi <= 0) { preview.style.display = 'none'; return; }
        const total   = durasi * hargaPerHari;
        durasiText.textContent = durasi + ' hari';
        totalText.textContent  = 'Rp ' + total.toLocaleString('id-ID');
        preview.style.display  = 'block';
    }

    tglMulai.addEventListener('change', function() {
        tglSelesai.min = this.value;
        hitungHarga();
    });
    tglSelesai.addEventListener('change', hitungHarga);
    </script>
</body>
</html>
