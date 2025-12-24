<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['id_dosen']) || $_SESSION['role'] !== 'dosen') {
    header("Location: home.html");
    exit;
}

$id_dosen = $_SESSION['id_dosen'];
$q = mysqli_query($conn, "SELECT nama FROM dosen WHERE id_dosen='$id_dosen'");
$data = mysqli_fetch_assoc($q);
$nama = $data['nama'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Dosen</title>
</head>
<body>

<a href="dashboard_dsn.php">🏠 Dashboard</a> |
<a href="portofolio_dsn.php">📁 Portofolio Mahasiswa</a> |
<a href="ganti_password_dsn.php">🔑 Ganti Password</a> |
<a href="logout.php">🚪 Logout</a>

<h2>Selamat datang, <?= htmlspecialchars($nama) ?> (Dosen)</h2>

</body>
</html>
