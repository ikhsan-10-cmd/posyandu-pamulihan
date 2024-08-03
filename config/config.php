<?php
// In config.php, after creating the $db connection
try {
    $db = new PDO('sqlite:C:\xampp\htdocs\website_posyandu\database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    createTasksTable($db); // Add this line to create the tasks table
} catch(PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

function query($sql, $params = []) {
    global $db;
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

function fetchAll($result) {
    return $result->fetchAll(PDO::FETCH_ASSOC);
}

function escapeString($string) {
    global $db;
    return $db->quote($string);
}

// Update the getBalitaAndPengukuran function to use the correct table name
function getBalitaAndPengukuran($db, $id_balita) {
    $sql = "SELECT b.*, p.id_pengukuran, p.tanggal_pengukuran, p.berat_badan, p.tinggi_badan, p.status_gizi, p.bulan
            FROM balita b
            LEFT JOIN pengukuran_balita p ON b.id_balita = p.id_balita
            WHERE b.id_balita = :id_balita";
    $stmt = $db->prepare($sql);
    $stmt->execute([':id_balita' => $id_balita]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPengukuranByBulan($db, $id_balita, $bulan) {
    $sql = "SELECT * FROM pengukuran_balita
            WHERE id_balita = :id_balita AND bulan = :bulan";
    $stmt = $db->prepare($sql);
    $stmt->execute([':id_balita' => $id_balita, ':bulan' => $bulan]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi untuk validasi password
function validatePassword($password) {
    $regex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
    return preg_match($regex, $password);
}

function createTasksTable($db) {
    $sql = "CREATE TABLE IF NOT EXISTS tasks (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        task TEXT NOT NULL,
        due_date DATE NOT NULL
    )";
    $db->exec($sql);
}

// Setelah koneksi database dibuat
createTasksTable($db);