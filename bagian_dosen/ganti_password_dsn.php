<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['id_dosen']) || $_SESSION['role'] !== 'dosen') {
    header("Location: index.php");
    exit;
}

$id = $_SESSION['id_dosen'];
$pesan = "";

if (isset($_POST['submit'])) {
    $lama = $_POST['password_lama'];
    $baru = $_POST['password_baru'];
    $konf = $_POST['konfirmasi'];

    $q = mysqli_query($conn, "SELECT password FROM dosen WHERE id_dosen='$id'");
    $d = mysqli_fetch_assoc($q);

    if (!password_verify($lama, $d['password'])) {
        $pesan = "Password lama salah";
    } elseif ($baru !== $konf) {
        $pesan = "Konfirmasi tidak cocok";
    } else {
        $hash = password_hash($baru, PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE dosen SET password='$hash' WHERE id_dosen='$id'");
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
