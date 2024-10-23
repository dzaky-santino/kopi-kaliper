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

   $no_meja = $_POST['meja'];
   $no_meja = filter_var($no_meja);

   $update_address = $conn->prepare("UPDATE `users` set no_meja = ? WHERE id = ?");
   $update_address->execute([$no_meja, $user_id]);

   $message[] = 'Nomor Meja Diperbarui!';

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Perbarui Nomor Meja</title>

   <link rel="icon" href="kopi_kaliper/logo.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php' ?>

<section class="form-container">

   <form action="" method="post">
      <h3>Nomor Meja</h3>
      <input type="number" class="box" placeholder="Nomor Meja" required maxlength="3" name="meja">
      <input type="submit" value="Perbarui" name="submit" class="btn">
   </form>
</section>

<?php include 'components/footer.php' ?>

<script src="js/script.js"></script>

</body>
</html>