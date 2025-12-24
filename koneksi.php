<?php
// koneksi.php
$host = "localhost";
$user = "root";
$pass = "";$db   = "proyek"; // nama database

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Gagal koneksi database: " . mysqli_connect_error());
}
?>
