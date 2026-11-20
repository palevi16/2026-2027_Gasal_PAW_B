<?php
include_once "midlewire.php";
include_once "koneksi.php";
cek_owner();

$id = (int) $_GET['id'];

// user yang sedang login tidak boleh menghapus dirinya sendiri
if ($id == $_SESSION['id_user']) {
  echo "<p>Anda tidak dapat menghapus akun yang sedang digunakan!</p>";
  echo "<a href='user.php'>Kembali</a>";
} else {
  mysqli_query($connect, "DELETE FROM user WHERE id_user=$id");
  header("Location: user.php");
  exit();
}
?>
