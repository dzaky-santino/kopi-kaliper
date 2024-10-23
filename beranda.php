<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
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
   <title>beranda</title>

   <link rel="icon" href="kopi_kaliper/logo.png">
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>

<body>
   <?php include 'components/user_header.php'; ?>

   <section class="hero">
      <div class="swiper hero-slider">
         <div class="swiper-wrapper">
            <div class="swiper-slide slide">
               <div class="content">
                  <span>PESAN SEKARANG</span>
                  <h3>Kopi Kaliper</h3>
                  <a href="menu.php" class="btn">MENU</a>
               </div>
               <div class="image">
                  <img src="kopi_kaliper/kopi-rider.jpeg" alt="Kopi Rider">
               </div>
            </div>

            <div class="swiper-slide slide">
               <div class="content">
                  <span>PESAN SEKARANG</span>
                  <h3>Stop Cheese Burger</h3>
                  <a href="menu.php" class="btn">MENU</a>
               </div>
               <div class="image">
                  <img src="kopi_kaliper/stop-cheese-burger.jpeg" alt="Stop Cheese Burger">
               </div>
            </div>

            <div class="swiper-slide slide">
               <div class="content">
                  <span>PESAN SEKARANG</span>
                  <h3>Stop Burger</h3>
                  <a href="menu.php" class="btn">MENU</a>
               </div>
               <div class="image">
                  <img src="kopi_kaliper/stop-burger.jpeg" alt="Stop Burger">
               </div>
            </div>
         </div>
         <div class="swiper-pagination"></div>
      </div>
   </section>

   <section class="category">
      <h1 class="title">KATEGORI MENU</h1>
      <div class="box-container">
         <a href="kategori.php?kategori=Coffee" class="box">
            <img src="images/coffee.png" alt="">
            <h3>Coffee</h3>
         </a>

         <a href="kategori.php?kategori=Non-Coffee" class="box">
            <img src="images/non-coffee.png" alt="">
            <h3>Non-Coffee</h3>
         </a>

         <a href="kategori.php?kategori=Burgers" class="box">
            <img src="images/burgers.png" alt="">
            <h3>Burgers</h3>
         </a>

         <a href="kategori.php?kategori=Starters" class="box">
            <img src="images/starters.png" alt="">
            <h3>Starters</h3>
         </a>
      </div>
   </section>

   <section class="products">
      <h1 class="title">menu terlaris</h1>
      <div class="box-container">
         <?php
         $select_products = $conn->prepare("SELECT * FROM `produk` LIMIT 3");
         $select_products->execute();
         if ($select_products->rowCount() > 0) {
            while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
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
         } else {
            echo '<p class="empty">tidak ada produk yang ditambahkan!</p>';
         }
         ?>
      </div>

      <div class="more-btn">
         <a href="menu.php" class="btn">lihat semua</a>
      </div>
   </section>

   <?php include 'components/footer.php'; ?>

   <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
   <script src="js/script.js"></script>

   <script>
      var swiper = new Swiper(".hero-slider", {
         loop: true,
         grabCursor: true,
         effect: "flip",
         pagination: {
            el: ".swiper-pagination",
            clickable: true,
         },
      });
   </script>

</body>

</html>