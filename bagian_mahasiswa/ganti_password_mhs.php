<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['id_mahasiswa']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../index.php");
    exit;
}

$id = $_SESSION['id_mahasiswa'];
$pesan = "";

if (isset($_POST['submit'])) {
    $lama = $_POST['password_lama'];
    $baru = $_POST['password_baru'];
    $konf = $_POST['konfirmasi'];

    $q = mysqli_query($koneksi, "SELECT password FROM mahasiswa WHERE id_mahasiswa='$id'");
    $d = mysqli_fetch_assoc($q);

    if (!$d || !password_verify($lama, $d['password'])) {
        $pesan = "Password lama salah";
    } elseif ($baru !== $konf) {
        $pesan = "Konfirmasi tidak cocok";
    } else {
        $hash = password_hash($baru, PASSWORD_DEFAULT);
        mysqli_query($koneksi, "UPDATE mahasiswa SET password='$hash' WHERE id_mahasiswa='$id'");
        $pesan = "Password berhasil diubah";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ganti Password Mahasiswa</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .btn-pink {
            background-color: #e83e8c;
            border-color: #e83e8c;
            color: white;
        }
        .btn-pink:hover {
            background-color: #d63384;
            border-color: #d63384;
            color: white;
        }

        /* hover saja pink tua */
        .btn-back:hover {
            background-color: #ad1457 !important;
            border-color: #ad1457 !important;
            color: white !important;
        }
    </style>
</head>

<body class="bg-light">

<form method="post" class="container mt-5" style="max-width: 420px;">
    <div class="card shadow-sm">
        <div class="card-body">

            <h3 class="text-center mb-4">Ganti Password Mahasiswa</h3>

            <?php if ($pesan != "") { ?>
                <div class="alert alert-info text-center">
                    <?= $pesan ?>
                </div>
            <?php } ?>

            Password Lama
            <input type="password" name="password_lama" class="form-control mb-3" required>

            Password Baru
            <input type="password" name="password_baru" class="form-control mb-3" required>

            Konfirmasi
            <input type="password" name="konfirmasi" class="form-control mb-4" required>

            <button name="submit" class="btn btn-pink w-100 mb-2">
                Simpan
            </button>

            <a href="dashboard_mhs.php" class="btn btn-outline-secondary btn-back w-100">
                Kembali ke Dashboard
            </a>

        </div>
    </div>
</form>

</body>
</html>
