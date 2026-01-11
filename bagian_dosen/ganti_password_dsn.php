<?php
/*
=========================================================
  Nama File   : ganti_password_dsn.php
  Deskripsi   : Ganti password dosen
  Pembuat     : Jesina HolyBlesty Simatupang (3312511017)
              : Vivian Sarah Diva Alisianoi (3312511018)
  Tanggal     : 19 Oktober 2025
=========================================================
*/

session_set_cookie_params(0);
session_start();
include "../koneksi.php";

// cek login dosen
if (!isset($_SESSION['id_dosen']) || $_SESSION['role'] !== 'dosen') {
    header("Location: ../index.php");
    exit;
}

$idDosen = $_SESSION['id_dosen'];
$pesan = "";

/* =========================
   FUNGSI GANTI PASSWORD DOSEN
========================= */
function gantiPasswordDosen($koneksi, $idDosen, $passwordLama, $passwordBaru, $konfirmasi)
{
    try {
        $q = mysqli_query(
            $koneksi,
            "SELECT password FROM dosen WHERE id_dosen='$idDosen'"
        );

        if (!$q) {
            return "Gagal mengambil data password";
        }

        $data = mysqli_fetch_assoc($q);

        if (!$data || !password_verify($passwordLama, $data['password'])) {
            return "Password lama salah";
        }

        if ($passwordBaru !== $konfirmasi) {
            return "Konfirmasi password tidak cocok";
        }

        $hash = password_hash($passwordBaru, PASSWORD_DEFAULT);

        $update = mysqli_query(
            $koneksi,
            "UPDATE dosen SET password='$hash' WHERE id_dosen='$idDosen'"
        );

        if (!$update) {
            return "Gagal memperbarui password";
        }

        return "Password berhasil diubah";

    } catch (Exception $e) {
        return "Terjadi kesalahan sistem";
    }
}

// proses form
if (isset($_POST['submit'])) {
    $pesan = gantiPasswordDosen(
        $koneksi,
        $idDosen,
        $_POST['password_lama'],
        $_POST['password_baru'],
        $_POST['konfirmasi']
    );
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Ganti Password Dosen</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<form method="post" class="container mt-5" style="max-width:420px">
<div class="card shadow-sm">
<div class="card-body">

<h3 class="text-center mb-4">Ganti Password Dosen</h3>

<?php if ($pesan != "") { ?>
<div class="alert alert-info text-center"><?= $pesan ?></div>
<?php } ?>

<label>Password Lama</label>
<input type="password" name="password_lama" class="form-control mb-3" required>

<label>Password Baru</label>
<input type="password" name="password_baru" class="form-control mb-3" required>

<label>Konfirmasi Password</label>
<input type="password" name="konfirmasi" class="form-control mb-4" required>

<button name="submit" class="btn btn-primary w-100 mb-2">Simpan</button>
<a href="dashboard_dsn.php" class="btn btn-outline-secondary w-100">Kembali</a>

</div>
</div>
</form>

</body>
</html>