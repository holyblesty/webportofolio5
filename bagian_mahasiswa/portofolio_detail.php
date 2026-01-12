<?php
/*
=========================================================
  Nama File   : portofolio_detail.php
  Deskripsi   : Kelola (tambah, edit, hapus) portofolio mahasiswa
  Pembuat     : Jesina HolyBlesty Simatupang (3312511017)
              : Vivian Sarah Diva Alisianoi (3312511018)
  Tanggal     : 19 Oktober 2025
=========================================================
*/

// =========================
// SESSION & KONEKSI
// =========================
session_set_cookie_params(0);
session_start();
include "../koneksi.php";

// =========================
// CEK LOGIN MAHASISWA
// =========================
if (!isset($_SESSION['id_mahasiswa']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../index.php");
    exit;
}

$idMahasiswa  = $_SESSION['id_mahasiswa'];
$folderUpload = "../uploads/";

/* =========================
   FUNGSI HAPUS PORTOFOLIO
========================= */
function hapusPortofolio($koneksi, $idPortofolio, $idMahasiswa, $folderUpload)
{
    try {
        // cek data portofolio
        $cek = mysqli_query(
            $koneksi,
            "SELECT gambar FROM portofolio
             WHERE id_portofolio='$idPortofolio'
             AND id_mahasiswa='$idMahasiswa'"
        );

        if (!$cek) {
            return false;
        }

        $data = mysqli_fetch_assoc($cek);

        // hapus file gambar jika ada
        if ($data && $data['gambar'] != "" && file_exists($folderUpload . $data['gambar'])) {
            unlink($folderUpload . $data['gambar']);
        }

        // hapus data dari database
        return mysqli_query(
            $koneksi,
            "DELETE FROM portofolio
             WHERE id_portofolio='$idPortofolio'
             AND id_mahasiswa='$idMahasiswa'"
        );

    } catch (Exception $e) {
        return false;
    }
}

/* =========================
   FUNGSI SIMPAN / UPDATE PORTOFOLIO
========================= */
function simpanPortofolio($koneksi, $post, $file, $idMahasiswa, $folderUpload)
{
    try {
        $judul     = $post['judul'];
        $deskripsi = $post['deskripsi'];
        $repoLink  = $post['repo_link'];
        $gambar    = $post['gambar_lama'] ?? "";

        // proses upload gambar
        if (!empty($file['gambar']['name'])) {

            if ($gambar != "" && file_exists($folderUpload . $gambar)) {
                unlink($folderUpload . $gambar);
            }

            $namaFile = time() . "_" . $file['gambar']['name'];
            move_uploaded_file(
                $file['gambar']['tmp_name'],
                $folderUpload . $namaFile
            );

            $gambar = $namaFile;
        }

        // jika edit
        if (isset($post['id_portofolio'])) {
            return mysqli_query(
                $koneksi,
                "UPDATE portofolio SET
                    judul='$judul',
                    deskripsi='$deskripsi',
                    gambar='$gambar',
                    repo_link='$repoLink'
                 WHERE id_portofolio='{$post['id_portofolio']}'
                 AND id_mahasiswa='$idMahasiswa'"
            );
        }

        // jika tambah
        return mysqli_query(
            $koneksi,
            "INSERT INTO portofolio
             (id_mahasiswa, judul, deskripsi, gambar, repo_link)
             VALUES
             ('$idMahasiswa', '$judul', '$deskripsi', '$gambar', '$repoLink')"
        );

    } catch (Exception $e) {
        return false;
    }
}

/* =========================
   MODE HAPUS
========================= */
if (isset($_GET['mode'], $_GET['id']) && $_GET['mode'] === "hapus") {
    hapusPortofolio($koneksi, $_GET['id'], $idMahasiswa, $folderUpload);
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

    $q = mysqli_query(
        $koneksi,
        "SELECT * FROM portofolio
         WHERE id_portofolio='$idPortofolio'
         AND id_mahasiswa='$idMahasiswa'"
    );

    if (mysqli_num_rows($q) === 0) {
        header("Location: dashboard_mhs.php");
        exit;
    }

    $data = mysqli_fetch_assoc($q);
}

/* =========================
   PROSES SIMPAN
========================= */
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    simpanPortofolio($koneksi, $_POST, $_FILES, $idMahasiswa, $folderUpload);
    header("Location: dashboard_mhs.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= $edit ? "Edit" : "Tambah" ?> Portofolio</title>

<!-- Bootstrap CSS -->
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
<input type="text" name="judul" class="form-control"
       value="<?= $edit ? htmlspecialchars($data['judul']) : '' ?>" required>
</div>

<div class="mb-3">
<label class="form-label">Deskripsi</label>
<textarea name="deskripsi" class="form-control" rows="4" required><?= $edit ? htmlspecialchars($data['deskripsi']) : '' ?></textarea>
</div>

<div class="mb-3">
<label class="form-label">Gambar</label>
<input type="file" name="gambar" class="form-control">
</div>

<?php if ($edit && $data['gambar'] != "") { ?>
<img src="../uploads/<?= $data['gambar'] ?>" width="200" class="mb-3 rounded">
<?php } ?>

<div class="mb-3">
<label class="form-label">Link Repository</label>
<input type="text" name="repo_link" class="form-control"
       value="<?= $edit ? htmlspecialchars($data['repo_link']) : '' ?>">
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