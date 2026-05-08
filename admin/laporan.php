<?php
session_start();

// PROTEKSI ADMIN
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../includes/conn.php';


// ==========================
// TOTAL DATA
// ==========================

// TOTAL PRODUK
$produk = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) as total_produk 
        FROM produk
    ")
);

// TOTAL PELANGGAN
$pelanggan = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) as total_pelanggan 
        FROM pelanggan
    ")
);

// TOTAL TRANSAKSI
$transaksi = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) as total_transaksi 
        FROM transaksi
    ")
);

// TOTAL PENDAPATAN
$pendapatan = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT SUM(total_harga) as total_pendapatan
        FROM transaksi
        WHERE status_pembayaran='Lunas'
    ")
);

// TOTAL BOOKING
$booking = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) as total_booking
        FROM booking
    ")
);


// ==========================
// DATA TRANSAKSI
// ==========================
$data_transaksi = mysqli_query($conn, "
    SELECT t.*, p.nama
    FROM transaksi t
    JOIN pelanggan p
    ON t.id_pelanggan = p.id_pelanggan
    ORDER BY t.id_transaksi DESC
");


// ==========================
// LAPORAN PRODUK
// ==========================
$produk_terlaris = mysqli_query($conn, "
    SELECT 
        pr.nama_produk,

        COALESCE(SUM(dt.jumlah), 0) as total_terjual,

        pr.stok as sisa_stok,

        (pr.stok + COALESCE(SUM(dt.jumlah),0)) as stok_awal

    FROM produk pr

    LEFT JOIN detail_transaksi dt
    ON pr.id_produk = dt.id_produk

    GROUP BY pr.id_produk

    ORDER BY total_terjual DESC
");


// ==========================
// DATA KONSULTASI
// ==========================
$konsultasi = mysqli_query($conn, "
    SELECT 
        b.*,
        p.nama as nama_pelanggan,
        j.tanggal,
        j.konsultan
    FROM booking b
    JOIN pelanggan p
    ON b.id_pelanggan = p.id_pelanggan
    JOIN jadwal j
    ON b.id_jadwal = j.id_jadwal
    ORDER BY b.id_booking DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Laporan Admin</title>

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

.header h1{
    font-family:'Playfair Display',serif;
    font-size:30px;
    color:#1a0a12;
}

.header p{
    color:#8a6070;
    margin-top:5px;
}

.stats-grid{
    display:grid;
    grid-template-columns:repeat(5,1fr);
    gap:20px;
    margin-top:30px;
}

.stat-card{
    background:white;
    border-radius:20px;
    padding:25px;
    box-shadow:0 4px 15px rgba(0,0,0,0.03);
}

.stat-card h3{
    font-size:14px;
    color:#8a6070;
    margin-bottom:10px;
}

.stat-card h1{
    font-size:28px;
    color:#ff4f81;
}

.card-box{
    background:white;
    border-radius:20px;
    padding:30px;
    margin-top:30px;
    box-shadow:0 4px 15px rgba(0,0,0,0.03);
}

.section-title{
    font-size:22px;
    font-weight:700;
    margin-bottom:20px;
    color:#1a0a12;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    padding:15px 10px;
    text-align:left;
    font-size:12px;
    color:#999;
    text-transform:uppercase;
    border-bottom:2px solid #f0f0f0;
}

td{
    padding:16px 10px;
    border-bottom:1px solid #f5f5f5;
    font-size:14px;
}

.badge{
    padding:6px 12px;
    border-radius:8px;
    font-size:12px;
    font-weight:600;
}

.lunas{
    background:#eaffea;
    color:#2ecc71;
}

.menunggu{
    background:#fff4e5;
    color:#f39c12;
}

</style>

</head>

<body>

<?php include '../includes/sidebar_admin.php'; ?>

<div class="main">

    <div class="header">
        <h1>Laporan Admin</h1>
        <p>Data penjualan dan konsultasi MAARS Beauty 📊</p>
    </div>


    <!-- STATISTIK -->
    <div class="stats-grid">

        <div class="stat-card">
            <h3>Total Produk</h3>
            <h1><?= $produk['total_produk']; ?></h1>
        </div>

        <div class="stat-card">
            <h3>Total Pelanggan</h3>
            <h1><?= $pelanggan['total_pelanggan']; ?></h1>
        </div>

        <div class="stat-card">
            <h3>Total Transaksi</h3>
            <h1><?= $transaksi['total_transaksi']; ?></h1>
        </div>

        <div class="stat-card">
            <h3>Total Booking</h3>
            <h1><?= $booking['total_booking']; ?></h1>
        </div>

        <div class="stat-card">
            <h3>Pendapatan</h3>
            <h1>
                Rp <?= number_format($pendapatan['total_pendapatan'],0,',','.'); ?>
            </h1>
        </div>

    </div>



    <!-- LAPORAN TRANSAKSI -->
    <div class="card-box">

        <div class="section-title">
            Laporan Transaksi
        </div>

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

            <?php
            while($t = mysqli_fetch_assoc($data_transaksi)){

                $badge = strtolower($t['status_pembayaran']) == 'lunas'
                ? 'lunas'
                : 'menunggu';
            ?>

            <tr>

                <td>
                    TRX-00<?= $t['id_transaksi']; ?>
                </td>

                <td><?= $t['nama']; ?></td>

                <td>
                    Rp <?= number_format($t['total_harga'],0,',','.'); ?>
                </td>

                <td>
                    <span class="badge <?= $badge; ?>">
                        <?= $t['status_pembayaran']; ?>
                    </span>
                </td>

            </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>



    <!-- LAPORAN PRODUK -->
    <div class="card-box">

        <div class="section-title">
            Laporan Produk
        </div>

        <table>

            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Stok Awal</th>
                    <th>Total Terjual</th>
                    <th>Sisa Stok</th>
                </tr>
            </thead>

            <tbody>

            <?php while($p = mysqli_fetch_assoc($produk_terlaris)){ ?>

            <tr>

                <td><?= $p['nama_produk']; ?></td>

                <td>
                    <?= $p['stok_awal']; ?>
                </td>

                <td>
                    <span class="badge lunas">
                        <?= $p['total_terjual']; ?>
                    </span>
                </td>

                <td>
                    <span class="badge menunggu">
                        <?= $p['sisa_stok']; ?>
                    </span>
                </td>

            </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>



    <!-- LAPORAN KONSULTASI -->
    <div class="card-box">

        <div class="section-title">
            Laporan Konsultasi
        </div>

        <table>

            <thead>
                <tr>
                    <th>Pelanggan</th>
                    <th>Konsultan</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

            <?php
            while($k = mysqli_fetch_assoc($konsultasi)){

                $badge = strtolower($k['status']) == 'selesai'
                ? 'lunas'
                : 'menunggu';
            ?>

            <tr>

                <td><?= $k['nama_pelanggan']; ?></td>

                <td><?= $k['konsultan']; ?></td>

                <td>
                    <?= date('d M Y', strtotime($k['tanggal'])); ?>
                </td>

                <td>
                    <span class="badge <?= $badge; ?>">
                        <?= $k['status']; ?>
                    </span>
                </td>

            </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>