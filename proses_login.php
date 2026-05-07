<?php
session_start();
include 'koneksi.php';

$email    = mysqli_real_escape_string($conn, $_POST['email']);
$password = $_POST['password'];

$query  = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    if (password_verify($password, $row['password'])) {
        $_SESSION['id']    = $row['id'];
        $_SESSION['nama']  = $row['nama'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['role']  = $row['role'];

        if ($row['role'] === 'admin') {
            echo "<script>alert('Selamat datang, Admin!'); window.location='dashboard_admin.php';</script>";
        } else {
            echo "<script>alert('Login Sukses, Bre!'); window.location='index.php';</script>";
        }
    } else {
        echo "<script>alert('Password salah, coba lagi!'); window.location='login.php';</script>";
    }
} else {
    echo "<script>alert('Email belum terdaftar, silakan daftar dulu!'); window.location='login.php';</script>";
}
?>
