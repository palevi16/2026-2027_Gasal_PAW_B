<?php
include_once "midlewire.php";
include_once "koneksi.php";
cek_owner();

$id = $_GET['id'] ?? "";
$nama = $username = $level = "";
$error = "";

if ($id != "") {
  $stmt = mysqli_prepare($connect, "SELECT * FROM user WHERE id_user = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

  if (!$data) {
    header("Location: user.php");
    exit();
  }
  $nama = $data['name'];
  $username = $data['username'];
  $level = $data['level'];
}

if (isset($_POST['simpan'])) {
  $nama = trim($_POST['name']);
  $username = trim($_POST['username']);
  $password = $_POST['password'];
  $level = $_POST['level'];

  if ($nama == "" || $username == "" || $level == "") {
    $error = "Nama, username, dan level harus diisi!";
  } elseif ($id == "" && $password == "") {
    $error = "Password harus diisi!";
  } else {
    $id_cek = ($id == "") ? 0 : (int) $id;
    $level = (int) $level;

    // username tidak boleh kembar
    $stmt = mysqli_prepare($connect, "SELECT id_user FROM user WHERE username = ? AND id_user != ?");
    mysqli_stmt_bind_param($stmt, "si", $username, $id_cek);
    mysqli_stmt_execute($stmt);
    $kembar = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if ($kembar) {
      $error = "Username sudah dipakai!";
    } else {
      if ($id == "") {
        $pass_md5 = md5($password);
        $stmt = mysqli_prepare($connect, "INSERT INTO user (name, username, password, level) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssi", $nama, $username, $pass_md5, $level);
      } elseif ($password == "") {
        // password dikosongkan = tidak diganti
        $stmt = mysqli_prepare($connect, "UPDATE user SET name=?, username=?, level=? WHERE id_user=?");
        mysqli_stmt_bind_param($stmt, "ssii", $nama, $username, $level, $id_cek);
      } else {
        $pass_md5 = md5($password);
        $stmt = mysqli_prepare($connect, "UPDATE user SET name=?, username=?, password=?, level=? WHERE id_user=?");
        mysqli_stmt_bind_param($stmt, "sssii", $nama, $username, $pass_md5, $level, $id_cek);
      }
      mysqli_stmt_execute($stmt);

      // kalau yang diedit adalah akun yang sedang login, session ikut diperbarui
      if ($id_cek == $_SESSION['id_user']) {
        $_SESSION['name'] = $nama;
        $_SESSION['username'] = $username;
        $_SESSION['level'] = $level;
      }

      header("Location: user.php");
      exit();
    }
  }
}

$judul = ($id == "") ? "Tambah Data User" : "Edit Data User";
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
      <input type="text" name="name" maxlength="100" value="<?= htmlspecialchars($nama) ?>">
    </div>
    <div class="form-baris">
      <label>Username</label>
      <input type="text" name="username" maxlength="50" value="<?= htmlspecialchars($username) ?>">
    </div>
    <div class="form-baris">
      <label>Password</label>
      <input type="password" name="password" placeholder="<?= ($id != "") ? 'kosongkan jika tidak diganti' : '' ?>">
    </div>
    <div class="form-baris">
      <label>Level</label>
      <select name="level">
        <option value="">-- Pilih Level --</option>
        <option value="1" <?= ($level == 1) ? "selected" : "" ?>>1 - Owner</option>
        <option value="2" <?= ($level == 2) ? "selected" : "" ?>>2 - Kasir</option>
      </select>
    </div>
    <div class="form-tombol">
      <button type="submit" name="simpan" class="btn btn-simpan">Simpan</button>
      <a href="user.php" class="btn btn-batal">Batal</a>
    </div>
  </form>

</div>
</body>
</html>
