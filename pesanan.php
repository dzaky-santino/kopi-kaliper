<?php
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:beranda.php');
}

if(isset($_GET['success']) && $_GET['success'] == '1'){
   $delete_cart = $conn->prepare("DELETE FROM `keranjang` WHERE user_id = ?");
   $delete_cart->execute([$user_id]);
   $message[] = 'Pembayaran Berhasil';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>pesanan</title>

   <link rel="icon" href="kopi_kaliper/logo.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="heading">
   <h3>pesanan</h3>
   <p><a href="beranda.php">beranda</a> <span> / pesanan</span></p>
</div>

<section class="orders">
   <h1 class="title">pesanan anda</h1>
   <div class="box-container">

   <?php
      if($user_id == ''){
         echo '<p class="empty">silakan login untuk melihat pesanan Anda</p>';
      }else{
         $select_orders = $conn->prepare("SELECT * FROM `pesanan` WHERE user_id = ?");
         $select_orders->execute([$user_id]);
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
               $total_produk = json_decode($fetch_orders['total_produk'], true);
   ?>
   <div class="box">
      <p>Dipesan Pada : <span><?= date('d-m-Y', strtotime($fetch_orders['waktu_pesan'])); ?></span></p>
      <p>Nama : <span><?= $fetch_orders['nama']; ?></span></p>
      <p>Email : <span><?= $fetch_orders['email']; ?></span></p>
      <p>No. HP : <span><?= $fetch_orders['no_hp']; ?></span></p>
      <p>Nomor Meja : <span><?= $fetch_orders['no_meja']; ?></span></p>
      <p>Metode Pembayaran : <span><?= $fetch_orders['metode']; ?></span></p>
      <p>Pesanan : <span>
         <?php
         foreach ($total_produk as $item) {
            echo $item['nama'] . " (" . $item['harga'] . " x " . $item['kuantitas'] . ") - ";
         }
         ?>
      </span></p>
      <p>Total Harga : <span>Rp.<?= $fetch_orders['total_harga']; ?>/-</span></p>
      <p>Status Pembayaran : <span style="color:<?php if($fetch_orders['status_pembayaran'] == 'belum bayar'){ echo 'red'; }else{ echo 'green'; }; ?>"><?= $fetch_orders['status_pembayaran']; ?></span></p>

      <?php if($fetch_orders['metode'] == 'qris' && $fetch_orders['status_pembayaran'] == 'belum bayar'){ ?>
     
      <?php } ?>
   </div>
   <?php
            }
         }else{
            echo '<p class="empty">Belum Ada Pesanan!</p>';
         }
      }
   ?>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>

