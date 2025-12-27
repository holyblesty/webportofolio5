<?php
/*
  Nama File   : dashboard_mhs.php
  Deskripsi   : Halaman dashboard mahasiswa untuk mengelola portofolio dan melihat nilai
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
$nama = $dataNama['nama'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Mahasiswa</title>

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

        /* header dashboard */
        .card-header-pink {
            background: #e11584;
            color: white;
        }

        /* menu list (disamakan dengan dashboard dosen) */
        .list-group-item {
            border: none;
            padding: 12px 16px;
        }

        /* hover menu */
        .list-group-item-action:hover {
            background: #f8cfe3;
            color: #7a0044;
        }

        /* tombol logout */
        .logout-item {
            color: #dc3545;
        }

        .logout-item:hover {
            background: #f8d7da;
            color: #842029;
        }

        /* preview gambar portofolio */
        img.preview {
            max-width: 80px;
            border-radius: 6px;
        }
    </style>
</head>
<body>

<div class="container mt-4">

    <!-- =========================
         HEADER DASHBOARD
    ========================= -->
    <div class="card mb-3 shadow-sm">
        <div class="card-header card-header-pink">
            <h4 class="mb-0">Dashboard Mahasiswa</h4>
        </div>
        <div class="card-body">
            <p class="mb-0">
                Selamat datang,
                <strong><?= htmlspecialchars($nama) ?></strong>
            </p>
        </div>
    </div>

    <!-- =========================
         MENU DASHBOARD
    ========================= -->
    <div class="list-group mb-3 shadow-sm">

        <!-- menu tambah portofolio -->
        <a
            href="portofolio_detail.php"
            class="list-group-item list-group-item-action"
        >
            ➕ Tambah Portofolio
        </a>

        <!-- menu lihat nilai -->
        <a
            href="lihat_nilai.php"
            class="list-group-item list-group-item-action"
        >
            📊 Lihat Nilai
        </a>

        <!-- menu ganti password -->
        <a
            href="ganti_password_mhs.php"
            class="list-group-item list-group-item-action"
        >
            🔑 Ganti Password
        </a>

        <!-- menu logout -->
        <a
            href="../logout.php"
            class="list-group-item list-group-item-action logout-item"
        >
            🚪 Logout
        </a>

    </div>

    <!-- =========================
         TABEL PORTOFOLIO
    ========================= -->
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <table class="table table-bordered align-middle mb-0">

                <!-- header tabel -->
                <thead class="table-danger text-center">
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
// query data portofolio mahasiswa
$queryPortofolio = mysqli_query(
    $koneksi,
    "SELECT * FROM portofolio WHERE id_mahasiswa='$idMahasiswa'"
);

// cek apakah mahasiswa memiliki portofolio
if (mysqli_num_rows($queryPortofolio) == 0) {
    // jika belum ada portofolio
    echo "
        <tr>
            <td colspan='5' class='text-center'>
                Belum ada portofolio.
            </td>
        </tr>
    ";
} else {

    // nomor urut tabel
    $no = 1;

    // looping data portofolio
    while ($p = mysqli_fetch_assoc($queryPortofolio)) {
?>
                    <tr>
                        <!-- nomor -->
                        <td class="text-center">
                            <?= $no++ ?>
                        </td>

                        <!-- gambar -->
                        <td class="text-center">
                            <?php if ($p['gambar']) { ?>
                                <img
                                    src="../uploads/<?= htmlspecialchars($p['gambar']) ?>"
                                    class="preview"
                                >
                            <?php } else { ?>
                                -
                            <?php } ?>
                        </td>

                        <!-- judul -->
                        <td>
                            <?= htmlspecialchars($p['judul']) ?>
                        </td>

                        <!-- repository -->
                        <td class="text-center">
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

                        <!-- aksi -->
                        <td class="text-center">
                            <a
                                href="portofolio_detail.php?id=<?= $p['id_portofolio'] ?>"
                                class="btn btn-outline-primary btn-sm"
                            >
                                Edit
                            </a>

                            <a
                                href="portofolio_detail.php?mode=hapus&id=<?= $p['id_portofolio'] ?>"
                                onclick="return confirm('Hapus portofolio?')"
                                class="btn btn-outline-danger btn-sm"
                            >
                                Hapus
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
