<?php
session_start();
require_once 'koneksi.php';

// 1. KEAMANAN: Hanya admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: katalog.php'); exit;
}

// 2. QUERY DATA ORDER
// Pastikan nama tabelnya 'orders' atau 'transaksi' (sesuaikan di bawah ini)
$query = "SELECT * FROM orders ORDER BY id DESC";
$result = mysqli_query($conn, $query);

// Cek apakah query berhasil
if (!$result) {
    die("Error Database: " . mysqli_error($conn) . ". Pastikan tabel 'orders' ada.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Validasi Order - Admin</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container" style="padding: 30px;">
        <h2>🔍 Validasi Order</h2>
        
        <table border="1" style="width:100%; border-collapse:collapse; margin-top:20px;">
            <tr style="background:#f4f4f4;">
                <th>Kode Order</th>
                <th>Produk ID</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            
            <?php 
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) { 
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['kode_order']) ?></td>
                    <td><?= htmlspecialchars($row['produk_id']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td>
                        <?php if($row['status'] == 'pending'): ?>
                            <a href="proses_validasi.php?kode=<?= $row['kode_order'] ?>">✅ Konfirmasi</a>
                        <?php else: ?>
                            <span>Sudah Selesai</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center;'>Tidak ada orderan baru.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>