<?php
include 'config.php';
session_start();

if(isset($_POST['submit'])){

   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));

   // ✅ Hardcoded Admin credentials
   $admin_email = 'immu@gmail.com';
   $admin_password = md5('immu123');

   // ✅ If admin tries to login
   if($email === $admin_email && $pass === $admin_password){
      $_SESSION['admin_name'] = 'Admin';
      $_SESSION['admin_email'] = $admin_email;
      $_SESSION['admin_id'] = 0; // No DB ID needed
      header('location:admin_page.php');
      exit();
   }

   // ✅ Regular user login via DB
   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('query failed');

   if(mysqli_num_rows($select_users) > 0){

      $row = mysqli_fetch_assoc($select_users);

      if($row['user_type'] == 'user'){
         $_SESSION['user_name'] = $row['name'];
         $_SESSION['user_email'] = $row['email'];
         $_SESSION['user_id'] = $row['id'];
         header('location:home.php');
         exit();
      } else {
         $message[] = 'Access denied!';
      }

   } else {
      $message[] = 'Incorrect email or password!';
   }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/styles.css">

   <style>
      .book-icon {
         position: fixed;
         bottom: 20px;
         right: 20px;
         font-size: 50px;
         color: #c2b7c3ff;
         animation: float 3s ease-in-out infinite;
         z-index: 999;
      }

      @keyframes float {
         0% { transform: translateY(0); }
         50% { transform: translateY(-15px); }
         100% { transform: translateY(0); }
      }

      .message {
         background-color: #f8d7da;
         color: #721c24;
         padding: 10px 20px;
         border: 1px solid #f5c6cb;
         border-radius: 5px;
         margin: 10px auto;
         width: fit-content;
         display: flex;
         align-items: center;
         gap: 10px;
      }

      .message i {
         cursor: pointer;
      }
   </style>
</head>
<body>

<?php
if(isset($message)){
   foreach($message as $msg){
      echo '
      <div class="message">
         <span>'.$msg.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>
   
<div class="form-container">

   <form action="" method="post">
      <h3>login now</h3>
      <input type="email" name="email" placeholder="enter your email" required class="box">
      <input type="password" name="password" placeholder="enter your password" required class="box">
      <input type="submit" name="submit" value="login now" class="btn">
      <p>don't have an account? <a href="register.php">register now</a></p>
   </form>

</div>

<!-- ✅ Book icon with float animation and link to homepage -->
<a href="home.php" class="book-icon">
   <i class="fas fa-book"></i>
</a>>

</body>
</html>
