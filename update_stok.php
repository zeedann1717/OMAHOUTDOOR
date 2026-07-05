<?php
require_once 'koneksi.php';

$id   = (int) $_GET['id'];
$type = $_GET['type'] ?? '';

if ($type === 'plus') {
    mysqli_query($conn, "UPDATE produk SET stok = stok + 1 WHERE id = $id");
} 
elseif ($type === 'min') {
    // biar gak minus
    mysqli_query($conn, "UPDATE produk SET stok = GREATEST(stok - 1, 0) WHERE id = $id");
}

header("Location: kelola_produk.php");
exit;