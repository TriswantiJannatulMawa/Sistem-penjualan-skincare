<?php
session_start();
include "../conn.php";

var_dump($_GET);
exit;
?>

<!DOCTYPE html>
<html>
<head>
<title>Checkout</title>

<style>
body {
  font-family: 'Poppins', sans-serif;
  background: #ffe5ec;
  padding: 30px;
}

.box {
  background: white;
  padding: 20px;
  border-radius: 15px;
  max-width: 500px;
  margin: auto;
  text-align: center;
}

img {
  width: 120px;
  height: 120px;
  object-fit: cover;
  border-radius: 10px;
}

button {
  background: #ff7aa2;
  color: white;
  padding: 12px;
  border: none;
  border-radius: 10px;
  width: 100%;
  margin-top: 20px;
  cursor: pointer;
}

button:hover {
  background: #ff4f81;
}
</style>
</head>

<body>

<div class="box">

<h2>Checkout</h2>

<img src="../gambar/<?= $data['gambar']; ?>">

<p><b><?= $data['nama_produk']; ?></b></p>
<p>Harga: Rp<?= number_format($data['harga']); ?></p>
<p>Jumlah: 1</p>

<hr>

<h3>Total: Rp<?= number_format($total); ?></h3>

<form action="proses_checkout.php" method="POST">
  <input type="hidden" name="id_produk" value="<?= $data['id_produk']; ?>">
  <input type="hidden" name="jumlah" value="1">

  <button type="submit">Bayar Sekarang</button>
</form>

</div>

</body>
</html>