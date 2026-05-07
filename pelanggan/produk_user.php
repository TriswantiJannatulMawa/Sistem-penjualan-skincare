<?php
session_start();

// Proteksi halaman pelanggan
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: ../login.php");
    exit;
}

include '../includes/conn.php';

// Ambil semua data produk
$query = mysqli_query($conn, "SELECT * FROM produk ORDER BY id_produk DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Katalog Produk - MAARS Beauty</title>
  
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

    .search-box {
      background: white;
      border: 1px solid #eee;
      padding: 10px 15px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      gap: 10px;
      width: 280px;
    }

    .search-box input {
      border: none;
      outline: none;
      width: 100%;
      font-family: 'DM Sans', sans-serif;
      font-size: 13px;
    }

    /* GRID PRODUK */
    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
      gap: 25px;
    }

    .product-card {
      background: white;
      border-radius: 20px;
      padding: 15px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.02);
      border: 1px solid rgba(240, 221, 229, 0.4);
      display: flex;
      flex-direction: column;
    }

    .product-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 15px;
      margin-bottom: 15px;
    }

    .product-card h4 {
      font-size: 16px;
      color: #1a0a12;
      margin-bottom: 8px;
      font-weight: 600;
    }

    .product-card .price {
      color: #ff4f81;
      font-weight: 700;
      font-size: 16px;
      margin-bottom: 15px;
    }

    .btn-group {
      display: flex;
      gap: 10px;
      margin-top: auto;
    }

    .btn-buy {
      flex: 1;
      background: #ff4f81;
      color: white;
      text-decoration: none;
      padding: 10px;
      border-radius: 10px;
      text-align: center;
      font-size: 13px;
      font-weight: 500;
    }

    .btn-cart {
      flex: 1;
      background: transparent;
      color: #ff4f81;
      border: 1.5px solid #ff4f81;
      padding: 8px;
      border-radius: 10px;
      cursor: pointer;
      font-size: 13px;
      font-weight: 500;
      transition: 0.2s;
    }

    .btn-cart:hover {
      background: #fff0f5;
    }
  </style>
</head>
<body>

  <?php include '../includes/sidebar_user.php'; ?>

  <div class="main">
    <div class="topbar-modern">
      <div class="header-text">
        <h1>Produk Skincare</h1>
        <p style="color: #8a6070; font-size: 14px;">Pilih produk terbaik untuk kulit cantikmu ✨</p>
      </div>
      <div class="search-box">
        <span style="color: #aaa; font-size: 14px;">🔍</span>
        <input type="text" placeholder="Cari skincare favoritmu...">
      </div>
    </div>

    <div class="product-grid">
      <?php if(mysqli_num_rows($query) > 0): ?>
        <?php while($row = mysqli_fetch_assoc($query)): ?>
          <div class="product-card">
            <img src="../assets/gambar/<?= $row['gambar']; ?>" alt="<?= $row['nama_produk']; ?>">
            <h4><?= $row['nama_produk']; ?></h4>
            <p class="price">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></p>
            
            <div class="btn-group">
              <a href="check_out.php?id_produk=<?= $row['id_produk']; ?>" class="btn-buy">Beli</a>
              
              <form action="proses_keranjang.php" method="POST" style="flex: 1;">
                <input type="hidden" name="id_produk" value="<?= $row['id_produk']; ?>">
                <button type="submit" class="btn-cart">Keranjang</button>
              </form>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p style="grid-column: 1/-1; text-align: center; color: #888; padding: 50px;">Belum ada produk tersedia.</p>
      <?php endif; ?>
    </div>
  </div>

</body>
</html>