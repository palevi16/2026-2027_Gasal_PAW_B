<?php
include_once "midlewire.php";
include_once "koneksi.php";
cek_owner();

// id pelanggan berupa kode (contoh PLG001), diisi manual saat tambah
$id = $_GET['id'] ?? "";
$kode = $nama = $jk = $telp = $alamat = "";
$error = "";

if ($id != "") {
  $stmt = mysqli_prepare($connect, "SELECT * FROM pelanggan WHERE id = ?");
  mysqli_stmt_bind_param($stmt, "s", $id);
  mysqli_stmt_execute($stmt);
  $data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

  if (!$data) {
    header("Location: pelanggan.php");
    exit();
  }
  $kode = $data['id'];
  $nama = $data['nama'];
  $jk = $data['jenis_kelamin'];
  $telp = $data['telp'];
  $alamat = $data['alamat'];
}

if (isset($_POST['simpan'])) {
  if ($id == "") {
    $kode = trim($_POST['id']);
  }
  $nama = trim($_POST['nama']);
  $jk = $_POST['jenis_kelamin'] ?? "";
  $telp = trim($_POST['telp']);
  $alamat = trim($_POST['alamat']);

  if ($kode == "" || $nama == "" || $jk == "" || $telp == "" || $alamat == "") {
    $error = "Semua field harus diisi!";
  } elseif (!is_numeric($telp)) {
    $error = "Telp harus berupa angka!";
  } else {
    $kembar = false;
    if ($id == "") {
      // cek id pelanggan sudah dipakai atau belum
      $stmt = mysqli_prepare($connect, "SELECT id FROM pelanggan WHERE id = ?");
      mysqli_stmt_bind_param($stmt, "s", $kode);
      mysqli_stmt_execute($stmt);
      $kembar = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    }

    if ($kembar) {
      $error = "ID pelanggan sudah dipakai!";
    } else {
      if ($id == "") {
        $stmt = mysqli_prepare($connect, "INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssss", $kode, $nama, $jk, $telp, $alamat);
      } else {
        $stmt = mysqli_prepare($connect, "UPDATE pelanggan SET nama=?, jenis_kelamin=?, telp=?, alamat=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "sssss", $nama, $jk, $telp, $alamat, $id);
      }
      mysqli_stmt_execute($stmt);
      header("Location: pelanggan.php");
      exit();
    }
  }
}

$judul = ($id == "") ? "Tambah Data Pelanggan" : "Edit Data Pelanggan";
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
      <label>ID Pelanggan</label>
      <input type="text" name="id" maxlength="20" value="<?= htmlspecialchars($kode) ?>" <?= ($id != "") ? "readonly" : "" ?>>
    </div>
    <div class="form-baris">
      <label>Nama</label>
      <input type="text" name="nama" maxlength="100" value="<?= htmlspecialchars($nama) ?>">
    </div>
    <div class="form-baris">
      <label>Jenis Kelamin</label>
      <select name="jenis_kelamin">
        <option value="">-- Pilih --</option>
        <option value="L" <?= ($jk == 'L') ? "selected" : "" ?>>Laki-laki</option>
        <option value="P" <?= ($jk == 'P') ? "selected" : "" ?>>Perempuan</option>
      </select>
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
      <a href="pelanggan.php" class="btn btn-batal">Batal</a>
    </div>
  </form>

</div>
</body>
</html>
