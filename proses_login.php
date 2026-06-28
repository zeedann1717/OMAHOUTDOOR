<?php
session_start();
include 'koneksi.php';

$email    = strtolower(trim($_POST['email'] ?? ''));
$email    = mysqli_real_escape_string($conn, $email);
$password = trim($_POST['password'] ?? '');

$query  = "SELECT * FROM users WHERE LOWER(email) = LOWER('$email')";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    $password_ok = false;
    if (password_verify($password, $row['password'])) {
        $password_ok = true;
    } elseif ($row['password'] === $password) {
        $password_ok = true;
    } elseif (md5($password) === $row['password']) {
        $password_ok = true;
    }

    if ($password_ok) {
        $_SESSION['id']    = $row['id'];
        $_SESSION['nama']  = $row['nama'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['role']  = $row['role'];

        if ($row['role'] === 'admin') {
            echo "<script>alert('Selamat datang, Admin!'); window.location='dashboard_admin.php';</script>";
        } else {
            $redirect_url = (isset($_POST['redirect']) && $_POST['redirect'] === 'katalog') ? 'katalog.php' : 'index.php';
            echo "<script>alert('Login Sukses, Bre!'); window.location='$redirect_url';</script>";
        }
    } else {
        echo "<script>alert('Password salah, coba lagi!'); window.location='login.php';</script>";
    }
} else {
    echo "<script>alert('Email belum terdaftar, silakan daftar dulu!'); window.location='login.php';</script>";
}
?>
