<?php 
include '../conn.php'; 
include '../includes/sidebar_admin.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pesanan Admin</title>

<link rel="stylesheet" href="style.css">

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

/* SIDEBAR */
.sidebar {
  width: 240px;
  background: #fff;
  height: 100vh;
  padding: 20px;
  box-shadow: 2px 0 10px rgba(0,0,0,0.05);
}

.sidebar h2 {
  color: #ff7aa2;
  margin-bottom: 30px;
}

.menu {
  list-style: none;
}

.menu li {
  margin-bottom: 10px;
}

.menu a {
  display: block;
  padding: 12px;
  border-radius: 10px;
  text-decoration: none;
  color: black;
}

.menu a:hover,
.menu a.active {
  background: #ffe6ee;
  color: #ff4f81;
}

/* MAIN */
.main {
  flex: 1;
  padding: 25px;
}

.topbar {
  display: flex;
  justify-content: space-between;
  margin-bottom: 20px;
}

/* FILTER */
.filter {
  margin-bottom: 20px;
}

.filter button {
  padding: 8px 15px;
  border: none;
  border-radius: 20px;
  margin-right: 5px;
  cursor: pointer;
  background: #ffd1dc;
  color: #ff4f81;
}

.filter button.active {
  background: #ff7aa2;
  color: white;
}

/* LIST */
.order-list {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

/* CARD */
.order-card {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: white;
  padding: 15px;
  border-radius: 15px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.05);
  transition: 0.3s;
}

.order-card:hover {
  transform: translateY(-3px);
}

/* LEFT */
.order-left {
  display: flex;
  align-items: center;
  gap: 15px;
}

.order-left img {
  width: 70px;
  height: 70px;
  border-radius: 10px;
  object-fit: cover;
}

.order-info h4 {
  margin-bottom: 5px;
}

.price {
  color: #ff4f81;
  font-weight: bold;
}

/* STATUS */
.status {
  padding: 5px 10px;
  border-radius: 20px;
  font-size: 12px;
  color: white;
  display: inline-block;
  margin-top: 5px;
}

.menunggu { background: orange; }
.diproses { background: #3498db; }
.dikirim { background: purple; }
.selesai { background: #2ecc71; }

/* RIGHT */
.order-right {
  text-align: right;
}

.btn {
  margin-top: 8px;
  background: #ff7aa2;
  color: white;
  padding: 6px 12px;
  border-radius: 8px;
  border: none;
  cursor: pointer;
}

.btn:hover {
  background: #ff4f81;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
  <h2>MAARS Beauty</h2>
  <ul class="menu">
    <li><a href="dashboard_admin.php">Dashboard</a></li>
    <li><a href="produk.php">Produk</a></li>
    <li><a href="pesanan.php" class="active">Pesanan</a></li>
    <li><a href="konsultasi.php">Konsultasi</a></li>
    <li><a href="laporan.php">Laporan</a></li>
  </ul>
</div>

<!-- MAIN -->
<div class="main">

  <div class="topbar">
    <h3>Kelola Pesanan</h3>
    <div>Halo, Admin 👋</div>
  </div>

  <!-- FILTER -->
  <div class="filter">
    <button class="active" onclick="filterOrder('all')">Semua</button>
    <button onclick="filterOrder('menunggu')">Menunggu</button>
    <button onclick="filterOrder('diproses')">Diproses</button>
    <button onclick="filterOrder('dikirim')">Dikirim</button>
    <button onclick="filterOrder('selesai')">Selesai</button>
  </div>

  <!-- LIST -->
  <div class="order-list">

    <div class="order-card" data-status="menunggu">
      <div class="order-left">
        <img src="../gambar/serum.jpg">
        <div class="order-info">
          <h4>Serum Vitamin C</h4>
          <p class="price">Rp120.000</p>
          <span class="status menunggu">Menunggu</span>
        </div>
      </div>
      <div class="order-right">
        <p>x2</p>
        <button class="btn">Proses</button>
      </div>
    </div>

    <div class="order-card" data-status="diproses">
      <div class="order-left">
        <img src="../gambar/fw.png">
        <div class="order-info">
          <h4>Facial Wash</h4>
          <p class="price">Rp75.000</p>
          <span class="status diproses">Diproses</span>
        </div>
      </div>
      <div class="order-right">
        <p>x1</p>
        <button class="btn">Kirim</button>
      </div>
    </div>

    <div class="order-card" data-status="dikirim">
      <div class="order-left">
        <img src="../gambar/fw.png">
        <div class="order-info">
          <h4>Facial Wash</h4>
          <p class="price">Rp75.000</p>
          <span class="status dikirim">Dikirim</span>
        </div>
      </div>
      <div class="order-right">
        <p>x3</p>
        <button class="btn">Selesaikan</button>
      </div>
    </div>

    <div class="order-card" data-status="selesai">
      <div class="order-left">
        <img src="../gambar/serum.jpg">
        <div class="order-info">
          <h4>Serum Vitamin C</h4>
          <p class="price">Rp120.000</p>
          <span class="status selesai">Selesai</span>
        </div>
      </div>
      <div class="order-right">
        <p>x1</p>
        <button class="btn">Detail</button>
      </div>
    </div>

  </div>

</div>

<script>
function filterOrder(status) {
  let cards = document.querySelectorAll(".order-card");
  let buttons = document.querySelectorAll(".filter button");

  buttons.forEach(btn => btn.classList.remove("active"));
  event.target.classList.add("active");

  cards.forEach(card => {
    if (status === "all") {
      card.style.display = "flex";
    } else {
      card.style.display =
        card.dataset.status === status ? "flex" : "none";
    }
  });
}
</script>

</body>
</html>