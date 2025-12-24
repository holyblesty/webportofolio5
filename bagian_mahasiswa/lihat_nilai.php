<?php
// ===============================
// LIHAT NILAI & CATATAN PROYEK
// MAHASISWA (SEMESTER 1)
// ===============================

session_start();
include "../koneksi.php";

// Cek login mahasiswa
if (!isset($_SESSION['id_mahasiswa']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../index.php");
    exit;
}

$id_mahasiswa = $_SESSION['id_mahasiswa'];

// Ambil nama mahasiswa
$qNama = mysqli_query($koneksi, "SELECT nama FROM mahasiswa WHERE id_mahasiswa='$id_mahasiswa'");
$dataNama = mysqli_fetch_assoc($qNama);
$nama_mahasiswa = $dataNama['nama'];

// Ambil data portofolio + nilai
$sql = "SELECT p.judul, n.nilai, n.catatan, d.nama AS nama_dosen
        FROM portofolio p
        LEFT JOIN nilai n ON p.id_portofolio = n.id_portofolio
        LEFT JOIN dosen d ON n.id_dosen = d.id_dosen
        WHERE p.id_mahasiswa = '$id_mahasiswa'";

$data = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nilai Proyek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <h3 class="mb-2">Nilai & Catatan Proyek</h3>
    <p>Mahasiswa: <strong><?= htmlspecialchars($nama_mahasiswa) ?></strong></p>

    <a href="dashboard_mhs.php" class="btn btn-secondary btn-sm mb-3">← Kembali ke Dashboard</a>

<?php if (mysqli_num_rows($data) == 0) { ?>

    <div class="alert alert-info">Belum ada proyek.</div>

<?php } else { ?>

    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>Judul Proyek</th>
                <th>Nilai</th>
                <th>Catatan</th>
                <th>Dosen</th>
            </tr>
        </thead>
        <tbody>

<?php while ($row = mysqli_fetch_assoc($data)) { ?>
            <tr>
                <td><?= htmlspecialchars($row['judul']) ?></td>
                <td>
                    <?= $row['nilai'] !== null ? $row['nilai'] : 'Belum dinilai' ?>
                </td>
                <td><?= $row['catatan'] ? htmlspecialchars($row['catatan']) : '-' ?></td>
                <td><?= $row['nama_dosen'] ? htmlspecialchars($row['nama_dosen']) : '-' ?></td>
            </tr>
<?php } ?>

        </tbody>
    </table>

<?php } ?>
</div>

</body>
</html>
