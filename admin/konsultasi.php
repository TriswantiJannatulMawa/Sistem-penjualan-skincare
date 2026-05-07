<?php
session_start();

// PROTEKSI HALAMAN ADMIN
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../includes/conn.php';


// =======================
// UPDATE STATUS SELESAI
// =======================
if(isset($_GET['selesai'])){

    $id = $_GET['selesai'];

    mysqli_query($conn, "
        UPDATE booking
        SET status='Selesai'
        WHERE id_booking='$id'
    ");

    header("Location: konsultasi.php");
    exit;
}


// =======================
// SIMPAN JADWAL ADMIN
// =======================
if(isset($_POST['simpan_jadwal'])){

    $tanggal = $_POST['tanggal'];
    $jam = $_POST['jam'];
    $konsultan = $_POST['konsultan'];

    mysqli_query($conn, "
        INSERT INTO jadwal (tanggal, jam_mulai, konsultan)
        VALUES ('$tanggal', '$jam', '$konsultan')
    ");

    header("Location: konsultasi.php");
    exit;
}


// =======================
// DATA BOOKING PELANGGAN
// =======================
$booking = mysqli_query($conn, "
    SELECT b.*, p.nama as nama_pelanggan
    FROM booking b
    JOIN pelanggan p 
    ON b.id_pelanggan = p.id_pelanggan
    ORDER BY b.id_booking DESC
");


// =======================
// DATA JADWAL ADMIN
// =======================
$jadwal = mysqli_query($conn, "
    SELECT * FROM jadwal
    ORDER BY tanggal ASC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Konsultasi Admin</title>

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

.header-text h1{
    font-family:'Playfair Display',serif;
    font-size:30px;
    color:#1a0a12;
}

.header-text p{
    color:#8a6070;
    margin-top:5px;
}

.card-box{
    background:white;
    border-radius:20px;
    padding:30px;
    margin-top:25px;
    box-shadow:0 4px 15px rgba(0,0,0,0.03);
}

.section-title{
    font-size:20px;
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
    border-bottom:2px solid #f0f0f0;
    text-transform:uppercase;
}

td{
    padding:18px 10px;
    border-bottom:1px solid #f5f5f5;
    font-size:14px;
}

.badge{
    padding:6px 12px;
    border-radius:8px;
    font-size:12px;
    font-weight:600;
}

.badge-menunggu{
    background:#fff4e5;
    color:#f39c12;
}

.badge-selesai{
    background:#eaffea;
    color:#2ecc71;
}

.btn-jadwal{
    background:#ff4f87;
    color:white;
    padding:10px 16px;
    border-radius:10px;
    text-decoration:none;
    font-size:13px;
    font-weight:600;
    border:none;
    cursor:pointer;
}

.btn-jadwal:hover{
    background:#e63f75;
}

.form-box{
    background:#fff;
    border-radius:20px;
    padding:25px;
    margin-top:25px;
    box-shadow:0 4px 15px rgba(0,0,0,0.03);
}

.form-box input{
    width:100%;
    padding:12px;
    border:1px solid #eee;
    border-radius:12px;
    margin-top:8px;
    margin-bottom:18px;
}

.form-box label{
    font-size:14px;
    font-weight:600;
    color:#444;
}

.btn-selesai{
    background:#ff4f81;
    color:white;
    padding:8px 12px;
    border-radius:8px;
    text-decoration:none;
    font-size:12px;
    font-weight:600;
    display:inline-block;
    margin-top:8px;
}

.btn-selesai:hover{
    background:#e63f75;
}

</style>
</head>

<body>

<?php include '../includes/sidebar_admin.php'; ?>

<div class="main">

    <div class="header-text">
        <h1>Jadwal Konsultasi</h1>
        <p>Kelola jadwal konsultasi pelanggan 🧖‍♀️</p>
    </div>


    <!-- FORM JADWAL -->
    <div class="form-box">

        <div class="section-title">
            Atur Jadwal Konsultasi
        </div>

        <form method="POST">

            <label>Tanggal Konsultasi</label>
            <input type="date" name="tanggal" required>

            <label>Jam Konsultasi</label>
            <input type="time" name="jam" required>

            <label>Nama Konsultan</label>
            <input type="text" name="konsultan" required>

            <button type="submit"
                    name="simpan_jadwal"
                    class="btn-jadwal">

                Simpan Jadwal

            </button>

        </form>

    </div>



    <!-- TABEL JADWAL -->
    <div class="card-box">

        <div class="section-title">
            Jadwal Konsultasi Tersedia
        </div>

        <table>

            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Konsultan</th>
                </tr>
            </thead>

            <tbody>

            <?php
            if(mysqli_num_rows($jadwal) > 0){

                $no = 1;

                while($j = mysqli_fetch_assoc($jadwal)){
            ?>

                <tr>

                    <td><?= $no++; ?></td>

                    <td>
                        <?= date('d M Y', strtotime($j['tanggal'])); ?>
                    </td>

                    <td><?= $j['jam_mulai']; ?></td>

                    <td><?= $j['konsultan']; ?></td>

                </tr>

            <?php
                }

            } else {

                echo "
                <tr>
                    <td colspan='4' style='text-align:center;'>
                        Belum ada jadwal konsultasi.
                    </td>
                </tr>";
            }
            ?>

            </tbody>

        </table>

    </div>



    <!-- DATA BOOKING -->
    <div class="card-box">

        <div class="section-title">
            Data Booking Pelanggan
        </div>

        <table>

            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pelanggan</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

            <?php
            if(mysqli_num_rows($booking) > 0){

                $no = 1;

                while($b = mysqli_fetch_assoc($booking)){

                    $status = strtolower($b['status']);

                    if($status == 'selesai'){

                        $badge = 'badge-selesai';

                    } else {

                        $badge = 'badge-menunggu';
                    }
            ?>

                <tr>

                    <td><?= $no++; ?></td>

                    <td>
                        <strong>
                            <?= $b['nama_pelanggan']; ?>
                        </strong>
                    </td>

                    <td>

                        <span class="badge <?= $badge; ?>">
                            <?= $b['status']; ?>
                        </span>

                        <?php if($status != 'selesai'){ ?>

                            <br>

                            <a href="?selesai=<?= $b['id_booking']; ?>"
                               class="btn-selesai">

                               Tandai Selesai

                            </a>

                        <?php } ?>

                    </td>

                </tr>

            <?php
                }

            } else {

                echo "
                <tr>
                    <td colspan='3' style='text-align:center;'>
                        Belum ada booking pelanggan.
                    </td>
                </tr>";
            }
            ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>