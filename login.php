<?php
session_start();
include 'includes/conn.php';

if (isset($_POST['login'])) {
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $data  = mysqli_fetch_assoc($query);

    // MENGGUNAKAN MD5 UNTUK PENCOCOKAN PASSWORD
    if ($data && $data['password'] === md5($password)) {
        $_SESSION['user']         = $data;
        $_SESSION['role']         = $data['role'];
        $_SESSION['id_pelanggan'] = $data['id_pelanggan'];

        if ($data['role'] == 'admin') {
            header("Location: admin/dashboard_admin.php");
        } else {
            header("Location: pelanggan/dashboard.php");
        }
        exit;
    } else {
        $error = "Email atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — MAARS Beauty</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    :root {
      --pink-50:  #fff0f5;
      --pink-300: #ffaace;
      --pink-500: #ff4f81;
      --rose:     #ff7aa2;
      --dark:     #1a0a12;
      --muted:    #8a6070;
      --white:    #ffffff;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'DM Sans', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(145deg, #2d0a1a 0%, #1a0a12 60%, #0d0509 100%);
      padding: 20px;
      position: relative;
      overflow: hidden;
    }

    body::before {
      content: '';
      position: fixed;
      width: 600px; height: 600px;
      background: radial-gradient(circle, rgba(255,79,129,0.15) 0%, transparent 70%);
      top: -150px; left: -150px;
      border-radius: 50%;
      animation: pulse 7s ease-in-out infinite;
      pointer-events: none;
    }

    body::after {
      content: '';
      position: fixed;
      width: 500px; height: 500px;
      background: radial-gradient(circle, rgba(255,122,162,0.1) 0%, transparent 70%);
      bottom: -100px; right: -100px;
      border-radius: 50%;
      animation: pulse 7s ease-in-out infinite 3.5s;
      pointer-events: none;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); opacity: 1; }
      50%       { transform: scale(1.15); opacity: 0.6; }
    }

    .card {
      background: var(--white);
      border-radius: 28px;
      padding: 48px 44px;
      width: 100%;
      max-width: 420px;
      position: relative;
      z-index: 1;
      box-shadow: 0 30px 80px rgba(0,0,0,0.4);
      animation: fadeUp 0.5s ease both;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(24px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .card::before {
      content: '';
      position: absolute;
      top: 0; left: 50%;
      transform: translateX(-50%);
      width: 60px; height: 4px;
      background: linear-gradient(90deg, var(--rose), var(--pink-500));
      border-radius: 0 0 4px 4px;
    }

    .brand {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      margin-bottom: 32px;
    }

    .brand-dot {
      width: 9px; height: 9px;
      background: var(--rose);
      border-radius: 50%;
    }

    .brand-name {
      font-family: 'Playfair Display', serif;
      font-size: 18px;
      color: var(--dark);
      letter-spacing: 2px;
      text-transform: uppercase;
    }

    .form-header {
      text-align: center;
      margin-bottom: 28px;
    }

    .form-header h2 {
      font-family: 'Playfair Display', serif;
      font-size: 28px;
      color: var(--dark);
      margin-bottom: 6px;
    }

    .form-header p {
      font-size: 13px;
      color: var(--muted);
    }

    .error-msg {
      background: #fff0f3;
      border: 1px solid #ffb3c6;
      color: #c0143c;
      padding: 11px 14px;
      border-radius: 10px;
      font-size: 13px;
      margin-bottom: 18px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .input-group {
      margin-bottom: 16px;
    }

    .input-group label {
      display: block;
      font-size: 12px;
      font-weight: 500;
      color: var(--dark);
      margin-bottom: 7px;
      letter-spacing: 0.3px;
    }

    .input-wrap {
      position: relative;
    }

    .input-wrap input {
      width: 100%;
      padding: 13px 16px 13px 44px;
      border: 1.5px solid #f0dde5;
      border-radius: 12px;
      font-family: 'DM Sans', sans-serif;
      font-size: 14px;
      color: var(--dark);
      background: var(--pink-50);
      outline: none;
      transition: 0.25s;
    }

    .input-wrap input:focus {
      border-color: var(--rose);
      background: var(--white);
      box-shadow: 0 0 0 4px rgba(255,122,162,0.12);
    }

    .input-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 16px;
      color: var(--pink-300);
      pointer-events: none;
    }

    .btn-submit {
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, var(--rose), var(--pink-500));
      color: white;
      border: none;
      border-radius: 12px;
      font-family: 'DM Sans', sans-serif;
      font-size: 15px;
      font-weight: 500;
      cursor: pointer;
      margin-top: 8px;
      transition: 0.3s;
      box-shadow: 0 8px 24px rgba(255,79,129,0.3);
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 14px 32px rgba(255,79,129,0.4);
    }

    .btn-submit:active { transform: translateY(0); }

    .divider {
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 20px 0;
      color: #ccc;
      font-size: 12px;
    }

    .divider::before, .divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: #f0dde5;
    }

    .footer-link {
      text-align: center;
      font-size: 13px;
      color: var(--muted);
    }

    .footer-link a {
      color: var(--pink-500);
      font-weight: 500;
      text-decoration: none;
    }

    .footer-link a:hover { text-decoration: underline; }
  </style>
</head>
<body>

<div class="card">

  <div class="brand">
    <div class="brand-dot"></div>
    <span class="brand-name">MAARS Beauty</span>
  </div>

  <div class="form-header">
    <h2>Selamat Datang 👋</h2>
    <p>Masuk untuk melanjutkan ke akunmu</p>
  </div>

  <?php if (isset($error)): ?>
    <div class="error-msg">⚠️ <?= $error ?></div>
  <?php endif; ?>

  <form method="POST" action="">

    <div class="input-group">
      <label>Email</label>
      <div class="input-wrap">
        <span class="input-icon">✉️</span>
        <input type="email" name="email" placeholder="nama@email.com" required>
      </div>
    </div>

    <div class="input-group">
      <label>Password</label>
      <div class="input-wrap">
        <span class="input-icon">🔒</span>
        <input type="password" name="password" placeholder="Password kamu" required>
      </div>
    </div>

    <button type="submit" name="login" class="btn-submit">Masuk Sekarang</button>

  </form>

  <div class="divider">atau</div>

  <div class="footer-link">
    Belum punya akun? <a href="register.php">Daftar di sini</a>
  </div>

</div>

</body>
</html>