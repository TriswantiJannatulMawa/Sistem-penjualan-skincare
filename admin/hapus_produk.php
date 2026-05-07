<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../includes/conn.php';

// Cek apakah ada parameter ID di URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 1. Ambil nama file gambar dari database sebelum barisnya dihapus
    $query = mysqli_query($conn, "SELECT gambar FROM produk WHERE id_produk = '$id'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        $gambar = $data['gambar'];
        // 2. Hapus file fisik gambar dari folder assets/gambar
        if (file_exists('../assets/gambar/' . $gambar) && $gambar != "") {
            unlink('../assets/gambar/' . $gambar);
        }

        // 3. Hapus data dari tabel database
        $hapus_query = mysqli_query($conn, "DELETE FROM produk WHERE id_produk = '$id'");

        if ($hapus_query) {
            echo "<script>alert('Produk berhasil dihapus!'); window.location='produk.php';</script>";
        } else {
            // Error jika produk masih terhubung ke tabel transaksi (Foreign Key)
            echo "<script>alert('Gagal menghapus! Produk ini mungkin memiliki riwayat transaksi.'); window.location='produk.php';</script>";
        }
    }
} else {
    header("Location: produk.php");
}
exit;
?>