<?php
// dipanggil di paling atas setiap halaman yang harus login dulu
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// kalau belum login, balikkan ke halaman login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit();
}

// dipakai di halaman Data Master: hanya owner (level 1) yang boleh masuk
function cek_owner() {
    if ($_SESSION['level'] != 1) {
        header("Location: home.php");
        exit();
    }
}
?>
