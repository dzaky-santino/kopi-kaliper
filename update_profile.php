<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:beranda.php');
};

if(isset($_POST['submit'])){

   $nama = $_POST['nama'];
   $nama = filter_var($nama);

   $email = $_POST['email'];
   $email = filter_var($email);
   $no_hp = $_POST['no_hp'];
   $no_hp = filter_var($no_hp);

   if(!empty($nama)){
      $update_nama = $conn->prepare("UPDATE `users` SET nama = ? WHERE id = ?");
      $update_nama->execute([$nama, $user_id]);
      $message[] = 'Profile Berhasil Diperbarui!';
   }

   if(!empty($email)){
      $select_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
      $select_email->execute([$email]);
      if($select_email->rowCount() > 0){
         $message[] = 'email sudah ada!';
      }else{
         $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE id = ?");
         $update_email->execute([$email, $user_id]);
         $message[] = 'Profile Berhasil Diperbarui!';
      }
   }

   if(!empty($no_hp)){
      $select_no_hp = $conn->prepare("SELECT * FROM `users` WHERE no_hp = ?");
      $select_no_hp->execute([$no_hp]);
      if($select_no_hp->rowCount() > 0){
         $message[] = 'nomor HP suda ada!';
      }else{
         $update_no_hp = $conn->prepare("UPDATE `users` SET no_hp = ? WHERE id = ?");
         $update_no_hp->execute([$no_hp, $user_id]);
         $message[] = 'Profile Berhasil Diperbarui!';
      }
   }
   
   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $select_prev_pass = $conn->prepare("SELECT password FROM `users` WHERE id = ?");
   $select_prev_pass->execute([$user_id]);
   $fetch_prev_pass = $select_prev_pass->fetch(PDO::FETCH_ASSOC);
   $prev_pass = $fetch_prev_pass['password'];
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass);
   $confirm_pass = sha1($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass);

   if($old_pass != $empty_pass){
      if($old_pass != $prev_pass){
         $message[] = 'password lama tidak cocok!';
      }elseif($new_pass != $confirm_pass){
         $message[] = 'konfirmasi password tidak cocok!';
      }else{
         if($new_pass != $empty_pass){
            $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
            $update_pass->execute([$confirm_pass, $user_id]);
            $message[] = 'password berhasil diperbarui!';
         }else{
            $message[] = 'silahkan masukkan passowrd baru!';
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
   <title>update profile</title>

   <link rel="icon" href="kopi_kaliper/logo.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="form-container update-form">
   <form action="" method="post">
      <h3>perbarui profile</h3>
      <input type="text" name="nama" placeholder="<?= $fetch_profile['nama']; ?>" class="box" maxlength="50">
      <input type="email" name="email" placeholder="<?= $fetch_profile['email']; ?>" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="number" name="no_hp" placeholder="<?= $fetch_profile['no_hp']; ?>"" class="box" min="0" max="9999999999999" maxlength="13">
      <input type="password" name="old_pass" placeholder="masukkan kata sandi lama " class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="new_pass" placeholder="masukkan kata sandi baru " class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="confirm_pass" placeholder="konfirmasi kata sandi baru" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="perbarui" name="submit" class="btn">
   </form>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>