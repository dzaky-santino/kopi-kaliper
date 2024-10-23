<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['update'])){

   $produk_id = $_POST['produk_id'];
   $produk_id = filter_var($produk_id);
   $nama = $_POST['nama'];
   $nama = filter_var($nama);
   $harga = $_POST['harga'];
   $harga = filter_var($harga);
   $kategori = $_POST['kategori'];
   $kategori = filter_var($kategori);
   $deskripsi = $_POST['deskripsi'];
   $deskripsi = filter_var($deskripsi);

   $update_product = $conn->prepare("UPDATE `produk` SET nama = ?, kategori = ?, harga = ?, deskripsi = ? WHERE id = ?");
   $update_product->execute([$nama, $kategori, $harga, $deskripsi, $produk_id]);

   $old_image = $_POST['old_image'];
   $gambar = $_FILES['gambar']['name'];
   $gambar = filter_var($gambar);
   $ukuran_gambar = $_FILES['gambar']['size'];
   $gambar_sementara = $_FILES['gambar']['tmp_name'];
   $folder_gambar = '../uploaded_img/'.$gambar;

   $update_successful = true;

   if(!empty($gambar)){
      if($ukuran_gambar > 1000000){
         $message[] = 'Ukuran Gambar Terlalu Besar! Maksimal 1MB';
         $update_successful = false;
      }else{
         if($gambar !== $old_image) {
            $update_gambar = $conn->prepare("UPDATE `produk` SET gambar = ? WHERE id = ?");
            $update_gambar->execute([$gambar, $produk_id]);
            move_uploaded_file($gambar_sementara, $folder_gambar);
            unlink('../uploaded_img/'.$old_image);
         } else {
            move_uploaded_file($gambar_sementara, $folder_gambar);
         }
      }
   }   

   if($update_successful){
      $update_product = $conn->prepare("UPDATE `produk` SET nama = ?, kategori = ?, harga = ?, deskripsi = ? WHERE id = ?");
      $update_product->execute([$nama, $kategori, $harga, $deskripsi, $produk_id]);
      $message[] = 'Produk Diperbarui!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Perbarui Produk</title>

   <link rel="icon" href="../kopi_kaliper/logo.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php' ?>

<section class="update-product">
   <h1 class="heading">perbarui produk</h1>

   <?php
      $update_id = $_GET['update'];
      $show_products = $conn->prepare("SELECT * FROM `produk` WHERE id = ?");
      $show_products->execute([$update_id]);
      if($show_products->rowCount() > 0){
         while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <form action="" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="produk_id" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="old_image" value="<?= $fetch_products['gambar']; ?>">
      <img src="../uploaded_img/<?= $fetch_products['gambar']; ?>" alt="">
      <span>Nama Produk</span>
      <input type="text" required placeholder="Masukkan Nama Produk" name="nama" maxlength="50" class="box" value="<?= $fetch_products['nama']; ?>">
      <span>Harga Produk</span>
      <input type="number" min="0" max="9999999999" required placeholder="Masukkan Harga Produk" name="harga" onkeypress="if(this.value.length == 10) return false;" class="box" value="<?= $fetch_products['harga']; ?>">
      <span>Kategori Produk</span>
      <select name="kategori" class="box" required>
         <option selected value="<?= $fetch_products['kategori']; ?>"><?= $fetch_products['kategori']; ?></option>
         <option value="Coffee">Coffee</option>
         <option value="Non-Coffee">Non-Coffee</option>
         <option value="Burgers">Burgers</option>
         <option value="Starters">Starters</option>
      </select>
      <span>Deskripsi Produk</span>
      <input type="text" name="deskripsi" class="box" required placeholder="Masukkan Deskripsi Produk" maxlength="100" value="<?= $fetch_products['deskripsi']; ?>" >
      <span>Foto Produk</span>
      <input type="file" name="gambar" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
      <div class="flex-btn">
         <input type="submit" value="perbarui" class="btn" name="update">
         <a href="produk.php" class="option-btn">Kembali</a>
      </div>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">Tidak Ada Produk!</p>';
      }
   ?>
</section>

<script src="../js/admin_script.js"></script>
</body>
</html>
