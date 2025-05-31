<?php
session_start();
$message = $_SESSION['message'] ?? "You are not authorized to access this page.";
unset($_SESSION['message']); // Optional: clear message after showing
?>

<!DOCTYPE html>
<html>
<head>
    <title>Access Denied</title>
</head>
<body>
    <h1>Access Denied</h1>
    <p><?= htmlspecialchars($message) ?></p>
    <a href="login.php">Back to Login</a>
</body>
</html>
