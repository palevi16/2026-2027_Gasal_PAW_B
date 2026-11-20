<?php
include_once "midlewire.php";
include_once "koneksi.php";
cek_owner();

$id = $_GET['id'] ?? "";
$kode = $nama = $harga = $stok = $supplier_id = "";
$error = "";

// kalau ada id berarti mode edit, ambil data lama
if ($id != "") {
  $stmt = mysqli_prepare($connect, "SELECT * FROM barang WHERE id = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

  if (!$data) {
    header("Location: barang.php");
    exit();
  }
  $kode = $data['kode_barang'];
  $nama = $data['nama_barang'];
  $harga = $data['harga'];
  $stok = $data['stok'];
  $supplier_id = $data['supplier_id'];
}

if (isset($_POST['simpan'])) {
  $kode = trim($_POST['kode_barang']);
  $nama = trim($_POST['nama_barang']);
  $harga = trim($_POST['harga']);
  $stok = trim($_POST['stok']);
  $supplier_id = $_POST['supplier_id'];

  if ($kode == "" || $nama == "" || $harga == "" || $stok == "" || $supplier_id == "") {
    $error = "Semua field harus diisi!";
  } elseif (!is_numeric($harga) || !is_numeric($stok)) {
    $error = "Harga dan stok harus berupa angka!";
  } else {
    $id_cek = ($id == "") ? 0 : (int) $id;
    $stmt = mysqli_prepare($connect, "SELECT id FROM barang WHERE kode_barang = ? AND id != ?");
    mysqli_stmt_bind_param($stmt, "si", $kode, $id_cek);
    mysqli_stmt_execute($stmt);
    $kembar = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if ($kembar) {
      $error = "Kode barang sudah dipakai!";
    } else {
      $harga = (int) $harga;
      $stok = (int) $stok;
      $supplier_id = (int) $supplier_id;

      if ($id == "") {
        $stmt = mysqli_prepare($connect, "INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssiii", $kode, $nama, $harga, $stok, $supplier_id);
      } else {
        $stmt = mysqli_prepare($connect, "UPDATE barang SET kode_barang=?, nama_barang=?, harga=?, stok=?, supplier_id=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "ssiiii", $kode, $nama, $harga, $stok, $supplier_id, $id_cek);
      }
      mysqli_stmt_execute($stmt);
      header("Location: barang.php");
      exit();
    }
  }
}

$supplier = mysqli_query($connect, "SELECT id, nama FROM supplier ORDER BY nama");
$judul = ($id == "") ? "Tambah Data Barang" : "Edit Data Barang";
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
      <label>Kode Barang</label>
      <input type="text" name="kode_barang" maxlength="10" value="<?= htmlspecialchars($kode) ?>">
    </div>
    <div class="form-baris">
      <label>Nama Barang</label>
      <input type="text" name="nama_barang" maxlength="100" value="<?= htmlspecialchars($nama) ?>">
    </div>
    <div class="form-baris">
      <label>Harga</label>
      <input type="number" name="harga" min="0" value="<?= htmlspecialchars($harga) ?>">
    </div>
    <div class="form-baris">
      <label>Stok</label>
      <input type="number" name="stok" min="0" value="<?= htmlspecialchars($stok) ?>">
    </div>
    <div class="form-baris">
      <label>Supplier</label>
      <select name="supplier_id">
        <option value="">-- Pilih Supplier --</option>
        <?php
        while ($row = mysqli_fetch_assoc($supplier)) {
          $pilih = ($row['id'] == $supplier_id) ? "selected" : "";
          echo "<option value='" . $row['id'] . "' $pilih>" . htmlspecialchars($row['nama']) . "</option>";
        }
        ?>
      </select>
    </div>
    <div class="form-tombol">
      <button type="submit" name="simpan" class="btn btn-simpan">Simpan</button>
      <a href="barang.php" class="btn btn-batal">Batal</a>
    </div>
  </form>

</div>
</body>
</html>
