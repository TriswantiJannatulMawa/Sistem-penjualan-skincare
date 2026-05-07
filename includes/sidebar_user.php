<?php
$current = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
  <h2>MAARS Beauty</h2>
  <ul class="menu">
    <li><a href="dashboard.php" <?= $current == 'dashboard.php' ? 'class="active"' : '' ?>>Dashboard</a></li>
    <li><a href="produk_user.php" <?= $current == 'produk_user.php' ? 'class="active"' : '' ?>>Produk</a></li>
    <li><a href="keranjang.php" <?= $current == 'keranjang.php' ? 'class="active"' : '' ?>>Keranjang</a></li>
    <li><a href="pesanan_user.php" <?= $current == 'pesanan_user.php' ? 'class="active"' : '' ?>>Pesanan Saya</a></li>
    <li><a href="booking_konsul.php" <?= $current == 'booking_konsul.php' ? 'class="active"' : '' ?>>Booking Konsultasi</a></li>
    <li><a href="riwayat_konsul.php" <?= $current == 'riwayat_konsul.php' ? 'class="active"' : '' ?>>Riwayat Konsultasi</a></li>
    <li><a href="profil.php" <?= $current == 'profil.php' ? 'class="active"' : '' ?>>Profil</a></li>
  </ul>
</div>