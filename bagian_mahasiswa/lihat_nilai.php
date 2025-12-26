<?php
/*
  Nama File   : lihat_nilai.php
  Deskripsi   : Halaman mahasiswa untuk melihat nilai dan catatan proyek
  Pembuat    : Vivian Sarah Diva Alisianoi & Jesina Holyblesty Simatupang
  Tanggal    : 26 Desember 2025
*/

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

// mengambil hasil query
$dataNama = mysqli_fetch_assoc($queryNama);

// menyimpan nama mahasiswa
$namaMahasiswa = $dataNama['nama'];

// =========================
// AMBIL DATA NILAI PROYEK
// =========================

// query untuk mengambil data portofolio, nilai, dan dosen
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

// menjalankan query
$dataNilai = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nilai Proyek</title>

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        /* latar belakang halaman */
        body {
            background: #f9f0f5;
        }

        /* header kartu */
        .card-header-pink {
            background: #e11584;
            color: white;
        }
    </style>
</head>
<body>

<div class="container mt-4">

    <!-- =========================
         HEADER HALAMAN
    ========================= -->
    <div class="card mb-3 shadow-sm">
        <div class="card-header card-header-pink">
            <h4 class="mb-0">Nilai & Catatan Proyek</h4>
        </div>
        <div class="card-body">
            <p class="mb-0">
                Mahasiswa:
                <strong><?= htmlspecialchars($namaMahasiswa) ?></strong>
            </p>
        </div>
    </div>

    <!-- =========================
         TOMBOL KEMBALI
    ========================= -->
    <div class="mb-3">
        <a
            href="dashboard_mhs.php"
            class="btn btn-outline-secondary btn-sm"
        >
            ← Kembali ke Dashboard
        </a>
    </div>

<?php
// cek apakah ada data nilai
if (mysqli_num_rows($dataNilai) == 0) {
?>

    <!-- jika belum ada proyek -->
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

                <!-- header tabel -->
                <thead class="table-danger text-center">
                    <tr>
                        <th>Judul Proyek</th>
                        <th width="15%">Nilai</th>
                        <th>Catatan</th>
                        <th width="20%">Dosen</th>
                    </tr>
                </thead>

                <tbody>

<?php
    // looping data nilai
    while ($row = mysqli_fetch_assoc($dataNilai)) {
?>
                    <tr>
                        <!-- judul proyek -->
                        <td>
                            <?= htmlspecialchars($row['judul']) ?>
                        </td>

                        <!-- nilai -->
                        <td class="text-center">
                            <?= $row['nilai'] !== null ? $row['nilai'] : 'Belum dinilai' ?>
                        </td>

                        <!-- catatan -->
                        <td>
                            <?= $row['catatan'] ? htmlspecialchars($row['catatan']) : '-' ?>
                        </td>

                        <!-- nama dosen -->
                        <td>
                            <?= $row['nama_dosen'] ? htmlspecialchars($row['nama_dosen']) : '-' ?>
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
