<?php
session_start();
include "../koneksi.php";

// cek login dosen
if (!isset($_SESSION['id_dosen']) || $_SESSION['role'] !== 'dosen') {
    header("Location: ../index.php");
    exit;
}

$id_dosen = $_SESSION['id_dosen'];

// ambil nama dosen
$q = mysqli_query(
    $koneksi,
    "SELECT nama FROM dosen WHERE id_dosen='$id_dosen'"
);
$data = mysqli_fetch_assoc($q);
$nama = $data['nama'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Dosen</title>

    <!-- Bootstrap (aman semester 1) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background:#f9f0f5;
        }

        /* header card */
        .card-header-pink {
            background:#e11584;
            color:white;
        }

        /* menu list */
        .list-group-item {
            border: none;
            padding: 12px 16px;
        }

        .list-group-item-action:hover {
            background:#f8cfe3;
            color:#7a0044;
        }

        /* logout merah lembut */
        .logout-item {
            color:#dc3545;
        }
        .logout-item:hover {
            background:#f8d7da;
            color:#842029;
        }
    </style>
</head>
<body>

<div class="container mt-4">

    <!-- HEADER -->
    <div class="card mb-3 shadow-sm">
        <div class="card-header card-header-pink">
            <h4 class="mb-0">Dashboard Dosen</h4>
        </div>
        <div class="card-body">
            <p class="mb-0">
                Selamat datang, <strong><?= htmlspecialchars($nama) ?></strong>
            </p>
        </div>
    </div>

    <!-- MENU -->
    <div class="list-group mb-3 shadow-sm">
        <a href="portofolio_dsn.php" class="list-group-item list-group-item-action">
            📁 Portofolio Mahasiswa
        </a>
        <a href="ganti_password_dsn.php" class="list-group-item list-group-item-action">
            🔑 Ganti Password
        </a>
        <a href="../logout.php" class="list-group-item list-group-item-action logout-item">
            🚪 Logout
        </a>
    </div>

    <!-- INFO -->
    <div class="alert alert-light border">
        Silakan pilih menu di atas untuk melanjutkan.
    </div>

</div>

</body>
</html>
