<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit;
}
require_once __DIR__ . '/../includes/header_dashboard.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Posyandu Desa Pamulihan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Selamat datang, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h1>
        <p>Ini adalah halaman dashboard Posyandu Desa Pamulihan.</p>
        
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Balita</h5>
                        <p class="card-text">Kelola data balita yang terdaftar di Posyandu.</p>
                        <a href="#" class="btn btn-primary">Lihat Data Balita</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Pengukuran</h5>
                        <p class="card-text">Catat dan lihat data pengukuran balita.</p>
                        <a href="#" class="btn btn-primary">Kelola Pengukuran</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Laporan</h5>
                        <p class="card-text">Generate laporan Posyandu.</p>
                        <a href="#" class="btn btn-primary">Buat Laporan</a>
                    </div>
                </div>
            </div>
        </div>
        
        <a href="../auth/logout.php" class="btn btn-danger mt-4">Logout</a>
    </div>
</body>
</html>

<?php
require_once __DIR__ . '/../includes/footer_dashboard.php';
?>