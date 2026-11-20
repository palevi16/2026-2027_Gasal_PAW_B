<?php
include_once "midlewire.php";
include_once "koneksi.php";
cek_owner();

$id = $_GET['id'] ?? "";
$nama = $telp = $alamat = "";
$error = "";

if ($id != "") {
  $stmt = mysqli_prepare($connect, "SELECT * FROM supplier WHERE id = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

  if (!$data) {
    header("Location: supplier.php");
    exit();
  }
  $nama = $data['nama'];
  $telp = $data['telp'];
  $alamat = $data['alamat'];
}

if (isset($_POST['simpan'])) {
  $nama = trim($_POST['nama']);
  $telp = trim($_POST['telp']);
  $alamat = trim($_POST['alamat']);

  if ($nama == "" || $telp == "" || $alamat == "") {
    $error = "Semua field harus diisi!";
  } elseif (!is_numeric($telp)) {
    $error = "Telp harus berupa angka!";
  } else {
    if ($id == "") {
      $stmt = mysqli_prepare($connect, "INSERT INTO supplier (nama, telp, alamat) VALUES (?, ?, ?)");
      mysqli_stmt_bind_param($stmt, "sss", $nama, $telp, $alamat);
    } else {
      $id_int = (int) $id;
      $stmt = mysqli_prepare($connect, "UPDATE supplier SET nama=?, telp=?, alamat=? WHERE id=?");
      mysqli_stmt_bind_param($stmt, "sssi", $nama, $telp, $alamat, $id_int);
    }
    mysqli_stmt_execute($stmt);
    header("Location: supplier.php");
    exit();
  }
}

$judul = ($id == "") ? "Tambah Data Supplier" : "Edit Data Supplier";
?>
<!DOCTYPE html>
<html>
<head>
  <title><?= $judul ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "navbar.php"; ?>
<div class="container">

  <div class="header">
    <h2><?= $judul ?></h2>
  </div>

  <?php if ($error != "") { ?>
    <p class="error"><?= $error ?></p>
  <?php } ?>

  <form method="post">
    <div class="form-baris">
      <label>Nama</label>
      <input type="text" name="nama" maxlength="100" value="<?= htmlspecialchars($nama) ?>">
    </div>
    <div class="form-baris">
      <label>Telp</label>
      <input type="text" name="telp" maxlength="12" value="<?= htmlspecialchars($telp) ?>">
    </div>
    <div class="form-baris">
      <label>Alamat</label>
      <input type="text" name="alamat" value="<?= htmlspecialchars($alamat) ?>">
    </div>
    <div class="form-tombol">
      <button type="submit" name="simpan" class="btn btn-simpan">Simpan</button>
      <a href="supplier.php" class="btn btn-batal">Batal</a>
    </div>
  </form>

</div>
</body>
</html>
