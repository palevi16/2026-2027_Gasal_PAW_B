<?php
include_once "midlewire.php";
include_once "koneksi.php";

$nama_level = ($_SESSION['level'] == 1) ? "Owner" : "Kasir";
?>
<!DOCTYPE html>
<html>
<head>
  <title>Home</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "navbar.php"; ?>
<div class="container">
  <div class="header">
    <h2>Home</h2>
  </div>
  <p>Selamat datang, <b><?= htmlspecialchars($_SESSION['name']) ?></b>!</p>
  <p>Anda login sebagai <b><?= $nama_level ?></b>.</p>
</div>
</body>
</html>
