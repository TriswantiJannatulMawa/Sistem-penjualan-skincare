<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../includes/conn.php';



// VERIFIKASI PEMBAYARAN

if(isset($_GET['verifikasi'])){

    $id = $_GET['verifikasi'];

    mysqli_query($conn, "
        UPDATE transaksi
        SET status_pembayaran='Lunas'
        WHERE id_transaksi='$id'
    ");

    header("Location: pesanan.php");
    exit;
}



// SEARCH

$search = $_GET['search'] ?? '';

if($search != ''){

    $query = mysqli_query($conn, "
        SELECT 
            t.*, 
            p.nama, 
            p.no_hp 
        FROM transaksi t 
        JOIN pelanggan p 
        ON t.id_pelanggan = p.id_pelanggan 
        WHERE 
            p.nama LIKE '%$search%'
            OR CONCAT('TRX-00', t.id_transaksi) LIKE '%$search%'
            OR t.id_transaksi LIKE '%$search%'
            OR t.status_pembayaran LIKE '%$search%'
        ORDER BY t.id_transaksi DESC
    ");

}else{

    $query = mysqli_query($conn, "
        SELECT 
            t.*, 
            p.nama, 
            p.no_hp 
        FROM transaksi t 
        JOIN pelanggan p 
        ON t.id_pelanggan = p.id_pelanggan 
        ORDER BY t.id_transaksi DESC
    ");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Pesanan Masuk - MAARS Beauty</title>

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
}

.header-text h1{
    font-family:'Playfair Display',serif;
    font-size:28px;
    color:#1a0a12;
    margin-bottom:6px;
}

.header-text p{
    color:#8a6070;
    font-size:14px;
}

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

.card-box{
    background:#ffffff;
    border-radius:20px;
    padding:30px;
    box-shadow:0 4px 15px rgba(0,0,0,0.02);
}

.card-header{
    margin-bottom:20px;
}

.card-header h3{
    font-family:'Playfair Display',serif;
    font-size:18px;
    color:#1a0a12;
}

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
    padding:15px 10px;
    border-bottom:1px solid #f5f5f5;
    font-size:14px;
    color:#444;
    vertical-align:middle;
}

tbody tr:hover{
    background-color:#fcfcfc;
}

.badge-status{
    padding:6px 12px;
    border-radius:8px;
    font-size:12px;
    font-weight:600;
    display:inline-block;
}

.status-belum{
    background:#fff5e6;
    color:#f39c12;
}

.status-lunas{
    background:#eaffea;
    color:#2ecc71;
}

.status-batal{
    background:#fef2f2;
    color:#ef4444;
}

.btn-detail{
    padding:8px 12px;
    border-radius:8px;
    font-size:12px;
    font-weight:500;
    text-decoration:none;
    background:#fdf2f6;
    color:#ff4f81;
    transition:0.3s;
}

.btn-detail:hover{
    background:#ff4f81;
    color:white;
}

</style>
</head>

<body>

<?php include '../includes/sidebar_admin.php'; ?>

<div class="main">

    <div class="topbar-modern">

        <div class="header-text">
            <h1>Pesanan Masuk</h1>
            <p>Pantau semua transaksi pelanggan MAARS Beauty 📦</p>
        </div>

        <form method="GET" class="search-form">
            <input
                type="text"
                name="search"
                placeholder="Cari ID transaksi / nama pelanggan..."
                value="<?= $search; ?>">
            <button type="submit">🔍</button>
        </form>

    </div>

    <div class="card-box">

        <div class="card-header">
            <h3>Riwayat Transaksi</h3>
        </div>

        <table>
            <thead>
                <tr>
                    <th width="15%">ID Transaksi</th>
                    <th width="20%">Tanggal</th>
                    <th width="25%">Nama Pelanggan</th>
                    <th width="15%">Total Tagihan</th>
                    <th width="15%">Status</th>
                    <th width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody>

            <?php
            if(mysqli_num_rows($query) > 0){

                while($row = mysqli_fetch_assoc($query)){

                    $status_pembayaran = strtolower($row['status_pembayaran']);

                    if($status_pembayaran == 'lunas'){
                        $badge_class = 'status-lunas';
                    }elseif($status_pembayaran == 'batal' || $status_pembayaran == 'dibatalkan'){
                        $badge_class = 'status-batal';
                    }else{
                        $badge_class = 'status-belum';
                    }

                    echo "<tr>";

                    // ID TRANSAKSI
                    echo "
                    <td>
                        <strong style='color:#1a0a12;'>
                            TRX-00{$row['id_transaksi']}
                        </strong>
                    </td>
                    ";

                    // TANGGAL
                    $tanggal = !empty($row['tgl_transaksi'])
                    ? date('d M Y, H:i', strtotime($row['tgl_transaksi']))
                    : '-';

                    echo "<td>$tanggal</td>";

                    // NAMA PELANGGAN
                    echo "
                    <td>
                        <span style='display:block; font-weight:600; color:#1a0a12;'>
                            {$row['nama']}
                        </span>

                        <span style='font-size:12px; color:#888;'>
                            {$row['no_hp']}
                        </span>
                    </td>
                    ";

                    // TOTAL
                    echo "
                    <td style='font-weight:600; color:#ff4f81;'>
                        Rp " . number_format($row['total_harga'],0,',','.') . "
                    </td>
                    ";

                    // STATUS
                    echo "
                    <td>
                        <span class='badge-status {$badge_class}'>
                            {$row['status_pembayaran']}
                        </span>
                    </td>
                    ";

                    // AKSI
                    echo "<td>";

                    if($status_pembayaran != 'lunas'){

                        echo "
                        <a 
                            href='?verifikasi={$row['id_transaksi']}'
                            class='btn-detail'
                        >
                            Verifikasi
                        </a>
                        ";

                    }else{

                        echo "
                        <span style='color:#2ecc71; font-weight:600;'>
                            Sudah Lunas
                        </span>
                        ";
                    }

                    echo "</td>";

                    echo "</tr>";
                }

            }else{

                echo "
                <tr>
                    <td colspan='6' style='text-align:center; color:#888; padding:30px;'>
                        Data transaksi tidak ditemukan.
                    </td>
                </tr>
                ";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>