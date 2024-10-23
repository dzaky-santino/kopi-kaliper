<?php

include '../components/connect.php';

session_start();

if(isset($_POST['submit'])){

   $nama = $_POST['nama'];
   $nama = filter_var($nama);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass);

   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE nama = ? AND password = ?");
   $select_admin->execute([$nama, $pass]);
   
   if($select_admin->rowCount() > 0){
      $fetch_admin_id = $select_admin->fetch(PDO::FETCH_ASSOC);
      $_SESSION['admin_id'] = $fetch_admin_id['id'];
      header('location:beranda.php');
   }else{
      $message[] = 'Username Atau Kata Sandi Salah!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   <link rel="icon" href="../kopi_kaliper/logo.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

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

<section class="form-container">
   <form action="" method="POST">
      <h3>Login Admin</h3>
      <input type="text" name="nama" maxlength="20" required placeholder="masukkan username" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" maxlength="20" required placeholder="masukkan password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="masuk" name="submit" class="btn">
   </form>
</section>
</body>
</html>