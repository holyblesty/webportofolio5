<!-- 
=========================================================
  Nama File   : aplikasi-pengumuman-akademik-online.html
  Deskripsi   : Halaman portofolio Projek PBL
                Sistem Aplikasi Pengumuman Akademik Online
  Pembuat     : Jesina HolyBlesty Simatupang (3312511017)
              : Vivian Sarah Diva Alisianoi (3312511018)
  Tanggal     : 19 Oktober 2025
=========================================================
-->

<?php
session_set_cookie_params(0);
session_start();
include "../koneksi.php";

// =========================
// CEK LOGIN DOSEN
// =========================
if (!isset($_SESSION['id_dosen']) || $_SESSION['role'] !== 'dosen') {
    header("Location: ../index.php");
    exit;
}

$idDosen = $_SESSION['id_dosen'];
$pesan = "";

// =========================
// PROSES GANTI PASSWORD
// =========================
if (isset($_POST['submit'])) {

    $passwordLama = $_POST['password_lama'];
    $passwordBaru = $_POST['password_baru'];
    $konfirmasi   = $_POST['konfirmasi'];

    $queryPassword = mysqli_query(
        $koneksi,
        "SELECT password FROM dosen WHERE id_dosen='$idDosen'"
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
            "UPDATE dosen SET password='$hashPassword' WHERE id_dosen='$idDosen'"
        );
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
            background: #e8eeff;
        }

        /* tombol utama */
        .btn-primary-custom {
            background-color: #0041C2;
            border-color: #0041C2;
            color: white;
        }

        .btn-primary-custom:hover {
            background-color: #0035a0;
            border-color: #0035a0;
            color: white;
        }

        /* tombol kembali */
        .btn-back {
            border-color: #0041C2;
            color: #0041C2;
        }

        .btn-back:hover {
            background-color: #0041C2;
            border-color: #0041C2;
            color: white;
        }
    </style>
</head>

<body>

<form method="post" class="container mt-5" style="max-width: 420px;">
    <div class="card shadow-sm">
        <div class="card-body">

            <h3 class="text-center mb-4">
                Ganti Password Dosen
            </h3>

            <?php if ($pesan != "") { ?>
                <div class="alert alert-info text-center">
                    <?= $pesan ?>
                </div>

            <?php } ?>
            <label>Password Lama</label>
            <input type="password" name="password_lama" class="form-control mb-3" required>

            <label>Password Baru</label>
            <input type="password" name="password_baru" class="form-control mb-3" required>

            <label>Konfirmasi Password</label>
            <input type="password" name="konfirmasi" class="form-control mb-4" required>

            <!-- tombol simpan -->
            <button name="submit" class="btn btn-primary-custom w-100 mb-2">
                Simpan
            </button>

            <!-- tombol kembali -->
            <a href="dashboard_dsn.php" class="btn btn-outline-secondary btn-back w-100">
                Kembali ke Dashboard
            </a>

        </div>
    </div>
</form>

</body>
</html>
