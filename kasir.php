<?php
session_start();

// --- MULAI KODE PENGAMAN ---
// Cek apakah user sudah login DAN apakah rolenya adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Jika bukan admin (atau belum login), tendang balik ke halaman katalog!
    header('Location: katalog.php');
    exit;
}
// --- SELESAI KODE PENGAMAN ---

require_once 'koneksi.php';

$pesan = "";

// 1. PROSES KONFIRMASI (JIKA TOMBOL KONFIRMASI DIKLIK KASIR)
if (isset($_POST['konfirmasi'])) {
    $kode = $_POST['kode_order'];
    $produk_id = $_POST['produk_id'];

    // Update status order jadi dikonfirmasi
    $update_order = mysqli_query($conn, "UPDATE orders SET status='dikonfirmasi' WHERE kode_order='$kode'");
    
    // Update status produk jadi disewa agar tidak bisa disewa orang lain
    $update_produk = mysqli_query($conn, "UPDATE produk SET status='disewa' WHERE id='$produk_id'");

    if ($update_order && $update_produk) {
        $pesan = "<div style='color: #155724; background: #d4edda; padding: 10px; border-radius: 5px; margin-bottom: 15px;'>
                    ✅ Berhasil! Pesanan <b>$kode</b> telah dikonfirmasi dan barang diserahkan.
                  </div>";
    } else {
        $pesan = "<div style='color: #721c24; background: #f8d7da; padding: 10px; border-radius: 5px; margin-bottom: 15px;'>
                    🚨 Gagal mengkonfirmasi: " . mysqli_error($conn) . "
                  </div>";
    }
}

// 2. PROSES PENCARIAN KODE ORDER
$data_order = null;
$data_produk = null;

if (isset($_GET['cari_kode']) && $_GET['cari_kode'] != '') {
    // Amankan input dari injeksi SQL
    $cari = mysqli_real_escape_string($conn, $_GET['cari_kode']);
    
    // Cari data di tabel orders
    $query_order = mysqli_query($conn, "SELECT * FROM orders WHERE kode_order='$cari'");
    
    if (mysqli_num_rows($query_order) > 0) {
        $data_order = mysqli_fetch_assoc($query_order);
        
        // Ambil nama produk dari tabel produk berdasarkan produk_id
        $p_id = $data_order['produk_id'];
        $query_produk = mysqli_query($conn, "SELECT nama_produk FROM produk WHERE id='$p_id'");
        if ($query_produk && mysqli_num_rows($query_produk) > 0) {
            $data_produk = mysqli_fetch_assoc($query_produk);
        }
    } else {
        $pesan = "<div style='color: #856404; background: #fff3cd; padding: 10px; border-radius: 5px; margin-bottom: 15px;'>
                    ⚠️ Pesanan dengan kode <b>$cari</b> tidak ditemukan! Cek kembali ketikan kodenya.
                  </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panel Kasir - Omah Outdoor</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; padding: 30px; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .form-group { display: flex; gap: 10px; margin-bottom: 20px; }
        input[type="text"] { flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px; text-transform: uppercase; }
        .btn { padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; color: white; font-weight: bold; }
        .btn-cari { background-color: #2e7d32; }
        .btn-cari:hover { background-color: #1b5e20; }
        .btn-konfirmasi { background-color: #0277bd; width: 100%; margin-top: 15px; padding: 12px; }
        .btn-konfirmasi:hover { background-color: #01579b; }
        .detail-item { margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px dashed #eee; }
        .label { display: inline-block; width: 140px; color: #555; }
        .nav-back { display: inline-block; margin-bottom: 20px; color: #555; text-decoration: none; }
        .nav-back:hover { color: #000; text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <a href="katalog.php" class="nav-back">← Kembali ke Katalog</a>
    <h2 style="margin-top: 0; color: #333;">🧑‍💻 Panel Kasir Omah Outdoor</h2>
    
    <?= $pesan ?>

    <form method="GET" action="">
        <div class="form-group">
            <input type="text" name="cari_kode" placeholder="Ketik Kode (Contoh: ORD-0001)" required 
                   value="<?= isset($_GET['cari_kode']) ? htmlspecialchars($_GET['cari_kode']) : '' ?>">
            <button type="submit" class="btn btn-cari">Cari</button>
        </div>
    </form>

    <?php if ($data_order): ?>
        <div style="background: #fafafa; border: 1px solid #ddd; padding: 15px; border-radius: 6px;">
            <div class="detail-item">
                <span class="label">Status:</span> 
                <b style="color: <?= $data_order['status'] == 'pending' ? 'orange' : 'green' ?>;">
                    <?= strtoupper($data_order['status']) ?>
                </b>
            </div>
            <div class="detail-item">
                <span class="label">Kode Order:</span> <b><?= $data_order['kode_order'] ?></b>
            </div>
            <div class="detail-item">
                <span class="label">Produk:</span> <b><?= $data_produk ? $data_produk['nama_produk'] : 'Produk Dihapus' ?></b>
            </div>
            <div class="detail-item">
                <span class="label">Tanggal Sewa:</span> <b><?= $data_order['tanggal_mulai'] ?> s/d <?= $data_order['tanggal_selesai'] ?></b>
            </div>
            <div class="detail-item" style="border-bottom: none; font-size: 18px;">
                <span class="label">Total Bayar:</span> <b style="color: #d32f2f;">Rp <?= number_format($data_order['total_harga'], 0, ',', '.') ?></b>
            </div>
        </div>

        <?php if ($data_order['status'] === 'pending'): ?>
            <form method="POST" action="">
                <input type="hidden" name="kode_order" value="<?= $data_order['kode_order'] ?>">
                <input type="hidden" name="produk_id" value="<?= $data_order['produk_id'] ?>">
                
                <button type="submit" name="konfirmasi" class="btn btn-konfirmasi" 
                        onclick="return confirm('Pastikan pelanggan sudah membayar Rp <?= number_format($data_order['total_harga'], 0, ',', '.') ?>. Konfirmasi sekarang?')">
                    ✅ Terima Pembayaran & Serahkan Barang
                </button>
            </form>
        <?php else: ?>
            <div style="margin-top: 15px; padding: 12px; background: #e8f5e9; color: #2e7d32; border-radius: 4px; text-align: center; border: 1px solid #c8e6c9;">
                <b>Orderan ini sudah dikonfirmasi sebelumnya.</b>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>