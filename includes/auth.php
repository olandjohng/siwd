<?php
session_start();

function require_role($allowed_roles = []) {
    if (!isset($_SESSION['user_id'], $_SESSION['role'])) {
        header("Location: ../login.php");
        exit();
    }

    if (!in_array($_SESSION['role'], $allowed_roles)) {
        // Optionally set a message or log this
        header("Location: ../unauthorized.php"); // Show access denied
        exit();
    }
}
?>