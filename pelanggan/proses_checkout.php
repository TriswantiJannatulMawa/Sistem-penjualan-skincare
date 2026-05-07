<?php
session_start();
include '../includes/conn.php';

$id_pelanggan = $_SESSION['id_pelanggan'];

$metode = $_POST['metode_pembayaran'];
$total = $_POST['total_harga'];


// BUAT KODE PEMBAYARAN
$kode = "MB-" . strtoupper(substr(md5(rand()), 0, 8));


// SIMPAN PESANAN
mysqli_query($conn, "
    INSERT INTO transaksi 
    (id_pelanggan, total_harga, metode_pembayaran, kode_pembayaran)
    VALUES
    ('$id_pelanggan', '$total', '$metode', '$kode')
");

$id_pesanan = mysqli_insert_id($conn);


// SIMPAN DETAIL PESANAN
foreach($_POST['id_produk'] as $key => $id_produk){

    $jumlah = $_POST['jumlah'][$key];
    $harga = $_POST['harga_satuan'][$key];

    mysqli_query($conn, "
        INSERT INTO detail_transaksi
        (id_transaksi, id_produk, jumlah, harga)
        VALUES
        ('$id_pesanan', '$id_produk', '$jumlah', '$harga')
    ");
}


// HAPUS KERANJANG
if($_POST['mode'] == 'keranjang'){
    mysqli_query($conn, "
        DELETE FROM keranjang 
        WHERE id_pelanggan='$id_pelanggan'
    ");
}


// REDIRECT KE PEMBAYARAN
header("Location: pembayaran.php?id=$id_pesanan");
exit;
?>