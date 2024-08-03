<?php
require_once 'config.php';
include 'includes/header.php';

$result_balita = query("SELECT COUNT(*) as total FROM balita");
$total_balita = fetchAll($result_balita)[0]['total'];

$result_pengukuran = query("SELECT COUNT(*) as total FROM pengukuran");
$total_pengukuran = fetchAll($result_pengukuran)[0]['total'];

$result_status_gizi = query("SELECT status_gizi, COUNT(*) as total FROM pengukuran GROUP BY status_gizi");
$status_gizi = fetchAll($result_status_gizi);

// Tambahkan query untuk mendapatkan data per bulan
$result_per_bulan = query("SELECT CAST(strftime('%m', tanggal_pengukuran) AS INTEGER) as bulan, COUNT(*) as total FROM pengukuran GROUP BY bulan ORDER BY bulan");
$data_per_bulan = fetchAll($result_per_bulan);
?>

<h1 class="mb-4">Laporan</h1>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Total Balita</h5>
                <p class="card-text"><?= $total_balita ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Total Pengukuran</h5>
                <p class="card-text"><?= $total_pengukuran ?></p>
            </div>
        </div>
    </div>
</div>

<h2 class="mb-3">Status Gizi</h2>
<table class="table">
    <thead>
        <tr>
            <th>Status Gizi</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($status_gizi as $sg): ?>
        <tr>
            <td><?= $sg['status_gizi'] ?></td>
            <td><?= $sg['total'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2 class="mb-3">Data Pengukuran per Bulan</h2>
<table class="table">
    <thead>
        <tr>
            <th>Bulan</th>
            <th>Jumlah Pengukuran</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data_per_bulan as $dpb): ?>
        <tr>
            <td><?= date('F', mktime(0, 0, 0, $dpb['bulan'], 1)) ?></td>
            <td><?= $dpb['total'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
include 'includes/footer.php';
?>