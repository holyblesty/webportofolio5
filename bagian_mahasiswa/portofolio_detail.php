<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['id_mahasiswa']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../index.php");
    exit;
}

$id_mahasiswa = $_SESSION['id_mahasiswa'];
$folder = "../uploads/";

/* =========================
   MODE HAPUS
========================= */
if (isset($_GET['mode']) && $_GET['mode'] == "hapus" && isset($_GET['id'])) {

    $id = $_GET['id'];

    // pastikan data milik mahasiswa ini
    $q = mysqli_query($koneksi,
        "SELECT gambar FROM portofolio 
         WHERE id_portofolio='$id' 
         AND id_mahasiswa='$id_mahasiswa'"
    );

    $data = mysqli_fetch_assoc($q);

    if ($data) {
        // hapus gambar jika ada
        if ($data['gambar'] != "" && file_exists($folder.$data['gambar'])) {
            unlink($folder.$data['gambar']);
        }

        // hapus data
        mysqli_query($koneksi,
            "DELETE FROM portofolio 
             WHERE id_portofolio='$id' 
             AND id_mahasiswa='$id_mahasiswa'"
        );
    }

    header("Location: dashboard_mhs.php");
    exit;
}

/* =========================
   MODE EDIT (AMBIL DATA)
========================= */
$edit = false;
$data = null;

if (isset($_GET['id']) && !isset($_GET['mode'])) {
    $edit = true;
    $id = $_GET['id'];

    $q = mysqli_query($koneksi,
        "SELECT * FROM portofolio 
         WHERE id_portofolio='$id' 
         AND id_mahasiswa='$id_mahasiswa'"
    );

    if (mysqli_num_rows($q) == 0) {
        header("Location: dashboard_mhs.php");
        exit;
    }

    $data = mysqli_fetch_assoc($q);
}

/* =========================
   PROSES SIMPAN (TAMBAH / EDIT)
========================= */
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $repo = $_POST['repo_link'];
    $gambar = "";

    // ===== MODE EDIT =====
    if (isset($_POST['id_portofolio'])) {

        $id_portofolio = $_POST['id_portofolio'];
        $gambar = $_POST['gambar_lama'];

        if (!empty($_FILES['gambar']['name'])) {

            if ($gambar != "" && file_exists($folder.$gambar)) {
                unlink($folder.$gambar);
            }

            $nama_file = time()."_".$_FILES['gambar']['name'];
            move_uploaded_file($_FILES['gambar']['tmp_name'], $folder.$nama_file);
            $gambar = $nama_file;
        }

        mysqli_query($koneksi,
            "UPDATE portofolio SET
                judul='$judul',
                deskripsi='$deskripsi',
                gambar='$gambar',
                repo_link='$repo'
             WHERE id_portofolio='$id_portofolio'
             AND id_mahasiswa='$id_mahasiswa'"
        );

    } 
    // ===== MODE TAMBAH =====
    else {

        if (!empty($_FILES['gambar']['name'])) {
            $nama_file = time()."_".$_FILES['gambar']['name'];
            move_uploaded_file($_FILES['gambar']['tmp_name'], $folder.$nama_file);
            $gambar = $nama_file;
        }

        mysqli_query($koneksi,
            "INSERT INTO portofolio 
             (id_mahasiswa, judul, deskripsi, gambar, repo_link)
             VALUES 
             ('$id_mahasiswa', '$judul', '$deskripsi', '$gambar', '$repo')"
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
        .btn-pink {
            background:#e11584;
            border-color:#e11584;
            color:white;
        }
        .btn-pink:hover {
            background:#c5116f;
            border-color:#c5116f;
            color:white;
        }
    </style>
</head>
<body>

<div class="container mt-4" style="max-width:650px;">

<div class="card shadow-sm">
    <div class="card-header card-header-pink">
        <h5 class="mb-0">
            <?= $edit ? "Edit Portofolio" : "Tambah Portofolio" ?>
        </h5>
    </div>

    <div class="card-body">

        <form method="POST" enctype="multipart/form-data">

        <?php if ($edit): ?>
            <input type="hidden" name="id_portofolio" value="<?= $data['id_portofolio'] ?>">
            <input type="hidden" name="gambar_lama" value="<?= $data['gambar'] ?>">
        <?php endif; ?>

        <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" name="judul" class="form-control"
                   value="<?= $edit ? htmlspecialchars($data['judul']) : '' ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="4" required><?= 
                $edit ? htmlspecialchars($data['deskripsi']) : '' ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Gambar</label>
            <input type="file" name="gambar" class="form-control">
        </div>

        <?php if ($edit && $data['gambar'] != ""): ?>
            <img src="../uploads/<?= $data['gambar'] ?>" width="200" class="mb-3 rounded">
        <?php endif; ?>

        <div class="mb-3">
            <label class="form-label">Link Repository</label>
            <input type="text" name="repo_link" class="form-control"
                   value="<?= $edit ? htmlspecialchars($data['repo_link']) : '' ?>">
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-pink flex-fill">
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
