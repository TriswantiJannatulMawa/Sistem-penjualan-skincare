<?php
include '../conn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil dulu nama gambar untuk dihapus dari folder
    $ambil = mysqli_query($conn, "SELECT gambar FROM produk WHERE id_produk='$id'");
    $data = mysqli_fetch_assoc($ambil);

    // Hapus file gambar jika ada
    if ($data['gambar'] != "") {
        unlink("../gambar/" . $data['gambar']);
    }

    // Hapus data dari database
    mysqli_query($conn, "DELETE FROM produk WHERE id_produk='$id'");

    echo "<script>
        alert('Produk berhasil dihapus!');
        window.location='produk.php';
    </script>";
} else {
    echo "ID tidak ditemukan!";
}
?>