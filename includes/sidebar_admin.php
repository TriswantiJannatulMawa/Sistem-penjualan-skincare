<?php
$current = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
  <h2>MAARS Beauty</h2>
  <ul class="menu">
    <li><a href="dashboard_admin.php" <?= $current == 'dashboard_admin.php' ? 'class="active"' : '' ?>>Dashboard</a></li>
    <li><a href="produk.php" <?= $current == 'produk.php' ? 'class="active"' : '' ?>>Produk</a></li>
    <li><a href="pesanan.php" <?= $current == 'pesanan.php' ? 'class="active"' : '' ?>>Pesanan</a></li>
    <li><a href="konsultasi.php" <?= $current == 'konsultasi.php' ? 'class="active"' : '' ?>>Konsultasi</a></li>
    <li><a href="laporan.php" <?= $current == 'laporan.php' ? 'class="active"' : '' ?>>Laporan</a></li>
  </ul>
</div>