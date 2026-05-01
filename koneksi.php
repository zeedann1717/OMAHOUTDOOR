<?php
$host = "localhost";
$user = "root"; // Username bawaan XAMPP
$pass = "";     // Password bawaan XAMPP (dibiarkan kosong)
$db   = "omah_outdoor"; // Nama database lo

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Aduh, koneksi ke database gagal Bre: " . mysqli_connect_error());
}
?>