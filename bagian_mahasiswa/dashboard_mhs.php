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
</head>
<body>

<h2>Selamat datang, <?= htmlspecialchars($nama) ?> (Mahasiswa)</h2>

<a href="portofolio_detail.php">➕ Tambah Portofolio</a> |
<a href="lihat_nilai.php">📊 Lihat Nilai</a> |
<a href="ganti_password_mhs.php">🔑 Ganti Password</a> |
<a href="../logout.php">🚪 Logout</a>

<hr>

<h3>Daftar Portofolio</h3>

<?php
$q = mysqli_query($koneksi, "SELECT * FROM portofolio WHERE id_mahasiswa='$id_mahasiswa'");

if (mysqli_num_rows($q) == 0) {
    echo "Belum ada portofolio.";
} else {
?>
<table border="1" cellpadding="5">
<tr>
    <th>No</th>
    <th>Judul</th>
    <th>Deskripsi</th>
    <th>Aksi</th>
</tr>

<?php
$no = 1;
while ($p = mysqli_fetch_assoc($q)) {
?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= htmlspecialchars($p['judul']) ?></td>
    <td><?= htmlspecialchars(substr($p['deskripsi'], 0, 50)) ?></td>
    <td>
        <a href="portofolio_detail.php?id=<?= $p['id_portofolio'] ?>">Edit</a> |
        <a href="hapus_portofolio.php?id=<?= $p['id_portofolio'] ?>"
           onclick="return confirm('Yakin hapus?')">Hapus</a>
    </td>
</tr>
<?php } ?>
</table>
<?php } ?>

</body>
</html>
