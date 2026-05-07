<?php
session_start();
include 'conn.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>BeautyFlow - Landing Page</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

    <nav>
        <div class="logo">
            <div class="logo-icon">
                <img src="gambar/icon.png" alt="logo">
            </div>
            MAARS Beauty
        </div>
        <div class="nav-links">
            <a href="login.php" class="btn-login">Masuk</a>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <h1>Kulit Sehat,percaya diri setiap hari✨</h1>
            <p>Dapatkan produk skincare terbaikmu untuk kulitmu</p>
        </div>
        
        <div class="hero-image-container">
            <img src="gambar/scincare.jpeg" class="slide active" alt="Promo 1">
            <img src="gambar/g2g.jpg" class="slide" alt="Promo 2">
            <img src="gambar/wardah.png" class="slide" alt="Promo 3">
            
            <div class="dots">
                <span class="dot active" onclick="manualSlide(0)"></span>
                <span class="dot" onclick="manualSlide(1)"></span>
                <span class="dot" onclick="manualSlide(2)"></span>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="card">
            <div class="card-icon">
                <img src="gambar/wardah2.png" alt="">
            </div>
            <h3>Wardah</h3>
            <p>Pantau ribuan SKU produk kosmetik dengan peringatan stok menipis.</p>
        </div>
        <div class="card">
            <div class="card-icon">
                <img src="gambar/skintific2.png" alt="">
            </div>
            <h3>Scintifik</h3>
            <p>Lihat keuntungan bersih dan produk terlaris Anda dalam satu klik.</p>
        </div>
        <div class="card">
            <div class="card-icon">
                <img src="gambar/emina2.png" alt="">
            </div>
            <h3>Emina</h3>
            <p>Kelola poin pelanggan untuk meningkatkan transaksi berulang.</p>
        </div>
        <div class="card">
            <div class="card-icon">
                <img src="gambar/emina2.png" alt="">
            </div>
            <h3>Loyalty Member</h3>
            <p>Kelola poin pelanggan untuk meningkatkan transaksi berulang.</p>
        </div>
        <div class="card">
            <div class="card-icon">
                <img src="gambar/emina2.png" alt="">
            </div>
            <h3>Loyalty Member</h3>
            <p>Kelola poin pelanggan untuk meningkatkan transaksi berulang.</p>
        </div>
        <div class="card">
            <div class="card-icon">
                <img src="gambar/emina2.png" alt="">
            </div>
            <h3>Loyalty Member</h3>
            <p>Kelola poin pelanggan untuk meningkatkan transaksi berulang.</p>
        </div>
    </section>

    <script>
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.dot');
        let index = 0;
        let slideInterval;

        function showSlide(n) {
            // Hapus class active dari semua
            slides.forEach(s => s.classList.remove('active'));
            dots.forEach(d => d.classList.remove('active'));
            
            index = n;
            
            // Putar balik jika melebihi jumlah slide
            if (index >= slides.length) index = 0;
            if (index < 0) index = slides.length - 1;

            slides[index].classList.add('active');
            dots[index].classList.add('active');
        }

        function nextSlide() {
            index++;
            showSlide(index);
        }

        function manualSlide(n) {
            clearInterval(slideInterval); // Berhenti sementara jika diklik manual
            showSlide(n);
            startAutoSlide(); // Jalankan lagi
        }

        function startAutoSlide() {
            slideInterval = setInterval(nextSlide, 3000);
        }

        // Mulai jalankan
        startAutoSlide();
    </script>

</body>
</html>