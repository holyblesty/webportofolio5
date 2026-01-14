<!-- 
=========================================================
  Nama File   : ganti_password_mhs.php
  Deskripsi   : Menampilkan dashboard mahasiswa bagian_mahasiswa
  Pembuat     : Jesina HolyBlesty Simatupang (3312511017)
              : Vivian Sarah Diva Alisianoi (3312511018)
  Tanggal     : 19 Oktober 2025
=========================================================
-->

<?php
// =========================
// SESSION & KONEKSI DATABASE
// =========================

// memulai session
session_start();

// Koneksi ke database
include "../koneksi.php";

// =========================
// CEK LOGIN MAHASISWA
// =========================

// Pastikan user sudah login sebagai mahasiswa
if (!isset($_SESSION['id_mahasiswa']) || $_SESSION['role'] !== 'mahasiswa') {
    // Jika belum login, arahkan ke halaman login
    header("Location: ../index.php");
    exit;
}

// Ambil id mahasiswa dari session
$idMahasiswa = $_SESSION['id_mahasiswa'];

// Variabel untuk menampung pesan hasil proses
$pesan = "";

/* =========================
   FUNGSI GANTI PASSWORD MAHASISWA
   - Validasi password lama
   - Password baru tidak boleh sama
   - Konfirmasi password
========================= */
function gantiPasswordMahasiswa($koneksi, $idMahasiswa, $passwordLama, $passwordBaru, $konfirmasi)
{
    try {
        // Ambil password lama (dalam bentuk hash) dari database
        $q = mysqli_query(
            $koneksi,
            "SELECT password FROM mahasiswa WHERE id_mahasiswa='$idMahasiswa'"
        );

        // Jika query gagal
        if (!$q) {
            return "Gagal mengambil data password";
        }

        // Ambil data hasil query
        $data = mysqli_fetch_assoc($q);

        // Validasi password lama
        if (!$data || !password_verify($passwordLama, $data['password'])) {
            return "Password lama salah";
        }

        // Password baru tidak boleh sama dengan password lama
        if (password_verify($passwordBaru, $data['password'])) {
            return "Password baru tidak boleh sama dengan password lama";
        }

        // Validasi konfirmasi password
        if ($passwordBaru !== $konfirmasi) {
            return "Konfirmasi password tidak cocok";
        }

        // Enkripsi password baru
        $hash = password_hash($passwordBaru, PASSWORD_DEFAULT);

        // Update password baru ke database
        $update = mysqli_query(
            $koneksi,
            "UPDATE mahasiswa SET password='$hash' WHERE id_mahasiswa='$idMahasiswa'"
        );

        // Jika update gagal
        if (!$update) {
            return "Gagal memperbarui password";
        }

        // Jika semua proses berhasil
        return "Password berhasil diubah";

    } catch (Exception $e) {
        // Menangani error tak terduga
        return "Terjadi kesalahan sistem";
    }
}

// =========================
// PROSES SUBMIT FORM
// =========================

// Jika tombol submit ditekan
if (isset($_POST['submit'])) {

    // Panggil fungsi ganti password
    $pesan = gantiPasswordMahasiswa(
        $koneksi,
        $idMahasiswa,
        $_POST['password_lama'],
        $_POST['password_baru'],
        $_POST['konfirmasi']
    );
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Encoding karakter -->
    <meta charset="UTF-8">

    <!-- Judul halaman -->
    <title>Ganti Password Mahasiswa</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<!-- Form ganti password -->
<form method="post" class="container mt-5" style="max-width:420px">

    <!-- Card pembungkus form -->
    <div class="card shadow-sm">
        <div class="card-body">

            <!-- Judul halaman -->
            <h3 class="text-center mb-4">Ganti Password Mahasiswa</h3>

            <!-- Menampilkan pesan hasil proses -->
            <?php if ($pesan != "") { ?>
            <div class="alert alert-info text-center">
                <?= htmlspecialchars($pesan) ?>
            </div>
            <?php } ?>

            <!-- Input password lama -->
            <label>Password Lama</label>
            <input type="password" name="password_lama" class="form-control mb-3" required>

            <!-- Input password baru -->
            <label>Password Baru</label>
            <input type="password" name="password_baru" class="form-control mb-3" required>

            <!-- Input konfirmasi password -->
            <label>Konfirmasi Password</label>
            <input type="password" name="konfirmasi" class="form-control mb-4" required>

            <!-- Tombol simpan -->
            <button name="submit" class="btn btn-primary w-100 mb-2">
                Simpan
            </button>

            <!-- Tombol kembali -->
            <a href="dashboard_mhs.php" class="btn btn-outline-secondary w-100">
                Kembali
            </a>

        </div>
    </div>
</form>

</body>
</html>