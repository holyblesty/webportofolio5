<?php
session_set_cookie_params(0);
session_start();
include "../koneksi.php";

// cek login mahasiswa
if (!isset($_SESSION['id_mahasiswa']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../index.php");
    exit;
}

$id_mahasiswa = $_SESSION['id_mahasiswa'];

// ambil nama mahasiswa
$qNama = mysqli_query(
    $koneksi,
    "SELECT nama FROM mahasiswa WHERE id_mahasiswa='$id_mahasiswa'"
);
$dataNama = mysqli_fetch_assoc($qNama);
$nama = $dataNama['nama'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Mahasiswa</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background:#f9f0f5;
        }

        /* header pink */
        .card-header-pink {
            background:#e11584;
            color:white;
        }

        /* menu list (sama dengan dosen) */
        .list-group-item {
            border: none;
            padding: 12px 16px;
        }

        .list-group-item-action:hover {
            background:#f8cfe3;
            color:#7a0044;
        }

        .logout-item {
            color:#dc3545;
        }
        .logout-item:hover {
            background:#f8d7da;
            color:#842029;
        }

        /* table */
        img.preview {
            max-width:80px;
            border-radius:6px;
        }
    </style>
</head>
<body>

<div class="container mt-4">

    <!-- HEADER -->
    <div class="card mb-3 shadow-sm">
        <div class="card-header card-header-pink">
            <h4 class="mb-0">Dashboard Mahasiswa</h4>
        </div>
        <div class="card-body">
            <p class="mb-0">
                Selamat datang, <strong><?= htmlspecialchars($nama) ?></strong>
            </p>
        </div>
    </div>

    <!-- MENU (DISAMAIN DENGAN DOSEN) -->
    <div class="list-group mb-3 shadow-sm">
        <a href="portofolio_detail.php" class="list-group-item list-group-item-action">
            ➕ Tambah Portofolio
        </a>
        <a href="lihat_nilai.php" class="list-group-item list-group-item-action">
            📊 Lihat Nilai
        </a>
        <a href="ganti_password_mhs.php" class="list-group-item list-group-item-action">
            🔑 Ganti Password
        </a>
        <a href="../logout.php" class="list-group-item list-group-item-action logout-item">
            🚪 Logout
        </a>
    </div>

    <!-- TABEL PORTOFOLIO -->
    <div class="card shadow-sm">
        <div class="card-body p-0">

        <table class="table table-bordered align-middle mb-0">
            <thead class="table-danger text-center">
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Gambar</th>
                    <th>Judul</th>
                    <th width="15%">Repository</th>
                    <th width="25%">Kelola</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $q = mysqli_query(
                $koneksi,
                "SELECT * FROM portofolio WHERE id_mahasiswa='$id_mahasiswa'"
            );

            if (mysqli_num_rows($q) == 0) {
                echo "<tr><td colspan='5' class='text-center'>Belum ada portofolio.</td></tr>";
            } else {
                $no = 1;
                while ($p = mysqli_fetch_assoc($q)) {
            ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td class="text-center">
                        <?php if ($p['gambar']) { ?>
                            <img src="../uploads/<?= htmlspecialchars($p['gambar']) ?>" class="preview">
                        <?php } else { echo "-"; } ?>
                    </td>
                    <td><?= htmlspecialchars($p['judul']) ?></td>
                    <td class="text-center">
                        <?php if ($p['repo_link']) { ?>
                            <a href="<?= htmlspecialchars($p['repo_link']) ?>" target="_blank">Repo</a>
                        <?php } else { echo "-"; } ?>
                    </td>
                    <td class="text-center">
                        <a href="portofolio_detail.php?id=<?= $p['id_portofolio'] ?>"
                           class="btn btn-outline-primary btn-sm">
                           Edit
                        </a>
                        <a href="portofolio_detail.php?mode=hapus&id=<?= $p['id_portofolio'] ?>"
                           onclick="return confirm('Hapus portofolio?')"
                           class="btn btn-outline-danger btn-sm">
                           Hapus
                        </a>
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
