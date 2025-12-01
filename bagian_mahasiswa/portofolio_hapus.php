<?php
session_start();
include "koneksi.php";

if(!isset($_SESSION['id_mahasiswa'])){
    header("Location: login_mhs.php");
    exit();
}

$id_mhs = $_SESSION['id_mahasiswa'];
$id     = $_GET['id'];

// cek apakah proyek milik mahasiswa ini
$q = mysqli_query($koneksi,
    "SELECT * FROM portofolio 
     WHERE id_portofolio='$id' AND id_mahasiswa='$id_mhs'");

if(mysqli_num_rows($q) == 0){
    die("Akses ditolak!");
}

mysqli_query($koneksi, "DELETE FROM portofolio WHERE id_portofolio='$id'");
header("Location: dashboard_mhs.php");
