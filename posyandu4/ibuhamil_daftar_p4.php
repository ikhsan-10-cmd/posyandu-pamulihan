<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../pos_4/header_ih_p4.php';

// Menangani pengiriman formulir untuk menambah dan mengedit balita
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    if ($action === 'add') {
        $stmt = $db->prepare("INSERT INTO ibu_hamil_4 (nama_ibu_hamil, nik, tempat_tanggal_lahir_ibu, nama_suami, nik_suami, tempat_tanggal_lahir_suami, alamat) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['nama_ibu_hamil'],
            $_POST['nik'],
            $_POST['tempat_tanggal_lahir_ibu'],
            $_POST['nama_suami'],
            $_POST['nik_suami'],
            $_POST['tempat_tanggal_lahir_suami'],
            $_POST['alamat']
        ]);
    } elseif ($action === 'edit') {
        $stmt = $db->prepare("UPDATE ibu_hamil_4 SET nama_ibu_hamil = ?, nik = ?, tempat_tanggal_lahir_ibu = ?, nama_suami = ?, nik_suami = ?, tempat_tanggal_lahir_suami = ?, alamat = ? WHERE id_ibu = ?");
        $stmt->execute([
            $_POST['nama_ibu_hamil'],
            $_POST['nik'],
            $_POST['tempat_tanggal_lahir_ibu'],
            $_POST['nama_suami'],
            $_POST['nik_suami'],
            $_POST['tempat_tanggal_lahir_suami'],
            $_POST['alamat'],
            $_POST['id_ibu']
        ]);
    } elseif ($action === 'delete') {
        $stmt = $db->prepare("DELETE FROM ibu_hamil_4 WHERE id_ibu = ?");
        $result = $stmt->execute([$_POST['id_ibu']]);
        if ($result) {
            // Hapus juga data catatan kehamilan terkait
            $stmt = $db->prepare("DELETE FROM catatan_kehamilan_4 WHERE id_ibu = ?");
            $stmt->execute([$_POST['id_ibu']]);
            
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

// Mengambil data ibu hamil untuk diedit
$ibu_hamil = null;
if (isset($_GET['edit'])) {
    $result = getIbuHamil4AndCatatanKehamilan($db, $_GET['edit']);
    if (!empty($result)) {
        $ibu_hamil = $result[0];
    }
}

// Mengambil daftar semua ibu hamil
$stmt = $db->query("SELECT * FROM ibu_hamil_4");
$ibu_hamil_list = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
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
                <i class="fas fa-female title-icon"></i>
            </div>
            <div class="col-auto">
                <h1 class="display-4">Daftar Ibu Hamil</h1>
                <p class="lead">Sistem Informasi Pengelolaan Data Ibu Hamil</p>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Tambah/Edit Ibu Hamil</h5>
                </div>

                <div class="card-body">
                    <form method="post">
                        <input type="hidden" name="id_ibu" value="<?php echo isset($ibu_hamil) ? htmlspecialchars($ibu_hamil['id_ibu']) : ''; ?>">

                        <div class="mb-3">
                        <label for="nama_ibu_hamil" class="form-label">Nama Ibu Hamil:</label>
                        <input type="text" name="nama_ibu_hamil" id="nama_ibu_hamil" class="form-control" value="<?php echo $ibu_hamil ? htmlspecialchars($ibu_hamil['nama_ibu_hamil']) : ''; ?>" required>
                    </div>

                        <div class="mb-3">
                            <label for="nik" class="form-label">NIK:</label>
                            <input type="text" name="nik" id="nik" class="form-control" value="<?php echo isset($ibu_hamil) ? htmlspecialchars($ibu_hamil['nik']) : ''; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="tempat_tanggal_lahir_ibu" class="form-label">Tempat, Tanggal Lahir :</label>
                            <input type="text" name="tempat_tanggal_lahir_ibu" id="tempat_tanggal_lahir_ibu" class="form-control" value="<?php echo isset($ibu_hamil) ? htmlspecialchars($ibu_hamil['tempat_tanggal_lahir_ibu']) : ''; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="nama_suami" class="form-label">Nama Suami:</label>
                            <input type="text" name="nama_suami" id="nama_suami" class="form-control" value="<?php echo isset($ibu_hamil) ? htmlspecialchars($ibu_hamil['nama_suami']) : ''; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="nik_suami" class="form-label">NIK Suami:</label>
                            <input type="text" name="nik_suami" id="nik_suami" class="form-control" value="<?php echo isset($ibu_hamil) ? htmlspecialchars($ibu_hamil['nik_suami']) : ''; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="tempat_tanggal_lahir_suami" class="form-label">Tempat, Tanggal Lahir Suami:</label>
                            <input type="text" name="tempat_tanggal_lahir_suami" id="tempat_tanggal_lahir_suami" class="form-control" value="<?php echo isset($ibu_hamil) ? htmlspecialchars($ibu_hamil['tempat_tanggal_lahir_suami']) : ''; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat:</label>
                            <input type="text" name="alamat" id="alamat" class="form-control" value="<?php echo isset($ibu_hamil) ? htmlspecialchars($ibu_hamil['alamat']) : ''; ?>">
                        </div>

                        <button type="submit" name="action" value="<?php echo isset($ibu_hamil) ? 'edit' : 'add'; ?>" class="btn btn-primary w-100">Simpan</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Daftar Ibu Hamil</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                    <th>ID</th>
                                    <th>Nama ibu hamil</th>
                                    <th>NIK</th>
                                    <th>Tempat Tanggal Lahir</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ibu_hamil_list as $ibu): ?>
                                    <tr id="ibu-row-<?php echo htmlspecialchars($ibu['id_ibu']); ?>">
                                        <td><?php echo htmlspecialchars($ibu['id_ibu']); ?></td>
                                        <td><?php echo htmlspecialchars($ibu['nama_ibu_hamil']); ?></td>
                                        <td><?php echo htmlspecialchars($ibu['nik']); ?></td>
                                        <td><?php echo htmlspecialchars($ibu['tempat_tanggal_lahir_ibu'], ENT_QUOTES); ?></td>
                                        <td>
                                            <a href="?edit=<?php echo $ibu['id_ibu']; ?>" class="btn btn-sm btn-warning mb-1">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form method="post" style="display:inline;">
                                                <input type="hidden" name="id_ibu" value="<?php echo $ibu['id_ibu']; ?>">
                                                <button type="submit" name="action" value="delete" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                    <i class="fas fa-trash-alt"></i> Delete
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    $('.delete-ibu').on('click', function() {
        var ibuId = $(this).data('id');
        if (confirm('Apakah Anda yakin ingin menghapus ibu hamil ini?')) {
            $.ajax({
                url: 'balita_daftar_p4.php',
                type: 'POST',
                data: {
                    action: 'delete',
                    id_ibu: ibuId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#ibu-row-' + ibuId).fadeOut(300, function() {
                            $(this).remove();
                        });
                        showAlert('success', 'Data ibu hamil berhasil dihapus dari ibu hamil.');
                    } else {
                        showAlert('danger', 'Gagal menghapus data ibu hamil dari ibu hamil.');
                    }
                },
                error: function() {
                    showAlert('danger', 'Terjadi kesalahan saat menghapus data dari ibu hamil.');
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

<?php include __DIR__ . '/../pos_4/footer_ih_p4.php'; ?>