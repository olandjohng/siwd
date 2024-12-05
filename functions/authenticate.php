<?php
session_start();

include('../config/db-con.php');

if(isset($_POST['login_btn']))
{
    // validate this input
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user['user_id'];

            if ($user['role'] == 2) { 
                header("Location: ../cashier/index.php");
            } else if ($user['role'] == 3) { 
                header("Location: ../admin/index.php");
            } else {
                header("Location: ../default/index.php");
            }
            exit();
        } else {
            $_SESSION['message'] = "Incorrect password. Please try again.";
            header("Location: ../login.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "User not found. Please try again.";
        header("Location: ../login.php");
        exit();
    }
} else {
   header("Location: ../login.php");
   exit();
}

?>
