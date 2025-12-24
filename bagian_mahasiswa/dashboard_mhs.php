<?php
session_start();
include "../koneksi.php";

// ===============================
// CEK LOGIN MAHASISWA
// ===============================
if (!isset($_SESSION['id_mahasiswa']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../index.php");
    exit;
}

$id_mahasiswa = $_SESSION['id_mahasiswa'];

// ===============================
// AMBIL NAMA MAHASISWA
// ===============================
$qNama = mysqli_query(
    $koneksi,
    "SELECT nama FROM mahasiswa WHERE id_mahasiswa='$id_mahasiswa'"
);
$dataNama = mysqli_fetch_assoc($qNama);
$nama = $dataNama['nama'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Mahasiswa</title>

    <!-- Bootstrap dasar (aman semester 1) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

    <!-- HEADER -->
    <div class="card mb-3">
        <div class="card-body">
            <h4 class="mb-1">Dashboard Mahasiswa</h4>
            <p class="mb-0">
                Selamat datang, <strong><?= htmlspecialchars($nama) ?></strong>
            </p>
        </div>
    </div>

    <!-- MENU -->
    <div class="list-group mb-4">
        <a href="dashboard_mhs.php" class="list-group-item list-group-item-action">
            🏠 Dashboard
        </a>
        <a href="portofolio_detail.php" class="list-group-item list-group-item-action">
            ➕ Kelola Portofolio
        </a>
        <a href="lihat_nilai.php" class="list-group-item list-group-item-action">
            📊 Lihat Nilai
        </a>
        <a href="ganti_password_mhs.php" class="list-group-item list-group-item-action">
            🔑 Ganti Password
        </a>
        <a href="../logout.php" class="list-group-item list-group-item-action text-danger">
            🚪 Logout
        </a>
    </div>

    <!-- DAFTAR PORTOFOLIO -->
    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Daftar Portofolio Saya</h5>

            <?php
            $q = mysqli_query(
                $koneksi,
                "SELECT * FROM portofolio WHERE id_mahasiswa='$id_mahasiswa'"
            );

            if (mysqli_num_rows($q) == 0) {
                echo "<div class='alert alert-warning'>Belum ada portofolio.</div>";
            } else {
            ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-secondary">
                        <tr>
                            <th width="5%">No</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($p = mysqli_fetch_assoc($q)) {
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($p['judul']) ?></td>
                            <td><?= htmlspecialchars(substr($p['deskripsi'], 0, 50)) ?>...</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php } ?>
        </div>
    </div>
</div>


</body>
</html>
