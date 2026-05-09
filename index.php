<?php
session_start();
include 'includes/conn.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>MAARS Beauty</title>
<style>
    /* RESET */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background: #fff0f5;
    color: #333;
}

/* NAVBAR */
nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 50px;
    background: #fff;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    position: sticky;
    top: 0;
    z-index: 1000;
    
}

.logo {
    display: flex;
    align-items: center;
    font-weight: bold;
    font-size: 35px;
    gap: 10px;
    color: #ff4f81;
}


.nav-links a {
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 8px;
    background: #ff7aa2;
    color: white;
    font-weight: bold;
    transition: 0.3s;
}

.nav-links a:hover {
    background: #ff4f81;
}

/* HERO */
.hero {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 60px 80px;
    gap: 40px;
}

.hero-content {
    max-width: 500px;
}

.hero-content h1 {
    font-size: 45px;
    color: #222;
    margin-bottom: 15px;
}

.hero-content p {
    font-size: 20px;
    color: #666;
}

/* SLIDER */
.hero-image-container {
    position: relative;
    width: 600px;
    height: 320px;
}

.slide {
    width: 100%;
    height: 320px;
    object-fit: cover;
    border-radius: 20px;
    position: absolute;
    opacity: 0;
    transition: 0.5s ease-in-out;
}

.slide.active {
    opacity: 1;
}

/* DOTS */
.dots {
    position: absolute;
    bottom: 15px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 8px;
}

.dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #ccc;
    cursor: pointer;
    transition: 0.3s;
}

.dot.active {
    background: #ff4f81;
}

/* FEATURES */
.features {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 25px;
    padding: 60px 80px;
}

/* CARD */
.card {
    background: white;
    padding: 25px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 10px 25px rgba(0,0,0,0.06);
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-8px);
}

.card img {
    width: 90px;
    margin-bottom: 10px;
}

.card h3 {
    margin-bottom: 10px;
    color: #ff4f81;
}

.card p {
    font-size: 14px;
    color: #666;
}

/* RESPONSIVE */
@media (max-width: 900px) {

    .hero {
        flex-direction: column;
        text-align: center;
        padding: 40px 20px;
    }

    .hero-image-container {
        width: 100%;
        height: 250px;
    }

    .features {
        padding: 40px 20px;
    }

    nav {
        padding: 15px 20px;
    }
}
</style>
</head>

<body>

<!-- NAVBAR -->
<nav>
    <div class="logo">
        MAARS Beauty
    </div>

    <div class="nav-links">
        <a href="login.php" class="btn-login">Masuk</a>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-content">
        <h1>Kulit sehat, percaya diri setiap hari ✨</h1>
        <p>Dapatkan produk skincare terbaik untuk kulitmu</p>
    </div>

    <div class="hero-image-container">
        <img src="assets/gambar/scincare.jpeg" class="slide active">
        <img src="assets/gambar/g2g.jpg" class="slide">
        <img src="assets/gambar/wardah.png" class="slide">

        <div class="dots">
            <span class="dot active" onclick="manualSlide(0)"></span>
            <span class="dot" onclick="manualSlide(1)"></span>
            <span class="dot" onclick="manualSlide(2)"></span>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="features">

    <div class="card">
        <img src="assets/gambar/wardah2.png">
        <h3>Wardah</h3>
        <p>Produk skincare halal dengan kualitas terbaik untuk perawatan wajah.</p>
    </div>

    <div class="card">
        <img src="assets/gambar/skintific2.png">
        <h3>Skintific</h3>
        <p>Skincare modern dengan teknologi untuk memperbaiki skin barrier.</p>
    </div>

    <div class="card">
        <img src="assets/gambar/g2g1.png">
        <h3>Glad2Glow</h3>
        <p>skincare viral asal China yang diformulasikan untuk kulit remaja dan pemula</p>
    </div>

    <div class="card">
        <img src="assets/gambar/b-erl.png">
        <h3>B ERL</h3>
        <p>Produk pencerah serta perawatan kulit aman, halal, dan BPOM.</p>
    </div>

</section>

<!-- SLIDER SCRIPT -->
<script>
const slides = document.querySelectorAll('.slide');
const dots = document.querySelectorAll('.dot');

let index = 0;
let interval;

function showSlide(n){
    slides.forEach(s => s.classList.remove('active'));
    dots.forEach(d => d.classList.remove('active'));

    index = n;

    if(index >= slides.length) index = 0;
    if(index < 0) index = slides.length - 1;

    slides[index].classList.add('active');
    dots[index].classList.add('active');
}

function nextSlide(){
    showSlide(index + 1);
}

function manualSlide(n){
    clearInterval(interval);
    showSlide(n);
    startAuto();
}

function startAuto(){
    interval = setInterval(nextSlide, 3000);
}

startAuto();
</script>

</body>
</html>