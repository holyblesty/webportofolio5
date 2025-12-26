<?php
/*
  Nama File   : portofolio_detail.php
  Deskripsi   : Halaman mahasiswa untuk menambah, mengedit, dan menghapus data portofolio
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

// folder upload gambar
$folderUpload = "../uploads/";

// =========================
// MODE HAPUS PORTOFOLIO
// =========================

// cek apakah mode hapus dipanggil
if (
    isset($_GET['mode']) &&
    $_GET['mode'] == "hapus" &&
    isset($_GET['id'])
) {

    // mengambil id portofolio
    $idPortofolio = $_GET['id'];

    // memastikan portofolio milik mahasiswa yang login
    $queryCek = mysqli_query(
        $koneksi,
        "SELECT gambar
         FROM portofolio
         WHERE id_portofolio='$idPortofolio'
         AND id_mahasiswa='$idMahasiswa'"
    );

    // mengambil hasil query
    $dataPortofolio = mysqli_fetch_assoc($queryCek);

    // jika data ditemukan
    if ($dataPortofolio) {

        // hapus file gambar jika ada
        if (
            $dataPortofolio['gambar'] != "" &&
            file_exists($folderUpload . $dataPortofolio['gambar'])
        ) {
            unlink($folderUpload . $dataPortofolio['gambar']);
        }

        // hapus data portofolio dari database
        mysqli_query(
            $koneksi,
            "DELETE FROM portofolio
             WHERE id_portofolio='$idPortofolio'
             AND id_mahasiswa='$idMahasiswa'"
        );
    }

    // kembali ke dashboard mahasiswa
    header("Location: dashboard_mhs.php");
    exit;
}

// =========================
// MODE EDIT (AMBIL DATA)
// =========================

// penanda mode edit
$edit = false;

// variabel data portofolio
$data = null;

// cek apakah parameter id ada tanpa mode hapus
if (isset($_GET['id']) && !isset($_GET['mode'])) {

    // aktifkan mode edit
    $edit = true;

    // mengambil id portofolio
    $idPortofolio = $_GET['id'];

    // query data portofolio mahasiswa
    $queryPortofolio = mysqli_query(
        $koneksi,
        "SELECT *
         FROM portofolio
         WHERE id_portofolio='$idPortofolio'
         AND id_mahasiswa='$idMahasiswa'"
    );

    // jika data tidak ditemukan
    if (mysqli_num_rows($queryPortofolio) == 0) {
        header("Location: dashboard_mhs.php");
        exit;
    }

    // mengambil data portofolio
    $data = mysqli_fetch_assoc($queryPortofolio);
}

// =========================
// PROSES SIMPAN DATA
// (TAMBAH / EDIT)
// =========================

// cek apakah form dikirim
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // mengambil data dari form
    $judul     = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $repoLink  = $_POST['repo_link'];
    $gambar    = "";

    // =====================
    // MODE EDIT DATA
    // =====================
    if (isset($_POST['id_portofolio'])) {

        // mengambil id portofolio
        $idPortofolio = $_POST['id_portofolio'];

        // gambar lama
        $gambar = $_POST['gambar_lama'];

        // jika upload gambar baru
        if (!empty($_FILES['gambar']['name'])) {

            // hapus gambar lama jika ada
            if (
                $gambar != "" &&
                file_exists($folderUpload . $gambar)
            ) {
                unlink($folderUpload . $gambar);
            }

            // generate nama file baru
            $namaFile = time() . "_" . $_FILES['gambar']['name'];

            // pindahkan file ke folder upload
            move_uploaded_file(
                $_FILES['gambar']['tmp_name'],
                $folderUpload . $namaFile
            );

            // simpan nama gambar
            $gambar = $namaFile;
        }

        // update data portofolio
        mysqli_query(
            $koneksi,
            "UPDATE portofolio SET
                judul='$judul',
                deskripsi='$deskripsi',
                gambar='$gambar',
                repo_link='$repoLink'
             WHERE id_portofolio='$idPortofolio'
             AND id_mahasiswa='$idMahasiswa'"
        );

    }
    // =====================
    // MODE TAMBAH DATA
    // =====================
    else {

        // jika upload gambar
        if (!empty($_FILES['gambar']['name'])) {

            // generate nama file
            $namaFile = time() . "_" . $_FILES['gambar']['name'];

            // pindahkan file ke folder upload
            move_uploaded_file(
                $_FILES['gambar']['tmp_name'],
                $folderUpload . $namaFile
            );

            // simpan nama gambar
            $gambar = $namaFile;
        }

        // insert data portofolio baru
        mysqli_query(
            $koneksi,
            "INSERT INTO portofolio
             (id_mahasiswa, judul, deskripsi, gambar, repo_link)
             VALUES
             ('$idMahasiswa', '$judul', '$deskripsi', '$gambar', '$repoLink')"
        );
    }

    // kembali ke dashboard mahasiswa
    header("Location: dashboard_mhs.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>
        <?= $edit ? "Edit" : "Tambah" ?> Portofolio
    </title>

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

        /* tombol utama */
        .btn-pink {
            background: #e11584;
            border-color: #e11584;
            color: white;
        }

        /* hover tombol utama */
        .btn-pink:hover {
            background: #c5116f;
            border-color: #c5116f;
            color: white;
        }
    </style>
