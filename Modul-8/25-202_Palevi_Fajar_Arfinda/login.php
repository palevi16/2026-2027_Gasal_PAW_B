<?php
session_start();
include_once "koneksi.php";

// kalau sudah login, tidak boleh buka halaman login lagi
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header("Location: home.php");
    exit();
}

$error = "";

if (isset($_POST['tombol_login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // validasi: tidak boleh kosong
    if ($username == "" || $password == "") {
        $error = "Username dan password harus diisi!";
    } else {
        $stmt = mysqli_prepare($connect, "SELECT * FROM user WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        if (!$row) {
            $error = "Username tidak ditemukan!";
        } elseif (md5($password) != $row['password']) {
            $error = "Password salah!";
        } else {
            // login berhasil -> set session
            session_regenerate_id(true);
            $_SESSION['login'] = true;
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['level'] = $row['level'];
            header("Location: home.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Halaman Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-box">
    <h2>Sistem Penjualan</h2>
    <h3>Silakan Login</h3>

    <?php if ($error != "") { ?>
        <p style="color: red;"><?= $error ?></p>
    <?php } ?>

    <form action="" method="post">
        <div class="form-login">
            <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
        </div>
        <div class="form-login">
            <input type="password" name="password" placeholder="Password">
        </div>
        <button type="submit" name="tombol_login" class="btn btn-detail">Login</button>
    </form>
</div>
</body>
</html>
