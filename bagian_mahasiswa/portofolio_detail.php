<?php
session_start();
include "koneksi.php";

/*
  portofolio_detail.php
  Modes:
    - add       -> ?mode=add
    - edit      -> ?id=...&mode=edit
    - delete    -> ?id=...&mode=delete
    - view      -> ?id=...
  Notes:
    - Upload gambar wajib pada tambah & edit
    - Menghapus file gambar saat delete / replace
    - id_portofolio auto increment
*/

// Pastikan mahasiswa login
if (!isset($_SESSION['id_mahasiswa'])) {
    header("Location: home.html");
    exit;
}

$id_mahasiswa = $_SESSION['id_mahasiswa'];
$nama = $_SESSION['nama'] ?? 'Mahasiswa';

$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
// default: kalau ada id -> view, kalau tidak ada -> add
if ($mode === '') {
    $mode = isset($_GET['id']) ? 'view' : 'add';
}

// helper untuk upload gambar (mengembalikan filename atau false)
function upload_gambar_wajib($file_field = 'gambar') {
    if (!isset($_FILES[$file_field]) || $_FILES[$file_field]['error'] !== 0) {
        return false;
    }

    $allowed_types = ['image/jpeg','image/jpg','image/png','image/gif'];
    $max_size = 2 * 1024 * 1024; // 2MB

    $type = $_FILES[$file_field]['type'];
    $size = $_FILES[$file_field]['size'];

    if (!in_array($type, $allowed_types)) {
        return "TYPE_ERR";
    }
    if ($size > $max_size) {
        return "SIZE_ERR";
    }

    $target_dir = "uploads/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

    $ext = pathinfo($_FILES[$file_field]['name'], PATHINFO_EXTENSION);
    $filename = uniqid('img_') . '_' . time() . '.' . $ext;
    $target = $target_dir . $filename;

    if (move_uploaded_file($_FILES[$file_field]['tmp_name'], $target)) {
        return $filename;
    } else {
        return false;
    }
}

// fungsi hapus file gambar bila ada
function hapus_file_gambar($filename) {
    if ($filename && file_exists("uploads/" . $filename)) {
        @unlink("uploads/" . $filename);
    }
}

// pesan (feedback)
$flash_success = '';
$flash_error = '';

// ---------- MODE: ADD ----------
if ($mode === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = bersihkan_input($_POST['judul'] ?? '');
    $deskripsi = bersihkan_input($_POST['deskripsi'] ?? '');
    $repo_link = bersihkan_input($_POST['repo_link'] ?? '');

    // Validasi wajib
    if ($judul === '' || $deskripsi === '') {
        $flash_error = "Judul dan Deskripsi wajib diisi.";
    } else {
        $uploaded = upload_gambar_wajib('gambar');
        if ($uploaded === false) {
            $flash_error = "Gambar wajib diupload dan gagal diunggah.";
        } elseif ($uploaded === 'TYPE_ERR') {
            $flash_error = "Format gambar tidak didukung. Gunakan JPG/PNG/GIF.";
        } elseif ($uploaded === 'SIZE_ERR') {
            $flash_error = "Ukuran gambar terlalu besar. Maksimal 2MB.";
        } else {
            // masukkan ke DB
            $sql = "INSERT INTO portofolio (id_mahasiswa, judul, deskripsi, gambar, repo_link)
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("issss", $id_mahasiswa, $judul, $deskripsi, $uploaded, $repo_link);
            if ($stmt->execute()) {
                $new_id = $stmt->insert_id;
                $flash_success = "Proyek berhasil ditambahkan.";
                header("Location: portofolio_detail.php?id=" . $new_id);
                exit;
            } else {
                // hapus file yang sudah diupload jika insert gagal
                hapus_file_gambar($uploaded);
                $flash_error = "Gagal menyimpan ke database: " . $koneksi->error;
            }
        }
    }
}

