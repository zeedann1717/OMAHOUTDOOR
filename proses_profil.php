<?php
require_once 'cek_login.php';
require_once 'koneksi.php';

$action  = $_POST['action'] ?? '';
$user_id = (int) $_SESSION['id'];

// ========================
// UPDATE PROFIL (nama & no_wa)
// ========================
if ($action === 'update_profil') {
    $nama  = mysqli_real_escape_string($conn, trim($_POST['nama']));
    $no_wa = mysqli_real_escape_string($conn, trim($_POST['no_wa']));

    if (empty($nama)) {
        header('Location: profil.php?pesan=nama_kosong');
        exit;
    }

    mysqli_query($conn, "UPDATE users SET nama='$nama', no_wa='$no_wa' WHERE id=$user_id");

    // Update session
    $_SESSION['nama'] = $nama;

    header('Location: profil.php?pesan=profil_sukses');
    exit;
}

// ========================
// GANTI PASSWORD
// ========================
if ($action === 'ganti_password') {
    $pass_lama    = $_POST['password_lama'];
    $pass_baru    = $_POST['password_baru'];
    $pass_konfirm = $_POST['password_konfirm'];

    // Ambil password saat ini dari DB
    $res  = mysqli_query($conn, "SELECT password FROM users WHERE id=$user_id");
    $row  = mysqli_fetch_assoc($res);

    if (!password_verify($pass_lama, $row['password'])) {
        header('Location: profil.php?pesan=pass_lama_salah#ganti-password');
        exit;
    }

    if (strlen($pass_baru) < 6) {
        header('Location: profil.php?pesan=pass_terlalu_pendek#ganti-password');
        exit;
    }

    if ($pass_baru !== $pass_konfirm) {
        header('Location: profil.php?pesan=pass_tidak_cocok#ganti-password');
        exit;
    }

    $hash = password_hash($pass_baru, PASSWORD_DEFAULT);
    mysqli_query($conn, "UPDATE users SET password='$hash' WHERE id=$user_id");

    header('Location: profil.php?pesan=pass_sukses');
    exit;
}

header('Location: profil.php');
exit;
?>
