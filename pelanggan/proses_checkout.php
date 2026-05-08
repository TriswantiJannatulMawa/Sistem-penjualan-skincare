<?php
session_start();

include '../includes/conn.php';

if(!isset($_SESSION['id_pelanggan'])){
    header("Location: ../login.php");
    exit;
}

$id_pelanggan = $_SESSION['id_pelanggan'];

$total = $_POST['total_harga'] ?? 0;

if($total <= 0){
    die("Checkout gagal");
}


// ======================================
// BUAT KODE PEMBAYARAN
// ======================================
$kode = "MB-" . strtoupper(substr(md5(rand()),0,8));


// ======================================
// SIMPAN TRANSAKSI
// ======================================
mysqli_query($conn, "
    INSERT INTO transaksi
    (
        id_pelanggan,
        total_harga,
        kode_pembayaran
    )
    VALUES
    (
        '$id_pelanggan',
        '$total',
        '$kode'
    )
");

$id_transaksi = mysqli_insert_id($conn);


// ======================================
// DETAIL TRANSAKSI
// ======================================
foreach($_POST['id_produk'] as $key => $id_produk){

    $jumlah = $_POST['jumlah'][$key];

    $harga = $_POST['harga_satuan'][$key];

    // SIMPAN DETAIL
    mysqli_query($conn, "
        INSERT INTO detail_transaksi
        (
            id_transaksi,
            id_produk,
            jumlah,
            harga
        )
        VALUES
        (
            '$id_transaksi',
            '$id_produk',
            '$jumlah',
            '$harga'
        )
    ");

    // KURANGI STOK
    mysqli_query($conn, "
        UPDATE produk
        SET stok = stok - $jumlah
        WHERE id_produk='$id_produk'
    ");

    // HAPUS DARI KERANJANG
    mysqli_query($conn, "
        DELETE FROM keranjang
        WHERE
            id_pelanggan='$id_pelanggan'
            AND id_produk='$id_produk'
    ");
}


// ======================================
// REDIRECT
// ======================================
header("Location: pembayaran.php?id=$id_transaksi");
exit;
?>