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

      <a href="beranda.php" class="logo">Admin<span>Panel</span></a>

      <nav class="navbar">
         <a href="beranda.php">beranda</a>
         <a href="produk.php">produk</a>
         <a href="pesanan.php">pesanan</a>
         <a href="admin_accounts.php">admins</a>
         <a href="users_accounts.php">users</a>
         <a href="kritik.php">saran dan kritik</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p><?= $fetch_profile['nama']; ?></p>
         <a href="update_profile.php" class="btn">perbarui profile</a>
         <a href="../components/admin_logout.php" onclick="return confirm('keluar dari website?');" class="delete-btn">keluar</a>
      </div>

   </section>

</header>