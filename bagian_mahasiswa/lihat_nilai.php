```php
<?php
// ===============================
// LIHAT NILAI & CATATAN PROYEK
// VERSI PEMULA (SEMESTER 1)
// ===============================

session_start();
include "koneksi.php";

// Cek login mahasiswa
if (!isset($_SESSION['id_mahasiswa'])) {
    header("Location: home.html");
    exit;
}

$id_mahasiswa = $_SESSION['id_mahasiswa'];
$username = $_SESSION['username'];

// Ambil data portofolio + nilai
$sql = "SELECT p.judul, p.deskripsi, n.nilai, n.catatan, d.nama AS nama_dosen
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <h3 class="mb-3">Nilai & Catatan Proyek</h3>
    <p>Mahasiswa: <strong><?php echo $username; ?></strong></p>

    <a href="dashboard_mhs.php" class="btn btn-secondary btn-sm mb-3">Kembali</a>

    <?php if (mysqli_num_rows($data) == 0) { ?>
        <div class="alert alert-info">Belum ada proyek yang dinilai</div>
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
        <?php
        $total = 0;
        $jumlah = 0;

        while ($row = mysqli_fetch_assoc($data)) {
        ?>
            <tr>
                <td><?php echo $row['judul']; ?></td>
                <td>
                    <?php
                    if ($row['nilai'] != null) {
                        echo $row['nilai'];
                        $total += $row['nilai'];
                        $jumlah++;
                    } else {
                        echo "Belum dinilai";
                    }
                    ?>
                </td>
                <td><?php echo $row['catatan'] ?: '-'; ?></td>
                <td><?php echo $row['nama_dosen'] ?: '-'; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <?php if ($jumlah > 0) { ?>
        <div class="alert alert-success">
            Rata-rata nilai: <strong><?php echo number_format($total / $jumlah, 2); ?></strong>
        </div>
    <?php } ?>

    <?php } ?>
</div>

</body>
</html>

