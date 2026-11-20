<?php
include_once "midlewire.php";
include_once "koneksi.php";

// $export diisi true oleh export_excel.php (tanpa navbar, filter, dan grafik)
$export = $export ?? false;

$labels = []; $data_total = []; $rekap = [];
$total_pendapatan = 0; $total_pelanggan = 0;

if (!empty($_GET['mulai']) && !empty($_GET['selesai'])) {
  $mulai = mysqli_real_escape_string($connect, $_GET['mulai']);
  $selesai = mysqli_real_escape_string($connect, $_GET['selesai']);

  // Query agregasi total pendapatan harian
  $q = "SELECT DATE(waktu_transaksi) AS tgl, SUM(total) AS total_harian
        FROM transaksi WHERE waktu_transaksi BETWEEN '$mulai' AND '$selesai'
        GROUP BY DATE(waktu_transaksi) ORDER BY tgl ASC";
  $res = mysqli_query($connect, $q);
  while ($r = mysqli_fetch_assoc($res)) {
    $labels[] = $r['tgl'];
    $data_total[] = $r['total_harian'];
    $rekap[] = $r;
    $total_pendapatan += $r['total_harian'];
  }

  // Query jumlah pelanggan unik
  $qp = "SELECT COUNT(DISTINCT pelanggan_id) AS jml FROM transaksi
         WHERE waktu_transaksi BETWEEN '$mulai' AND '$selesai'";
  $rp = mysqli_fetch_assoc(mysqli_query($connect, $qp));
  $total_pelanggan = $rp['jml'] ?? 0;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Laporan Penjualan dan Grafik</title>
<link rel="stylesheet" href="style.css">
<?php if (!$export) { ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php } ?>
</head>
<body>
<?php if (!$export) { include "navbar.php"; } ?>
<div class="container">

  <?php if (!$export) { ?>
  <div class="no-print">
    <h2>Filter Laporan Penjualan</h2>
    <form method="GET">
      <input type="date" name="mulai" value="<?= htmlspecialchars($_GET['mulai'] ?? '') ?>" required>
      Sampai: <input type="date" name="selesai" value="<?= htmlspecialchars($_GET['selesai'] ?? '') ?>" required><br><br>
      <button type="submit">Tampilkan</button>
    </form>
    <button onclick="window.print()">Cetak</button>
    <a href="export_excel.php?mulai=<?= urlencode($_GET['mulai'] ?? '') ?>&selesai=<?= urlencode($_GET['selesai'] ?? '') ?>"><button>Excel</button></a>
  </div>
  <?php } ?>

  <?php if (!empty($labels)): ?>

  <?php if (!$export) { ?>
  <h2>Grafik Penjualan</h2>
  <div style="width: 600px; height: 300px;"><canvas id="myChart"></canvas></div>

  <script>
    const ctx = document.getElementById('myChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: <?= json_encode($labels); ?>,
        datasets: [{ label: 'Total Penjualan', data: <?= json_encode($data_total); ?> }]
      }
    });
  </script>
  <?php } ?>

  <h2>Rekapitulasi</h2>
  <table border="1">
    <tr><th>No</th><th>Total</th><th>Tanggal</th></tr>

    <?php $no = 1; foreach ($rekap as $row): ?>
    <tr>
      <td><?= $no++; ?></td>
      <td>Rp. <?= number_format($row['total_harian'], 0, ',', '.'); ?></td>
      <td><?= $row['tgl']; ?></td>
    </tr>
    <?php endforeach; ?>

  </table>

  <p><b>Jumlah Pelanggan:</b> <?= $total_pelanggan; ?> Orang |
  <b>Jumlah Pendapatan:</b> Rp. <?= number_format($total_pendapatan, 0, ',', '.'); ?></p>
  <?php endif; ?>

</div>
</body>
</html>
