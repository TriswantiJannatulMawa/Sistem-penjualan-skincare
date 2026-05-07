<?php
session_start();
include "../conn.php";

$id_pelanggan = $_SESSION['id_pelanggan'];
$id_produk = $_POST['id_produk'];

// cek apakah produk sudah ada di keranjang
$cek = mysqli_query($conn, "SELECT * FROM keranjang 
WHERE id_pelanggan='$id_pelanggan' AND id_produk='$id_produk'");

if(mysqli_num_rows($cek) > 0){
    // kalau sudah ada → tambah jumlah
    mysqli_query($conn, "UPDATE keranjang 
    SET jumlah = jumlah + 1 
    WHERE id_pelanggan='$id_pelanggan' AND id_produk='$id_produk'");
} else {
    // kalau belum ada → insert baru
    mysqli_query($conn, "INSERT INTO keranjang 
    (id_pelanggan, id_produk, jumlah) 
    VALUES ('$id_pelanggan','$id_produk',1)");
}

// arahkan ke keranjang
header("Location: keranjang.php");