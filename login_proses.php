<?php
session_start();
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $role     = $_POST['role']; // role dari modal

    /* ======================
       LOGIN DOSEN
    ====================== */
    if ($role === 'dosen') {

        $q = mysqli_query($conn, "SELECT * FROM dosen WHERE username='$username'");
        if (mysqli_num_rows($q) === 1) {

            $data = mysqli_fetch_assoc($q);

            if (password_verify($password, $data['password'])) {

                $_SESSION['id_dosen'] = $data['id_dosen'];
                $_SESSION['username'] = $data['username'];
                $_SESSION['role']     = 'dosen';

                header("Location: dashboard_dsn.php");
                exit;
            }
        }

        echo "Login dosen gagal. Username atau password salah.";
        exit;
    }

    /* ======================
       LOGIN MAHASISWA
    ====================== */
    if ($role === 'mahasiswa') {

        $q = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE username='$username'");
        if (mysqli_num_rows($q) === 1) {

            $data = mysqli_fetch_assoc($q);

            if (password_verify($password, $data['password'])) {

                $_SESSION['id_mahasiswa'] = $data['id_mahasiswa'];
                $_SESSION['username'] = $data['username'];
                $_SESSION['role']     = 'mahasiswa';

                header("Location: dashboard_mhs.php");
                exit;
            }
        }

        echo "Login mahasiswa gagal. Username atau password salah.";
        exit;
    }

    /* ======================
       ROLE TIDAK VALID
    ====================== */
    echo "Role login tidak valid.";
}
?>
