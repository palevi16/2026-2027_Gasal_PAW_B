<?php
include_once "midlewire.php";
include_once "koneksi.php";
cek_owner();

$id = (int) $_GET['id'];

// supplier yang masih dipakai barang tidak boleh dihapus
$cek = mysqli_query($connect, "SELECT * FROM barang WHERE supplier_id=$id");
$dipakai = mysqli_fetch_assoc($cek);

if ($dipakai) {
  echo "<p>Supplier tidak dapat dihapus karena masih digunakan pada data barang!</p>";
  echo "<a href='supplier.php'>Kembali</a>";
} else {
  mysqli_query($connect, "DELETE FROM supplier WHERE id=$id");
  header("Location: supplier.php");
  exit();
}
?>
