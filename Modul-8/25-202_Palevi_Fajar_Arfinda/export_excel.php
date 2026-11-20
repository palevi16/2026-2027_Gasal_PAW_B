<?php
// cek login dulu sebelum kirim header download
include_once "midlewire.php";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_excel.xls");

$export = true;
require "laporan.php";
?>
