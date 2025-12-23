<?php
session_start();
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($conn,
        "SELECT * FROM proyek 
         WHERE username='$username' 
         AND password='$password'"
    );

    if (mysqli_num_rows($query) == 1) {

        $data = mysqli_fetch_assoc($query);

        $_SESSION['username'] = $data['username'];
        $_SESSION['role']     = $data['role'];

        if ($data['role'] == "dosen") {
            header("Location: dashboard_dsn.php");
        } else if ($data['role'] == "mahasiswa") {
            header("Location: dashboard_mhs.php");
        }

        exit;

    } else {
        echo "Username atau password salah";
    }
}
?>
