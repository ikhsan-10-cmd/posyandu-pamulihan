<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/header_dashboard.php';

// Menangani pengiriman formulir untuk menambah dan mengedit balita
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add') {
        $stmt = $db->prepare("INSERT INTO balita (nama_balita, jenis_kelamin, nik, tanggal_lahir, berat_badan_lahir, nama_ayah, nama_ibu, alamat, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['nama_balita'],
            $_POST['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan',
            $_POST['nik'],
            $_POST['tanggal_lahir'],
            $_POST['berat_badan_lahir'],
            $_POST['nama_ayah'],
            $_POST['nama_ibu'],
            $_POST['alamat'],
            $_POST['status']
        ]);
    } elseif ($action === 'edit') {
        $stmt = $db->prepare("UPDATE balita SET nama_balita = ?, jenis_kelamin = ?, nik = ?, tanggal_lahir = ?, berat_badan_lahir = ?, nama_ayah = ?, nama_ibu = ?, alamat = ?, status = ? WHERE id_balita = ?");
        $stmt->execute([
            $_POST['nama_balita'],
            $_POST['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan',
            $_POST['nik'],
            $_POST['tanggal_lahir'],
            $_POST['berat_badan_lahir'],
            $_POST['nama_ayah'],
            $_POST['nama_ibu'],
            $_POST['alamat'],
            $_POST['status'],
            $_POST['id_balita']
        ]);
    } elseif ($action === 'delete') {
        $stmt = $db->prepare("DELETE FROM balita WHERE id_balita = ?");
        $result = $stmt->execute([$_POST['id_balita']]);
        if ($result) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode(['success' => true]);
                exit;
            }
        } else {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode(['success' => false, 'message' => 'Gagal menghapus data']);
                exit;
            }
        }
    }
}

// Mengambil data balita untuk diedit
$balita = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM balita WHERE id_balita = ?");
    $stmt->execute([$_GET['edit']]);
    $balita = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Mengambil daftar semua balita
