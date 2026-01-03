<?php
/*
  =========================================================
  Nama File   : lihat_nilai.php
  Deskripsi   : Halaman mahasiswa untuk melihat nilai
                proyek PBL dalam bentuk bintang (1–5),
                catatan dosen, dan tanggal penilaian
  Pembuat     : Vivian Sarah Diva Alisianoi
                Jesina Holyblesty Simatupang
  Tanggal     : 26 Desember 2025
  =========================================================
*/

// =========================
// SESSION & KONEKSI
// =========================

// session hanya aktif selama browser terbuka
session_set_cookie_params(0);

// memulai session
session_start();

// memanggil koneksi database
include "../koneksi.php";

// =========================
// CEK LOGIN MAHASISWA
// =========================

// memastikan user sudah login sebagai mahasiswa
if (!isset($_SESSION['id_mahasiswa']) || $_SESSION['role'] !== 'mahasiswa') {
    // jika belum login atau role bukan mahasiswa
    // diarahkan kembali ke halaman index
    header("Location: ../index.php");
    exit;
}

// mengambil id mahasiswa dari session
$idMahasiswa = $_SESSION['id_mahasiswa'];

// =========================
// AMBIL DATA MAHASISWA
// =========================

// query untuk mengambil nama mahasiswa
$queryNama = mysqli_query(
    $koneksi,
    "SELECT nama FROM mahasiswa WHERE id_mahasiswa='$idMahasiswa'"
);

// menyimpan hasil query ke array
$dataNama = mysqli_fetch_assoc($queryNama);

// menyimpan nama mahasiswa
$namaMahasiswa = $dataNama['nama'];

// =========================
// AMBIL DATA NILAI PROYEK
// =========================

// query untuk mengambil data portofolio beserta nilai,
// catatan, dosen penilai, dan tanggal penilaian
$sql = "
    SELECT
        p.judul,
        n.nilai,
        n.catatan,
        n.tanggal_penilaian,
        d.nama AS nama_dosen
    FROM portofolio p
    LEFT JOIN nilai n
        ON p.id_portofolio = n.id_portofolio
    LEFT JOIN dosen d
        ON n.id_dosen = d.id_dosen
    WHERE p.id_mahasiswa = '$idMahasiswa'
";

// menjalankan query
$dataNilai = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Nilai Proyek</title>

<!-- =========================
     BOOTSTRAP CSS
========================= -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* =========================
   STYLE HALAMAN
========================= */

/* warna latar belakang halaman */
body {
    background: #f4f7ff;
}

/* header card */
.card-header-blue {
    background: #0041C2;
    color: white;
}

/* tampilan bintang nilai */
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

<!-- =========================
     HEADER HALAMAN
========================= -->
<div class="card mb-3 shadow-sm">
    <div class="card-header card-header-blue">
        <h4 class="mb-0">Nilai & Catatan Proyek</h4>
    </div>
    <div class="card-body">
        <p class="mb-0">
            <!-- menampilkan nama mahasiswa -->
            Mahasiswa:
            <strong><?= htmlspecialchars($namaMahasiswa) ?></strong>
        </p>
    </div>
</div>

<!-- =========================
     TOMBOL KEMBALI
========================= -->
<div class="mb-3">
    <a href="dashboard_mhs.php" class="btn btn-outline-secondary btn-sm">
        ← Kembali ke Dashboard
    </a>
</div>

<?php
// jika mahasiswa belum memiliki proyek
if (mysqli_num_rows($dataNilai) == 0) {
?>

<div class="alert alert-info">
    Belum ada proyek.
</div>

<?php
} else {
?>

<!-- =========================
     TABEL NILAI PROYEK
========================= -->
<div class="card shadow-sm">
<div class="card-body p-0">

<table class="table table-bordered align-middle mb-0">

<thead class="table-primary text-center">
<tr>
    <th>Judul Proyek</th>
    <th width="18%">Nilai</th>
    <th>Catatan</th>
    <th width="18%">Dosen</th>
    <th width="18%">Tanggal Penilaian</th>
</tr>
</thead>

<tbody>

<?php
// loop data nilai proyek
while ($row = mysqli_fetch_assoc($dataNilai)) {
?>
<tr>
    <!-- judul proyek -->
    <td><?= htmlspecialchars($row['judul']) ?></td>

    <!-- NILAI BINTANG -->
    <td class="text-center">
    <?php
    // jika nilai sudah ada
    if ($row['nilai'] !== null) {
        $nilai = (int) $row['nilai'];

        // konversi angka ke bintang
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

    <!-- catatan dosen -->
    <td>
        <?= $row['catatan']
            ? htmlspecialchars($row['catatan'])
            : '-' ?>
    </td>

    <!-- nama dosen -->
    <td>
        <?= $row['nama_dosen']
            ? htmlspecialchars($row['nama_dosen'])
            : '-' ?>
    </td>

    <!-- tanggal penilaian -->
    <td class="text-center">
        <?= $row['tanggal_penilaian']
            ? date('d-m-Y', strtotime($row['tanggal_penilaian']))
            : '-' ?>
    </td>
</tr>
<?php
}
?>

</tbody>
</table>

</div>
</div>

<?php
}
?>

</div>

</body>
</html>
