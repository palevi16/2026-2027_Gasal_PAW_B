<?php
include_once "midlewire.php";
include_once "koneksi.php";
cek_owner();

$id = $_GET['id'];

// pelanggan yang sudah punya transaksi tidak boleh dihapus
$stmt = mysqli_prepare($connect, "SELECT id FROM transaksi WHERE pelanggan_id = ?");
mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);
$dipakai = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if ($dipakai) {
  echo "<p>Pelanggan tidak dapat dihapus karena masih memiliki transaksi!</p>";
  echo "<a href='pelanggan.php'>Kembali</a>";
} else {
  $stmt = mysqli_prepare($connect, "DELETE FROM pelanggan WHERE id = ?");
  mysqli_stmt_bind_param($stmt, "s", $id);
  mysqli_stmt_execute($stmt);
  header("Location: pelanggan.php");
  exit();
}
?>
