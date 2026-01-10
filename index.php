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
/* Mengatur cookie session agar berakhir saat browser ditutup */
session_set_cookie_params(0);

/* Memulai session */
session_start();

/* =========================
   CEK STATUS LOGIN
========================= */
/* Mengecek apakah user sudah login berdasarkan role */
if (isset($_SESSION['role'])) {

    /* Jika role adalah dosen, arahkan ke dashboard dosen */
    if ($_SESSION['role'] === 'dosen') {
        header("Location: bagian_dosen/dashboard_dsn.php");
        exit;
    }
    /* Jika role adalah mahasiswa, arahkan ke dashboard mahasiswa */
    elseif ($_SESSION['role'] === 'mahasiswa') {
        header("Location: bagian_mahasiswa/dashboard_mhs.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Pengaturan encoding karakter -->
    <meta charset="UTF-8">

    <!-- Pengaturan viewport agar responsif -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Judul halaman -->
    <title>Web Portofolio Projek PBL</title>

    <!-- =========================
         BOOTSTRAP & FONT
    ========================= -->
    <!-- Import Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Import font Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Mengatur tinggi penuh halaman */
        html, body { height: 100%; }

        /* Pengaturan dasar body */
        body {
            background: white;
            font-family: 'Poppins', sans-serif;
            color: #000;
            padding-top: 70px;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Konten utama agar footer tetap di bawah */
        .main-content { flex: 1; }

        /* =========================
           NAVBAR
        ========================= */
    .navbar{
      background:#0041C2; /* Warna latar navbar */
    }

    /* Mengatur warna dan ketebalan teks link dan brand navbar */
    .navbar .nav-link,
    .navbar .navbar-brand{
      color:white!important;
      font-weight:600;
    }

    /* Mengatur tampilan menu navbar yang aktif */
    .navbar .nav-link.active{
      background:#05034240;
      border-radius:10px;
      color:white!important;
}

    /* ================= DROPDOWN ================= */
    .dropdown-menu{
      min-width:280px; /* Lebar minimum dropdown */
      background-color:#0041C226; /* Warna latar dropdown */
      border-radius:10px; /* Sudut membulat */
      padding:8px; /* Jarak dalam dropdown */
      border:none; /* Menghilangkan border */
      backdrop-filter:blur(6px); /* Efek blur latar */
    }

    /* Mengatur item pada dropdown */
    .dropdown-menu .dropdown-item{
      color:#0041C2;
      font-weight:500;
      text-align:center;
      margin-bottom:10px;
      border-radius:20px;
      transition:background-color 0.3s ease; /* Animasi hover */
    }

    /* Efek hover pada item dropdown */
    .dropdown-menu .dropdown-item:hover{
      background-color:rgba(0,65,194,0.25);
      color:#003399;
    }

        /* =========================
           FOTO PROFIL
        ========================= */
        .circle-img {
            width: 500px;
            height: 500px;
            border-radius: 70%;
            background: url(FOTOPEMBUATWEBSITE.JPG.jpeg) center/cover;
            border: 5px solid #fff;
        }

        /* =========================
           KONTEN BOX
        ========================= */
        .content-box {
            background: rgba(0,65,194,.1);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,.1);
            margin-bottom: 20px;
            transition: .3s;
        }

        /* Efek hover box */
        .content-box:hover { transform: translateY(-4px); }

        /* Paragraf konten */
        .paragraph-section p {
            text-align: justify;
            text-indent: 40px;
            line-height: 1.8;
            font-size: 16px;
        }

        /* =========================
           CAROUSEL
        ========================= */
        #carouselPBL img {
            height: 380px;
            object-fit: cover;
        }

        /* Caption carousel */
        .custom-caption {
            background: rgba(0,65,194,.3);
            border-radius: 12px;
            padding: 6px 12px;
            color: #fff;
        }

        /* =========================
           LOGIN
        ========================= */
        .modal-header {
            background: #0041C2;
            color: #fff;
        }

        /* Tombol biru */
        .btn-blue {
            background: #0041C2;
            color: white;
            border: none;
            transition: .25s;
        }

        .btn-blue:hover { background: #003399; }

        /* Input form */
        input.form-control {
            border: 1.5px solid #0041C2;
            border-radius: 10px;
        }

        input.form-control:focus {
            box-shadow: 0 0 5px rgba(0,65,194,.6);
            border-color: #0041C2;
        }

    /* ================= FOOTER ================= */
    footer{
      background:#0041C2;
      color:white;
      text-align:center;
      padding:12px 0;
      margin-top:auto;
    }

    /* ================= RESPONSIVE ================= */
    @media(max-width:768px){
      .project-image,
      .project-info{
        width:100%;
      }
    }
    </style>
</head>

<body>

<!-- =========================
     NAVBAR
========================= -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <!-- Brand navbar -->
        <a class="navbar-brand fw-bold text-white">WEB PORTOFOLIO PROJEK PBL</a>

        <!-- Toggle navbar mobile -->
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu navbar -->
        <div class="collapse navbar-collapse justify-content-end" id="nav">
            <ul class="navbar-nav">

                <!-- Menu beranda -->
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">BERANDA</a>
                </li>

                <!-- Dropdown projek -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white fw-semibold"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown">
                        KUMPULAN PROJEK PBL
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="buku-tamu-tata-usaha.html">BUKU TAMU TATA USAHA</a></li>
                        <li><a class="dropdown-item" href="pengelolaan-rapat.html">PENGELOLAAN RAPAT</a></li>
                        <li><a class="dropdown-item" href="pencatatan-notulen.html">PENCATATAN NOTULEN</a></li>
                        <li><a class="dropdown-item" href="pengelolaan-surat-peringatan-sp.html">PENGELOLAAN SURAT PERINGATAN SP</a></li>
                        <li><a class="dropdown-item" href="jadwal-perkuliahan-mahasiswa-pribadi.html">JADWAL PERKULIAHAN MAHASISWA PRIBADI</a></li>
                        <li><a class="dropdown-item" href="web-informasi-event-kampus.html">WEB INFORMASI EVENT KAMPUS</a></li>
                        <li><a class="dropdown-item" href="aplikasi-pengumuman-akademik-online.html">APLIKASI PENGUMUMAN AKADEMIK ONLINE</a></li>
                    </ul>
                </li>

                <!-- Tombol login -->
                <li class="nav-item ms-3">
                    <button id="loginBtn" class="btn btn-blue px-3 fw-semibold">LOGIN</button>
                </li>

            </ul>
        </div>
    </div>
</nav>
<!-- ========================= 
     MODAL PILIH LOGIN
     Modal ini digunakan untuk memilih jenis login
     antara Dosen dan Mahasiswa
========================= -->
<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">

            <!-- Header modal -->
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center">
                    Pilih Jenis Login
                </h5>

                <!-- Tombol untuk menutup modal -->
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- Body modal -->
            <div class="modal-body text-center p-4">
                <p class="mb-3">Silakan pilih jenis login </p>

                <!-- Tombol login sebagai dosen -->
                <button id="btnLoginDosen"
                        type="button"
                        class="btn btn-blue w-100 mb-2 py-2">
                    Login Dosen
                </button>

                <!-- Tombol login sebagai mahasiswa -->
                <button id="btnLoginMahasiswa"
                        type="button"
                        class="btn btn-blue w-100 py-2">
                    Login Mahasiswa
                </button>
            </div>

        </div>
    </div>
</div>

<!-- =========================
     MODAL FORM LOGIN
     Modal ini menampilkan form login
     sesuai jenis user yang dipilih
========================= -->
<div class="modal fade" id="formLoginModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">

            <!-- Header modal login -->
            <div class="modal-header" id="loginHeader">
                <h5 class="modal-title w-100 text-center"
                    id="formLoginModalLabel">
                    Login
                </h5>

                <!-- Tombol tutup modal -->
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- Body modal berisi form login -->
            <div class="modal-body p-4">
                <form method="POST" action="login_proses.php">

                    <!-- Input tersembunyi untuk menyimpan role user -->
                    <input type="hidden" name="role" id="role">

                    <!-- Input username -->
                    <label class="fw-semibold">Username</label>
                    <input type="text"
                           name="username"
                           class="form-control mb-3"
                           required>

                    <!-- Input password -->
                    <label class="fw-semibold">Password</label>
                    <input type="password"
                           name="password"
                           class="form-control mb-3"
                           required>

                    <!-- Tombol submit login -->
                    <button type="submit"
                            class="btn btn-blue w-100 py-2 fw-semibold">
                        Login 
                    </button>

                </form>
            </div>

        </div>
    </div>
</div>


<!-- =========================
     KONTEN UTAMA
     Berisi informasi pembuat,
     deskripsi projek, dan dokumentasi
========================= -->
<div class="main-content">
    <div class="container mt-4">
        <div class="row">

            <!-- ================= KIRI ================= -->
            <!-- Bagian kiri menampilkan profil dan identitas -->
            <div class="col-md-5 text-center">

                <!-- Foto profil pembuat website -->
                <div class="circle-img mx-auto mb-4"></div>

                <!-- Informasi pembuat website -->
                <div class="content-box">
                    <p class="fw-bold fs-3">PEMBUATAN WEBSITE OLEH :</p>
                    <p class="fw-bold fs-4">❀ JESINA HOLYBLESTY SIMATUPANG</p>
                    <p class="fw-bold fs-4">❀ VIVIAN SARAH DIVA ALISIANOI</p>
                </div>

                <!-- Informasi projek PBL -->
                <div class="content-box">
                    <p class="fw-bold fs-3">HASIL PROJEK PBL<br>D3 - TEKNIK INFORMATIKA</p>
                    <p class="fw-bold fs-3">MALAM A</p>
                    <p class="fw-bold fs-3">POLITEKNIK NEGERI BATAM</p>
                </div>

                <!-- Kutipan motivasi -->
                <div class="content-box">
                    "Setiap proyek kecil membawa langkah besar menuju impian."
                </div>
            </div>

            <!-- ================= KANAN ================= -->
            <!-- Bagian kanan berisi penjelasan dan dokumentasi -->
            <div class="col-md-7">

                <!-- PARAGRAF PEMBUKA -->
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

                <!-- PARAGRAF PENJELASAN -->
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

                <!-- PARAGRAF PENUTUP -->
                <div class="content-box paragraph-section">
                    <h5 class="text-center text-danger fw-bold">PENUTUP</h5>

                    <p>
                        Terima kasih penulis ucapkan kepada seluruh pengunjung website kami yang telah meluangkan waktu untuk melihat hasil karya ini.
                    </p>
                </div>
<!-- ================= CAROUSEL ================= -->
<!-- Carousel dokumentasi kegiatan PBL -->
<div id="carouselPBL" class="carousel slide mb-4" data-bs-ride="carousel">
<div class="carousel-inner rounded-4 shadow-lg">
                      
<!-- Slide pertama -->
                        <div class="carousel-item active">
                            <img src="dokumentasi-pbl.jpeg" class="d-block w-100">
                            <div class="carousel-caption custom-caption">
                                <h5>DOKUMENTASI</h5>
                            </div>
                        </div>

                        <!-- Slide kedua -->
                        <div class="carousel-item">
                            <img src="dokumentasi-pbl-2.jpeg" class="d-block w-100">
                            <div class="carousel-caption custom-caption">
                                <h5>DOKUMENTASI</h5>
                            </div>
                        </div>

                        <!-- Slide ketiga -->
                        <div class="carousel-item">
                            <img src="dokumentasi-pbl-3.jpeg" class="d-block w-100">
                            <div class="carousel-caption custom-caption">
                                <h5>DOKUMENTASI</h5>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol navigasi carousel -->
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
</div>

<!-- =========================
     CONTACT
     Informasi kontak pembuat website
========================= -->
<div class="mt-5 mb-4 text-center">
    <h5 class="fw-bold text-danger">CONTACT US</h5>

    <div class="contact-box mt-3">
        <div><b>EMAIL:</b> jesinaaurora@gmail.com | viviansarahdiva25@gmail.com</div>
        <div><b>INSTAGRAM:</b> holy_blesty01 | vv_nyaw</div>
    </div>
</div>

<!-- ================= FOOTER ================= -->
<footer>
  © 2025 Projek PBL | All Rights Reserved
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- =========================
     SCRIPT LOGIN MODAL
========================= -->
<script>
    /* Inisialisasi modal */
    const modalPick = new bootstrap.Modal('#loginModal');
    const modalForm = new bootstrap.Modal('#formLoginModal');

    /* Menampilkan modal pilihan login */
    document.getElementById("loginBtn").onclick = () => modalPick.show();

    /* Login sebagai dosen */
    document.getElementById("btnLoginDosen").onclick = () => {
        document.getElementById("role").value = "dosen";
        document.getElementById("formLoginModalLabel").textContent = "Login Dosen";
        modalForm.show();
        modalPick.hide();
    };

    /* Login sebagai mahasiswa */
    document.getElementById("btnLoginMahasiswa").onclick = () => {
        document.getElementById("role").value = "mahasiswa";
        document.getElementById("formLoginModalLabel").textContent = "Login Mahasiswa";
        modalForm.show();
        modalPick.hide();
    };
</script>

</body>
</html>
