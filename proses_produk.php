<?php
require_once 'cek_admin.php';
require_once 'koneksi.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// ========================
// TAMBAH PRODUK
// ========================
if ($action === 'tambah') {
    $nama    = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga   = (int) $_POST['harga_per_hari'];
    $status  = $_POST['status'] === 'disewa' ? 'disewa' : 'tersedia';
    $gambar  = '';

    // Upload gambar
    if (!empty($_FILES['gambar']['name'])) {
        $ext      = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $allowed  = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($ext, $allowed)) {
            $nama_file = time() . '_' . preg_replace('/\s+/', '_', $_FILES['gambar']['name']);
            $tujuan    = 'assets/images/' . $nama_file;
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $tujuan)) {
                $gambar = $nama_file;
            }
        }
    }

    $query = "INSERT INTO produk (nama_produk, harga_per_hari, status, gambar) VALUES ('$nama', $harga, '$status', '$gambar')";
    if (mysqli_query($conn, $query)) {
        header('Location: kelola_produk.php?pesan=tambah_sukses');
    } else {
        header('Location: kelola_produk.php?pesan=gagal');
    }
    exit;
}

// ========================
// EDIT PRODUK
// ========================
if ($action === 'edit') {
    $id     = (int) $_POST['id'];
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga  = (int) $_POST['harga_per_hari'];
    $status = $_POST['status'] === 'disewa' ? 'disewa' : 'tersedia';

    // Ambil gambar lama
    $res_lama  = mysqli_query($conn, "SELECT gambar FROM produk WHERE id=$id");
    $row_lama  = mysqli_fetch_assoc($res_lama);
    $gambar    = $row_lama['gambar'];

    // Upload gambar baru jika ada
    if (!empty($_FILES['gambar']['name'])) {
        $ext     = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($ext, $allowed)) {
            $nama_file = time() . '_' . preg_replace('/\s+/', '_', $_FILES['gambar']['name']);
            $tujuan    = 'assets/images/' . $nama_file;
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $tujuan)) {
                // Hapus gambar lama jika ada
                if ($gambar && file_exists('assets/images/' . $gambar)) {
                    unlink('assets/images/' . $gambar);
                }
                $gambar = $nama_file;
            }
        }
    }

    $query = "UPDATE produk SET nama_produk='$nama', harga_per_hari=$harga, status='$status', gambar='$gambar' WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        header('Location: kelola_produk.php?pesan=edit_sukses');
    } else {
        header('Location: kelola_produk.php?pesan=gagal');
    }
    exit;
}

// ========================
// HAPUS PRODUK
// ========================
if ($action === 'hapus') {
    $id = (int) $_GET['id'];

    // Ambil nama gambar sebelum hapus
    $res   = mysqli_query($conn, "SELECT gambar FROM produk WHERE id=$id");
    $row   = mysqli_fetch_assoc($res);
    $gambar = $row['gambar'] ?? '';

    if (mysqli_query($conn, "DELETE FROM produk WHERE id=$id")) {
        // Hapus file gambar jika ada (hanya file upload, bukan gambar lama hardcode)
        if ($gambar && file_exists('assets/images/' . $gambar) && strpos($gambar, '_') !== false) {
            unlink('assets/images/' . $gambar);
        }
        header('Location: kelola_produk.php?pesan=hapus_sukses');
    } else {
        header('Location: kelola_produk.php?pesan=gagal');
    }
    exit;
}

header('Location: kelola_produk.php');
exit;
?>
