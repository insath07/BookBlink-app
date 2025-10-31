<?php
include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
   header('location:login.php');
};

// Add product
if(isset($_POST['add_product'])){
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $price = $_POST['price'];
   $available = isset($_POST['available']) ? $_POST['available'] : 1;

   $image = $_FILES['image']['name'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_size = $_FILES['image']['size'];
   $image_folder = 'uploaded_img/'.$image;

   $select_product = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$name'") or die('query failed');

   if(mysqli_num_rows($select_product) > 0){
      $message[] = 'Product already exists!';
   } else {
      $insert = mysqli_query($conn, "INSERT INTO `products` (name, price, image, available) VALUES('$name', '$price', '$image', '$available')") or die('query failed');
      if($insert){
         if($image_size > 2000000){
            $message[] = 'Image is too large!';
         } else {
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'Product added successfully!';
         }
      } else {
         $message[] = 'Failed to add product!';
      }
   }
}

// Delete product
if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'");
   $fetch_image = mysqli_fetch_assoc($image_query);
   unlink('uploaded_img/'.$fetch_image['image']);
   mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_products.php');
}

// Update product
if(isset($_POST['update_product'])){
   $update_p_id = $_POST['update_p_id'];
   $update_name = $_POST['update_name'];
   $update_price = $_POST['update_price'];
   $update_available = isset($_POST['update_available']) ? $_POST['update_available'] : 1;

   mysqli_query($conn, "UPDATE `products` SET name='$update_name', price='$update_price', available='$update_available' WHERE id='$update_p_id'") or die('query failed');

   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_folder = 'uploaded_img/'.$update_image;
   $old_image = $_POST['update_old_image'];

   if(!empty($update_image)){
      if($update_image_size > 2000000){
         $message[] = 'Image size is too large!';
      } else {
         mysqli_query($conn, "UPDATE `products` SET image='$update_image' WHERE id='$update_p_id'");
         move_uploaded_file($update_image_tmp_name, $update_folder);
         unlink('uploaded_img/'.$old_image);
      }
   }

   header('location:admin_products.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Admin Products</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="css/admin_style.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="add-products">
   <h1 class="title">Shop Products</h1>
   <form action="" method="post" enctype="multipart/form-data">
      <h3>Add Product</h3>
      <input type="text" name="name" class="box" placeholder="Enter product name" required>
      <input type="number" min="0" name="price" class="box" placeholder="Enter product price" required>
      <input type="file" name="image" accept="image/*" class="box" required>
      <select name="available" class="box" required>
         <option value="1">Available</option>
         <option value="0">Unavailable</option>
      </select>
      <input type="submit" value="Add Product" name="add_product" class="btn">
   </form>
</section>

<section class="show-products">
   <div class="box-container">
      <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch = mysqli_fetch_assoc($select_products)){
      ?>
      <div class="box">
         <img src="uploaded_img/<?php echo $fetch['image']; ?>" alt="">
         <div class="name"><?php echo $fetch['name']; ?></div>
         <div class="price">$<?php echo $fetch['price']; ?></div>
         <div class="status">Status: <strong><?php echo $fetch['available'] ? 'Available' : 'Unavailable'; ?></strong></div>
         <a href="admin_products.php?update=<?php echo $fetch['id']; ?>" class="option-btn">Update</a>
         <a href="admin_products.php?delete=<?php echo $fetch['id']; ?>" onclick="return confirm('Delete this product?');" class="delete-btn">Delete</a>
      </div>
      <?php
         }
      } else {
         echo '<p class="empty">No products added yet!</p>';
      }
      ?>
   </div>
</section>

<section class="edit-product-form">
<?php
   if(isset($_GET['update'])){
      $update_id = $_GET['update'];
      $update_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'");
      if(mysqli_num_rows($update_query) > 0){
         $fetch_update = mysqli_fetch_assoc($update_query);
?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
      <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
      <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="">
      <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required>
      <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" class="box" required>
      <select name="update_available" class="box" required>
         <option value="1" <?php echo $fetch_update['available'] ? 'selected' : ''; ?>>Available</option>
         <option value="0" <?php echo !$fetch_update['available'] ? 'selected' : ''; ?>>Unavailable</option>
      </select>
      <input type="file" name="update_image" class="box" accept="image/*">
      <input type="submit" value="Update" name="update_product" class="btn">
      <input type="reset" value="Cancel" id="close-update" class="option-btn" onclick="window.location.href='admin_products.php'">
   </form>
<?php
      }
   } else {
      echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
   }
?>
</section>

<script src="js/admin_script.js"></script>
</body>
</html>
