<!-- 
=========================================================
  Nama File   : proses_nilai.php
  Deskripsi   : Menampilkan portofolio bagian dosen
  Pembuat     : Jesina HolyBlesty Simatupang (3312511017)
              : Vivian Sarah Diva Alisianoi (3312511018)
  Tanggal     : 19 Oktober 2025
=========================================================
-->

<?php
// =========================
// SESSION & KONEKSI DATABASE
// =========================

// memulai session
session_start();
include "../koneksi.php";

// =========================
// CEK LOGIN DOSEN
// =========================
if (!isset($_SESSION['id_dosen']) || $_SESSION['role'] !== 'dosen') {
    header("Location: ../index.php");
    exit;
}

$idDosen = $_SESSION['id_dosen'];

/* =========================
   FUNGSI SIMPAN / UPDATE NILAI
========================= */
function simpanNilai($koneksi, $idDosen, $idPortofolio, $nilai, $catatan)
{
    try {
        // validasi nilai
        if ($nilai < 1 || $nilai > 5) {
            return false;
        }

        // cek apakah nilai sudah ada
        $cek = mysqli_query(
            $koneksi,
            "SELECT id_nilai FROM nilai
             WHERE id_portofolio='$idPortofolio'
             AND id_dosen='$idDosen'"
        );

        if (!$cek) {
            return false;
        }

        // jika sudah ada → update
        if (mysqli_num_rows($cek) > 0) {
            return mysqli_query(
                $koneksi,
                "UPDATE nilai SET
                    nilai='$nilai',
                    catatan='$catatan',
                    tanggal_penilaian=NOW()
                 WHERE id_portofolio='$idPortofolio'
                 AND id_dosen='$idDosen'"
            );
        }

        // jika belum ada → insert
        return mysqli_query(
            $koneksi,
            "INSERT INTO nilai
             (id_portofolio, id_dosen, nilai, catatan, tanggal_penilaian)
             VALUES
             ('$idPortofolio', '$idDosen', '$nilai', '$catatan', NOW())"
        );

    } catch (Exception $e) {
        return false;
    }
}

/* =========================
   PROSES HAPUS NILAI
========================= */
if (isset($_GET['hapus']) && isset($_GET['id_portofolio'])) {

    $idPortofolio = $_GET['id_portofolio'];

    mysqli_query(
        $koneksi,
        "DELETE FROM nilai
         WHERE id_portofolio='$idPortofolio'
         AND id_dosen='$idDosen'"
    );

    $_SESSION['pesan'] = "Nilai berhasil dihapus";
    header("Location: portofolio_mhs.php");
    exit;
}

// =========================
// PROSES SIMPAN NILAI
// =========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $idPortofolio = $_POST['id_portofolio'];
    $nilai        = $_POST['nilai'];
    $catatan      = $_POST['catatan'];

    $hasil = simpanNilai(
        $koneksi,
        $idDosen,
        $idPortofolio,
        $nilai,
        $catatan
    );

    $_SESSION['pesan'] = $hasil
        ? "Nilai berhasil disimpan"
        : "Gagal menyimpan nilai";

    header("Location: portofolio_mhs.php");
    exit;
}

// =========================
// AMBIL DATA PORTOFOLIO
// =========================
if (!isset($_GET['id_portofolio'])) {
    echo "ID portofolio tidak ditemukan.";
    exit;
}

$idPortofolio = $_GET['id_portofolio'];

// data portofolio & mahasiswa
$qPortofolio = mysqli_query(
    $koneksi,
    "SELECT p.*, m.nama
     FROM portofolio p
     JOIN mahasiswa m ON m.id_mahasiswa = p.id_mahasiswa
     WHERE p.id_portofolio='$idPortofolio'"
);

$portofolio = mysqli_fetch_assoc($qPortofolio);

// cek nilai dosen (jika sudah ada)
$qNilai = mysqli_query(
    $koneksi,
    "SELECT * FROM nilai
     WHERE id_portofolio='$idPortofolio'
     AND id_dosen='$idDosen'"
);

$nilaiData = mysqli_fetch_assoc($qNilai);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= $nilaiData ? "Edit Nilai" : "Beri Nilai" ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f4f6fb;
}

.card-header-blue {
    background: #0041C2;
    color: white;
}

/* rating bintang */
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

<div class="container mt-5" style="max-width: 600px;">

<div class="card shadow-sm">

<div class="card-header card-header-blue">
    <?= $nilaiData ? "Edit Nilai Proyek" : "Beri Nilai Proyek" ?>
</div>

<div class="card-body">

<form method="POST">

<input type="hidden" name="id_portofolio" value="<?= $portofolio['id_portofolio'] ?>">

<!-- nama mahasiswa -->
<div class="mb-3">
<label class="form-label">Nama Mahasiswa</label>
<input type="text" class="form-control"
       value="<?= htmlspecialchars($portofolio['nama']) ?>" readonly>
</div>

<!-- judul proyek -->
<div class="mb-3">
<label class="form-label">Judul Proyek</label>
<input type="text" class="form-control"
       value="<?= htmlspecialchars($portofolio['judul']) ?>" readonly>
</div>

<!-- nilai bintang -->
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

<!-- catatan -->
<div class="mb-3">
<label class="form-label">Catatan</label>
<textarea name="catatan" class="form-control" rows="4"><?= $nilaiData['catatan'] ?? '' ?></textarea>
</div>

<button class="btn btn-success w-100 mb-2">
<?= $nilaiData ? "Perbarui Nilai" : "Simpan Nilai" ?>
</button>

<?php if ($nilaiData) { ?>
<a
    href="proses_nilai.php?hapus=1&id_portofolio=<?= $idPortofolio ?>"
    onclick="return confirm('Yakin ingin menghapus nilai ini?')"
    class="btn btn-danger w-100"
>
Hapus Nilai
</a>
<?php } ?>

</form>

<div class="text-center mt-3">
<a href="portofolio_mhs.php">← Kembali</a>
</div>

</div>
</div>
</div>

</body>
</html>
