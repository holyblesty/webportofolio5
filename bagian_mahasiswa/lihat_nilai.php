<?php
/*
  Nama File   : lihat_nilai.php
  Deskripsi   : Halaman mahasiswa untuk melihat nilai (bintang) dan catatan proyek
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
$namaMahasiswa = $dataNama['nama'];

/* =========================
   AMBIL DATA NILAI PROYEK
========================= */
$sql = "
    SELECT
        p.judul,
        n.nilai,
        n.catatan,
        d.nama AS nama_dosen
    FROM portofolio p
    LEFT JOIN nilai n
        ON p.id_portofolio = n.id_portofolio
    LEFT JOIN dosen d
        ON n.id_dosen = d.id_dosen
    WHERE p.id_mahasiswa = '$idMahasiswa'
";

$dataNilai = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nilai Proyek</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: #f4f7ff;
        }

        .card-header-blue {
            background: #0041C2;
            color: white;
        }

        /* ===== STAR RATING DISPLAY ===== */
        .star {
            color: gold;
            font-size: 1.3rem;
        }

        .star-muted {
            color: #ccc;
            font-size: 1.3rem;
        }
    </style>
</head>
<body>

<div class="container mt-4">

    <!-- HEADER HALAMAN -->
    <div class="card mb-3 shadow-sm">
        <div class="card-header card-header-blue">
            <h4 class="mb-0">Nilai & Catatan Proyek</h4>
        </div>
        <div class="card-body">
            <p class="mb-0">
                Mahasiswa:
                <strong><?= htmlspecialchars($namaMahasiswa) ?></strong>
            </p>
        </div>
    </div>

    <!-- TOMBOL KEMBALI -->
    <div class="mb-3">
        <a href="dashboard_mhs.php" class="btn btn-outline-secondary btn-sm">
            ← Kembali ke Dashboard
        </a>
    </div>

<?php if (mysqli_num_rows($dataNilai) == 0) { ?>

    <div class="alert alert-info">
        Belum ada proyek.
    </div>

<?php } else { ?>

    <!-- TABEL NILAI PROYEK -->
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <table class="table table-bordered align-middle mb-0">

                <thead class="table-primary text-center">
                    <tr>
                        <th>Judul Proyek</th>
                        <th width="18%">Nilai</th>
                        <th>Catatan</th>
                        <th width="20%">Dosen</th>
                    </tr>
                </thead>

                <tbody>

                <?php while ($row = mysqli_fetch_assoc($dataNilai)) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['judul']) ?></td>

                        <!-- NILAI BINTANG -->
                        <td class="text-center">
                        <?php
                        if ($row['nilai'] !== null) {
                            $nilai = (int) $row['nilai'];
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $nilai) {
                                    echo "<span class='star'>★</span>";
                                } else {
                                    echo "<span class='star-muted'>★</span>";
                                }
                            }
                        } else {
                            echo "<span class='text-muted'>Belum dinilai</span>";
                        }
                        ?>
                        </td>

                        <td>
                            <?= $row['catatan'] ? htmlspecialchars($row['catatan']) : '-' ?>
                        </td>

                        <td>
                            <?= $row['nama_dosen'] ? htmlspecialchars($row['nama_dosen']) : '-' ?>
                        </td>
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
