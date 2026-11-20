<?php
include_once "midlewire.php";
include_once "koneksi.php";
cek_owner();

$result = mysqli_query($connect, "SELECT * FROM supplier ORDER BY id");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Data Master Supplier</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "navbar.php"; ?>
<div class="container">

  <div class="header">
    <h2>Data Master Supplier</h2>
    <a href="supplier_form.php" class="btn btn-tambah">Tambah Data</a>
  </div>

  <table>
    <tr>
      <th width="50">No</th>
      <th>Nama</th>
      <th>Telp</th>
      <th>Alamat</th>
      <th width="130">Tindakan</th>
    </tr>
    <?php
    $no = 1;
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<tr>";
      echo "<td>" . $no . "</td>";
      echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
      echo "<td>" . htmlspecialchars($row['telp']) . "</td>";
      echo "<td>" . htmlspecialchars($row['alamat']) . "</td>";
      echo "<td>";
      echo "<a href='supplier_form.php?id=" . $row['id'] . "' class='btn btn-kecil btn-edit'>Edit</a> ";
      echo "<a href='hapus_supplier.php?id=" . $row['id'] . "' class='btn btn-kecil btn-hapus' onclick=\"return confirm('Anda yakin akan menghapus supplier ini?')\">Hapus</a>";
      echo "</td>";
      echo "</tr>";
      $no = $no + 1;
    }
    ?>
  </table>

</div>
</body>
</html>
