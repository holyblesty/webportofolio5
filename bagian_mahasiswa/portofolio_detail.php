<!-- 
=========================================================
  Nama File   : aplikasi-pengumuman-akademik-online.html
  Deskripsi   : Halaman portofolio Projek PBL
                Sistem Aplikasi Pengumuman Akademik Online
  Pembuat     : Jesina HolyBlesty Simatupang (3312511017)
              : Vivian Sarah Diva Alisianoi (3312511018)
  Tanggal     : 19 Oktober 2025
=========================================================
-->

<?php
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
$folderUpload = "../uploads/";

/* =========================
   MODE HAPUS PORTOFOLIO
========================= */
if (
    isset($_GET['mode']) &&
    $_GET['mode'] == "hapus" &&
    isset($_GET['id'])
) {

    $idPortofolio = $_GET['id'];

    $queryCek = mysqli_query(
        $koneksi,
        "SELECT gambar
         FROM portofolio
         WHERE id_portofolio='$idPortofolio'
         AND id_mahasiswa='$idMahasiswa'"
    );

    $dataPortofolio = mysqli_fetch_assoc($queryCek);

    if ($dataPortofolio) {
        if (
            $dataPortofolio['gambar'] != "" &&
            file_exists($folderUpload . $dataPortofolio['gambar'])
        ) {
            unlink($folderUpload . $dataPortofolio['gambar']);
        }

        mysqli_query(
            $koneksi,
            "DELETE FROM portofolio
             WHERE id_portofolio='$idPortofolio'
             AND id_mahasiswa='$idMahasiswa'"
        );
    }

    header("Location: dashboard_mhs.php");
    exit;
}

/* =========================
   MODE EDIT
========================= */
$edit = false;
$data = null;

if (isset($_GET['id']) && !isset($_GET['mode'])) {

    $edit = true;
    $idPortofolio = $_GET['id'];

    $queryPortofolio = mysqli_query(
        $koneksi,
        "SELECT *
         FROM portofolio
         WHERE id_portofolio='$idPortofolio'
         AND id_mahasiswa='$idMahasiswa'"
    );

    if (mysqli_num_rows($queryPortofolio) == 0) {
        header("Location: dashboard_mhs.php");
        exit;
    }

    $data = mysqli_fetch_assoc($queryPortofolio);
}

/* =========================
   PROSES SIMPAN DATA
========================= */
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $judul     = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $repoLink  = $_POST['repo_link'];
    $gambar    = "";

    if (isset($_POST['id_portofolio'])) {

        $idPortofolio = $_POST['id_portofolio'];
        $gambar = $_POST['gambar_lama'];

        if (!empty($_FILES['gambar']['name'])) {

            if ($gambar != "" && file_exists($folderUpload . $gambar)) {
                unlink($folderUpload . $gambar);
            }

            $namaFile = time() . "_" . $_FILES['gambar']['name'];
            move_uploaded_file(
                $_FILES['gambar']['tmp_name'],
                $folderUpload . $namaFile
            );

            $gambar = $namaFile;
        }

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

    } else {

        if (!empty($_FILES['gambar']['name'])) {
            $namaFile = time() . "_" . $_FILES['gambar']['name'];
            move_uploaded_file(
                $_FILES['gambar']['tmp_name'],
                $folderUpload . $namaFile
            );
            $gambar = $namaFile;
        }

        mysqli_query(
            $koneksi,
            "INSERT INTO portofolio
             (id_mahasiswa, judul, deskripsi, gambar, repo_link)
             VALUES
             ('$idMahasiswa', '$judul', '$deskripsi', '$gambar', '$repoLink')"
        );
    }

    header("Location: dashboard_mhs.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= $edit ? "Edit" : "Tambah" ?> Portofolio</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f0f4ff;
        }

        .card-header-blue {
            background: #0041C2;
            color: white;
        }

        .btn-blue {
            background: #0041C2;
            border-color: #0041C2;
            color: white;
        }

        .btn-blue:hover {
            background: #003399;
            border-color: #003399;
            color: white;
        }
    </style>
</head>
<body>

<div class="container mt-4" style="max-width: 650px;">

    <div class="card shadow-sm">

        <div class="card-header card-header-blue">
            <h5 class="mb-0">
                <?= $edit ? "Edit Portofolio" : "Tambah Portofolio" ?>
            </h5>
        </div>

        <div class="card-body">

            <form method="POST" enctype="multipart/form-data">

                <?php if ($edit) { ?>
                    <input type="hidden" name="id_portofolio" value="<?= $data['id_portofolio'] ?>">
                    <input type="hidden" name="gambar_lama" value="<?= $data['gambar'] ?>">
                <?php } ?>

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

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea
                        name="deskripsi"
                        class="form-control"
                        rows="4"
                        required
                    ><?= $edit ? htmlspecialchars($data['deskripsi']) : '' ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Gambar</label>
                    <input type="file" name="gambar" class="form-control">
                </div>

                <?php if ($edit && $data['gambar'] != "") { ?>
                    <img
                        src="../uploads/<?= $data['gambar'] ?>"
                        width="200"
                        class="mb-3 rounded"
                    >
                <?php } ?>

                <div class="mb-3">
                    <label class="form-label">Link Repository</label>
                    <input
                        type="text"
                        name="repo_link"
                        class="form-control"
                        value="<?= $edit ? htmlspecialchars($data['repo_link']) : '' ?>"
                    >
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-blue flex-fill">
                        <?= $edit ? "Update" : "Simpan" ?>
                    </button>
                    <a href="dashboard_mhs.php" class="btn btn-secondary flex-fill">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>

</body>
</html>
