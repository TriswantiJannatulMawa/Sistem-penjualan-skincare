<?php
session_start();
include '../includes/conn.php';

// Auth check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Data realtime dari DB
$total_penjualan = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT SUM(total_harga) as total FROM transaksi WHERE status_pembayaran='Lunas'"))['total'] ?? 0;

$total_pesanan = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as total FROM transaksi"))['total'] ?? 0;

$total_pelanggan = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as total FROM pelanggan"))['total'] ?? 0;

$total_konsultasi = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as total FROM booking"))['total'] ?? 0;

// Pesanan terbaru
$pesanan_terbaru = mysqli_query($conn,
    "SELECT t.id_transaksi, p.nama, t.total_harga, t.status_pembayaran, t.tgl_transaksi
     FROM transaksi t
     JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
     ORDER BY t.tgl_transaksi DESC LIMIT 5");

// Produk stok menipis
$stok_menipis = mysqli_query($conn,
    "SELECT nama_produk, stok FROM produk WHERE stok <= 5 ORDER BY stok ASC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin — MAARS Beauty</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --pink-50:  #fff0f5;
      --pink-100: #ffe0ed;
      --pink-300: #ffaace;
      --pink-500: #ff4f81;
      --rose:     #ff7aa2;
      --dark:     #1a0a12;
      --dark2:    #2d0a1a;
      --muted:    #8a6070;
      --white:    #ffffff;
      --sidebar:  240px;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'DM Sans', sans-serif;
      background: #f9f0f3;
      display: flex;
      min-height: 100vh;
    }

    /* ── SIDEBAR ── */
    .sidebar {
      width: var(--sidebar);
      background: var(--dark);
      min-height: 100vh;
      padding: 28px 20px;
      display: flex;
      flex-direction: column;
      position: fixed;
      top: 0; left: 0;
      z-index: 100;
    }

    .sidebar-brand {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 40px;
      padding: 0 8px;
    }

    .sidebar-brand-dot {
      width: 8px; height: 8px;
      background: var(--rose);
      border-radius: 50%;
      flex-shrink: 0;
    }

    .sidebar-brand-name {
      font-family: 'Playfair Display', serif;
      font-size: 16px;
      color: var(--white);
      letter-spacing: 1.5px;
      text-transform: uppercase;
    }

    .sidebar-label {
      font-size: 10px;
      font-weight: 600;
      color: #4a2535;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      padding: 0 12px;
      margin-bottom: 8px;
      margin-top: 8px;
    }

    .menu {
      list-style: none;
      flex: 1;
    }

    .menu li { margin-bottom: 4px; }

    .menu a {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 11px 12px;
      border-radius: 10px;
      text-decoration: none;
      color: #9a7080;
      font-size: 14px;
      font-weight: 400;
      transition: 0.2s;
    }

    .menu a:hover {
      background: rgba(255,122,162,0.1);
      color: var(--rose);
    }

    .menu a.active {
      background: rgba(255,122,162,0.15);
      color: var(--white);
      font-weight: 500;
    }

    .menu a .icon {
      width: 32px; height: 32px;
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      font-size: 15px;
      background: rgba(255,255,255,0.05);
      flex-shrink: 0;
    }

    .menu a.active .icon {
      background: var(--rose);
    }

    .sidebar-footer {
      padding: 12px;
      border-top: 1px solid rgba(255,255,255,0.05);
      margin-top: 20px;
    }

    .sidebar-footer a {
      display: flex;
      align-items: center;
      gap: 10px;
      color: #9a7080;
      text-decoration: none;
      font-size: 13px;
      padding: 8px;
      border-radius: 8px;
      transition: 0.2s;
    }

    .sidebar-footer a:hover {
      color: var(--rose);
      background: rgba(255,122,162,0.1);
    }

    /* ── MAIN ── */
    .main {
      margin-left: var(--sidebar);
      flex: 1;
      padding: 28px 32px;
      min-height: 100vh;
    }

    /* ── TOPBAR ── */
    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 28px;
    }

    .topbar-left h2 {
      font-family: 'Playfair Display', serif;
      font-size: 24px;
      color: var(--dark);
    }

    .topbar-left p {
      font-size: 13px;
      color: var(--muted);
      margin-top: 2px;
    }

    .topbar-right {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .topbar-search {
      position: relative;
    }

    .topbar-search input {
      padding: 9px 16px 9px 38px;
      border: 1.5px solid #f0dde5;
      border-radius: 10px;
      font-family: 'DM Sans', sans-serif;
      font-size: 13px;
      background: var(--white);
      outline: none;
      width: 220px;
      transition: 0.2s;
      color: var(--dark);
    }

    .topbar-search input:focus {
      border-color: var(--rose);
      box-shadow: 0 0 0 3px rgba(255,122,162,0.1);
    }

    .topbar-search span {
      position: absolute;
      left: 12px; top: 50%;
      transform: translateY(-50%);
      font-size: 14px;
      color: var(--pink-300);
    }

    .avatar {
      width: 38px; height: 38px;
      background: linear-gradient(135deg, var(--rose), var(--pink-500));
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      color: white;
      font-size: 16px;
      cursor: pointer;
    }

    /* ── STAT CARDS ── */
    .stat-cards {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
      margin-bottom: 24px;
    }

    .stat-card {
      background: var(--white);
      border-radius: 16px;
      padding: 20px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.04);
      position: relative;
      overflow: hidden;
      animation: fadeUp 0.4s ease both;
    }

    .stat-card:nth-child(1) { animation-delay: 0.05s; }
    .stat-card:nth-child(2) { animation-delay: 0.1s; }
    .stat-card:nth-child(3) { animation-delay: 0.15s; }
    .stat-card:nth-child(4) { animation-delay: 0.2s; }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(16px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .stat-card::after {
      content: '';
      position: absolute;
      top: 0; right: 0;
      width: 80px; height: 80px;
      background: radial-gradient(circle at top right, rgba(255,122,162,0.08), transparent 70%);
      border-radius: 0 16px 0 0;
    }

    .stat-icon {
      width: 42px; height: 42px;
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-size: 20px;
      margin-bottom: 14px;
    }

    .stat-icon.pink   { background: #ffe0ed; }
    .stat-icon.rose   { background: #ffd1dc; }
    .stat-icon.purple { background: #f3e0ff; }
    .stat-icon.green  { background: #d4f5e2; }

    .stat-card h4 {
      font-size: 12px;
      font-weight: 500;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 6px;
    }

    .stat-card p {
      font-size: 22px;
      font-weight: 600;
      color: var(--dark);
    }

    .stat-card .stat-sub {
      font-size: 11px;
      color: #2ecc71;
      margin-top: 4px;
      font-weight: 500;
    }

    /* ── CONTENT GRID ── */
    .content-grid {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 20px;
    }

    /* ── BOX ── */
    .box {
      background: var(--white);
      border-radius: 16px;
      padding: 22px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.04);
      animation: fadeUp 0.4s ease 0.25s both;
    }

    .box-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 18px;
    }

    .box-header h3 {
      font-family: 'Playfair Display', serif;
      font-size: 17px;
      color: var(--dark);
    }

    .box-header a {
      font-size: 12px;
      color: var(--rose);
      text-decoration: none;
      font-weight: 500;
    }

    .box-header a:hover { text-decoration: underline; }

    /* ── TABLE ── */
    table {
      width: 100%;
      border-collapse: collapse;
    }

    thead th {
      font-size: 11px;
      font-weight: 600;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 0 12px 10px;
      text-align: left;
      border-bottom: 1px solid #f0dde5;
    }

    tbody td {
      padding: 12px;
      font-size: 13px;
      color: var(--dark);
      border-bottom: 1px solid #faf0f3;
    }

    tbody tr:last-child td { border-bottom: none; }

    tbody tr:hover td { background: var(--pink-50); }

    /* ── BADGE ── */
    .badge {
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 500;
      display: inline-block;
    }

    .badge-lunas    { background: #d4f5e2; color: #1a7a45; }
    .badge-belum    { background: #fff0e0; color: #b35c00; }
    .badge-proses   { background: #e0f0ff; color: #1a5a9a; }

    /* ── STOK MENIPIS ── */
    .stok-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 0;
      border-bottom: 1px solid #faf0f3;
    }

    .stok-item:last-child { border-bottom: none; }

    .stok-item-left {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .stok-dot {
      width: 8px; height: 8px;
      border-radius: 50%;
      background: var(--rose);
      flex-shrink: 0;
    }

    .stok-dot.danger { background: #ff4f81; }
    .stok-dot.warn   { background: #f9a825; }

    .stok-item p {
      font-size: 13px;
      color: var(--dark);
      font-weight: 500;
    }

    .stok-item span {
      font-size: 12px;
      color: var(--pink-500);
      font-weight: 600;
    }

    /* ── KONSULTASI BOX ── */
    .konsul-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 0;
      border-bottom: 1px solid #faf0f3;
    }

    .konsul-item:last-child { border-bottom: none; }

    .konsul-left {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .konsul-avatar {
      width: 34px; height: 34px;
      background: var(--pink-100);
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 16px;
    }

    .konsul-info p {
      font-size: 13px;
      font-weight: 500;
      color: var(--dark);
    }

    .konsul-info span {
      font-size: 11px;
      color: var(--muted);
    }

    .btn-sm {
      background: linear-gradient(135deg, var(--rose), var(--pink-500));
      color: white;
      border: none;
      padding: 6px 14px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 500;
      cursor: pointer;
      transition: 0.2s;
      text-decoration: none;
      display: inline-block;
    }

    .btn-sm:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(255,79,129,0.3);
    }

    /* ── EMPTY STATE ── */
    .empty {
      text-align: center;
      padding: 30px;
      color: var(--muted);
      font-size: 13px;
    }

    .empty span { font-size: 28px; display: block; margin-bottom: 8px; }
  </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
  <div class="sidebar-brand">
    <div class="sidebar-brand-dot"></div>
    <span class="sidebar-brand-name">MAARS Beauty</span>
  </div>

  <div class="sidebar-label">Menu</div>

  <ul class="menu">
    <li>
      <a href="dashboard_admin.php" class="active">
        <div class="icon">🏠</div> Dashboard
      </a>
    </li>
    <li>
      <a href="produk.php">
        <div class="icon">🛍️</div> Produk
      </a>
    </li>
    <li>
      <a href="pesanan.php">
        <div class="icon">📦</div> Pesanan
      </a>
    </li>
    <li>
      <a href="konsultasi.php">
        <div class="icon">💬</div> Konsultasi
      </a>
    </li>
    <li>
      <a href="laporan.php">
        <div class="icon">📊</div> Laporan
      </a>
    </li>
  </ul>

  <div class="sidebar-footer">
    <a href="../logout.php">🚪 Keluar</a>
  </div>
</div>

<!-- MAIN -->
<div class="main">

  <!-- TOPBAR -->
  <div class="topbar">
    <div class="topbar-left">
      <h2>Dashboard</h2>
      <p>Selamat datang kembali, Admin 👋</p>
    </div>
    <div class="topbar-right">
      <div class="topbar-search">
        <span>🔍</span>
        <input type="text" placeholder="Cari...">
      </div>
      <div class="avatar">👤</div>
    </div>
  </div>

  <!-- STAT CARDS -->
  <div class="stat-cards">
    <div class="stat-card">
      <div class="stat-icon pink">💰</div>
      <h4>Total Penjualan</h4>
      <p>Rp<?= number_format($total_penjualan) ?></p>
      <div class="stat-sub">↑ Data real-time</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon rose">📦</div>
      <h4>Total Pesanan</h4>
      <p><?= $total_pesanan ?></p>
      <div class="stat-sub">↑ Semua transaksi</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon purple">👥</div>
      <h4>Total Pelanggan</h4>
      <p><?= $total_pelanggan ?></p>
      <div class="stat-sub">↑ Terdaftar</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon green">💬</div>
      <h4>Konsultasi</h4>
      <p><?= $total_konsultasi ?></p>
      <div class="stat-sub">↑ Total booking</div>
    </div>
  </div>

  <!-- CONTENT GRID -->
  <div class="content-grid">

    <!-- PESANAN TERBARU -->
    <div class="box">
      <div class="box-header">
        <h3>Pesanan Terbaru</h3>
        <a href="pesanan.php">Lihat semua →</a>
      </div>

      <?php if (mysqli_num_rows($pesanan_terbaru) > 0): ?>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Pelanggan</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($pesanan_terbaru)): ?>
          <tr>
            <td>#<?= $row['id_transaksi'] ?></td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><?= date('d M Y', strtotime($row['tgl_transaksi'])) ?></td>
            <td>Rp<?= number_format($row['total_harga']) ?></td>
            <td>
              <?php
                $status = $row['status_pembayaran'];
                $cls = match($status) {
                  'Lunas'       => 'badge-lunas',
                  'Belum Bayar' => 'badge-belum',
                  default       => 'badge-proses'
                };
              ?>
              <span class="badge <?= $cls ?>"><?= $status ?></span>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      <?php else: ?>
        <div class="empty"><span>📭</span>Belum ada pesanan</div>
      <?php endif; ?>
    </div>

    <!-- RIGHT COLUMN -->
    <div style="display:flex; flex-direction:column; gap:20px;">

      <!-- STOK MENIPIS -->
      <div class="box">
        <div class="box-header">
          <h3>Stok Menipis ⚠️</h3>
          <a href="produk.php">Kelola →</a>
        </div>

        <?php if (mysqli_num_rows($stok_menipis) > 0): ?>
          <?php while ($row = mysqli_fetch_assoc($stok_menipis)): ?>
          <div class="stok-item">
            <div class="stok-item-left">
              <div class="stok-dot <?= $row['stok'] <= 2 ? 'danger' : 'warn' ?>"></div>
              <p><?= htmlspecialchars($row['nama_produk']) ?></p>
            </div>
            <span><?= $row['stok'] ?> pcs</span>
          </div>
          <?php endwhile; ?>
        <?php else: ?>
          <div class="empty"><span>✅</span>Stok aman semua</div>
        <?php endif; ?>
      </div>

      <!-- KONSULTASI -->
      <div class="box">
        <div class="box-header">
          <h3>Konsultasi Hari Ini</h3>
          <a href="konsultasi.php">Lihat →</a>
        </div>
        <div class="empty"><span>💬</span>Belum ada jadwal hari ini</div>
      </div>

    </div>

  </div>

</div>

</body>
</html>