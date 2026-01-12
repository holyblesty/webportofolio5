<?php
/* 
=========================================================
  Nama File   : login_proses.php
  Deskripsi   : Proses login pengguna (Dosen & Mahasiswa)
                pada aplikasi Web Portofolio Projek PBL
  Pembuat     : Jesina HolyBlesty Simatupang (3312511017)
              : Vivian Sarah Diva Alisianoi (3312511018)
  Tanggal     : 19 Oktober 2025
=========================================================
*/

// session cookie hanya hidup selama browser terbuka
session_set_cookie_params(0);
// pemanggilan session
session_start();

// koneksi database
include "koneksi.php";

// ===============================
// FUNGSI LOGIN DOSEN
// ===============================
function loginDosen($koneksi, $username, $password)
{
    try {
        // query data dosen berdasarkan username
        $query = mysqli_query(
            $koneksi,
            "SELECT * FROM dosen WHERE username='$username'"
        );

        // cek apakah data ditemukan
        if (mysqli_num_rows($query) == 1) {
            $data = mysqli_fetch_assoc($query);

            // verifikasi password (hash)
            if (password_verify($password, $data['password'])) {

                // set session dosen
                $_SESSION['id_dosen'] = $data['id_dosen'];
                $_SESSION['nama']     = $data['nama'];
                $_SESSION['role']     = 'dosen';

                // redirect ke dashboard dosen
                header("Location: bagian_dosen/dashboard_dsn.php");
                exit;
            }
        }

        // jika login gagal
        throw new Exception("Username atau password dosen salah");
    } catch (Exception $e) {
        // menampilkan pesan error
        echo "<script>
                alert('" . $e->getMessage() . "');
                window.location='index.php';
              </script>";
        exit;
    }
}

// ===============================
// FUNGSI LOGIN MAHASISWA
// ===============================
function loginMahasiswa($koneksi, $username, $password)
{
    try {
        // query data mahasiswa berdasarkan username
        $query = mysqli_query(
            $koneksi,
            "SELECT * FROM mahasiswa WHERE username='$username'"
        );

        // cek apakah data ditemukan
        if (mysqli_num_rows($query) == 1) {
            $data = mysqli_fetch_assoc($query);

            // verifikasi password (hash)
            if (password_verify($password, $data['password'])) {

                // set session mahasiswa
                $_SESSION['id_mahasiswa'] = $data['id_mahasiswa'];
                $_SESSION['nama']         = $data['nama'];
                $_SESSION['role']         = 'mahasiswa';

                // redirect ke dashboard mahasiswa
                header("Location: bagian_mahasiswa/dashboard_mhs.php");
                exit;
            }
        }

        // jika login gagal
        throw new Exception("Username atau password mahasiswa salah");
    } catch (Exception $e) {
        // menampilkan pesan error
        echo "<script>
                alert('" . $e->getMessage() . "');
                window.location='index.php';
              </script>";
        exit;
    }
}

// ===============================
// AMBIL DATA DARI FORM
// ===============================
$username = $_POST['username'];
$password = $_POST['password'];
$role     = $_POST['role'];

// ===============================
// PROSES LOGIN BERDASARKAN ROLE
// ===============================
if ($role == "dosen") {
    loginDosen($koneksi, $username, $password);
} elseif ($role == "mahasiswa") {
    loginMahasiswa($koneksi, $username, $password);
} else {
    // jika role tidak valid
    echo "<script>
            alert('Role login tidak valid');
            window.location='index.php';
          </script>";
    exit;
}