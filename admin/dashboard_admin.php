<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../includes/conn.php';

// 1. Statistik Global

$q_penjualan = mysqli_query($conn, "
    SELECT SUM(total_harga) as total 
    FROM transaksi 
    WHERE status_pembayaran = 'Lunas'
");

$total_penjualan = mysqli_fetch_assoc($q_penjualan)['total'] ?? 0;

$q_pesanan = mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM transaksi
");

$total_pesanan = mysqli_fetch_assoc($q_pesanan)['total'];

$q_pelanggan = mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM pelanggan
");

$total_pelanggan = mysqli_fetch_assoc($q_pelanggan)['total'];

$q_konsul = mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM booking
");

$total_konsul = mysqli_fetch_assoc($q_konsul)['total'];

// 2. Pesanan Terbaru

$q_pesanan_baru = mysqli_query($conn, "
    SELECT t.*, p.nama 
    FROM transaksi t
    JOIN pelanggan p 
    ON t.id_pelanggan = p.id_pelanggan
    ORDER BY t.tgl_transaksi DESC
    LIMIT 4
");

// 3. Stok Menipis

$q_stok = mysqli_query($conn, "
    SELECT * 
    FROM produk
    WHERE stok <= 5
    ORDER BY stok ASC
");

$ada_stok_menipis = mysqli_num_rows($q_stok) > 0;

// 4. Booking Konsultasi

$q_jadwal_hari_ini = mysqli_query($conn, "
    SELECT b.*, p.nama, j.jam_mulai
    FROM booking b
    JOIN jadwal j 
    ON b.id_jadwal = j.id_jadwal
    JOIN pelanggan p
    ON b.id_pelanggan = p.id_pelanggan
    WHERE b.status = 'Menunggu'
");

$total_booking = mysqli_num_rows($q_jadwal_hari_ini);
$ada_jadwal = $total_booking > 0;
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

body{
    font-family:'DM Sans',sans-serif;
    background:#fbf6f8;
    display:flex;
}

.main{
    flex:1;
    padding:40px;
    height:100vh;
    overflow-y:auto;
}

.topbar-modern{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
}

.header-text h1{
    font-family:'Playfair Display',serif;
    font-size:32px;
    color:#1a0a12;
    margin-bottom:6px;
}

.header-text p{
    color:#8a6070;
    font-size:14px;
}

.stats-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:20px;
    margin-bottom:30px;
}

.stat-card{
    background:white;
    padding:25px;
    border-radius:20px;
    border:1px solid rgba(240,221,229,.4);
    box-shadow:0 4px 15px rgba(0,0,0,.03);
}

.icon-box{
    width:40px;
    height:40px;
    border-radius:10px;
    display:flex;
    align-items:center;
    justify-content:center;
    margin-bottom:15px;
    font-size:18px;
}

.bg-yellow{ background:#fff5e6; }
.bg-pink{ background:#ffe5ec; }
.bg-purple{ background:#f3e8ff; }
.bg-green{ background:#eaffea; }

.stat-card h4{
    color:#8a6070;
    font-size:11px;
    text-transform:uppercase;
    margin-bottom:8px;
}

.stat-card p{
    font-size:24px;
    font-weight:700;
    color:#1a0a12;
}

.content-grid{
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:25px;
}

.content-card{
    background:white;
    padding:25px;
    border-radius:20px;
    border:1px solid rgba(240,221,229,.4);
    box-shadow:0 4px 15px rgba(0,0,0,.03);
}

.card-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.card-header h3{
    font-family:'Playfair Display',serif;
    font-size:20px;
}

.card-header a{
    color:#ff4f81;
    text-decoration:none;
    font-size:13px;
}

.order-list{
    display:flex;
    flex-direction:column;
    gap:15px;
}

.order-item{
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom:1px solid #f5f5f5;
    padding-bottom:15px;
}

.order-item:last-child{
    border:none;
    padding-bottom:0;
}

.order-info h5{
    font-size:14px;
}

.order-info p{
    font-size:12px;
    color:#8a6070;
}

.order-price{
    color:#ff4f81;
    font-weight:600;
}

.stok-item{
    display:flex;
    justify-content:space-between;
    margin-bottom:15px;
}

.stok-sisa{
    background:#fef2f2;
    color:#ef4444;
    padding:2px 8px;
    border-radius:6px;
    font-size:12px;
    font-weight:600;
}

.empty-state{
    text-align:center;
    padding:25px 10px;
}

.empty-icon{
    font-size:35px;
    margin-bottom:10px;
}

.notif-booking{
    color:#ff4f81;
    font-weight:600;
    font-size:15px;
}

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
    </div>

    <div class="stats-grid">

        <div class="stat-card">
            <div class="icon-box bg-yellow">💰</div>
            <h4>Total Penjualan</h4>
            <p>Rp <?= number_format($total_penjualan,0,',','.'); ?></p>
        </div>

        <div class="stat-card">
            <div class="icon-box bg-pink">📦</div>
            <h4>Total Pesanan</h4>
            <p><?= $total_pesanan; ?></p>
        </div>

        <div class="stat-card">
            <div class="icon-box bg-purple">👥</div>
            <h4>Total Pelanggan</h4>
            <p><?= $total_pelanggan; ?></p>
        </div>

        <div class="stat-card">
            <div class="icon-box bg-green">💬</div>
            <h4>Konsultasi</h4>
            <p><?= $total_konsul; ?></p>
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
                                <h5><?= $row['nama']; ?></h5>
                                <p><?= date('d M Y H:i', strtotime($row['tgl_transaksi'])); ?></p>
                            </div>

                            <div class="order-price">
                                Rp <?= number_format($row['total_harga'],0,',','.'); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
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
                        <span><?= $row['nama_produk']; ?></span>

                        <span class="stok-sisa">
                            Sisa <?= $row['stok']; ?>
                        </span>
                    </div>

                <?php endwhile; ?>

            <?php else: ?>

                <div class="empty-state">
                    <div class="empty-icon">✅</div>
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

                <div class="empty-state">

                    <div class="empty-icon"> 🔔 </div>

                    <div class="notif-booking">
                        Ada <?= $total_booking; ?> pelanggan yang booking konsultasi
                    </div>

                </div>

            <?php else: ?>

                <div class="empty-state">

                    <div class="empty-icon"> 💬  </div>
                    Belum ada booking konsultasi

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>

</body>
</html>