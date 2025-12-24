<?php
// =====================================
// PORTOFOLIO MAHASISWA (DETAIL & CRUD)
// UPLOAD SESUAI MODUL MENGOLAH FILE
// =====================================

session_start();
include "../koneksi.php";

// Cek login & role mahasiswa
if (!isset($_SESSION['id_mahasiswa']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../index.php");
    exit;
}

$id_mahasiswa = $_SESSION['id_mahasiswa'];

// Ambil nama mahasiswa
$qNama = mysqli_query($koneksi, "SELECT nama FROM mahasiswa WHERE id_mahasiswa='$id_mahasiswa'");
$dataNama = mysqli_fetch_assoc($qNama);
$nama = $dataNama['nama'];

$mode = isset($_GET['mode']) ? $_GET['mode'] : "";

// =====================================
// FUNGSI UPLOAD GAMBAR (SESUAI MODUL)
// =====================================
function upload_gambar() {

    // Jika tidak upload gambar
    if ($_FILES['gambar']['name'] == "") {
        return "";
    }

    $nama_file = $_FILES['gambar']['name'];
    $tmp       = $_FILES['gambar']['tmp_name'];
    $size      = $_FILES['gambar']['size'];

    // Ambil ekstensi
    $ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
    $ext_valid = ['jpg', 'jpeg', 'png'];

    // Validasi ekstensi
    if (!in_array($ext, $ext_valid)) {
        echo "<script>alert('Format file harus JPG, JPEG, atau PNG');</script>";
        return "";
    }

    // Validasi ukuran (max 2MB)
    if ($size > 2000000) {
        echo "<script>alert('Ukuran file maksimal 2 MB');</script>";
        return "";
    }

    // Folder upload
    $folder = "../uploads/";
    if (!is_dir($folder)) {
        mkdir($folder);
    }

    // Nama file baru
    $nama_baru = time() . "_" . $nama_file;

    // Pindahkan file
    move_uploaded_file($tmp, $folder . $nama_baru);

    return $nama_baru;
}

// =====================================
// TAMBAH PORTOFOLIO
// =====================================
if ($mode === "tambah" && $_SERVER['REQUEST_METHOD'] === "POST") {

    $judul     = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $repo      = mysqli_real_escape_string($koneksi, $_POST['repo_link']);
    $gambar    = upload_gambar();

    mysqli_query(
        $koneksi,
        "INSERT INTO portofolio 
        (id_mahasiswa, judul, deskripsi, gambar, repo_link)
        VALUES
        ('$id_mahasiswa', '$judul', '$deskripsi', '$gambar', '$repo')"
    );

    header("Location: portofolio_detail.php");
    exit;
}

// =====================================
// HAPUS PORTOFOLIO (HANYA MILIK SENDIRI)
// =====================================
if ($mode === "hapus" && isset($_GET['id'])) {

    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    $q = mysqli_query(
        $koneksi,
        "SELECT gambar FROM portofolio 
         WHERE id_portofolio='$id' 
         AND id_mahasiswa='$id_mahasiswa'"
    );

    if (mysqli_num_rows($q) == 0) {
        echo "<script>alert('Akses ditolak'); window.location='portofolio_detail.php';</script>";
        exit;
    }

    $data = mysqli_fetch_assoc($q);

    if ($data['gambar'] != "") {
        unlink("../uploads/" . $data['gambar']);
    }

    mysqli_query(
        $koneksi,
        "DELETE FROM portofolio 
         WHERE id_portofolio='$id' 
         AND id_mahasiswa='$id_mahasiswa'"
    );

    header("Location: portofolio_detail.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Portofolio Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

    <h3>Halo, <?= htmlspecialchars($nama) ?></h3>
    <a href="dashboard_mhs.php" class="btn btn-secondary btn-sm mb-3">← Kembali</a>

    <!-- FORM TAMBAH -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            Tambah Portofolio
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" action="?mode=tambah">
                <div class="mb-2">
                    <label>Judul</label>
                    <input type="text" name="judul" class="form-control" required>
                </div>

                <div class="mb-2">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" required></textarea>
                </div>

                <div class="mb-2">
                    <label>Gambar (JPG / PNG)</label>
                    <input type="file" name="gambar" class="form-control">
                </div>

                <div class="mb-2">
                    <label>Link Repository</label>
                    <input type="text" name="repo_link" class="form-control">
                </div>

                <button class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    <!-- DAFTAR -->
    <h4>Portofolio Saya</h4>

<?php
$q = mysqli_query(
    $koneksi,
    "SELECT * FROM portofolio 
     WHERE id_mahasiswa='$id_mahasiswa'
     ORDER BY id_portofolio DESC"
);

if (mysqli_num_rows($q) == 0) {
    echo "<div class='alert alert-info'>Belum ada portofolio.</div>";
}

while ($p = mysqli_fetch_assoc($q)) {
?>
    <div class="card mb-3">
        <div class="card-body">
            <h5><?= htmlspecialchars($p['judul']) ?></h5>
            <p><?= htmlspecialchars($p['deskripsi']) ?></p>

            <?php if ($p['gambar'] != "") { ?>
                <img src="../uploads/<?= $p['gambar'] ?>" width="200" class="mb-2">
            <?php } ?>

            <?php if ($p['repo_link'] != "") { ?>
                <p>
                    <a href="<?= htmlspecialchars($p['repo_link']) ?>" target="_blank">
                        Lihat Repository
                    </a>
                </p>
            <?php } ?>

            <a href="?mode=hapus&id=<?= $p['id_portofolio'] ?>"
               onclick="return confirm('Yakin hapus?')"
               class="btn btn-danger btn-sm">
               Hapus
            </a>
        </div>
    </div>
<?php } ?>

</div>

</body>
</html>
