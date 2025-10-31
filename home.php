<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
$message = [];

if (isset($_POST['add_to_cart'])) {
   if ($user_id) {
      $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
      $product_price = floatval($_POST['product_price']);
      $product_image = mysqli_real_escape_string($conn, $_POST['product_image']);
      $product_quantity = intval($_POST['product_quantity']);

      $check_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('Query failed');

      if (mysqli_num_rows($check_cart) > 0) {
         $message[] = 'Already added to cart!';
      } else {
         mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('Query failed');
         $message[] = 'Product added to cart!';
      }
   } else {
      $message[] = 'Please login to add items to your cart.';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home - BookBlink</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<?php include 'header.php'; ?>

<!-- ✅ Alert messages -->
<?php
if (!empty($message)) {
   foreach ($message as $msg) {
      echo '<div class="message"><span>' . htmlspecialchars($msg) . '</span> <i class="fas fa-times" onclick="this.parentElement.remove();"></i></div>';
   }
}
?>

<!-- ✅ Hero Section -->
<section class="home">
   <div class="content">
      <h3>Hand Picked Books to Your Door.</h3>
      <p>“Trust woven in every page,<br>
      Stories lighting up your days,<br>
      Our books, your cozy space,<br>
      Friends for the heart’s embrace.”</p>
      <a href="about.php" class="white-btn">Discover More</a>
   </div>
</section>

<!-- ✅ Product Section -->
<section class="products">
   <h1 class="title">New Arrivel</h1>
   <div class="box-container">
      <?php  
         $select_products = mysqli_query($conn, "SELECT * FROM `products` LIMIT 6") or die('Query failed');
         if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
      ?>
      <form action="shop.php" method="post" class="box">
         <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
         <div class="name"><?php echo $fetch_products['name']; ?></div>
         <div class="price">Rs <?php echo $fetch_products['price']; ?>/-</div>
         <div class="availability">
            <?php if ($fetch_products['available'] > 0) { ?>
               <span style="color: green;">Available (<?php echo $fetch_products['available']; ?>)</span>
            <?php } else { ?>
               <span style="color: red;">Out of Stock</span>
            <?php } ?>
         </div>
         <input type="number" min="1" name="product_quantity" value="1" class="qty"
            max="<?php echo $fetch_products['available']; ?>" 
            <?php if($fetch_products['available'] == 0) echo 'disabled'; ?>>
         <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
         <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
         <input type="submit" value="Continue" class="btn"  
            <?php if($fetch_products['available'] == 0) echo 'disabled style="background:#aaa;cursor:not-allowed;"'; ?>>
      </form>
      <?php
            }
         } else {
            echo '<p class="empty">No products added yet!</p>';
         }
      ?>
   </div>

   <div class="load-more" style="margin-top: 2rem; text-align:center">
      <a href="shop.php" class="option-btn">Load More</a>
   </div>
</section>

<!-- ✅ About Section -->
<section class="about">
   <div class="flex">
      <div class="image">
         <img src="images/abc.jpg" alt="">
      </div>
      <div class="content">
         <h3>About Us</h3>
         <p>At BookBlink, we believe every book is a journey waiting to unfold.
         Carefully handpicked, our collection brings you stories that inspire, comfort, and delight.

         We’re more than just a bookstore — we’re a community of readers, dreamers, and lifelong learners.

         From our shelves straight to your door, we’re committed to making the magic of reading easy and accessible.

         Join us, and discover your next favorite story today.</p>
         <a href="about.php" class="btn">Read More</a>
      </div>
   </div>
</section>

<!-- ✅ Contact Prompt -->
<section class="home-contact">
   <div class="content">
      <h3>Have any questions?</h3>
      <p>“For any queries or clarifications, please do not hesitate to contact us.”</p>
      <a href="contact.php" class="white-btn">Contact Us</a>
   </div>
</section>

<?php include 'footer.php'; ?>

<!-- Custom JS -->
<script src="js/script.js"></script>

</body>
</html>
