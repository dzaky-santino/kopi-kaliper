<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">

   <section class="flex">

      <a href="beranda.php" class="logo">KOPI KALIPER</a>

      <nav class="navbar">
         <a href="beranda.php">beranda</a>
         <a href="tentang_kami.php">tentang</a>
         <a href="menu.php">menu</a>
         <a href="pesanan.php">pesanan</a>
         <a href="kritik.php">saran & kritik</a>
      </nav>

      <div class="icons">
         <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `keranjang` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_items = $count_cart_items->rowCount();
         ?>
         <a href="search.php"><i class="fas fa-search"></i></a>
         <a href="keranjang.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_items; ?>)</span></a>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="menu-btn" class="fas fa-bars"></div>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p class="name"><?= $fetch_profile['nama']; ?></p>
         <div class="flex">
            <a href="profile.php" class="btn">profile</a>
            <a href="components/user_logout.php" onclick="return confirm('Keluar Dari Web Ini?');" class="delete-btn">Keluar</a>
         </div>
         
         <?php
            }else{
         ?>
            <p class="name">Silahkan Masuk Akun!</p>
            <a href="login.php" class="btn">masuk</a>
         <?php
          }
         ?>
      </div>

   </section>

</header>

