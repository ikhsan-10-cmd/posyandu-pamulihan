<?php
// config/config.php

define('DB_PATH', 'C:\xampp\htdocs\website_posyandu\database.sqlite');

try {
    $db = new PDO('sqlite:' . DB_PATH);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    createTables($db);
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
    return $result->fetchAll();
}

function fetchOne($result) {
    return $result->fetch();
}

function escapeString($string) {
    global $db;
    return $db->quote($string);
}

function getBalitaAndPengukuran($db, $id_balita) {
    $sql = "SELECT b.*, p.id_pengukuran, p.tanggal_pengukuran, p.berat_badan, p.tinggi_badan, p.status_gizi, p.bulan
            FROM balita b
            LEFT JOIN pengukuran_balita p ON b.id_balita = p.id_balita
            WHERE b.id_balita = :id_balita";
    $stmt = $db->prepare($sql);
    $stmt->execute([':id_balita' => $id_balita]);
    return $stmt->fetchAll();
}

function getPengukuranByBulan($db, $id_balita, $bulan) {
    $sql = "SELECT * FROM pengukuran_balita
            WHERE id_balita = :id_balita AND bulan = :bulan";
    $stmt = $db->prepare($sql);
    $stmt->execute([':id_balita' => $id_balita, ':bulan' => $bulan]);
    return $stmt->fetchAll();
}

function validatePassword($password) {
    $regex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
    return preg_match($regex, $password);
}

function createTables($db) {
    // Create admin table
    $sql = "CREATE TABLE IF NOT EXISTS admin (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL
    )";
    $db->exec($sql);

    // Create tasks table
    $sql = "CREATE TABLE IF NOT EXISTS tasks (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        task TEXT NOT NULL,
        due_date DATE NOT NULL
    )";
    $db->exec($sql);

    // Add more table creation statements here if needed
}

function getDbConnection() {
    global $db;
    return $db;
}

function addAdmin($username, $password) {
    global $db;
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO admin (username, password) VALUES (:username, :password)";
    $stmt = $db->prepare($sql);
    return $stmt->execute([':username' => $username, ':password' => $hashedPassword]);
}

function verifyAdminLogin($username, $password) {
    global $db;
    $sql = "SELECT * FROM admin WHERE username = :username";
    $stmt = $db->prepare($sql);
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        return true;
    }
    return false;
}