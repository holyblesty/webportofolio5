<?php
session_start();
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $role     = $_POST['role'];

    // LOGIN DOSEN
    if ($role === 'dosen') {
        $q = mysqli_query($koneksi, "SELECT * FROM dosen WHERE username='$username'");
        if (mysqli_num_rows($q) === 1) {
            $data = mysqli_fetch_assoc($q);
            if (password_verify($password, $data['password'])) {
                $_SESSION['id_dosen'] = $data['id_dosen'];
                $_SESSION['role'] = 'dosen';
                header("Location: dashboard_dsn.php");
                exit;
            }
        }
        die("Login dosen gagal. Username atau password salah.");
    }

    // LOGIN MAHASISWA
    if ($role === 'mahasiswa') {
        $q = mysqli_query($koneksi, "SELECT * FROM mahasiswa WHERE username='$username'");
        if (mysqli_num_rows($q) === 1) {
            $data = mysqli_fetch_assoc($q);
            if (password_verify($password, $data['password'])) {
                $_SESSION['id_mahasiswa'] = $data['id_mahasiswa'];
                $_SESSION['role'] = 'mahasiswa';
                header("Location: dashboard_mhs.php");
                exit;
            }
        }
        die("Login mahasiswa gagal. Username atau password salah.");
    }

    die("Role tidak valid.");
}
?>
