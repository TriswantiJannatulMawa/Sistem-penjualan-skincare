<?php
session_start();

// Cek apakah user sudah login dan rolenya admin
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
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

<title>Produk - MAARS Beauty</title>

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


/* TOPBAR */
.topbar-modern{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    margin-bottom:30px;
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


/* ACTION */
.header-actions{
    display:flex;
    align-items:center;
    gap:15px;
}


/* SEARCH BAR */
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


/* BUTTON TAMBAH */
.btn-add{
    background:#ff4f81;
    color:white;
    text-decoration:none;
    padding:14px 20px;
    border-radius:14px;
    font-size:14px;
    font-weight:600;
    transition:0.3s;
    white-space:nowrap;
}

.btn-add:hover{
    background:#e63e6d;
}


/* CARD */
.card-box{
    background:#ffffff;
    border-radius:22px;
    padding:30px;
    box-shadow:0 4px 15px rgba(0,0,0,0.03);
}

.card-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.card-header h3{
    font-family:'Playfair Display',serif;
    font-size:20px;
    color:#1a0a12;
}


/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
}

th{
    text-align:left;
    padding:15px 10px;
    color:#a08892;
    font-size:12px;
    text-transform:uppercase;
    letter-spacing:0.5px;
    border-bottom:2px solid #f0f0f0;
}

td{
    padding:16px 10px;
    border-bottom:1px solid #f5f5f5;
    font-size:14px;
    color:#444;
    vertical-align:middle;
}

tbody tr:hover{
    background:#fcfcfc;
}


/* IMAGE */
.product-img{
    width:55px;
    height:55px;
    border-radius:12px;
    object-fit:cover;
    border:1px solid #eee;
}


/* PRODUCT */
.product-name{
    font-weight:600;
    color:#1a0a12;
}


/* BADGE */
.badge-stok{
    background:#eaffea;
    color:#2ecc71;
    padding:7px 12px;
    border-radius:8px;
    font-size:12px;
    font-weight:600;
    display:inline-block;
}

.badge-stok.tipis{
    background:#fff5e6;
    color:#f39c12;
}


/* BUTTON AKSI */
.btn-action{
    padding:8px 12px;
    border-radius:8px;
    font-size:12px;
    font-weight:600;
    text-decoration:none;
    margin-right:5px;
    transition:0.3s;
}

.btn-edit{
    background:#fdf2f6;
    color:#ff4f81;
}

.btn-edit:hover{
    background:#ff4f81;
    color:white;
}

.btn-delete{
    background:#fef2f2;
    color:#ef4444;
}

.btn-delete:hover{
    background:#ef4444;
    color:white;
}


/* EMPTY */
.empty{
    text-align:center;
    padding:40px;
    color:#8a6070;
}

</style>

</head>

<body>

<?php include '../includes/sidebar_admin.php'; ?>

<div class="main">

    <div class="topbar-modern">

        <div class="header-text">

            <h1>Daftar Produk</h1>

            <p>
                Kelola katalog dan stok skincare MAARS Beauty 🛍️
            </p>

        </div>


        <div class="header-actions">

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


            <!-- BUTTON -->
            <a href="tambah_produk.php" class="btn-add">

                + Tambah Baru

            </a>

        </div>

    </div>



    <!-- TABLE -->
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
            if(mysqli_num_rows($query) > 0){

                $no = 1;

                while($row = mysqli_fetch_assoc($query)){

                    $stok_class = (
                        $row['stok'] > 5
                    ) ? 'badge-stok' : 'badge-stok tipis';
            ?>

                <tr>

                    <td><?= $no++; ?></td>

                    <td>

                        <img
                            src="../assets/gambar/<?= $row['gambar']; ?>"
                            class="product-img"
                        >

                    </td>

                    <td class="product-name">

                        <?= $row['nama_produk']; ?>

                    </td>

                    <td style="font-weight:600;">

                        Rp <?= number_format(
                            $row['harga'],
                            0,
                            ',',
                            '.'
                        ); ?>

                    </td>

                    <td>

                        <span class="<?= $stok_class; ?>">

                            <?= $row['stok']; ?> Unit

                        </span>

                    </td>

                    <td>

                        <a
                            href="edit_produk.php?id=<?= $row['id_produk']; ?>"
                            class="btn-action btn-edit"
                        >
                            Edit
                        </a>

                        <a
                            href="hapus_produk.php?id=<?= $row['id_produk']; ?>"
                            class="btn-action btn-delete"
                            onclick="return confirm('Yakin ingin menghapus produk ini?')"
                        >
                            Hapus
                        </a>

                    </td>

                </tr>

            <?php
                }
            }else{
            ?>

                <tr>

                    <td colspan="6" class="empty">

                        Produk tidak ditemukan 😢

                    </td>

                </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>