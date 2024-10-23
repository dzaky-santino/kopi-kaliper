<?php

if(isset($_POST['add_to_cart'])){

   if($user_id == ''){
      header('location:login.php');
   }else{

      $produk_id = $_POST['produk_id'];
      $produk_id = filter_var($produk_id);
      $nama = $_POST['nama'];
      $nama = filter_var($nama);
      $harga = $_POST['harga'];
      $harga = filter_var($harga);
      $gambar = $_POST['gambar'];
      $gambar = filter_var($gambar);
      $kuantitas = $_POST['kuantitas'];
      $kuantitas = filter_var($kuantitas);

      $check_cart_numbers = $conn->prepare("SELECT * FROM `keranjang` WHERE nama = ? AND user_id = ?");
      $check_cart_numbers->execute([$nama, $user_id]);

      if($check_cart_numbers->rowCount() > 0){
         $message[] = 'Sudah Ditambahkan Ke Keranjang!';
      }else{
         $insert_cart = $conn->prepare("INSERT INTO `keranjang`(user_id, produk_id, nama, harga, kuantitas, gambar) VALUES(?,?,?,?,?,?)");
         $insert_cart->execute([$user_id, $produk_id, $nama, $harga, $kuantitas, $gambar]);
         $message[] = 'Ditambahkan Ke Keranjang!';
      }
   }
}
?>