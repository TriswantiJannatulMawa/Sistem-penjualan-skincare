<?php
session_start();
include 'conn.php';

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);

    // Query untuk mencocokkan email dan password
    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        // Simpan data user ke session
        $_SESSION['user'] = $data;
        $_SESSION['role'] = $data['role'];
        $_SESSION['id_pelanggan'] = $data['id_pelanggan'];
         // Simpan role ke session agar mudah dicek di halaman lain

        // Logika pengalihan berdasarkan role
        if ($data['role'] == 'admin') {
            header("Location: admin/dashboard_admin.php"); 
            exit;
        } elseif ($data['role'] == 'pelanggan') {
            header("Location: pelanggan/dashboard.php");
            exit;
        } else {
            // Jika ada role lain atau role tidak terdefinisi
            header("Location: index.php");
            exit;
        }
    } else {
        $error = "Email atau Password salah! Periksa kembali.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BeautyFlow</title>
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

        <div class="login-container">
            <h2>Halo, <span>Beauty care!</span></h2>
            <p>Ayo masuk dan pantau bisnismu biar makin <span></span></p>
    
            <?php if (isset($error)) { echo "<div class='error-msg'>$error</div>"; } ?>
    
            <form method="POST" action=" ">
                <input type="email" name="email" placeholder="Email Terdaftar" required>
                <input type="password" name="password" placeholder="Password Rahasia" required>
                <button type="submit" name="login">Login</button>
            </form>
        
            <div class="footer-text">
                Belum punya akun? <a href="register.php">Daftar Sekarang disini!</a>
            </div>
        </div>
    </div>

</body>
</html>