<?php
session_start();
include "../conn.php";

if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_SESSION['id_pelanggan'];

$query = mysqli_query($conn, "SELECT email FROM pelanggan WHERE id_pelanggan='$id'");
$data = mysqli_fetch_assoc($query);

$nama = $data['email'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard User</title>

<link rel="stylesheet" type="text/css" href="style.css">

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
  padding: 25px 20px;
  box-shadow: 2px 0 10px rgba(0,0,0,0.05);
}

.sidebar h2 {
  color: #ff4f81;
  margin-bottom: 40px;
}

.menu {
  list-style: none;
}

.menu li {
  margin-bottom: 10px;
}

.menu a {
  display: block;
  padding: 12px 15px;
  border-radius: 10px;
  text-decoration: none;
  color: #333;
  transition: 0.3s;
}

.menu a:hover,
.menu a.active {
  background: #ffe6ee;
  color: #ff4f81;
  font-weight: 500;
}

/* MAIN */
.main {
  flex: 1;
  padding: 30px;
}

/* TOPBAR */
.topbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
}

.search {
  width: 280px;
  padding: 10px 15px;
  border-radius: 10px;
  border: 1px solid #ddd;
}

/* CARDS */
.cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px,1fr));
  gap: 20px;
  margin-bottom: 25px;
}

.card {
  background: white;
  padding: 20px;
  border-radius: 15px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.card h4 {
  font-size: 14px;
  color: black;
}

.card p {
  font-size: 20px;
  font-weight: bold;
  color: black;
  margin-top: 5px;
}

/* CONTENT */
.content {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 20px;
}

/* BOX */
.box {
  background: white;
  padding: 20px;
  border-radius: 15px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.box h3 {
  margin-bottom: 15px;
}

/* PRODUK */
.product {
  display: flex;
  gap: 15px;
  margin-bottom: 15px;
  align-items: center;
  padding: 10px;
  border-radius: 10px;
  transition: 0.3s;
}

.product:hover {
  background: #fff0f5;
}

.product img {
  width: 70px;
  height: 70px;
  object-fit: cover;
  border-radius: 10px;
}

.product p {
  font-weight: 500;
}

.product small {
  color: #888;
}

/* BUTTON */
.btn {
  margin-top: 5px;
  background: #ff7aa2;
  border: none;
  color: white;
  padding: 6px 12px;
  border-radius: 8px;
  cursor: pointer;
  font-size: 13px;
}

.btn:hover {
  background: #ff4f81;
}

</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
  <h2> MAARS Beauty</h2>
  <ul class="menu">
      <li>
      <a href="dashboard.php" class="menu-container">Dashboard</a>
      </li>
      <li>
      <a href="produk_user.php" class="menu-container">Produk</a>
      </li>
      <li>
      <a href="keranjang.php" class="menu-container">Keranjang</a>
      </li>
      <li>
      <a href="pesanan_user.php" class="menu-container">Pesanan saya</a>
      </li>
      <li>
      <a href="booking_konsul.php" class="menu-container">Booking Konsultasi</a>
      </li>
      <li>
      <a href="riwayat_konsul.php" class="menu-container">Riwayat Konsultasi</a>
      </li>
      <li>
      <a href="profil.php" class="menu-container">Profil</a>
      </li>
    </ul>
</div>

<!-- MAIN -->
<div class="main">

  <div class="topbar">
    <h3>Halo, <?= $nama ?> 👋</h3>
    <input type="text" class= "search" placeholder="Cari skincare...">
  </div>

  <!-- CARDS -->
  <div class="cards">
    <div class="card">
      <h4>Total Pesanan</h4>
      <p>12</p>
    </div>
    <div class="card">
      <h4>Pesanan Selesai</h4>
      <p>8</p>
    </div>
    <div class="card">
      <h4>Total Belanja</h4>
      <p>Rp1.250.000</p>
    </div>
    <div class="card">
      <h4>Konsultasi</h4>
      <p>3</p>
    </div>
  </div>

  <!-- CONTENT -->
  <div class="content">

    <!-- LEFT -->
    <div>

      <!-- PRODUK -->
      <div class="box">
        <h3>Produk Populer</h3>

        <div class="product">
          <img src="../gambar/serum.jpg">
          <div>
            <p>Serum Vitamin C</p>
            <small>Rp120.000</small><br>
            <button class="btn">Beli</button>
          </div>
        </div>

        <div class="product">
          <img src="../gambar/fw.png">
          <div>
            <p>Facial Wash</p>
            <small>Rp75.000</small><br>
            <button class="btn">Beli</button>
          </div>
        </div>

        <div class="product">
          <img src="../gambar/fw.png">
          <div>
            <p>Facial Wash</p>
            <small>Rp75.000</small><br>
            <button class="btn">Beli</button>
          </div>
        </div>

      </div>

      <!-- PESANAN -->

    </div>

    <!-- RIGHT -->
    <div>

      <!-- BOOKING -->
      <div class="box">
        <h3>Booking Konsultasi</h3>
        <p>Konsultasi dengan ahli skincare</p>
        <button class="btn">Booking Sekarang</button>
      </div>

      <!-- JADWAL -->
     

    </div>

  </div>

</div>

</body>
</html>