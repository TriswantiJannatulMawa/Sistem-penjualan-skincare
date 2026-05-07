<?php
include '../conn.php';

if(isset($_POST['simpan'])){
  $nama = $_POST['nama'];
  $harga = $_POST['harga'];
  $stok = $_POST['stok'];

  $gambar = $_FILES['gambar']['name'];
  $tmp = $_FILES['gambar']['tmp_name'];

  // folder upload
  $folder = "../gambar/";

  if(!is_dir($folder)){
    mkdir($folder);
  }

  // upload gambar
  if(move_uploaded_file($tmp, $folder.$gambar)){
    
    mysqli_query($conn, "INSERT INTO produk 
    (nama_produk, harga, stok, gambar) 
    VALUES ('$nama','$harga','$stok','$gambar')");

    header("Location: produk.php");
    exit;

  } else {
    echo "Upload gambar gagal!";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Tambah Produk</title>

<style>
body { font-family:sans-serif; background:#ffe5ec; padding:20px; }

.container {
    display: flex;
    justify-content: center;
}

.login-container {
    background: white;
    padding: 40px;
    border-radius: 30px;
    box-shadow: 0 20px 50px rgba(141, 110, 99, 0.15);
    width: 100%;
    max-width: 400px;
    text-align: center;
}

.login-container h2 {
    color: #332B24;
    margin-bottom: 10px;
}

input {
    width: 100%;
    padding: 15px;
    margin-bottom: 15px;
    border-radius: 12px;
    border: 1px solid #ddd;
}

button {
    width: 100%;
    padding: 15px;
    background: #ff7aa2;
    color: white;
    border: none;
    border-radius: 12px;
    cursor: pointer;
}

button:hover {
    background: #ff4f81;
}
</style>
</head>

<body>

<div class="container">
  <div class="login-container">
    <h2>Tambah Produk</h2>

    <!-- FIX FORM -->
    <form method="POST" enctype="multipart/form-data">
      <input type="text" name="nama" placeholder="Nama Produk" required>
      <input type="number" name="harga" placeholder="Harga" required>
      <input type="number" name="stok" placeholder="Stok" required>
      <input type="file" name="gambar" required>

      <!-- FIX BUTTON -->
      <button type="submit" name="simpan">Simpan</button>
    </form>

  </div>
</div>

</body>
</html>