<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../pos_4/header_ih_p4.php';

// Konstanta untuk nama tabel
define('TABLE_IBU_HAMIL_4', 'ibu_hamil_4');
define('TABLE_CATATAN_KEHAMILAN_4', 'catatan_kehamilan_4');

// Fungsi untuk validasi dan sanitasi input
function validateInput($data) {
    $cleanData = [];
    foreach ($data as $key => $value) {
        switch ($key) {
            case 'id_ibu':
            case 'mendapatkan_bantuan':
            case 'mempunyai_bpjs':
                $cleanData[$key] = filter_var($value, FILTER_VALIDATE_INT);
                break;
            case 'hamil_keberapa':
                $cleanData[$key] = filter_var($value, FILTER_VALIDATE_INT, [
                    'options' => ['min_range' => 1]
                ]);
                if ($cleanData[$key] === false) {
                    $cleanData[$key] = 1; // Default ke 1 jika input tidak valid
                }
                break;
            case 'tinggi_badan':
            case 'berat_badan':
            case 'lila':
                $cleanData[$key] = filter_var($value, FILTER_VALIDATE_FLOAT);
                break;
            case 'bulan':
                $cleanData[$key] = in_array(strtolower($value), ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember']) ? strtolower($value) : null;
                break;
            default:
                $cleanData[$key] = filter_var($value, FILTER_SANITIZE_STRING);
                break;
        }
    }
    return $cleanData;
}

// Menangani pengiriman formulir untuk menambah, mengedit, dan menghapus catatan kehamilan 4
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'add':
        case 'edit':
            $data = validateInput([
                'id_ibu' => $_POST['id_ibu'] ?? null,
                'hamil_keberapa' => $_POST['hamil_keberapa'] ?? null,
                'hpht' => $_POST['hpht'] ?? null,
                'hpl' => $_POST['hpl'] ?? null,
                'usia_kehamilan' => $_POST['usia_kehamilan'] ?? null,
                'status_kehamilan' => $_POST['status_kehamilan'] ?? null,
                'tinggi_badan' => $_POST['tinggi_badan'] ?? null,
                'berat_badan' => $_POST['berat_badan'] ?? null,
                'lila' => $_POST['lila'] ?? null,
                'laboratorium' => $_POST['laboratorium'] ?? null,
                'imunisasi' => $_POST['imunisasi'] ?? null,
                'mendapatkan_bantuan' => $_POST['mendapatkan_bantuan'] ?? null,
                'mempunyai_bpjs' => $_POST['mempunyai_bpjs'] ?? null,
                'bulan' => $_POST['bulan'] ?? null
            ]);

            if ($action === 'add') {
                $sql = "INSERT INTO " . TABLE_CATATAN_KEHAMILAN_4 . " (id_ibu, hamil_keberapa, hpht, hpl, usia_kehamilan, status_kehamilan, tinggi_badan, berat_badan, lila, laboratorium, imunisasi, mendapatkan_bantuan, mempunyai_bpjs, bulan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            } else {
                $data['id_kehamilan'] = filter_var($_POST['id_kehamilan'], FILTER_VALIDATE_INT);
                $sql = "UPDATE " . TABLE_CATATAN_KEHAMILAN_4 . " SET id_ibu = ?, hamil_keberapa = ?, hpht = ?, hpl = ?, usia_kehamilan = ?, status_kehamilan = ?, tinggi_badan = ?, berat_badan = ?, lila = ?, laboratorium = ?, imunisasi = ?, mendapatkan_bantuan = ?, mempunyai_bpjs = ?, bulan = ? WHERE id_kehamilan = ?";
            }

            $stmt = $db->prepare($sql);
            $stmt->execute(array_values($data));
            break;

        case 'delete':
            $id_kehamilan = filter_var($_POST['id_kehamilan'], FILTER_VALIDATE_INT);
            if ($id_kehamilan) {
                $stmt = $db->prepare("DELETE FROM " . TABLE_CATATAN_KEHAMILAN_4 . " WHERE id_kehamilan = ?");
                $stmt->execute([$id_kehamilan]);
            }
            break;
    }

    // Redirect untuk menghindari pengiriman ulang form
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Mengambil data catatan kehamilan untuk diedit
$catatan_kehamilan_4 = null;
if (isset($_GET['edit'])) {
    $id_kehamilan = filter_var($_GET['edit'], FILTER_VALIDATE_INT);
    if ($id_kehamilan) {
        $stmt = $db->prepare("SELECT * FROM " . TABLE_CATATAN_KEHAMILAN_4 . " WHERE id_kehamilan = ?");
        $stmt->execute([$id_kehamilan]);
        $catatan_kehamilan_4 = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// Mengambil daftar semua ibu hamil untuk dropdown
$stmt = $db->query("SELECT id_ibu, nama_ibu_hamil FROM " . TABLE_IBU_HAMIL_4);
$ibu_hamil_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mengambil daftar semua catatan kehamilan
$stmt = $db->query("SELECT c.*, i.nama_ibu_hamil FROM " . TABLE_CATATAN_KEHAMILAN_4 . " c JOIN " . TABLE_IBU_HAMIL_4 . " i ON c.id_ibu = i.id_ibu ORDER BY c.id_kehamilan ASC");
$catatan_kehamilan_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Daftar bulan
$bulan_list = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Catatan Kehamilan</title>
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
                <i class="fas fa-female title-icon"></i>
            </div>
            <div class="col-auto">
                <h1 class="display-4">Catatan Kehamilan</h1>
                <p class="lead">Sistem Informasi Pengelolaan Data Kehamilan</p>
            </div>
        </div>
    </div>
</div>
 
<div class="container">
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Tambah/Edit Catatan Kehamilan</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="<?php echo isset($catatan_kehamilan_4) ? 'edit' : 'add'; ?>">
                        <?php if (isset($catatan_kehamilan_4)): ?>
                            <input type="hidden" name="id_kehamilan" value="<?php echo $catatan_kehamilan_4['id_kehamilan']; ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="id_ibu" class="form-label">Nama Ibu Hamil</label>
                            <select class="form-select" id="id_ibu" name="id_ibu" required>
                                <?php foreach ($ibu_hamil_list as $ibu): ?>
                                    <option value="<?php echo $ibu['id_ibu']; ?>" <?php echo (isset($catatan_kehamilan_4) && $catatan_kehamilan_4['id_ibu'] == $ibu['id_ibu']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($ibu['nama_ibu_hamil']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="hamil_keberapa" class="form-label">Hamil Ke-</label>
                            <input type="number" class="form-control" id="hamil_keberapa" name="hamil_keberapa" 
                                value="<?php echo isset($catatan_kehamilan_4['hamil_keberapa']) ? $catatan_kehamilan_4['hamil_keberapa'] : 1; ?>" 
                                required min="1" step="1">
                        </div>

                        <div class="mb-3">
                            <label for="hpht" class="form-label">HPHT</label>
                            <input type="date" class="form-control" id="hpht" name="hpht" value="<?php echo isset($catatan_kehamilan_4) ? $catatan_kehamilan_4['hpht'] : ''; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="hpl" class="form-label">HPL</label>
                            <input type="date" class="form-control" id="hpl" name="hpl" value="<?php echo isset($catatan_kehamilan_4) ? $catatan_kehamilan_4['hpl'] : ''; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="usia_kehamilan" class="form-label">Usia Kehamilan</label>
                            <input type="text" class="form-control" id="usia_kehamilan" name="usia_kehamilan" value="<?php echo isset($catatan_kehamilan_4) ? $catatan_kehamilan_4['usia_kehamilan'] : ''; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="status_kehamilan" class="form-label">Status Kehamilan</label>
                            <input type="text" class="form-control" id="status_kehamilan" name="status_kehamilan" value="<?php echo isset($catatan_kehamilan_4) ? $catatan_kehamilan_4['status_kehamilan'] : ''; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="tinggi_badan" class="form-label">Tinggi Badan (cm)</label>
                            <input type="number" step="0.1" class="form-control" id="tinggi_badan" name="tinggi_badan" value="<?php echo isset($catatan_kehamilan_4) ? $catatan_kehamilan_4['tinggi_badan'] : ''; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="berat_badan" class="form-label">Berat Badan (kg)</label>
                            <input type="number" step="0.1" class="form-control" id="berat_badan" name="berat_badan" value="<?php echo isset($catatan_kehamilan_4) ? $catatan_kehamilan_4['berat_badan'] : ''; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="lila" class="form-label">LILA (cm)</label>
                            <input type="number" step="0.1" class="form-control" id="lila" name="lila" value="<?php echo isset($catatan_kehamilan_4) ? $catatan_kehamilan_4['lila'] : ''; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="laboratorium" class="form-label">Laboratorium</label>
                            <textarea class="form-control" id="laboratorium" name="laboratorium" required><?php echo isset($catatan_kehamilan_4) ? $catatan_kehamilan_4['laboratorium'] : ''; ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="imunisasi" class="form-label">Imunisasi</label>
                            <input type="text" class="form-control" id="imunisasi" name="imunisasi" value="<?php echo isset($catatan_kehamilan_4) ? $catatan_kehamilan_4['imunisasi'] : ''; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="mendapatkan_bantuan" class="form-label">Mendapatkan Bantuan</label>
                            <select class="form-select" id="mendapatkan_bantuan" name="mendapatkan_bantuan" required>
                                <option value="1" <?php echo (isset($catatan_kehamilan_4) && $catatan_kehamilan_4['mendapatkan_bantuan'] == 1) ? 'selected' : ''; ?>>Ya</option>
                                <option value="0" <?php echo (isset($catatan_kehamilan_4) && $catatan_kehamilan_4['mendapatkan_bantuan'] == 0) ? 'selected' : ''; ?>>Tidak</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="mempunyai_bpjs" class="form-label">Mempunyai BPJS</label>
                            <select class="form-select" id="mempunyai_bpjs" name="mempunyai_bpjs" required>
                            <option value="1" <?php echo (isset($catatan_kehamilan_4) && $catatan_kehamilan_4['mempunyai_bpjs'] == 1) ? 'selected' : ''; ?>>Ya</option>
                                <option value="0" <?php echo (isset($catatan_kehamilan_4) && $catatan_kehamilan_4['mempunyai_bpjs'] == 0) ? 'selected' : ''; ?>>Tidak</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="bulan" class="form-label">Bulan</label>
                            <select class="form-select" id="bulan" name="bulan" required>
                                <?php foreach ($bulan_list as $bulan): ?>
                                    <option value="<?php echo $bulan; ?>" <?php echo (isset($catatan_kehamilan_4) && $catatan_kehamilan_4['bulan'] == $bulan) ? 'selected' : ''; ?>>
                                        <?php echo ucfirst($bulan); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i><?php echo isset($catatan_kehamilan_4) ? 'Update' : 'Tambah'; ?> Catatan Kehamilan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Daftar Catatan Kehamilan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nama Ibu Hamil</th>
                                    <th>Hamil Ke</th>
                                    <th>HPHT</th>
                                    <th>HPL</th>
                                    <th>Status Kehamilan</th>
                                    <th>Bulan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($catatan_kehamilan_list as $catatan): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($catatan['nama_ibu_hamil']); ?></td>
                                        <td><?php echo intval($catatan['hamil_keberapa']); ?></td>
                                        <td><?php echo date('d-m-Y', strtotime($catatan['hpht'])); ?></td>
                                        <td><?php echo date('d-m-Y', strtotime($catatan['hpl'])); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $catatan['status_kehamilan'] == 'Normal' ? 'success' : 'warning'; ?>">
                                                <?php echo htmlspecialchars($catatan['status_kehamilan']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars(ucfirst($catatan['bulan'])); ?></td>
                                        <td>
                                            <a href="?edit=<?php echo $catatan['id_kehamilan']; ?>" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="post" style="display: inline;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id_kehamilan" value="<?php echo $catatan['id_kehamilan']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" title="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php 
require_once __DIR__ . '/../pos_4/footer_ih_p4.php';
?>