<?php
include '../conn.php';
include '../includes/sidebar_admin.php';

// Ambil data berdasarkan ID
$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk='$id'");
$row = mysqli_fetch_assoc($data);

// Jika tombol update ditekan
if (isset($_POST['update'])) {

    $nama  = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok  = $_POST['stok'];

    // Upload gambar baru (optional)
    if ($_FILES['gambar']['name'] != "") {

        $gambar = $_FILES['gambar']['name'];
        $tmp    = $_FILES['gambar']['tmp_name'];

        move_uploaded_file($tmp, "../gambar/" . $gambar);

        mysqli_query($conn, "UPDATE produk SET 
            nama_produk='$nama',
            harga='$harga',
            stok='$stok',
            gambar='$gambar'
            WHERE id_produk='$id'
        ");

    } else {

        mysqli_query($conn, "UPDATE produk SET 
            nama_produk='$nama',
            harga='$harga',
            stok='$stok'
            WHERE id_produk='$id'
        ");
    }

    echo "<script>
        alert('Produk berhasil diupdate!');
        window.location='produk.php';
    </script>";
}
?>

<?php
include '../conn.php';

// ambil data
$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk='$id'");
$row = mysqli_fetch_assoc($data);

// proses update
if(isset($_POST['update'])){
  $nama = $_POST['nama'];
  $harga = $_POST['harga'];
  $stok = $_POST['stok'];

  $gambar = $_FILES['gambar']['name'];
  $tmp = $_FILES['gambar']['tmp_name'];

  $folder = "../gambar/";

  // kalau upload gambar baru
  if($gambar != ""){

    move_uploaded_file($tmp, $folder.$gambar);

    mysqli_query($conn, "UPDATE produk SET
      nama_produk='$nama',
      harga='$harga',
      stok='$stok',
      gambar='$gambar'
      WHERE id_produk='$id'
    ");

  } else {

    // kalau tidak ganti gambar
    mysqli_query($conn, "UPDATE produk SET
      nama_produk='$nama',
      harga='$harga',
      stok='$stok'
      WHERE id_produk='$id'
    ");
  }

  header("Location: produk.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Produk</title>

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

img {
    margin-bottom: 10px;
    border-radius: 10px;
}
</style>
</head>

<body>

<div class="container">
  <div class="login-container">
    <h2>Edit Produk</h2>

    <form method="POST" enctype="multipart/form-data">
      
      <input type="text" name="nama" value="<?= $row['nama_produk']; ?>" required>

      <input type="number" name="harga" value="<?= $row['harga']; ?>" required>

      <input type="number" name="stok" value="<?= $row['stok']; ?>" required>

      <!-- tampilkan gambar lama -->
      <img src="../gambar/<?= $row['gambar']; ?>" width="100">

      <!-- upload baru (optional) -->
      <input type="file" name="gambar">

      <button type="submit" name="update">Update</button>
    </form>

  </div>
</div>

</body>
</html>