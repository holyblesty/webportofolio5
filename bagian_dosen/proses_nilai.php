<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['id_dosen'])) {
    header("Location: home.html");
    exit;
}

/* =========================
   SIMPAN NILAI
========================= */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_portofolio = $_POST['id_portofolio'];
    $nilai   = $_POST['nilai'];
    $catatan = $_POST['catatan'];

    // gaya semester 1 (escape manual)
    $id_portofolio = mysqli_real_escape_string($koneksi, $id_portofolio);
    $nilai         = mysqli_real_escape_string($koneksi, $nilai);
    $catatan       = mysqli_real_escape_string($koneksi, $catatan);

    if ($nilai < 0 || $nilai > 100) {
        echo "<script>alert('Data nilai tidak valid'); window.history.back();</script>";
        exit;
    }

    // update nilai (sesuai kode asli, gaya dasar)
    $query = mysqli_query($koneksi,
        "UPDATE portofolio 
         SET nilai='$nilai' 
         WHERE id_portofolio='$id_portofolio'"
    );

    if ($query) {
        echo "<script>alert('Nilai berhasil disimpan'); window.location='portofolio_detail.php?id=$id_portofolio';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan nilai'); window.history.back();</script>";
    }
    exit;
}

/* =========================
   AMBIL DATA (BERI / EDIT NILAI)
========================= */
if (!isset($_GET['id_portofolio'])) {
    echo "ID Portofolio tidak ditemukan!";
    exit;
}

$id_portofolio = $_GET['id_portofolio'];
$id_portofolio = mysqli_real_escape_string($koneksi, $id_portofolio);

/* ambil portofolio + mahasiswa */
$data = mysqli_query($koneksi,
    "SELECT p.*, m.nama 
     FROM portofolio p
     JOIN login_mhs m ON m.id_mahasiswa = p.id_mahasiswa
     WHERE p.id_portofolio='$id_portofolio'"
);

$data_portofolio = mysqli_fetch_array($data);

/* ambil nilai sebelumnya */
$data_nilai = mysqli_query($koneksi,
    "SELECT * FROM nilai WHERE id_portofolio='$id_portofolio'"
);

$nilai = mysqli_fetch_array($data_nilai);
?>

<h2>
<?php
if ($nilai) {
    echo "Edit Nilai";
} else {
    echo "Beri Nilai";
}
?>
</h2>

<form action="proses_nilai.php" method="POST">

    <input type="hidden" name="id_portofolio"
           value="<?php echo $data_portofolio['id_portofolio']; ?>">

    Nama Mahasiswa:<br>
    <input type="text" value="<?php echo $data_portofolio['nama']; ?>" readonly><br><br>

    Judul Proyek:<br>
    <input type="text" value="<?php echo $data_portofolio['judul']; ?>" readonly><br><br>

    Nilai:<br>
    <input type="number" name="nilai" min="0" max="100"
           value="<?php echo $nilai['nilai']; ?>" required><br><br>

    Catatan:<br>
    <textarea name="catatan" rows="4"><?php echo $nilai['catatan']; ?></textarea><br><br>

    <button type="submit">
        <?php
        if ($nilai) {
            echo "Perbarui Nilai";
        } else {
            echo "Simpan Nilai";
        }
        ?>
    </button>

</form>
