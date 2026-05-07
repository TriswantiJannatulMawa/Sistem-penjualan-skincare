<?php
session_start();

// Proteksi halaman pelanggan
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: ../login.php");
    exit;
}

include '../includes/conn.php';
$id_pelanggan = $_SESSION['id_pelanggan'];

// Ambil riwayat pesanan pelanggan ini
$query = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_pelanggan = '$id_pelanggan' ORDER BY tgl_transaksi DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pesanan Saya - MAARS Beauty</title>
  
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/global.css">
  <link rel="stylesheet" href="../assets/css/sidebar.css">

  <style>
    body { font-family: 'DM Sans', sans-serif; background-color: #fbf6f8; display: flex; }
    .main { flex: 1; padding: 40px; height: 100vh; overflow-y: auto; }
    .topbar-modern { margin-bottom: 35px; }
    .header-text h1 { font-family: 'Playfair Display', serif; font-size: 28px; color: #1a0a12; margin-bottom: 6px; }
    .header-text p { color: #8a6070; font-size: 14px; }

    .card-box {
      background: white;
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.02);
      border: 1px solid rgba(240, 221, 229, 0.4);
    }

    table { width: 100%; border-collapse: collapse; }
    th { text-align: left; padding: 15px 10px; color: #a08892; font-size: 12px; text-transform: uppercase; border-bottom: 2px solid #f0f0f0; }
    td { padding: 18px 10px; border-bottom: 1px solid #f5f5f5; font-size: 14px; color: #444; }

    .badge-status {
      padding: 6px 12px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 600;
      display: inline-block;
    }

    /* Penyesuaian warna badge status */
    .status-menunggu { background: #fff5e6; color: #f39c12; }
    .status-lunas { background: #eaffea; color: #2ecc71; }
    .status-batal { background: #fef2f2; color: #ef4444; }
  </style>
</head>
<body>

  <?php include '../includes/sidebar_user.php'; ?>

  <div class="main">
    <div class="topbar-modern">
      <div class="header-text">
        <h1>Pesanan Saya</h1>
        <p>Pantau status transaksi dan riwayat belanja kamu di sini 🛍️</p>
      </div>
    </div>

    <div class="card-box">
      <table>
        <thead>
          <tr>
            <th>ID Pesanan</th>
            <th>Tanggal Pembelian</th>
            <th>Metode Pembayaran</th>
            <th>Total Tagihan</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if(mysqli_num_rows($query) > 0) {
            while($row = mysqli_fetch_assoc($query)) {

              // STATUS PEMBAYARAN
              $status = !empty($row['status_pembayaran'])
                  ? strtolower($row['status_pembayaran'])
                  : 'belum bayar';
          
              // WARNA BADGE
              if (strpos($status, 'lunas') !== false) {
          
                  $badge = 'status-lunas';
          
              } elseif (strpos($status, 'batal') !== false) {
          
                  $badge = 'status-batal';
          
              } else {
          
                  $badge = 'status-menunggu';
              }
          
              // FORMAT TANGGAL
              $tanggal = !empty($row['tgl_transaksi'])
                  ? date('d M Y', strtotime($row['tgl_transaksi']))
                  : '-';
          
              echo "<tr>";
          
              echo "
              <td>
                  <strong style='color:#1a0a12;'>
                      TRX-00{$row['id_transaksi']}
                  </strong>
              </td>
              ";
          
              echo "<td>{$tanggal}</td>";
          
              echo "<td>{$row['metode_pembayaran']}</td>";
          
              echo "
              <td style='font-weight:600; color:#ff4f81;'>
                  Rp " . number_format($row['total_harga'], 0, ',', '.') . "
              </td>
              ";
          
              echo "
              <td>
                  <span class='badge-status {$badge}'>
                      {$row['status_pembayaran']}
                  </span>
              </td>
              ";
          
              echo "</tr>";
          }
          } else {
            echo "<tr><td colspan='5' style='text-align:center; padding:40px; color:#888;'>Kamu belum pernah melakukan pesanan. <br><br> <a href='produk_user.php' style='color:#ff4f81; text-decoration:none; font-weight:600;'>Mulai Belanja</a></td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>