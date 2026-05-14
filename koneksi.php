<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "omah_outdoor";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Aduh, koneksi ke database gagal Bre: " . mysqli_connect_error());
}
?>