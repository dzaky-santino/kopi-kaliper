<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:beranda.php');
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>profile</title>

   <link rel="icon" href="kopi_kaliper/logo.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="user-details">
   <div class="user">
      <?php
         
      ?>
      <img src="images/user-icon.png" alt="">
      <p><i class="fas fa-user"></i><span><span><?= $fetch_profile['nama']; ?></span></span></p>
      <p><i class="fas fa-phone"></i><span><?= $fetch_profile['no_hp']; ?></span></p>
      <p><i class="fas fa-envelope"></i><span><?= $fetch_profile['email']; ?></span></p>
      <a href="update_profile.php" class="btn">perbarui profile</a>
      <p class="address"><i class="fa-solid fa-barcode"></i><span><?php if($fetch_profile['no_meja'] == ''){echo 'Tolong Isi Nomor Meja Anda!';}else{echo $fetch_profile['no_meja'];} ?></span></p>
      <a href="update_meja.php" class="btn">perbarui nomor meja</a>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>