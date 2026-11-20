<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "penjualan";

$connect = mysqli_connect($host, $username, $password, $dbname);

if (!$connect) {
    die("GAGAL KONEKSI KE DATABASE");
}

// beberapa file lama pakai $conn, jadi disamakan saja dengan $connect
$conn = $connect;
?>