// ---------- MODE: EDIT ----------
if ($mode === 'edit') {
    if (!isset($_GET['id']) && !isset($_POST['id_portofolio'])) {
        die("ID portofolio tidak ditentukan untuk edit.");
    }
    $id_portofolio = isset($_POST['id_portofolio']) ? (int)$_POST['id_portofolio'] : (int)$_GET['id'];

    // cek kepemilikan
    $q = $koneksi->prepare("SELECT * FROM portofolio WHERE id_portofolio = ? AND id_mahasiswa = ?");
    $q->bind_param("ii", $id_portofolio, $id_mahasiswa);
    $q->execute();
    $res = $q->get_result();
    if ($res->num_rows === 0) {
        die("Akses ditolak atau data tidak ditemukan.");
    }
    $row = $res->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $judul = bersihkan_input($_POST['judul'] ?? '');
        $deskripsi = bersihkan_input($_POST['deskripsi'] ?? '');
        $repo_link = bersihkan_input($_POST['repo_link'] ?? '');

        if ($judul === '' || $deskripsi === '') {
            $flash_error = "Judul dan Deskripsi wajib diisi.";
        } else {
            // Gambar wajib pada edit juga: harus upload baru
            $uploaded = upload_gambar_wajib('gambar');
            if ($uploaded === false) {
                $flash_error = "Gambar wajib diupload dan gagal diunggah.";
            } elseif ($uploaded === 'TYPE_ERR') {
                $flash_error = "Format gambar tidak didukung. Gunakan JPG/PNG/GIF.";
            } elseif ($uploaded === 'SIZE_ERR') {
                $flash_error = "Ukuran gambar terlalu besar. Maksimal 2MB.";
            } else {
                // hapus file lama
                hapus_file_gambar($row['gambar']);

                // update DB
                $sql = "UPDATE portofolio SET judul = ?, deskripsi = ?, gambar = ?, repo_link = ? WHERE id_portofolio = ? AND id_mahasiswa = ?";
                $stmt = $koneksi->prepare($sql);
                $stmt->bind_param("ssssii", $judul, $deskripsi, $uploaded, $repo_link, $id_portofolio, $id_mahasiswa);
                if ($stmt->execute()) {
                    $flash_success = "Proyek berhasil diperbarui.";
                    header("Location: portofolio_detail.php?id=" . $id_portofolio);
                    exit;
                } else {
                    // hapus image baru jika gagal update
                    hapus_file_gambar($uploaded);
                    $flash_error = "Gagal memperbarui proyek: " . $koneksi->error;
                }
            }
        }
    }
}

// ---------- MODE: DELETE ----------
if ($mode === 'delete') {
    if (!isset($_GET['id']) && !isset($_POST['id'])) {
        die("ID portofolio tidak ditentukan untuk delete.");
    }
    $id_portofolio = isset($_POST['id']) ? (int)$_POST['id'] : (int)$_GET['id'];

    // cek kepemilikan
    $q = $koneksi->prepare("SELECT gambar FROM portofolio WHERE id_portofolio = ? AND id_mahasiswa = ?");
    $q->bind_param("ii", $id_portofolio, $id_mahasiswa);
    $q->execute();
    $res = $q->get_result();
    if ($res->num_rows === 0) {
        die("Akses ditolak atau data tidak ditemukan.");
    }
    $row = $res->fetch_assoc();

    // hapus record
    $del = $koneksi->prepare("DELETE FROM portofolio WHERE id_portofolio = ? AND id_mahasiswa = ?");
    $del->bind_param("ii", $id_portofolio, $id_mahasiswa);
    if ($del->execute()) {
        // hapus file gambar juga
        hapus_file_gambar($row['gambar']);
        $_SESSION['success'] = "Proyek berhasil dihapus.";
        header("Location: dashboard_mhs.php");
        exit;
    } else {
        die("Gagal menghapus: " . $koneksi->error);
    }
}

// ---------- MODE: VIEW (default) ----------
$detail = null;
if ($mode === 'view') {
    if (!isset($_GET['id'])) {
        die("ID portofolio tidak ditentukan.");
    }
    $id_portofolio = (int)$_GET['id'];

    // cek kepemilikan dan ambil data
    $q = $koneksi->prepare("SELECT * FROM portofolio WHERE id_portofolio = ? AND id_mahasiswa = ?");
    $q->bind_param("ii", $id_portofolio, $id_mahasiswa);
    $q->execute();
    $res = $q->get_result();
    if ($res->num_rows === 0) {
        die("Akses ditolak atau data tidak ditemukan.");
    }
    $detail = $res->fetch_assoc();
}

