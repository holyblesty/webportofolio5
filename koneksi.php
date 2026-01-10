<!-- 
=========================================================
  Nama File   : aplikasi-pengumuman-akademik-online.html
  Deskripsi   : Halaman portofolio Projek PBL
                Sistem Aplikasi Pengumuman Akademik Online
  Pembuat     : Jesina HolyBlesty Simatupang (3312511017)
              : Vivian Sarah Diva Alisianoi (3312511018)
  Tanggal     : 19 Oktober 2025
=========================================================
-->

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