$balitas = $db->query("SELECT * FROM balita")->fetchAll(PDO::FETCH_ASSOC);
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
        }
        .title-container {
            background: linear-gradient(135deg, #007bff, #6610f2);
            padding: 3rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .animated-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #ffffff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            animation: fadeInDown 1s ease-out;
        }
        .title-icon {
            font-size: 4rem;
            color: #ffffff;
            animation: bounce 2s infinite;
        }
        .subtitle {
            color: #ffffff;
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
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-30px); }
            60% { transform: translateY(-15px); }
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        .card-header {
            border-radius: 15px 15px 0 0 !important;
            font-weight: 600;
        }
        .form-control, .btn {
            border-radius: 10px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            transition: all 0.3s ease;
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
        }
        .table-responsive {
            max-height: 600px;
            overflow-y: auto;
        }
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .header {
            background: linear-gradient(45deg, #007bff, #6610f2);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
        }
    </style>
</head>
<body>

<header class="header animate__animated animate__fadeIn">
<div class="title-container">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-3 text-center">
                <i class="fas fa-baby title-icon"></i>
            </div>
            <div class="col-md-9">
                <h1 class="animated-title">Manajemen Balita</h1>
                <p class="subtitle lead">Sistem Informasi Pengelolaan Data Balita</p>
            </div>
        </div>
    </div>
</div>
</header>

<div class="container mb-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Tambah/Edit Balita</h5>
                </div>
                <div class="card-body">
                    <form method="post">
                        <input type="hidden" name="id_balita" value="<?php echo isset($balita) ? htmlspecialchars($balita['id_balita']) : ''; ?>">
                        <div class="mb-3">
                            <label for="nama_balita" class="form-label">Nama Balita:</label>
                            <input type="text" name="nama_balita" id="nama_balita" class="form-control" value="<?php echo isset($balita) ? htmlspecialchars($balita['nama_balita']) : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin:</label>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
                                <option value="L" <?php echo isset($balita) && $balita['jenis_kelamin'] === 'Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
                                <option value="P" <?php echo isset($balita) && $balita['jenis_kelamin'] === 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nik" class="form-label">NIK:</label>
                            <input type="text" name="nik" id="nik" class="form-control" value="<?php echo isset($balita) ? htmlspecialchars($balita['nik']) : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir:</label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" value="<?php echo isset($balita) ? htmlspecialchars($balita['tanggal_lahir']) : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="berat_badan_lahir" class="form-label">Berat Badan Lahir:</label>
                            <input type="number" step="0.01" name="berat_badan_lahir" id="berat_badan_lahir" class="form-control" value="<?php echo isset($balita) ? htmlspecialchars($balita['berat_badan_lahir']) : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_ayah" class="form-label">Nama Ayah:</label>
                            <input type="text" name="nama_ayah" id="nama_ayah" class="form-control" value="<?php echo isset($balita) ? htmlspecialchars($balita['nama_ayah']) : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="nama_ibu" class="form-label">Nama Ibu:</label>
                            <input type="text" name="nama_ibu" id="nama_ibu" class="form-control" value="<?php echo isset($balita) ? htmlspecialchars($balita['nama_ibu']) : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat:</label>
                            <input type="text" name="alamat" id="alamat" class="form-control" value="<?php echo isset($balita) ? htmlspecialchars($balita['alamat']) : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status:</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="Aktif" <?php echo isset($balita) && $balita['status'] === 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
                                <option value="Lulus" <?php echo isset($balita) && $balita['status'] === 'Lulus' ? 'selected' : ''; ?>>Lulus</option>
                                <option value="Keluar" <?php echo isset($balita) && $balita['status'] === 'Keluar' ? 'selected' : ''; ?>>Keluar</option>
                                <option value="Pindah" <?php echo isset($balita) && $balita['status'] === 'Pindah' ? 'selected' : ''; ?>>Pindah</option>
                                <option value="Meninggal" <?php echo isset($balita) && $balita['status'] === 'Meninggal' ? 'selected' : ''; ?>>Meninggal</option>
                                <option value="Tidak Aktif" <?php echo isset($balita) && $balita['status'] === 'Tidak Aktif' ? 'selected' : ''; ?>>Tidak Aktif</option>
                            </select>
                        </div>
                        <button type="submit" name="action" value="<?php echo isset($balita) ? 'edit' : 'add'; ?>" class="btn btn-primary w-100">Simpan</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Daftar Balita</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($balitas as $balita): ?>
                                    <tr id="balita-row-<?php echo htmlspecialchars($balita['id_balita']); ?>">
                                        <td><?php echo htmlspecialchars($balita['id_balita']); ?></td>
                                        <td><?php echo htmlspecialchars($balita['nama_balita']); ?></td>
                                        <td><?php echo htmlspecialchars($balita['jenis_kelamin']); ?></td>
                                        <td>
                                            <a href="balita_crud.php?edit=<?php echo htmlspecialchars($balita['id_balita']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <button type="button" class="btn btn-danger btn-sm delete-balita" data-id="<?php echo htmlspecialchars($balita['id_balita']); ?>">Hapus</button>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    $('.delete-balita').on('click', function() {
        var balitaId = $(this).data('id');
        if (confirm('Apakah Anda yakin ingin menghapus balita ini?')) {
            $.ajax({
                url: 'balita_crud.php',
                type: 'POST',
                data: {
                    action: 'delete',
                    id_balita: balitaId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#balita-row-' + balitaId).fadeOut(300, function() {
                            $(this).remove();
                        });
                        showAlert('success', 'Data balita berhasil dihapus.');
                    } else {
                        showAlert('danger', 'Gagal menghapus data balita.');
                    }
                },
                error: function() {
                    showAlert('danger', 'Terjadi kesalahan saat menghapus data.');
                }
            });
        }
    });

    function showAlert(type, message) {
        var alertHtml = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
                        message +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                        '</div>';
        $('.container').prepend(alertHtml);
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    }

    // Tambahkan animasi pada form input
    $('.form-control, .form-select').focus(function() {
        $(this).parent().addClass('form-group-focus');
    }).blur(function() {
        $(this).parent().removeClass('form-group-focus');
    });

    // Tambahkan efek hover pada baris tabel
    $('tbody tr').hover(
        function() {
            $(this).addClass('table-hover-highlight');
        },
        function() {
            $(this).removeClass('table-hover-highlight');
        }
    );
});
</script>

<style>
    .form-group-focus {
        transform: translateY(-5px);
        transition: all 0.3s ease;
    }
    .table-hover-highlight {
        background-color: #f8f9fa;
        transition: background-color 0.3s ease;
    }
    .fade-out {
        animation: fadeOut 0.5s ease;
    }
    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }
</style>

</body>
</html>

<?php include __DIR__ . '/../includes/footer_dashboard.php'; ?>