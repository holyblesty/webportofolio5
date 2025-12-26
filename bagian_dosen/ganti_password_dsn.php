<?php
/*
  Nama File   : ganti_password_dsn.php
  Deskripsi   : Halaman dosen untuk mengganti password akun
  Pembuat    : Vivian Sarah Diva Alisianoi & Jesina Holyblesty Simatupang
  Tanggal    : 26 Desember 2025
*/

// session hanya aktif selama browser terbuka
session_set_cookie_params(0);

// memulai session
session_start();

// memanggil file koneksi database
include "../koneksi.php";

// =========================
// CEK LOGIN DOSEN
// =========================

// memastikan user sudah login sebagai dosen
if (!isset($_SESSION['id_dosen']) || $_SESSION['role'] !== 'dosen') {
    // jika belum login atau role bukan dosen
    header("Location: ../index.php");
    exit;
}

// mengambil id dosen dari session
$idDosen = $_SESSION['id_dosen'];

// variabel untuk menyimpan pesan notifikasi
$pesan = "";

// =========================
// PROSES GANTI PASSWORD
// =========================

// cek apakah form disubmit
if (isset($_POST['submit'])) {

    // mengambil input dari form
    $passwordLama = $_POST['password_lama'];
    $passwordBaru = $_POST['password_baru'];
    $konfirmasi   = $_POST['konfirmasi'];

    // query password lama dari database
    $queryPassword = mysqli_query(
        $koneksi,
        "SELECT password FROM dosen WHERE id_dosen='$idDosen'"
    );

    // mengambil hasil query
    $dataPassword = mysqli_fetch_assoc($queryPassword);

    // validasi password lama
    if (!$dataPassword || !password_verify($passwordLama, $dataPassword['password'])) {
        $pesan = "Password lama salah";
    }
    // validasi konfirmasi password
    elseif ($passwordBaru !== $konfirmasi) {
        $pesan = "Konfirmasi tidak cocok";
    }
    // jika semua valid
    else {
        // hash password baru
        $hashPassword = password_hash($passwordBaru, PASSWORD_DEFAULT);

        // update password di database
        mysqli_query(
            $koneksi,
            "UPDATE dosen SET password='$hashPassword' WHERE id_dosen='$idDosen'"
        );

        // pesan sukses
        $pesan = "Password berhasil diubah";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ganti Password Dosen</title>

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        /* warna latar belakang halaman */
        body {
            background: #f9f0f5;
        }

        /* tombol simpan */
        .btn-pink {
            background-color: #e83e8c;
            border-color: #e83e8c;
            color: white;
        }

        /* hover tombol simpan */
        .btn-pink:hover {
            background-color: #d63384;
            border-color: #d63384;
            color: white;
        }

        /* tombol kembali: warna normal bootstrap, hover pink tua */
        .btn-back:hover {
            background-color: #ad1457 !important;
            border-color: #ad1457 !important;
            color: white !important;
        }
    </style>
</head>

<body class="bg-light">

<!-- =========================
     FORM GANTI PASSWORD
========================= -->
<form
    method="post"
    class="container mt-5"
    style="max-width: 420px;"
>
    <div class="card shadow-sm">
        <div class="card-body">

            <!-- judul halaman -->
            <h3 class="text-center mb-4">
                Ganti Password Dosen
            </h3>

            <!-- pesan notifikasi -->
            <?php if ($pesan != "") { ?>
                <div class="alert alert-info text-center">
                    <?= $pesan ?>
                </div>
            <?php } ?>

            <!-- input password lama -->
            <label>Password Lama</label>
            <input
                type="password"
                name="password_lama"
                class="form-control mb-3"
                required
            >

            <!-- input password baru -->
            <label>Password Baru</label>
            <input
                type="password"
                name="password_baru"
                class="form-control mb-3"
                required
            >

            <!-- input konfirmasi password -->
            <label>Konfirmasi Password</label>
            <input
                type="password"
                name="konfirmasi"
                class="form-control mb-4"
                required
            >

            <!-- tombol simpan -->
            <button
                name="submit"
                class="btn btn-pink w-100 mb-2"
            >
                Simpan
            </button>

            <!-- tombol kembali ke dashboard -->
            <a
                href="dashboard_dsn.php"
                class="btn btn-outline-secondary btn-back w-100"
            >
                Kembali ke Dashboard
            </a>

        </div>
    </div>
</form>

</body>
</html>
