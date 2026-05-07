<?php
session_start();
include "../conn.php";

if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: ../login.php");
    exit;
}

// ambil data produk dari database
$data = mysqli_query($conn, "SELECT * FROM produk");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Produk Skincare</title>

<link rel="stylesheet" href="style.css">

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

body {
  background: #ffe5ec;
  display: flex;
}

/* SIDEBAR */
.sidebar {
  width: 240px;
  background: #fff;
  height: 100vh;
  padding: 20px;
  box-shadow: 2px 0 10px rgba(0,0,0,0.05);
}

.sidebar h2 {
  color: #ff7aa2;
  margin-bottom: 30px;
}

.menu {
  list-style: none;
}

.menu li {
  margin-bottom: 10px;
}

.menu a {
  display: block;
  padding: 12px;
  border-radius: 10px;
  text-decoration: none;
  color: black;
}

.menu a:hover,
.menu a.active {
  background: #ffe6ee;
  color: #ff4f81;
}

/* MAIN */
.main {
  flex: 1;
  padding: 25px;
}

/* TOPBAR */
.topbar {
  display: flex;
  justify-content: space-between;
  margin-bottom: 20px;
  align-items: center;
}

.search {
  width: 250px;
  padding: 8px;
  border-radius: 8px;
  border: 1px solid #ddd;
}

/* GRID PRODUK */
.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px,1fr));
  gap: 20px;
}

/* CARD */
.product-card {
  background: white;
  border-radius: 15px;
  padding: 15px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.05);
  transition: 0.3s;
}

.product-card:hover {
  transform: translateY(-5px);
}

/* GAMBAR */
.product-card img {
  width: 100%;
  height: 180px;
  object-fit: cover;
  border-radius: 10px;
}

/* TEXT */
.product-card h4 {
  margin: 10px 0 5px;
  font-size: 15px;
}

.price {
  color: #ff4f81;
  font-weight: bold;
  margin-bottom: 10px;
}

/* BUTTON */
.btn-group {
  display: flex;
  gap: 5px;
}

.btn {
  flex: 1;
  background: #ff7aa2;
  color: white;
  padding: 8px;
  border-radius: 8px;
  border: none;
  cursor: pointer;
  font-size: 13px;
}

.btn:hover {
  background: #ff4f81;
}

.btn-outline {
  flex: 1;
  background: transparent;
  border: 1px solid #ff7aa2;
  color: #ff7aa2;
  padding: 8px;
  border-radius: 8px;
  cursor: pointer;
}

.btn-outline:hover {
  background: #ffe6ee;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
  <h2>MAARS Beauty</h2>
  <ul class="menu">
    <li><a href="dashboard.php">Dashboard</a></li>
    <li><a href="produk_user.php" class="active">Produk</a></li>
    <li><a href="keranjang.php">Keranjang</a></li>
    <li><a href="pesanan_user.php">Pesanan Saya</a></li>
    <li><a href="profil.php">Profil</a></li>
  </ul>
</div>

<!-- MAIN -->
<div class="main">

  <div class="topbar">
    <h3>Produk Skincare</h3>
    <input type="text" class="search" placeholder="Cari produk...">
  </div>

  <!-- GRID PRODUK -->
  <div class="product-grid">

    <?php if(mysqli_num_rows($data) > 0){ ?>
      <?php while($row = mysqli_fetch_assoc($data)) { ?>

        <div class="product-card">
          <img src="../gambar/<?= $row['gambar']; ?>">

          <h4><?= $row['nama_produk']; ?></h4>

          <p class="price">
            Rp<?= number_format($row['harga']); ?>
          </p>

          <div class="btn-group">

            <!-- MASUK KERANJANG -->
            <form action="check_out.php" method="GET">
              <input type="hidden" name="id_produk" value="<?= $row['id_produk']; ?>">
              <button type="submit" class="btn">Beli</button>
            </form>

            <!-- MASUK KERANJANG -->
            <form action="proses_keranjang.php" method="POST">
              <input type="hidden" name="id_produk" value="<?= $row['id_produk']; ?>">
              <button class="btn-outline">Keranjang</button>
            </form>


          </div>
        </div>

      <?php } ?>
    <?php } else { ?>
      <p>Belum ada produk tersedia</p>
    <?php } ?>

  </div>

</div>

</body>
</html>