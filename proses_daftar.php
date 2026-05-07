<?php
include 'koneksi.php';

$nama     = mysqli_real_escape_string($conn, $_POST['nama']);
$email    = mysqli_real_escape_string($conn, $_POST['email']);
$no_wa    = mysqli_real_escape_string($conn, $_POST['no_wa']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$query = "INSERT INTO users (nama, email, no_wa, password, role) VALUES ('$nama', '$email', '$no_wa', '$password', 'user')";

if (mysqli_query($conn, $query)) {
    echo "<script>alert('Akun berhasil dibuat! Silakan login.'); window.location='login.php';</script>";
} else {
    echo "<script>alert('Gagal daftar! Email mungkin sudah dipakai.'); window.location='login.php';</script>";
}
?>
