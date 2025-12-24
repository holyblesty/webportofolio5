<?php
// koneksi.php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "proyek"; // ← sesuai nama DATABASE

$koneksi = mysqli_connect($host, $user, $pass, $db);
if ($koneksi) {
  echo "Gagal konek: " . die(mysqli_error($koneksi));
}
?>

