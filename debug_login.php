<?php
include 'koneksi.php';

$email = 'maulanazidan4420@gmail.com';

$query = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "❌ Query gagal: " . mysqli_error($conn);
    exit;
}

if (mysqli_num_rows($result) === 0) {
    echo "❌ Email tidak ditemukan di database!";
    exit;
}

$row = mysqli_fetch_assoc($result);

echo "<pre>";
echo "✅ Email ditemukan!\n";
echo "ID    : " . $row['id'] . "\n";
echo "Nama  : " . $row['nama'] . "\n";
echo "Email : " . $row['email'] . "\n";
echo "Role  : " . $row['role'] . "\n";
echo "Hash  : " . $row['password'] . "\n";
echo "Panjang hash: " . strlen($row['password']) . " karakter\n\n";

$test_password = 'admin123';
$cocok = password_verify($test_password, $row['password']);
echo "Test password_verify('admin123') : " . ($cocok ? "✅ COCOK" : "❌ TIDAK COCOK") . "\n";
echo "</pre>";
?>
