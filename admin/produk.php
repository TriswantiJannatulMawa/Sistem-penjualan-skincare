<?php
session_start();

// Cek apakah user sudah login dan rolenya admin
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../includes/conn.php';

// Ambil data produk
$query = mysqli_query($conn, "SELECT * FROM produk ORDER BY id_produk DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Produk - MAARS Beauty</title>
  
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="../assets/css/global.css">
  <link rel="stylesheet" href="../assets/css/sidebar.css">

  <style>
    body {
      font-family: 'DM Sans', sans-serif;
      background-color: #fbf6f8; /* Soft pink sesuai dashboard */
      display: flex;
    }

    .main {
      flex: 1;
      padding: 40px;
      height: 100vh;
      overflow-y: auto;
    }

    /* TOPBAR */
    .topbar-modern {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 30px;
    }

    .header-text h1 {
      font-family: 'Playfair Display', serif;
      font-size: 28px;
      color: #1a0a12;
      margin-bottom: 6px;
    }

    .header-text p {
      color: #8a6070;
      font-size: 14px;
    }

    .header-actions {
      display: flex;
      gap: 15px;
      align-items: center;
    }

    /* SEARCH BAR */
    .search-box {
      background: white;
      border: 1px solid #eee;
      padding: 10px 15px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      gap: 10px;
      width: 250px;
    }

    .search-box input {
      border: none;
      outline: none;
      width: 100%;
      font-family: 'DM Sans', sans-serif;
      font-size: 13px;
    }

    /* TOMBOL TAMBAH (Tanpa efek melompat) */
    .btn-add {
      background: #ff4f81;
      color: white;
      text-decoration: none;
      padding: 12px 20px;
      border-radius: 12px;
      font-size: 14px;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .btn-add:hover {
      background: #e63e6d; /* Hanya ubah warna sedikit lebih gelap saat di-hover */
    }

    /* CARD TABEL */
    .card-box {
      background: #ffffff;
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }

    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .card-header h3 {
      font-family: 'Playfair Display', serif;
      font-size: 18px;
      color: #1a0a12;
    }

    /* TABEL BERSIH */
    table {
      width: 100%;
      border-collapse: collapse;
    }

    th {
      text-align: left;
      padding: 15px 10px;
      color: #a08892;
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      border-bottom: 2px solid #f0f0f0;
    }

    td {
      padding: 15px 10px;
      border-bottom: 1px solid #f5f5f5;
      font-size: 14px;
      color: #444;
      vertical-align: middle;
    }

    /* Hanya efek background super tipis saat baris di-hover, tanpa scale/transform */
    tbody tr:hover {
      background-color: #fcfcfc;
    }

    .product-img {
      width: 50px;
      height: 50px;
      border-radius: 10px;
      object-fit: cover;
      background: #f9f9f9;
      padding: 2px;
      border: 1px solid #eee;
    }

    .product-name {
      font-weight: 600;
      color: #1a0a12;
    }

    /* BADGE STOK */
    .badge-stok {
      background: #eaffea;
      color: #2ecc71;
      padding: 6px 12px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 600;
      display: inline-block;
    }

    .badge-stok.tipis {
      background: #fff5e6;
      color: #f39c12;
    }

    /* TOMBOL AKSI (Tanpa efek transform) */
    .btn-action {
      padding: 8px 12px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 500;
      text-decoration: none;
      margin-right: 5px;
    }

    .btn-edit { background: #fdf2f6; color: #ff4f81; }
    .btn-edit:hover { background: #ff4f81; color: white; }

    .btn-delete { background: #fef2f2; color: #ef4444; }
    .btn-delete:hover { background: #ef4444; color: white; }
  </style>
</head>
<body>

  <?php include '../includes/sidebar_admin.php'; ?>

  <div class="main">
    
    <div class="topbar-modern">
      <div class="header-text">
        <h1>Daftar Produk</h1>
        <p>Kelola katalog dan stok skincare MAARS Beauty 🛍️</p>
      </div>
      <div class="header-actions">
        <div class="search-box">
          <span style="color: #aaa; font-size: 14px;">🔍</span>
          <input type="text" placeholder="Cari...">
        </div>
        <a href="tambah_produk.php" class="btn-add">+ Tambah Baru</a>
      </div>
    </div>

    <div class="card-box">
      <div class="card-header">
        <h3>Semua Produk</h3>
      </div>
      
      <table>
        <thead>
          <tr>
            <th width="5%">No</th>
            <th width="10%">Foto</th>
            <th width="30%">Nama Produk</th>
            <th width="15%">Harga</th>
            <th width="15%">Stok</th>
            <th width="25%">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if(mysqli_num_rows($query) > 0) {
            $no = 1;
            while($row = mysqli_fetch_assoc($query)) {
              // Logika warna stok (jika di bawah 5, warnanya jadi peringatan)
              $stok_class = ($row['stok'] > 5) ? 'badge-stok' : 'badge-stok tipis';
              
              echo "<tr>";
              echo "<td>{$no}</td>";
              echo "<td><img src='../assets/gambar/{$row['gambar']}' class='product-img' alt='gambar'></td>";
              echo "<td class='product-name'>{$row['nama_produk']}</td>";
              echo "<td style='font-weight:600;'>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
              echo "<td><span class='{$stok_class}'>{$row['stok']} Unit</span></td>";
              echo "<td>
                      <a href='edit_produk.php?id={$row['id_produk']}' class='btn-action btn-edit'>Edit</a>
                      <a href='hapus_produk.php?id={$row['id_produk']}' class='btn-action btn-delete' onclick=\"return confirm('Yakin ingin menghapus produk ini?')\">Hapus</a>
                    </td>";
              echo "</tr>";
              $no++;
            }
          } else {
            echo "<tr><td colspan='6' style='text-align:center; color:#888; padding:30px;'>Belum ada produk.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>

  </div>

</body>
</html>