<?php

// include('../functions/functions.php');

// if (!isset($_SESSION['user_id'])) {
    
//     header("Location: ../login.php");
//     exit();
// }

require_once('../functions/functions.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "Please log in to continue.";
    header("Location: ../login.php");
    exit();
}

// Redirect if not an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 3) {
    $_SESSION['message'] = "Access denied: Admins only.";
    header("Location: ../unauthorized.php");
    exit();
}

?>