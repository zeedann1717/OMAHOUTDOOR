<?php
session_start();
include 'koneksi.php';

// Menangkap data dari form login
$email = $_POST['email'];
$password = $_POST['password'];

// Cari email tersebut di database
$query = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $query);

// Kalo emailnya ketemu
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    
    // Cek kecocokan password yang diketik dengan yang di database
    if (password_verify($password, $row['password'])) {
        
        // INI DIA YANG DIBENERIN 👇 (Namanya disamain jadi 'email' biar klop sama index.php)
        $_SESSION['email'] = $email; 
        
        echo "<script>alert('Login Sukses, Bre!'); window.location='index.php';</script>";
    } else {
        // Kalau password salah
        echo "<script>alert('Password salah, coba lagi!'); window.location='login.php';</script>";
    }
} else {
    // Kalau email nggak ada di database
    echo "<script>alert('Email belum terdaftar, silakan daftar dulu!'); window.location='login.php';</script>";
}
?>