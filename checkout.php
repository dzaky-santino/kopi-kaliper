<?php
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:beranda.php');
}

if(isset($_POST['submit'])){
   $nama = filter_var($_POST['nama'], FILTER_SANITIZE_STRING);
   $no_hp = filter_var($_POST['no_hp'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
   $metode = filter_var($_POST['metode'], FILTER_SANITIZE_STRING);
   $no_meja = filter_var($_POST['no_meja'], FILTER_SANITIZE_STRING);
   $total_produk = $_POST['total_produk'];
   $total_harga = $_POST['total_harga'];

   $check_cart = $conn->prepare("SELECT * FROM `keranjang` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if($check_cart->rowCount() > 0){
      if($no_meja == ''){
         $message[] = 'Silakan Tambahkan Nomor Meja Anda!';
      }else{
         $order_id = rand();
         $insert_order = $conn->prepare("INSERT INTO `pesanan`(user_id, nama, no_hp, email, metode, no_meja, total_produk, total_harga, waktu_pesan, status_pembayaran, bukti_bayar) VALUES(?,?,?,?,?,?,?,?,NOW(),'belum bayar','')");
         $insert_order->execute([$user_id, $nama, $no_hp, $email, $metode, $no_meja, $total_produk, $total_harga]);

         if ($metode == 'qris') {
            require_once 'midtrans-php-master/Midtrans.php';
            \Midtrans\Config::$serverKey = '#';
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;
        
            $params = array(
                'transaction_details' => array(
                    'order_id' => $order_id,
                    'gross_amount' => $total_harga,
                ),
                'item_details' => array(),
                'customer_details' => array(
                    'first_name' => $nama,
                    'email' => $email,
                    'phone' => $no_hp,
                ),
            );
            
            // Add each item details to the $params array
            foreach (json_decode($total_produk, true) as $item) {
               $params['item_details'][] = array(
                   'id' => $item['id'], // Add a unique identifier if available
                   'price' => $item['harga'],
                   'quantity' => $item['kuantitas'],
                   'name' => $item['nama']
               );
           }

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        window.snap.pay('$snapToken', {
                            onSuccess: function(result) {
                                window.location.href = 'pesanan.php?success=1';
                            },
                            onPending: function(result) {
                                window.location.href = 'pesanan.php?success=1';
                            },
                            onError: function(result) {
                                window.location.href = 'pesanan.php?error=1';
                            }
                        });
                    });
                  </script>";        
         } else {
             $delete_cart = $conn->prepare("DELETE FROM `keranjang` WHERE user_id = ?");
             $delete_cart->execute([$user_id]);

             $message[] = 'Pesanan Berhasil!';
             header('Location:pesanan.php');
             exit();
         }
      }
   } else {
      $message[] = 'Keranjang Anda Kosong!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <link rel="icon" href="kopi_kaliper/logo.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <script type="text/javascript"
       src="https://app.sandbox.midtrans.com/snap/snap.js"
       data-client-key="#"></script>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<div class="heading">
   <h3>checkout</h3>
   <p><a href="beranda.php">beranda</a> <span> / checkout</span></p>
</div>

<section class="checkout">
   <h1 class="title">ringkasan pesanan</h1>
   <form action="" method="post">
      <div class="cart-items">
         <h3>pesanan</h3>
         <?php
         $grand_total = 0;
         $cart_items = [];
         $select_cart = $conn->prepare("SELECT * FROM `keranjang` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
               $cart_items[] = $fetch_cart;
               $grand_total += ($fetch_cart['harga'] * $fetch_cart['kuantitas']);
         ?>
         <p><span class="name"><?= $fetch_cart['nama']; ?></span><span class="price">Rp.<?= $fetch_cart['harga']; ?> x <?= $fetch_cart['kuantitas']; ?></span></p>
         <?php
            }
         }else{
            echo '<p class="empty">Keranjang Anda Kosong!</p>';
         }
         ?>
         <p class="grand-total"><span class="name">Total Pesanan :</span><span class="price">Rp.<?= $grand_total; ?></span></p>
         <a href="keranjang.php" class="btn">lihat keranjang</a>
      </div>

      <input type="hidden" name="total_produk" value="<?= htmlspecialchars(json_encode($cart_items), ENT_QUOTES, 'UTF-8'); ?>">
      <input type="hidden" name="total_harga" value="<?= $grand_total; ?>">
      <input type="hidden" name="nama" value="<?= $fetch_profile['nama'] ?>">
      <input type="hidden" name="no_hp" value="<?= $fetch_profile['no_hp'] ?>">
      <input type="hidden" name="email" value="<?= $fetch_profile['email'] ?>">
      <input type="hidden" name="no_meja" value="<?= $fetch_profile['no_meja'] ?>">

      <div class="user-info">
         <h3>info anda</h3>
         <p><i class="fas fa-user"></i><span><?= $fetch_profile['nama'] ?></span></p>
         <p><i class="fas fa-phone"></i><span><?= $fetch_profile['no_hp'] ?></span></p>
         <p><i class="fas fa-envelope"></i><span><?= $fetch_profile['email'] ?></span></p>
         <a href="update_profile.php" class="btn">perbarui info</a>
         <h3>Nomor Meja</h3>
         <p><i class="fa-solid fa-barcode"></i><span><?php if($fetch_profile['no_meja'] == ''){echo 'tolong masukkan nomor meja anda';}else{echo $fetch_profile['no_meja'];} ?></span></p>
         <a href="update_meja.php" class="btn">perbarui nomor meja</a>
         <select name="metode" class="box" id="metodePembayaran" onchange="tampilkanQR()" required>
            <option value="" disabled selected>Metode Pembayaran --</option>
            <option value="kasir">Bayar Di Kasir</option>
            <option value="qris">Qris</option>
         </select>
         <input type="submit" value="selesaikan pembayaran" class="btn <?php if($fetch_profile['no_meja'] == ''){echo 'disabled';} ?>" style="width:100%; background:var(--red); color:var(--white);" name="submit">
      </div>
   </form>
</section>

<?php include 'components/footer.php'; ?>

<script>
function tampilkanQR() {
    var metodePembayaran = document.getElementById("metodePembayaran").value;
    var gambarQris = document.getElementById("gambarQris");

    if (metodePembayaran === "qris") {
        gambarQris.style.display = "block";
    } else {
        gambarQris.style.display = "none";
    }
}
</script>

<script src="js/script.js"></script>

</body>
</html>

