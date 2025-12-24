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

    <!-- Bootstrap sederhana (semester 1 aman) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

    <!-- HEADER -->
    <div class="card mb-3">
        <div class="card-body">
            <h4 class="mb-1">Dashboard Dosen</h4>
            <p class="mb-0">
                Selamat datang, <strong><?= htmlspecialchars($nama) ?></strong>
            </p>
        </div>
    </div>

    <!-- NAVBAR MENU -->
    <div class="list-group mb-3">
        <a href="dashboard_dsn.php" class="list-group-item list-group-item-action">
            🏠 Dashboard
        </a>
        <a href="portofolio_dsn.php" class="list-group-item list-group-item-action">
            📁 Portofolio Mahasiswa
        </a>
        <a href="ganti_password_dsn.php" class="list-group-item list-group-item-action">
            🔑 Ganti Password
        </a>
        <a href="../logout.php" class="list-group-item list-group-item-action text-danger">
            🚪 Logout
        </a>
    </div>

    <!-- INFO -->
    <div class="alert alert-info">
        Silakan pilih menu di atas untuk melanjutkan.
    </div>

</div>

</body>
</html>

</script>
</script>