<?php

// $host = "localhost";
// $username = "u215434580_siwd";
// $password = "!LetMeIn2025";
// $database = "u215434580_siwddev";

// $conn = mysqli_connect($host, $username, $password, $database);

// if(!$conn)
// {
//     error_log("Connection failed: " . mysqli_connect_error());
//     die("Connection failed. Please contact the administrator.");
// }

$host = "localhost";
$username = "root";
$password = "root"; // this is MAMP's default
$database = "siwd_local"; // or whatever you named it

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    error_log("Connection failed: " . mysqli_connect_error());
    die("Connection failed. Please contact the administrator.");
}

?>
