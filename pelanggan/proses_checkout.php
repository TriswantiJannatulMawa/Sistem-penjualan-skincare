<?php
session_start();
include "../conn.php";
include '../includes/sidebar_user.php';

$id_pelanggan = $_SESSION['id_pelanggan'];
$id_produk = $_POST['id_produk'];
$jumlah = $_POST['jumlah'];

// ambil harga
$p = mysqli_query($conn, "SELECT harga FROM produk WHERE id_produk='$id_produk'");
$d = mysqli_fetch_assoc($p);

$harga = $d['harga'];
$total = $harga * $jumlah;

// insert transaksi
mysqli_query($conn, "
INSERT INTO transaksi (id_pelanggan, tgl_transaksi, total_harga, status_pembayaran)
VALUES ('$id_pelanggan', NOW(), '$total', 'Belum Bayar')
");

$id_transaksi = mysqli_insert_id($conn);

// insert detail
mysqli_query($conn, "
INSERT INTO detail_transaksi (id_transaksi, id_produk, jumlah, harga)
VALUES ('$id_transaksi','$id_produk','$jumlah','$harga')
");

// redirect
header("Location: pesanan_user.php");
exit;
?>