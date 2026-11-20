<?php
include_once "midlewire.php";
include_once "koneksi.php";
cek_owner();

$id = (int) $_GET['id'];

// barang yang sudah dipakai di transaksi tidak boleh dihapus
$cek = mysqli_query($connect, "SELECT * FROM transaksi_detail WHERE barang_id=$id");
$dipakai = mysqli_fetch_assoc($cek);

if ($dipakai) {
  echo "<p>Barang tidak dapat dihapus karena masih digunakan pada transaksi!</p>";
  echo "<a href='barang.php'>Kembali</a>";
} else {
  mysqli_query($connect, "DELETE FROM barang WHERE id=$id");
  header("Location: barang.php");
  exit();
}
?>
