<!-- 
=========================================================
  Nama File   : proses_nilai.php
  Deskripsi   : Langkah pembuatan nilai dan catatan bagian dosen
  Pembuat     : Jesina HolyBlesty Simatupang (3312511017)
              : Vivian Sarah Diva Alisianoi (3312511018)
  Tanggal     : 19 Oktober 2025
=========================================================
-->

<?php
// =========================
// SESSION & KONEKSI
// =========================

// session hanya aktif selama browser terbuka
session_set_cookie_params(0);

// memulai session
session_start();

// memanggil koneksi database
include "../koneksi.php";

// =========================
// CEK LOGIN DOSEN
// =========================

// memastikan user login sebagai dosen
if (!isset($_SESSION['id_dosen']) || $_SESSION['role'] !== 'dosen') {
    header("Location: ../index.php");
    exit;
}

// mengambil id dosen dari session
$idDosen = $_SESSION['id_dosen'];

// =========================
// SIMPAN / UPDATE NILAI
// =========================

// mengecek apakah form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // mengambil data dari form
    $idPortofolio = mysqli_real_escape_string($koneksi, $_POST['id_portofolio']);
    $nilai        = mysqli_real_escape_string($koneksi, $_POST['nilai']);   // bintang 1–5
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

    // jika sudah ada → UPDATE
    if (mysqli_num_rows($cek) > 0) {

        mysqli_query(
            $koneksi,
            "UPDATE nilai 
             SET nilai='$nilai', catatan='$catatan'
             WHERE id_portofolio='$idPortofolio' 
             AND id_dosen='$idDosen'"
        );

        $_SESSION['success'] = "Nilai berhasil diperbarui.";

    } else {
        // jika belum ada → INSERT
        mysqli_query(
            $koneksi,
            "INSERT INTO nilai (id_portofolio, id_dosen, nilai, catatan)
             VALUES ('$idPortofolio', '$idDosen', '$nilai', '$catatan')"
        );

        $_SESSION['success'] = "Nilai berhasil disimpan.";
    }

    // kembali ke halaman dosen
    header("Location: portofolio_dsn.php");
    exit;
}

// =========================
// AMBIL DATA PORTOFOLIO
// =========================

// cek apakah id_portofolio dikirim
if (!isset($_GET['id_portofolio'])) {
    echo "ID portofolio tidak ditemukan.";
    exit;
}

// ambil id portofolio dari URL
$idPortofolio = mysqli_real_escape_string($koneksi, $_GET['id_portofolio']);

// query data portofolio & mahasiswa
$dataPortofolio = mysqli_query(
    $koneksi,
    "SELECT p.*, m.nama 
     FROM portofolio p
     JOIN mahasiswa m 
       ON m.id_mahasiswa = p.id_mahasiswa
     WHERE p.id_portofolio='$idPortofolio'"
);

// simpan data portofolio
$portofolio = mysqli_fetch_assoc($dataPortofolio);

// cek apakah dosen sudah memberi nilai
$queryNilai = mysqli_query(
    $koneksi,
    "SELECT * 
     FROM nilai 
     WHERE id_portofolio='$idPortofolio' 
     AND id_dosen='$idDosen'"
);

// simpan nilai jika ada
$nilaiData = mysqli_fetch_assoc($queryNilai);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">

<!-- Judul halaman -->
<title><?= $nilaiData ? "Edit Nilai" : "Beri Nilai" ?></title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* =========================
   STYLE HALAMAN
========================= */

body {
    background:#f4f6fb;
}

/* Header card */
.card-header {
    background:#0041C2;
    color:white;
}

/* Star rating */
.star-rating {
    direction: rtl;
    display: inline-flex;
    font-size: 2rem;
}

/* sembunyikan radio */
.star-rating input {
    display: none;
}

/* warna default bintang */
.star-rating label {
    color: #ccc;
    cursor: pointer;
}

/* warna aktif */
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

<!-- HEADER -->
<div class="card-header">
    <?= $nilaiData ? "Edit Nilai Proyek" : "Beri Nilai Proyek" ?>
</div>

<div class="card-body">

<!-- FORM NILAI -->
<form method="POST">

<!-- id portofolio (hidden) -->
<input type="hidden" name="id_portofolio" value="<?= $portofolio['id_portofolio'] ?>">

<!-- Nama mahasiswa -->
<div class="mb-3">
<label class="form-label">Nama Mahasiswa</label>
<input type="text" class="form-control"
       value="<?= htmlspecialchars($portofolio['nama']) ?>" readonly>
</div>

<!-- Judul proyek -->
<div class="mb-3">
<label class="form-label">Judul Proyek</label>
<input type="text" class="form-control"
       value="<?= htmlspecialchars($portofolio['judul']) ?>" readonly>
</div>

<!-- NILAI BERUPA BINTANG -->
<div class="mb-3">
<label class="form-label">Nilai</label><br>

<div class="star-rating">
<?php
// nilai saat ini (jika edit)
$nilaiSekarang = $nilaiData['nilai'] ?? 0;

// loop bintang 5 sampai 1
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

<!-- Catatan dosen -->
<div class="mb-3">
<label class="form-label">Catatan</label>
<textarea name="catatan" class="form-control" rows="4">
<?= $nilaiData['catatan'] ?? '' ?>
</textarea>
</div>

<!-- Tombol simpan -->
<button class="btn btn-primary w-100">
<?= $nilaiData ? "Perbarui Nilai" : "Simpan Nilai" ?>
</button>

</form>

<!-- Link kembali -->
<div class="text-center mt-3">
<a href="portofolio_dsn.php">← Kembali</a>
</div>

</div>
</div>
</div>

</body>
</html>
