<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../pos_3/header_balita_p3.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Handle form submission
$id_balita = isset($_POST['id_balita']) ? (int)$_POST['id_balita'] : 1;
$bulan = isset($_POST['bulan']) ? $_POST['bulan'] : 'januari'; 

// Fetch data for the selected id_balita and bulan
$balitaData = getBalita3AndPengukuran($db, $id_balita);
$pengukuranData = getPengukuranByBulan3($db, $id_balita, $bulan);

// Fetch list of balita for dropdown
$balitas = $db->query("SELECT id_balita, nama_balita FROM balita_3")->fetchAll(PDO::FETCH_ASSOC);

// List of months for dropdown
$months = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Balita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }
    .jumbotron {
        background: linear-gradient(135deg, #007bff, #6610f2);
        color: white;
        padding: 4rem 2rem;
        margin-bottom: 2rem;
        border-radius: 0.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .jumbotron .row {
        margin: 0;
    }
    .jumbotron h1 {
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        animation: fadeInDown 1s ease-out;
        margin-bottom: 0.5rem;
    }
    .jumbotron p {
        animation: fadeInUp 1s ease-out 0.5s;
        animation-fill-mode: both;
        margin-bottom: 0;
    }
    .title-icon {
        font-size: 4rem;
        color: #ffffff;
        animation: bounceIn 1s ease-out;
        margin-right: 1rem;
    }
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes bounceIn {
        0% { opacity: 0; transform: scale(0.3); }
        50% { opacity: 1; transform: scale(1.05); }
        70% { transform: scale(0.9); }
        100% { transform: scale(1); }
    }
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        margin-bottom: 2rem;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }
    .card-header {
        border-radius: 15px 15px 0 0 !important;
        font-weight: 600;
        padding: 1rem 1.5rem;
    }
    .card-body {
        padding: 1.5rem;
    }
    .form-control, .btn {
        border-radius: 10px;
    }
    .btn-primary {
        background-color: #007bff;
        border: none;
        transition: all 0.3s ease;
        padding: 0.5rem 1rem;
    }
    .btn-primary:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .table {
        border-radius: 15px;
        overflow: hidden;
    }
    .table th {
        background-color: #007bff;
        color: white;
        border: none;
    }
    .table td {
        vertical-align: middle;
    }
    @media (max-width: 768px) {
        .jumbotron {
            padding: 3rem 1rem;
        }
        .title-icon {
            font-size: 3rem;
            margin-right: 0.5rem;
        }
        .jumbotron h1 {
            font-size: 2rem;
        }
        .jumbotron p {
            font-size: 1rem;
        }
    }
</style>
</head>
<body>
<div class="container">
    <div class="jumbotron text-center">
        <div class="row align-items-center justify-content-center">
            <div class="col-auto">
                <i class="fas fa-baby title-icon"></i>
            </div>
            <div class="col-auto">
                <h1 class="display-4">Data Detail Balita</h1>
                <p class="lead">Sistem Informasi Pengelolaan Data Balita</p>
            </div>
        </div>
    </div>
</div>
    <div class="container mb-5">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Pilih Balita dan Bulan</h5>
                        <form method="post">
                            <div class="mb-3">
                                <label for="id_balita" class="form-label">Pilih Balita:</label>
                                <select name="id_balita" id="id_balita" class="form-select">
                                    <?php foreach ($balitas as $balita): ?>
                                        <option value="<?php echo htmlspecialchars($balita['id_balita']); ?>"
                                            <?php if ($balita['id_balita'] == $id_balita): ?> selected <?php endif; ?>>
                                            <?php echo htmlspecialchars($balita['nama_balita']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="bulan" class="form-label">Pilih Bulan:</label>
                                <select name="bulan" id="bulan" class="form-select">
                                    <?php foreach ($months as $month): ?>
                                        <option value="<?php echo htmlspecialchars($month); ?>"
                                            <?php if ($month === $bulan): ?> selected <?php endif; ?>>
                                            <?php echo ucfirst($month); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <?php if (!empty($balitaData)): ?>
                    <?php $row = $balitaData[0]; ?>
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Data Balita</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>ID Balita:</strong> <?php echo htmlspecialchars($row['id_balita']); ?></p>
                                    <p><strong>Nama:</strong> <?php echo htmlspecialchars($row['nama_balita']); ?></p>
                                    <p><strong>Jenis Kelamin:</strong> <?php echo htmlspecialchars($row['jenis_kelamin']); ?></p>
                                    <p><strong>NIK:</strong> <?php echo htmlspecialchars($row['nik']); ?></p>
                                    <p><strong>Tanggal Lahir:</strong> <?php echo htmlspecialchars($row['tanggal_lahir']); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Berat Badan Lahir:</strong> <?php echo htmlspecialchars($row['berat_badan_lahir']); ?></p>
                                    <p><strong>Nama Ayah:</strong> <?php echo htmlspecialchars($row['nama_ayah']); ?></p>
                                    <p><strong>Nama Ibu:</strong> <?php echo htmlspecialchars($row['nama_ibu']); ?></p>
                                    <p><strong>Alamat:</strong> <?php echo htmlspecialchars($row['alamat']); ?></p>
                                    <p><strong>Status:</strong> <?php echo htmlspecialchars($row['status']); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($pengukuranData)): ?>
                        <?php $pengukuran = $pengukuranData[0]; ?>
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">Pengukuran Balita (<?php echo ucfirst($bulan); ?>)</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>ID Pengukuran:</strong> <?php echo htmlspecialchars($pengukuran['id_pengukuran']); ?></p>
                                        <p><strong>Tanggal Pengukuran:</strong> <?php echo htmlspecialchars($pengukuran['tanggal_pengukuran']); ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Berat Badan:</strong> <?php echo htmlspecialchars($pengukuran['berat_badan']); ?> kg</p>
                                        <p><strong>Tinggi Badan:</strong> <?php echo htmlspecialchars($pengukuran['tinggi_badan']); ?> cm</p>
                                        <p><strong>Status Gizi:</strong> <?php echo htmlspecialchars($pengukuran['status_gizi']); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info mt-3" role="alert">
                            Tidak ada data pengukuran untuk bulan <?php echo ucfirst($bulan); ?>.
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-warning" role="alert">
                        Tidak ada data untuk balita ini.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php 
require_once __DIR__ . '/../pos_3/footer_balita_p3.php';
 ?>