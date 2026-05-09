<?php
session_start();

include '../includes/conn.php';

if(!isset($_SESSION['id_pelanggan'])){
    header("Location: ../login.php");
    exit;
}

$id_pelanggan = $_SESSION['id_pelanggan'];

$items = [];
$total_bayar = 0;


// ======================================
// BELI LANGSUNG
// ======================================
if(isset($_POST['id_produk'])){

    $id_produk = $_POST['id_produk'];

    $query = mysqli_query($conn, "
        SELECT *
        FROM produk
        WHERE id_produk='$id_produk'
    ");

    $produk = mysqli_fetch_assoc($query);

    if(!$produk){
        die("Produk tidak ditemukan");
    }

    $items[] = [
        'id_produk'   => $produk['id_produk'],
        'nama_produk' => $produk['nama_produk'],
        'harga'       => $produk['harga'],
        'jumlah'      => 1,
        'gambar'      => $produk['gambar']
    ];
}


// ======================================
// DARI KERANJANG
// ======================================
elseif(isset($_POST['pilih_produk'])){

    foreach($_POST['pilih_produk'] as $id_produk){

        $query = mysqli_query($conn, "
            SELECT
                k.*,
                p.nama_produk,
                p.harga,
                p.gambar
            FROM keranjang k
            JOIN produk p
            ON k.id_produk = p.id_produk
            WHERE
                k.id_pelanggan='$id_pelanggan'
                AND k.id_produk='$id_produk'
        ");

        $row = mysqli_fetch_assoc($query);

        if($row){
            $items[] = $row;
        }
    }

}else{
    die("Produk tidak ditemukan");
}



// ======================================
// TOTAL
// ======================================
foreach($items as $item){

    $total_bayar += (
        $item['harga'] * $item['jumlah']
    );
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Checkout - MAARS Beauty</title>

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
    font-size:34px;
    color:#1a0a12;
    margin-bottom:8px;
}

.header-text p{
    color:#8a6070;
    margin-bottom:30px;
    font-size:14px;
}

.checkout-grid{
    display:grid;
    grid-template-columns:1.7fr 1fr;
    gap:25px;
}

.card{
    background:white;
    border-radius:24px;
    padding:30px;
    box-shadow:0 6px 18px rgba(0,0,0,0.03);
    border:1px solid rgba(240,221,229,0.5);
}

.card-title{
    font-family:'Playfair Display',serif;
    font-size:24px;
    margin-bottom:25px;
    color:#1a0a12;
}

.product-item{
    display:flex;
    align-items:center;
    gap:18px;
    padding:18px 0;
    border-bottom:1px solid #f3f3f3;
}

.product-item:last-child{
    border-bottom:none;
}

.product-item img{
    width:90px;
    height:90px;
    border-radius:18px;
    object-fit:cover;
    border:1px solid #f0f0f0;
}

.product-info{
    flex:1;
}

.product-info h4{
    font-size:16px;
    color:#1a0a12;
    margin-bottom:8px;
    font-weight:700;
}

.product-info p{
    font-size:13px;
    color:#8a6070;
}

.product-price{
    font-weight:700;
    color:#ff4f81;
    font-size:16px;
}

.summary-box{
    background:#fff7fa;
    border-radius:18px;
    padding:22px;
}

.summary-row{
    display:flex;
    justify-content:space-between;
    margin-bottom:18px;
    font-size:14px;
    color:#8a6070;
}

.total-row{
    display:flex;
    justify-content:space-between;
    font-size:24px;
    font-weight:700;
    color:#ff4f81;
    margin-top:18px;
    padding-top:18px;
    border-top:2px dashed #f0dde5;
}

.payment-box{
    margin-top:20px;
    background:#fdf2f6;
    border:1px solid #ffd4e1;
    padding:16px;
    border-radius:14px;
}

.payment-box h5{
    color:#ff4f81;
    margin-bottom:8px;
    font-size:15px;
}

.payment-box p{
    font-size:13px;
    color:#8a6070;
    line-height:1.6;
}

.btn-checkout{
    width:100%;
    padding:16px;
    border:none;
    border-radius:16px;
    background:#ff4f81;
    color:white;
    font-size:15px;
    font-weight:700;
    cursor:pointer;
    margin-top:25px;
    transition:0.3s;
    box-shadow:0 6px 14px rgba(255,79,129,0.25);
}

.btn-checkout:hover{
    background:#e63e6d;
    transform:translateY(-2px);
}

.empty{
    text-align:center;
    color:#8a6070;
    padding:30px;
}

</style>

</head>

<body>

<?php include '../includes/sidebar_user.php'; ?>

<div class="main">

    <div class="header-text">

        <h1>Checkout Pesanan</h1>

        <p>
            Periksa kembali produk sebelum melanjutkan pembayaran ✨
        </p>

    </div>


    <form action="proses_checkout.php" method="POST">

        <div class="checkout-grid">

            <!-- PRODUK -->
            <div class="card">

                <div class="card-title">
                    Produk Dipilih
                </div>

                <?php if(count($items) > 0): ?>

                    <?php foreach($items as $item): ?>

                    <div class="product-item">

                        <img src="../assets/gambar/<?= $item['gambar']; ?>">

                        <div class="product-info">

                            <h4>
                                <?= $item['nama_produk']; ?>
                            </h4>

                            <p>
                                <?= $item['jumlah']; ?> x
                                Rp <?= number_format($item['harga'],0,',','.'); ?>
                            </p>

                        </div>

                        <div class="product-price">

                            Rp <?= number_format(
                                $item['harga'] * $item['jumlah'],
                                0,
                                ',',
                                '.'
                            ); ?>

                        </div>

                    </div>


                    <!-- DATA -->
                    <input
                        type="hidden"
                        name="id_produk[]"
                        value="<?= $item['id_produk']; ?>"
                    >

                    <input
                        type="hidden"
                        name="jumlah[]"
                        value="<?= $item['jumlah']; ?>"
                    >

                    <input
                        type="hidden"
                        name="harga_satuan[]"
                        value="<?= $item['harga']; ?>"
                    >

                    <?php endforeach; ?>

                <?php else: ?>

                    <div class="empty">
                        Tidak ada produk dipilih
                    </div>

                <?php endif; ?>

            </div>



            <!-- RINGKASAN -->
            <div class="card">

                <div class="card-title">
                    Ringkasan Belanja
                </div>

                <div class="summary-box">

                    <div class="summary-row">

                        <span>Total Produk</span>

                        <span>
                            <?= count($items); ?>
                        </span>

                    </div>

                    <div class="summary-row">

                        <span>Status</span>

                        <span>
                            Menunggu Pembayaran
                        </span>

                    </div>

                    <div class="summary-row">

                        <span>Metode</span>

                        <span>
                            Transfer Bank
                        </span>

                    </div>

                    <div class="total-row">

                        <span>Total</span>

                        <span>

                            Rp <?= number_format(
                                $total_bayar,
                                0,
                                ',',
                                '.'
                            ); ?>

                        </span>

                    </div>

                </div>


                <!-- METODE -->
                <input
                    type="hidden"
                    name="metode_pembayaran"
                    value="Transfer Bank"
                >


                <!-- TOTAL -->
                <input
                    type="hidden"
                    name="total_harga"
                    value="<?= $total_bayar; ?>"
                >


                <div class="payment-box">

                    <h5>Transfer Bank</h5>

                    <p>
                        Silakan lakukan pembayaran melalui transfer bank
                        setelah checkout berhasil dilakukan.
                    </p>

                </div>


                <button type="submit" class="btn-checkout">

                    Checkout Sekarang

                </button>

            </div>

        </div>

    </form>

</div>

</body>
</html>