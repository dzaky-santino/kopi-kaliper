<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>beranda</title>

   <link rel="icon" href="../kopi_kaliper/logo.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<section class="dashboard">
   <h1 class="heading">beranda</h1>
   <div class="box-container">
   <div class="box">
      <h3>Selamat Datang!</h3>
      <p><?= $fetch_profile['nama']; ?></p>
      <a href="update_profile.php" class="btn">perbarui profile</a>
   </div>

   <div class="box">
      <?php
         $total_pendings = 0;
         $select_pendings = $conn->prepare("SELECT * FROM `pesanan` WHERE status_pembayaran = ?");
         $select_pendings->execute(['belum bayar']);
         while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
            $total_pendings += $fetch_pendings['total_harga'];
         }
      ?>
      <h3><span>Rp.</span><?= $total_pendings; ?><span>/-</span></h3>
      <p>Total Belum bayar</p>
      <a href="pesanan.php" class="btn">lihat pesanan</a>
   </div>

   <div class="box">
      <?php
         $total_completes = 0;
         $select_completes = $conn->prepare("SELECT * FROM `pesanan` WHERE status_pembayaran = ?");
         $select_completes->execute(['sudah bayar']);
         while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
            $total_completes += $fetch_completes['total_harga'];
         }
      ?>
      <h3><span>Rp.</span><?= $total_completes; ?><span>/-</span></h3>
      <p>Total Sudah Bayar</p>
      <a href="pesanan.php" class="btn">lihat pesanan</a>
   </div>

   <div class="box">
      <?php
         $select_orders = $conn->prepare("SELECT * FROM `pesanan`");
         $select_orders->execute();
         $numbers_of_orders = $select_orders->rowCount();
      ?>
      <h3><?= $numbers_of_orders; ?></h3>
      <p>Total Pesanan</p>
      <a href="pesanan.php" class="btn">lihat pesanan</a>
   </div>

   <div class="box">
      <?php
         $select_products = $conn->prepare("SELECT * FROM `produk`");
         $select_products->execute();
         $numbers_of_products = $select_products->rowCount();
      ?>
      <h3><?= $numbers_of_products; ?></h3>
      <p>Produk Ditambahkan</p>
      <a href="produk.php" class="btn">lihat produk</a>
   </div>

   <div class="box">
      <?php
         $select_users = $conn->prepare("SELECT * FROM `users`");
         $select_users->execute();
         $numbers_of_users = $select_users->rowCount();
      ?>
      <h3><?= $numbers_of_users; ?></h3>
      <p>Akun User</p>
      <a href="users_accounts.php" class="btn">Lihat User</a>
   </div>

   <div class="box">
      <?php
         $select_admins = $conn->prepare("SELECT * FROM `admin`");
         $select_admins->execute();
         $numbers_of_admins = $select_admins->rowCount();
      ?>
      <h3><?= $numbers_of_admins; ?></h3>
      <p>Akun Admin</p>
      <a href="admin_accounts.php" class="btn">lihat admin</a>
   </div>

   <div class="box">
      <?php
         $select_kritik = $conn->prepare("SELECT * FROM `kritik`");
         $select_kritik->execute();
         $numbers_of_kritik = $select_kritik->rowCount();
      ?>
      <h3><?= $numbers_of_kritik; ?></h3>
      <p>Saran dan Kritik</p>
      <a href="kritik.php" class="btn">lihat</a>
   </div>

   </div>

</section>

<script src="../js/admin_script.js"></script>

</body>
</html>