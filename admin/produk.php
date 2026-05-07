<?php 
include '../conn.php'; 

$data = mysqli_query($conn, "SELECT * FROM produk");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Produk Admin</title>

<link rel="stylesheet" href="style.css">

<style>
/* (SEMUA CSS KAMU TETAP, TIDAK DIUBAH) */
* { 
  margin:0; 
  padding:0; 
  box-sizing:border-box; 
  font-family:'Poppins',sans-serif; 
  }
body { 
  background:#ffe5ec; 
  display:flex; }

.sidebar {
  width:240px; 
  background:#fff; 
  height:100vh; 
  padding:20px;
  box-shadow:2px 0 10px rgba(0,0,0,0.05);
}
.sidebar h2 { 
  color:#ff7aa2; 
  margin-bottom:30px;
 }

.menu { list-style:none; }
.menu-container {
  display:inline-block; 
  padding:12px 24px; 
  color:black;
  text-decoration:none; 
  font-weight:bold; 
  border-radius:8px;
}
.menu a:hover { 
  background:#ffe6ee; 
  color:#ff4f81; }

.main { 
  flex:1; 
  padding:25px;
}

.topbar {
  display:flex; 
  justify-content:space-between; 
  margin-bottom:25px;
}

.btn-tambah {
  background:#ff7aa2; 
  color:white; 
  padding:8px 15px;
  border:none; 
  border-radius:6px; 
  text-decoration:none;
}
.btn-tambah:hover { background:#ff4f81; }

.box {
  background:#fff; 
  padding:20px; 
  border-radius:15px;
  box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

table { 
  width:100%; 
  border-collapse:collapse; 
  margin-top:10px;
}
th, td { 
  padding:10px; 
  border-bottom:1px solid #eee;
}

.btn {
  background:#ff7aa2; 
  color:white; 
  padding:5px 10px;
  border:none; 
  border-radius:6px; 
  text-decoration:none;
}
</style>
</head>

<body>

<div class="sidebar">
    <h2>MAARS Beauty</h2>
    <ul class="menu">
    <li>
      <a href="dashboard.php" class="menu-container">Dashboard</a>
      </li>
      <li>
      <a href="produk.php" class="menu-container">Produk</a>
      </li>
      <li>
      <a href="pesanan.php" class="menu-container">Pesanan</a>
      </li>
      <li>
      <a href="konsultasi.php" class="menu-container">Konsultasi</a>
      </li>
      <li>
      <a href="laporan.php" class="menu-container">Laporan</a>
      </li>
    </ul>
  </div>

<div class="main">

  <div class="topbar">
    <a href="tambah_produk.php" class="btn-tambah">+ Tambah Produk</a>
    <div>Halo, Admin 👋</div>
  </div>

  <div class="box">
    <h3>Data Produk</h3>

    <table>
      <tr>
        <th>ID</th>
        <th>Gambar</th>
        <th>Nama</th>
        <th>Harga</th>
        <th>Stok</th>
        <th>Aksi</th>
      </tr>

      <?php while($row = mysqli_fetch_assoc($data)) { ?>
      <tr>
        <td><?= $row['id_produk']; ?></td>
        <td>
          <img src="../gambar/<?= $row['gambar']; ?>" width="60">
        </td>
        <td><?= $row['nama_produk']; ?></td>
        <td>Rp<?= number_format($row['harga']); ?></td>
        <td><?= $row['stok']; ?></td>
        <td>
          <a href="hapus_produk.php?id=<?= $row['id_produk']; ?>" class="btn">Hapus</a>
          <a href="edit_produk.php?id=<?= $row['id_produk']; ?>" class="btn">Edit</a>
        </td>
      </tr>
      <?php } ?>

    </table>
  </div>

</div>

</body>
</html>