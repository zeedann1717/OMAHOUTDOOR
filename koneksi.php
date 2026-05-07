<?php
$host = "localhost";
$user = "root"; // Username bawaan XAMPP
$pass = "root"; // Password bawaan Laragon
$db   = "omah_outdoor"; // Nama database lo

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Aduh, koneksi ke database gagal Bre: " . mysqli_connect_error());
}
?>
