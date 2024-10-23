<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_message = $conn->prepare("DELETE FROM `kritik` WHERE id = ?");
   $delete_message->execute([$delete_id]);
   header('location:kritik.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>saran & kritik</title>

   <link rel="icon" href="../kopi_kaliper/logo.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<section class="messages">
   <h1 class="heading">Saran dan Kritik</h1>
   <div class="box-container">
      <?php
         $select_kritik = $conn->prepare("SELECT * FROM `kritik`");
         $select_kritik->execute();
         if($select_kritik->rowCount() > 0){
            while($fetch_kritik = $select_kritik->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="box">
         <p> nama : <span><?= $fetch_kritik['nama']; ?></span> </p>
         <p> nomor hp : <span><?= $fetch_kritik['no_hp']; ?></span> </p>
         <p> email : <span><?= $fetch_kritik['email']; ?></span> </p>
         <p> saran dan kritik : <span><?= $fetch_kritik['kritik']; ?></span> </p>
         <a href="kritik.php?delete=<?= $fetch_kritik['id']; ?>" class="delete-btn" onclick="return confirm('Hapus Pesan Ini?');">Hapus</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">Tidak Ada Saran dan Kritik</p>';
      }
      ?>
   </div>
</section>

<script src="../js/admin_script.js"></script>

</body>
</html>