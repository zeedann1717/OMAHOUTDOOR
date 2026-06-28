<?php
include 'koneksi.php';

$nama     = mysqli_real_escape_string($conn, $_POST['nama']);
$email    = mysqli_real_escape_string($conn, $_POST['email']);
$no_wa    = mysqli_real_escape_string($conn, $_POST['no_wa']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// 1. CEK DULU: Apakah email sudah beneran ada di database?
$cek_email = mysqli_query($conn, "SELECT email FROM users WHERE email = '$email'");

if (mysqli_num_rows($cek_email) > 0) {
    // Kalau emailnya memang sudah ada
    echo "<script>alert('Gagal daftar! Email tersebut sudah terdaftar.'); window.location='login.php$redirect';</script>";
} else {
    // 2. Kalau email belum ada, baru eksekusi INSERT
    $query = "INSERT INTO users (nama, email, no_wa, password, role) VALUES ('$nama', '$email', '$no_wa', '$password', 'user')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Akun berhasil dibuat! Silakan login.'); window.location='login.php$redirect';</script>";
    } else {
        // 3. TAMPILKAN ERROR ASLI: Kalau gagal simpan karena struktur database salah
        $error_db = mysqli_error($conn);
        echo "<script>alert('Gagal daftar! Error sistem: " . addslashes($error_db) . "'); window.history.back();</script>";
    }
}
?>