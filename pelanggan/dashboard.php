<?php
session_start();

// Cek apakah user sudah login dan rolenya pelanggan
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: ../login.php");
    exit;
}

include '../includes/conn.php';

$id_pelanggan = $_SESSION['id_pelanggan'];

// 1. Ambil Nama Pelanggan
$q_user = mysqli_query($conn, "SELECT nama FROM pelanggan WHERE id_pelanggan = '$id_pelanggan'");
$data_user = mysqli_fetch_assoc($q_user);
$nama_user = $data_user['nama'];

// 2. Hitung Statistik Pelanggan
// Total Pesanan
$q_total_pesanan = mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi WHERE id_pelanggan = '$id_pelanggan'");
$total_pesanan = mysqli_fetch_assoc($q_total_pesanan)['total'];

// Pesanan Selesai
$q_selesai = mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi WHERE id_pelanggan = '$id_pelanggan' AND status_pembayaran = 'Lunas'");
$pesanan_selesai = mysqli_fetch_assoc($q_selesai)['total'];

// Total Belanja
$q_belanja = mysqli_query($conn, "SELECT SUM(total_harga) as total FROM transaksi WHERE id_pelanggan = '$id_pelanggan' AND status_pembayaran = 'Lunas'");
$total_belanja = mysqli_fetch_assoc($q_belanja)['total'] ?? 0;

// Total Konsultasi
$q_konsul = mysqli_query($conn, "SELECT COUNT(*) as total FROM booking WHERE id_pelanggan = '$id_pelanggan'");
$total_konsul = mysqli_fetch_assoc($q_konsul)['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Pelanggan - MAARS Beauty</title>
  
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
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 35px;
    }

    .header-text h1 {
      font-family: 'Playfair Display', serif;
      font-size: 28px;
      color: #1a0a12;
    }

    /* CARDS STATISTIK */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-bottom: 35px;
    }

    .stat-card {
      background: white;
      padding: 25px;
      border-radius: 20px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.02);
      border: 1px solid rgba(240, 221, 229, 0.4);
    }

    .stat-card h4 {
      color: #8a6070;
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 10px;
    }

    .stat-card p {
      font-size: 22px;
      font-weight: 700;
      color: #1a0a12;
    }

    /* PRODUK POPULER */
    .content-section {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 25px;
    }

    .box-card {
      background: white;
      padding: 30px;
      border-radius: 24px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }

    .box-card h3 {
      font-family: 'Playfair Display', serif;
      font-size: 20px;
      margin-bottom: 20px;
      color: #1a0a12;
    }

    .product-list {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .product-item {
      display: flex;
      align-items: center;
      gap: 15px;
      padding: 15px;
      border-radius: 15px;
      background: #fdfafb;
      transition: background 0.2s;
    }

    .product-item img {
      width: 60px;
      height: 60px;
      border-radius: 12px;
      object-fit: cover;
    }

    .product-info h5 {
      font-size: 15px;
      color: #1a0a12;
      margin-bottom: 4px;
    }

    .product-info span {
      color: #ff4f81;
      font-weight: 700;
      font-size: 14px;
    }

    .btn-buy {
      margin-left: auto;
      background: #ff7aa2;
      color: white;
      text-decoration: none;
      padding: 8px 16px;
      border-radius: 10px;
      font-size: 13px;
      font-weight: 500;
    }

    /* KONSULTASI CARD */
    .promo-card {
      background: linear-gradient(135deg, #ff7aa2, #ff4f81);
      color: white;
      padding: 30px;
      border-radius: 24px;
      text-align: center;
    }

    .promo-card h4 {
      font-family: 'Playfair Display', serif;
      font-size: 18px;
      margin-bottom: 10px;
    }

    .promo-card p {
      font-size: 13px;
      margin-bottom: 20px;
      opacity: 0.9;
    }

    .btn-white {
      background: white;
      color: #ff4f81;
      padding: 10px 20px;
      border-radius: 10px;
      text-decoration: none;
      font-weight: 600;
      font-size: 13px;
      display: inline-block;
    }
  </style>
</head>
<body>

  <?php include '../includes/sidebar_user.php'; ?>

  <div class="main">
    <div class="topbar-modern">
      <div class="header-text">
        <h1>Halo, <?= $nama_user; ?> 👋</h1>
        <p style="color: #8a6070; font-size: 14px;">Mau cari skincare apa hari ini?</p>
      </div>
    </div>

    <div class="stats-grid">
      <div class="stat-card">
        <h4>Total Pesanan</h4>
        <p><?= $total_pesanan; ?></p>
      </div>
      <div class="stat-card">
        <h4>Pesanan Selesai</h4>
        <p><?= $pesanan_selesai; ?></p>
      </div>
      <div class="stat-card">
        <h4>Total Belanja</h4>
        <p style="color: #ff4f81;">Rp <?= number_format($total_belanja, 0, ',', '.'); ?></p>
      </div>
      <div class="stat-card">
        <h4>Konsultasi</h4>
        <p><?= $total_konsul; ?></p>
      </div>
    </div>

    <div class="content-section">
      <div class="box-card">
        <h3>Produk Populer</h3>
        <div class="product-list">
          <?php
          $q_produk = mysqli_query($conn, "SELECT * FROM produk LIMIT 3");
          while($row = mysqli_fetch_assoc($q_produk)):
          ?>
          <div class="product-item">
            <img src="../assets/gambar/<?= $row['gambar']; ?>" alt="produk">
            <div class="product-info">
              <h5><?= $row['nama_produk']; ?></h5>
              <span>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></span>
            </div>
            <a href="produk_user.php" class="btn-buy">Lihat</a>
          </div>
          <?php endwhile; ?>
        </div>
      </div>

      <div>
        <div class="promo-card">
          <h4>Butuh Konsultasi?</h4>
          <p>Tanyakan masalah kulitmu langsung pada ahlinya secara gratis.</p>
          <a href="booking_konsul.php" class="btn-white">Booking Sekarang</a>
        </div>
      </div>
    </div>
  </div>

</body>
</html>