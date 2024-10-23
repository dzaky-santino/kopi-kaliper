<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:beranda.php');
};

if(isset($_POST['delete'])){
   $keranjang_id = $_POST['keranjang_id'];
   $delete_cart_item = $conn->prepare("DELETE FROM `keranjang` WHERE id = ?");
   $delete_cart_item->execute([$keranjang_id]);
   $message[] = 'Pesanan Dihapus!';
}

if(isset($_POST['delete_all'])){
   $delete_cart_item = $conn->prepare("DELETE FROM `keranjang` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   $message[] = 'Semua Pesanan Dihapus!';
}

if(isset($_POST['update_kuantitas'])){
   $keranjang_id = $_POST['keranjang_id'];
   $kuantitas = $_POST['kuantitas'];
   $kuantitas = filter_var($kuantitas);
   $update_kuantitas = $conn->prepare("UPDATE `keranjang` SET kuantitas = ? WHERE id = ?");
   $update_kuantitas->execute([$kuantitas, $keranjang_id]);
   $message[] = 'Jumlah Pesanan Diperbarui';
}

$grand_total = 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>keranjang</title>

   <link rel="icon" href="kopi_kaliper/logo.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="heading">
   <h3>keranjang</h3>
   <p><a href="beranda.php">beranda</a> <span> / keranjang</span></p>
</div>


<section class="products">
   <h1 class="title">pesanan anda</h1>
   <div class="box-container">
      <?php
         $grand_total = 0;
         $select_cart = $conn->prepare("SELECT * FROM `keranjang` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
      ?>
      <form action="" method="post" class="box">
         <input type="hidden" name="keranjang_id" value="<?= $fetch_cart['id']; ?>">
         <button type="submit" class="fas fa-times" name="delete" onclick="return confirm('hapus produk ini?');"></button>
         <img src="uploaded_img/<?= $fetch_cart['gambar']; ?>" alt="">
         <div class="name"><?= $fetch_cart['nama']; ?></div>
         <div class="flex">
            <div class="price"><span>Rp.</span><?= $fetch_cart['harga']; ?></div>
            <input type="number" name="kuantitas" class="qty" min="1" max="99" value="<?= $fetch_cart['kuantitas']; ?>" maxlength="2">
            <button type="submit" class="fas fa-edit" name="update_kuantitas"></button>
         </div>
         <div class="sub-total"> subtotal : <span>Rp.<?= $sub_total = ($fetch_cart['harga'] * $fetch_cart['kuantitas']); ?>/-</span> </div>
      </form>
      <?php
               $grand_total += $sub_total;
            }
         }else{
            echo '<p class="empty">tidak ada pesanan!</p>';
         }
      ?>
   </div>

   <div class="cart-total">
      <p>Total Pesanan : <span>Rp.<?= $grand_total; ?></span></p>
      <a href="checkout.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">lanjutkan pembayaran</a>
   </div>

   <div class="more-btn">
      <form action="" method="post">
         <button type="submit" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>" name="delete_all" onclick="return confirm('hapus semua pesanan?');">hapus semua</button>
      </form>
      <a href="menu.php" class="btn">lanjut belanja</a>
   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>