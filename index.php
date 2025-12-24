<?php
session_start();

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'dosen') {
        header("Location: bagian_dosen/dashboard_dsn.php");
        exit;
    } elseif ($_SESSION['role'] === 'mahasiswa') {
        header("Location: bagian_mahasiswa/dashboard_mhs.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Web Portofolio Projek PBL</title>

 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      background:white; font-family:'Poppins',sans-serif; color:#000;
      padding-top:70px; overflow-x:hidden;
    }

    /* ==== NAVBAR ==== */
    .navbar {background:#FD5DA8; transition:.3s;}
    .navbar.transparent {background:rgba(253,93,168,.3);}
    .navbar .nav-link.active {
      background:rgba(208,65,144,.3);
      border-radius:10px; color:#e11584 !important;
    }

    /* ==== DROPDOWN PINK ==== */
    .dropdown-menu {
      width: 400px;
      background: rgba(254,197,229,0.85);
      border-radius: 12px;
      padding: 10px;
      border: none;
      backdrop-filter: blur(6px);
    }
    .dropdown-item {
      font-weight: 600; color: #d63384;
      border-radius: 12px; margin-bottom: 10px;
      transition:.25s;
      text-align:center;
    }
    .dropdown-item:hover {
      background: rgba(222,100,169,0.7);
      color:white;
    }

    /* ==== FOTO BULAT ==== */
    .circle-img {
      width:500px; height:500px; border-radius:70%;
      background:url(FOTOPEMBUATWEBSITE.JPG.jpeg) center/cover;
      border:5px solid #fff;
    }

    /* ==== KOTAK KONTEN ==== */
    .content-box {
      background:#fec5e5; padding:30px; border-radius:12px;
      box-shadow:0 3px 10px rgba(0,0,0,.1);
      transition:.3s; margin-bottom:20px;
    }
    .content-box:hover {transform:translateY(-4px);}

    /* ==== PARAGRAF ==== */
    .paragraph-section p {
      text-align:justify; text-indent:40px; line-height:1.8; font-size:16px;
    }

    /* ==== CAROUSEL ==== */
    #carouselPBL img {height:380px; object-fit:cover;}
    .custom-caption {
      background:rgba(255,182,193,.8);
      border-radius:12px; padding:6px 12px; color:#000;
    }

    /* ==== QUOTE ==== */
    .quote {
      background:#fec5e5; border-radius:12px;
      text-align:center; padding:18px;
      font-style:italic; color:#e11584;
      animation:fadeIn 1s ease;
    }
    .quote::after {content:"🌸 🌷 🌸"; display:block; margin-top:6px;}

    @keyframes fadeIn {from{opacity:.2;} to{opacity:1;}}
    .fade-up {opacity:0; transform:translateY(30px); transition:.7s;}
    .fade-up.show {opacity:1; transform:translateY(0);}

    /* ==== LOGIN ==== */
    .modal-header {background:#e11584; color:#fff;}
    .btn-pink {background:#e11584; color:white; border:none; transition:.25s;}
    .btn-pink:hover {background:#d10b73;}

    input.form-control {
      border:1.5px solid #ffb6c1; border-radius:10px;
    }
    input.form-control:focus {
      box-shadow:0 0 5px rgba(255,105,180,.6);
      border-color:#ff69b4;
    }

    /* MOBILE */
    @media(max-width:768px){
      .circle-img{width:300px;height:300px;}
      #carouselPBL img {height:230px;}
      .dropdown-menu{width:90vw;}
    }
  </style>
</head>
<body>

<!-- ========================= NAVBAR ========================= -->
<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container">
    <a class="navbar-brand fw-bold text-white">WEB PORTOFOLIO PROJEK PBL</a>

    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="nav">
      <ul class="navbar-nav">

        <li class="nav-item">
          <a class="nav-link active text-white" href="index.php">BERANDA</a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" data-bs-toggle="dropdown">KUMPULAN PROJEK PBL</a>

          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="buku-tamu-tata-usaha.html">BUKU TAMU TATA USAHA (TU)</a></li>
            <li><a class="dropdown-item" href="pengelolaan-rapat.html">PENGELOLAAN RAPAT</a></li>
            <li><a class="dropdown-item" href="pencatatan-notulen.html">PENCATATAN NOTULEN</a></li>
            <li><a class="dropdown-item" href="pengelolaan-surat-peringatan-sp.html">PENGELOLAAN SURAT PERINGATAN SP</a></li>
            <li><a class="dropdown-item" href="jadwal-perkuliahan-mahasiswa-pribadi.html">JADWAL PERKULIAHAN MAHASISWA</a></li>
            <li><a class="dropdown-item" href="web-informasi-event-kampus.html">WEB INFORMASI EVENT KAMPUS</a></li>
            <li><a class="dropdown-item" href="aplikasi-pengumuman-akademik-online.html">APLIKASI PENGUMUMAN AKADEMIK ONLINE</a></li>
          </ul>
        </li>

        <li class="nav-item ms-3">
          <button id="loginBtn" class="btn btn-pink px-3 fw-semibold">LOGIN</button>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- ========================= MODAL LOGIN PILIHAN ========================= -->
<div class="modal fade" id="loginModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">
      <div class="modal-header rounded-top-4">
        <h5 class="modal-title w-100 text-center">Pilih Jenis Login</h5>
      </div>

      <div class="modal-body text-center p-4">
        <p class="mb-3">Silakan pilih jenis login 💖</p>

        <button id="btnLoginDosen" class="btn btn-pink w-100 mb-2 py-2">Login Dosen</button>
        <button id="btnLoginMahasiswa" class="btn btn-pink w-100 py-2">Login Mahasiswa</button>
      </div>
    </div>
  </div>
</div>

<!-- ========================= MODAL LOGIN FORM ========================= -->
<div class="modal fade" id="formLoginModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">
      <div class="modal-header rounded-top-4" id="loginHeader">
        <h5 class="modal-title w-100 text-center" id="formLoginModalLabel">Login</h5>
      </div>

      <div class="modal-body p-4">
        <form method="POST" action="login_proses.php" id="popupLoginForm">
          <input type="hidden" name="role" id="role">

          <label class="fw-semibold">Username</label>
          <input type="text" name="username" class="form-control mb-3" required>

          <label class="fw-semibold">Password</label>
          <input type="password" name="password" class="form-control mb-3" required>

          <button class="btn btn-pink w-100 py-2 fw-semibold">Login 💖</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- ========================= KONTEN UTAMA ========================= -->
<div class="container mt-4">
  <div class="row">

    <!-- KIRI -->
    <div class="col-md-5 text-center">
      <div class="circle-img mx-auto mb-4"></div>

      <div class="content-box">
        <p class="fw-bold fs-3">PEMBUATAN WEBSITE OLEH :</p>
        <p class="fw-bold fs-4">❀ JESINA HOLYBLESTY SIMATUPANG</p>
        <p class="fw-bold fs-4">❀ VIVIAN SARAH DIVA ALISIANOI</p>
      </div>

      <div class="content-box">
        <p class="fw-bold fs-3">HASIL PROJEK PBL<br>D3 - TEKNIK INFORMATIKA</p>
        <p class="fw-bold fs-3">MALAM A</p>
        <p class="fw-bold fs-3">POLITEKNIK NEGERI BATAM</p>
      </div>

      <div class="content-box quote">
        "Setiap proyek kecil membawa langkah besar menuju impian."
      </div>
    </div>

    <!-- KANAN -->
    <div class="col-md-7">

      <!-- PARAGRAF 1 -->
      <div class="content-box paragraph-section">
        <h5 class="text-center text-danger fw-bold">PEMBUKA</h5>

        <p>
          Puji dan syukur penulis panjatkan kepada Tuhan Yang Maha Esa, karena atas rahmat dan berkat-Nya, website ini dapat diselesaikan tepat waktu.
        </p>
        <p>
          Tidak lupa, penulis juga menyampaikan terima kasih kepada Manpro kami, Bapak <b>Dwi Ely Kurniawan</b>,  
          Ketua Jurusan Teknik Informatika, Bapak <b>Ahmad Hamim Thohari</b>, serta KPS Teknik Informatika,  
          Ibu <b>Yeni Rokhayati</b>, atas segala dukungan dan bimbingan yang telah diberikan selama proses pembuatan website ini.
        </p>
      </div>

      <!-- PARAGRAF 2 -->
      <div class="content-box paragraph-section">
        <h5 class="text-center text-danger fw-bold">PENJELASAN TENTANG PROJEK PBL</h5>

        <p>
          Proyek ini kami buat sebagai bagian dari penugasan mata kuliah, yang tentunya bertujuan untuk mengasah keterampilan dan pengetahuan dasar kami dalam mengembangkan sebuah website.
        </p>
        <p>
          Selain itu, website ini dibuat agar penulis dapat menampilkan hasil karya dari rekan-rekan sekelas <b>IF A Malam</b>,  
          yang diharapkan dapat menjadi referensi dan inspirasi bagi para pengunjung yang datang untuk melihat.
        </p>
      </div>

      <!-- PARAGRAF 3 -->
      <div class="content-box paragraph-section">
        <h5 class="text-center text-danger fw-bold">PENUTUP</h5>

        <p>
          Terima kasih penulis ucapkan kepada seluruh pengunjung website kami yang telah meluangkan waktu untuk melihat hasil karya ini.
        </p>
      </div>

      <!-- CAROUSEL -->
      <div id="carouselPBL" class="carousel slide mb-4" data-bs-ride="carousel">
        <div class="carousel-inner rounded-4 shadow-lg">
          <div class="carousel-item active">
            <img src="dokumentasi-pbl.jpeg" class="d-block w-100">
            <div class="carousel-caption custom-caption">
              <h5>DOKUMENTASI</h5>
            </div>
          </div>

          <div class="carousel-item">
            <img src="dokumentasi-pbl-2.jpeg" class="d-block w-100">
            <div class="carousel-caption custom-caption">
              <h5>DOKUMENTASI</h5>
            </div>
          </div>

          <div class="carousel-item">
            <img src="dokumentasi-pbl-3.jpeg" class="d-block w-100">
            <div class="carousel-caption custom-caption">
              <h5>DOKUMENTASI</h5>
            </div>
          </div>
        </div>

        <button class="carousel-control-prev" data-bs-target="#carouselPBL" data-bs-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" data-bs-target="#carouselPBL" data-bs-slide="next">
          <span class="carousel-control-next-icon"></span>
        </button>
      </div>

    </div>
  </div>
</div>

<!-- ========================= CONTACT ========================= -->
<div class="mt-5 text-center">
  <h5 class="fw-bold text-danger">CONTACT US</h5>

  <div class="contact-box mt-3">
    <div><b>EMAIL:</b> jesinaaurora@gmail.com | viviansarahdiva25@gmail.com</div>
    <div><b>INSTAGRAM:</b> holy_blesty01 | vv_nyaw</div>
  </div>
</div>

<!-- ========================= FOOTER ========================= -->
<footer class="text-center text-white py-3 mt-5" style="background:#FD5DA8;">
  © 2025 Projek PBL | All Rights Reserved
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<script>

/* LOGIN */
const modalPick = new bootstrap.Modal('#loginModal');
const modalForm = new bootstrap.Modal('#formLoginModal');

document.getElementById("loginBtn").onclick=()=>modalPick.show();

document.getElementById("btnLoginDosen").onclick=()=>{
  document.getElementById("role").value="dosen";
  document.getElementById("formLoginModalLabel").textContent="Login Dosen";
  modalForm.show(); modalPick.hide();
};

document.getElementById("btnLoginMahasiswa").onclick=()=>{
  document.getElementById("role").value="mahasiswa";
  document.getElementById("formLoginModalLabel").textContent="Login Mahasiswa";
  modalForm.show(); modalPick.hide();
};
</script>

</body>
</html>