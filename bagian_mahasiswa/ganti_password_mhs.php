<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['id_mahasiswa']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: home.html");
    exit;
}

$id = $_SESSION['id_mahasiswa'];
$pesan = "";

if (isset($_POST['submit'])) {
    $lama = $_POST['password_lama'];
    $baru = $_POST['password_baru'];
    $konf = $_POST['konfirmasi'];

    $q = mysqli_query($conn, "SELECT password FROM mahasiswa WHERE id_mahasiswa='$id'");
    $d = mysqli_fetch_assoc($q);

    if (!password_verify($lama, $d['password'])) {
        $pesan = "Password lama salah";
    } elseif ($baru !== $konf) {
        $pesan = "Konfirmasi tidak cocok";
    } else {
        $hash = password_hash($baru, PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE mahasiswa SET password='$hash' WHERE id_mahasiswa='$id'");
        $pesan = "Password berhasil diubah";
    }
}
?>

<form method="post">
    <h3>Ganti Password</h3>
    <?= $pesan ?><br><br>
    Password Lama <br><input type="password" name="password_lama"><br>
    Password Baru <br><input type="password" name="password_baru"><br>
    Konfirmasi <br><input type="password" name="konfirmasi"><br><br>
    <button name="submit">Simpan</button>
</form>
