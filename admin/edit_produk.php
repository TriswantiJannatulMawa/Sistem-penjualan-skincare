<?php
session_start();

// Cek apakah user sudah login dan rolenya admin
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../includes/conn.php';

// Ambil ID produk dari URL
$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Ambil data produk yang saat ini ada di database
$query_data = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk = '$id'");
$data = mysqli_fetch_assoc($query_data);

// Jika data tidak ditemukan, kembalikan ke halaman produk
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='produk.php';</script>";
    exit;
}

// PROSES UPDATE DATA
if (isset($_POST['update'])) {
    $id_produk   = $_POST['id_produk']; // Diambil dari input type="hidden"
    $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga       = $_POST['harga'];
    $stok        = $_POST['stok'];
    $deskripsi   = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $gambar_lama = $_POST['gambar_lama']; // Nama file lama untuk referensi

    $nama_file   = $_FILES['gambar']['name'];
    $tmp_file    = $_FILES['gambar']['tmp_name'];
    $ukuran_file = $_FILES['gambar']['size'];

    // CEK APAKAH ADMIN UPLOAD GAMBAR BARU
    if ($nama_file != "") {
        // Logika jika GANTI gambar
        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg');
        $x = explode('.', $nama_file);
        $ekstensi = strtolower(end($x));

        if (in_array($ekstensi, $ekstensi_diperbolehkan)) {
            if ($ukuran_file < 2048000) {
                $gambar_baru = time() . '-' . $nama_file;
                move_uploaded_file($tmp_file, '../assets/gambar/' . $gambar_baru);

                // Hapus gambar lama dari folder agar tidak menumpuk (mencegah duplikasi file)
                if (file_exists('../assets/gambar/' . $gambar_lama) && $gambar_lama != "") {
                    unlink('../assets/gambar/' . $gambar_lama);
                }

                // Query UPDATE dengan merubah kolom gambar
                $query = mysqli_query($conn, "UPDATE produk SET 
                    nama_produk = '$nama_produk',
                    deskripsi = '$deskripsi',
                    harga = '$harga',
                    stok = '$stok',
                    gambar = '$gambar_baru'
                    WHERE id_produk = '$id_produk'");
            } else {
                $error = "Ukuran gambar terlalu besar! Maksimal 2MB.";
            }
        } else {
            $error = "Ekstensi tidak diperbolehkan! Gunakan JPG/PNG.";
        }
    } else {
        // Logika jika TIDAK GANTI gambar (gambar lama tetap dipakai)
        // Query UPDATE tanpa mengubah kolom gambar
        $query = mysqli_query($conn, "UPDATE produk SET 
            nama_produk = '$nama_produk',
            deskripsi = '$deskripsi',
            harga = '$harga',
            stok = '$stok'
            WHERE id_produk = '$id_produk'");
    }

    if (isset($query) && $query) {
        echo "<script>alert('Produk berhasil diperbarui!'); window.location='produk.php';</script>";
    } else if (!isset($error)) {
        $error = "Gagal memperbarui data di database.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Produk - MAARS Beauty</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/global.css">
  <link rel="stylesheet" href="../assets/css/sidebar.css">
  <style>
    /* Styling identik dengan tambah_produk.php */
    body { font-family: 'DM Sans', sans-serif; background-color: #fbf6f8; display: flex; }
    .main { flex: 1; padding: 40px; height: 100vh; overflow-y: auto; }
    .topbar-modern { margin-bottom: 30px; }
    .header-text h1 { font-family: 'Playfair Display', serif; font-size: 28px; color: #1a0a12; margin-bottom: 6px; }
    .header-text p { color: #8a6070; font-size: 14px; }
    .card-box { background: #ffffff; border-radius: 20px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); max-width: 800px; }
    .error-msg { background: #fef2f2; color: #ef4444; padding: 12px 15px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; border: 1px solid #fee2e2; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .form-group { margin-bottom: 15px; }
    .form-group.full { grid-column: 1 / -1; }
    .form-group label { display: block; margin-bottom: 8px; color: #1a0a12; font-size: 13px; font-weight: 600; }
    .form-control { width: 100%; padding: 12px 15px; border: 1px solid #eee; border-radius: 10px; font-family: 'DM Sans', sans-serif; font-size: 14px; background: #fbf6f8; outline: none; transition: 0.2s; }
    .form-control:focus { background: #ffffff; border-color: #ff4f81; }
    textarea.form-control { height: 100px; resize: vertical; }
    input[type="file"] { background: #ffffff; padding: 10px; border: 1px dashed #ccc; }
    .btn-submit { background: #ff4f81; color: white; border: none; padding: 12px 25px; border-radius: 10px; font-size: 14px; font-weight: 500; cursor: pointer; margin-top: 10px; display: inline-block; }
    .btn-submit:hover { background: #e63e6d; }
    .btn-kembali { background: #f0f0f0; color: #444; text-decoration: none; padding: 12px 25px; border-radius: 10px; font-size: 14px; font-weight: 500; margin-left: 10px; display: inline-block; }
    .btn-kembali:hover { background: #e0e0e0; }
    .img-preview { width: 80px; height: 80px; object-fit: cover; border-radius: 10px; border: 2px solid #eee; margin-top: 10px; display: block; }
  </style>
</head>
<body>

  <?php include '../includes/sidebar_admin.php'; ?>

  <div class="main">
    <div class="topbar-modern">
      <div class="header-text">
        <h1>Edit Produk</h1>
        <p>Perbarui informasi detail produk atau stok barang.</p>
      </div>
    </div>

    <div class="card-box">
      <?php if (isset($error)): ?>
        <div class="error-msg"><?= $error; ?></div>
      <?php endif; ?>

      <form action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_produk" value="<?= $data['id_produk']; ?>">
        <input type="hidden" name="gambar_lama" value="<?= $data['gambar']; ?>">
        
        <div class="form-grid">
          <div class="form-group full">
            <label>Nama Produk</label>
            <input type="text" name="nama_produk" class="form-control" value="<?= $data['nama_produk']; ?>" required>
          </div>

          <div class="form-group">
            <label>Harga (Rp)</label>
            <input type="number" name="harga" class="form-control" value="<?= $data['harga']; ?>" required>
          </div>

          <div class="form-group">
            <label>Stok Produk</label>
            <input type="number" name="stok" class="form-control" value="<?= $data['stok']; ?>" required>
          </div>

          <div class="form-group full">
            <label>Deskripsi Produk</label>
            <textarea name="deskripsi" class="form-control"><?= $data['deskripsi']; ?></textarea>
          </div>

          <div class="form-group full">
            <label>Ganti Foto Produk (Kosongkan jika tidak ingin ganti)</label>
            <input type="file" name="gambar" class="form-control" accept="image/png, image/jpeg, image/jpg">
            <br>
            <small style="color: #888;">Gambar saat ini:</small>
            <img src="../assets/gambar/<?= $data['gambar']; ?>" class="img-preview" alt="Preview Gambar">
          </div>
        </div>

        <button type="submit" name="update" class="btn-submit">Simpan Perubahan</button>
        <a href="produk.php" class="btn-kembali">Batal</a>
      </form>
    </div>
  </div>

</body>
</html>