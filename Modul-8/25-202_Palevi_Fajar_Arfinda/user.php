<?php
include_once "midlewire.php";
include_once "koneksi.php";
cek_owner();

$result = mysqli_query($connect, "SELECT * FROM user ORDER BY id_user");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Data Master User</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "navbar.php"; ?>
<div class="container">

  <div class="header">
    <h2>Data Master User</h2>
    <a href="user_form.php" class="btn btn-tambah">Tambah Data</a>
  </div>

  <table>
    <tr>
      <th width="50">No</th>
      <th>Nama</th>
      <th>Username</th>
      <th>Level</th>
      <th width="130">Tindakan</th>
    </tr>
    <?php
    $no = 1;
    while ($row = mysqli_fetch_assoc($result)) {
      $nama_level = ($row['level'] == 1) ? "Owner" : "Kasir";
      echo "<tr>";
      echo "<td>" . $no . "</td>";
      echo "<td>" . htmlspecialchars($row['name']) . "</td>";
      echo "<td>" . htmlspecialchars($row['username']) . "</td>";
      echo "<td>" . $nama_level . "</td>";
      echo "<td>";
      echo "<a href='user_form.php?id=" . $row['id_user'] . "' class='btn btn-kecil btn-edit'>Edit</a> ";
      echo "<a href='hapus_user.php?id=" . $row['id_user'] . "' class='btn btn-kecil btn-hapus' onclick=\"return confirm('Anda yakin akan menghapus user ini?')\">Hapus</a>";
      echo "</td>";
      echo "</tr>";
      $no = $no + 1;
    }
    ?>
  </table>

</div>
</body>
</html>
