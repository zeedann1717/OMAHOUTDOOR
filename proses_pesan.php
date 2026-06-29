<?php
// Pastikan session sudah berjalan jika cek_login.php tidak memanggil session_start()
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'cek_login.php';
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: katalog.php');
    exit;
}

$user_id     = (int) $_SESSION['id'];
$produk_id   = (int) $_POST['produk_id'];
$tgl_mulai   = $_POST['tanggal_mulai'];
$tgl_selesai = $_POST['tanggal_selesai'];

// Validasi tanggal kosong
if (empty($tgl_mulai) || empty($tgl_selesai)) {
    header('Location: pesan.php?produk_id=' . $produk_id . '&error=tanggal_kosong');
    exit;
}

// Validasi jika tanggal selesai lebih lampau dari tanggal mulai
if (strtotime($tgl_selesai) < strtotime($tgl_mulai)) {
    header('Location: pesan.php?produk_id=' . $produk_id . '&error=tanggal_salah');
    exit;
}

// Ambil data produk
$res_produk = mysqli_query($conn, "SELECT * FROM produk WHERE id=$produk_id");
if (!$res_produk || mysqli_num_rows($res_produk) === 0) {
    header('Location: katalog.php');
    exit;
}
$produk = mysqli_fetch_assoc($res_produk);

// Cek status produk di backend — tolak jika sedang disewa
if ($produk['status'] === 'disewa') {
    header('Location: katalog.php?error=produk_disewa');
    exit;
}

// Cek apakah user sudah punya order aktif untuk produk yang sama
$cek_duplikat = mysqli_query($conn,
    "SELECT id FROM orders
     WHERE user_id=$user_id
     AND produk_id=$produk_id
     AND status IN ('pending','dikonfirmasi')"
);
if ($cek_duplikat && mysqli_num_rows($cek_duplikat) > 0) {
    header('Location: pesan.php?produk_id=' . $produk_id . '&error=sudah_pesan');
    exit;
}

// Hitung durasi dan total harga (PERBAIKAN: minimal sewa dihitung 1 hari)
$durasi = (int) ((strtotime($tgl_selesai) - strtotime($tgl_mulai)) / 86400);
if ($durasi < 1) {
    $durasi = 1; 
}
$total = $durasi * $produk['harga_per_hari'];

// Tentukan status awal: jika pemesan adalah admin, langsung dikonfirmasi
$initial_status = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') ? 'dikonfirmasi' : 'pending';

// Simpan dulu dengan kode sementara
$temp_kode = 'TEMP-' . uniqid();

$query = "INSERT INTO orders (kode_order, user_id, produk_id, tanggal_mulai, tanggal_selesai, durasi_hari, total_harga, qty, status)
          VALUES ('$temp_kode', $user_id, $produk_id, '$tgl_mulai', '$tgl_selesai', $durasi, $total, 1, '$initial_status')";

// Eksekusi query utama
if (mysqli_query($conn, $query)) {
    // Ambil ID yang baru diinsert → jadikan nomor antrian urut
    $new_id     = mysqli_insert_id($conn);
    $kode_order = 'ORD-' . str_pad($new_id, 4, '0', STR_PAD_LEFT);

    // Update kode_order dengan nomor urut
    mysqli_query($conn, "UPDATE orders SET kode_order='$kode_order' WHERE id=$new_id");

    // Jika pemesan admin dan order langsung dikonfirmasi, set status produk jadi 'disewa'
    if ($initial_status === 'dikonfirmasi') {
        mysqli_query($conn, "UPDATE produk SET status='disewa' WHERE id=$produk_id");
    }

    // Sukses, alihkan ke bukti order
    header('Location: bukti_order.php?kode=' . $kode_order);
    exit;
} else {
    // MENAMPILKAN ERROR ASLI JIKA DATABASE MENOLAK
    echo "<div style='background: #ffcccc; padding: 20px; border: 1px solid red; font-family: sans-serif;'>";
    echo "<h3>🚨 Gagal Menyimpan ke Database!</h3>";
    echo "<b>Pesan Error dari MySQL:</b> <br>" . mysqli_error($conn) . "<br><br>";
    echo "<b>Query yang dikirim:</b> <br><code>" . $query . "</code><br><br>";
    echo "<i>Silakan screenshot pesan ini dan cocokkan nama kolom di atas dengan yang ada di phpMyAdmin (tabel orders).</i>";
    echo "</div>";
    exit;
}
?>