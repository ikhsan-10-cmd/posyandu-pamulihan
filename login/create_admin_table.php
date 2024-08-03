<?php
require_once 'config/config.php'; // Pastikan path ke config.php benar

// Buat tabel admin
$sql = "CREATE TABLE IF NOT EXISTS admin (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL
)";

try {
    $db->exec($sql);
    echo "Tabel admin berhasil dibuat.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
