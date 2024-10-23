<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_product'])){

   $nama = $_POST['nama'];
   $nama = filter_var($nama);
   $harga = $_POST['harga'];
   $harga = filter_var($harga);
   $kategori = $_POST['kategori'];
   $kategori = filter_var($kategori);
   $deskripsi = $_POST['deskripsi'];
   $deskripsi = filter_var($deskripsi);

   $gambar = $_FILES['gambar']['name'];
   $gambar = filter_var($gambar);
   $ukuran_gambar = $_FILES['gambar']['size'];
   $gambar_sementara = $_FILES['gambar']['tmp_name'];
   $folder_gambar = '../uploaded_img/'.$gambar;

   $select_products = $conn->prepare("SELECT * FROM `produk` WHERE nama = ?");
   $select_products->execute([$nama]);

   if($select_products->rowCount() > 0){
      $message[] = 'nama produk sudah ada!';
   }else{
      if($ukuran_gambar > 1000000){
         $message[] = 'Ukuran Gambar Terlalu Besar! Maksimal 1MB';
      }else{
         move_uploaded_file($gambar_sementara, $folder_gambar);

         $insert_product = $conn->prepare("INSERT INTO `produk`(nama, kategori, harga, deskripsi, gambar) VALUES(?,?,?,?,?)");
         $insert_product->execute([$nama, $kategori, $harga, $deskripsi, $gambar]);

         $message[] = 'produk baru ditambahkan!';
      }
   }
}

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT * FROM `produk` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['gambar']);
   $delete_product = $conn->prepare("DELETE FROM `produk` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `keranjang` WHERE produk_id = ?");
   $delete_cart->execute([$delete_id]);
   header('location:produk.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>produk</title>

   <link rel="icon" href="../kopi_kaliper/logo.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php' ?>

<section class="add-products">
   <form action="" method="POST" enctype="multipart/form-data">
      <h3>tambah produk</h3>
      <input type="text" required placeholder="Masukkan Nama Produk" name="nama" maxlength="50" class="box">
      <input type="number" min="0" max="9999999999" required placeholder="Masukkan Harga Produk" name="harga" onkeypress="if(this.value.length == 10) return false;" class="box">
      <select name="kategori" class="box" required>
         <option value="" disabled selected>Pilih Kategori --</option>
         <option value="Coffee">Coffee</option>
         <option value="Non-Coffee">Non-Coffee</option>
         <option value="Burgers">Burgers</option>
         <option value="Starters">Starters</option>
      </select>
      <input type="text" name="deskripsi" class="box" required placeholder="Masukkan Deskripsi Produk" maxlength="100" >
      <input type="file" name="gambar" class="box" accept="image/jpg, image/jpeg, image/png" required>
      <input type="submit" value="tambah produk" name="add_product" class="btn">
   </form>
</section>

<section class="show-products" style="padding-top: 0;">
   <div class="box-container">
      <?php
         $show_products = $conn->prepare("SELECT * FROM `produk`");
         $show_products->execute();
         if($show_products->rowCount() > 0){
            while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
      ?>
      <div class="box">
         <img src="../uploaded_img/<?= $fetch_products['gambar']; ?>" alt="">
         <div class="flex">
            <div class="price"><span>Rp.</span><?= $fetch_products['harga']; ?><span>/-</span></div>
            <div class="category"><?= $fetch_products['kategori']; ?></div>
         </div>
         <div class="name"><?= $fetch_products['nama']; ?></div>
         <div class="deskripsi"><?= $fetch_products['deskripsi']; ?></div>
         <div class="flex-btn">
            <a href="update_produk.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Perbarui</a>
            <a href="produk.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Hapus Produk Ini?');">Hapus</a>
         </div>
      </div>
      <?php
            }
         }else{
            echo '<p class="empty">Tidak Ada Produk!</p>';
         }
      ?>
   </div>
</section>

<script src="../js/admin_script.js"></script>
</body>
</html>
