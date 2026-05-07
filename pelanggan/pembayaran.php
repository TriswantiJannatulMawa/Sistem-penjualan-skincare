<?php
session_start();
include '../includes/conn.php';

$id = $_GET['id'];

$query = mysqli_query($conn, "
    SELECT * FROM transaksi
    WHERE id_transaksi='$id'
");

$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Pembayaran</title>

<style>

body{
    font-family:Arial;
    background:#fbf6f8;
    padding:40px;
}

.card{
    max-width:500px;
    margin:auto;
    background:white;
    padding:30px;
    border-radius:20px;
}

h2{
    color:#ff4f81;
}

.kode{
    background:#fff0f5;
    padding:15px;
    border-radius:12px;
    text-align:center;
    font-size:22px;
    font-weight:bold;
    margin:20px 0;
    color:#ff4f81;
}

.btn{
    width:100%;
    background:#ff4f81;
    color:white;
    padding:14px;
    border:none;
    border-radius:12px;
    margin-top:20px;
    cursor:pointer;
}

input[type=file]{
    width:100%;
    margin-top:15px;
}

.qris{
    width:220px;
    display:block;
    margin:auto;
}

</style>
</head>

<body>

<div class="card">

    <h2>Pembayaran Pesanan</h2>

    <p><strong>Total:</strong></p>

    <h1>
        Rp <?= number_format($data['total_harga'],0,',','.'); ?>
    </h1>

    <p>Kode Pembayaran:</p>

    <div class="kode">
        <?= $data['kode_pembayaran']; ?>
    </div>

    <p>
        Silakan transfer sesuai total pembayaran.
    </p>

    <!-- QRIS -->
    

    <form action="proses_pembayaran.php" 
          method="POST" 
          enctype="multipart/form-data">

        <input type="hidden" 
               name="id_transaksi"
               value="<?= $data['id_transaksi']; ?>">

        <label>Upload Bukti Pembayaran</label>

        <input type="file" 
               name="bukti"
               required>

        <button type="submit" class="btn">
            Kirim Bukti Pembayaran
        </button>

    </form>

</div>

</body>
</html>