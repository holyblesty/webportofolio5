<?php
session_start();
include "../koneksi.php";

// cek login dosen
if (!isset($_SESSION['id_dosen']) || $_SESSION['role'] !== 'dosen') {
    header("Location: ../index.php");
    exit;
}

$id_dosen = $_SESSION['id_dosen'];

// ambil nama dosen
$qNama = mysqli_query($koneksi, "SELECT nama FROM dosen WHERE id_dosen='$id_dosen'");
$dataNama = mysqli_fetch_assoc($qNama);
$nama = $dataNama['nama'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Portofolio Mahasiswa</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body { background:#f9f0f5; padding-top:80px; }

        .badge-belum  { background:#f8d7da; color:#842029; }
        .badge-tinggi { background:#d4edda; color:#155724; }
        .badge-sedang { background:#fff3cd; color:#856404; }
        .badge-rendah { background:#f8d7da; color:#721c24; }

        /* tombol MERAH (nilai belum ada) */
        .btn-danger-soft {
            background-color:#dc3545;
            border-color:#dc3545;
            color:white;
        }
        .btn-danger-soft:hover {
            background-color:#bb2d3b;
            border-color:#bb2d3b;
            color:white;
        }

        /* tombol EDIT (pink lembut) */
        .btn-pink-soft {
            background-color:#f8cfe3;
            border-color:#f8cfe3;
            color:#7a0044;
        }
        .btn-pink-soft:hover {
            background-color:#eeb6d2;
            border-color:#eeb6d2;
            color:#7a0044;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background:#e11584;">
  <div class="container">
    <span class="navbar-brand fw-bold">Dashboard Dosen</span>
    <div class="ms-auto">
      <a href="dashboard_dsn.php" class="btn btn-outline-light btn-sm me-2">Dashboard</a>
      <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container">

  <div class="mb-4 p-4 rounded text-white" style="background:#e11584;">
    <h4 class="mb-1">Portofolio Mahasiswa</h4>
    <p class="mb-0">Dosen: <?= htmlspecialchars($nama) ?></p>
  </div>

  <div class="card shadow-sm">
    <div class="card-body p-0">

      <table class="table table-striped mb-0 align-middle">
        <thead class="table-dark">
          <tr>
            <th>No</th>
            <th>Mahasiswa</th>
            <th>Judul</th>
            <th>Deskripsi</th>
            <th>Repo</th>
            <th>Nilai</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>

<?php
$sql = "
    SELECT 
        p.id_portofolio,
        p.judul,
        p.deskripsi,
        p.repo_link,
        m.nama AS nama_mahasiswa,
        n.nilai
    FROM portofolio p
    JOIN mahasiswa m ON m.id_mahasiswa = p.id_mahasiswa
    LEFT JOIN nilai n ON n.id_portofolio = p.id_portofolio
    ORDER BY p.id_portofolio ASC
";

$q = mysqli_query($koneksi, $sql);

if (mysqli_num_rows($q) === 0) {
    echo "<tr><td colspan='7' class='text-center p-4'>Belum ada portofolio.</td></tr>";
} else {
    $no = 1;
    while ($p = mysqli_fetch_assoc($q)) {

        if ($p['nilai'] === null) {
            $badge = "badge-belum";
            $nilaiText = "Belum";
        } elseif ($p['nilai'] >= 80) {
            $badge = "badge-tinggi";
            $nilaiText = $p['nilai'];
        } elseif ($p['nilai'] >= 60) {
            $badge = "badge-sedang";
            $nilaiText = $p['nilai'];
        } else {
            $badge = "badge-rendah";
            $nilaiText = $p['nilai'];
        }
?>
<tr>
  <td><?= $no++ ?></td>
  <td><?= htmlspecialchars($p['nama_mahasiswa']) ?></td>
  <td><?= htmlspecialchars($p['judul']) ?></td>
  <td><?= htmlspecialchars(substr($p['deskripsi'],0,50)) ?>...</td>
  <td>
    <?php if ($p['repo_link']): ?>
      <a href="<?= htmlspecialchars($p['repo_link']) ?>" target="_blank">Link</a>
    <?php else: ?>
      -
    <?php endif; ?>
  </td>
  <td><span class="badge <?= $badge ?>"><?= $nilaiText ?></span></td>
  <td>
    <?php if ($p['nilai'] === null): ?>
      <!-- BELUM DINILAI → MERAH -->
      <a href="proses_nilai.php?id_portofolio=<?= $p['id_portofolio'] ?>"
         class="btn btn-sm btn-danger-soft">
         Nilai
      </a>
    <?php else: ?>
      <!-- SUDAH DINILAI → EDIT PINK -->
      <a href="proses_nilai.php?id_portofolio=<?= $p['id_portofolio'] ?>"
         class="btn btn-sm btn-pink-soft">
         Edit
      </a>
    <?php endif; ?>
  </td>
</tr>
<?php }} ?>

        </tbody>
      </table>

    </div>
  </div>
</div>

</body>
</html>
