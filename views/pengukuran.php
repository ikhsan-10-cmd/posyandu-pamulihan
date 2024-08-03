<?php
require_once 'config.php';
include 'includes/header.php';

// Di bagian atas file, setelah include header
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $id_balita = $_GET['id'];
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            var createModal = new bootstrap.Modal(document.getElementById('createModal'));
            createModal.show();
            document.getElementById('id_balita').value = $id_balita;
        });
    </script>";
}

// Create
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'create') {
    $nomer = $_POST['nomer'];
    $id_balita = $_POST['id_balita'];
    $tanggal_pengukuran = $_POST['tanggal_pengukuran'];
    $bb = $_POST['bb'];
    $tb = $_POST['tb'];
    $status_gizi = $_POST['status_gizi'];
    $keterangan = $_POST['keterangan'];

    $sql = "INSERT INTO pengukuran (nomer, id_balita, tanggal_pengukuran, bb, tb, status_gizi, keterangan) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    query($sql, [$nomer, $id_balita, $tanggal_pengukuran, $bb, $tb, $status_gizi, $keterangan]);
    header("Location: pengukuran.php");
    exit();
}

// Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
    $id_pengukuran = $_POST['id_pengukuran'];
    $nomer = $_POST['nomer'];
    $id_balita = $_POST['id_balita'];
    $tanggal_pengukuran = $_POST['tanggal_pengukuran'];
    $bb = $_POST['bb'];
    $tb = $_POST['tb'];
    $status_gizi = $_POST['status_gizi'];
    $keterangan = $_POST['keterangan'];

    $sql = "UPDATE pengukuran SET nomer=?, id_balita=?, tanggal_pengukuran=?, 
            bb=?, tb=?, status_gizi=?, keterangan=? 
            WHERE id_pengukuran=?";
    query($sql, [$nomer, $id_balita, $tanggal_pengukuran, $bb, $tb, $status_gizi, $keterangan, $id_pengukuran]);
    header("Location: pengukuran.php");
    exit();
}

// Delete
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_pengukuran = $_GET['id'];
    $sql = "DELETE FROM pengukuran WHERE id_pengukuran=?";
    query($sql, [$id_pengukuran]);
    header("Location: pengukuran.php");
    exit();
}

$result = query("SELECT p.*, b.nama_balita FROM pengukuran p JOIN balita b ON p.id_balita = b.id_balita");
$pengukuran = fetchAll($result);

$result_balita = query("SELECT id_balita, nama_balita FROM balita");
$balita = fetchAll($result_balita);
// Di bagian atas file, setelah include header
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
  $id_balita = escapeString($_GET['id']);
  $result = query("SELECT * FROM balita WHERE id_balita = '$id_balita'");
  $balita_edit = fetchAll($result)[0];
  echo "<script>
      document.addEventListener('DOMContentLoaded', function() {
          var editModal = new bootstrap.Modal(document.getElementById('editModal" . $balita_edit['id_balita'] . "'));
          editModal.show();
      });
  </script>";
}

// Create
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'create') {
  $no = escapeString($_POST['no']);
  $nama_balita = escapeString($_POST['nama_balita']);
  $jenis_kelamin = escapeString($_POST['jenis_kelamin']);
  $nik = escapeString($_POST['nik']);
  $tanggal_lahir = escapeString($_POST['tanggal_lahir']);
  $nama_ayah = escapeString($_POST['nama_ayah']);
  $nama_ibu = escapeString($_POST['nama_ibu']);
  $alamat = escapeString($_POST['alamat']);
  $status = escapeString($_POST['status']);

  $sql = "INSERT INTO balita (no, nama_balita, jenis_kelamin, nik, tanggal_lahir, nama_ayah, nama_ibu, alamat, status) 
          VALUES ('$no', '$nama_balita', '$jenis_kelamin', '$nik', '$tanggal_lahir', '$nama_ayah', '$nama_ibu', '$alamat', '$status')";
  query($sql);
  header("Location: balita.php");
  exit();
}

// Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
  $id_balita = escapeString($_POST['id_balita']);
  $no = escapeString($_POST['no']);
  $nama_balita = escapeString($_POST['nama_balita']);
  $jenis_kelamin = escapeString($_POST['jenis_kelamin']);
  $nik = escapeString($_POST['nik']);
  $tanggal_lahir = escapeString($_POST['tanggal_lahir']);
  $nama_ayah = escapeString($_POST['nama_ayah']);
  $nama_ibu = escapeString($_POST['nama_ibu']);
  $alamat = escapeString($_POST['alamat']);
  $status = escapeString($_POST['status']);

  $sql = "UPDATE balita SET no='$no', nama_balita='$nama_balita', jenis_kelamin='$jenis_kelamin', nik='$nik', 
          tanggal_lahir='$tanggal_lahir', nama_ayah='$nama_ayah', nama_ibu='$nama_ibu', alamat='$alamat', status='$status' 
          WHERE id_balita='$id_balita'";
  query($sql);
  header("Location: balita.php");
  exit();
}

// Delete
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
  $id_balita = escapeString($_GET['id']);
  $sql = "DELETE FROM balita WHERE id_balita='$id_balita'";
  query($sql);
  header("Location: balita.php");
  exit();
}

$result = query("SELECT * FROM balita");
$balita = fetchAll($result);
?>

<h1 class="mb-4">Data Balita</h1>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
  Tambah Balita
