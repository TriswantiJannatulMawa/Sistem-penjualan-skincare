<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: ../login.php");
    exit;
}

include '../includes/conn.php';

$id_pelanggan = $_SESSION['id_pelanggan'];


// ==========================
// UPDATE PROFIL
// ==========================
if (isset($_POST['update'])) {

    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    // FOTO
    $foto = $_FILES['foto']['name'];
    $tmp  = $_FILES['foto']['tmp_name'];

    // Jika upload foto baru
    if(!empty($foto)){

        $nama_foto = time() . "_" . $foto;

        move_uploaded_file(
            $tmp,
            "../assets/gambar/profil/" . $nama_foto
        );

        $update = mysqli_query($conn, "
            UPDATE pelanggan 
            SET 
                nama='$nama',
                no_hp='$no_hp',
                alamat='$alamat',
                foto='$nama_foto'
            WHERE id_pelanggan='$id_pelanggan'
        ");

    } else {

        // Jika tidak upload foto
        $update = mysqli_query($conn, "
            UPDATE pelanggan 
            SET 
                nama='$nama',
                no_hp='$no_hp',
                alamat='$alamat'
            WHERE id_pelanggan='$id_pelanggan'
        ");
    }

    if($update){

        $sukses = "Profil berhasil diperbarui!";
    }
}


// ==========================
// AMBIL DATA PROFIL
// ==========================
$q_profil = mysqli_query($conn, "
    SELECT * FROM pelanggan
    WHERE id_pelanggan='$id_pelanggan'
");

$data = mysqli_fetch_assoc($q_profil);
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">

<title>Profil Saya</title>

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/global.css">
<link rel="stylesheet" href="../assets/css/sidebar.css">

<style>

body{
    font-family:'DM Sans',sans-serif;
    background:#fbf6f8;
    display:flex;
    
}

.main{
    flex:1;
    padding:40px;
    height:100vh;
    overflow-y:auto;
}

.header-text h1{
    font-family:'Playfair Display',serif;
    font-size:30px;
    color:#1a0a12;
    margin-bottom:6px;
}

.card-box{
    background:white;
    border-radius:20px;
    padding:35px;
    box-shadow:0 4px 15px rgba(0,0,0,0.03);
    max-width:700px;
    margin-top:20px;
}

.profile-photo{
    text-align:center;
    margin-bottom:30px;
}

.profile-photo img{
    width:130px;
    height:130px;
    border-radius:50%;
    object-fit:cover;
    border:5px solid #ffe4ee;
}

.profile-photo h3{
    margin-top:15px;
    color:#1a0a12;
}

.form-group{
    margin-bottom:18px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    color:#1a0a12;
    font-weight:600;
    font-size:13px;
}

.form-control{
    width:100%;
    padding:13px;
    border:1px solid #eee;
    border-radius:12px;
    font-family:'DM Sans',sans-serif;
    background:#fbf6f8;
    outline:none;
}

textarea.form-control{
    resize:vertical;
    height:90px;
}

.btn-submit{
    background:#ff4f81;
    color:white;
    border:none;
    padding:13px 25px;
    border-radius:12px;
    cursor:pointer;
    font-weight:600;
    margin-top:10px;
    width:100%;
}

.btn-submit:hover{
    background:#e63f75;
}

.alert-success{
    background:#eaffea;
    color:#2ecc71;
    padding:14px;
    border-radius:12px;
    margin-bottom:20px;
    font-size:13px;
}

.file-upload{
    background:#fbf6f8;
    padding:12px;
    border-radius:12px;
    border:1px solid #eee;
}

</style>

</head>

<body>

<?php include '../includes/sidebar_user.php'; ?>

<div class="main">

    <div class="header-text">
        <h1>Profil Saya</h1>
    </div>

    <div class="card-box">

        <?php if(isset($sukses)){ ?>

            <div class="alert-success">
                <?= $sukses; ?>
            </div>

        <?php } ?>


        <!-- FOTO PROFIL -->
        <div class="profile-photo">

            <?php
            if(!empty($data['foto'])){
            ?>

                <img src="../assets/gambar/profil/<?= $data['foto']; ?>">

            <?php
            } else {
            ?>

                <img src="../assets/gambar/mawa.webp">

            <?php } ?>

            <h3><?= $data['nama']; ?></h3>

        </div>


        <!-- FORM -->
        <form action="" method="POST" enctype="multipart/form-data">

            <div class="form-group">

                <label>Email</label>

                <input 
                    type="text"
                    class="form-control"
                    value="<?= $data['email']; ?>"
                    readonly
                    style="background:#eee; color:#888;"
                >

            </div>

            <div class="form-group">

                <label>Nama Lengkap</label>

                <input 
                    type="text"
                    name="nama"
                    class="form-control"
                    value="<?= $data['nama']; ?>"
                    required
                >

            </div>


            <div class="form-group">

                <label>Nomor HP</label>

                <input 
                    type="text"
                    name="no_hp"
                    class="form-control"
                    value="<?= $data['no_hp']; ?>"
                    required
                >

            </div>


            <div class="form-group">

                <label>Alamat Lengkap</label>

                <textarea 
                    name="alamat"
                    class="form-control"
                    required
                ><?= $data['alamat']; ?></textarea>

            </div>


            <button 
                type="submit"
                name="update"
                class="btn-submit"
            >
                Simpan Perubahan
            </button>

        </form>

    </div>

</div>

</body>
</html>