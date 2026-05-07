<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: ../login.php");
    exit;
}
include '../includes/conn.php';
$id_pelanggan = $_SESSION['id_pelanggan'];

// Proses Update Profil
if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    
    $update = mysqli_query($conn, "UPDATE pelanggan SET nama='$nama', no_hp='$no_hp', alamat='$alamat' WHERE id_pelanggan='$id_pelanggan'");
    if ($update) {
        $sukses = "Profil berhasil diperbarui!";
    }
}

// Ambil data terbaru
$q_profil = mysqli_query($conn, "SELECT * FROM pelanggan WHERE id_pelanggan='$id_pelanggan'");
$data = mysqli_fetch_assoc($q_profil);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Profil Saya - MAARS Beauty</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/global.css">
  <link rel="stylesheet" href="../assets/css/sidebar.css">
  <style>
    body { font-family: 'DM Sans', sans-serif; background-color: #fbf6f8; display: flex; }
    .main { flex: 1; padding: 40px; height: 100vh; overflow-y: auto; }
    .header-text h1 { font-family: 'Playfair Display', serif; font-size: 28px; color: #1a0a12; margin-bottom: 6px; }
    .card-box { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); max-width: 600px; margin-top: 20px; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 8px; color: #1a0a12; font-weight: 600; font-size: 13px; }
    .form-control { width: 100%; padding: 12px; border: 1px solid #eee; border-radius: 10px; font-family: 'DM Sans', sans-serif; background: #fbf6f8; outline: none; }
    textarea.form-control { resize: vertical; height: 80px; }
    .btn-submit { background: #ff4f81; color: white; border: none; padding: 12px 25px; border-radius: 10px; cursor: pointer; font-weight: 500; margin-top: 10px; }
    .alert-success { background: #eaffea; color: #2ecc71; padding: 12px; border-radius: 10px; margin-bottom: 15px; font-size: 13px; }
  </style>
</head>
<body>
  <?php include '../includes/sidebar_user.php'; ?>
  <div class="main">
    <div class="header-text">
      <h1>Profil Saya</h1>
    </div>
    <div class="card-box">
      <?php if(isset($sukses)) echo "<div class='alert-success'>$sukses</div>"; ?>
      <form action="" method="POST">
        <div class="form-group">
          <label>Email (Tidak bisa diubah)</label>
          <input type="text" class="form-control" value="<?= $data['email']; ?>" readonly style="background: #eee; color: #888;">
        </div>
        <div class="form-group">
          <label>Nama Lengkap</label>
          <input type="text" name="nama" class="form-control" value="<?= $data['nama']; ?>" required>
        </div>
        <div class="form-group">
          <label>Nomor HP</label>
          <input type="text" name="no_hp" class="form-control" value="<?= $data['no_hp']; ?>" required>
        </div>
        <div class="form-group">
          <label>Alamat Lengkap</label>
          <textarea name="alamat" class="form-control" required><?= $data['alamat']; ?></textarea>
        </div>
        <button type="submit" name="update" class="btn-submit">Simpan Perubahan</button>
      </form>
    </div>
  </div>
</body>
</html>