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

// Mengatur cookie session agar berakhir saat browser ditutup
session_set_cookie_params(0);

// Memulai session PHP
session_start();

// Memanggil file koneksi database
include "../koneksi.php";

// =========================
// CEK LOGIN MAHASISWA
// =========================

// Mengecek apakah user sudah login dan memiliki role mahasiswa
if (!isset($_SESSION['id_mahasiswa']) || $_SESSION['role'] !== 'mahasiswa') {
    // Jika tidak valid, arahkan kembali ke halaman index
    header("Location: ../index.php");
    exit;
}

// Menyimpan id mahasiswa dari session ke variabel
$idMahasiswa  = $_SESSION['id_mahasiswa'];

// Menentukan folder tempat upload gambar portofolio
$folderUpload = "../uploads/";

// =========================
// KONFIGURASI UPLOAD
// =========================

// Menentukan ukuran maksimal file upload (2 MB)
define('MAX_FILE_SIZE', 2 * 1024 * 1024);

// Menentukan ekstensi file yang diperbolehkan
$allowedExt = ['jpg', 'jpeg', 'png', 'gif'];

// =========================
// ERROR MESSAGE
// =========================

// Variabel untuk menampung pesan error
$errorMessage = "";

/* =========================
   FUNGSI HAPUS PORTOFOLIO
========================= */

// Fungsi untuk menghapus data portofolio beserta file gambarnya
function hapusPortofolio($koneksi, $idPortofolio, $idMahasiswa, $folderUpload)
{
    try {
        // Query untuk mengambil nama file gambar portofolio
        $cek = mysqli_query(
            $koneksi,
            "SELECT gambar FROM portofolio
             WHERE id_portofolio='$idPortofolio'
             AND id_mahasiswa='$idMahasiswa'"
        );

        // Jika query gagal, lempar exception
        if (!$cek) {
            throw new Exception("Gagal mengambil data portofolio.");
        }

        // Mengambil hasil query dalam bentuk array asosiatif
        $data = mysqli_fetch_assoc($cek);

        // Jika gambar ada dan file benar-benar ada di folder
        if ($data && $data['gambar'] != "" && file_exists($folderUpload . $data['gambar'])) {
            // Menghapus file gambar dari folder uploads
            unlink($folderUpload . $data['gambar']);
        }

        // Menghapus data portofolio dari database
        return mysqli_query(
            $koneksi,
            "DELETE FROM portofolio
             WHERE id_portofolio='$idPortofolio'
             AND id_mahasiswa='$idMahasiswa'"
        );

    } catch (Exception $e) {
        // Jika terjadi error, fungsi mengembalikan false
        return false;
    }
}

/* =========================
   FUNGSI SIMPAN / UPDATE PORTOFOLIO
========================= */

// Fungsi untuk menyimpan data baru atau memperbarui data portofolio
function simpanPortofolio($koneksi, $post, $file, $idMahasiswa, $folderUpload)
{
    try {
        // Mengambil dan membersihkan input judul
        $judul     = trim($post['judul']);

        // Mengambil dan membersihkan input deskripsi
        $deskripsi = trim($post['deskripsi']);

        // Mengambil dan membersihkan link repository
        $repoLink  = trim($post['repo_link']);

        // Menyimpan nama gambar lama jika ada (mode edit)
        $gambar    = $post['gambar_lama'] ?? "";

        // =========================
        // PROSES UPLOAD GAMBAR
        // =========================

        // Mengecek apakah user mengupload file gambar
        if (!empty($file['gambar']['name'])) {

            // Menyimpan nama file asli
            $fileName = $file['gambar']['name'];

            // Menyimpan lokasi sementara file
            $fileTmp  = $file['gambar']['tmp_name'];

            // Menyimpan ukuran file
            $fileSize = $file['gambar']['size'];

            // Mengambil ekstensi file dan mengubah ke huruf kecil
            $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Validasi ekstensi file
            $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($fileExt, $allowedExt)) {
                throw new Exception(
                    "File tidak valid. Hanya gambar (JPG, JPEG, PNG, GIF) yang diperbolehkan."
                );
            }

            // Validasi ukuran file
            if ($fileSize > MAX_FILE_SIZE) {
                throw new Exception(
                    "Ukuran file terlalu besar. Maksimal 2 MB."
                );
            }

            // Menghapus gambar lama jika sedang mode edit
            if ($gambar != "" && file_exists($folderUpload . $gambar)) {
                unlink($folderUpload . $gambar);
            }

            // Membuat nama file baru agar unik
            $namaFile = time() . "_" . basename($fileName);

            // Memindahkan file dari temporary ke folder uploads
            if (!move_uploaded_file($fileTmp, $folderUpload . $namaFile)) {
                throw new Exception(
                    "Gagal mengupload gambar. Silakan coba lagi."
                );
            }

            // Menyimpan nama file baru ke variabel gambar
            $gambar = $namaFile;
        }

        // =========================
        // MODE EDIT
        // =========================

        // Mengecek apakah terdapat id_portofolio (edit data)
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

        // =========================
        // MODE TAMBAH
        // =========================

        // Menyimpan data portofolio baru ke database
        return mysqli_query(
            $koneksi,
            "INSERT INTO portofolio
             (id_mahasiswa, judul, deskripsi, gambar, repo_link)
             VALUES
             ('$idMahasiswa', '$judul', '$deskripsi', '$gambar', '$repoLink')"
        );

    } catch (Exception $e) {
        // Melempar kembali exception ke pemanggil fungsi
        throw $e;
    }
}

