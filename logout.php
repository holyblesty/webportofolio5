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
session_set_cookie_params(0);
session_start();
session_destroy();
header("Location: index.php");
exit;
?>
