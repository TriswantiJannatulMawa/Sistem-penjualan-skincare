<?php
session_start();
include "../conn.php";
include '../includes/sidebar_user.php';

if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_SESSION['id_pelanggan'];

$data = mysqli_query($conn, "
SELECT keranjang.*, produk.nama_produk, produk.harga, produk.gambar
FROM keranjang
JOIN produk ON keranjang.id_produk = produk.id_produk
WHERE keranjang.id_pelanggan='$id'
");

$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
<title>Keranjang</title>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

body {
  background: #ffe5ec;
  display: flex;
}

/* SIDEBAR (opsional kalau mau sama dashboard) */
.sidebar {
  width: 240px;
  background: #fff;
  height: 100vh;
  padding: 20px;
}

.main {
  flex: 1;
  padding: 25px;
}

/* HEADER */
.topbar {
  display: flex;
  justify-content: space-between;
  margin-bottom: 20px;
}

/* FILTER STYLE */
.filter {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
}

.filter span {
  padding: 8px 15px;
  background: #ffd1dc;
  border-radius: 20px;
  font-size: 14px;
}

/* CARD PRODUK */
.cart-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: #fff;
  padding: 15px;
  border-radius: 15px;
  margin-bottom: 15px;
}

.left {
  display: flex;
  gap: 15px;
  align-items: center;
}

.left img {
  width: 60px;
  height: 60px;
  border-radius: 10px;
  object-fit: cover;
}

.info h4 {
  font-size: 16px;
}

.info p {
  color: #ff4f81;
  font-weight: bold;
}

.qty {
  font-size: 14px;
}

/* BUTTON */
.btn {
  background: #ff7aa2;
  color: white;
  border: none;
  padding: 8px 15px;
  border-radius: 10px;
  cursor: pointer;
}

.btn:hover {
  background: #ff4f81;
}

/* TOTAL */
.total-box {
  background: #fff;
  padding: 20px;
  border-radius: 15px;
  text-align: right;
  margin-top: 20px;
}
</style>
</head>

<body>

<div class="main">

<div class="topbar">
  <h2>Keranjang Saya</h2>
</div>

<?php if(mysqli_num_rows($data) > 0) { ?>

  <?php while($row = mysqli_fetch_assoc($data)) { 
    $subtotal = $row['harga'] * $row['jumlah'];
    $total += $subtotal;
  ?>

  <div class="cart-item">
    
    <div class="left">
      <img src="../gambar/<?= $row['gambar']; ?>">

      <div class="info">
        <h4><?= $row['nama_produk']; ?></h4>
        <p>Rp<?= number_format($row['harga']); ?></p>
      </div>
    </div>

    <div class="qty">
      x<?= $row['jumlah']; ?>
    </div>

  </div>

  <?php } ?>

  <div class="total-box">
    <h3>Total: Rp<?= number_format($total); ?></h3>

    <form action="checkout.php" method="POST">
      <button class="btn">Checkout</button>
    </form>
  </div>

<?php } else { ?>

  <p>Keranjang kosong 😢</p>

<?php } ?>

</div>

</body>
</html>