<?php
session_start();
include "koneksi.php";

/* cek login */
if (!isset($_SESSION['id_mahasiswa'])) {
    header("Location: home.html");
    exit;
}

$id_mahasiswa = $_SESSION['id_mahasiswa'];
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Mahasiswa</title>
</head>
<body>

<h2>Selamat datang, <?php echo $username; ?></h2>
<p>Dashboard Mahasiswa</p>

<hr>

<a href="portofolio_detail.php">Tambah Portofolio</a> |
<a href="lihat_nilai.php">Lihat Nilai</a> |
<a href="logout.php">Logout</a>

<hr>

<h3>Daftar Portofolio</h3>

<?php
$sql = "SELECT * FROM portofolio WHERE id_mahasiswa='$id_mahasiswa'";
$query = mysqli_query($koneksi, $sql);

if (mysqli_num_rows($query) == 0) {
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
while ($p = mysqli_fetch_assoc($query)) {
?>
    <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo $p['judul']; ?></td>
        <td><?php echo substr($p['deskripsi'], 0, 50); ?></td>
        <td>
            <a href="portofolio_detail.php?id=<?php echo $p['id_portofolio']; ?>">Edit</a> |
            <a href="hapus_portofolio.php?id=<?php echo $p['id_portofolio']; ?>"
               onclick="return confirm('Yakin hapus?')">Hapus</a>
        </td>
    </tr>
<?php } ?>
</table>
<?php } ?>

</body>
</html>
