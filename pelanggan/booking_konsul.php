<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: ../login.php");
    exit;
}
include '../includes/conn.php';
$id_pelanggan = $_SESSION['id_pelanggan'];

// Proses jika tombol booking ditekan
if (isset($_POST['booking'])) {
    $id_jadwal = $_POST['id_jadwal'];
    
    // Cek apakah sudah pernah booking di jadwal ini
    $cek = mysqli_query($conn, "SELECT * FROM booking WHERE id_pelanggan='$id_pelanggan' AND id_jadwal='$id_jadwal'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "Kamu sudah membooking jadwal ini sebelumnya.";
    } else {
        $query = mysqli_query($conn, "INSERT INTO booking (id_pelanggan, id_jadwal, tanggal_booking, status) VALUES ('$id_pelanggan', '$id_jadwal', CURDATE(), 'Menunggu')");
        if ($query) {
            echo "<script>alert('Booking berhasil! Silakan cek riwayat konsultasi.'); window.location='riwayat_konsul.php';</script>";
        }
    }
}

// Ambil jadwal yang tersedia (tanggal hari ini atau ke depan)
$q_jadwal = mysqli_query($conn, "SELECT * FROM jadwal WHERE tanggal >= CURDATE() ORDER BY tanggal ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Booking Konsultasi - MAARS Beauty</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/global.css">
  <link rel="stylesheet" href="../assets/css/sidebar.css">
  <style>
    body { font-family: 'DM Sans', sans-serif; background-color: #fbf6f8; display: flex; }
    .main { flex: 1; padding: 40px; height: 100vh; overflow-y: auto; }
    .header-text h1 { font-family: 'Playfair Display', serif; font-size: 28px; color: #1a0a12; margin-bottom: 6px; }
    .header-text p { color: #8a6070; font-size: 14px; margin-bottom: 30px; }
    .card-box { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); max-width: 600px; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; color: #1a0a12; font-weight: 600; font-size: 13px; }
    .form-control { width: 100%; padding: 12px; border: 1px solid #eee; border-radius: 10px; font-family: 'DM Sans', sans-serif; background: #fbf6f8; outline: none; }
    .btn-submit { background: #ff4f81; color: white; border: none; padding: 12px 25px; border-radius: 10px; cursor: pointer; font-weight: 500; width: 100%; }
    .btn-submit:hover { background: #e63e6d; }
    .error-msg { background: #fef2f2; color: #ef4444; padding: 12px; border-radius: 10px; margin-bottom: 15px; font-size: 13px; }
  </style>
</head>
<body>
  <?php include '../includes/sidebar_user.php'; ?>
  <div class="main">
    <div class="header-text">
      <h1>Booking Konsultasi</h1>
      <p>Pilih jadwal untuk berkonsultasi dengan ahli kecantikan kami.</p>
    </div>
    <div class="card-box">
      <?php if (isset($error)) echo "<div class='error-msg'>$error</div>"; ?>
      <form action="" method="POST">
        <div class="form-group">
          <label>Pilih Jadwal & Konsultan</label>
          <select name="id_jadwal" class="form-control" required>
            <option value="">-- Pilih Jadwal Tersedia --</option>
            <?php while($row = mysqli_fetch_assoc($q_jadwal)): ?>
              <option value="<?= $row['id_jadwal']; ?>">
                <?= date('d M Y', strtotime($row['tanggal'])); ?> | <?= $row['jam_mulai']; ?> - <?= $row['konsultan']; ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>
        <button type="submit" name="booking" class="btn-submit">Booking Sekarang</button>
      </form>
    </div>
  </div>
</body>
</html>