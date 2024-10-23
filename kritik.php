<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['send'])){

   $nama = $_POST['nama'];
   $nama = filter_var($nama);
   $email = $_POST['email'];
   $email = filter_var($email);
   $no_hp = $_POST['no_hp'];
   $no_hp = filter_var($no_hp);
   $msg = $_POST['msg'];
   $msg = filter_var($msg);

   $select_message = $conn->prepare("SELECT * FROM `kritik` WHERE nama = ? AND email = ? AND no_hp = ? AND kritik = ?");
   $select_message->execute([$nama, $email, $no_hp, $msg]);

   if($select_message->rowCount() > 0){
      $message[] = 'Pesan Sudah Terkirim!';
   }else{

      $insert_message = $conn->prepare("INSERT INTO `kritik`(user_id, nama, email, no_hp, kritik) VALUES(?,?,?,?,?)");
      $insert_message->execute([$user_id, $nama, $email, $no_hp, $msg]);

      $message[] = 'Pesan Berhasil Dikirim!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>saran & kritik</title>

   <link rel="icon" href="kopi_kaliper/logo.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="heading">
   <h3>Saran dan Kritik</h3>
   <p><a href="beranda.php">beranda</a> <span> / saran & kritik</span></p>
</div>

<section class="contact">
   <div class="row">
      <div class="image">
         <img src="images/customer-service.png" alt="">
      </div>

      <form action="" method="post">
         <h3>berikan saran atau kritik anda!</h3>
         <input type="text" name="nama" maxlength="50" class="box" placeholder="Masukkan Nama" required>
         <input type="number" name="no_hp" class="box" placeholder="Masukkan No HP" required maxlength="12">
         <input type="email" name="email" maxlength="50" class="box" placeholder="Masukkan Email" required>
         <textarea name="msg" class="box" required placeholder="Masukkan Kritik atau Saran Anda" maxlength="500" cols="30" rows="10"></textarea>
         <input type="submit" value="kirim" name="send" class="btn">
      </form>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>