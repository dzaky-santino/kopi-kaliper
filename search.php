<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/add_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>pencarian</title>

   <link rel="icon" href="kopi_kaliper/logo.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="search-form">
   <form method="post" action="">
      <input type="text" name="search_box" placeholder="cari disini..." class="box">
      <button type="submit" name="search_btn" class="fas fa-search"></button>
   </form>
</section>

<section class="products" style="min-height: 100vh; padding-top:0;">

<div class="box-container">
      <?php
         if(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
         $search_box = $_POST['search_box'];
         $select_products = $conn->prepare("SELECT * FROM `produk` WHERE nama LIKE '%{$search_box}%'");
         $select_products->execute();
         if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
      ?>
      <form action="" method="post" class="box">
         <input type="hidden" name="produk_id" value="<?= $fetch_products['id']; ?>">
         <input type="hidden" name="nama" value="<?= $fetch_products['nama']; ?>">
         <input type="hidden" name="harga" value="<?= $fetch_products['harga']; ?>">
         <input type="hidden" name="gambar" value="<?= $fetch_products['gambar']; ?>">
   
         <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
         <img src="uploaded_img/<?= $fetch_products['gambar']; ?>" alt="">
         <a href="kategori.php?kategori=<?= $fetch_products['kategori']; ?>" class="cat"><?= $fetch_products['kategori']; ?></a>
         <div class="name"><?= $fetch_products['nama']; ?></div>
         <div class="flex">
            <div class="price"><span>Rp.</span><?= $fetch_products['harga']; ?></div>
            <input type="number" name="kuantitas" class="qty" min="1" max="99" value="1" maxlength="2">
         </div>
      </form>
      <?php
            }
         }else{
            echo '<p class="empty">Tidak Ada Produk Tersebut!</p>';
         }
      }
      ?>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>