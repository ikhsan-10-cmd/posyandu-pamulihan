<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- includes/header.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posyandu Desa Pamulihan</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        .header-logo {
            max-height: 50px;
        }
    </style>
</head>
<body>
    <header class="bg-light py-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <a href="index.php" class="text-decoration-none">
                        <img src="logo/logo.png" alt="Logo Posyandu" class="header-logo me-3">
                        <span class="h4 text-dark">Posyandu Desa Pamulihan</span>
                    </a>
                </div>
                <div class="col-md-6 text-end">
                    <a href="auth/login.php" class="btn btn-primary">Login</a>
                </div>
            </div>
        </div>
    </header>
