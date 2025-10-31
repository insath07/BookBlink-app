<?php
include 'config.php';
session_start();

if(isset($_POST['submit'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
   $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
   $user_type = 'user'; // force user type

   // ✅ Block admin email from registering
   $admin_email = 'admin@example.com';
   if($email === $admin_email){
      $message[] = 'This email is reserved for admin. Cannot register!';
   } else {
      $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query failed');

      if(mysqli_num_rows($select_users) > 0){
         $message[] = 'User already exists!';
      } else {
         if($pass != $cpass){
            $message[] = 'Confirm password not matched!';
         } else {
            mysqli_query($conn, "INSERT INTO `users`(name, email, password, user_type) VALUES('$name', '$email', '$cpass', '$user_type')") or die('query failed');
            $message[] = 'Registered successfully!';
            header('location:login.php');
            exit();
         }
      }
   }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>

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
         color: #0b0a0bff;
         animation: float 3s ease-in-out infinite;
         z-index: 999;
      }

      @keyframes float {
         0% { transform: translateY(0); }
         50% { transform: translateY(-15px); }
         100% { transform: translateY(0); }
      }

     
   </style>

</head>
<body>

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
   
<div class="form-container">

   <form action="" method="post">
      <h3>register now</h3>
      <input type="text" name="name" placeholder="enter your name" required class="box">
      <input type="email" name="email" placeholder="enter your email" required class="box">
      <input type="password" name="password" placeholder="enter your password" required class="box">
      <input type="password" name="cpassword" placeholder="confirm your password" required class="box">
      
      <input type="submit" name="submit" value="register now" class="btn">
      <p>already have an account? <a href="login.php">login now</a></p>
   </form>

</div>

<!-- ✅ Floating book icon -->
<div class="book-icon">
   <i class="fas fa-book"></i>
</div>

</body>
</html>
