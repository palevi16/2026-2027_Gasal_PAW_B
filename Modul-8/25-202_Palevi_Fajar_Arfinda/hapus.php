<?php
include_once "midlewire.php";
include_once "koneksi.php";

$id = (int) $_GET["id"];

$query = "DELETE FROM transaksi WHERE id=$id";

if (mysqli_query($conn, $query)) {
  header("Location: index.php");
} else {
  echo "Error: " . mysqli_error($conn);
}
?>