/* =========================
   MODE HAPUS
========================= */

// Mengecek apakah mode hapus dipanggil melalui parameter URL
if (isset($_GET['mode'], $_GET['id']) && $_GET['mode'] === "hapus") {
    // Memanggil fungsi hapus portofolio
    hapusPortofolio($koneksi, $_GET['id'], $idMahasiswa, $folderUpload);

    // Redirect kembali ke dashboard mahasiswa
    header("Location: dashboard_mhs.php");
    exit;
}

/* =========================
   MODE EDIT
========================= */

// Penanda apakah halaman dalam mode edit
$edit = false;

// Variabel penampung data portofolio
$data = null;

// Mengecek apakah terdapat parameter id tanpa mode hapus
if (isset($_GET['id']) && !isset($_GET['mode'])) {

    // Mengaktifkan mode edit
    $edit = true;

    // Menyimpan id portofolio
    $idPortofolio = $_GET['id'];

    // Query mengambil data portofolio
    $q = mysqli_query(
        $koneksi,
        "SELECT * FROM portofolio
         WHERE id_portofolio='$idPortofolio'
         AND id_mahasiswa='$idMahasiswa'"
    );

    // Jika data tidak ditemukan, redirect ke dashboard
    if (mysqli_num_rows($q) === 0) {
        header("Location: dashboard_mhs.php");
        exit;
    }

    // Mengambil data hasil query
    $data = mysqli_fetch_assoc($q);
}

/* =========================
   PROSES SIMPAN
========================= */

// Mengecek apakah form disubmit dengan metode POST
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        // Memanggil fungsi simpan atau update portofolio
        simpanPortofolio($koneksi, $_POST, $_FILES, $idMahasiswa, $folderUpload);

        // Redirect kembali ke dashboard setelah berhasil
        header("Location: dashboard_mhs.php");
        exit;
    } catch (Exception $e) {
        // Menyimpan pesan error ke variabel
        $errorMessage = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Encoding karakter -->
    <meta charset="UTF-8">

    <!-- Judul halaman dinamis: Edit atau Tambah Portofolio -->
    <title><?= $edit ? "Edit" : "Tambah" ?> Portofolio</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Styling tambahan halaman -->
    <style>
    body {
        background: #f0f4ff;
    }
    /* Header card berwarna biru */
    .card-header-blue {
        background: #0041C2;
        color: white;
    }
    /* Tombol utama */
    .btn-blue {
        background: #0041C2;
        border-color: #0041C2;
        color: white;
    }
    /* Hover tombol utama */
    .btn-blue:hover {
        background: #003399;
        border-color: #003399;
        color: white;
    }
    </style>
</head>

<body>

<!-- Container utama dengan lebar maksimal -->
<div class="container mt-4" style="max-width: 650px;">

    <!-- Card form -->
    <div class="card shadow-sm">

        <!-- Header card -->
        <div class="card-header card-header-blue">
            <h5 class="mb-0">
                <!-- Judul menyesuaikan mode (edit / tambah) -->
                <?= $edit ? "Edit Portofolio" : "Tambah Portofolio" ?>
            </h5>
        </div>

        <!-- Body card -->
        <div class="card-body">

            <!-- Menampilkan pesan error jika ada -->
            <?php if ($errorMessage != "") { ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($errorMessage) ?>
            </div>
            <?php } ?>

            <!-- Form input portofolio -->
            <form method="POST" enctype="multipart/form-data">

                <!-- Input tersembunyi khusus mode edit -->
                <?php if ($edit) { ?>
                    <!-- ID portofolio -->
                    <input type="hidden" name="id_portofolio" value="<?= $data['id_portofolio'] ?>">
                    <!-- Nama gambar lama -->
                    <input type="hidden" name="gambar_lama" value="<?= $data['gambar'] ?>">
                <?php } ?>

                <!-- Input judul portofolio -->
                <div class="mb-3">
                    <label class="form-label">Judul</label>
                    <input type="text" name="judul" class="form-control"
                           value="<?= $edit ? htmlspecialchars($data['judul']) : '' ?>" required>
                </div>

                <!-- Input deskripsi portofolio -->
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="4" required><?= $edit ? htmlspecialchars($data['deskripsi']) : '' ?></textarea>
                </div>

                <!-- Input upload gambar -->
                <div class="mb-3">
                    <label class="form-label">Gambar</label>
                    <input type="file" name="gambar" class="form-control">
                    <!-- Keterangan format dan ukuran file -->
                    <small class="text-muted">
                        Format: JPG, JPEG, PNG, GIF. Maksimal 2 MB.
                    </small>
                </div>

                <!-- Preview gambar lama jika mode edit -->
                <?php if ($edit && $data['gambar'] != "") { ?>
                <img src="../uploads/<?= $data['gambar'] ?>" width="200" class="mb-3 rounded">
                <?php } ?>

                <!-- Input link repository -->
                <div class="mb-3">
                    <label class="form-label">Link Repository</label>
                    <input type="text" name="repo_link" class="form-control"
                           value="<?= $edit ? htmlspecialchars($data['repo_link']) : '' ?>">
                </div>

                <!-- Tombol aksi -->
                <div class="d-flex gap-2">
                    <!-- Tombol simpan / update -->
                    <button class="btn btn-blue flex-fill">
                        <?= $edit ? "Update" : "Simpan" ?>
                    </button>

                    <!-- Tombol batal -->
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