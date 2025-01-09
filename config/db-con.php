<?php

$host = "localhost";
$username = "u215434580_siwd";
$password = "!Tested123";
$database = "u215434580_siwd";

$conn = mysqli_connect($host, $username, $password, $database);

if(!$conn)
{
    error_log("Connection failed: " . mysqli_connect_error());
    die("Connection failed. Please contact the administrator.");
}

?>
