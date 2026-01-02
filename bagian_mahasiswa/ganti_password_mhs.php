<?php
/*
  Nama File   : ganti_password_mhs.php
  Deskripsi   : Halaman mahasiswa untuk mengganti password akun
  Pembuat    : Vivian Sarah Diva Alisianoi & Jesina Holyblesty Simatupang
  Tanggal    : 26 Desember 2025
*/

session_set_cookie_params(0);
session_start();

include "../koneksi.php";

/* =========================
   CEK LOGIN MAHASISWA
========================= */
if (!isset($_SESSION['id_mahasiswa']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../index.php");
    exit;
}

$idMahasiswa = $_SESSION['id_mahasiswa'];
$pesan = "";

/* =========================
   PROSES GANTI PASSWORD
========================= */
if (isset($_POST['submit'])) {

    $passwordLama = $_POST['password_lama'];
    $passwordBaru = $_POST['password_baru'];
    $konfirmasi   = $_POST['konfirmasi'];

    $queryPassword = mysqli_query(
        $koneksi,
        "SELECT password FROM mahasiswa WHERE id_mahasiswa='$idMahasiswa'"
    );

    $dataPassword = mysqli_fetch_assoc($queryPassword);

    if (!$dataPassword || !password_verify($passwordLama, $dataPassword['password'])) {
        $pesan = "Password lama salah";
    } elseif ($passwordBaru !== $konfirmasi) {
        $pesan = "Konfirmasi tidak cocok";
    } else {
        $hashPassword = password_hash($passwordBaru, PASSWORD_DEFAULT);

        mysqli_query(
            $koneksi,
            "UPDATE mahasiswa 
             SET password='$hashPassword' 
             WHERE id_mahasiswa='$idMahasiswa'"
        );

        $pesan = "Password berhasil diubah";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ganti Password Mahasiswa</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        /* tombol simpan */
        .btn-blue {
            background-color: #0041C2;
            border-color: #0041C2;
            color: white;
        }

        /* hover tombol simpan */
        .btn-blue:hover {
            background-color: #00349a;
            border-color: #00349a;
            color: white;
        }

        /* tombol kembali */
        .btn-back:hover {
            background-color: #0041C2 !important;
            border-color: #0041C2 !important;
            color: white !important;
        }
    </style>
</head>

<body class="bg-light">

<form
    method="post"
    class="container mt-5"
    style="max-width: 420px;"
>
    <div class="card shadow-sm">
        <div class="card-body">

            <h3 class="text-center mb-4">
                Ganti Password Mahasiswa
            </h3>

            <?php if ($pesan != "") { ?>
                <div class="alert alert-info text-center">
                    <?= $pesan ?>
                </div>
            <?php } ?>

            <label>Password Lama</label>
            <input
                type="password"
                name="password_lama"
                class="form-control mb-3"
                required
            >

            <label>Password Baru</label>
            <input
                type="password"
                name="password_baru"
                class="form-control mb-3"
                required
            >

            <label>Konfirmasi Password</label>
            <input
                type="password"
                name="konfirmasi"
                class="form-control mb-4"
                required
            >

            <button
                name="submit"
                class="btn btn-blue w-100 mb-2"
            >
                Simpan
            </button>

            <a
                href="dashboard_mhs.php"
                class="btn btn-outline-secondary btn-back w-100"
            >
                Kembali ke Dashboard
            </a>

        </div>
    </div>
</form>

</body>
</html>
