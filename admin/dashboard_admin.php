<?php 
include '../conn.php'; 

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Skincare</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Google Font -->
  <<link rel="stylesheet" type="text/css" href="style.css">
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

    .menu-container {
    display: inline-block;
    padding: 12px 24px;
    
    color: black; /* Warna teks */
    
    /* Menghilangkan garis bawah khas link */
    text-decoration: none;
    
    /* Tipografi & Bentuk */
    font-family: sans-serif;
    font-weight: bold;
    border-radius: 8px;
    }

    .menu a {
      padding: 12px;
      border-radius: 10px;
      margin-bottom: 10px;
      cursor: pointer;
      transition: 0.3s;

    }

    .menu a:hover {
      background: #ffe6ee;
      color: #ff4f81;
    }

    /* MAIN */
    .main {
      flex: 1;
      padding: 25px;
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      margin-bottom: 25px;
    }

    .search {
      width: 300px;
      padding: 10px;
      border-radius: 10px;
      border: 1px solid #ddd;
    }

    .user {
      font-weight: 500;
    }

    /* CARDS */
    .cards {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 15px;
      margin-bottom: 25px;
    }

    .card {
      background: #fff;
      padding: 15px;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .card h4 {
      color: #888;
      font-size: 14px;
    }

    .card p {
      font-size: 20px;
      font-weight: bold;
      margin-top: 5px;
    }

    /* CONTENT */
    .content {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 20px;
    }

    .box {
      background: #fff;
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .box h3 {
        margin-bottom:15px;
    }

    /* CHART PLACEHOLDER */
    .chart {
      height: 200px;
      background: linear-gradient(120deg, #ffd1dc, #ffe6ee);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #ff4f81;
      font-weight: bold;
    }

    /* CONSULTATION */
    .consultation-item {
      display: flex;
      justify-content: space-between;
      margin-bottom: 15px;
      font-size: 14px;
    }

    .btn {
      background: #ff7aa2;
      color: white;
      padding: 8px 12px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
    }

    .btn:hover {
      background: #ff4f81;
    }

    /* TABLE */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    table th, table td {
      padding: 10px;
      border-bottom: 1px solid #eee;
      text-align: left;
      font-size: 14px;
    }

    /* PROMO */
    .promo {
      background: linear-gradient(120deg, #ff9bb3, #ffd1dc);
      color: white;
      text-align: center;
    }

    .promo h2 {
      font-size: 30px;
    }

  </style>
</head>
<body>

  <!-- SIDEBAR -->
  <div class="sidebar">
    <h2>MAARS Beauty</h2>
    <ul class="menu">
    <li>
      <a href="dashboard.php" class="menu-container">Dashboard</a>
      </li>
      <li>
      <a href="produk.php" class="menu-container">Produk</a>
      </li>
      <li>
      <a href="pesanan.php" class="menu-container">Pesanan</a>
      </li>
      <li>
      <a href="konsultasi.php" class="menu-container">Konsultasi</a>
      </li>
      <li>
      <a href="laporan.php" class="menu-container">Laporan</a>
      </li>
    </ul>
  </div>

  <!-- MAIN -->
  <div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
      <input type="text" class="search" placeholder="Cari produk...">
      <div class="user">Halo, Admin 👋</div>
    </div>

    <!-- CARDS -->
    <div class="cards">
      <div class="card">
        <h4>Total Penjualan</h4>
        <p>Rp12.000.000</p>
      </div>
      <div class="card">
        <h4>Total Pesanan</h4>
        <p>120</p>
      </div>
      <div class="card">
        <h4>Total Pelanggan</h4>
        <p>89</p>
      </div>
      <div class="card">
        <h4>Konsultasi</h4>
        <p>15</p>
      </div>
    </div>

    <!-- CONTENT -->
    <div class="content">

      <!-- LEFT -->
      <div>
        <div class="box">
          <h3>Grafik Penjualan</h3>
          <div class="chart">Chart Area</div>
        </div>

        <div class="box" style="margin-top:20px;">
          <h3>Pesanan Terbaru</h3>
          <table>
            <tr>
              <th>ID</th>
              <th>Nama</th>
              <th>Status</th>
              <th>Total</th>
            </tr>
            <tr>
              <td>#001</td>
              <td>Aulia</td>
              <td>Selesai</td>
              <td>Rp200.000</td>
            </tr>
            <tr>
              <td>#002</td>
              <td>Nadya</td>
              <td>Diproses</td>
              <td>Rp150.000</td>
            </tr>
          </table>
        </div>
      </div>

      <!-- RIGHT -->
      <div>
        <div class="box">
          <h3>Konsultasi Mendatang</h3>

          <div class="consultation-item">
            <span>Nadya - 10:00</span>
            <button class="btn">Detail</button>
          </div>

          <div class="consultation-item">
            <span>Putri - 13:00</span>
            <button class="btn">Detail</button>
          </div>
          <div class="consultation-item">
            <span>Putri - 13:00</span>
            <button class="btn">Detail</button>
          </div>
        </div>
      </div>

    </div>

  </div>

</body>
</html>