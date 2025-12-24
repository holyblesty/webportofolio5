<?php
session_start();
include "../koneksi.php";

// cek login mahasiswa
if (!isset($_SESSION['id_mahasiswa']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../index.php");
    exit;
}

$id_mahasiswa = $_SESSION['id_mahasiswa'];

// ambil nama mahasiswa
$qNama = mysqli_query($koneksi, "SELECT nama FROM mahasiswa WHERE id_mahasiswa='$id_mahasiswa'");
$dataNama = mysqli_fetch_assoc($qNama);
$nama = $dataNama['nama'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

    <h4>Halo, <?= htmlspecialchars($nama) ?> 👋</h4>

    <!-- MENU -->
    <div class="mb-3">
        <a href="portofolio_detail.php" class="btn btn-primary btn-sm">➕ Tambah Portofolio</a>
        <a href="lihat_nilai.php" class="btn btn-success btn-sm">📊 Lihat Nilai</a>
        <a href="ganti_password_mhs.php" class="btn btn-warning btn-sm">🔑 Ganti Password</a>
        <a href="../logout.php" class="btn btn-danger btn-sm">🚪 Logout</a>
    </div>

    <hr>

    <h5>Kelola Portofolio Saya</h5>

    <?php
    $q = mysqli_query($koneksi, "SELECT * FROM portofolio WHERE id_mahasiswa='$id_mahasiswa'");

    if (mysqli_num_rows($q) == 0) {
        echo "<div class='alert alert-info'>Belum ada portofolio.</div>";
    } else {
    ?>
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-secondary">
            <tr>
                <th width="5%">No</th>
                <th width="15%">Gambar</th>
                <th>Judul</th>
                <th width="15%">Repository</th>
                <th width="25%">Kelola</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        while ($p = mysqli_fetch_assoc($q)) {
        ?>
            <tr>
                <td><?= $no++ ?></td>

                <!-- GAMBAR -->
                <td class="text-center">
                    <?php if ($p['gambar'] != "") { ?>
                        <img src="../uploads/<?= $p['gambar'] ?>" width="80">
                    <?php } else { ?>
                        -
                    <?php } ?>
                </td>

                <td><?= htmlspecialchars($p['judul']) ?></td>
               <!-- REPOSITORY -->
                <td>
                 <?php if (!empty($p['repo_link'])) { ?>
                <a href="<?= htmlspecialchars($p['repo_link']) ?>" target="_blank">
                Lihat Repo
                 </a>
                <?php } else { ?>
                     -
                <?php } ?>
                 </td>
                 
                  <td>
                  <a href="portofolio_detail.php?id=<?= $p['id_portofolio'] ?>"
                    class="btn btn-outline-primary btn-sm">
                ✏️ Edit
                 </a>

                <a href="portofolio_detail.php?mode=hapus&id=<?= $p['id_portofolio'] ?>"
                 onclick="return confirm('Yakin hapus portofolio ini?')"
                  class="btn btn-outline-danger btn-sm">
                  🗑️ Hapus
                </a>
                </td>

            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php } ?>

</div>

</body>
</html>
