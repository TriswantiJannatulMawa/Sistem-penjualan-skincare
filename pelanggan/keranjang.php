<?php
session_start();

// Proteksi halaman pelanggan
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: ../login.php");
    exit;
}

include '../includes/conn.php';

$id_pelanggan = $_SESSION['id_pelanggan'];

// Ambil data keranjang beserta detail produknya
$data = mysqli_query($conn, "
    SELECT k.*, p.nama_produk, p.harga, p.gambar
    FROM keranjang k
    JOIN produk p ON k.id_produk = p.id_produk
    WHERE k.id_pelanggan = '$id_pelanggan'
");

$total = 0;
$jumlah_barang = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Keranjang Saya - MAARS Beauty</title>
  
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
      margin-bottom: 35px;
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

    /* LAYOUT KERANJANG 2 KOLOM */
    .cart-container {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 30px;
      align-items: start;
    }

    .cart-items {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    /* CARD ITEM KERANJANG */
    .cart-item {
      background: white;
      padding: 20px;
      border-radius: 20px;
      display: flex;
      align-items: center;
      gap: 20px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.02);
      border: 1px solid rgba(240, 221, 229, 0.4);
    }

    .cart-item img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 12px;
      border: 1px solid #f0dde5;
    }

    .cart-info {
      flex: 1;
    }

    .cart-info h4 {
      font-size: 16px;
      color: #1a0a12;
      margin-bottom: 5px;
      font-weight: 600;
    }

    .cart-info p {
      color: #8a6070;
      font-size: 14px;
    }

    .cart-qty {
      font-weight: 600;
      color: #1a0a12;
      background: #fbf6f8;
      padding: 8px 16px;
      border-radius: 10px;
      font-size: 14px;
    }

    .cart-subtotal {
      font-weight: 700;
      color: #ff4f81;
      font-size: 16px;
      min-width: 100px;
      text-align: right;
    }

    /* RINGKASAN BELANJA (KOLOM KANAN) */
    .summary-box {
      background: white;
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.02);
      border: 1px solid rgba(240, 221, 229, 0.4);
      position: sticky;
      top: 40px;
    }

    .summary-box h3 {
      font-family: 'Playfair Display', serif;
      font-size: 20px;
      margin-bottom: 25px;
      color: #1a0a12;
    }

    .summary-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 15px;
      color: #444;
      font-size: 14px;
    }

    .summary-total {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
      padding-top: 20px;
      border-top: 2px dashed #f0dde5;
      font-weight: 700;
      font-size: 18px;
      color: #ff4f81;
    }

    .btn-checkout {
      width: 100%;
      background: #ff4f81;
      color: white;
      padding: 15px;
      border-radius: 12px;
      border: none;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      margin-top: 25px;
      transition: 0.3s;
    }

    .btn-checkout:hover {
      background: #e63e6d;
    }

    .empty-cart {
      text-align: center;
      padding: 50px;
      background: white;
      border-radius: 20px;
      color: #8a6070;
      border: 1px dashed #f0dde5;
    }
  </style>
</head>
<body>

  <?php include '../includes/sidebar_user.php'; ?>

  <div class="main">
    <div class="topbar-modern">
      <div class="header-text">
        <h1>Keranjang Belanja</h1>
        <p>Periksa kembali produk yang ingin kamu beli.</p>
      </div>
    </div>

    <?php if(mysqli_num_rows($data) > 0): ?>
      <div class="cart-container">
        
        <div class="cart-items">
          <?php 
          while($row = mysqli_fetch_assoc($data)): 
            $subtotal = $row['harga'] * $row['jumlah'];
            $total += $subtotal;
            $jumlah_barang += $row['jumlah'];
          ?>
          <div class="cart-item">
            <img src="../assets/gambar/<?= $row['gambar']; ?>" alt="Produk">
            <div class="cart-info">
              <h4><?= $row['nama_produk']; ?></h4>
              <p>Rp <?= number_format($row['harga'], 0, ',', '.'); ?> / pcs</p>
            </div>
            <div class="cart-qty">
              x<?= $row['jumlah']; ?>
            </div>
            <div class="cart-subtotal">
              Rp <?= number_format($subtotal, 0, ',', '.'); ?>
            </div>
          </div>
          <?php endwhile; ?>
        </div>

        <div class="summary-box">
          <h3>Ringkasan Belanja</h3>
          
          <div class="summary-row">
            <span>Total Barang</span>
            <span><?= $jumlah_barang; ?> item</span>
          </div>
          
          <div class="summary-row">
            <span>Biaya Pengiriman</span>
            <span style="color: #2ecc71; font-weight: 600;">Gratis</span>
          </div>

          <div class="summary-total">
            <span>Total Tagihan</span>
            <span>Rp <?= number_format($total, 0, ',', '.'); ?></span>
          </div>

          <form action="check_out.php" method="POST">
            <input type="hidden" name="total_belanja" value="<?= $total; ?>">
            <button class="btn-checkout">Lanjut ke Pembayaran</button>
          </form>
        </div>

      </div>
    <?php else: ?>
      <div class="empty-cart">
        <h3 style="font-family: 'Playfair Display', serif; color: #1a0a12; font-size: 22px; margin-bottom: 10px;">Keranjangmu masih kosong 😢</h3>
        <p style="margin-bottom: 20px;">Yuk, lihat-lihat katalog produk kami dan temukan skincare favoritmu!</p>
        <a href="produk_user.php" style="background: #ff4f81; color: white; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: 500;">Mulai Belanja</a>
      </div>
    <?php endif; ?>

  </div>

</body>
</html>