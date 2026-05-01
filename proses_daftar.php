<?php
include 'koneksi.php';

// Menangkap data dari inputan form
$email = $_POST['email'];
$password = $_POST['password'];

// Enkripsi password biar aman (standar anak IT!)
$password_hashed = password_hash($password, PASSWORD_DEFAULT);

// Masukin email dan password ke database
// (Nama & No WA belum dimasukin ke sini karena di tabel MySQL lo tadi belum dibuatin kolomnya)
$query = "INSERT INTO users (email, password) VALUES ('$email', '$password_hashed')";

if (mysqli_query($conn, $query)) {
    echo "<script>alert('Akun berhasil dibuat! Silakan login.'); window.location='login.php';</script>";
} else {
    echo "<script>alert('Gagal daftar! Mungkin email sudah pernah dipakai.'); window.location='login.php';</script>";
}
?>