<?php
// ===============================
// PROSES LOGIN (DOSEN & MAHASISWA)
// SESUAI MODUL SESSION SEMESTER 1
// ===============================

session_start();
include "koneksi.php";

// ambil data dari form
$username = $_POST['username'];
$password = $_POST['password'];
$role     = $_POST['role'];

// ===============================
// LOGIN DOSEN
// ===============================
if ($role == "dosen") {

    $query = mysqli_query(
        $koneksi,
        "SELECT * FROM dosen 
         WHERE username='$username'"
    );

    if (mysqli_num_rows($query) == 1) {
        $data = mysqli_fetch_assoc($query);

        // cek password (hash)
        if (password_verify($password, $data['password'])) {

            $_SESSION['id_dosen'] = $data['id_dosen'];
            $_SESSION['nama']     = $data['nama'];
            $_SESSION['role']     = 'dosen';

            header("Location: bagian_dosen/dashboard_dsn.php");
            exit;
        }
    }

    echo "<script>
            alert('Username atau password dosen salah');
            window.location='index.php';
          </script>";
    exit;
}

// ===============================
// LOGIN MAHASISWA
// ===============================
if ($role == "mahasiswa") {

    $query = mysqli_query(
        $koneksi,
        "SELECT * FROM mahasiswa 
         WHERE username='$username'"
    );

    if (mysqli_num_rows($query) == 1) {
        $data = mysqli_fetch_assoc($query);

        // cek password (hash)
        if (password_verify($password, $data['password'])) {

            $_SESSION['id_mahasiswa'] = $data['id_mahasiswa'];
            $_SESSION['nama']         = $data['nama'];
            $_SESSION['role']         = 'mahasiswa';

            header("Location: bagian_mahasiswa/dashboard_mhs.php");
            exit;
        }
    }

    echo "<script>
            alert('Username atau password mahasiswa salah');
            window.location='index.php';
          </script>";
    exit;
}

// ===============================
// JIKA ROLE TIDAK VALID
// ===============================
echo "<script>
        alert('Role login tidak valid');
        window.location='index.php';
      </script>";
