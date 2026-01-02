<?php
/*
  Nama File   : proses_nilai.php
  Deskripsi   : Halaman dosen memberi / mengedit nilai portofolio (bintang 1–5)
*/

// session hanya aktif selama browser terbuka
session_set_cookie_params(0);
session_start();
include "../koneksi.php";

// cek login dosen
if (!isset($_SESSION['id_dosen']) || $_SESSION['role'] !== 'dosen') {
    header("Location: ../index.php");
    exit;
}

$idDosen = $_SESSION['id_dosen'];

// =========================
// SIMPAN / UPDATE NILAI
// =========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $idPortofolio = mysqli_real_escape_string($koneksi, $_POST['id_portofolio']);
    $nilai        = mysqli_real_escape_string($koneksi, $_POST['nilai']); // 1–5
    $catatan      = mysqli_real_escape_string($koneksi, $_POST['catatan']);

    // validasi nilai bintang
    if ($nilai < 1 || $nilai > 5) {
        $_SESSION['error'] = "Nilai harus antara 1 sampai 5 bintang.";
        header("Location: portofolio_dsn.php");
        exit;
    }

    // cek apakah nilai sudah ada
    $cek = mysqli_query(
        $koneksi,
        "SELECT id_nilai 
         FROM nilai 
         WHERE id_portofolio='$idPortofolio' 
         AND id_dosen='$idDosen'"
    );

    if (mysqli_num_rows($cek) > 0) {
        // update
        mysqli_query(
            $koneksi,
            "UPDATE nilai 
             SET nilai='$nilai', catatan='$catatan'
             WHERE id_portofolio='$idPortofolio' 
             AND id_dosen='$idDosen'"
        );
        $_SESSION['success'] = "Nilai berhasil diperbarui.";
    } else {
        // insert
        mysqli_query(
            $koneksi,
            "INSERT INTO nilai (id_portofolio, id_dosen, nilai, catatan)
             VALUES ('$idPortofolio', '$idDosen', '$nilai', '$catatan')"
        );
        $_SESSION['success'] = "Nilai berhasil disimpan.";
    }

    header("Location: portofolio_dsn.php");
    exit;
}

// =========================
// AMBIL DATA PORTOFOLIO
// =========================
if (!isset($_GET['id_portofolio'])) {
    echo "ID portofolio tidak ditemukan.";
    exit;
}

$idPortofolio = mysqli_real_escape_string($koneksi, $_GET['id_portofolio']);

$dataPortofolio = mysqli_query(
    $koneksi,
    "SELECT p.*, m.nama 
     FROM portofolio p
     JOIN mahasiswa m ON m.id_mahasiswa = p.id_mahasiswa
     WHERE p.id_portofolio='$idPortofolio'"
);

$portofolio = mysqli_fetch_assoc($dataPortofolio);

// ambil nilai jika ada
$queryNilai = mysqli_query(
    $koneksi,
    "SELECT * FROM nilai 
     WHERE id_portofolio='$idPortofolio' 
     AND id_dosen='$idDosen'"
);

$nilaiData = mysqli_fetch_assoc($queryNilai);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= $nilaiData ? "Edit Nilai" : "Beri Nilai" ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background:#f4f6fb; }

.card-header {
    background:#0041C2;
    color:white;
}

.star-rating {
    direction: rtl;
    display: inline-flex;
    font-size: 2rem;
}

.star-rating input {
    display: none;
}

.star-rating label {
    color: #ccc;
    cursor: pointer;
}

.star-rating input:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label {
    color: gold;
}
</style>
</head>

<body>
<div class="container mt-5" style="max-width:600px">
<div class="card shadow">

<div class="card-header">
    <?= $nilaiData ? "Edit Nilai Proyek" : "Beri Nilai Proyek" ?>
</div>

<div class="card-body">
<form method="POST">

<input type="hidden" name="id_portofolio" value="<?= $portofolio['id_portofolio'] ?>">

<div class="mb-3">
<label class="form-label">Nama Mahasiswa</label>
<input type="text" class="form-control" value="<?= htmlspecialchars($portofolio['nama']) ?>" readonly>
</div>

<div class="mb-3">
<label class="form-label">Judul Proyek</label>
<input type="text" class="form-control" value="<?= htmlspecialchars($portofolio['judul']) ?>" readonly>
</div>

<!-- NILAI BINTANG -->
<div class="mb-3">
<label class="form-label">Nilai</label><br>

<div class="star-rating">
<?php
$nilaiSekarang = $nilaiData['nilai'] ?? 0;
for ($i = 5; $i >= 1; $i--) {
    $checked = ($nilaiSekarang == $i) ? 'checked' : '';
    echo "
    <input type='radio' id='star$i' name='nilai' value='$i' $checked required>
    <label for='star$i'>★</label>
    ";
}
?>
</div>
</div>

<div class="mb-3">
<label class="form-label">Catatan</label>
<textarea name="catatan" class="form-control" rows="4"><?= $nilaiData['catatan'] ?? '' ?></textarea>
</div>

<button class="btn btn-primary w-100">
<?= $nilaiData ? "Perbarui Nilai" : "Simpan Nilai" ?>
</button>

</form>

<div class="text-center mt-3">
<a href="portofolio_dsn.php">← Kembali</a>
</div>

</div>
</div>
</div>
</body>
</html>