// Untuk mode edit: jika belum POST, ambil data utk form
$edit_data = null;
if ($mode === 'edit' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    // id di query
    $id_portofolio = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $q = $koneksi->prepare("SELECT * FROM portofolio WHERE id_portofolio = ? AND id_mahasiswa = ?");
    $q->bind_param("ii", $id_portofolio, $id_mahasiswa);
    $q->execute();
    $res = $q->get_result();
    if ($res->num_rows === 0) {
        die("Akses ditolak atau data tidak ditemukan.");
    }
    $edit_data = $res->fetch_assoc();
}

// UI START
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Portofolio — <?= htmlspecialchars($nama) ?></title>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
<style>
  body { font-family: 'Poppins', sans-serif; background:#f9f0f5; padding-top:80px; }
  .header { background: linear-gradient(135deg,#FD5DA8,#e11584); color:#fff; padding:20px; border-radius:12px; margin-bottom:20px; box-shadow:0 6px 18px rgba(225,21,132,0.12);}
  .card-pink { background:white; border-radius:12px; padding:20px; border:1px solid #ffe4f3; box-shadow:0 6px 18px rgba(225,21,132,0.04); }
  .btn-pink { background: linear-gradient(135deg,#e11584,#ff69b4); color:#fff; border:none; }
  .btn-pink:hover { opacity:0.95; }
  .label-box { background:#fff0f6; padding:10px; border-radius:8px; border-left:4px solid #e11584; }
  .img-preview { max-width:320px; max-height:320px; border-radius:8px; border:2px solid #ddd; display:block; }
  .small-muted { color:#666; font-size:14px; }
  .flash-success { background:#d4edda; padding:10px; border-left:4px solid #28a745; border-radius:8px; margin-bottom:12px; }
  .flash-error { background:#f8d7da; padding:10px; border-left:4px solid #dc3545; border-radius:8px; margin-bottom:12px; }
  @media(max-width:768px){ .img-preview{max-width:100%;height:auto;} }
</style>
</head>
<body>

<div class="container">
  <div class="header">
    <h2 class="mb-0">Portofolio — <?= htmlspecialchars($nama) ?></h2>
    <small>Halaman portofolio (tambah / edit / hapus / detail)</small>
  </div>

  <?php if ($flash_success): ?>
    <div class="flash-success"><?= htmlspecialchars($flash_success) ?></div>
  <?php endif; ?>
  <?php if ($flash_error): ?>
    <div class="flash-error"><?= htmlspecialchars($flash_error) ?></div>
  <?php endif; ?>
  <?php if (isset($_SESSION['success'])): ?>
    <div class="flash-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>

  <div class="card-pink">

  <?php if ($mode === 'add'): ?>
    <h4>➕ Tambah Proyek Portofolio</h4>
    <form method="POST" enctype="multipart/form-data" class="mt-3">
      <div class="mb-3">
        <label class="form-label">Judul Proyek *</label>
        <input type="text" name="judul" class="form-control" required value="<?= isset($_POST['judul'])?htmlspecialchars($_POST['judul']):'' ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Deskripsi *</label>
        <textarea name="deskripsi" class="form-control" rows="6" required><?= isset($_POST['deskripsi'])?htmlspecialchars($_POST['deskripsi']):'' ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Gambar Proyek * (JPG/PNG/GIF, max 2MB)</label>
        <input type="file" name="gambar" accept="image/*" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Link Repository</label>
        <input type="url" name="repo_link" class="form-control" value="<?= isset($_POST['repo_link'])?htmlspecialchars($_POST['repo_link']):'' ?>">
      </div>

      <div class="d-flex gap-2">
        <a href="dashboard_mhs.php" class="btn btn-secondary">← Kembali</a>
        <button type="submit" class="btn btn-pink">💾 Simpan Proyek</button>
      </div>
    </form>

  <?php elseif ($mode === 'edit'): ?>
    <h4>✏️ Edit Proyek</h4>

    <?php
      // gunakan $edit_data (diambil lebih atas)
      $e = $edit_data;
    ?>
    <form method="POST" enctype="multipart/form-data" class="mt-3">
      <input type="hidden" name="id_portofolio" value="<?= htmlspecialchars($e['id_portofolio']) ?>">
      <div class="mb-3">
        <label class="form-label">Judul Proyek *</label>
        <input type="text" name="judul" class="form-control" required value="<?= htmlspecialchars($e['judul']) ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Deskripsi *</label>
        <textarea name="deskripsi" class="form-control" rows="6" required><?= htmlspecialchars($e['deskripsi']) ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Gambar Proyek Baru * (unggah menggantikan gambar lama)</label>
        <input type="file" name="gambar" accept="image/*" class="form-control" required>
        <div class="small-muted mt-2">Gambar lama:</div>
        <?php if (!empty($e['gambar']) && file_exists("uploads/" . $e['gambar'])): ?>
          <img src="uploads/<?= htmlspecialchars($e['gambar']) ?>" class="img-preview mt-2" alt="Gambar lama">
        <?php else: ?>
          <div class="label-box mt-2">Tidak ada gambar lama</div>
        <?php endif; ?>
      </div>

      <div class="mb-3">
        <label class="form-label">Link Repository</label>
        <input type="url" name="repo_link" class="form-control" value="<?= htmlspecialchars($e['repo_link']) ?>">
      </div>

      <div class="d-flex gap-2">
        <a href="portofolio_detail.php?id=<?= htmlspecialchars($e['id_portofolio']) ?>" class="btn btn-secondary">← Batal</a>
        <button type="submit" class="btn btn-pink">💾 Perbarui Proyek</button>
      </div>
    </form>

  <?php elseif ($mode === 'view' && $detail): ?>
    <h4>📁 Detail Proyek</h4>

    <div class="detail-section mt-3">
      <div class="detail-item mb-3">
        <label class="form-label">Judul Proyek</label>
        <div class="label-box"><?= htmlspecialchars($detail['judul']) ?></div>
      </div>

      <div class="detail-item mb-3">
        <label class="form-label">Deskripsi</label>
        <div class="label-box"><?= nl2br(htmlspecialchars($detail['deskripsi'])) ?></div>
      </div>

      <div class="detail-item mb-3">
        <label class="form-label">Gambar Proyek</label>
        <?php if (!empty($detail['gambar']) && file_exists("uploads/" . $detail['gambar'])): ?>
          <div class="mt-2">
            <img src="uploads/<?= htmlspecialchars($detail['gambar']) ?>" class="img-preview" alt="Gambar proyek">
          </div>
        <?php else: ?>
          <div class="label-box">Tidak ada gambar</div>
        <?php endif; ?>
      </div>

      <div class="detail-item mb-3">
        <label class="form-label">Link Repository</label>
        <?php if (!empty($detail['repo_link'])): ?>
          <div class="label-box"><a href="<?= htmlspecialchars($detail['repo_link']) ?>" target="_blank"><?= htmlspecialchars($detail['repo_link']) ?></a></div>
        <?php else: ?>
          <div class="label-box">Tidak tersedia</div>
        <?php endif; ?>
      </div>
    </div>

    <div class="d-flex gap-2 mt-3">
      <a href="dashboard_mhs.php" class="btn btn-secondary">← Kembali</a>
      <a href="portofolio_detail.php?id=<?= htmlspecialchars($detail['id_portofolio']) ?>&mode=edit" class="btn btn-pink">✏ Edit</a>

      <form method="POST" action="portofolio_detail.php?mode=delete" onsubmit="return confirm('Yakin ingin menghapus proyek ini? Semua file gambar juga akan terhapus.');" style="display:inline;">
        <input type="hidden" name="id" value="<?= htmlspecialchars($detail['id_portofolio']) ?>">
        <button type="submit" class="btn btn-danger">🗑 Hapus</button>
      </form>
    </div>

  <?php else: ?>
    <div class="label-box">
      Mode tidak dikenali atau data tidak ditemukan.
    </div>
  <?php endif; ?>

  </div> <!-- card -->
</div> <!-- container -->

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
