```php
<?php
// ===============================
// PORTOFOLIO MAHASISWA (VERSI PEMULA)
// Dibuat dengan gaya mahasiswa semester 1
// Tanpa konsep rumit, sesuai modul dasar PHP
// ===============================

session_start();
include "koneksi.php";

// Cek login mahasiswa
if (!isset($_SESSION['id_mahasiswa'])) {
    header("Location: home.html");
    exit;
}

$id_mahasiswa = $_SESSION['id_mahasiswa'];
$nama = $_SESSION['nama'];

// Ambil mode (tambah, lihat, edit, hapus)
$mode = isset($_GET['mode']) ? $_GET['mode'] : "";

// ===============================
// FUNGSI UPLOAD GAMBAR (SANGAT SEDERHANA)
// ===============================
function upload_gambar() {
    if ($_FILES['gambar']['name'] == "") {
        return "";
    }

    $folder = "uploads/";
    if (!is_dir($folder)) {
        mkdir($folder);
    }

    $nama_file = time() . $_FILES['gambar']['name'];
    move_uploaded_file($_FILES['gambar']['tmp_name'], $folder . $nama_file);
    return $nama_file;
}

// ===============================
// TAMBAH DATA
// ===============================
if ($mode == "tambah" && $_SERVER['REQUEST_METHOD'] == "POST") {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $repo = $_POST['repo_link'];
    $gambar = upload_gambar();

    $sql = "INSERT INTO portofolio VALUES ('', '$id_mahasiswa', '$judul', '$deskripsi', '$gambar', '$repo')";
    mysqli_query($koneksi, $sql);

    header("Location: portofolio_pemula.php");
    exit;
}

// ===============================
// HAPUS DATA
// ===============================
if ($mode == "hapus") {
    $id = $_GET['id'];

    // Ambil nama gambar
    $q = mysqli_query($koneksi, "SELECT gambar FROM portofolio WHERE id_portofolio='$id'");
    $data = mysqli_fetch_assoc($q);

    if ($data['gambar'] != "") {
        unlink("uploads/" . $data['gambar']);
    }

    mysqli_query($koneksi, "DELETE FROM portofolio WHERE id_portofolio='$id'");
    header("Location: portofolio_pemula.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Portofolio Mahasiswa</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>
<body class="container mt-4">

<h3>Halo, <?php echo $nama; ?></h3>

<!-- =============================== -->
<!-- FORM TAMBAH -->
<!-- =============================== -->
<h4>Tambah Portofolio</h4>
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
        <label>Gambar</label>
        <input type="file" name="gambar" class="form-control">
    </div>

    <div class="mb-2">
        <label>Link Repository</label>
        <input type="text" name="repo_link" class="form-control">
    </div>

    <button class="btn btn-primary">Simpan</button>
</form>

<hr>

<!-- =============================== -->
<!-- TAMPIL DATA -->
<!-- =============================== -->
<h4>Daftar Portofolio</h4>

<?php
$q = mysqli_query($koneksi, "SELECT * FROM portofolio WHERE id_mahasiswa='$id_mahasiswa'");
while ($p = mysqli_fetch_assoc($q)) {
?>
    <div class="card mb-3" style="padding:10px;">
        <h5><?php echo $p['judul']; ?></h5>
        <p><?php echo $p['deskripsi']; ?></p>

        <?php if ($p['gambar'] != "") { ?>
            <img src="uploads/<?php echo $p['gambar']; ?>" width="200">
        <?php } ?>

        <p>
            <a href="<?php echo $p['repo_link']; ?>" target="_blank">Repository</a>
        </p>

        <a href="?mode=hapus&id=<?php echo $p['id_portofolio']; ?>" onclick="return confirm('Hapus data?')" class="btn btn-danger btn-sm">Hapus</a>
    </div>
<?php } ?>

</body>
</html>
```
