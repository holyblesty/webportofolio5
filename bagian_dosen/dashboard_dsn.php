<?php
session_start();
include "../koneksi.php";

// cek login dosen
if (!isset($_SESSION['id_dosen']) || $_SESSION['role'] !== 'dosen') {
    header("Location: ../index.php");
    exit;
}

$id_dosen = $_SESSION['id_dosen'];

// ambil nama dosen
$q = mysqli_query($koneksi, "SELECT nama FROM dosen WHERE id_dosen='$id_dosen'");
$data = mysqli_fetch_assoc($q);
$nama = $data['nama'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Dosen</title>
</head>
<body>

<!-- NAVBAR -->
<div style="margin-bottom: 15px;">
    <a href="dashboard_dsn.php">🏠 Dashboard</a> |
    <a href="portofolio_dsn.php">📁 Portofolio Mahasiswa</a> |
    <a href="ganti_password_dsn.php">🔑 Ganti Password</a>
    <a href="../logout.php">🚪 Logout</a>
</div>

<h2>Selamat datang, <?= htmlspecialchars($nama) ?> (Dosen)</h2>
<p>Silakan pilih menu di atas.</p>

</body>
</html>
