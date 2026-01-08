<?php
/*
  Nama File   : dashboard_mhs.php
  Deskripsi   : Halaman dashboard mahasiswa untuk mengelola portofolio dan melihat nilai
  Pembuat    : Vivian Sarah Diva Alisianoi & Jesina Holyblesty Simatupang
  Tanggal    : 26 Desember 2025
*/

session_set_cookie_params(0);
session_start();
include "../koneksi.php";

/* =========================
   CEK LOGIN MAHASISWA
========================= */
if (!isset($_SESSION['id_mahasiswa']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../index.php");
    exit;
}

$idMahasiswa = $_SESSION['id_mahasiswa'];

/* =========================
   AMBIL DATA MAHASISWA
========================= */
$queryNama = mysqli_query(
    $koneksi,
    "SELECT nama FROM mahasiswa WHERE id_mahasiswa='$idMahasiswa'"
);
$dataNama = mysqli_fetch_assoc($queryNama);
$nama = $dataNama['nama'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Mahasiswa</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* latar belakang halaman */
        body {
            background: #f4f7ff;
        }

        /* header dashboard */
        .card-header-blue {
            background: #0041C2;
            color: white;
        }

        /* menu list */
        .list-group-item {
            border: none;
            padding: 12px 16px;
        }

        /* hover menu */
        .list-group-item-action:hover {
            background: #e7efff;
            color: #0041C2;
        }

        /* tombol logout */
        .logout-item {
            color: #dc3545;
        }

        .logout-item:hover {
            background: #f8d7da;
            color: #842029;
        }

        /* preview gambar */
        img.preview {
            max-width: 80px;
            border-radius: 6px;
        }
    </style>
</head>

<body>
<div class="container mt-4">

    <!-- HEADER DASHBOARD -->
    <div class="card mb-3 shadow-sm">
        <div class="card-header card-header-blue">
            <h4 class="mb-0">Dashboard Mahasiswa</h4>
        </div>
        <div class="card-body">
            <p class="mb-0">
                Selamat datang,
                <strong><?= htmlspecialchars($nama) ?></strong>
            </p>
        </div>
    </div>

    <!-- MENU DASHBOARD -->
    <div class="list-group mb-3 shadow-sm">

        <a href="portofolio_detail.php" class="list-group-item list-group-item-action">
            ➕ Tambah Portofolio
        </a>

        <a href="lihat_nilai.php" class="list-group-item list-group-item-action">
            📊 Lihat Nilai
        </a>

        <a href="ganti_password_mhs.php" class="list-group-item list-group-item-action">
            🔑 Ganti Password
        </a>

        <a href="../logout.php" class="list-group-item list-group-item-action logout-item">
            🚪 Logout
        </a>

    </div>

    <!-- TABEL PORTOFOLIO -->
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <table class="table table-bordered align-middle mb-0">

                <thead class="table-primary text-center">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Gambar</th>
                        <th>Judul</th>
                        <th width="15%">Repository</th>
                        <th width="25%">Kelola</th>
                    </tr>
                </thead>

                <tbody>

<?php

$queryPortofolio = mysqli_query(
    $koneksi,
    "SELECT * FROM portofolio WHERE id_mahasiswa='$idMahasiswa'"
);

if (mysqli_num_rows($queryPortofolio) == 0) {

    echo "
        <tr>
            <td colspan='5' class='text-center'>
                Belum ada portofolio.
            </td>
        </tr>
    ";

} else {

    $no = 1;

    while ($p = mysqli_fetch_assoc($queryPortofolio)) {

        echo "<tr>";

        echo "<td class='text-center'>";
        echo $no++;
        echo "</td>";

        echo "<td class='text-center'>";
        if ($p['gambar']) {
            echo "<img src='../uploads/" . htmlspecialchars($p['gambar']) . "' class='preview'>";
        } else {
            echo "-";
        }
        echo "</td>";

        echo "<td>";
        echo htmlspecialchars($p['judul']);
        echo "</td>";

        echo "<td class='text-center'>";
        if ($p['repo_link']) {
            echo "<a href='" . htmlspecialchars($p['repo_link']) . "' target='_blank'>Link</a>";
        } else {
            echo "-";
        }
        echo "</td>";

        echo "<td class='text-center'>";

        echo "<a
                href='portofolio_detail.php?id=" . $p['id_portofolio'] . "'
                class='btn btn-outline-primary btn-sm'>
                Edit
              </a> ";

        echo "<a
                href='portofolio_detail.php?mode=hapus&id=" . $p['id_portofolio'] . "'
                onclick=\"return confirm('Hapus portofolio?')\"
                class='btn btn-outline-danger btn-sm'>
                Hapus
              </a>";

        echo "</td>";

        echo "</tr>";
    }
}

?>
