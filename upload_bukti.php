<?php
include 'components/connect.php';
session_start();

if(isset($_POST['upload'])){
   $order_id = $_POST['order_id'];
   $file_name = $_FILES['bukti_bayar']['name'];
   $file_tmp = $_FILES['bukti_bayar']['tmp_name'];
   $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
   
   // Daftar ekstensi yang diperbolehkan
   $allowed_ext = array('jpg', 'jpeg', 'png');

   // Periksa apakah ekstensi file valid
   if(in_array($file_ext, $allowed_ext)){
      $new_file_name = uniqid() . '.' . $file_ext;
      move_uploaded_file($file_tmp, 'uploads/' . $new_file_name);

      $update_proof = $conn->prepare("UPDATE pesanan SET bukti_bayar = ?, status_pembayaran = 'sudah bayar' WHERE id = ?");
      $update_proof->execute([$new_file_name, $order_id]);

      header('location:pesanan.php');
   } else {
      echo '<script>alert("File tidak valid. Harap unggah file dengan ekstensi jpg, jpeg, atau png."); window.location.href="pesanan.php";</script>';
   }
}
?>
