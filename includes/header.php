<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Posyandu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .navbar-brand img {
            max-height: 40px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="../views/index.php"> <!-- Update this link if necessary -->
                <img src="../logo/logo.png" alt="Logo Posyandu" class="d-inline-block align-top"> <!-- Update this path if necessary -->
                Posyandu
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
    <?php if (isset($_SESSION['user_id'])): ?>
        <li class="nav-item">
            <a class="nav-link" href="../views/tasks.php"><i class="fas fa-tasks"></i> Tugas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../views/balita.php"><i class="fas fa-baby"></i> Data Balita</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../crud/balita_crud.php"><i class="fas fa-user-edit"></i> Manajemen Balita</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../crud/balita_detail.php"><i class="fas fa-ruler"></i> Pengukuran Balita</a>
        </li>
    <?php endif; ?>
</ul>
                <?php if (isset($_SESSION['user_id'])): ?>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../reset_password.php"><i class="fas fa-key"></i> Reset Password</a> <!-- Update this link if necessary -->
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../login/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a> <!-- Update this link if necessary -->
                    </li>
                </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
