<?php
session_start();

// Cek apakah user sudah login dan rolenya admin
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../includes/conn.php';

// Ambil data transaksi beserta nama pelanggannya (menggunakan JOIN)
$query = mysqli_query($conn, "
    SELECT t.*, p.nama, p.no_hp 
    FROM transaksi t 
    JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan 
    ORDER BY t.id_transaksi DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pesanan Masuk - MAARS Beauty</title>
  
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
      align-items: flex-start;
      margin-bottom: 30px;
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

    .card-box {
      background: #ffffff;
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }

    .card-header {
      margin-bottom: 20px;
    }

    .card-header h3 {
      font-family: 'Playfair Display', serif;
      font-size: 18px;
      color: #1a0a12;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th {
      text-align: left;
      padding: 15px 10px;
      color: #a08892;
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      border-bottom: 2px solid #f0f0f0;
    }

    td {
      padding: 15px 10px;
      border-bottom: 1px solid #f5f5f5;
      font-size: 14px;
      color: #444;
      vertical-align: middle;
    }

    tbody tr:hover {
      background-color: #fcfcfc;
    }

    .badge-status {
      padding: 6px 12px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 600;
      display: inline-block;
    }

    /* Warna Status */
    .status-belum { background: #fff5e6; color: #f39c12; }
    .status-lunas { background: #eaffea; color: #2ecc71; }
    .status-batal { background: #fef2f2; color: #ef4444; }

    .btn-detail {
      padding: 8px 12px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 500;
      text-decoration: none;
      background: #fdf2f6; 
      color: #ff4f81;
    }

    .btn-detail:hover {
      background: #ff4f81; 
      color: white;
    }
  </style>
</head>
<body>

  <?php include '../includes/sidebar_admin.php'; ?>

  <div class="main">
    
    <div class="topbar-modern">
      <div class="header-text">
        <h1>Pesanan Masuk</h1>
        <p>Pantau semua transaksi pelanggan MAARS Beauty 📦</p>
      </div>
      <div class="search-box">
        <span style="color: #aaa; font-size: 14px;">🔍</span>
        <input type="text" placeholder="Cari ID atau Nama...">
      </div>
    </div>

    <div class="card-box">
      <div class="card-header">
        <h3>Riwayat Transaksi</h3>
      </div>
      
      <table>
        <thead>
          <tr>
            <th width="15%">ID Transaksi</th>
            <th width="20%">Tanggal</th>
            <th width="25%">Nama Pelanggan</th>
            <th width="15%">Total Tagihan</th>
            <th width="15%">Status</th>
            <th width="10%">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if(mysqli_num_rows($query) > 0) {
            while($row = mysqli_fetch_assoc($query)) {
              
              // Menentukan warna badge berdasarkan status pembayaran
              $status_pembayaran = strtolower($row['status_pembayaran']);
              if ($status_pembayaran == 'lunas') {
                  $badge_class = 'status-lunas';
              } elseif ($status_pembayaran == 'batal' || $status_pembayaran == 'dibatalkan') {
                  $badge_class = 'status-batal';
              } else {
                  $badge_class = 'status-belum';
              }

              echo "<tr>";
              echo "<td><strong style='color: #1a0a12;'>TRX-00{$row['id_transaksi']}</strong></td>";
              echo "<td>" . date('d M Y, H:i', strtotime($row['tgl_transaksi'])) . "</td>";
              echo "<td>
                      <span style='display:block; font-weight:600; color:#1a0a12;'>{$row['nama']}</span>
                      <span style='font-size:12px; color:#888;'>{$row['no_hp']}</span>
                    </td>";
              echo "<td style='font-weight:600; color:#ff4f81;'>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</td>";
              echo "<td><span class='badge-status {$badge_class}'>{$row['status_pembayaran']}</span></td>";
              echo "<td><a href='#' class='btn-detail'>Detail</a></td>";
              echo "</tr>";
            }
          } else {
            echo "<tr><td colspan='6' style='text-align:center; color:#888; padding:30px;'>Belum ada transaksi masuk.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>

  </div>

</body>
</html>