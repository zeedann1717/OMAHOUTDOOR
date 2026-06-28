<?php
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

// Validasi tanggal
if (empty($tgl_mulai) || empty($tgl_selesai)) {
    header('Location: pesan.php?produk_id=' . $produk_id . '&error=tanggal_kosong');
    exit;
}

if (strtotime($tgl_selesai) <= strtotime($tgl_mulai)) {
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

// FIX 2: Cek status produk di backend — tolak jika sedang disewa
if ($produk['status'] === 'disewa') {
    header('Location: katalog.php?error=produk_disewa');
    exit;
}

// FIX 3: Cek apakah user sudah punya order aktif untuk produk yang sama
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

// Hitung durasi dan total harga
$durasi = (int) ((strtotime($tgl_selesai) - strtotime($tgl_mulai)) / 86400);
$total  = $durasi * $produk['harga_per_hari'];

// Tentukan status awal: jika pemesan adalah admin, langsung dikonfirmasi
$initial_status = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') ? 'dikonfirmasi' : 'pending';

// Simpan dulu dengan kode sementara, lalu update pakai ID urutan
$temp_kode = 'TEMP-' . uniqid();

$query = "INSERT INTO orders (kode_order, user_id, produk_id, tanggal_mulai, tanggal_selesai, durasi_hari, total_harga, qty, status)
          VALUES ('$temp_kode', $user_id, $produk_id, '$tgl_mulai', '$tgl_selesai', $durasi, $total, 1, '$initial_status')";

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

    header('Location: bukti_order.php?kode=' . $kode_order);
    exit;
} else {
    header('Location: pesan.php?produk_id=' . $produk_id . '&error=gagal');
    exit;
}
?>
