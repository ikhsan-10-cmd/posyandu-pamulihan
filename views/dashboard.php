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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .jumbotron {
            background: linear-gradient(135deg, #007bff, #6610f2);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 3rem 1rem;
            margin-bottom: 2rem;
            border-radius: 0;
        }
        .display-4 {
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            animation: fadeInDown 1s ease-out;
        }
        .lead {
            animation: fadeInUp 1s ease-out 0.5s;
            animation-fill-mode: both;
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .welcome-section {
            background: linear-gradient(135deg, #ffffff, #f3f3f3);
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            padding: 2.5rem;
            margin-bottom: 2.5rem;
            transition: all 0.3s ease;
        }
        .welcome-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        .welcome-text {
            animation: fadeInUp 1s ease-out;
            color: #333;
        }
        .section-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 10px;
            overflow: hidden;
        }
        .section-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        .card-title {
            color: #007bff;
            font-weight: 600;
        }
        .card-text {
            color: #6c757d;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="jumbotron text-white text-center py-5 mb-4">
        <h1 class="display-4">Dashboard Posyandu Desa Pamulihan</h1>
        <p class="lead">Mengelola data untuk kesehatan ibu dan anak</p>
    </div>

    <div class="container">
        <div class="welcome-section mb-5">
            <h2 class="welcome-text mb-4">Selamat datang, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h2>
            <p class="lead">Ini adalah halaman dashboard Posyandu Desa Pamulihan. Silakan pilih menu di bawah ini untuk mengelola data posyandu.</p>
        </div>
        
        <div class="row">
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card section-card h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><i class="fas fa-baby me-2"></i>Data Balita</h5>
                        <p class="card-text flex-grow-1">Informasi tentang anak-anak di bawah lima tahun yang terdaftar di Posyandu.</p>
                        <a href="../views/balita.php" class="btn btn-primary mt-auto">Lihat Data Balita</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card section-card h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><i class="fas fa-user-edit me-2"></i>Manajemen Balita</h5>
                        <p class="card-text flex-grow-1">Tambah, edit, atau hapus data balita sesuai dengan perkembangan terbaru.</p>
                        <a href="../crud/balita_crud.php" class="btn btn-primary mt-auto">Kelola Data Balita</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card section-card h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><i class="fas fa-ruler me-2"></i>Pengukuran Balita</h5>
                        <p class="card-text flex-grow-1">Catat perkembangan fisik balita, termasuk berat badan, tinggi badan, dan lingkar kepala.</p>
                        <a href="../crud/balita_detail.php" class="btn btn-primary mt-auto">Catat Pengukuran</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card section-card h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><i class="fas fa-tasks me-2"></i>Tugas</h5>
                        <p class="card-text flex-grow-1">Kelola dan lacak berbagai kegiatan Posyandu seperti jadwal imunisasi dan penyuluhan.</p>
                        <a href="../views/tasks.php" class="btn btn-primary mt-auto">Lihat Tugas</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
require_once __DIR__ . '/../includes/footer_dashboard.php';
?>