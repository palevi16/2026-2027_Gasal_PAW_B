<?php // navbar dipakai di semua halaman, menu menyesuaikan level user ?>
<div class="navbar no-print">
  <span class="brand">Sistem Penjualan</span>
  <a href="home.php">Home</a>

  <?php if ($_SESSION['level'] == 1) { ?>
  <div class="dropdown">
    <a href="#">Data Master &#9662;</a>
    <div class="dropdown-isi">
      <a href="barang.php">Data Barang</a>
      <a href="supplier.php">Data Supplier</a>
      <a href="pelanggan.php">Data Pelanggan</a>
      <a href="user.php">Data User</a>
    </div>
  </div>
  <?php } ?>

  <a href="index.php">Transaksi</a>
  <a href="laporan.php">Laporan</a>

  <div class="dropdown kanan-nav">
    <a href="#"><?= htmlspecialchars($_SESSION['name']) ?> &#9662;</a>
    <div class="dropdown-isi">
      <a href="logout.php">Logout</a>
    </div>
  </div>
</div>
