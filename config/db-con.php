<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "siwd";

$conn = mysqli_connect($host, $username, $password, $database);

if(!$conn)
{
    error_log("Connection failed: " . mysqli_connect_error());
    die("Connection failed. Please contact the administrator.");
}

?>