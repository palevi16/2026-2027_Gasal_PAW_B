<?php
session_start();

// hapus semua session lalu kembali ke login
session_unset();
session_destroy();

header("Location: login.php");
exit();
?>
