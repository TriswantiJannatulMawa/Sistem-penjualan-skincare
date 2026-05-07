<?php
session_start();

// Cek apakah user sudah login dan rolenya admin
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../includes/conn.php';

// Proses jika tombol simpan ditekan
if (isset($_POST['simpan'])) {
    $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga       = $_POST['harga'];
    $stok        = $_POST['stok'];
    $deskripsi   = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    // Proses Upload Gambar
    $nama_file   = $_FILES['gambar']['name'];
    $ukuran_file = $_FILES['gambar']['size'];
    $tmp_file    = $_FILES['gambar']['tmp_name'];
    
    // Ekstensi yang diperbolehkan
    $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg');
    $x = explode('.', $nama_file);
    $ekstensi = strtolower(end($x));

    if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
        if ($ukuran_file < 2048000) { // Maksimal 2MB
            // Buat nama file unik agar tidak tertimpa
            $gambar_baru = time() . '-' . $nama_file;
            move_uploaded_file($tmp_file, '../assets/gambar/' . $gambar_baru);

            // Masukkan data ke database
            $query = mysqli_query($conn, "INSERT INTO produk (nama_produk, deskripsi, harga, stok, gambar) 
                                          VALUES ('$nama_produk', '$deskripsi', '$harga', '$stok', '$gambar_baru')");
            
            if ($query) {
                echo "<script>alert('Produk berhasil ditambahkan!'); window.location='produk.php';</script>";
            } else {
                $error = "Gagal menyimpan ke database.";
            }
        } else {
            $error = "Ukuran gambar terlalu besar! Maksimal 2MB.";
        }
    } else {
        $error = "Ekstensi gambar tidak diperbolehkan! Gunakan JPG/PNG.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Produk - MAARS Beauty</title>
  
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/global.css">
  <link rel="stylesheet" href="../assets/css/sidebar.css">

  <style>
    body {
      font-family: 'DM Sans', sans-serif;
      background-color: #fbf6f8;
      display: flex;
    }

    .main {
      flex: 1;
      padding: 40px;
      height: 100vh;
      overflow-y: auto;
    }

    .topbar-modern {
      margin-bottom: 30px;
    }

    .header-text h1 {
      font-family: 'Playfair Display', serif;
      font-size: 28px;
      color: #1a0a12;
      margin-bottom: 6px;
    }

    .header-text p {
      color: #8a6070;
      font-size: 14px;
    }

    /* CARD FORM */
    .card-box {
      background: #ffffff;
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.02);
      max-width: 800px;
    }

    .error-msg {
      background: #fef2f2;
      color: #ef4444;
      padding: 12px 15px;
      border-radius: 10px;
      margin-bottom: 20px;
      font-size: 14px;
      border: 1px solid #fee2e2;
    }

    /* FORM STYLING */
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group.full {
      grid-column: 1 / -1;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      color: #1a0a12;
      font-size: 13px;
      font-weight: 600;
    }

    .form-control {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid #eee;
      border-radius: 10px;
      font-family: 'DM Sans', sans-serif;
      font-size: 14px;
      background: #fbf6f8;
      outline: none;
      transition: 0.2s;
    }

    .form-control:focus {
      background: #ffffff;
      border-color: #ff4f81;
    }

    textarea.form-control {
      height: 100px;
      resize: vertical;
    }

    /* FILE INPUT STYLING */
    input[type="file"] {
      background: #ffffff;
      padding: 10px;
      border: 1px dashed #ccc;
    }

    .btn-submit {
      background: #ff4f81;
      color: white;
      border: none;
      padding: 12px 25px;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      margin-top: 10px;
      display: inline-block;
    }

    .btn-submit:hover {
      background: #e63e6d;
    }

    .btn-kembali {
      background: #f0f0f0;
      color: #444;
      text-decoration: none;
      padding: 12px 25px;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 500;
      margin-left: 10px;
      display: inline-block;
    }

    .btn-kembali:hover {
      background: #e0e0e0;
    }
  </style>
</head>
<body>

  <?php include '../includes/sidebar_admin.php'; ?>

  <div class="main">
    
    <div class="topbar-modern">
      <div class="header-text">
        <h1>Tambah Produk Baru</h1>
        <p>Masukkan detail produk skincare untuk ditambahkan ke etalase.</p>
      </div>
    </div>

    <div class="card-box">
      
      <?php if (isset($error)): ?>
        <div class="error-msg"><?= $error; ?></div>
      <?php endif; ?>

      <form action="" method="POST" enctype="multipart/form-data">
        
        <div class="form-grid">
          <div class="form-group full">
            <label>Nama Produk</label>
            <input type="text" name="nama_produk" class="form-control" placeholder="Contoh: Skintific 5X Ceramide" required>
          </div>

          <div class="form-group">
            <label>Harga (Rp)</label>
            <input type="number" name="harga" class="form-control" placeholder="Contoh: 150000" required>
          </div>

          <div class="form-group">
            <label>Stok Produk</label>
            <input type="number" name="stok" class="form-control" placeholder="Contoh: 50" required>
          </div>

          <div class="form-group full">
            <label>Deskripsi Produk</label>
            <textarea name="deskripsi" class="form-control" placeholder="Jelaskan manfaat dan kandungan produk..."></textarea>
          </div>

          <div class="form-group full">
            <label>Foto Produk (Maks. 2MB)</label>
            <input type="file" name="gambar" class="form-control" accept="image/png, image/jpeg, image/jpg" required>
          </div>
        </div>

        <button type="submit" name="simpan" class="btn-submit">Simpan Produk</button>
        <a href="produk.php" class="btn-kembali">Batal</a>

      </form>
    </div>

  </div>

</body>
</html>