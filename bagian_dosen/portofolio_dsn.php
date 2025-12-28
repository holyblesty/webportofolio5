<?php
/*
  Nama File   : portofolio_dsn.php
  Deskripsi   : Halaman dosen untuk melihat daftar portofolio mahasiswa
                serta melakukan penilaian atau pengeditan nilai
  Pembuat    : Vivian Sarah Diva Alisianoi & Jesina Holyblesty Simatupang
  Tanggal    : 26 Desember 2025
*/

/*
  Session dibuat hanya aktif selama browser terbuka
  (aman dan sesuai kebutuhan sistem PBL)
*/
session_set_cookie_params(0);
session_start();

/*
  Memanggil file koneksi database
*/
include "../koneksi.php";

/* =========================
   CEK LOGIN DOSEN
========================= */
/*
  Memastikan user sudah login
  dan memiliki role sebagai dosen
*/
if (!isset($_SESSION['id_dosen']) || $_SESSION['role'] !== 'dosen') {
    // Jika tidak valid, kembalikan ke halaman utama
    header("Location: ../index.php");
    exit;
}

// Mengambil id dosen dari session
$idDosen = $_SESSION['id_dosen'];

/* =========================
   AMBIL DATA DOSEN
========================= */
/*
  Query untuk mengambil nama dosen
  berdasarkan id dosen yang sedang login
*/
$qNama = mysqli_query(
    $koneksi,
    "SELECT nama FROM dosen WHERE id_dosen='$idDosen'"
);

// Mengambil hasil query menjadi array asosiatif
$dNama = mysqli_fetch_assoc($qNama);

// Menyimpan nama dosen
$namaDosen = $dNama['nama'];

/* =========================
   AMBIL KEYWORD PENCARIAN
========================= */
/*
  Keyword digunakan untuk fitur pencarian
  berdasarkan nama atau NIM mahasiswa
*/
$keyword = '';
if (isset($_GET['cari'])) {
    // Escape input untuk mencegah SQL error
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
        /* Warna latar halaman */
        body {
            background: #f9f0f5;
            padding-top: 80px;
        }

        /* Badge status nilai */
        .badge-belum { background:#f8d7da; color:#842029; }
        .badge-tinggi { background:#d4edda; color:#155724; }
        .badge-sedang { background:#fff3cd; color:#856404; }
        .badge-rendah { background:#f8d7da; color:#721c24; }

        /* Tombol nilai */
        .btn-danger-soft {
            background:#dc3545;
            color:white;
        }
        .btn-danger-soft:hover {
            background:#bb2d3b;
            color:white;
        }

        /* Tombol edit */
        .btn-pink-soft {
            background:#f8cfe3;
            color:#7a0044;
        }
        .btn-pink-soft:hover {
            background:#eeb6d2;
            color:#7a0044;
        }
    </style>
</head>
<body>

<!-- =========================
     NAVBAR
========================= -->
<!--
  Navbar utama untuk halaman dosen
-->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background:#e11584;">
    <div class="container">
        <span class="navbar-brand fw-bold">Dashboard Dosen</span>
        <div class="ms-auto">
            <a href="dashboard_dsn.php" class="btn btn-outline-light btn-sm me-2">Dashboard</a>
            <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container">

    <!-- HEADER HALAMAN -->
    <div class="mb-4 p-4 rounded text-white" style="background:#e11584;">
        <h4 class="mb-1">Portofolio Mahasiswa</h4>
        <!-- Menampilkan nama dosen yang sedang login -->
        <p class="mb-0">Dosen: <?= htmlspecialchars($namaDosen) ?></p>
    </div>

    <!-- =========================
         FORM PENCARIAN
    ========================= -->
    <!--
      Form untuk mencari portofolio mahasiswa
      berdasarkan Nama atau NIM (username)
    -->
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
                    <button type="submit" class="btn btn-pink-soft fw-semibold">
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
/* =========================
   QUERY DATA PORTOFOLIO
========================= */
/*
  Mengambil data portofolio mahasiswa
  NIM direpresentasikan sebagai username
*/
$sql = "
    SELECT
        p.id_portofolio,
        p.judul,
        p.deskripsi,
        p.repo_link,
        m.nama,
        m.username AS nim, -- username digunakan sebagai NIM
        n.nilai
    FROM portofolio p
    JOIN mahasiswa m ON m.id_mahasiswa = p.id_mahasiswa
    LEFT JOIN nilai n ON n.id_portofolio = p.id_portofolio
";

/*
  Jika ada keyword pencarian,
  tambahkan kondisi WHERE
*/
if ($keyword !== '') {
    $sql .= " WHERE m.nama LIKE '%$keyword%'
              OR m.username LIKE '%$keyword%'";
}

// Mengurutkan data berdasarkan id portofolio
$sql .= " ORDER BY p.id_portofolio ASC";

// Menjalankan query
$query = mysqli_query($koneksi, $sql);

/*
  Jika tidak ada data yang ditemukan
*/
if (mysqli_num_rows($query) === 0) {
    echo "
        <tr>
            <td colspan='8' class='text-center p-4'>
                Data tidak ditemukan.
            </td>
        </tr>
    ";
} else {

    // Nomor urut tabel
    $no = 1;

    // Menampilkan data portofolio
    while ($p = mysqli_fetch_assoc($query)) {

        /*
          Menentukan badge dan teks nilai
          berdasarkan nilai yang diperoleh
        */
        if ($p['nilai'] === null) {
            $badge = "badge-belum";
            $nilaiText = "Belum";
        } elseif ($p['nilai'] >= 80) {
            $badge = "badge-tinggi";
            $nilaiText = $p['nilai'];
        } elseif ($p['nilai'] >= 60) {
            $badge = "badge-sedang";
            $nilaiText = $p['nilai'];
        } else {
            $badge = "badge-rendah";
            $nilaiText = $p['nilai'];
        }
?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($p['nama']) ?></td>
            <td><?= htmlspecialchars($p['nim']) ?></td>
            <td><?= htmlspecialchars($p['judul']) ?></td>
            <td><?= htmlspecialchars(substr($p['deskripsi'], 0, 50)) ?>...</td>
            <td>
                <?php if ($p['repo_link']) { ?>
                    <a href="<?= htmlspecialchars($p['repo_link']) ?>" target="_blank">Link</a>
                <?php } else { echo "-"; } ?>
            </td>
            <td>
                <span class="badge <?= $badge ?>"><?= $nilaiText ?></span>
            </td>
            <td>
                <!-- Tombol nilai atau edit nilai -->
                <a
                    href="proses_nilai.php?id_portofolio=<?= $p['id_portofolio'] ?>"
                    class="btn btn-sm <?= ($p['nilai'] === null) ? 'btn-danger-soft' : 'btn-pink-soft' ?>"
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
