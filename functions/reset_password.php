<?php
session_start();

include('../config/db-con.php');

if(isset($_POST['reset_password'])){
  
  $username = $_POST['username'];
  $password = $_POST['password'];
  $new_password = $_POST['new_password'];

  $stmt = mysqli_prepare($conn, "SELECT user_id as id, role, username, password FROM users WHERE username = ?");

  mysqli_stmt_bind_param($stmt, "s", $username);

  /* execute query */
  mysqli_stmt_execute($stmt);

  $query_run = mysqli_stmt_get_result($stmt);

  $result = mysqli_fetch_assoc($query_run);

  if(!$result) {
    $_SESSION['_flush']['message'] = "Invalid Credentials";
    header("Location: ../reset-password.php");
    exit();
  }
  
  if(!password_verify($password, $result['password'])) {
    $_SESSION['_flush']['message'] = "Invalid Credentials";
    header("Location: ../reset-password.php");
    exit();
  }
  
  $hash_new_password = password_hash($new_password, PASSWORD_DEFAULT);

  $update_prepare = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE user_id = ?");

  mysqli_stmt_bind_param($update_prepare, "si", $hash_new_password, $result['id']);
  mysqli_stmt_execute($update_prepare);
  
  $_SESSION['username'] = $result['username'];
  $_SESSION['user_id'] = $result['id'];
  
  unset($_SESSION['_flush']);
  
  switch($result['role']) {
    case 3 : 
      header("Location: ../admin/index.php");
      break;
    default : 
        header("Location: ../cashier/index.php");
      break;
  }
  exit();
}