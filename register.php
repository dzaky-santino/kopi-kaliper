<?php
include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $nama = $_POST['nama'];
   $nama = filter_var($nama);
   $email = $_POST['email'];
   $email = filter_var($email);
   $no_hp = $_POST['no_hp'];
   $no_hp = filter_var($no_hp);
   $no_meja = $_POST['no_meja']; 
   $no_meja = filter_var($no_meja);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? OR no_hp = ?");
   $select_user->execute([$email, $no_hp]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $message[] = 'Email Atau Nomor HP Sudah Ada!';
   }else{
      if($pass != $cpass){
         $message[] = 'Konfirmasi Kata Sandi Tidak Cocok!';
      }else{
         $insert_user = $conn->prepare("INSERT INTO `users`(nama, email, no_hp, no_meja, password) VALUES(?,?,?,?,?)");
         $insert_user->execute([$nama, $email, $no_hp, $no_meja, $cpass]);
         $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
         $select_user->execute([$email, $pass]);
         $row = $select_user->fetch(PDO::FETCH_ASSOC);
         if($select_user->rowCount() > 0){
            $_SESSION['user_id'] = $row['id'];
            header('location:beranda.php');
         }
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>daftar</title>

   <link rel="icon" href="kopi_kaliper/logo.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="form-container">
   <form action="" method="post">
      <h3>daftar sekarang</h3>
      <input type="text" name="nama" required placeholder="Masukkan Nama" class="box" maxlength="50">
      <input type="email" name="email" required placeholder="Masukkan Email" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="number" name="no_hp" required placeholder="Masukkan Nomor HP" class="box" min="0"  maxlength="13">
      <input type="number" name="no_meja" required placeholder="Masukkan Nomor Meja" class="box" maxlength="3">
      <input type="password" name="pass" required placeholder="Masukkan Kata Sandi" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="Konfirmasi Kata Sandi" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="daftar sekarang" name="submit" class="btn">
      <p>sudah punya akun? <a href="login.php">masuk</a></p>
   </form>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>