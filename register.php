<?php
include 'conn.php';

if (isset($_POST['daftar'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat'];
    $password = md5($_POST['password']);

    // 1. simpan ke pelanggan
    mysqli_query($conn, "INSERT INTO pelanggan 
    (nama,email,no_hp,alamat,tanggal_daftar)
    VALUES ('$nama','$email','$no_hp','$alamat',NOW())");

    $id_pelanggan = mysqli_insert_id($conn);

    // 2. simpan ke users
    mysqli_query($conn, "INSERT INTO users 
    (id_pelanggan,email,password,role)
    VALUES ('$id_pelanggan','$email','$password','pelanggan')");

    header("Location: login.php?pesan=berhasil_daftar");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar MAARS Beauty</title>
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
    </nav>
    <div class="container">

        <div class="register-container">
           66666666666666666666666666666666666666666666
            <form method="POST" action="login.php">
                <div class="input-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" placeholder="Contoh: Cantika Skincare" required>
                </div>
        
                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="email@bisnis.com" required>
                </div>
        
                <div class="input-group">
                    <label>WhatsApp </label>
                    <input type="text" name="no_hp" placeholder="0812xxxx" required>
                </div>
        
                <div class="input-group">
                    <label>Alamat</label>
                    <textarea name="alamat" placeholder="Jl. Kecantikan No. 1..." required></textarea>
                </div>
        
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Min. 8 Karakter" required>
                </div>
        
                <button type="submit" name="daftar">Daftar</button>
            </form>
        </div>
    </div>

</body>
</html>