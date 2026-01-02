<?php
/*
  Nama File   : dashboard_dsn.php
  Deskripsi   : Halaman dashboard dosen untuk mengelola menu utama
  Pembuat    : Vivian Sarah Diva Alisianoi & Jesina Holyblesty Simatupang
  Tanggal    : 26 Desember 2025
*/

// session hanya aktif selama browser terbuka
session_set_cookie_params(0);
// memulai session
session_start();
// memanggil koneksi database
include "../koneksi.php";

// cek apakah dosen sudah login
if (!isset($_SESSION['id_dosen']) || $_SESSION['role'] !== 'dosen') {
    // jika belum login, kembali ke halaman index
    header("Location: ../index.php");
    exit;
}

// mengambil id dosen dari session
$idDosen = $_SESSION['id_dosen'];

// query untuk mengambil nama dosen
$queryNama = mysqli_query(
    $koneksi,
    "SELECT nama FROM dosen WHERE id_dosen='$idDosen'"
);
// mengambil hasil query dalam bentuk array
$dataNama = mysqli_fetch_assoc($queryNama);
// menyimpan nama dosen
$nama = $dataNama['nama'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Dosen</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* warna latar belakang halaman */
        body {
            background: #f9f0f5;
        }

        /* header dashboard */
        .card-header-pink {
            background: #0041C2;
            color: white;
        }

        /* item menu */
        .list-group-item {
            border: none;
            padding: 12px 16px;
        }

        /* efek hover menu */
        .list-group-item-action:hover {
            background: #f8cfe3;
            color: #7a0044;
        }

        /* warna khusus tombol logout */
        .logout-item {
            color: #dc3545;
        }

        /* hover tombol logout */
        .logout-item:hover {
            background: #f8d7da;
            color: #842029;
        }
    </style>
</head>
<body>

<div class="container mt-4">

    <!-- card header -->
    <div class="card mb-3 shadow-sm">
        <div class="card-header card-header-pink">
            <h4 class="mb-0">Dashboard Dosen</h4>
        </div>
        <div class="card-body">
            <p class="mb-0">
                <!-- menampilkan nama dosen -->
                Selamat datang, <strong><?= htmlspecialchars($nama) ?></strong>
            </p>
        </div>
    </div>

    <!-- menu navigasi dosen -->
    <div class="list-group mb-3 shadow-sm">
        <!-- menu portofolio mahasiswa -->
        <a href="portofolio_dsn.php" class="list-group-item list-group-item-action">
            📁 Portofolio Mahasiswa
        </a>
        <!-- menu ganti password -->
        <a href="ganti_password_dsn.php" class="list-group-item list-group-item-action">
            🔑 Ganti Password
        </a>
        <!-- menu logout -->
        <a href="../logout.php" class="list-group-item list-group-item-action logout-item">
            🚪 Logout
        </a>
    </div>

    <!-- informasi tambahan -->
    <div class="alert alert-light border">
        Silakan pilih menu di atas untuk melanjutkan.
    </div>

</div>

</body>
</html>
