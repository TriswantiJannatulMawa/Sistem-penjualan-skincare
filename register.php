<?php
session_start();
include 'includes/conn.php';

if (isset($_POST['daftar'])) {
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $no_hp    = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $alamat   = mysqli_real_escape_string($conn, $_POST['alamat']);
    
    // MENGGUNAKAN MD5 UNTUK ENKRIPSI PASSWORD BARU
    $password = md5($_POST['password']);

    // Cek email duplikat
    $cek = mysqli_query($conn, "SELECT id_user FROM users WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "Email sudah terdaftar, gunakan email lain.";
    } else {
        mysqli_query($conn, "INSERT INTO pelanggan 
            (nama, email, no_hp, alamat, tanggal_daftar)
            VALUES ('$nama','$email','$no_hp','$alamat', NOW())");

        $id_pelanggan = mysqli_insert_id($conn);

        mysqli_query($conn, "INSERT INTO users 
            (id_pelanggan, email, password, role)
            VALUES ('$id_pelanggan','$email','$password','pelanggan')");

        header("Location: login.php?pesan=berhasil_daftar");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar — MAARS Beauty</title>
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
      padding: 24px 20px;
      position: relative;
      overflow-x: hidden;
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
      padding: 44px 44px;
      width: 100%;
      max-width: 480px;
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
      margin-bottom: 28px;
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
      margin-bottom: 24px;
    }

    .form-header h2 {
      font-family: 'Playfair Display', serif;
      font-size: 26px;
      color: var(--dark);
      margin-bottom: 6px;
    }

    .form-header p {
      font-size: 13px;
      color: var(--muted);
    }

    .form-header p a {
      color: var(--pink-500);
      font-weight: 500;
      text-decoration: none;
    }

    .form-header p a:hover { text-decoration: underline; }

    .error-msg {
      background: #fff0f3;
      border: 1px solid #ffb3c6;
      color: #c0143c;
      padding: 11px 14px;
      border-radius: 10px;
      font-size: 13px;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    /* 2 COLUMN GRID */
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 0 14px;
    }

    .input-group {
      margin-bottom: 14px;
    }

    .input-group.full {
      grid-column: 1 / -1;
    }

    .input-group label {
      display: block;
      font-size: 12px;
      font-weight: 500;
      color: var(--dark);
      margin-bottom: 6px;
      letter-spacing: 0.3px;
    }

    .input-wrap {
      position: relative;
    }

    .input-wrap input,
    .input-wrap textarea {
      width: 100%;
      padding: 12px 14px 12px 42px;
      border: 1.5px solid #f0dde5;
      border-radius: 10px;
      font-family: 'DM Sans', sans-serif;
      font-size: 13px;
      color: var(--dark);
      background: var(--pink-50);
      outline: none;
      transition: 0.25s;
      resize: none;
    }

    .input-wrap textarea {
      height: 72px;
      padding-top: 10px;
    }

    .input-wrap input:focus,
    .input-wrap textarea:focus {
      border-color: var(--rose);
      background: var(--white);
      box-shadow: 0 0 0 4px rgba(255,122,162,0.12);
    }

    .input-icon {
      position: absolute;
      left: 13px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 15px;
      color: var(--pink-300);
      pointer-events: none;
    }

    .input-wrap textarea ~ .input-icon {
      top: 13px;
      transform: none;
    }

    /* STRENGTH BAR */
    .strength-bar {
      height: 3px;
      background: #f0dde5;
      border-radius: 2px;
      margin-top: 6px;
      overflow: hidden;
    }

    .strength-fill {
      height: 100%;
      width: 0%;
      border-radius: 2px;
      transition: 0.3s;
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
      margin-top: 6px;
      transition: 0.3s;
      box-shadow: 0 8px 24px rgba(255,79,129,0.3);
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 14px 32px rgba(255,79,129,0.4);
    }

    .btn-submit:active { transform: translateY(0); }

    .footer-link {
      text-align: center;
      font-size: 13px;
      color: var(--muted);
      margin-top: 14px;
    }

    .footer-link a {
      color: var(--pink-500);
      font-weight: 500;
      text-decoration: none;
    }

    .footer-link a:hover { text-decoration: underline; }

    @media (max-width: 500px) {
      .card { padding: 36px 24px; }
      .form-grid { grid-template-columns: 1fr; }
      .input-group.full { grid-column: 1; }
    }
  </style>
</head>
<body>

<div class="card">

  <div class="brand">
    <div class="brand-dot"></div>
    <span class="brand-name">MAARS Beauty</span>
  </div>

  <div class="form-header">
    <h2>Buat Akun Baru ✨</h2>
    <p>Sudah punya akun? <a href="login.php">Masuk di sini</a></p>
  </div>

  <?php if (isset($error)): ?>
    <div class="error-msg">⚠️ <?= $error ?></div>
  <?php endif; ?>

  <form method="POST" action="register.php">

    <div class="form-grid">

      <div class="input-group full">
        <label>Nama Lengkap</label>
        <div class="input-wrap">
          <span class="input-icon">👤</span>
          <input type="text" name="nama" placeholder="Nama lengkap kamu" required>
        </div>
      </div>

      <div class="input-group">
        <label>Email</label>
        <div class="input-wrap">
          <span class="input-icon">✉️</span>
          <input type="email" name="email" placeholder="nama@email.com" required>
        </div>
      </div>

      <div class="input-group">
        <label>No. WhatsApp</label>
        <div class="input-wrap">
          <span class="input-icon">📱</span>
          <input type="text" name="no_hp" placeholder="0812xxxx" required>
        </div>
      </div>

      <div class="input-group full">
        <label>Alamat</label>
        <div class="input-wrap">
          <span class="input-icon">📍</span>
          <textarea name="alamat" placeholder="Alamat lengkap kamu..." required></textarea>
        </div>
      </div>

      <div class="input-group full">
        <label>Password</label>
        <div class="input-wrap">
          <span class="input-icon">🔒</span>
          <input type="password" name="password" id="password" placeholder="Min. 8 karakter" required>
        </div>
        <div class="strength-bar">
          <div class="strength-fill" id="strengthFill"></div>
        </div>
      </div>

    </div>

    <button type="submit" name="daftar" class="btn-submit">Daftar Sekarang</button>

  </form>

  <div class="footer-link">
    Sudah punya akun? <a href="login.php">Masuk di sini</a>
  </div>

</div>

<script>
  const pwd  = document.getElementById('password');
  const fill = document.getElementById('strengthFill');
  const colors = ['#ff4f81', '#ff7aa2', '#f9a825', '#2ecc71'];
  const widths  = ['25%', '50%', '75%', '100%'];

  pwd.addEventListener('input', () => {
    const v = pwd.value;
    let s = 0;
    if (v.length >= 8)        s++;
    if (/[A-Z]/.test(v))      s++;
    if (/[0-9]/.test(v))      s++;
    if (/[^A-Za-z0-9]/.test(v)) s++;

    fill.style.width      = s > 0 ? widths[s - 1] : '0%';
    fill.style.background = s > 0 ? colors[s - 1] : 'transparent';
  });
</script>

</body>
</html>