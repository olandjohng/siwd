<?php
include('../config/db-con.php');
include('../functions/functions.php');

if(isset($_POST['client_id'])) {

    $client_id = $_POST['client_id'];
    $suspend_client_query = "UPDATE clients SET status = 0 WHERE client_id = ?";
    $suspend_client_stmt = $conn->prepare($suspend_client_query);
    $suspend_client_stmt->bind_param("i", $client_id);

    if($suspend_client_stmt->execute()) {
        echo '200';
    } else {
        echo '500';
    }
} else {
    echo '400';
}


?>