</head>
<body>

<div class="container mt-4" style="max-width: 650px;">

    <!-- =========================
         CARD FORM PORTOFOLIO
    ========================= -->
    <div class="card shadow-sm">

        <!-- header card -->
        <div class="card-header card-header-pink">
            <h5 class="mb-0">
                <?= $edit ? "Edit Portofolio" : "Tambah Portofolio" ?>
            </h5>
        </div>

        <div class="card-body">

            <!-- =========================
                 FORM PORTOFOLIO
            ========================= -->
            <form method="POST" enctype="multipart/form-data">

                <!-- input tersembunyi mode edit -->
                <?php if ($edit) { ?>
                    <input
                        type="hidden"
                        name="id_portofolio"
                        value="<?= $data['id_portofolio'] ?>"
                    >
                    <input
                        type="hidden"
                        name="gambar_lama"
                        value="<?= $data['gambar'] ?>"
                    >
                <?php } ?>

                <!-- judul portofolio -->
                <div class="mb-3">
                    <label class="form-label">Judul</label>
                    <input
                        type="text"
                        name="judul"
                        class="form-control"
                        value="<?= $edit ? htmlspecialchars($data['judul']) : '' ?>"
                        required
                    >
                </div>

                <!-- deskripsi portofolio -->
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea
                        name="deskripsi"
                        class="form-control"
                        rows="4"
                        required
                    ><?= $edit ? htmlspecialchars($data['deskripsi']) : '' ?></textarea>
                </div>

                <!-- upload gambar -->
                <div class="mb-3">
                    <label class="form-label">Gambar</label>
                    <input
                        type="file"
                        name="gambar"
                        class="form-control"
                    >
                </div>

                <!-- preview gambar -->
                <?php if ($edit && $data['gambar'] != "") { ?>
                    <img
                        src="../uploads/<?= $data['gambar'] ?>"
                        width="200"
                        class="mb-3 rounded"
                    >
                <?php } ?>

                <!-- link repository -->
                <div class="mb-3">
                    <label class="form-label">Link Repository</label>
                    <input
                        type="text"
                        name="repo_link"
                        class="form-control"
                        value="<?= $edit ? htmlspecialchars($data['repo_link']) : '' ?>"
                    >
                </div>

                <!-- tombol aksi -->
                <div class="d-flex gap-2">
                    <button class="btn btn-pink flex-fill">
                        <?= $edit ? "Update" : "Simpan" ?>
                    </button>
                    <a
                        href="dashboard_mhs.php"
                        class="btn btn-secondary flex-fill"
                    >
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>

</body>
</html>