</button>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createModalLabel">Tambah Balita</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST">
          <input type="hidden" name="action" value="create">
          <div class="mb-3">
            <label for="no" class="form-label">No</label>
            <input type="number" class="form-control" id="no" name="no" required>
          </div>
          <div class="mb-3">
            <label for="nama_balita" class="form-label">Nama Balita</label>
            <input type="text" class="form-control" id="nama_balita" name="nama_balita" required>
          </div>
          <div class="mb-3">
            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="nik" class="form-label">NIK</label>
            <input type="text" class="form-control" id="nik" name="nik" required>
          </div>
          <div class="mb-3">
            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
          </div>
          <div class="mb-3">
            <label for="nama_ayah" class="form-label">Nama Ayah</label>
            <input type="text" class="form-control" id="nama_ayah" name="nama_ayah" required>
          </div>
          <div class="mb-3">
            <label for="nama_ibu" class="form-label">Nama Ibu</label>
            <input type="text" class="form-control" id="nama_ibu" name="nama_ibu" required>
          </div>
          <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" required></textarea>
          </div>
          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="aktif">Aktif</option>
                <option value="Lulus">Lulus</option>
                <option value="Keluar">Keluar</option>
                <option value="Pindah">Pindah</option>
                <option value="Meninggal">Meninggal</option>
                <option value="Tidak Aktif">Tidak Aktif</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>No</th>
            <th>Nama Balita</th>
            <th>Jenis Kelamin</th>
            <th>NIK</th>
            <th>Tanggal Lahir</th>
            <th>Nama Ayah</th>
            <th>Nama Ibu</th>
            <th>Alamat</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($balita as $b): ?>
        <tr>
            <td><?= $b['id_balita'] ?></td>
            <td><?= $b['no'] ?></td>
            <td><?= $b['nama_balita'] ?></td>
            <td><?= $b['jenis_kelamin'] ?></td>
            <td><?= $b['nik'] ?></td>
            <td><?= $b['tanggal_lahir'] ?></td>
            <td><?= $b['nama_ayah'] ?></td>
            <td><?= $b['nama_ibu'] ?></td>
            <td><?= $b['alamat'] ?></td>
            <td><?= $b['status'] ?></td>
            <td>
                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $b['id_balita'] ?>">
                    Edit
                </button>
                <a href="balita.php?action=delete&id=<?= $b['id_balita'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
            </td>
        </tr>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal<?= $b['id_balita'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $b['id_balita'] ?>" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel<?= $b['id_balita'] ?>">Edit Balita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form method="POST">
                  <input type="hidden" name="action" value="update">
                  <input type="hidden" name="id_balita" value="<?= $b['id_balita'] ?>">
                  <div class="mb-3">
                    <label for="no" class="form-label">No</label>
                    <input type="number" class="form-control" id="no" name="no" value="<?= $b['no'] ?>" required>
                  </div>
                  <div class="mb-3">
                    <label for="nama_balita" class="form-label">Nama Balita</label>
                    <input type="text" class="form-control" id="nama_balita" name="nama_balita" value="<?= $b['nama_balita'] ?>" required>
                  </div>
                  <div class="mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="Laki-laki" <?= $b['jenis_kelamin'] == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="Perempuan" <?= $b['jenis_kelamin'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="nik" class="form-label">NIK</label>
                    <input type="text" class="form-control" id="nik" name="nik" value="<?= $b['nik'] ?>" required>
                  </div>
                  <div class="mb-3">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?= $b['tanggal_lahir'] ?>" required>
                  </div>
                  <div class="mb-3">
                    <label for="nama_ayah" class="form-label">Nama Ayah</label>
                    <input type="text" class="form-control" id="nama_ayah" name="nama_ayah" value="<?= $b['nama_ayah'] ?>" required>
                  </div>
                  <div class="mb-3">
                    <label for="nama_ibu" class="form-label">Nama Ibu</label>
                    <input type="text" class="form-control" id="nama_ibu" name="nama_ibu" value="<?= $b['nama_ibu'] ?>" required>
                  </div>
                  <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" required><?= $b['alamat'] ?></textarea>
                  </div>
                  <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="aktif" <?= $b['status'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                        <option value="Lulus" <?= $b['status'] == 'Lulus' ? 'selected' : '' ?>>Lulus</option>
                        <option value="Keluar" <?= $b['status'] == 'Keluar' ? 'selected' : '' ?>>Keluar</option>
                        <option value="Pindah" <?= $b['status'] == 'Pindah' ? 'selected' : '' ?>>Pindah</option>
                        <option value="Meninggal" <?= $b['status'] == 'Meninggal' ? 'selected' : '' ?>>Meninggal</option>
                        <option value="Tidak Aktif" <?= $b['status'] == 'Tidak Aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                    </select>
                  </div>
                  <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
    </tbody>
</table>

<h1 class="mb-4">Data Pengukuran</h1>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
  Tambah Pengukuran
</button>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createModalLabel">Tambah Pengukuran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST">
          <input type="hidden" name="action" value="create">
          <div class="mb-3">
            <label for="nomer" class="form-label">Nomer</label>
            <input type="number" class="form-control" id="nomer" name="nomer" required>
          </div>
          <div class="mb-3">
            <label for="id_balita" class="form-label">Nama Balita</label>
            <select class="form-control" id="id_balita" name="id_balita" required>
                <?php foreach ($balita as $b): ?>
                    <option value="<?= $b['id_balita'] ?>"><?= $b['nama_balita'] ?></option>
                <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="tanggal_pengukuran" class="form-label">Tanggal Pengukuran</label>
            <input type="date" class="form-control" id="tanggal_pengukuran" name="tanggal_pengukuran" required>
          </div>
          <div class="mb-3">
            <label for="bb" class="form-label">Berat Badan (kg)</label>
            <input type="number" step="0.01" class="form-control" id="bb" name="bb" required>
          </div>
          <div class="mb-3">
            <label for="tb" class="form-label">Tinggi Badan (cm)</label>
            <input type="number" step="0.01" class="form-control" id="tb" name="tb" required>
          </div>
          <div class="mb-3">
            <label for="status_gizi" class="form-label">Status Gizi</label>
            <select class="form-control" id="status_gizi" name="status_gizi" required>
                <option value="Gizi Buruk">Gizi Buruk</option>
                <option value="Gizi Kurang">Gizi Kurang</option>
                <option value="Normal">Normal</option>
                <option value="Lebih">Lebih</option>
                <option value="Obesitas">Obesitas</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nomer</th>
            <th>Nama Balita</th>
            <th>Tanggal Pengukuran</th>
            <th>Berat Badan (kg)</th>
            <th>Tinggi Badan (cm)</th>
            <th>Status Gizi</th>
            <th>Keterangan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pengukuran as $p): ?>
        <tr>
            <td><?= $p['id_pengukuran'] ?></td>
            <td><?= $p['nomer'] ?></td>
            <td><?= $p['nama_balita'] ?></td>
            <td><?= $p['tanggal_pengukuran'] ?></td>
            <td><?= $p['bb'] ?></td>
            <td><?= $p['tb'] ?></td>
            <td><?= $p['status_gizi'] ?></td>
            <td><?= $p['keterangan'] ?></td>
            <td>
                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $p['id_pengukuran'] ?>">
                    Edit
                </button>
                <a href="pengukuran.php?action=delete&id=<?= $p['id_pengukuran'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
            </td>
        </tr>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal<?= $p['id_pengukuran'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $p['id_pengukuran'] ?>" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel<?= $p['id_pengukuran'] ?>">Edit Pengukuran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form method="POST">
                  <input type="hidden" name="action" value="update">
                  <input type="hidden" name="id_pengukuran" value="<?= $p['id_pengukuran'] ?>">
                  <div class="mb-3">
                    <label for="nomer" class="form-label">Nomer</label>
                    <input type="number" class="form-control" id="nomer" name="nomer" value="<?= $p['nomer'] ?>" required>
                  </div>
                  <div class="mb-3">
                    <label for="id_balita" class="form-label">Nama Balita</label>
                    <select class="form-control" id="id_balita" name="id_balita" required>
                        <?php foreach ($balita as $b): ?>
                            <option value="<?= $b['id_balita'] ?>" <?= $b['id_balita'] == $p['id_balita'] ? 'selected' : '' ?>><?= $b['nama_balita'] ?></option>
                        <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="tanggal_pengukuran" class="form-label">Tanggal Pengukuran</label>
                    <input type="date" class="form-control" id="tanggal_pengukuran" name="tanggal_pengukuran" value="<?= $p['tanggal_pengukuran'] ?>" required>
                  </div>
                  <div class="mb-3">
                    <label for="bb" class="form-label">Berat Badan (kg)</label>
                    <input type="number" step="0.01" class="form-control" id="bb" name="bb" value="<?= $p['bb'] ?>" required>
                  </div>
                  <div class="mb-3">
                    <label for="tb" class="form-label">Tinggi Badan (cm)</label>
                    <input type="number" step="0.01" class="form-control" id="tb" name="tb" value="<?= $p['tb'] ?>" required>
                  </div>
                  <div class="mb-3">
                    <label for="status_gizi" class="form-label">Status Gizi</label>
                    <select class="form-control" id="status_gizi" name="status_gizi" required>
                        <option value="Gizi Buruk" <?= $p['status_gizi'] == 'Gizi Buruk' ? 'selected' : '' ?>>Gizi Buruk</option>
                        <option value="Gizi Kurang" <?= $p['status_gizi'] == 'Gizi Kurang' ? 'selected' : '' ?>>Gizi Kurang</option>
                        <option value="Normal" <?= $p['status_gizi'] == 'Normal' ? 'selected' : '' ?>>Normal</option>
                        <option value="Lebih" <?= $p['status_gizi'] == 'Lebih' ? 'selected' : '' ?>>Lebih</option>
                        <option value="Obesitas" <?= $p['status_gizi'] == 'Obesitas' ? 'selected' : '' ?>>Obesitas</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan"><?= $p['keterangan'] ?></textarea>
                  </div>
                  <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
    </tbody>
</table>

<h2 class="mt-5 mb-3">Detail Data Balita per Bulan</h2>

<?php
$months = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];

foreach ($months as $month_num => $month_name):
    $result = query("SELECT p.*, b.nama_balita 
                     FROM pengukuran p 
                     JOIN balita b ON p.id_balita = b.id_balita 
                     WHERE CAST(strftime('%m', p.tanggal_pengukuran) AS INTEGER) = ?
                     ORDER BY b.nama_balita", [$month_num]);
    $data_bulan = fetchAll($result);
?>

<h3><?= $month_name ?></h3>
<?php if (count($data_bulan) > 0): ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nama Balita</th>
                <th>Tanggal Pengukuran</th>
                <th>Berat Badan (kg)</th>
                <th>Tinggi Badan (cm)</th>
                <th>Status Gizi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data_bulan as $data): ?>
            <tr>
                <td><?= $data['nama_balita'] ?></td>
                <td><?= $data['tanggal_pengukuran'] ?></td>
                <td><?= $data['bb'] ?></td>
                <td><?= $data['tb'] ?></td>
                <td><?= $data['status_gizi'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Tidak ada data pengukuran untuk bulan ini.</p>
<?php endif; ?>

<?php endforeach; ?>

<?php
include 'includes/footer.php';
?>