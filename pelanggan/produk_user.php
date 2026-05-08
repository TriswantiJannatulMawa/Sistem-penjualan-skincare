<?php
session_start();

// Proteksi halaman pelanggan
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: ../login.php");
    exit;
}

include '../includes/conn.php';


// ======================================
// SEARCH PRODUK
// ======================================
$search = $_GET['search'] ?? '';

if($search != ''){

    $query = mysqli_query($conn, "
        SELECT *
        FROM produk
        WHERE nama_produk LIKE '%$search%'
        ORDER BY id_produk DESC
    ");

}else{

    $query = mysqli_query($conn, "
        SELECT *
        FROM produk
        ORDER BY id_produk DESC
    ");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Katalog Produk - MAARS Beauty</title>

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/global.css">
<link rel="stylesheet" href="../assets/css/sidebar.css">

<style>

body{
    font-family:'DM Sans',sans-serif;
    background-color:#fbf6f8;
    display:flex;
}

.main{
    flex:1;
    padding:40px;
    height:100vh;
    overflow-y:auto;
}

.topbar-modern{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:35px;
    gap:20px;
}

.header-text h1{
    font-family:'Playfair Display',serif;
    font-size:30px;
    color:#1a0a12;
    margin-bottom:6px;
}

.header-text p{
    color:#8a6070;
    font-size:14px;
}


/* SEARCH */
.search-form{
    display:flex;
    align-items:center;
    background:white;
    border-radius:18px;
    padding:6px;
    width:420px;
    box-shadow:0 4px 15px rgba(0,0,0,0.04);
}

.search-form input{
    flex:1;
    border:none;
    outline:none;
    padding:14px 18px;
    font-size:14px;
    background:transparent;
    font-family:'DM Sans',sans-serif;
    color:#1a0a12;
}

.search-form button{
    width:50px;
    height:50px;
    border:none;
    border-radius:14px;
    background:#ff4f81;
    color:white;
    font-size:18px;
    cursor:pointer;
    transition:0.3s;
}

.search-form button:hover{
    background:#e63e6d;
    transform:scale(1.05);
}

/* GRID PRODUK */
.product-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(240px,1fr));
    gap:25px;
}

.product-card{
    background:white;
    border-radius:22px;
    padding:15px;
    box-shadow:0 4px 15px rgba(0,0,0,0.03);
    border:1px solid rgba(240,221,229,0.4);
    display:flex;
    flex-direction:column;
    transition:0.3s;
}

.product-card:hover{
    transform:translateY(-5px);
}

.product-card img{
    width:100%;
    height:220px;
    object-fit:cover;
    border-radius:16px;
    margin-bottom:15px;
}

.product-card h4{
    font-size:17px;
    color:#1a0a12;
    margin-bottom:8px;
    font-weight:600;
}

.price{
    color:#ff4f81;
    font-weight:700;
    font-size:17px;
    margin-bottom:15px;
}


/* BUTTON */
.action-buttons{
    display:flex;
    gap:12px;
    margin-top:auto;
}

.btn-beli,
.btn-keranjang{
    flex:1;
    padding:12px;
    border-radius:12px;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
    border:none;
    font-family:'DM Sans',sans-serif;
}

.btn-beli{
    background:#ff4f81;
    color:white;
    box-shadow:0 4px 10px rgba(255,79,129,0.25);
}

.btn-beli:hover{
    background:#e63e6d;
    transform:translateY(-2px);
}

.btn-keranjang{
    background:white;
    color:#ff4f81;
    border:1.5px solid #ff4f81;
}

.btn-keranjang:hover{
    background:#fff0f5;
    transform:translateY(-2px);
}


/* EMPTY */
.empty-state{
    grid-column:1/-1;
    text-align:center;
    padding:60px 20px;
    color:#8a6070;
    background:white;
    border-radius:20px;
}

</style>

</head>

<body>

<?php include '../includes/sidebar_user.php'; ?>

<div class="main">

    <div class="topbar-modern">

        <div class="header-text">

            <h1>Produk Skincare</h1>

            <p>
                Pilih produk terbaik untuk kulit cantikmu ✨
            </p>

        </div>


        <!-- SEARCH -->
        <form method="GET" class="search-form">

          <input
              type="text"
              name="search"
              placeholder="Cari produk skincare..."
              value="<?= $_GET['search'] ?? ''; ?>"
          >

          <button type="submit">
              🔍
          </button>

        </form>

    </div>



    <!-- PRODUK -->
    <div class="product-grid">

        <?php if(mysqli_num_rows($query) > 0): ?>

            <?php while($row = mysqli_fetch_assoc($query)): ?>

            <div class="product-card">

                <img 
                    src="../assets/gambar/<?= $row['gambar']; ?>"
                    alt="<?= $row['nama_produk']; ?>"
                >

                <h4>
                    <?= $row['nama_produk']; ?>
                </h4>

                <p class="price">

                    Rp <?= number_format(
                        $row['harga'],
                        0,
                        ',',
                        '.'
                    ); ?>

                </p>


                <div class="action-buttons">

                    <!-- BELI -->
                    <form action="check_out.php" method="POST">

                        <input
                            type="hidden"
                            name="id_produk"
                            value="<?= $row['id_produk']; ?>"
                        >

                        <button type="submit" class="btn-beli">
                            ✨ Beli
                        </button>

                    </form>


                    <!-- KERANJANG -->
                    <form action="tambah_keranjang.php" method="POST">

                        <input
                            type="hidden"
                            name="id_produk"
                            value="<?= $row['id_produk']; ?>"
                        >

                        <button type="submit" class="btn-keranjang">
                            🛒 Keranjang
                        </button>

                    </form>

                </div>

            </div>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="empty-state">

                <h3>
                    Produk tidak ditemukan 😢
                </h3>

                <p>
                    Coba cari dengan kata kunci lain
                </p>

            </div>

        <?php endif; ?>

    </div>

</div>

</body>
</html>