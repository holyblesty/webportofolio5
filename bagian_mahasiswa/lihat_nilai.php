<?php
// ===============================
// LIHAT NILAI & CATATAN PROYEK
// MAHASISWA (SEMESTER 1)
// ===============================

session_set_cookie_params(0);
session_start();
include "../koneksi.php";

// Cek login mahasiswa
if (!isset($_SESSION['id_mahasiswa']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../index.php");
    exit;
}

$id_mahasiswa = $_SESSION['id_mahasiswa'];

// Ambil nama mahasiswa
$qNama = mysqli_query($koneksi, "SELECT nama FROM mahasiswa WHERE id_mahasiswa='$id_mahasiswa'");
$dataNama = mysqli_fetch_assoc($qNama);
$nama_mahasiswa = $dataNama['nama'];

// Ambil data portofolio + nilai
$sql = "SELECT p.judul, n.nilai, n.catatan, d.nama AS nama_dosen
        FROM portofolio p
        LEFT JOIN nilai n ON p.id_portofolio = n.id_portofolio
        LEFT JOIN dosen d ON n.id_dosen = d.id_dosen
        WHERE p.id_mahasiswa = '$id_mahasiswa'";

$data = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nilai Proyek</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background:#f9f0f5;
        }
        .card-header-pink {
            background:#e11584;
            color:white;
        }
    </style>
</head>
<body>

<div class="container mt-4">

    <!-- HEADER -->
    <div class="card mb-3 shadow-sm">
        <div class="card-header card-header-pink">
            <h4 class="mb-0">Nilai & Catatan Proyek</h4>
        </div>
        <div class="card-body">
            <p class="mb-0">
                Mahasiswa: <strong><?= htmlspecialchars($nama_mahasiswa) ?></strong>
            </p>
        </div>
    </div>

    <!-- KEMBALI -->
    <div class="mb-3">
        <a href="dashboard_mhs.php" class="btn btn-outline-secondary btn-sm">
            ← Kembali ke Dashboard
        </a>
    </div>

<?php if (mysqli_num_rows($data) == 0) { ?>

    <div class="alert alert-info">
        Belum ada proyek.
    </div>

<?php } else { ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">

        <table class="table table-bordered align-middle mb-0">
            <thead class="table-danger text-center">
                <tr>
                    <th>Judul Proyek</th>
                    <th width="15%">Nilai</th>
                    <th>Catatan</th>
                    <th width="20%">Dosen</th>
                </tr>
            </thead>
            <tbody>

        <?php while ($row = mysqli_fetch_assoc($data)) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td class="text-center">
                        <?= $row['nilai'] !== null ? $row['nilai'] : 'Belum dinilai' ?>
                    </td>
                    <td><?= $row['catatan'] ? htmlspecialchars($row['catatan']) : '-' ?></td>
                    <td><?= $row['nama_dosen'] ? htmlspecialchars($row['nama_dosen']) : '-' ?></td>
                </tr>
        <?php } ?>

            </tbody>
        </table>

        </div>
    </div>

<?php } ?>

</div>

</body>
</html>
