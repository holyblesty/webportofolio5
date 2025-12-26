<?php
/*
  Nama File   : proses_nilai.php
  Deskripsi   : Halaman dosen untuk memberi dan mengedit nilai portofolio mahasiswa
  Pembuat    : Vivian Sarah Diva Alisianoi & Jesina Holyblesty Simatupang
  Tanggal    : 26 Desember 2025
*/

// session hanya aktif selama browser terbuka
session_set_cookie_params(0);
// memulai session
session_start();
// memanggil koneksi database
include "../koneksi.php";

// cek login dosen
if (!isset($_SESSION['id_dosen']) || $_SESSION['role'] !== 'dosen') {
    // jika belum login atau bukan dosen, kembali ke index
    header("Location: ../index.php");
    exit;
}

// mengambil id dosen dari session
$idDosen = $_SESSION['id_dosen'];

// =========================
// SIMPAN / UPDATE NILAI
// =========================

// cek apakah form dikirim dengan method POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // mengambil data dari form
    $idPortofolio = mysqli_real_escape_string($koneksi, $_POST['id_portofolio']);
    $nilai        = mysqli_real_escape_string($koneksi, $_POST['nilai']);
    $catatan      = mysqli_real_escape_string($koneksi, $_POST['catatan']);

    // validasi nilai (0–100)
    if ($nilai < 0 || $nilai > 100) {
        // jika nilai tidak valid
        $_SESSION['error'] = "Nilai harus antara 0–100.";
        header("Location: portofolio_dsn.php");
        exit;
    }

    // cek apakah nilai sudah pernah disimpan
    $cekNilai = mysqli_query(
        $koneksi,
        "SELECT id_nilai 
         FROM nilai 
         WHERE id_portofolio='$idPortofolio' 
         AND id_dosen='$idDosen'"
    );

    // jika nilai sudah ada → update
    if (mysqli_num_rows($cekNilai) > 0) {

        // update nilai dan catatan
        mysqli_query(
            $koneksi,
            "UPDATE nilai 
             SET nilai='$nilai', catatan='$catatan'
             WHERE id_portofolio='$idPortofolio' 
             AND id_dosen='$idDosen'"
        );

        // pesan sukses
        $_SESSION['success'] = "Nilai berhasil diperbarui.";

    } 
    // jika nilai belum ada → insert
    else {

        // simpan nilai baru
        mysqli_query(
            $koneksi,
            "INSERT INTO nilai (id_portofolio, id_dosen, nilai, catatan)
             VALUES ('$idPortofolio', '$idDosen', '$nilai', '$catatan')"
        );

        // pesan sukses
        $_SESSION['success'] = "Nilai berhasil disimpan.";
    }

    // kembali ke halaman portofolio
    header("Location: portofolio_dsn.php");
    exit;
}

// =========================
// AMBIL DATA PORTOFOLIO
// =========================

// cek parameter id_portofolio
if (!isset($_GET['id_portofolio'])) {
    // jika tidak ada id
    echo "ID portofolio tidak ditemukan.";
    exit;
}

// mengambil id portofolio dari URL
$idPortofolio = mysqli_real_escape_string($koneksi, $_GET['id_portofolio']);

// query data portofolio + mahasiswa
$dataPortofolio = mysqli_query(
    $koneksi,
    "SELECT p.*, m.nama 
     FROM portofolio p
     JOIN mahasiswa m 
         ON m.id_mahasiswa = p.id_mahasiswa
     WHERE p.id_portofolio='$idPortofolio'"
);

// mengambil hasil query
$portofolio = mysqli_fetch_assoc($dataPortofolio);

// query nilai jika sudah ada
$queryNilai = mysqli_query(
    $koneksi,
    "SELECT * 
     FROM nilai 
     WHERE id_portofolio='$idPortofolio' 
     AND id_dosen='$idDosen'"
);

// mengambil data nilai
$nilaiData = mysqli_fetch_assoc($queryNilai);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>
        <?= $nilaiData ? "Edit Nilai" : "Beri Nilai" ?>
    </title>

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        /* warna latar belakang */
        body {
            background: #f9f0f5;
        }

        /* header kartu */
        .bg-pink {
            background: #e11584;
            color: white;
        }

        /* tombol simpan */
        .btn-pink {
            background: #e11584;
            border-color: #e11584;
            color: white;
        }

        /* hover tombol simpan */
        .btn-pink:hover {
            background: #c5116f;
            border-color: #c5116f;
            color: white;
        }

        /* link kembali */
        .back-link {
            color: #e11584;
            text-decoration: none;
        }

        /* hover link kembali */
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="container mt-5" style="max-width: 600px;">
    <div class="card shadow-sm">

        <!-- header halaman -->
        <div class="card-header bg-pink">
            <?= $nilaiData ? "Edit Nilai Portofolio" : "Beri Nilai Portofolio" ?>
        </div>

        <div class="card-body">

            <!-- form input nilai -->
            <form method="POST">

                <!-- menyimpan id portofolio -->
                <input
                    type="hidden"
                    name="id_portofolio"
                    value="<?= $portofolio['id_portofolio'] ?>"
                >

                <!-- nama mahasiswa -->
                <div class="mb-3">
                    <label class="form-label">Nama Mahasiswa</label>
                    <input
                        type="text"
                        class="form-control"
                        value="<?= htmlspecialchars($portofolio['nama']) ?>"
                        readonly
                    >
                </div>

                <!-- judul proyek -->
                <div class="mb-3">
                    <label class="form-label">Judul Proyek</label>
                    <input
                        type="text"
                        class="form-control"
                        value="<?= htmlspecialchars($portofolio['judul']) ?>"
                        readonly
                    >
                </div>

                <!-- input nilai -->
                <div class="mb-3">
                    <label class="form-label">Nilai (0–100)</label>
                    <input
                        type="number"
                        name="nilai"
                        class="form-control"
                        min="0"
                        max="100"
                        value="<?= $nilaiData['nilai'] ?? '' ?>"
                        required
                    >
                </div>

                <!-- input catatan -->
                <div class="mb-3">
                    <label class="form-label">Catatan</label>
                    <textarea
                        name="catatan"
                        class="form-control"
                        rows="4"
                    ><?= $nilaiData['catatan'] ?? '' ?></textarea>
                </div>

                <!-- tombol simpan / update -->
                <button class="btn btn-pink w-100">
                    <?= $nilaiData ? "Perbarui Nilai" : "Simpan Nilai" ?>
                </button>
            </form>

            <!-- link kembali -->
            <div class="text-center mt-3">
                <a
                    href="portofolio_dsn.php"
                    class="back-link"
                >
                    ← Kembali ke Portofolio
                </a>
            </div>

        </div>
    </div>
</div>

</body>
</html>
