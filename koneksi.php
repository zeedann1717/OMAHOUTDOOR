<?php
$host = "localhost";
$user = "root"; // Username bawaan XAMPP
 HEAD
$pass = "root"; // Password bawaan Laragon
$db   = "omah_outdoor"; // Nama database lo bos

$pass = "root"; // Password bawaan Laragon
$db   = "omah_outdoor"; // Nama database lo
 c0cd69b8dd2ab34be41ac5634a728818d43b5b24

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Aduh, koneksi ke database gagal Bre: " . mysqli_connect_error());
}
?>
