<?php
session_start();

// Proteksi halaman pelanggan
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: ../login.php");
    exit;
}

include '../includes/conn.php';
$id_pelanggan = $_SESSION['id_pelanggan'];

// Inisialisasi variabel
$items = [];
$total_bayar = 0;
$mode = "";

// SKENARIO 1: Checkout dari Keranjang (POST)
if (isset($_POST['total_belanja'])) {
    $mode = "keranjang";
    $query_cart = mysqli_query($conn, "
        SELECT k.*, p.nama_produk, p.harga, p.gambar 
        FROM keranjang k 
        JOIN produk p ON k.id_produk = p.id_produk 
        WHERE k.id_pelanggan = '$id_pelanggan'
    ");
    while ($row = mysqli_fetch_assoc($query_cart)) {
        $items[] = $row;
        $total_bayar += ($row['harga'] * $row['jumlah']);
    }
} 
// SKENARIO 2: Beli Langsung (GET)
elseif (isset($_GET['id_produk'])) {
    $mode = "langsung";
    $id_p = $_GET['id_produk'];
    $query_p = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk = '$id_p'");
    $res = mysqli_fetch_assoc($query_p);
    if ($res) {
        $res['jumlah'] = 1; // Default beli langsung adalah 1
        $items[] = $res;
        $total_bayar = $res['harga'];
    }
} else {
    header("Location: keranjang.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout - MAARS Beauty</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/global.css">
  <link rel="stylesheet" href="../assets/css/sidebar.css">
  <style>
    body { font-family: 'DM Sans', sans-serif; background-color: #fbf6f8; display: flex; }
    .main { flex: 1; padding: 40px; height: 100vh; overflow-y: auto; }
    .header-text h1 { font-family: 'Playfair Display', serif; font-size: 28px; color: #1a0a12; margin-bottom: 30px; }
    
    .checkout-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 30px; }
    .card-checkout { background: white; padding: 30px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1px solid rgba(240, 221, 229, 0.4); }
    
    .item-row { display: flex; align-items: center; gap: 15px; padding: 15px 0; border-bottom: 1px solid #f5f5f5; }
    .item-row img { width: 60px; height: 60px; border-radius: 10px; object-fit: cover; }
    .item-info { flex: 1; }
    .item-info h5 { font-size: 14px; color: #1a0a12; }
    .item-info p { font-size: 13px; color: #8a6070; }

    .form-group { margin-top: 20px; }
    .form-group label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 10px; color: #1a0a12; }
    .form-select { width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #eee; background: #fbf6f8; outline: none; }

    .total-section { margin-top: 25px; padding-top: 20px; border-top: 2px dashed #f0dde5; }
    .total-row { display: flex; justify-content: space-between; font-weight: 700; font-size: 18px; color: #ff4f81; }
    
    .btn-pay { width: 100%; background: #ff4f81; color: white; padding: 15px; border-radius: 12px; border: none; font-size: 15px; font-weight: 600; cursor: pointer; margin-top: 20px; }
  </style>
</head>
<body>
  <?php include '../includes/sidebar_user.php'; ?>
  <div class="main">
    <div class="header-text"><h1>Konfirmasi Pesanan</h1></div>
    <form action="proses_checkout.php" method="POST">
      <input type="hidden" name="mode" value="<?= $mode; ?>">
      <div class="checkout-grid">
        <div class="card-checkout">
          <h3 style="font-family: 'Playfair Display'; margin-bottom: 20px;">Rincian Produk</h3>
          <?php foreach ($items as $item): ?>
            <div class="item-row">
              <img src="../assets/gambar/<?= $item['gambar']; ?>">
              <div class="item-info">
                <h5><?= $item['nama_produk']; ?></h5>
                <p><?= $item['jumlah']; ?> x Rp <?= number_format($item['harga'], 0, ',', '.'); ?></p>
              </div>
              <div style="font-weight: 600;">Rp <?= number_format($item['harga'] * $item['jumlah'], 0, ',', '.'); ?></div>
              <input type="hidden" name="id_produk[]" value="<?= $item['id_produk']; ?>">
              <input type="hidden" name="jumlah[]" value="<?= $item['jumlah']; ?>">
              <input type="hidden" name="harga_satuan[]" value="<?= $item['harga']; ?>">
            </div>
          <?php endforeach; ?>
        </div>

        <div class="card-checkout">
          <h3 style="font-family: 'Playfair Display'; margin-bottom: 20px;">Metode Pembayaran</h3>
          <div class="form-group">
            <label>Pilih Metode</label>
            <select name="metode_pembayaran" class="form-select" required>
              <option value="Transfer Bank">Transfer Bank (Manual)</option>
              <option value="E-Wallet">E-Wallet (OVO/Dana)</option>
              <option value="COD">Bayar di Tempat (COD)</option>
            </select>
          </div>
          <div class="total-section">
            <div class="total-row">
              <span>Total Bayar</span>
              <span>Rp <?= number_format($total_bayar, 0, ',', '.'); ?></span>
            </div>
            <input type="hidden" name="total_harga" value="<?= $total_bayar; ?>">
          </div>
          <button type="submit" class="btn-pay">Bayar Sekarang</button>
        </div>
      </div>
    </form>
  </div>
</body>
</html>