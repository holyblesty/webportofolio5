<?php
session_start();
include "../koneksi.php";

/* =========================
   CEK LOGIN DOSEN
========================= */
if (!isset($_SESSION['id_dosen']) || $_SESSION['role'] !== 'dosen') {
    header("Location: ../index.php");
    exit;
}

$id_dosen = $_SESSION['id_dosen'];

/* =========================
   SIMPAN / UPDATE NILAI
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_portofolio = mysqli_real_escape_string($koneksi, $_POST['id_portofolio']);
    $nilai         = mysqli_real_escape_string($koneksi, $_POST['nilai']);
    $catatan       = mysqli_real_escape_string($koneksi, $_POST['catatan']);

    if ($nilai < 0 || $nilai > 100) {
        $_SESSION['error'] = "Nilai harus antara 0–100.";
        header("Location: portofolio_dsn.php");
        exit;
    }

    // cek apakah nilai sudah ada
    $cek = mysqli_query($koneksi,
        "SELECT id_nilai FROM nilai 
         WHERE id_portofolio='$id_portofolio' 
         AND id_dosen='$id_dosen'"
    );

    if (mysqli_num_rows($cek) > 0) {
        // UPDATE nilai
        mysqli_query($koneksi,
            "UPDATE nilai 
             SET nilai='$nilai', catatan='$catatan'
             WHERE id_portofolio='$id_portofolio' 
             AND id_dosen='$id_dosen'"
        );
        $_SESSION['success'] = "Nilai berhasil diperbarui.";
    } else {
        // INSERT nilai baru
        mysqli_query($koneksi,
            "INSERT INTO nilai (id_portofolio, id_dosen, nilai, catatan)
             VALUES ('$id_portofolio', '$id_dosen', '$nilai', '$catatan')"
        );
        $_SESSION['success'] = "Nilai berhasil disimpan.";
    }

    header("Location: portofolio_dsn.php");
    exit;
}

/* =========================
   AMBIL DATA PORTOFOLIO
========================= */
if (!isset($_GET['id_portofolio'])) {
    echo "ID portofolio tidak ditemukan.";
    exit;
}

$id_portofolio = mysqli_real_escape_string($koneksi, $_GET['id_portofolio']);

// ambil data portofolio + mahasiswa
$data = mysqli_query($koneksi,
    "SELECT p.*, m.nama 
     FROM portofolio p
     JOIN mahasiswa m ON m.id_mahasiswa = p.id_mahasiswa
     WHERE p.id_portofolio='$id_portofolio'"
);

$portofolio = mysqli_fetch_assoc($data);

// ambil nilai jika sudah ada
$qNilai = mysqli_query($koneksi,
    "SELECT * FROM nilai 
     WHERE id_portofolio='$id_portofolio' 
     AND id_dosen='$id_dosen'"
);
$nilaiData = mysqli_fetch_assoc($qNilai);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $nilaiData ? "Edit Nilai" : "Beri Nilai" ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background:#f9f0f5;
        }
        .bg-pink {
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
        a.back-link {
            color:#e11584;
            text-decoration:none;
        }
        a.back-link:hover {
            text-decoration:underline;
        }
    </style>
</head>

<body>

<div class="container mt-5" style="max-width:600px;">
    <div class="card shadow-sm">

        <!-- HEADER -->
        <div class="card-header bg-pink">
            <?= $nilaiData ? "Edit Nilai Portofolio" : "Beri Nilai Portofolio" ?>
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

                <div class="mb-3">
                    <label class="form-label">Nilai (0–100)</label>
                    <input type="number" name="nilai" class="form-control" min="0" max="100"
                           value="<?= $nilaiData['nilai'] ?? '' ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Catatan</label>
                    <textarea name="catatan" class="form-control" rows="4"><?= $nilaiData['catatan'] ?? '' ?></textarea>
                </div>

                <button class="btn btn-pink w-100">
                    <?= $nilaiData ? "Perbarui Nilai" : "Simpan Nilai" ?>
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="portofolio_dsn.php" class="back-link">
                    ← Kembali ke Portofolio
                </a>
            </div>

        </div>
    </div>
</div>

</body>
</html>
