<?php

require_once __DIR__ . '/../config/config.php';

// Pastikan sesi sudah dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit;
}

$user = $_SESSION['user'];

// Fungsi untuk menentukan apakah halaman saat ini aktif
function isActive($page) {
    return (basename($_SERVER['PHP_SELF']) == $page) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Posyandu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .navbar-brand img {
            max-height: 40px;
        }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .content {
            flex: 1 0 auto;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="../views/dashboard.php">
                <img src="../logo/logo.png" alt="Logo Posyandu" class="d-inline-block align-top">
                Posyandu
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo isActive('dashboard.php'); ?>" href="../posyandu4/dashboard_p4.php"><i class="fas fa-home"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo isActive('ibuhamil_p4.php'); ?>" href="../posyandu4/ibuhamil_p4.php"><i class="fas fa-female"></i> Data Ibu Hamil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo isActive('ibuhamil_daftar_p4.php'); ?>" href="../posyandu4/ibuhamil_daftar_p4.php"><i class="fas fa-user-edit"></i> Daftar Ibu Hamil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo isActive('ibuhamil_crud_p4.php'); ?>" href="../posyandu4/ibuhamil_crud_p4.php"><i class="fas fa-ruler"></i> Catatan Ibu Hamil</a>
                    </li>
            
                </ul>
            </div>
        </div>
    </nav>
    <div class="content">