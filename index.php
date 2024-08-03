<?php
session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/header.php';
?>

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
    
    <!-- CSS Kustom -->
    <style>
        .jumbotron {
            background: linear-gradient(45deg, #007bff, #6610f2);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem 1rem;
            margin-bottom: 2rem;
            border-radius: 0.3rem;
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
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .welcome-section {
            background: linear-gradient(45deg, #f3f3f3, #e6e6e6);
            border-radius: 15px;
            overflow: hidden;
        }
        .welcome-text {
            animation: fadeInUp 1s ease-out;
        }
        .info-card {
            transition: all 0.3s ease;
        }
        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="jumbotron text-white text-center py-5 mb-4">
        <h1 class="display-4">Selamat datang di Posyandu Desa Pamulihan</h1>
        <p class="lead">Melayani dengan sepenuh hati untuk kesehatan ibu dan anak</p>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4 shadow">
                    <div class="card-body">
                        <h2 class="card-title text-primary">Tentang Kami</h2>
                        <p class="card-text"><strong>Posyandu Desa Pamulihan</strong> adalah layanan kesehatan masyarakat yang berlokasi di Kecamatan Subang, Kabupaten Kuningan. Kami fokus pada kesehatan ibu dan anak dengan tujuan meningkatkan kualitas hidup dan kesejahteraan masyarakat. Dengan tenaga kesehatan yang berpengalaman, kami siap melayani kebutuhan kesehatan Anda dan keluarga.</p>
                    </div>
                </div>

                <div class="card mb-4 shadow">
                    <div class="card-body">
                        <h3 class="card-title text-success">Tujuan Posyandu:</h3>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="fas fa-check text-success me-2"></i>Menyediakan layanan kesehatan dasar bagi ibu dan anak.</li>
                            <li class="list-group-item"><i class="fas fa-check text-success me-2"></i>Meningkatkan kesadaran masyarakat tentang pentingnya kesehatan ibu dan anak.</li>
                            <li class="list-group-item"><i class="fas fa-check text-success me-2"></i>Memberikan edukasi dan informasi kesehatan untuk pencegahan penyakit.</li>
                        </ul>
                    </div>
                </div>

                <div class="card mb-4 shadow">
                    <div class="card-body">
                        <h3 class="card-title text-info">Layanan yang Diberikan:</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><i class="fas fa-syringe text-info me-2"></i>Imunisasi</li>
                                    <li class="list-group-item"><i class="fas fa-weight text-info me-2"></i>Penimbangan dan pengukuran kesehatan balita</li>
                                    <li class="list-group-item"><i class="fas fa-user-md text-info me-2"></i>Konsultasi kesehatan ibu dan anak</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><i class="fas fa-chalkboard-teacher text-info me-2"></i>Penyuluhan kesehatan</li>
                                    <li class="list-group-item"><i class="fas fa-female text-info me-2"></i>Pemeriksaan kehamilan</li>
                                    <li class="list-group-item"><i class="fas fa-pills text-info me-2"></i>Pemberian vitamin dan suplemen</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 shadow">
                    <div class="card-body">
                        <h3 class="card-title text-success">Jadwal Kegiatan Posyandu:</h3>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="fas fa-calendar-alt text-primary me-2"></i>Setiap hari Senin: Penimbangan bayi dan balita (08:00 - 11:00)</li>
                            <li class="list-group-item"><i class="fas fa-calendar-alt text-primary me-2"></i>Setiap hari Rabu: Pemeriksaan ibu hamil (09:00 - 12:00)</li>
                            <li class="list-group-item"><i class="fas fa-calendar-alt text-primary me-2"></i>Setiap hari Jumat: Imunisasi (08:00 - 11:00)</li>
                            <li class="list-group-item"><i class="fas fa-calendar-alt text-primary me-2"></i>Minggu pertama setiap bulan: Penyuluhan kesehatan (14:00 - 16:00)</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4 shadow">
                    <div class="card-body">
                        <h2 class="card-title text-primary">Visi dan Misi</h2>
                        <h4 class="text-success">Visi:</h4>
                        <p>Menjadi posyandu yang unggul dalam pelayanan kesehatan ibu dan anak, serta menjadi pusat informasi dan edukasi kesehatan masyarakat.</p>
                        <h4 class="text-success">Misi:</h4>
                        <ol>
                            <li>Memberikan layanan kesehatan yang berkualitas dan mudah diakses oleh masyarakat.</li>
                            <li>Menyediakan edukasi dan informasi kesehatan untuk meningkatkan kesadaran dan pengetahuan masyarakat.</li>
                            <li>Meningkatkan keterlibatan masyarakat dalam upaya peningkatan kesehatan ibu dan anak.</li>
                            <li>Membangun kerjasama dengan berbagai pihak untuk mendukung program kesehatan masyarakat.</li>
                        </ol>
                    </div>
                </div>

                <div class="card mb-4 shadow">
                    <div class="card-body">
                        <h3 class="card-title text-warning">Tips Kesehatan</h3>
                        <h5 class="text-success">Tips Menjaga Kesehatan Balita:</h5>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check-circle text-success me-2"></i>Pastikan balita mendapatkan imunisasi lengkap sesuai jadwal.</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>Berikan makanan bergizi seimbang dan cukup air putih.</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>Rutin melakukan pemeriksaan kesehatan ke posyandu.</li>
                        </ul>
                        <h5 class="text-success">Tips Kesehatan untuk Ibu Hamil:</h5>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check-circle text-success me-2"></i>Konsumsi makanan bergizi tinggi dan cukup asupan vitamin.</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>Rutin memeriksakan kehamilan ke bidan atau dokter.</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>Hindari stres dan istirahat yang cukup.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle dengan Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set last login time (replace with actual data from backend)
            document.getElementById('lastLogin').textContent = '2 Agustus 2024, 08:30';

            // Update current time
            function updateTime() {
                const now = new Date();
                const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                document.getElementById('currentTime').textContent = timeString;
            }
            updateTime();
            setInterval(updateTime, 1000);

            // Add hover effect to info cards
            const infoCards = document.querySelectorAll('.info-card');
            infoCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#f8f9fa';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '#f1f3f5';
                });
            });
        });
    </script>
</body>
</html>

<?php require_once __DIR__ . '/includes/footer.php'; ?>