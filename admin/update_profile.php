<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['submit'])){

   $nama = $_POST['nama'];
   $nama = filter_var($nama);

   if(!empty($nama)){
      $select_name = $conn->prepare("SELECT * FROM `admin` WHERE nama = ?");
      $select_name->execute([$nama]);
      if($select_name->rowCount() > 0){
         $message[] = 'username sudah ada!';
      }else{
         $update_name = $conn->prepare("UPDATE `admin` SET nama = ? WHERE id = ?");
         $update_name->execute([$nama, $admin_id]);
      }
   }

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $select_old_pass = $conn->prepare("SELECT password FROM `admin` WHERE id = ?");
   $select_old_pass->execute([$admin_id]);
   $fetch_prev_pass = $select_old_pass->fetch(PDO::FETCH_ASSOC);
   $prev_pass = $fetch_prev_pass['password'];
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass);
   $confirm_pass = sha1($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass);

   if($old_pass != $empty_pass){
      if($old_pass != $prev_pass){
         $message[] = 'kata sandi lama tidak cocok!';
      }elseif($new_pass != $confirm_pass){
         $message[] = 'konfirmasi kata sandi tidak cocok!';
      }else{
         if($new_pass != $empty_pass){
            $update_pass = $conn->prepare("UPDATE `admin` SET password = ? WHERE id = ?");
            $update_pass->execute([$confirm_pass, $admin_id]);
            $message[] = 'kata sandi berhasil diperbarui!';
         }else{
            $message[] = 'silahkan masukkan kata sandi baru!';
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
   <title>profile</title>

   <link rel="icon" href="../kopi_kaliper/logo.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<section class="form-container">
   <form action="" method="POST">
      <h3>Profile Admin</h3>
      <input type="text" name="nama" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')" placeholder="<?= $fetch_profile['nama']; ?>">
      <input type="password" name="old_pass" maxlength="20" placeholder="Masukkan Kata Sandi Lama" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="new_pass" maxlength="20" placeholder="Masukkan Kata Sandi Baru" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="confirm_pass" maxlength="20" placeholder="Konfirmasi Kata Sandi Baru" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="perbarui" name="submit" class="btn">
   </form>
</section>

<script src="../js/admin_script.js"></script>

</body>
</html>