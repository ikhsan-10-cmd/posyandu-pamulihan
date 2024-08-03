<?php
require_once 'config/config.php'; // Pastikan path ke config.php benar

$username = 'admin';
$password = 'admin123'; // Ganti dengan password yang Anda inginkan

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO admin (username, password) VALUES (:username, :password) ON CONFLICT(username) DO UPDATE SET password = :password";
$stmt = $db->prepare($sql);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':password', $hashed_password);

if ($stmt->execute()) {
    echo "Password admin berhasil diperbarui.";
} else {
    echo "Gagal memperbarui password admin.";
}
?>
