<?php 
require_once __DIR__ . '/../pos_3/header_p3.php';
?>

<div class="container">
    <div class="jumbotron text-center">
        <h1 class="display-4 text-white">Dashboard Posyandu Cempaka 3</h1>
        <p class="lead text-white">Silakan pilih data yang ingin Anda kelola</p>
    </div>
    <div class="welcome-section">
        <p class="welcome-text">Selamat datang di sistem informasi Posyandu Cempaka 3 Desa Pamulihan. Pilih salah satu opsi di bawah untuk mengelola data.</p>
    </div>
    <div class="row dashboard-container justify-content-center">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card section-card animate__animated animate__fadeInLeft animate__slow">
                <div class="card-body text-center">
                    <i class="fas fa-baby card-icon text-primary"></i>
                    <h5 class="card-title">Data Balita</h5>
                    <p class="card-text">Lihat dan kelola data balita di Posyandu Desa Pamulihan.</p>
                    <a href="../posyandu_3/balita_p3.php" class="btn btn-primary btn-lg mt-3">
                        <i class="fas fa-arrow-right me-2"></i>Masuk
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card section-card animate__animated animate__fadeInRight animate__slow">
                <div class="card-body text-center">
                    <i class="fas fa-female card-icon text-danger"></i>
                    <h5 class="card-title">Data Ibu Hamil</h5>
                    <p class="card-text">Lihat dan kelola data ibu hamil di Posyandu Desa Pamulihan.</p>
                    <a href="../posyandu_3/ibuhamil_p3.php" class="btn btn-primary btn-lg mt-3">
                        <i class="fas fa-arrow-right me-2"></i>Masuk
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-4">
        <div class="col-lg-4 col-md-6">
            <div class="card section-card animate__animated animate__fadeInUp animate__slow">
                <div class="card-body text-center">
                    <i class="fas fa-home card-icon text-success"></i>
                    <h5 class="card-title">Kembali ke Halaman Utama</h5>
                    <p class="card-text">Kembali ke dashboard utama Posyandu Desa Pamulihan.</p>
                    <a href="../views/dashboard.php" class="btn btn-success btn-lg mt-3">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

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
    .card-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease-in-out;
    }
    .section-card:hover .card-icon {
        transform: scale(1.2);
    }
    .selected-card {
        transform: scale(1.05);
        box-shadow: 0 0 30px rgba(0,123,255,0.5);
        z-index: 10;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.section-card');
        cards.forEach(card => {
            card.addEventListener('click', function(e) {
                if (!e.target.closest('a')) {
                    cards.forEach(c => c.classList.remove('selected-card'));
                    this.classList.add('selected-card');
                    this.classList.add('animate__animated', 'animate__heartBeat');
                    setTimeout(() => {
                        this.classList.remove('animate__animated', 'animate__heartBeat');
                    }, 1000);
                }
            });
        });
    });
</script>

<?php include __DIR__ . '/../pos_3/footer_p3.php'; ?>