<?php
/* 
=========================================================
  Nama File   : koneksi.php
  Deskripsi   : File koneksi database aplikasi Web Portofolio PBL
  Pembuat     : Jesina HolyBlesty Simatupang (3312511017)
              : Vivian Sarah Diva Alisianoi (3312511018)
  Tanggal     : 19 Oktober 2025
=========================================================
*/

// konfigurasi database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "proyek";

// proses koneksi database
$koneksi = mysqli_connect($host, $user, $pass, $db);

// pengecekan koneksi
if (!$koneksi) {
    die("Gagal koneksi database: " . mysqli_connect_error());
}
?>