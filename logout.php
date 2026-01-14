<!-- 
=========================================================
  Nama File   : logout.php
  Deskripsi   : Menampilakan halaman logout pada bagian logout.php
  Pembuat     : Jesina HolyBlesty Simatupang (3312511017)
              : Vivian Sarah Diva Alisianoi (3312511018)
  Tanggal     : 19 Oktober 2025
=========================================================
-->

<?php
session_start();          // Mulai session
unset($_SESSION['username']); // Hapus session tertentu
session_destroy();        // Hapus semua session
setcookie(session_name(), '', time() - 3600, '/'); // Hapus cookie session
header("Location: index.php"); // Redirect ke login
exit;
?>
