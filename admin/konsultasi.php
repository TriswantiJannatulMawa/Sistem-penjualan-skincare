<?php
session_start();

// Proteksi halaman admin
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../includes/conn.php';

// Query untuk mengambil data booking konsultasi
$query = mysqli_query($conn, "
    SELECT b.*, p.nama as nama_pelanggan, j.tanggal, j.jam_mulai, j.konsultan 
    FROM booking b
    JOIN pelanggan p ON b.id_pelanggan = p.id_pelanggan
    JOIN jadwal j ON b.id_jadwal = j.id_jadwal
    ORDER BY b.tanggal_booking DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Konsultasi - MAARS Beauty Admin</title>
  
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

    .card-box {
      background: #ffffff;
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.02);
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
      padding: 18px 10px;
      border-bottom: 1px solid #f5f5f5;
      font-size: 14px;
      color: #444;
    }

    .badge-status {
      padding: 6px 12px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 600;
      display: inline-block;
    }

    .status-selesai { background: #eaffea; color: #2ecc71; }
    .status-menunggu { background: #fff5e6; color: #f39c12; }
  </style>
</head>
<body>

  <?php include '../includes/sidebar_admin.php'; ?>

  <div class="main">
    <div class="topbar-modern">
      <div class="header-text">
        <h1>Jadwal Konsultasi</h1>
        <p>Kelola janji temu pelanggan dengan konsultan kecantikan 🧖‍♀️</p>
      </div>
    </div>

    <div class="card-box">
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Pelanggan</th>
            <th>Konsultan</th>
            <th>Tanggal Jadwal</th>
            <th>Waktu</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if(mysqli_num_rows($query) > 0) {
            $no = 1;
            while($row = mysqli_fetch_assoc($query)) {
              $status_class = (strtolower($row['status']) == 'selesai') ? 'status-selesai' : 'status-menunggu';
              
              echo "<tr>";
              echo "<td>{$no}</td>";
              echo "<td><strong style='color: #1a0a12;'>{$row['nama_pelanggan']}</strong></td>";
              echo "<td>{$row['konsultan']}</td>";
              echo "<td>" . date('d M Y', strtotime($row['tanggal'])) . "</td>";
              echo "<td>{$row['jam_mulai']}</td>";
              echo "<td><span class='badge-status {$status_class}'>{$row['status']}</span></td>";
              echo "</tr>";
              $no++;
            }
          } else {
            echo "<tr><td colspan='6' style='text-align:center; padding:30px; color:#888;'>Belum ada data booking.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>