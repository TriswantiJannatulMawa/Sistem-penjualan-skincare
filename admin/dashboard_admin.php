<?php
session_start();

// Proteksi halaman admin
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../includes/conn.php';

// 1. Ambil Statistik Global
$q_penjualan = mysqli_query($conn, "SELECT SUM(total_harga) as total FROM transaksi WHERE status_pembayaran = 'Lunas'");
$total_penjualan = mysqli_fetch_assoc($q_penjualan)['total'] ?? 0;

$q_pesanan = mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi");
$total_pesanan = mysqli_fetch_assoc($q_pesanan)['total'];

$q_pelanggan = mysqli_query($conn, "SELECT COUNT(*) as total FROM pelanggan");
$total_pelanggan = mysqli_fetch_assoc($q_pelanggan)['total'];

$q_konsul = mysqli_query($conn, "SELECT COUNT(*) as total FROM booking");
$total_konsul = mysqli_fetch_assoc($q_konsul)['total'];

// 2. Ambil Pesanan Terbaru (Maksimal 5)
$q_pesanan_baru = mysqli_query($conn, "
    SELECT t.*, p.nama 
    FROM transaksi t 
    JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan 
    ORDER BY t.tgl_transaksi DESC LIMIT 4
");

// 3. Cek Stok Menipis (Stok di bawah atau sama dengan 5)
$q_stok = mysqli_query($conn, "SELECT * FROM produk WHERE stok <= 5 ORDER BY stok ASC");
$ada_stok_menipis = mysqli_num_rows($q_stok) > 0;

// 4. Konsultasi Hari Ini
$q_jadwal_hari_ini = mysqli_query($conn, "
    SELECT b.*, p.nama, j.jam_mulai 
    FROM booking b
    JOIN jadwal j ON b.id_jadwal = j.id_jadwal
    JOIN pelanggan p ON b.id_pelanggan = p.id_pelanggan
    WHERE j.tanggal = CURDATE()
");
$ada_jadwal = mysqli_num_rows($q_jadwal_hari_ini) > 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - MAARS Beauty</title>
  
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/global.css">
  <link rel="stylesheet" href="../assets/css/sidebar.css">

  <style>
    body {
      font-family: 'DM Sans', sans-serif;
      background-color: #fbf6f8; /* Background soft pink sesuai referensi */
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
      margin-bottom: 30px;
    }

    .header-text h1 {
      font-family: 'Playfair Display', serif;
      font-size: 32px;
      color: #1a0a12;
      margin-bottom: 6px;
    }

    .header-text p {
      color: #8a6070;
      font-size: 14px;
    }

    .search-box {
      background: white;
      border: 1px solid #eee;
      padding: 10px 15px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      gap: 10px;
      width: 250px;
    }

    .search-box input {
      border: none;
      outline: none;
      width: 100%;
      font-family: 'DM Sans', sans-serif;
      font-size: 13px;
    }

    /* CARD STATISTIK ATAS */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
      margin-bottom: 30px;
    }

    .stat-card {
      background: white;
      padding: 25px;
      border-radius: 20px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.02);
      border: 1px solid rgba(240, 221, 229, 0.4);
    }

    .icon-box {
      width: 40px; height: 40px;
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      margin-bottom: 15px;
      font-size: 18px;
    }
    
    .bg-yellow { background: #fff5e6; }
    .bg-pink { background: #ffe5ec; }
    .bg-purple { background: #f3e8ff; }
    .bg-green { background: #eaffea; }

    .stat-card h4 {
      color: #8a6070;
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 8px;
    }

    .stat-card p {
      font-size: 24px;
      font-weight: 700;
      color: #1a0a12;
      margin-bottom: 5px;
    }

    .stat-card small {
      font-size: 11px;
      color: #2ecc71;
      font-weight: 500;
    }

    /* KONTEN UTAMA 2 KOLOM */
    .content-grid {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 25px;
    }

    .content-card {
      background: white;
      padding: 25px;
      border-radius: 20px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.02);
      border: 1px solid rgba(240, 221, 229, 0.4);
    }

    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .card-header h3 {
      font-family: 'Playfair Display', serif;
      font-size: 18px;
      color: #1a0a12;
    }

    .card-header a {
      color: #ff4f81;
      font-size: 13px;
      text-decoration: none;
      font-weight: 500;
    }

    /* LIST PESANAN TERBARU */
    .order-list { display: flex; flex-direction: column; gap: 15px; }
    .order-item { display: flex; justify-content: space-between; align-items: center; padding-bottom: 15px; border-bottom: 1px solid #f5f5f5; }
    .order-item:last-child { border-bottom: none; padding-bottom: 0; }
    .order-info h5 { font-size: 14px; color: #1a0a12; }
    .order-info p { font-size: 12px; color: #8a6070; margin-top: 3px; }
    .order-price { font-weight: 600; color: #ff4f81; font-size: 14px; }
    
    /* LIST STOK & KONSUL */
    .stok-item { display: flex; justify-content: space-between; margin-bottom: 15px; }
    .stok-item:last-child { margin-bottom: 0; }
    .stok-name { font-size: 13px; color: #1a0a12; font-weight: 500; }
    .stok-sisa { font-size: 12px; font-weight: 600; color: #ef4444; background: #fef2f2; padding: 2px 8px; border-radius: 6px; }

    .empty-state { text-align: center; padding: 30px 10px; color: #8a6070; font-size: 13px; }
    .empty-icon { font-size: 30px; margin-bottom: 10px; }
  </style>
</head>
<body>

  <?php include '../includes/sidebar_admin.php'; ?>

  <div class="main">
    <div class="topbar-modern">
      <div class="header-text">
        <h1>Dashboard</h1>
        <p>Selamat datang kembali, Admin 👋</p>
      </div>
      <div class="search-box">
        <span style="color: #aaa; font-size: 14px;">🔍</span>
        <input type="text" placeholder="Cari...">
      </div>
    </div>

    <div class="stats-grid">
      <div class="stat-card">
        <div class="icon-box bg-yellow">💰</div>
        <h4>Total Penjualan</h4>
        <p>Rp <?= number_format($total_penjualan, 0, ',', '.'); ?></p>
        <small>↑ Data real-time</small>
      </div>
      <div class="stat-card">
        <div class="icon-box bg-pink">📦</div>
        <h4>Total Pesanan</h4>
        <p><?= $total_pesanan; ?></p>
        <small>↑ Semua transaksi</small>
      </div>
      <div class="stat-card">
        <div class="icon-box bg-purple">👥</div>
        <h4>Total Pelanggan</h4>
        <p><?= $total_pelanggan; ?></p>
        <small>↑ Terdaftar</small>
      </div>
      <div class="stat-card">
        <div class="icon-box bg-green">💬</div>
        <h4>Konsultasi</h4>
        <p><?= $total_konsul; ?></p>
        <small>↑ Total booking</small>
      </div>
    </div>

    <div class="content-grid">
      
      <div class="content-card" style="grid-row: span 2;">
        <div class="card-header">
          <h3>Pesanan Terbaru</h3>
          <a href="pesanan.php">Lihat semua →</a>
        </div>
        
        <?php if(mysqli_num_rows($q_pesanan_baru) > 0): ?>
          <div class="order-list">
            <?php while($row = mysqli_fetch_assoc($q_pesanan_baru)): ?>
            <div class="order-item">
              <div class="order-info">
                <h5><?= $row['nama']; ?> (TRX-00<?= $row['id_transaksi']; ?>)</h5>
                <p><?= date('d M Y, H:i', strtotime($row['tgl_transaksi'])); ?> - <?= $row['status_pembayaran']; ?></p>
              </div>
              <div class="order-price">Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></div>
            </div>
            <?php endwhile; ?>
          </div>
        <?php else: ?>
          <div class="empty-state">
            <div class="empty-icon">📭</div>
            Belum ada pesanan masuk.
          </div>
        <?php endif; ?>
      </div>

      <div class="content-card">
        <div class="card-header">
          <h3>Stok Menipis ⚠️</h3>
          <a href="produk.php">Kelola →</a>
        </div>
        <?php if($ada_stok_menipis): ?>
          <?php while($row = mysqli_fetch_assoc($q_stok)): ?>
            <div class="stok-item">
              <span class="stok-name"><?= $row['nama_produk']; ?></span>
              <span class="stok-sisa">Sisa <?= $row['stok']; ?></span>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <div class="empty-state" style="padding: 10px;">
            <div class="empty-icon" style="font-size: 24px;">✅</div>
            Stok aman semua
          </div>
        <?php endif; ?>
      </div>

      <div class="content-card">
        <div class="card-header">
          <h3>Konsultasi Hari Ini</h3>
          <a href="konsultasi.php">Lihat →</a>
        </div>
        <?php if($ada_jadwal): ?>
          <?php while($row = mysqli_fetch_assoc($q_jadwal_hari_ini)): ?>
            <div class="stok-item">
              <span class="stok-name"><?= $row['nama']; ?></span>
              <span class="stok-sisa" style="background:#eaffea; color:#2ecc71;"><?= $row['jam_mulai']; ?></span>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <div class="empty-state" style="padding: 10px;">
            <div class="empty-icon" style="font-size: 24px;">💬</div>
            Belum ada jadwal hari ini
          </div>
        <?php endif; ?>
      </div>

    </div>

  </div>

</body>
</html>