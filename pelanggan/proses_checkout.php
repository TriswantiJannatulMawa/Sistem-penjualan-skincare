<?php
session_start();
include "../includes/conn.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pelanggan = $_SESSION['id_pelanggan'];
    $total_harga = $_POST['total_harga'];
    $metode = $_POST['metode_pembayaran'];
    $mode = $_POST['mode'];

    // 1. Simpan ke tabel 'transaksi'
    $query_trx = mysqli_query($conn, "INSERT INTO transaksi 
        (id_pelanggan, tgl_transaksi, total_harga, metode_pembayaran, status_pembayaran) 
        VALUES ('$id_pelanggan', NOW(), '$total_harga', '$metode', 'Menunggu Pembayaran')");

    if ($query_trx) {
        $id_transaksi = mysqli_insert_id($conn);

        // 2. Simpan detail produk (Looping karena bisa lebih dari 1 produk)
        $id_produk_arr = $_POST['id_produk'];
        $jumlah_arr = $_POST['jumlah'];
        $harga_arr = $_POST['harga_satuan'];

        for ($i = 0; $i < count($id_produk_arr); $i++) {
            $id_p = $id_produk_arr[$i];
            $qty = $jumlah_arr[$i];
            $hrg = $harga_arr[$i];
            $subtotal = $qty * $hrg;

            // Sesuaikan dengan kolom SQL: id_transaksi, id_produk, jumlah, harga_satuan, subtotal
            mysqli_query($conn, "INSERT INTO detail_transaksi 
                (id_transaksi, id_produk, jumlah, harga_satuan, subtotal) 
                VALUES ('$id_transaksi', '$id_p', '$qty', '$hrg', '$subtotal')");
            
            // Opsional: Kurangi stok produk
            mysqli_query($conn, "UPDATE produk SET stok = stok - $qty WHERE id_produk = '$id_p'");
        }

        // 3. Jika beli dari keranjang, kosongkan keranjang pelanggan tersebut
        if ($mode == "keranjang") {
            mysqli_query($conn, "DELETE FROM keranjang WHERE id_pelanggan = '$id_pelanggan'");
        }

        echo "<script>alert('Pesanan berhasil dibuat!'); window.location='pesanan_user.php';</script>";
    } else {
        echo "<script>alert('Gagal memproses pesanan.'); window.history.back();</script>";
    }
}
?>