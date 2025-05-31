<?php
require_once('../functions/functions.php');


if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "Please log in to continue.";
    header("Location: ../login.php");
    exit();
}


if (!isset($_SESSION['role']) || $_SESSION['role'] != 4) {
    $_SESSION['message'] = "Access denied: Billing department only.";
    header("Location: ../unauthorized.php");
    exit();
}
?>