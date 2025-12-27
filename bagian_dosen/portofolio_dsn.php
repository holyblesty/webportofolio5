<?php
/*
  Nama File   : portofolio_dsn.php
  Deskripsi   : Halaman dosen untuk melihat daftar portofolio mahasiswa
                serta melakukan penilaian atau pengeditan nilai
  Pembuat    : Vivian Sarah Diva Alisianoi & Jesina Holyblesty Simatupang
  Tanggal    : 26 Desember 2025
*/

// session hanya aktif selama browser terbuka
session_set_cookie_params(0);

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
$queryNama = mysqli_query(
    $koneksi,
    "SELECT nama FROM dosen WHERE id_dosen='$idDosen'"
);

// mengambil hasil query
$dataNama = mysqli_fetch_assoc($queryNama);

// menyimpan nama dosen
$nama = $dataNama['nama'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Portofolio Mahasiswa</title>

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Font Awesome (ikon) -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    >

    <style>
        /* latar belakang halaman */
        body {
            background: #f9f0f5;
            padding-top: 80px;
        }

        /* badge nilai belum dinilai */
        .badge-belum {
            background: #f8d7da;
            color: #842029;
        }

        /* badge nilai tinggi */
        .badge-tinggi {
            background: #d4edda;
            color: #155724;
        }

        /* badge nilai sedang */
        .badge-sedang {
            background: #fff3cd;
            color: #856404;
        }

        /* badge nilai rendah */
        .badge-rendah {
            background: #f8d7da;
            color: #721c24;
        }

        /* tombol nilai (merah) */
        .btn-danger-soft {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        /* hover tombol nilai */
        .btn-danger-soft:hover {
            background-color: #bb2d3b;
            border-color: #bb2d3b;
            color: white;
        }

        /* tombol edit (pink lembut) */
        .btn-pink-soft {
            background-color: #f8cfe3;
            border-color: #f8cfe3;
            color: #7a0044;
        }

        /* hover tombol edit */
        .btn-pink-soft:hover {
            background-color: #eeb6d2;
            border-color: #eeb6d2;
            color: #7a0044;
        }
    </style>
</head>
<body>

<!-- =========================
     NAVBAR
========================= -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background:#e11584;">
    <div class="container">

        <!-- judul navbar -->
        <span class="navbar-brand fw-bold">Dashboard Dosen</span>

        <!-- menu kanan -->
        <div class="ms-auto">

            <!-- tombol dashboard -->
            <a
                href="dashboard_dsn.php"
                class="btn btn-outline-light btn-sm me-2"
            >
                Dashboard
            </a>

            <!-- tombol logout -->
            <a
                href="../logout.php"
                class="btn btn-outline-light btn-sm"
            >
                Logout
            </a>

        </div>
    </div>
</nav>

<div class="container">

    <!-- =========================
         HEADER HALAMAN
    ========================= -->
    <div class="mb-4 p-4 rounded text-white" style="background:#e11584;">
        <h4 class="mb-1">Portofolio Mahasiswa</h4>
        <p class="mb-0">
            Dosen: <?= htmlspecialchars($nama) ?>
        </p>
    </div>

    <!-- =========================
         TABEL PORTOFOLIO
    ========================= -->
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <table class="table table-striped table-bordered align-middle mb-0">

                <!-- header tabel -->
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Mahasiswa</th>
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

// query untuk mengambil data portofolio mahasiswa
$sql = "
    SELECT
        p.id_portofolio,
        p.judul,
        p.deskripsi,
        p.repo_link,
        m.nama AS nama_mahasiswa,
        n.nilai
    FROM portofolio p
    JOIN mahasiswa m
        ON m.id_mahasiswa = p.id_mahasiswa
    LEFT JOIN nilai n
        ON n.id_portofolio = p.id_portofolio
    ORDER BY p.id_portofolio ASC
";

// menjalankan query
$query = mysqli_query($koneksi, $sql);

// =========================
// CEK DATA PORTOFOLIO
// =========================

// jika tidak ada data
if (mysqli_num_rows($query) === 0) {
    echo "
        <tr>
            <td colspan='7' class='text-center p-4'>
                Belum ada portofolio.
            </td>
        </tr>
    ";
} else {

    // nomor urut
    $no = 1;

    // looping data portofolio
    while ($p = mysqli_fetch_assoc($query)) {

        // =========================
        // LOGIKA NILAI & BADGE
        // =========================

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
                        <!-- nomor -->
                        <td><?= $no++ ?></td>

                        <!-- nama mahasiswa -->
                        <td><?= htmlspecialchars($p['nama_mahasiswa']) ?></td>

                        <!-- judul portofolio -->
                        <td><?= htmlspecialchars($p['judul']) ?></td>

                        <!-- deskripsi singkat -->
                        <td>
                            <?= htmlspecialchars(substr($p['deskripsi'], 0, 50)) ?>...
                        </td>

                        <!-- link repository -->
                        <td>
                            <?php if ($p['repo_link']) { ?>
                                <a
                                    href="<?= htmlspecialchars($p['repo_link']) ?>"
                                    target="_blank"
                                >
                                    Link
                                </a>
                            <?php } else { ?>
                                -
                            <?php } ?>
                        </td>

                        <!-- nilai -->
                        <td>
                            <span class="badge <?= $badge ?>">
                                <?= $nilaiText ?>
                            </span>
                        </td>

                        <!-- aksi -->
                        <td>
                            <?php if ($p['nilai'] === null) { ?>
                                <!-- tombol nilai jika belum dinilai -->
                                <a
                                    href="proses_nilai.php?id_portofolio=<?= $p['id_portofolio'] ?>"
                                    class="btn btn-sm btn-danger-soft"
                                >
                                    Nilai
                                </a>
                            <?php } else { ?>
                                <!-- tombol edit jika sudah dinilai -->
                                <a
                                    href="proses_nilai.php?id_portofolio=<?= $p['id_portofolio'] ?>"
                                    class="btn btn-sm btn-pink-soft"
                                >
                                    Edit
                                </a>
                            <?php } ?>
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
