<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/login.php');
    exit;
}

// Get username from database
$user_id = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT username FROM admin WHERE id = :id");
$stmt->bindParam(':id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$username = $user['username'];

// Fungsi untuk mendapatkan tugas hari ini
function getTodayTasks($db) {
    $today = date('Y-m-d');
    try {
        $stmt = $db->prepare("SELECT * FROM tasks WHERE due_date = :today ORDER BY due_date ASC");
        $stmt->execute(['today' => $today]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching today's tasks: " . $e->getMessage());
        return [];
    }
}

// Dapatkan tugas hari ini
$todayTasks = getTodayTasks($db);
$taskCount = count($todayTasks);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posyandu Desa Pamulihan</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
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
    </style>
</head>
<body>
    <div class="jumbotron text-white text-center py-5 mb-4">
        <h1 class="display-4">Selamat datang di Posyandu Desa Pamulihan</h1>
        <p class="lead">Melayani dengan sepenuh hati untuk kesehatan ibu dan anak</p>
    </div>

    <!-- Bootstrap Bundle dengan Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<div class="row welcome-section my-5">
    <div class="col-md-12">
        <div class="card shadow-lg">
            <div class="card-body text-center">
                <img src="https://api.dicebear.com/6.x/initials/svg?seed=<?php echo htmlspecialchars($username); ?>" alt="User Avatar" class="rounded-circle mb-3" style="width: 100px; height: 100px;">
                <h2 class="welcome-text mb-4">Selamat datang, <span class="text-primary"><?php echo htmlspecialchars($username); ?></span>!</h2>
                <div class="row">
                    <div class="col-md-4">
                        <div class="info-card bg-light p-3 rounded">
                            <i class="fas fa-calendar-check text-success fa-2x mb-2"></i>
                            <h5>Login Terakhir</h5>
                            <p id="lastLogin">Loading...</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-card bg-light p-3 rounded">
                            <i class="fas fa-clock text-primary fa-2x mb-2"></i>
                            <h5>Waktu Saat Ini</h5>
                            <p id="currentTime">Loading...</p>
                        </div>
                    </div>
                    <div class="col-md-4">
    <div class="info-card bg-light p-3 rounded">
        <i class="fas fa-tasks text-warning fa-2x mb-2"></i>
        <h5>Tugas Hari Ini</h5>
        <p><?= $taskCount ?> tugas menunggu</p>
        <?php if ($taskCount > 0): ?>
            <ul class="list-unstyled">
                <?php foreach ($todayTasks as $task): ?>
                    <li><?= htmlspecialchars($task['task']) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <a href="tasks.php" class="btn btn-sm btn-primary">Lihat Semua Tugas</a>
    </div>
</div>
                </div>
            </div>
        </div>
    </div>
</div>

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

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="text-center mb-4">Jadwal Kegiatan Posyandu</h3>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>Hari</th>
                                <th>Waktu</th>
                                <th>Kegiatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Senin</td>
                                <td>08.00 - 11.00</td>
                                <td>Penimbangan Balita dan Konsultasi Gizi</td>
                            </tr>
                            <tr>
                                <td>Rabu</td>
                                <td>09.00 - 12.00</td>
                                <td>Imunisasi dan Pemeriksaan Kehamilan</td>
                            </tr>
                            <tr>
                                <td>Jumat</td>
                                <td>14.00 - 16.00</td>
                                <td>Penyuluhan Kesehatan dan Senam Ibu Hamil</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
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
</style>

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

<?php require_once __DIR__ . '/../includes/footer.php'; ?>