<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">

   <div class="header-1">
   <div class="flex">
      <p> New <a href="login.php">login</a> | <a href="register.php">register</a> </p>

      <!-- Centered BookBlink Name -->
      <div class="header-center">
         <a href="home.php" class="bookblink-name">BookBlink</a>
      </div>

      <div class="share">
         <!-- Share Button -->
         <button class="share-btn" onclick="nativeShare()" title="Share this page">
            <i class="fas fa-share-alt"></i> 
         </button>

         <!-- Dark Mode Toggle Button -->
         <button id="darkModeToggle" class="dark-toggle" title="Toggle Dark Mode">
            <i class="fas fa-moon"></i>
         </button>
      </div>
   </div>
</div>


   <div class="header-2">
      <div class="flex">

        <div class="logo-box">
  <a href="home.php" class="logo-link">
    <img src="images/logo.png" alt="logo" class="logo-img">
  </a>
</div>



         <nav class="navbar">
            <a href="home.php"><i class="fas fa-home"></i> Home</a>
            <a href="about.php"><i class="fas fa-info-circle"></i> About</a>
            <a href="shop.php"><i class="fas fa-store"></i> Shop</a>
            <a href="contact.php"><i class="fas fa-envelope"></i> Contact</a>
            <a href="orders.php"><i class="fas fa-box"></i> Orders</a>
         </nav>

         <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="search_page.php" class="fas fa-search"></a>
            <div id="user-btn" class="fas fa-user"></div>
            <?php
               $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
               $cart_rows_number = mysqli_num_rows($select_cart_number); 
            ?>
            <a href="cart.php"> <i class="fas fa-shopping-cart"></i> <span>(<?php echo $cart_rows_number; ?>)</span> </a>
         </div>

         <div class="user-box">
            <?php if(isset($_SESSION['user_name']) && isset($_SESSION['user_email'])): ?>
               <p>username : <span><?php echo $_SESSION['user_name']; ?></span></p>
               <p>email : <span><?php echo $_SESSION['user_email']; ?></span></p>
               <a href="logout.php" class="delete-btn">Logout</a>
            <?php else: ?>
               <p>You are not logged in.</p>
               <a href="login.php" class="delete-btn">Login</a>
            <?php endif; ?>
         </div>

      </div>
   </div>
</header>

<!-- JavaScript for Share and Dark Mode -->
<script>
   function nativeShare() {
      if (navigator.share) {
         navigator.share({
            title: 'BookBlink',
            text: 'Check out this amazing book store!',
            url: window.location.href
         }).then(() => console.log('Thanks for sharing!'))
           .catch((err) => console.error('Error sharing:', err));
      } else {
         alert("Your device or browser doesn't support native sharing.");
      }
   }

   // Dark Mode Toggle Logic
   const toggle = document.getElementById('darkModeToggle');

   toggle.addEventListener('click', () => {
      document.body.classList.toggle('dark-mode');
      const isDark = document.body.classList.contains('dark-mode');
      localStorage.setItem('theme', isDark ? 'dark' : 'light');
      toggle.innerHTML = isDark 
         ? '<i class="fas fa-sun"></i>' 
         : '<i class="fas fa-moon"></i>';
   });

   window.addEventListener('DOMContentLoaded', () => {
      const savedTheme = localStorage.getItem('theme');
      if (savedTheme === 'dark') {
         document.body.classList.add('dark-mode');
         toggle.innerHTML = '<i class="fas fa-sun"></i>';
      } else {
         toggle.innerHTML = '<i class="fas fa-moon"></i>';
      }
   });
</script>
