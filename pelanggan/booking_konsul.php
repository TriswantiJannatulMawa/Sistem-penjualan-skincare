<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: ../login.php");
    exit;
}

include '../includes/conn.php';

$id_pelanggan = $_SESSION['id_pelanggan'];


// ==========================
// PROSES BOOKING
// ==========================
if (isset($_POST['booking'])) {

    if(isset($_POST['id_jadwal'])){

        $id_jadwal = $_POST['id_jadwal'];

        // Cek apakah sudah booking
        $cek = mysqli_query($conn, "
            SELECT * FROM booking 
            WHERE id_pelanggan='$id_pelanggan'
            AND id_jadwal='$id_jadwal'
        ");

        if(mysqli_num_rows($cek) > 0){

            $error = "Kamu sudah membooking jadwal ini.";

        } else {

            $insert = mysqli_query($conn, "
                INSERT INTO booking
                (id_pelanggan, id_jadwal, tanggal_booking, status)
                VALUES
                (
                    '$id_pelanggan',
                    '$id_jadwal',
                    CURDATE(),
                    'Menunggu'
                )
            ");

            if($insert){

                echo "
                <script>
                    alert('Booking berhasil!');
                    window.location='riwayat_konsul.php';
                </script>
                ";
            }
        }

    } else {

        $error = "Silakan pilih jadwal terlebih dahulu.";
    }
}



// ==========================
// AMBIL DATA JADWAL
// ==========================
$q_jadwal = mysqli_query($conn, "
    SELECT * FROM jadwal
    WHERE tanggal >= CURDATE()
    ORDER BY tanggal ASC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Booking Konsultasi</title>

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
    margin-bottom:5px;
}

.header-text p{
    color:#8a6070;
    margin-bottom:30px;
}

.card-box{
    background:white;
    border-radius:20px;
    padding:30px;
    box-shadow:0 4px 15px rgba(0,0,0,0.03);
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
    padding:18px 10px;
    border-bottom:1px solid #f5f5f5;
    font-size:14px;
}

tr:hover{
    background:#fcfcfc;
}

.radio-jadwal{
    width:18px;
    height:18px;
    accent-color:#ff4f81;
    cursor:pointer;
}

.btn-submit{
    margin-top:25px;
    background:#ff4f81;
    color:white;
    border:none;
    padding:14px 25px;
    border-radius:12px;
    font-weight:600;
    cursor:pointer;
    width:100%;
    font-size:14px;
}

.btn-submit:hover{
    background:#e63f75;
}

.error-msg{
    background:#fef2f2;
    color:#ef4444;
    padding:14px;
    border-radius:10px;
    margin-bottom:20px;
    font-size:13px;
}

.empty{
    text-align:center;
    color:#999;
    padding:30px;
}

</style>

</head>

<body>

<?php include '../includes/sidebar_user.php'; ?>

<div class="main">

    <div class="header-text">

        <h1>Booking Konsultasi</h1>

        <p>
            Pilih jadwal konsultasi yang tersedia ✨
        </p>

    </div>


    <div class="card-box">

        <?php if(isset($error)){ ?>

            <div class="error-msg">
                <?= $error; ?>
            </div>

        <?php } ?>


        <form method="POST">

            <table>

                <thead>

                    <tr>
                        <th width="10%">Pilih</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Konsultan</th>
                    </tr>

                </thead>

                <tbody>

                <?php
                if(mysqli_num_rows($q_jadwal) > 0){

                    while($row = mysqli_fetch_assoc($q_jadwal)){
                ?>

                    <tr>

                        <td>
                            <input 
                                type="radio"
                                name="id_jadwal"
                                value="<?= $row['id_jadwal']; ?>"
                                class="radio-jadwal"
                                required
                            >
                        </td>

                        <td>
                            <?= date('d M Y', strtotime($row['tanggal'])); ?>
                        </td>

                        <td>
                            <?= $row['jam_mulai']; ?>
                        </td>

                        <td>
                            <strong>
                                <?= $row['konsultan']; ?>
                            </strong>
                        </td>

                    </tr>

                <?php
                    }

                } else {

                    echo "
                    <tr>
                        <td colspan='4' class='empty'>
                            Belum ada jadwal konsultasi tersedia.
                        </td>
                    </tr>
                    ";
                }
                ?>

                </tbody>

            </table>

            <?php if(mysqli_num_rows($q_jadwal) > 0){ ?>

                <button 
                    type="submit"
                    name="booking"
                    class="btn-submit"
                >
                    Booking Sekarang
                </button>

            <?php } ?>

        </form>

    </div>

</div>

</body>
</html>