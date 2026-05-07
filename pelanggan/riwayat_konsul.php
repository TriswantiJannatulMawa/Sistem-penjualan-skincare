<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: ../login.php");
    exit;
}
include '../includes/conn.php';
$id_pelanggan = $_SESSION['id_pelanggan'];

$query = mysqli_query($conn, "
    SELECT b.*, j.tanggal, j.jam_mulai, j.konsultan 
    FROM booking b
    JOIN jadwal j ON b.id_jadwal = j.id_jadwal
    WHERE b.id_pelanggan = '$id_pelanggan'
    ORDER BY b.id_booking DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Konsultasi - MAARS Beauty</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/global.css">
  <link rel="stylesheet" href="../assets/css/sidebar.css">
  <style>
    body { font-family: 'DM Sans', sans-serif; background-color: #fbf6f8; display: flex; }
    .main { flex: 1; padding: 40px; height: 100vh; overflow-y: auto; }
    .header-text h1 { font-family: 'Playfair Display', serif; font-size: 28px; color: #1a0a12; margin-bottom: 6px; }
    .header-text p { color: #8a6070; font-size: 14px; margin-bottom: 30px; }
    .card-box { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
    table { width: 100%; border-collapse: collapse; }
    th { text-align: left; padding: 15px 10px; color: #a08892; font-size: 12px; text-transform: uppercase; border-bottom: 2px solid #f0f0f0; }
    td { padding: 15px 10px; border-bottom: 1px solid #f5f5f5; font-size: 14px; color: #444; }
    .badge { padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; display: inline-block; }
    .bg-wait { background: #fff5e6; color: #f39c12; }
    .bg-done { background: #eaffea; color: #2ecc71; }
  </style>
</head>
<body>
  <?php include '../includes/sidebar_user.php'; ?>
  <div class="main">
    <div class="header-text">
      <h1>Riwayat Konsultasi</h1>
      <p>Pantau jadwal temu kamu dengan konsultan kami.</p>
    </div>
    <div class="card-box">
      <table>
        <thead>
          <tr>
            <th>Tanggal Jadwal</th>
            <th>Waktu</th>
            <th>Konsultan</th>
            <th>Tanggal Booking</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = mysqli_fetch_assoc($query)): 
            $badge = (strtolower($row['status']) == 'selesai') ? 'bg-done' : 'bg-wait';
          ?>
          <tr>
            <td><?= date('d M Y', strtotime($row['tanggal'])); ?></td>
            <td><?= $row['jam_mulai']; ?></td>
            <td><strong style="color: #1a0a12;"><?= $row['konsultan']; ?></strong></td>
            <td><?= date('d M Y', strtotime($row['tanggal_booking'])); ?></td>
            <td><span class="badge <?= $badge; ?>"><?= $row['status']; ?></span></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>