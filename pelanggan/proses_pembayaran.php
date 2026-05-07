<?php
include '../includes/conn.php';

$id = $_POST['id_transaksi'];

$nama_file = $_FILES['bukti']['name'];
$tmp = $_FILES['bukti']['tmp_name'];

move_uploaded_file(
    $tmp,
    "../assets/bukti/" . $nama_file
);

mysqli_query($conn, "
    UPDATE transaksi
    SET
    bukti_pembayaran='$nama_file',
    status_pembayaran='Menunggu Verifikasi'
    WHERE id_transaksi='$id'
");

echo "
<script>
alert('Bukti pembayaran berhasil dikirim!');
window.location='pesanan_user.php';
</script>
";
?>