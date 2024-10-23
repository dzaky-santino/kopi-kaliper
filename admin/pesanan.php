<?php
include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['update_payment'])){

   $order_id = $_POST['order_id'];
   $status_pembayaran = $_POST['status_pembayaran'];

   if ($status_pembayaran == 'belum bayar') {

      $select_proof = $conn->prepare("SELECT bukti_bayar FROM pesanan WHERE id = ?");
      $select_proof->execute([$order_id]);
      $fetch_proof = $select_proof->fetch(PDO::FETCH_ASSOC);

      if ($fetch_proof['bukti_bayar'] && file_exists('../uploads/' . $fetch_proof['bukti_bayar'])) {
         unlink('../uploads/' . $fetch_proof['bukti_bayar']);
      }

      $update_status = $conn->prepare("UPDATE `pesanan` SET status_pembayaran = ?, bukti_bayar = NULL WHERE id = ?");
      $update_status->execute([$status_pembayaran, $order_id]);
   } else {
 
      $update_status = $conn->prepare("UPDATE `pesanan` SET status_pembayaran = ? WHERE id = ?");
      $update_status->execute([$status_pembayaran, $order_id]);
   }

   $message[] = 'Status pembayaran diperbarui!';
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `pesanan` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   header('location:pesanan.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>pesanan</title>

   <link rel="icon" href="../kopi_kaliper/logo.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<section class="placed-orders">

   <h1 class="heading">Pesanan</h1>

   <div class="box-container">

   <?php
      $select_orders = $conn->prepare("SELECT * FROM `pesanan`");
      $select_orders->execute();
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
            $total_produk = json_decode($fetch_orders['total_produk'], true);
   ?>
   <div class="box">
      <p> user id : <span><?= $fetch_orders['user_id']; ?></span> </p>
      <p> Dipesan Pada : <span><?= date('d-m-Y', strtotime($fetch_orders['waktu_pesan']));  ?></span> </p>
      <p> Nama : <span><?= $fetch_orders['nama']; ?></span> </p>
      <p> Email : <span><?= $fetch_orders['email']; ?></span> </p>
      <p> No. HP : <span><?= $fetch_orders['no_hp']; ?></span> </p>
      <p> Nomor Meja : <span><?= $fetch_orders['no_meja']; ?></span> </p>
      <p> Pesanan : <span>
         <?php
         foreach ($total_produk as $item) {
            echo $item['nama'] . " (" . $item['harga'] . " x " . $item['kuantitas'] . ") - ";
         }
         ?>
      </span> </p>
      <p> Total Harga : <span>Rp.<?= $fetch_orders['total_harga']; ?>/-</span> </p>
      <p> Metode Pembayaran : <span><?= $fetch_orders['metode']; ?></span> </p>
      <?php if ($fetch_orders['bukti_bayar']) { ?>
      <p>Bukti Pembayaran: <a href="../uploads/<?= $fetch_orders['bukti_bayar']; ?>" target="_blank">Lihat Bukti</a></p>
      <?php } ?>
      <form action="" method="POST">
         <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
         <select name="status_pembayaran" class="drop-down">
            <option value="" selected disabled><?= $fetch_orders['status_pembayaran']; ?></option>
            <option value="belum bayar">belum bayar</option>
            <option value="sudah bayar">sudah bayar</option>
         </select>
         <div class="flex-btn">
            <input type="submit" value="perbarui" class="btn" name="update_payment">
            <a href="pesanan.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('Hapus Pesanan Ini?');">Hapus</a>
         </div>
      </form>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">Belum Ada Pesanan!</p>';
      }
   ?>
   </div>
</section>

<script src="../js/admin_script.js"></script>

</body>
</html>
