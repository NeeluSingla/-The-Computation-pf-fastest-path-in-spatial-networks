<?php
   include("C:\Users\Neelu\Desktop\soft\part2_php\public_html\config.php");
   session_start();
   
   $user_check = $_SESSION['login_user'];
   
   $ses_sql = mysqli_query($mysqli ,"select username from login_details where username = '$user_check' ");
   
   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   
   $login_session = $row['username'];
  
?>