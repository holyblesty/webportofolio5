<!-- 
=========================================================
  Nama File   : Portofolio_mhs.php
  Deskripsi   : Menampilkan portofolio bagian dosen
  Pembuat     : Jesina HolyBlesty Simatupang (3312511017)
              : Vivian Sarah Diva Alisianoi (3312511018)
  Tanggal     : 19 Oktober 2025
=========================================================
-->

<?php

// =========================
// SESSION & KONEKSI DATABASE
// =========================

// memulai session
session_start();

// memanggil file koneksi database
include "../koneksi.php";

// =========================
// CEK LOGIN DOSEN
// =========================

// memastikan user sudah login sebagai dosen
if (!isset($_SESSION['id_dosen']) || $_SESSION['role'] !== 'dosen') {
    // jika belum login atau role bukan dosen, kembali ke index
    header("Location: ../index.php");
    exit;
}

// mengambil id dosen dari session
$idDosen = $_SESSION['id_dosen'];

// =========================
// AMBIL DATA DOSEN
// =========================

// query untuk mengambil nama dosen
$qNama = mysqli_query(
$koneksi,
"SELECT nama FROM dosen WHERE id_dosen='$idDosen'"
);

// menyimpan hasil query ke array
$dNama = mysqli_fetch_assoc($qNama);
$namaDosen = $dNama['nama'];

// =========================
// KEYWORD PENCARIAN
// =========================

// inisialisasi keyword pencarian
$keyword = '';

// mengecek apakah ada input pencarian
if (isset($_GET['cari'])) {
    // mengamankan input keyword
    $keyword = mysqli_real_escape_string($koneksi, $_GET['cari']);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Portofolio Mahasiswa</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

/* pengaturan dasar halaman */
body {
    background: #fbfbfbff;
    padding-top: 80px;
}

/* warna utama (custom biru) */
.bg-primary-custom {
    background: #0041C2;
    color: white;
}

.btn-primary-custom {
    background: #0041C2;
    color: white;
}

.btn-primary-custom:hover {
    background: #0035a0;
    color: white;
}

/* tampilan bintang nilai */
.star {
    color: #f5c518;
    font-size: 1.2rem;
}

.star-muted {
    color: #ccc;
    font-size: 1.2rem;
}
</style>
</head>

<body>

<!-- =========================
     NAVBAR
========================= -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-primary-custom">
    <div class="container">
        <span class="navbar-brand fw-bold">Dashboard Dosen</span>
        <div class="ms-auto">
            <a href="dashboard_dsn.php" class="btn btn-outline-light btn-sm me-2">
                Dashboard
            </a>
            <a href="../logout.php" class="btn btn-outline-light btn-sm">
                Logout
            </a>
        </div>
    </div>
</nav>

<div class="container">

<!-- =========================
     HEADER HALAMAN
========================= -->
<div class="mb-4 p-4 rounded bg-primary-custom">
    <h4 class="mb-1">Portofolio Mahasiswa</h4>
    <p class="mb-0">
        Dosen: <?= htmlspecialchars($namaDosen) ?>
    </p>
</div>

<!-- =========================
     FORM PENCARIAN
========================= -->
<div class="card mb-3 shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-10">
                <input
                    type="text"
                    name="cari"
                    class="form-control"
                    placeholder="Cari berdasarkan Nama atau NIM Mahasiswa..."
                    value="<?= htmlspecialchars($keyword) ?>"
                >
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-primary-custom fw-semibold">
                    Cari
                </button>
            </div>
        </form>
    </div>
</div>

<!-- =========================
     TABEL PORTOFOLIO
========================= -->
<div class="card shadow-sm">
<div class="card-body p-0">

<table class="table table-bordered align-middle mb-0">

<thead class="table-dark text-center">
<tr>
    <th>No</th>
    <th>Nama Mahasiswa</th>
    <th>NIM</th>
    <th>Judul</th>
    <th>Deskripsi</th>
    <th>Repo</th>
    <th>Nilai</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>

<?php
// =========================
// QUERY DATA PORTOFOLIO
// =========================

$sql = "SELECT
p.id_portofolio,
        p.judul,
        p.deskripsi,
        p.repo_link,
        m.nama,
        m.username AS nim,
        n.nilai
    FROM portofolio p
    JOIN mahasiswa m ON m.id_mahasiswa = p.id_mahasiswa
    LEFT JOIN nilai n ON n.id_portofolio = p.id_portofolio
";

// jika ada keyword pencarian
if ($keyword !== '') {
    $sql .= " WHERE m.nama LIKE '%$keyword%'
              OR m.username LIKE '%$keyword%'";
}

// urutkan data
$sql .= " ORDER BY p.id_portofolio ASC";

// eksekusi query
$query = mysqli_query($koneksi, $sql);

// jika data kosong
if (mysqli_num_rows($query) === 0) {

    echo "
        <tr>
            <td colspan='8' class='text-center p-4'>
                Data tidak ditemukan.
            </td>
        </tr>
    ";

} else {

    // nomor urut tabel
    $no = 1;

    // loop data portofolio
    while ($p = mysqli_fetch_assoc($query)) {

        // =====================
        // KONVERSI NILAI → BINTANG
        // =====================
        if ($p['nilai'] === null) {
            $nilaiView = "<span class='text-muted'>Belum</span>";
        } else {
            $nilaiView = "";
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $p['nilai']) {
                    $nilaiView .= "<span class='star'>★</span>";
                } else {
                    $nilaiView .= "<span class='star-muted'>★</span>";
                }
            }
        }
?>

<tr>
    <td><?= $no++ ?></td>
    <td><?= htmlspecialchars($p['nama']) ?></td>
    <td><?= htmlspecialchars($p['nim']) ?></td>
    <td><?= htmlspecialchars($p['judul']) ?></td>
    <td><?= htmlspecialchars(substr($p['deskripsi'], 0, 50)) ?>...</td>

    <!-- link repository -->
    <td>
        <?php if ($p['repo_link']) { ?>
            <a href="<?= htmlspecialchars($p['repo_link']) ?>" target="_blank">
                Link
            </a>
        <?php } else { echo "-"; } ?>
    </td>

    <!-- nilai bintang -->
    <td class="text-center">
        <?= $nilaiView ?>
    </td>

    <!-- tombol nilai / edit -->
    <td class="text-center">
        <a
            href="proses_nilai.php?id_portofolio=<?= $p['id_portofolio'] ?>"
            class="btn btn-sm btn-primary-custom"
        >
            <?= ($p['nilai'] === null) ? 'Nilai' : 'Edit' ?>
        </a>
    </td>
</tr>

<?php
    }
}
?>

</tbody>
</table>

</div>
</div>

</div>
</body>
</html>
